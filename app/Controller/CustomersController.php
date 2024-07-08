<?php
App::uses('AppController', 'Controller');

class CustomersController extends AppController {
    public $uses = array('Customer', 'Submission', 'Plan', 'ElectricityRate', 'GasRate', 'Tariff', 'SolarRebateScheme', 'Product', 'Pdf', 'LeadType', 'StreetType', 'Sale', 'LeadAgent', 'MoveInInfo', 'OriginLpg', 'Option', 'Lead', 'Dncr', 'LeadHistory', 'BlueNrgRate', 'BroadbandLog');

    public function beforeFilter() {

        parent::beforeFilter();

        if (!in_array($this->request->clientIp(), unserialize(STAFF_IPS))) {
            //$this->redirect('https://www.google.com/');
        }

        $this->Auth->allow();

        $this->layout = 'customers';

        // leads360 URLs
        $leads360_url_1 = $this->Option->find('first', array(
            'conditions' => array(
                'Option.option_name' => 'leads360_url_1',
            ),
        ));
        $this->leads360_url_1 = $leads360_url_1['Option']['option_value'];

        $leads360_url_2 = $this->Option->find('first', array(
            'conditions' => array(
                'Option.option_name' => 'leads360_url_2',
            ),
        ));
        $this->leads360_url_2 = $leads360_url_2['Option']['option_value'];
    }

    public function admin_signup($id) {
        if (!$this->Customer->exists($id)) {
            //$this->redirect( 'https://' . WEBSITE_MAIN_DOMAIN_NAME );
        }
        $customer = $this->Customer->findById($id);
        if (empty($customer)) {
            //$this->redirect( 'https://' . WEBSITE_MAIN_DOMAIN_NAME );
        }
        $user = unserialize($customer['Customer']['data']);
        $plan = unserialize($customer['Customer']['plan_data']);
        $elec_rate = ($customer['Customer']['elec_rate_data']) ? unserialize($customer['Customer']['elec_rate_data']) : '';
        $gas_rate = ($customer['Customer']['gas_rate_data']) ? unserialize($customer['Customer']['gas_rate_data']) : '';

        $lead_id = '';
        if ($customer['Customer']['leadid']) {
            if (strpos($customer['Customer']['leadid'], '-') === false) {
                $lead_id = $customer['Customer']['leadid'];
            }
        }

        $elec_product = array();
        $gas_product = array();
        if (in_array($plan['Plan']['package'], array('Dual', 'Elec'))) {
            $elec_product = $this->Product->find('all', array(
                'conditions' => array(
                    'Product.fuel' => 'Elec',
                    'Product.state' => $plan['Plan']['state'],
                    'Product.retailer' => $plan['Plan']['retailer'],
                    'Product.res_sme' => $plan['Plan']['res_sme'],
                    'Product.product_name' => $plan['Plan']['product_name']
                )
            ));
        }
        if (in_array($plan['Plan']['package'], array('Dual', 'Gas'))) {
            $gas_product = $this->Product->find('all', array(
                'conditions' => array(
                    'Product.fuel' => 'Gas',
                    'Product.state' => $plan['Plan']['state'],
                    'Product.retailer' => $plan['Plan']['retailer'],
                    'Product.res_sme' => $plan['Plan']['res_sme'],
                    'Product.product_name' => $plan['Plan']['product_name']
                )
            ));
        }
        $pdfs = $this->Pdf->find('all', array(
            'conditions' => array(
                'Pdf.state' => $plan['Plan']['state'],
                'Pdf.retailer' => $plan['Plan']['retailer'],
                'Pdf.res_sme' => $plan['Plan']['res_sme'],
            )
        ));
        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data;
            $is_telco = 0;
            if (isset($data['Final']['telco_lead']) && $data['Final']['telco_lead']) {
                $is_telco = 1;
            }

            $this->Customer->create();
            $this->Customer->save(array('Customer' => array(
                'id' => $id,
                'is_telco' => $is_telco,
                'signup_data' => serialize($data),
            )), true, array('is_telco', 'signup_data'));

            if ($plan['Plan']['retailer'] == 'Powerdirect and AGL') {
                $user['step1']['plan_type'] = $plan['Plan']['package'] = 'Elec';
                $plan['Plan']['retailer'] = 'Powerdirect';
                $pd_velocify_transactions = $this->velocify_transaction($user, $plan, $elec_rate, '', $data);
                $pd_lead_ids = $pd_velocify_transactions['lead_ids'];
                $pd_cb_lead_ids = $pd_velocify_transactions['cb_lead_ids'];
                $user['step1']['plan_type'] = $plan['Plan']['package'] = 'Gas';
                $plan['Plan']['retailer'] = 'AGL';
                $data['Plan']['lead_id'] = '';
                $agl_velocify_transactions = $this->velocify_transaction($user, $plan, '', $gas_rate, $data);
                $agl_lead_ids = $agl_velocify_transactions['lead_ids'];
                $agl_cb_lead_ids = $agl_velocify_transactions['cb_lead_ids'];
                $lead_ids = array_merge($pd_lead_ids, $agl_lead_ids);
                $cb_lead_ids = array_merge($pd_cb_lead_ids, $agl_cb_lead_ids);
            } else {
                $velocify_transactions = $this->velocify_transaction($user, $plan, $elec_rate, $gas_rate, $data);
                $lead_ids = $velocify_transactions['lead_ids'];
                $cb_lead_ids = $velocify_transactions['cb_lead_ids'];
            }

            $this->Customer->create();
            $this->Customer->save(array('Customer' => array(
                'id' => $id,
                'leadid' => ($lead_ids) ? implode('-', $lead_ids) : '',
            )), true, array('leadid'));

            $body = array(
                'lead_id' => ($lead_ids) ? implode('-', $lead_ids) : '',
                'cb_lead_id' => ($cb_lead_ids) ? implode('-', $cb_lead_ids) : '',
                'lead_datetime' => date('YmdHis'),
            );

            return new CakeResponse(array(
                'body' => json_encode($body),
                'type' => 'text',
                'status' => '201'
            ));
        }

        $this->set(compact('id', 'lead_id', 'user', 'plan', 'elec_rate', 'gas_rate', 'elec_product', 'gas_product', 'pdfs'));
    }

    public function admin_signup_telco($id) {
        if (!$this->Customer->exists($id)) {
            $this->redirect( 'https://' . WEBSITE_MAIN_DOMAIN_NAME );
        }
        $customer = $this->Customer->findById($id);
        if (empty($customer)) {
            $this->redirect( 'https://' . WEBSITE_MAIN_DOMAIN_NAME );
        }
        $user = unserialize($customer['Customer']['data']);
        $plan = unserialize($customer['Customer']['plan_data']);

        $lead_id = '';
        $referrer_lead_id = '';
        if ($customer['Customer']['leadid']) {
            if (strpos($customer['Customer']['leadid'], '-') === false) {
                if ($customer['Customer']['is_telco']) {
                    $lead_id = $customer['Customer']['leadid'];
                } else {
                    $referrer_lead_id = $customer['Customer']['leadid'];
                }
            }
        }

        $pdfs = $this->Pdf->find('all', array(
            'conditions' => array(
                'Pdf.state' => $plan['Plan']['state'],
                'Pdf.retailer' => $plan['Plan']['retailer'],
                'Pdf.res_sme' => $plan['Plan']['res_sme'],
            )
        ));
        if ($this->request->is('post') || $this->request->is('put')) {

            $data = $this->request->data;

            $submission = array();
            $submission['submitted']['FuelType'] = $data['Plan']['FuelType'];
            $submission['submitted']['MoveinOrTransfer'] = $data['Plan']['looking_for'];
            $submission['BusOrResidential'] = $data['Plan']['res_sme'];
            $submission['submitted']['SaleType'] = ($data['Plan']['res_sme'] == 'Business') ? 'BUS' : 'RES';
            $submission['submitted']['FlyBuysNumber'] = '0';
            $submission['submitted']['FlyBuysConsent'] = 'No';
            $submission['submitted']['FlyBuysPoints'] = '0';
            $submission['submitted']['AEORegistrationConsent'] = 'No';
            $submission['submitted']['GreenEnergy'] = '0';
            $submission['submitted']['LumoPackage'] = 'Lumo Market Offer';
            $submission['submitted']['BillingType'] = 'Quarterly';
            $submission['submitted']['LEDTransferYesNo'] = 'NO';
            $submission['submitted']['LEDRepresentative'] = 'NA';
            if ($plan['Plan']['retailer'] == 'Momentum') {
                $submission['submitted']['AnyHazardsAccessingMeter'] = '0';
            } else {
                $submission['submitted']['AnyHazardsAccessingMeter'] = 'No';
            }
            $submission['submitted']['NMIAcqRet'] = 'NA';
            $submission['submitted']['NMI'] = '0';
            $submission['submitted']['MIRN AcqRet'] = 'NA';
            $submission['submitted']['MIRNNumber'] = '0';

            // Contact
            if (isset($data['Contact'])) {
                $submission['submitted']['title'] = $data['Contact']['title'];
                $submission['submitted']['FirstName'] = ucwords(strtolower($data['Contact']['first_name']));
                $submission['submitted']['surname'] = ucwords(strtolower($data['Contact']['last_name']));
                $submission['submitted']['DateOfBirthDate'] = '';
                if ($data['Contact']['dateofbirth']) {
                    $data['Contact']['dateofbirth'] = str_replace('/', '-', $data['Contact']['dateofbirth']);
                    $submission['submitted']['DateOfBirthDate'] = date('m/d/Y', strtotime($data['Contact']['dateofbirth']));
                }
                $submission['submitted']['MobileNumber'] = ($data['Contact']['mobile']) ? $data['Contact']['mobile'] : '0';
                $submission['submitted']['HomePhone'] = ($data['Contact']['home_phone']) ? $data['Contact']['home_phone'] : '0';
                if ($data['Contact']['email'] && $data['Contact']['email'] != 'no@email.com.au') {
                    $submission['submitted']['eMail'] = $data['Contact']['email'];
                }
                if (isset($data['Contact']['company_position']) && $data['Contact']['company_position']) {
                    $submission['submitted']['CompanyPosition'] = ucwords(strtolower($data['Contact']['company_position']));;
                }
                if ($plan['Plan']['retailer'] == 'Momentum' && $user['step1']['customer_type'] == 'RES') {
                    $submission['submitted']['CompanyPosition'] = 'Authorised Contact';
                }
            }

            // Identification
            $submission['submitted']['CreditConsent'] = 'Yes';
            $submission['submitted']['DocumentType'] = '0';
            $submission['submitted']['DocumentIDNumber'] = '0';
            $submission['submitted']['DocumentExpiry'] = '1/1/1900';
            $submission['submitted']['DocumentExpiry1'] = '1/1/1900';
            $submission['submitted']['DLState'] = '0';
            $submission['submitted']['DocumentCountryofIssue'] = '0';
            $submission['submitted']['SecretQuestion'] = '0';
            if (isset($data['Identification'])) {
                if ($data['Identification']['document_id']) {
                    $submission['submitted']['DocumentType'] = $data['Identification']['document_type'];
                    $submission['submitted']['DocumentIDNumber'] = $data['Identification']['document_id'];
                    if ($data['Identification']['document_expiry']) {
                        $data['Identification']['document_expiry'] = str_replace('/', '-', $data['Identification']['document_expiry']);
                        $submission['submitted']['DocumentExpiry'] = $submission['submitted']['DocumentExpiry1'] = date('m/d/Y', strtotime($data['Identification']['document_expiry']));
                    }
                    $submission['submitted']['DLState'] = $data['Identification']['document_state'];
                    $submission['submitted']['DocumentCountryofIssue'] = $data['Identification']['document_country'];
                    if ($data['Identification']['driver_license_card_number']) {
                        $submission['submitted']['dlcardnumber'] = $data['Identification']['driver_license_card_number'];
                    }
                }
                if ($plan['Plan']['retailer'] == 'Alinta Energy') {
                    if (isset($data['Identification']['secret_question']) && $data['Identification']['secret_question']) {
                        $submission['submitted']['SecretQuestion'] = $data['Identification']['secret_question'];
                    }
                    if (isset($data['Identification']['secret_answer']) && $data['Identification']['secret_answer']) {
                        $submission['submitted']['SecretAnswer'] = $data['Identification']['secret_answer'];
                    }
                }
                if (isset($data['Identification']['document_medicare_colour']) && $data['Identification']['document_medicare_colour']) {
                    $submission['submitted']['SecretAnswer'] = $data['Identification']['document_medicare_colour'];
                }
            }

            if (isset($data['Concession'])) {
                $submission['submitted']['LifeSupportActive'] = (isset($data['Concession']['household_require']) && $data['Concession']['household_require'] == 1) ? 'Y' : 'N';
            }

            // Billing
            if (isset($data['Supply']['billing_address_is_different']) && $data['Supply']['billing_address_is_different'] == 1) {
                $submission['submitted']['BillingAddressDifferent'] = 'Y';
                if (isset($data['SupplySecondary'])) {
                    $submission['submitted']['Addresshasnostreetnumber_'] = ($data['SupplySecondary']['no_street_number'] > 0) ? 'Yes' : 'No';
                    $submission['submitted']['POBOX'] = $data['SupplySecondary']['po_box'];
                    $submission['submitted']['UnitBilling'] = $data['SupplySecondary']['unit'];
                    $submission['submitted']['UnitTypeBilling'] = $data['SupplySecondary']['unit_type'];
                    $submission['submitted']['LotBilling'] = $data['SupplySecondary']['lot'];
                    $submission['submitted']['FloorBilling'] = $data['SupplySecondary']['floor'];
                    $submission['submitted']['FloorTypeBilling'] = $data['SupplySecondary']['floor_type'];
                    $submission['submitted']['BuildingNameBilling'] = ucwords(strtolower($data['SupplySecondary']['building_name']));
                    $submission['submitted']['StreetNumberBilling'] = $data['SupplySecondary']['street_number'];
                    $submission['submitted']['StNoSuffixBilling'] = $data['SupplySecondary']['street_number_suffix'];
                    $submission['submitted']['StreetNameBilling'] = ucwords(strtolower($data['SupplySecondary']['street_name']));
                    $submission['submitted']['StNameSuffixBilling'] = $data['SupplySecondary']['street_name_suffix'];
                    $submission['submitted']['StreetTypeBilling'] = $data['SupplySecondary']['street_type'];
                    $submission['submitted']['SuburbBilling'] = ucwords(strtolower($data['SupplySecondary']['suburb']));
                    $submission['submitted']['PostcodeBilling'] = $data['SupplySecondary']['postcode'];
                    $submission['submitted']['StateBilling'] = $data['SupplySecondary']['state'];
                }
            } else {
                $submission['submitted']['BillingAddressDifferent'] = 'N';
                $submission['submitted']['Addresshasnostreetnumber_'] = ($data['Supply']['no_street_number'][0] > 0) ? 'Yes' : 'No';
                $submission['submitted']['POBOX'] = '';
                $submission['submitted']['UnitBilling'] = $data['Supply']['unit'][0];
                $submission['submitted']['UnitTypeBilling'] = $data['Supply']['unit_type'][0];
                $submission['submitted']['LotBilling'] = $data['Supply']['lot'][0];
                $submission['submitted']['FloorBilling'] = $data['Supply']['floor'][0];
                $submission['submitted']['FloorTypeBilling'] = $data['Supply']['floor_type'][0];
                $submission['submitted']['BuildingNameBilling'] = ucwords(strtolower($data['Supply']['building_name'][0]));
                $submission['submitted']['StreetNumberBilling'] = ($data['Supply']['street_number'][0]) ? $data['Supply']['street_number'][0] : '0';
                $submission['submitted']['StNoSuffixBilling'] = $data['Supply']['street_number_suffix'][0];
                $submission['submitted']['StreetNameBilling'] = ($data['Supply']['street_name'][0]) ? ucwords(strtolower($data['Supply']['street_name'][0])) : '0';
                $submission['submitted']['StNameSuffixBilling'] = $data['Supply']['street_name_suffix'][0];
                $submission['submitted']['StreetTypeBilling'] = ($data['Supply']['street_type'][0]) ? $data['Supply']['street_type'][0] : 'None';
                $submission['submitted']['SuburbBilling'] = ucwords(strtolower($data['Supply']['suburb'][0]));
                $submission['submitted']['PostcodeBilling'] = $data['Supply']['postcode'][0];
                $submission['submitted']['StateBilling'] = $data['Supply']['state'][0];
            }

            $submission['submitted']['eBill'] = 'Y';
            $submission['submitted']['ElectronicWelcomePack'] = 'Y';

            $submission['submitted']['ResidentialDuration'] = $data['Billing']['residential_duration'];
            $submission["submitted"]['referrer_lead_id'] = $data['Plan']['referrer_lead_id'];

            // Move In
            $submission['submitted']['ConnectionDate'] = $submission['submitted']['MoveInDate'] = '';
            if (isset($data['MoveInDetail'])) {
                if ($data['MoveInDetail']['date']) {
                    $data['MoveInDetail']['date'] = str_replace('/', '-', $data['MoveInDetail']['date']);
                    $submission['submitted']['ConnectionDate'] = $submission['submitted']['MoveinDate'] = date('m/d/Y', strtotime($data['MoveInDetail']['date']));
                }
            }

            // Telco
            if (isset($data['Telco'])) {
                $submission['submitted']['product'] = $data['Telco']['plan_type'];
                $submission['submitted']['nbn_speed'] = $data['Telco']['nbn_speed'];
                $submission['submitted']['cb_lead_type'] = $data['Telco']['price'];
                $submission['submitted']['ce_lead_type'] = $data['Telco']['bundle_discount'];
                $submission['submiited']['payment_type'] = $data['Telco']['payment_type'];
                $submission['submitted']['internet_use'] = $data['Telco']['pay_and_submit'];
                $submission['submitted']['isp_receipt_number'] = $data['Telco']['pre_auth_submit'];
                $submission['submitted']['referred_agent_name'] = $data['Telco']['na'];
                $submission['submitted']['isp'] = $data['Telco']['retailer'];
            }

            // Final
            if (isset($data['Final'])) {
                $submission['submitted']['SalesRepName'] = $data['Final']['sales_rep_name'];
                if ($data['Final']['sales_rep_email']) {
                    $submission['submitted']['sales_rep_if_applicable'] = $data['Final']['sales_rep_email'];
                } else {
                    $sale = $this->Sale->findByName($data['Final']['sales_rep_name']);
                    if ($sale) {
                        $submission['submitted']['sales_rep_if_applicable'] = $sale['Sale']['email'];
                    }
                }
                $submission['submitted']['SaleCompletionDate'] = date('m/d/Y');
                $submission['submitted']['SaleDateTime'] = date('m/d/Y h:i:s A');
                if ($plan['Plan']['retailer'] == 'Momentum') {
                    $submission['submitted']['MomentumFile'] = 9;
                }
                $submission['submitted']['VoiceVerificationNumber'] = $data['Final']['voice_verification_number'];

                $submission['submitted']['id'] = $data['Final']['id'];
            }

            $submission['submitted']['LeadType'] = 'Telco';

            //$submission['submitted']['status'] = 'URGENT: Please Select Commercial or Residential';
            $submission['submitted']['status'] = '(Sales Status) Sale Completed';
			if (in_array(strtolower($data['Contact']['first_name']), array('test')) || in_array($data['Final']['agent_id'], array('125', '191', '196')) || in_array($data['Contact']['mobile'], unserialize(BAN_PHONE_NUMBERS)) || in_array($data['Contact']['home_phone'], unserialize(BAN_PHONE_NUMBERS))) {
                $submission['submitted']['status'] = '*TestStatus';
                //$submission['submitted']['SalesRepName'] = 'Chelsea Eru';
                //$submission['submitted']['sales_rep_if_applicable'] = 'c.eru@electricitywizard.com.au';
            }
            // Sale Complete Time
            $submission['submitted']['sale_completion_time'] = date('g:i A');

            $lead_ids = array();
            // Supply
            if (isset($data['Supply'])) {
                // multiple properties
                $i = 0;
                $campaign_id = (isset($data['Final']['campaign_id']) && $data['Final']['campaign_id']) ? $data['Final']['campaign_id'] : 1;
                $campaign_name = (isset($data['Final']['campaign_name']) && $data['Final']['campaign_name']) ? $data['Final']['campaign_name'] : 'Phone';
                $first_campaign = (isset($data['Final']['first_campaign']) && $data['Final']['first_campaign']) ? $data['Final']['first_campaign'] : '';
                switch ($campaign_id) {
                    case '76':
                        if (!$first_campaign) {
                            $submission['submitted']['first_campaign_name'] = 'WTCM Business - Campaign';
                        }
                        break;
                    case '77':
                        if (!$first_campaign) {
                            $submission['submitted']['first_campaign_name'] = 'MIC - Phone';
                        }
                        break;
                    case '1':
                        if (!$first_campaign) {
                            $submission['submitted']['first_campaign_name'] = 'Phone';
                        }
                        break;
                    default:
                        if (!$first_campaign) {
                            $submission['submitted']['first_campaign_name'] = $campaign_name;
                        }
                        break;

                }
                foreach ($data['Supply']['state'] as $key => $value) {
                    $i++;
                    // Supply Address
                    $submission['submitted']['AddresshasnoStreetnumber'] = ($data['Supply']['no_street_number'][$key] > 0) ? 'Yes' : 'No';
                    $submission['submitted']['Unit'] = $data['Supply']['unit'][$key];
                    $submission['submitted']['UnitType'] = $data['Supply']['unit_type'][$key];
                    $submission['submitted']['Lot'] = $data['Supply']['lot'][$key];
                    $submission['submitted']['Floor'] = $data['Supply']['floor'][$key];
                    $submission['submitted']['FloorType'] = $data['Supply']['floor_type'][$key];
                    $submission['submitted']['BuildingNamesupply'] = ucwords(strtolower($data['Supply']['building_name'][$key]));
                    $submission['submitted']['StreetNumber'] = ($data['Supply']['street_number'][$key]) ? $data['Supply']['street_number'][$key] : '0';
                    $submission['submitted']['StreetNumberSuffix'] = $data['Supply']['street_number_suffix'][$key];
                    $submission['submitted']['StreetName'] = ($data['Supply']['street_name'][$key]) ? ucwords(strtolower($data['Supply']['street_name'][$key])) : '0';
                    $submission['submitted']['StreetNameSuffix'] = $data['Supply']['street_name_suffix'][$key];
                    $submission['submitted']['StreetTypey'] = ($data['Supply']['street_type'][$key]) ? $data['Supply']['street_type'][$key] : 'None';
                    $submission['submitted']['Suburb'] = ucwords(strtolower($data['Supply']['suburb'][$key]));
                    $submission['submitted']['Postcode'] = $data['Supply']['postcode'][$key];
                    $submission['submitted']['State'] = $data['Supply']['state'][$key];


                    //$submission['submitted']['MIRNAddressDifferent'] = 'N';
                    //$submission['submitted']['MSATSAddressDifferent'] = 'No';

                    // Create or Update?
                    if ($i == 1 && $data['Plan']['lead_id']) {
                        $lead_ids[] = trim($data['Plan']['lead_id']);
                        $this->update_lead($campaign_id, $data['Plan']['lead_id'], $submission);
                    } else {
                        $lead_ids[]= $this->create_lead($campaign_id, $submission);
                    }
                }

                $this->Customer->create();
                $this->Customer->save(array('Customer' => array(
                    'postcode' => $customer['Customer']['postcode'],
                    'state' => $customer['Customer']['state'],
                    'suburb' => $customer['Customer']['suburb'],
                    'data' => $customer['Customer']['data'],
                    'plan_data' => $customer['Customer']['plan_data'],
                    'signup_data' => serialize($data),
                    'is_telco' => 1,
                    'leadid' => ($lead_ids) ? implode('-', $lead_ids) : '',
                )));
            }

            return new CakeResponse(array(
                'body' => ($lead_ids) ? implode('-', $lead_ids) : '',
                'type' => 'text',
                'status' => '201'
            ));
        }

        $this->layout = 'customers_telco';

        $this->set(compact('id', 'lead_id', 'referrer_lead_id', 'user', 'plan', 'pdfs'));
    }

    private function velocify_transaction($user, $plan, $elec_rate, $gas_rate, $data) {
        $submission = array();
        $submission['submitted']['fueltype'] = $user['step1']['plan_type'];
        $submission['submitted']['BusinessResidential'] = ($user['step1']['customer_type'] == 'SME') ? 'Business' : 'Residential';
        $submission['submitted']['saletype'] = ($user['step1']['customer_type'] == 'SME') ? 'BUS' : 'RES';
        $submission['submitted']['FlyBuysNumber'] = '0';
        $submission['submitted']['FlyBuysConsent'] = 'No';
        $submission['submitted']['FlyBuysPoints'] = '0';
        $submission['submitted']['AEORegistrationConsent'] = 'No';
        $submission['submitted']['GreenEnergy'] = '0';
        $submission['submitted']['LumoPackage'] = 'Lumo Market Offer';
        $submission['submitted']['BillingType'] = 'Quarterly';
        $submission['submitted']['LEDTransferYesNo'] = 'NO';
        $submission['submitted']['LEDRepresentative'] = 'NA';
        if ($plan['Plan']['retailer'] == 'Momentum') {
            $submission['submitted']['AnyHazardsAccessingMeter'] = '0';
        } else {
            $submission['submitted']['AnyHazardsAccessingMeter'] = 'No';
        }
        $submission['submitted']['NMIAcqRet'] = 'NA';
        $submission['submitted']['NMI'] = '0';
        $submission['submitted']['MIRN AcqRet'] = 'NA';
        $submission['submitted']['MIRNNumber'] = '0';

        $submission['submitted']['addressoverride'] = 'No';

        $submission['submitted']['plan_ranking'] = $plan['Plan']['ranking']; // ranking

        if (in_array($plan['Plan']['package'], array('Elec', 'Dual')) && $plan['Plan']['retailer'] == 'Momentum' && $plan['Plan']['state'] == 'Victoria' && strpos($plan['Plan']['product_name'], 'Bill Boss') !== false) {
            $submission['submitted']['BillingType'] = 'CPRCN_MTHLY';
        }

        if ($plan['Plan']['package'] == 'Dual' || $plan['Plan']['package'] == 'Elec') {
            if ($plan['Plan']['product_code_elec']) {
                $submission['submitted']['product_code_elec _new'] = $plan['Plan']['product_code_elec'];
            }
            if ($plan['Plan']['campaign_code_elec']) {
                $submission['submitted']['campaign_code_elec'] = $plan['Plan']['campaign_code_elec'];
            }
        }
        if ($plan['Plan']['package'] == 'Dual' || $plan['Plan']['package'] == 'Gas') {
            if ($plan['Plan']['product_code_gas']) {
                $submission['submitted']['product_code_gas _new'] = $plan['Plan']['product_code_gas'];
            }
            if ($plan['Plan']['campaign_code_gas']) {
                $submission['submitted']['campaign_code_gas'] = $plan['Plan']['campaign_code_gas'];
            }
        }

        if ($user['step1']['elec_recent_bill'] == 'No') {
            $current_elec_supplier = (isset($user['step1']['elec_supplier2'])) ? $user['step1']['elec_supplier2'] : 'NA';
        } else {
            $current_elec_supplier = (isset($user['step1']['elec_supplier'])) ? $user['step1']['elec_supplier'] : 'NA';
        }
        if ($user['step1']['gas_recent_bill'] == 'No') {
            $current_gas_supplier = (isset($user['step1']['gas_supplier2'])) ? $user['step1']['gas_supplier2'] : 'NA';
        } else {
            $current_gas_supplier = (isset($user['step1']['gas_supplier'])) ? $user['step1']['gas_supplier'] : 'NA';
        }
        if ($plan['Plan']['package'] == 'Dual') {
            $submission['submitted']['CurrentRetailerElec'] = $current_elec_supplier;
            $submission['submitted']['CurrentRetailerGas'] = $current_gas_supplier;
            $submission['submitted']['NewElectricityRetailer'] = $submission['submitted']['NewGasRetailer'] = $plan['Plan']['retailer'];
            $submission['submitted']['ElectricityDistributor'] = ($elec_rate) ? $elec_rate['ElectricityRate']['distributor'] : 'NA';
            $submission['submitted']['ElectricityProduct'] = (isset($data['Plan']['ElectricityProduct'])) ? $data['Plan']['ElectricityProduct'] : 'NA';
            $submission['submitted']['GasDistributor'] = ($gas_rate) ? $gas_rate['GasRate']['distributor'] : 'NA';
            $submission['submitted']['GasProduct'] = (isset($data['Plan']['GasProduct'])) ? $data['Plan']['GasProduct'] : 'NA';
            if (isset($data['Plan']['elec_consumption_data'])) {
                $submission['submitted']['ElectricityUsage'] = $submission['submitted']['consumption'] = $data['Plan']['elec_consumption_data'];
            }
            if (isset($data['Plan']['gas_consumption_data'])) {
                $submission['submitted']['GasAnnualConsumption'] = $data['Plan']['gas_consumption_data'];
            }
        }
        else if ($plan['Plan']['package'] == 'Elec') {
            $submission['submitted']['CurrentRetailerElec'] = $current_elec_supplier;
            $submission['submitted']['NewElectricityRetailer'] = $plan['Plan']['retailer'];
            $submission['submitted']['CurrentRetailerGas'] = 'NA';
            $submission['submitted']['NewGasRetailer'] = 'NA';
            $submission['submitted']['ElectricityDistributor'] = ($elec_rate) ? $elec_rate['ElectricityRate']['distributor'] : 'NA';
            $submission['submitted']['ElectricityProduct'] = (isset($data['Plan']['ElectricityProduct'])) ? $data['Plan']['ElectricityProduct'] : 'NA';
            $submission['submitted']['GasDistributor'] = 'NA';
            $submission['submitted']['GasProduct'] = 'NA';
            if (isset($data['Plan']['elec_consumption_data'])) {
                $submission['submitted']['ElectricityUsage'] = $submission['submitted']['consumption'] = $data['Plan']['elec_consumption_data'];
            }
        }
        else if ($plan['Plan']['package'] == 'Gas') {
            $submission['submitted']['CurrentRetailerGas'] = $current_gas_supplier;
            $submission['submitted']['NewGasRetailer'] = $plan['Plan']['retailer'];
            $submission['submitted']['CurrentRetailerElec'] = 'NA';
            $submission['submitted']['NewElectricityRetailer'] = 'NA';
            $submission['submitted']['ElectricityDistributor'] = 'NA';
            $submission['submitted']['ElectricityProduct'] = 'NA';
            $submission['submitted']['GasDistributor'] = ($gas_rate) ? $gas_rate['GasRate']['distributor'] : 'NA';
            $submission['submitted']['GasProduct'] = (isset($data['Plan']['GasProduct'])) ? $data['Plan']['GasProduct'] : 'NA';
            if (isset($data['Plan']['gas_consumption_data'])) {
                $submission['submitted']['GasAnnualConsumption'] = $data['Plan']['gas_consumption_data'];
            }
        }
        $submission['submitted']['MoveInTransfer'] = ($user['step1']['looking_for'] == 'Move Properties') ? 'Move In' : 'Transfer';
        if (($plan['Plan']['retailer'] == 'AGL' || $plan['Plan']['retailer'] == 'Powerdirect' || $plan['Plan']['retailer'] == 'Momentum') && $plan['Plan']['res_sme'] == 'RES') {
            $submission['submitted']['ABN'] = 0;
            $submission['submitted']['TradingName'] = 0;
            $submission['submitted']['LegalName'] = 0;
        }
        if ($plan['Plan']['retailer'] == 'Lumo Energy' && $submission['submitted']['MoveinOrTransfer'] == 'Move In') {
            $submission['submitted']['LumoEnergyCustomerAC'] = 0;
        }
        if ($plan['Plan']['retailer'] == 'Powershop') {
            if ($submission['submitted']['MoveinOrTransfer'] == 'Transfer') {
                $submission['submitted']['ElectricityOn'] = 'Yes';
            } else {
                $submission['submitted']['ElectricityOn'] = (isset($data['MoveInDetail']['power_currently_on']) && $data['MoveInDetail']['power_currently_on'] > 0) ? 'Yes' : 'No';
            }
        }
        if (isset($data['Plan']['nrma']) && $data['Plan']['nrma']) {
            $submission['submitted']['FlyBuysNumber'] = $data['Plan']['nrma'];
        }
        if ($user['step1']['tariff1']) {
            $tariffs = array();
            if ($user['step1']['tariff1']) {
                $tariff1 = explode('|', $user['step1']['tariff1']);
                $tariffs[] = $tariff1[0];
            }
            if ($user['step1']['tariff2']) {
                $tariff2 = explode('|', $user['step1']['tariff2']);
                $tariffs[] = $tariff2[0];
            }
            if ($user['step1']['tariff3']) {
                $tariff3 = explode('|', $user['step1']['tariff3']);
                $tariffs[] = $tariff3[0];
            }
            if ($user['step1']['tariff4']) {
                $tariff4 = explode('|', $user['step1']['tariff4']);
                $tariffs[] = $tariff4[0];
            }
            if (!empty($tariffs)) {
                $submission['submitted']['MSATSTariffCode'] = implode('/', $tariffs);
            }
        }
        $submission['submitted']['contractlength'] = $plan['Plan']['contract_length'];

        if (isset($plan['Plan']['ovo_direct_debit']) && $plan['Plan']['ovo_direct_debit']) {
            //$submission['submitted']['ce_lead_type'] = $plan['Plan']['ovo_direct_debit'];
            $submission['submitted']['CE_Lead_Type'] = '$'.$plan['Plan']['ovo_direct_debit'];
        }
        /*
        if (isset($data['Plan']['contract_length']) && $data['Plan']['contract_length']) {
            $submission['submitted']['contractlength'] = $data['Plan']['contract_length'];
        }
        */
        if (isset($data['Plan']['meter_type']) && $data['Plan']['meter_type']) {
            $submission['submitted']['MeterType'] = $data['Plan']['meter_type'];
        }
        if (isset($data['Plan']['property_type']) && $data['Plan']['property_type']) {
            $submission['submitted']['PropertyType'] = $data['Plan']['property_type'];
            $submission['PropertyType'] = $data['Plan']['property_type'];
        }
        if (isset($data['Plan']['solar_panels']) && $data['Plan']['solar_panels'] > -1) {
            $submission['submitted']['SolarPanels'] = ($data['Plan']['solar_panels'] > 0) ? 'Yes' : 'No';
        }
        if (isset($data['Plan']['smart_meter']) && $data['Plan']['smart_meter'] > -1) {
            $submission['submitted']['POC'] = ($data['Plan']['smart_meter'] > 0) ? 'Yes' : 'No';
        } elseif (($plan['Plan']['retailer'] == 'Lumo Energy' && $plan['Plan']['state'] == 'Victoria') || ($plan['Plan']['retailer'] == 'Lumo Energy' && $plan['Plan']['state'] == 'South Australia' && $plan['Plan']['package'] == 'Gas')) {
            $submission['submitted']['POC'] = 'No';
        }
        // Contact
        if (isset($data['Contact'])) {
            $submission['submitted']['title'] = $data['Contact']['title'];
            $submission['submitted']['FirstName'] = ucwords(strtolower($data['Contact']['first_name']));
            $submission['submitted']['LastName'] = ucwords(strtolower($data['Contact']['last_name']));
            $submission['submitted']['DateOfBirth'] = '';
            if ($data['Contact']['dateofbirth']) {
                $data['Contact']['dateofbirth'] = str_replace('/', '-', $data['Contact']['dateofbirth']);
                $submission['submitted']['DateOfBirth'] = date('m/d/Y', strtotime($data['Contact']['dateofbirth']));
            }
            $submission['submitted']['MobileNumber'] = ($data['Contact']['mobile']) ? $data['Contact']['mobile'] : '0';
            $submission['submitted']['HomePhone'] = ($data['Contact']['home_phone']) ? $data['Contact']['home_phone'] : '0';
            if ($data['Contact']['email'] && $data['Contact']['email'] != 'no@email.com.au') {
                $submission['submitted']['eMail'] = $data['Contact']['email'];
            }
            if (isset($data['Contact']['company_position']) && $data['Contact']['company_position']) {
                $submission['submitted']['CompanyPosition'] = ucwords(strtolower($data['Contact']['company_position']));;
            }
            if ($plan['Plan']['retailer'] == 'Momentum' && $user['step1']['customer_type'] == 'RES') {
                $submission['submitted']['CompanyPosition'] = 'Authorised Contact';
            }

            if (isset($data['Contact']['middle_name']) && $data['Contact']['middle_name']) {
                $submission['submitted']['MiddleInitial'] = $data['Contact']['middle_name'];
            }
        }
        // Secondary Contact
        if (isset($data['Secondary'])) {
            $submission['submitted']['SecondaryContactTitle'] = $data['Secondary']['title'];
            $submission['submitted']['SecondaryContactFirstName'] = ucwords(strtolower($data['Secondary']['first_name']));
            $submission['submitted']['SecondaryContactSurname'] = ucwords(strtolower($data['Secondary']['last_name']));
            $submission['submitted']['Secondary_Contact_DOB'] = '';
            if ($data['Secondary']['dateofbirth']) {
                $data['Secondary']['dateofbirth'] = str_replace('/', '-', $data['Secondary']['dateofbirth']);
                $submission['submitted']['Secondary_Contact_DOB'] = date('m/d/Y', strtotime($data['Secondary']['dateofbirth']));
            }
            $submission['submitted']['SecondaryMobileNumber'] = $data['Secondary']['mobile'];
            $submission['submitted']['SecondaryEmail'] = $data['Secondary']['email'];
        }
        // Identification
        $submission['submitted']['DocumentType'] = '0';
        $submission['submitted']['DocumentIDNumber'] = '0';
        $submission['submitted']['DocumentExpiry'] = '1/1/1900';
        $submission['submitted']['DocumentExpiry1'] = '1/1/1900';
        $submission['submitted']['DrivingLicenceState'] = '0';
        $submission['submitted']['DocumentCountryofIssue'] = '0';
        $submission['submitted']['SecretQuestion'] = '0';
        if (isset($data['Identification'])) {
            if ($data['Identification']['document_id']) {
                $submission['submitted']['DocumentType'] = $data['Identification']['document_type'];
                $submission['submitted']['DocumentIDNumber'] = $data['Identification']['document_id'];
                if ($data['Identification']['document_expiry']) {
                    $data['Identification']['document_expiry'] = str_replace('/', '-', $data['Identification']['document_expiry']);
                    $submission['submitted']['DocumentExpiry'] = $submission['submitted']['DocumentExpiry1'] = date('m/d/Y', strtotime($data['Identification']['document_expiry']));
                }
                $submission['submitted']['DrivingLicenceState'] = $data['Identification']['document_state'];
                $submission['submitted']['DocumentCountryofIssue'] = $data['Identification']['document_country'];
            }
            if ($plan['Plan']['retailer'] == 'Alinta Energy') {
                if (isset($data['Identification']['secret_question']) && $data['Identification']['secret_question']) {
                    $submission['submitted']['SecretQuestion'] = $data['Identification']['secret_question'];
                }
                if (isset($data['Identification']['secret_answer']) && $data['Identification']['secret_answer']) {
                    $submission['submitted']['SecretAnswer'] = $data['Identification']['secret_answer'];
                }
            }
            if (isset($data['Identification']['document_medicare_colour']) && $data['Identification']['document_medicare_colour']) {
                $submission['submitted']['SecretAnswer'] = $data['Identification']['document_medicare_colour'];
            }
        }
        // Concession
        if (isset($data['Concession'])) {
            $submission['submitted']['Concessions'] = (isset($data['Concession']['valid_concession_card']) && $data['Concession']['valid_concession_card'] == 1) ? 'Yes' : 'No';

            $submission['submitted']['LifeSupportActive'] = (isset($data['Concession']['household_require']) && $data['Concession']['household_require'] == 1) ? 'Y' : 'N';
            $submission['submitted']['ConcessionCardIssuer'] = $data['Concession']['card_issuer'];
            $submission['submitted']['ConcessionCardType'] = $data['Concession']['card_type'];

            if (isset($data['Concession']['title']) && $data['Concession']['title']) {
                $submission['submitted']['ConcessionTitle'] = $data['Concession']['title'];
            }
            $name_on_card = '';
            if (isset($data['Concession']['first_name']) && $data['Concession']['first_name']) {
                $submission['submitted']['ConcessionFirstName'] = $data['Concession']['first_name'];
                $name_on_card .= $data['Concession']['first_name'];
            }
            if (isset($data['Concession']['middle_name']) && $data['Concession']['middle_name']) {
                $submission['submitted']['ConcessionMiddleName'] = $data['Concession']['middle_name'];
                $name_on_card .= " ".$data['Concession']['middle_name'];
            }
            if (isset($data['Concession']['last_name']) && $data['Concession']['last_name']) {
                $submission['submitted']['ConcessionLastName'] = $data['Concession']['last_name'];
                $name_on_card .= " ".$data['Concession']['last_name'];
            }
            if ($name_on_card) {
                $submission['submitted']['NameofConcessionCard'] = $name_on_card;
            }

            $submission['submitted']['ConcessionCardNumber'] = $data['Concession']['card_number'];
            $submission['submitted']['ConcessionCardstartdate'] = '';
            if ($data['Concession']['card_start']) {
                $data['Concession']['card_start'] = str_replace('/', '-', $data['Concession']['card_start']);
                $submission['submitted']['ConcessionCardstartdate'] = date('m/d/Y', strtotime($data['Concession']['card_start']));
            }
            $submission['submitted']['ConcessionCardExpiry Date'] = '';
            if ($data['Concession']['card_expiry']) {
                $data['Concession']['card_expiry'] = str_replace('/', '-', $data['Concession']['card_expiry']);
                $submission['submitted']['ConcessionCardExpiry Date'] = date('m/d/Y', strtotime($data['Concession']['card_expiry']));
            }
            if (isset($data['Concession']['concession_ms']) && $data['Concession']['concession_ms']) {
                $submission['submitted']['ConcessionHasMS'] = ($data['Concession']['concession_ms'] == 1) ? 'Y' : 'N';
            }
            if (isset($data['Concession']['concession_group_home']) && $data['Concession']['concession_group_home']) {
                $submission['submitted']['ConcessionInGroupHome'] = ($data['Concession']['concession_group_home'] == 1) ? 'Y' : 'N';
            }
            if (in_array($plan['Plan']['retailer'], array('Momentum', 'Lumo Energy'))) {
                if (isset($data['Concession']['machine_type']) && $data['Concession']['machine_type']) {
                    $submission['submitted']['LifeSupportMachineType'] = $data['Concession']['machine_type'];
                }
            }
            if (in_array($plan['Plan']['retailer'], array('AGL', 'Powerdirect', 'Powerdirect and AGL'))) {
                if (isset($data['Concession']['machine_type2']) && $data['Concession']['machine_type2']) {
                    $submission['submitted']['LifeSupportMachineType'] = $data['Concession']['machine_type2'];
                }
            }
            if (in_array($plan['Plan']['retailer'], array('Alinta Energy'))) {
                if (isset($data['Concession']['machine_type3']) && $data['Concession']['machine_type3']) {
                    $submission['submitted']['LifeSupportMachineType'] = $data['Concession']['machine_type3'];
                }
                $submission['submitted']['medical_hc'] = 'N';
                if (isset($data['Concession']['medical_heating_cooling']) && $data['Concession']['medical_heating_cooling']) {
                    $submission['submitted']['medical_hc'] = ($data['Concession']['medical_heating_cooling'] == 1) ? 'Y' : 'N';
                }
            }
            if (in_array($plan['Plan']['retailer'], array('Lumo Energy', 'AGL', 'Powerdirect', 'Powerdirect and AGL', 'Alinta Energy'))) {
                if (isset($data['Concession']['life_support_user_title']) && $data['Concession']['life_support_user_title']) {
                    $submission['submitted']['LifeSupporttitle'] = $data['Concession']['life_support_user_title'];
                }
                $life_support_username = '';
                if (isset($data['Concession']['life_support_user_first_name']) && $data['Concession']['life_support_user_first_name']) {
                    $submission['submitted']['LifeSupportFirstName'] = $data['Concession']['life_support_user_first_name'];
                    $life_support_username .= $data['Concession']['life_support_user_first_name'];
                }
                if (isset($data['Concession']['life_support_user_middle_name']) && $data['Concession']['life_support_user_middle_name']) {
                    $submission['submitted']['LifeSupportMiddleName'] = $data['Concession']['life_support_user_middle_name'];
                    $life_support_username .= " ".$data['Concession']['life_support_user_middle_name'];
                }
                if (isset($data['Concession']['life_support_user_last_name']) && $data['Concession']['life_support_user_last_name']) {
                    $submission['submitted']['LifeSupportLastName'] = $data['Concession']['life_support_user_last_name'];
                    $life_support_username .= " ".$data['Concession']['life_support_user_last_name'];
                }
                if ($life_support_username) {
                    $submission['submitted']['LifeSupportUserName'] = $life_support_username;
                }
            }
            if (isset($data['Concession']['machine_type_other']) && $data['Concession']['machine_type_other']) {
                $submission['submitted']['LifeSupportMachineTypeother'] = $data['Concession']['machine_type_other'];
            }
            if (isset($data['Concession']['machine_run_by']) && $data['Concession']['machine_run_by']) {
                if ($data['Concession']['machine_run_by'] == 1) {
                    $submission['submitted']['life_support_fuel_type'] = 'Electricity';
                } elseif ($data['Concession']['machine_run_by'] == 2) {
                    $submission['submitted']['life_support_fuel_type'] = 'Gas';
                } elseif ($data['Concession']['machine_run_by'] == 3) {
                    $submission['submitted']['life_support_fuel_type'] = 'Dual';
                }
            }
        }
        if ($plan['Plan']['retailer'] == 'Sumo Power') {
            $submission['submitted']['ConcessionCardNumber'] = ($data['Concession']['sumo_power_understand_form'] == 1) ? 'YES' : 'NO';
        }

        if ($plan['Plan']['retailer'] == 'Blue NRG') {
            $submission['submitted']['extrafield_a'] = ($elec_rate) ? $elec_rate['BlueNrgRate']['tco'] : '';
            $submission['submitted']['extrafield_b'] = ($elec_rate) ? $elec_rate['BlueNrgRate']['rate_card_code'] : '';
            $submission['submitted']['CE_Lead_Type'] = '$'.$plan['Plan']['blue_nrg_direct_debit'];
        }

        // Business
        if (isset($data['Business'])) {
            $submission['submitted']['ABN'] = $data['Business']['abn'];
            $submission['submitted']['TradingName'] = ucwords(strtolower($data['Business']['trading_name']));
            $submission['submitted']['CompanyName'] = ucwords(strtolower($data['Business']['legal_name']));
            $submission['submitted']['BusinessType'] = $data['Business']['type'];
            $submission['submitted']['CompanyIndustry'] = $data['Business']['company_industry'];

            $submission['submitted']['extrafield9'] = $data['Business']['trustee_type'];
            $submission['submitted']['extrafield10'] = $data['Business']['trustee_company_name'];
            $submission['submitted']['trustee_acn'] = $data['Business']['trustee_acn'];
        }
        // Billing
        if (isset($data['Supply']['billing_address_is_different']) && $data['Supply']['billing_address_is_different'] == 1) {
            $submission['submitted']['BillingAddressisDifferent'] = 'Y';
            if (isset($data['SupplySecondary'])) {
                $submission['submitted']['AddresshasnoStreetnumberBilling'] = ($data['SupplySecondary']['no_street_number'] > 0) ? 'Yes' : 'No';
                $submission['submitted']['POBoxBilling'] = $data['SupplySecondary']['po_box'];
                $submission['submitted']['UnitBilling'] = $submission['submitted']['FlatorUnitBilling'] = $data['SupplySecondary']['unit'];
                $submission['submitted']['UnitTypeBilling'] = $data['SupplySecondary']['unit_type'];
                $submission['submitted']['LotBilling'] = $data['SupplySecondary']['lot'];
                $submission['submitted']['FloorBilling'] = $data['SupplySecondary']['floor'];
                $submission['submitted']['FloorTypeBilling'] = $data['SupplySecondary']['floor_type'];
                $submission['submitted']['BuildingNameBilling'] = ucwords(strtolower($data['SupplySecondary']['building_name']));
                $submission['submitted']['StreetNumberBilling'] = $data['SupplySecondary']['street_number'];
                $submission['submitted']['StreetNumberSuffixBilling'] = $data['SupplySecondary']['street_number_suffix'];
                $submission['submitted']['StreetNameBilling'] = ucwords(strtolower($data['SupplySecondary']['street_name']));
                $submission['submitted']['StreetNameSuffixBilling'] = $data['SupplySecondary']['street_name_suffix'];
                $submission['submitted']['StreetTypeBilling'] = $data['SupplySecondary']['street_type'];
                $submission['submitted']['SuburbBilling'] = ucwords(strtolower($data['SupplySecondary']['suburb']));
                $submission['submitted']['PostcodeBilling'] = $data['SupplySecondary']['postcode'];
                $submission['submitted']['StateBilling'] = $data['SupplySecondary']['state'];
            }
        } else {
            $submission['submitted']['BillingAddressDifferent'] = 'N';
            $submission['submitted']['AddresshasnoStreetnumberBilling'] = ($data['Supply']['no_street_number'][0] > 0) ? 'Yes' : 'No';
            $submission['submitted']['POBoxBilling'] = '';
            $submission['submitted']['UnitBilling'] = $submission['submitted']['FlatorUnitBilling'] = $data['Supply']['unit'][0];
            $submission['submitted']['UnitTypeBilling'] = $data['Supply']['unit_type'][0];
            $submission['submitted']['LotBilling'] = $data['Supply']['lot'][0];
            $submission['submitted']['FloorBilling'] = $data['Supply']['floor'][0];
            $submission['submitted']['FloorTypeBilling'] = $data['Supply']['floor_type'][0];
            $submission['submitted']['BuildingNameBilling'] = ucwords(strtolower($data['Supply']['building_name'][0]));
            $submission['submitted']['StreetNumberBilling'] = ($data['Supply']['street_number'][0]) ? $data['Supply']['street_number'][0] : '0';
            $submission['submitted']['StreetNumberSuffixBilling'] = $data['Supply']['street_number_suffix'][0];
            $submission['submitted']['StreetNameBilling'] = ($data['Supply']['street_name'][0]) ? ucwords(strtolower($data['Supply']['street_name'][0])) : '0';
            $submission['submitted']['StreetNameSuffixBilling'] = $data['Supply']['street_name_suffix'][0];
            $submission['submitted']['StreetTypeBilling'] = ($data['Supply']['street_type'][0]) ? $data['Supply']['street_type'][0] : 'None';
            $submission['submitted']['SuburbBilling'] = ucwords(strtolower($data['Supply']['suburb'][0]));
            $submission['submitted']['PostcodeBilling'] = $data['Supply']['postcode'][0];
            $submission['submitted']['StateBilling'] = $data['Supply']['state'][0];
        }
        // Billing Info
        if (isset($data['Billing'])) {
            $submission['submitted']['eBill'] = (isset($data['Billing']['e_billing']) && $data['Billing']['e_billing'] == 1) ? 'Y' : 'N';
            if ($plan['Plan']['retailer'] == 'AGL') {
                if (isset($data['Contact']['agl_email_bill']) && $data['Contact']['agl_email_bill'] == 1) {
                    $submission['submitted']['eBill'] = 'Y';
                }
                if (strpos(strtolower($plan['Plan']['product_name']), 'essentials') !== false) {
                    $submission['submitted']['eBill'] = 'Y';
                    $submission['submitted']['ElectronicWelcomePack'] = 'Y';
                }
            } elseif ($plan['Plan']['retailer'] == 'Origin Energy') {
                $submission['submitted']['ElectronicWelcomePack'] = 'N';
                $submission['submitted']['eBill'] = 'N';
                if (isset($data['Contact']['origin_email_bill']) && $data['Contact']['origin_email_bill'] == 1) {
                    $submission['submitted']['eBill'] = 'Y';
                    $submission['submitted']['ElectronicWelcomePack'] = 'Y';
                }
                if (strpos($plan['Plan']['product_name'], 'Origin Max Saver') !== false) {
                    $submission['submitted']['eBill'] = 'Y';
                    $submission['submitted']['ElectronicWelcomePack'] = 'Y';
                }
            } elseif ($plan['Plan']['retailer'] == 'Powerdirect') {
                if (isset($data['Contact']['powerdirect_email_bill']) && $data['Contact']['powerdirect_email_bill'] == 1) {
                    $submission['submitted']['eBill'] = 'Y';
                }
            } elseif ($plan['Plan']['retailer'] == 'Sumo Power') {
                if (isset($data['Billing']['how_receive_welcome_pack']) && $data['Billing']['how_receive_welcome_pack'] == 1) {
                    $submission['submitted']['eBill'] = 'Y';
                }
            } elseif ($plan['Plan']['retailer'] == 'Energy Australia') {
                $submission['submitted']['ElectronicWelcomePack'] = 'N';
                if (isset($data['Contact']['EA_email_bill']) && $data['Contact']['EA_email_bill'] == 1) {
                    $submission['submitted']['eBill'] = 'Y';
                    $submission['submitted']['ElectronicWelcomePack'] = 'Y';
                }
            } elseif ($plan['Plan']['retailer'] == 'ERM') {
                if (isset($data['Contact']['erm_email_bill']) && $data['Contact']['erm_email_bill'] == 1) {
                    $submission['submitted']['eBill'] = 'Y';
                }
            } elseif ($plan['Plan']['retailer'] == 'Lumo Energy') {
                if (isset($data['Contact']['lumo_email_invoices']) && $data['Contact']['lumo_email_invoices'] == 1) {
                    $submission['submitted']['eBill'] = 'Y';
                    $submission['submitted']['ElectronicWelcomePack'] = 'Y';
                }
                if (in_array($plan['Plan']['product_name'], array('Lumo Plus', 'Lumo Movers'))) {
                    $submission['submitted']['eBill'] = 'Y';
                    $submission['submitted']['ElectronicWelcomePack'] = 'Y';
                }
                //$submission['submitted']['ElectronicWelcomePack'] = ($data['Contact']['lumo_email_welcome_pack']) ? 'Y' : 'N';
            } else if ($plan['Plan']['retailer'] == 'Momentum') {
                if (isset($data['Billing']['e_billing_momentum']) && $data['Billing']['e_billing_momentum'] == 1) {
                    $submission['submitted']['eBill'] = 'Y';
                }
                if (strpos($plan['Plan']['product_name'], 'Bill Boss') !== false) {
                    $submission['submitted']['eBill'] = 'Y';
                }
                //$submission['submitted']['ElectronicMarketingInfo'] = 'ACT';
                $submission['submitted']['ElectronicMarketingInfo'] = 'Y';
            } else if ($plan['Plan']['retailer'] == 'Powershop') {
                $submission['submitted']['eBill'] = 'Y';
                $submission['submitted']['ElectronicWelcomePack'] = 'Y';
            } else if ($plan['Plan']['retailer'] == 'OVO Energy') {
                $submission['submitted']['eBill'] = 'Y';
                $submission['submitted']['ElectronicWelcomePack'] = 'Y';
            }

            if ($plan['Plan']['retailer'] == 'ActewAGL') {
                if ($data['Contact']['email'] == 'no@email.com.au' && !in_array($plan['Plan']['state'], array('Victoria', 'South Australia'))) {
                    $submission['submitted']['eBill'] = 'N';
                    $submission['submitted']['ElectronicWelcomePack'] = 'N';
                } elseif ($plan['Plan']['product_name'] == 'ACT Energy Rewards 25 (E-Comms Mandatory)') {
                    $submission['submitted']['eBill'] = 'Y';
                    $submission['submitted']['ElectronicWelcomePack'] = 'Y';
                } else {
                    $submission['submitted']['ElectronicWelcomePack'] = $submission['submitted']['eBill'] = (isset($data['Contact']['actewagl_email_bill']) && $data['Contact']['actewagl_email_bill'] == 1) ? 'Y' : 'N';
                }

                if (isset($data['Billing']['allow_actewagl_use'])) {
                    if ($data['Billing']['allow_actewagl_use'] == 1) {
                        $submission['submitted']['MarketingOptOut'] = 'No';
                    } elseif ($data['Billing']['allow_actewagl_use'] == 0) {
                        $submission['submitted']['MarketingOptOut'] = 'Yes';
                    }
                }
            }

            if (in_array($plan['Plan']['retailer'], array('AGL', 'Powerdirect', 'Powerdirect and AGL', 'OVO Energy'))) {
                if ($data['Contact']['email'] == 'no@email.com.au') {
                    $submission['submitted']['ElectronicWelcomePack'] = 'N';
                    $submission['submitted']['eBill'] = 'N';
                    $submission['submitted']['ElectronicMarketingInfo'] = 'N';
                } else {
                    $submission['submitted']['ElectronicWelcomePack'] = 'Y';
                    //$submission['submitted']['eBill'] = 'Y';
                    $submission['submitted']['ElectronicMarketingInfo'] = (isset($data['Billing']['electronic']) && $data['Billing']['electronic'] == 1) ? 'Y' : 'N';
                    if (isset($data['Contact']['receive_bills_by_post']) && $data['Contact']['receive_bills_by_post'] == 0) {
                        $submission['submitted']['eBill'] = 'Y';
                    }
                }
            }

            if (in_array($plan['Plan']['retailer'], array('AGL', 'Momentum', 'Alinta Energy'))) {
                $submission['submitted']['DirectDebitRequired'] = (isset($data['Billing']['direct_debit']) && $data['Billing']['direct_debit'] == 1) ? 'Y' : 'N';
            }
            if ($plan['Plan']['retailer'] == 'ERM') {
                $submission['submitted']['MarketingOptOut'] = (isset($data['Billing']['erm_receive_communications']) && $data['Billing']['erm_receive_communications'] == 1) ? 'No' : 'Yes';
            } elseif ($plan['Plan']['retailer'] == 'Origin Energy') {
                $submission['submitted']['MarketingOptOut'] = 'No';
            } elseif ($plan['Plan']['retailer'] == 'Lumo Energy') {
                $submission['submitted']['MarketingOptOut'] = (isset($data['Billing']['marketing_opt_out']) && $data['Billing']['marketing_opt_out'] == 1) ? 'Yes' : 'No';
            } elseif ($plan['Plan']['retailer'] == 'Alinta Energy') {
                $submission['submitted']['MarketingOptOut'] = 'No';
            } elseif ($plan['Plan']['retailer'] == 'Sumo Power') {
                $submission['submitted']['MarketingOptOut'] = (isset($data['Billing']['marketing_opt_out_sumo']) && $data['Billing']['marketing_opt_out_sumo'] == 1) ? 'Yes' : 'No';
            }
            //$submission['submitted']['EZPay'] = (isset($data['Billing']['ezpay']) && $data['Billing']['ezpay'] == 1) ? 'Yes' : 'No';
            if (isset($data['Billing']['understand_direct_debit'])) {
                //$submission['submitted']['DirectDebitRequired'] = ($data['Billing']['understand_direct_debit'] > 0) ? 'Yes' : 'No';
            }
            if (isset($data['Billing']['solar_panels_installed']) && $data['Billing']['solar_panels_installed'] > -1) {
                //$submission['submitted']['SolarPanels'] = ($data['Billing']['solar_panels_installed'] > 0) ? 'Yes' : 'No';
            }
            /*
            if (isset($data['Billing']['battery_storage_solution']) && $data['Billing']['battery_storage_solution'] > -1) {
                $submission['submitted']['BatteryStorageEOI'] = ($data['Billing']['battery_storage_solution']) ? 'Yes' : 'No';
            }
            if (isset($data['Billing']['battery_storage_solar_solution']) && $data['Billing']['battery_storage_solar_solution'] > -1) {
                $submission['submitted']['BatteryStorageSolarEOI'] = ($data['Billing']['battery_storage_solar_solution']) ? 'Yes' : 'No';
            }
            */
            if (isset($data['Billing']['contact_method']) && $data['Billing']['contact_method']) {
                $submission['submitted']['preferred_contact_method'] = $data['Billing']['contact_method'];
                if ($data['Billing']['contact_method'] == 'Email') {
                    $submission['submitted']['ElectronicWelcomePack'] = 'Y';
                } else {
                    $submission['submitted']['ElectronicWelcomePack'] = 'N';
                }
            }
            if (isset($data['Billing']['secondary_contact_method']) && $data['Billing']['secondary_contact_method']) {
                $submission['submitted']['preferred_contact_method_2nd contact'] = $data['Billing']['secondary_contact_method'];
            }
            if (isset($data['Billing']['secondary_contact_method']) && $data['Billing']['secondary_contact_method']) {
                $submission['submitted']['preferred_contact_method_2nd contact'] = $data['Billing']['secondary_contact_method'];
            }
            
            if (isset($data['Billing']['carbon_neutral_consent'])) {
                if ($data['Billing']['carbon_neutral_consent'] > 0) {
                    $submission['submitted']['carbon_neutral_consent'] = $plan['Plan']['package'];
                } else {
                    $submission['submitted']['carbon_neutral_consent'] = 'No';
                }
                
            }
        }
        // Move In
        $submission['submitted']['ConnectionDate'] = $submission['submitted']['MoveinDate'] = '0';
        $submission['submitted']['bpid_elec'] = '';
        if (isset($data['MoveInDetail'])) {
            if ($data['MoveInDetail']['date']) {
                $submission['submitted']['bpid_elec'] = $data['MoveInDetail']['date'];

                $data['MoveInDetail']['date'] = str_replace('/', '-', $data['MoveInDetail']['date']);
                $submission['submitted']['ConnectionDate'] = $submission['submitted']['MoveinDate'] = date('m/d/Y', strtotime($data['MoveInDetail']['date']));
            }
            $submission['submitted']['CustomerMoveinFeeAdvised'] = (isset($data['MoveInDetail']['fee_advised']) && $data['MoveInDetail']['fee_advised'] == 1) ? 'Y' : 'N';
            $submission['submitted']['VisualInspectionDetailsQLDRequired'] = ($plan['Plan']['state'] == 'Queensland') ? $data['MoveInDetail']['visual_inspection'] : 'NA';
            if (isset($data['MoveInDetail']['nmi_status']) && $data['MoveInDetail']['nmi_status'] == 'Active (A)') {
                $submission['submitted']['VisualInspectionDetailsQLDRequired'] = 'NA';
            }
            if (isset($data['MoveInDetail']['nmi_status']) && $data['MoveInDetail']['nmi_status']) {
                if ($data['MoveInDetail']['nmi_status'] == 'Active (A)') {
                    $submission['submitted']['Elec_On'] = 'Yes';
                } else {
                    $submission['submitted']['Elec_On'] = 'No';
                }
            }
            if (isset($data['MoveInDetail']['works_planned'])) {
                $submission['submitted']['Electrical_works'] = ($data['MoveInDetail']['works_planned'] == 1) ? 'Yes' : 'No';
            }
            $submission['submitted']['ElectricityMeterLocation'] = (isset($data['MoveInDetail']['electricity_meter']) && $data['MoveInDetail']['electricity_meter']) ? $data['MoveInDetail']['electricity_meter'] : 'NA';
            $submission['submitted']['GasMeterLocation'] = (isset($data['MoveInDetail']['gas_meter']) && $data['MoveInDetail']['gas_meter']) ? $data['MoveInDetail']['gas_meter'] : 'NA';
            if (in_array($plan['Plan']['package'], array('Dual', 'Elec'))) {
                $submission['submitted']['ElecConnectionFeeType'] = (isset($data['MoveInDetail']['elec_connection_fee_type']) && $data['MoveInDetail']['elec_connection_fee_type']) ? $data['MoveInDetail']['elec_connection_fee_type'] : '';
            }
            if (in_array($plan['Plan']['package'], array('Dual', 'Gas'))) {
                $submission['submitted']['GasConnectionFeeType'] = 'Normal';
            }
            $submission['submitted']['AdvisedMainSwitchMustBeTurnedOff'] = (isset($data['MoveInDetail']['understand_main_switch_off']) && $data['MoveInDetail']['understand_main_switch_off'] == 1) ? 'Yes' : 'No';
            //$submission['submitted']['MainSwitchOff'] = (isset($data['MoveInDetail']['main_switch_off']) && $data['MoveInDetail']['main_switch_off'] == 1) ? 'Y' : 'N';
            $submission['submitted']['MainSwitchOff'] = 'Y';
            $submission['submitted']['ConnectionDogPremises'] = (isset($data['MoveInDetail']['dog_premises']) && $data['MoveInDetail']['dog_premises'] == 1) ? 'Y' : 'N';
            $submission['submitted']['ConnectionMeterHazard'] = (isset($data['MoveInDetail']['meter_hazard']) && $data['MoveInDetail']['meter_hazard'] == 1) ? 'Y' : 'N';
            if ($plan['Plan']['retailer'] == 'Origin Energy') {
                //$submission['submitted']['Electricalworkscompletedsincedisconnection'] = (isset($data['MoveInDetail']['works_completed_origin']) && in_array($data['MoveInDetail']['works_completed_origin'], array(1, 2))) ? 'Y' : 'N';
            }
            if (isset($data['MoveInDetail']['meter_hazard']) && $data['MoveInDetail']['meter_hazard'] == 1) {
                if ($plan['Plan']['retailer'] == 'Momentum') {
                    $submission['submitted']['AnyHazardsAccessingMeter'] = (isset($data['MoveInDetail']['hazards_momentum']) && $data['MoveInDetail']['hazards_momentum']) ? $data['MoveInDetail']['hazards_momentum'] : '0';
                } else {
                    $submission['submitted']['AnyHazardsAccessingMeter'] = $data['MoveInDetail']['hazards'];
                }
            }
            if (isset($data['MoveInDetail']['meter_hazard'])) {
                if ($data['MoveInDetail']['meter_hazard'] == 2) {
                    $submission['submitted']['AnyHazardsAccessingMeter'] = 'Unsure';
                } elseif ($data['MoveInDetail']['meter_hazard'] == 1) {
                    $submission['submitted']['AnyHazardsAccessingMeter'] = 'Yes';
                } else {
                    $submission['submitted']['AnyHazardsAccessingMeter'] = 'No';
                }
            }
            if ($plan['Plan']['retailer'] == 'Powershop') {
                $submission['submitted']['AccessRequirements'] = 0;
            }
            if (isset($data['MoveInDetail']['special_details']) && $data['MoveInDetail']['special_details']) {
                if ($plan['Plan']['retailer'] == 'Powershop') {
                    $submission['submitted']['AccessRequirements'] =  ucwords(strtolower($data['MoveInDetail']['special_details']));
                } else {
                    $submission['submitted']['SpecialInstructions'] = ucwords(strtolower($data['MoveInDetail']['special_details']));
                }
            }
            if ($plan['Plan']['retailer'] == 'Origin Energy' && isset($data['MoveInDetail']['nmi_status']) && $data['MoveInDetail']['nmi_status'] == 'De-energised (D)' && isset($elec_rate['ElectricityRate']['distributor']) && in_array($elec_rate['ElectricityRate']['distributor'], array('Ausgrid'))) {
                $submission['submitted']['AccessRequirements'] = $submission['submitted']['SpecialInstructions'] = 'Customer reports no power';
            }
            if ($plan['Plan']['retailer'] == 'Sumo Power') {
                $submission['submitted']['PreviousStreetAddress'] = ucwords(strtolower($data['MoveInDetail']['previous_street']));
                $submission['submitted']['PreviousSuburb'] = ucwords(strtolower($data['MoveInDetail']['previous_suburb']));
                $submission['submitted']['PreviousState'] = $data['MoveInDetail']['previous_state'];
                $submission['submitted']['PreviousPostcode'] = $data['MoveInDetail']['previous_postcode'];
            }
        }
        // Final
        if (isset($data['Final'])) {
            $submission['submitted']['SalesRepName'] = $data['Final']['sales_rep_name'];
            if ($data['Final']['sales_rep_email']) {
                $submission['submitted']['sales_rep_if_applicable'] = $data['Final']['sales_rep_email'];
            } else {
                $sale = $this->Sale->findByName($data['Final']['sales_rep_name']);
                if ($sale) {
                    $submission['submitted']['sales_rep_if_applicable'] = $sale['Sale']['email'];
                }
            }
            //$submission['submitted']['ComputerNumber'] = $data['Final']['computer_number'];
            $submission['submitted']['SaleCompletionDate'] = date('m/d/Y');
            $submission['submitted']['SaleCompletionTime'] = date('m/d/Y h:i:s A');
            if ($plan['Plan']['retailer'] == 'Momentum') {
                $submission['submitted']['MomentumFile'] = 9;
            }
            $submission['submitted']['VoiceVerificationNumber'] = $data['Final']['eic'];
            if (isset($data['Final']['powershop_token'])) {
                $submission['submitted']['PowershopToken'] = $data['Final']['powershop_token'];
            }

            $submission['submitted']['Purchase_Reason'] = $data['Final']['purchase_reason'];

            if (isset($data['Final']['healthdeal'])) {
                $submission['submitted']['referralnametest4'] = ($data['Final']['healthdeal']) ? 'Y' : 'N' ;
            }

            // Hidden fields
            /*
            $submission['submitted']['CampaignSource'] = $data['Final']['campaign_source'];
            $submission['submitted']['content'] = $data['Final']['content'];
            $submission['submitted']['LeadAge'] = $data['Final']['lead_age'];
            $submission['submitted']['medium'] = $data['Final']['medium'];
            $submission['submitted']['Howtheyfoundus'] = '';
            if ($data['Final']['howtheyfoundus']) {
                $submission['submitted']['Howtheyfoundus'] = str_replace(array('{', '}'), '', $data['Final']['howtheyfoundus']);
            }
            $submission['submitted']['keyword'] = $data['Final']['keyword'];
            $submission['submitted']['url'] = '';
            if ($data['Final']['url']) {
                $submission['submitted']['url'] = str_replace(array('{', '}'), '', $data['Final']['url']);
            }
            $submission['submitted']['LeadCampaign'] = $data['Final']['lead_campaign'];
            $submission['submitted']['CampaignAdgroup'] = $data['Final']['campaign_ad_group'];
            */
            $submission['submitted']['id'] = $data['Final']['id'];
        }

        $lead_type = $this->LeadType->find('first', array(
            'conditions' => array(
                'LeadType.retailer' => $plan['Plan']['retailer'],
                'LeadType.res_sme' => $plan['Plan']['res_sme'],
                'LeadType.looking_for' => $user['step1']['looking_for']
            )
        ));
        if ($lead_type) {
            $submission['submitted']['LeadType'] = $lead_type['LeadType']['lead_type'];
            //$submission['submitted']['status'] = $lead_type['LeadType']['lead_status'];
        }
        if (isset($data['Final']['telco_lead']) && $data['Final']['telco_lead']) {
            $submission['submitted']['LeadType'] = 'Telco';
        }

        //$submission['submitted']['status'] = 'URGENT: Please Select Commercial or Residential';
        $submission['submitted']['status'] = '(Sales Status) Sale Completed';
        if (in_array(strtolower($data['Contact']['first_name']), array('test')) || in_array($data['Final']['agent_id'], array('125', '191', '196')) || in_array($data['Contact']['mobile'], unserialize(BAN_PHONE_NUMBERS)) || in_array($data['Contact']['home_phone'], unserialize(BAN_PHONE_NUMBERS))) {
            $submission['submitted']['status'] = '*TestStatus';
            //$submission['submitted']['SalesRepName'] = 'Chelsea Eru';
            //$submission['submitted']['sales_rep_if_applicable'] = 'c.eru@electricitywizard.com.au';
        }
        // Sale Complete Time
        $submission['submitted']['sale_completion_time'] = date('g:i A');

        if ($plan['Plan']['retailer'] == 'Origin Energy' && in_array($plan['Plan']['package'], array('Elec'))) {
            if (isset($data['Supply']['origin_lpg_property']) && $data['Supply']['origin_lpg_property'] == 1) {
                if (isset($data['Supply']['lpg_date']) && $data['Supply']['lpg_date']) {
                    $submission['submitted']['LPG'] = $data['Supply']['lpg_date'];
                }
            }
        }

        $lead_ids = array();
        $cb_lead_ids = array();
        // Supply
        if (isset($data['Supply'])) {
            // multiple properties
            $i = 0;
            $campaign_id = (isset($data['Final']['campaign_id']) && $data['Final']['campaign_id']) ? $data['Final']['campaign_id'] : 1;
            $campaign_name = (isset($data['Final']['campaign_name']) && $data['Final']['campaign_name']) ? $data['Final']['campaign_name'] : 'Phone';
            $first_campaign = (isset($data['Final']['first_campaign']) && $data['Final']['first_campaign']) ? $data['Final']['first_campaign'] : '';
            $vid = (isset($data['Final']['vid']) && $data['Final']['vid']) ? $data['Final']['vid'] : '';
            $call_medium = (isset($data['Final']['call_medium']) && $data['Final']['call_medium']) ? $data['Final']['call_medium'] : '';
            if ($vid) {
                $submission['submitted']['extrafield8'] = $vid;
            }
            if ($call_medium) {
                $submission['submitted']['call_medium'] = $call_medium;
            }
            switch ($campaign_id) {
                case '76':
                    if (!$first_campaign) {
                        $submission['submitted']['first_campaign_name'] = 'WTCM Business - Campaign';
                    }
                    break;
                case '77':
                    if (!$first_campaign) {
                        $submission['submitted']['first_campaign_name'] = 'MIC - Phone';
                    }
                    break;
                case '1':
                    if (!$first_campaign) {
                        $submission['submitted']['first_campaign_name'] = 'Phone';
                    }
                    break;
                default:
                    if (!$first_campaign) {
                        $submission['submitted']['first_campaign_name'] = $campaign_name;
                    }
                    break;

            }
            foreach ($data['Supply']['state'] as $key => $value) {
                $i++;
                if (in_array($plan['Plan']['package'], array('Dual', 'Elec'))) {
                    $submission['submitted']['NMI'] = (isset($data['Supply']['nmi'][$key]) && $data['Supply']['nmi'][$key]) ? $data['Supply']['nmi'][$key] : 0;
                }
                if (in_array($plan['Plan']['package'], array('Dual', 'Gas'))) {
                    $submission['submitted']['MIRNNumber'] = (isset($data['Supply']['mirn'][$key]) && $data['Supply']['mirn'][$key]) ? $data['Supply']['mirn'][$key] : 0;
                }
                // Supply Address
                $submission['submitted']['AddresshasnostreetnumberSupply'] = ($data['Supply']['no_street_number'][$key] > 0) ? 'Yes' : 'No';
                $submission['submitted']['UnitSupply'] = $data['Supply']['unit'][$key];
                $submission['submitted']['UnitTypeSupply'] = $data['Supply']['unit_type'][$key];
                $submission['submitted']['LotSupply'] = $data['Supply']['lot'][$key];
                $submission['submitted']['FloorSupply'] = $data['Supply']['floor'][$key];
                $submission['submitted']['FloorTypeSupply'] = $data['Supply']['floor_type'][$key];
                $submission['submitted']['BuildingName'] = ucwords(strtolower($data['Supply']['building_name'][$key]));
                $submission['submitted']['StreetNumberSupply'] = ($data['Supply']['street_number'][$key]) ? $data['Supply']['street_number'][$key] : '0';
                $submission['submitted']['StNoSuffixSupply'] = $data['Supply']['street_number_suffix'][$key];
                $submission['submitted']['StreetNameSupply'] = ($data['Supply']['street_name'][$key]) ? ucwords(strtolower($data['Supply']['street_name'][$key])) : '0';
                $submission['submitted']['StNameSuffixSupply'] = $data['Supply']['street_name_suffix'][$key];
                $submission['submitted']['StreetTypeSupply'] = ($data['Supply']['street_type'][$key]) ? $data['Supply']['street_type'][$key] : 'None';
                $submission['submitted']['SuburbSupply'] = ucwords(strtolower($data['Supply']['suburb'][$key]));
                $submission['submitted']['PostcodeSupply'] = $data['Supply']['postcode'][$key];
                $submission['submitted']['StateSupply'] = $data['Supply']['state'][$key];

                if (in_array($plan['Plan']['package'], array('Dual', 'Elec')) && isset($data['Supply']['nmi_acq_ret'][$key])) {
                    $submission['submitted']['NMIAcqRet'] = $data['Supply']['nmi_acq_ret'][$key];
                }
                if (in_array($plan['Plan']['package'], array('Dual', 'Gas')) && isset($data['Supply']['mirn_acq_ret'][$key])) {
                    $submission['submitted']['MIRN AcqRet'] = $data['Supply']['mirn_acq_ret'][$key];
                }

                if ($plan['Plan']['retailer'] == 'AGL' && isset($data['Supply']['msats_mirn_address'][$key])) {
                    $submission['submitted']['MSATSMIRNAddress'] = ucwords(strtolower($data['Supply']['msats_mirn_address'][$key]));
                }
                if (isset($data['Supply']['tenant_owner'][$key])) {
                    $submission['submitted']['TenantOwner'] = $data['Supply']['tenant_owner'][$key];
                }
                if ($plan['Plan']['retailer'] == 'AGL' || $plan['Plan']['retailer'] == 'Powerdirect') {
                    if (isset($data['Supply']['nmi_acq_ret'][$key]) && $data['Supply']['nmi_acq_ret'][$key] == 'Acquisition') {
                        if ($plan['Plan']['package'] == 'Dual') {
                            $submission['submitted']['AGLSaleType'] = 'ADF'; // Acquisition - Dual Fuel
                        } else if ($plan['Plan']['package'] == 'Elec') {
                            $submission['submitted']['AGLSaleType'] = 'AEO'; // Acquisition - Electricity Only
                        } else if ($plan['Plan']['package'] == 'Gas') {
                            $submission['submitted']['AGLSaleType'] = 'AGO'; // Acquisition - Gas Only
                        }
                    } else if (isset($data['Supply']['nmi_acq_ret'][$key]) && $data['Supply']['nmi_acq_ret'][$key] == 'Retention') {
                        if ($plan['Plan']['package'] == 'Dual') {
                            $submission['submitted']['AGLSaleType'] = 'RDF'; // Retention - Dual Fuel
                        } else if ($plan['Plan']['package'] == 'Elec') {
                            $submission['submitted']['AGLSaleType'] = 'REO'; // Retention - Electricity Only
                        } else if ($plan['Plan']['package'] == 'Gas') {
                            $submission['submitted']['AGLSaleType'] = 'RGO'; // Retention - Gas Only
                        }
                    } else if (isset($data['Supply']['mirn_acq_ret'][$key]) && $data['Supply']['mirn_acq_ret'][$key] == 'Acquisition') {
                        if ($plan['Plan']['package'] == 'Dual') {
                            $submission['submitted']['AGLSaleType'] = 'ADF'; // Acquisition - Dual Fuel
                        } else if ($plan['Plan']['package'] == 'Elec') {
                            $submission['submitted']['AGLSaleType'] = 'AEO'; // Acquisition - Electricity Only
                        } else if ($plan['Plan']['package'] == 'Gas') {
                            $submission['submitted']['AGLSaleType'] = 'AGO'; // Acquisition - Gas Only
                        }
                    } else if (isset($data['Supply']['mirn_acq_ret'][$key]) && $data['Supply']['mirn_acq_ret'][$key] == 'Retention') {
                        if ($plan['Plan']['package'] == 'Dual') {
                            $submission['submitted']['AGLSaleType'] = 'RDF'; // Retention - Dual Fuel
                        } else if ($plan['Plan']['package'] == 'Elec') {
                            $submission['submitted']['AGLSaleType'] = 'REO'; // Retention - Electricity Only
                        } else if ($plan['Plan']['package'] == 'Gas') {
                            $submission['submitted']['AGLSaleType'] = 'RGO'; // Retention - Gas Only
                        }
                    }
                    // ?
                    if (isset($data['Supply']['nmi_acq_ret'][$key]) && $data['Supply']['nmi_acq_ret'][$key] && isset($data['Supply']['mirn_acq_ret'][$key]) && $data['Supply']['mirn_acq_ret'][$key]) {
                        if ($data['Supply']['nmi_acq_ret'][$key] == 'Acquisition' && $data['Supply']['mirn_acq_ret'][$key] == 'Retention') {
                            $submission['submitted']['AGLSaleType'] = 'CSE';
                        }
                        if ($data['Supply']['nmi_acq_ret'][$key] == 'Retention' && $data['Supply']['mirn_acq_ret'][$key] == 'Acquisition') {
                            $submission['submitted']['AGLSaleType'] = 'CSG';
                        }
                    }
                }
                $submission['submitted']['MIRNAddressDifferent'] = 'N';
                $submission['submitted']['MSATSAddressDifferent'] = $submission['submitted']['AddressDifferent'] = 'No';
                $submission['submitted']['extrafield'] = $submission['submitted']['extrafield1'] = 'N/A';
                // MIRN address is different
                if (in_array($plan['Plan']['package'], array('Dual')) && isset($data['Supply']['mirn_is_different'][$key]) && $data['Supply']['mirn_is_different'][$key]) {
                    $submission['submitted']['MIRNAddressDifferent'] = 'Y';
                    $submission['submitted']['AddresshasnostreetnumberMIRN'] = ($data['Supply']['no_street_number_mirn'][$key] > 0) ? 'Yes' : 'No';
                    $submission['submitted']['UnitMIRN'] = $data['Supply']['unit_mirn'][$key];
                    $submission['submitted']['UnitTypeMIRN'] = $data['Supply']['unit_type_mirn'][$key];
                    $submission['submitted']['LotMIRN'] = $data['Supply']['lot_mirn'][$key];
                    $submission['submitted']['FloorMIRN'] = $data['Supply']['floor_mirn'][$key];
                    $submission['submitted']['FloorTypeMIRN'] = $data['Supply']['floor_type_mirn'][$key];
                    $submission['submitted']['BuildingNameMIRN'] = ucwords(strtolower($data['Supply']['building_name_mirn'][$key]));
                    $submission['submitted']['StreetNumberMIRN'] = ($data['Supply']['street_number_mirn'][$key]) ? $data['Supply']['street_number_mirn'][$key] : '0';
                    $submission['submitted']['StNoSuffixMIRN'] = $data['Supply']['street_number_suffix_mirn'][$key];
                    $submission['submitted']['StreetNameMIRN'] = ($data['Supply']['street_name_mirn'][$key]) ? ucwords(strtolower($data['Supply']['street_name_mirn'][$key])) : '0';
                    $submission['submitted']['StNameSuffixMIRN'] = $data['Supply']['street_name_suffix_mirn'][$key];
                    $submission['submitted']['StreetTypeMIRN'] = ($data['Supply']['street_type_mirn'][$key]) ? $data['Supply']['street_type_mirn'][$key] : 'None';
                    $submission['submitted']['SuburbMIRN'] = ucwords(strtolower($data['Supply']['suburb_mirn'][$key]));
                    $submission['submitted']['PostcodeMIRN'] = $data['Supply']['postcode_mirn'][$key];
                    $submission['submitted']['StateMIRN'] = $data['Supply']['state_mirn'][$key];
                    $submission['submitted']['extrafield'] = $data['Supply']['unstructured_mirn_address'][$key];
                } elseif (in_array($plan['Plan']['package'], array('Dual', 'Gas'))) {
                    $submission['submitted']['AddresshasnostreetnumberMIRN'] = ($data['Supply']['no_street_number'][$key] > 0) ? 'Yes' : 'No';
                    $submission['submitted']['UnitMIRN'] = $data['Supply']['unit'][$key];
                    $submission['submitted']['UnitTypeMIRN'] = $data['Supply']['unit_type'][$key];
                    $submission['submitted']['LotMIRN'] = $data['Supply']['lot'][$key];
                    $submission['submitted']['FloorMIRN'] = $data['Supply']['floor'][$key];
                    $submission['submitted']['FloorTypeMIRN'] = $data['Supply']['floor_type'][$key];
                    $submission['submitted']['BuildingNameMIRN'] = ucwords(strtolower($data['Supply']['building_name'][$key]));
                    $submission['submitted']['StreetNumberMIRN'] = ($data['Supply']['street_number'][$key]) ? $data['Supply']['street_number'][$key] : '0';
                    $submission['submitted']['StNoSuffixMIRN'] = $data['Supply']['street_number_suffix'][$key];
                    $submission['submitted']['StreetNameMIRN'] = ($data['Supply']['street_name'][$key]) ? ucwords(strtolower($data['Supply']['street_name'][$key])) : '0';
                    $submission['submitted']['StNameSuffixMIRN'] = $data['Supply']['street_name_suffix'][$key];
                    $submission['submitted']['StreetTypeMIRN'] = ($data['Supply']['street_type'][$key]) ? $data['Supply']['street_type'][$key] : 'None';
                    $submission['submitted']['SuburbMIRN'] = ucwords(strtolower($data['Supply']['suburb'][$key]));
                    $submission['submitted']['PostcodeMIRN'] = $data['Supply']['postcode'][$key];
                    $submission['submitted']['StateMIRN'] = $data['Supply']['state'][$key];
                }
                // MSATS address is different
                if (in_array($plan['Plan']['package'], array('Dual', 'Elec')) && isset($data['Supply']['msats_is_different'][$key]) && $data['Supply']['msats_is_different'][$key]) {
                    $submission['submitted']['MSATSAddressDifferent'] = $submission['submitted']['AddressDifferent'] = 'Yes';
                    $submission['submitted']['AddresshasnostreetnumberMSATS'] = ($data['Supply']['no_street_number_msats'][$key] > 0) ? 'Yes' : 'No';
                    $submission['submitted']['UnitMSATS'] = $data['Supply']['unit_msats'][$key];
                    $submission['submitted']['UnitTypeMSATS'] = $data['Supply']['unit_type_msats'][$key];
                    $submission['submitted']['LotMSATS'] = $data['Supply']['lot_msats'][$key];
                    $submission['submitted']['FloorMSATS'] = $data['Supply']['floor_msats'][$key];
                    $submission['submitted']['FloorTypeMSATS'] = $data['Supply']['floor_type_msats'][$key];
                    $submission['submitted']['BuildingNameMSATS'] = ucwords(strtolower($data['Supply']['building_name_msats'][$key]));
                    $submission['submitted']['StreetNumberMSATS'] = ($data['Supply']['street_number_msats'][$key]) ? $data['Supply']['street_number_msats'][$key] : '0';
                    $submission['submitted']['StNoSuffixMSATS'] = $data['Supply']['street_number_suffix_msats'][$key];
                    $submission['submitted']['StreetNameMSATS'] = ($data['Supply']['street_name_msats'][$key]) ? ucwords(strtolower($data['Supply']['street_name_msats'][$key])) : '0';
                    $submission['submitted']['StNameSuffixMSATS'] = $data['Supply']['street_name_suffix_msats'][$key];
                    $submission['submitted']['StreetTypeMSATS'] = ($data['Supply']['street_type_msats'][$key]) ? $data['Supply']['street_type_msats'][$key] : 'None';
                    $submission['submitted']['SuburbMSATS'] = ucwords(strtolower($data['Supply']['suburb_msats'][$key]));
                    $submission['submitted']['PostcodeMSATS'] = $data['Supply']['postcode_msats'][$key];
                    $submission['submitted']['StateMSATS'] = $data['Supply']['state_msats'][$key];
                    $submission['submitted']['extrafield'] = $data['Supply']['unstructured_msats_address'][$key];
                } elseif (in_array($plan['Plan']['package'], array('Dual', 'Elec'))) {
                    $submission['submitted']['AddresshasnostreetnumberMSATS'] = ($data['Supply']['no_street_number'][$key] > 0) ? 'Yes' : 'No';
                    $submission['submitted']['UnitMSATS'] = $data['Supply']['unit'][$key];
                    $submission['submitted']['UnitTypeMSATS'] = $data['Supply']['unit_type'][$key];
                    $submission['submitted']['LotMSATS'] = $data['Supply']['lot'][$key];
                    $submission['submitted']['FloorMSATS'] = $data['Supply']['floor'][$key];
                    $submission['submitted']['FloorTypeMSATS'] = $data['Supply']['floor_type'][$key];
                    $submission['submitted']['BuildingNameMSATS'] = ucwords(strtolower($data['Supply']['building_name'][$key]));
                    $submission['submitted']['StreetNumberMSATS'] = ($data['Supply']['street_number'][$key]) ? $data['Supply']['street_number'][$key] : '0';
                    $submission['submitted']['StNoSuffixMSATS'] = $data['Supply']['street_number_suffix'][$key];
                    $submission['submitted']['StreetNameMSATS'] = ($data['Supply']['street_name'][$key]) ? ucwords(strtolower($data['Supply']['street_name'][$key])) : '0';
                    $submission['submitted']['StNameSuffixMSATS'] = $data['Supply']['street_name_suffix'][$key];
                    $submission['submitted']['StreetTypeMSATS'] = ($data['Supply']['street_type'][$key]) ? $data['Supply']['street_type'][$key] : 'None';
                    $submission['submitted']['SuburbMSATS'] = ucwords(strtolower($data['Supply']['suburb'][$key]));
                    $submission['submitted']['PostcodeMSATS'] = $data['Supply']['postcode'][$key];
                    $submission['submitted']['StateMSATS'] = $data['Supply']['state'][$key];
                }

                $cb_lead_id = 0;
                if (isset($user['step1']['is_broadband']) && $user['step1']['is_broadband'] == 1) {
                    $count = $this->BroadbandLog->find('count', array(
                        'conditions' => array(
                            'BroadbandLog.phone' => $submission['submitted']['MobileNumber'],
                            'BroadbandLog.submitted >' => date('Y-m-d H:i:s', strtotime('-8 hours')),
                        )
                    ));

                    if ($count <= 0) {
                        $broadband_data = array(
                            'firstName' => $submission['submitted']['FirstName'],
                            'surname' => $submission['submitted']['LastName'],
                            'phone' => $submission['submitted']['MobileNumber'],
                            'Email' => $submission['submitted']['eMail'],
                            'Postcode' => $submission['submitted']['Postcode'],
                            'ReferringAgent' => $user['step1']['referring_agent'],
                            'medium' => 'free',
                        );
                        $broadband_request = json_encode($broadband_data);
                        $broadband_response = $this->broadband($broadband_request);
                        if ($broadband_response) {
                            if ($broadband_response['Response'] == 'Success') {
                                $cb_lead_id = $broadband_response['Reference Number'];
                                $submission['submitted']['cb_lead_id'] = $cb_lead_id;
                                $submission['submitted']['referred_agent_name'] = $user['step1']['referring_agent'];
                                $submission['submitted']['referred_to_Foxtel'] = 'Yes';
                                $submission['submitted']['extrafield_e'] = date('m/d/Y');
                            }
                        }
                    }
                }

                // Create or Update?
                if ($i == 1 && $data['Plan']['lead_id']) {
                    $current_lead_id = trim($data['Plan']['lead_id']);
                    $lead_ids[] = $current_lead_id;
                    $this->update_lead($campaign_id, $current_lead_id, $submission);
                } else {
                    $current_lead_id = $this->create_lead($campaign_id, $submission);
                    if ($current_lead_id && isset($data['Final']['agent_id']) && $data['Final']['agent_id']) {
                        $agent_id = $data['Final']['agent_id'];
                        $agent = $this->assign_to_agent($current_lead_id, $agent_id);
                    }
                    $lead_ids[]= $current_lead_id;
                }

                if ($cb_lead_id) {
                    $this->BroadbandLog->create();
                    $this->BroadbandLog->save(array('BroadbandLog' => array(
                        'leadid' => $current_lead_id,
                        'phone' => $submission['submitted']['MobileNumber'],
                        'request' => $broadband_request,
                        'response' => serialize($broadband_response),
                        'submitted' => date('Y-m-d H:i:s'),
                    )));
                    $cb_lead_ids[$current_lead_id] = $cb_lead_id;
                }

                if (isset($data['Final']['my_moovers_consent']) && $data['Final']['my_moovers_consent'] == 1) {
                    $submission_moversbuddy = array();
                    $submission_moversbuddy['firstname'] = ucwords(strtolower($data['Contact']['first_name']));
                    $submission_moversbuddy['surname'] = ucwords(strtolower($data['Contact']['last_name']));
                    if ($data['Contact']['email'] && $data['Contact']['email'] != 'no@email.com.au') {
                        $submission_moversbuddy['email'] = $data['Contact']['email'];
                    }
                    if ($data['Contact']['mobile']) {
                        $submission_moversbuddy['mobile'] = $data['Contact']['mobile'];
                    }
                    $submission_moversbuddy['suburb'] = ucwords(strtolower($data['Supply']['suburb'][$key]));
                    $submission_moversbuddy['lead_id'] = $current_lead_id;
                    $submission_moversbuddy['residential_business'] = ($user['step1']['customer_type'] == 'SME') ? 'Business' : 'Residential';

                    $response_moversbuddy = $this->moversbuddy($submission_moversbuddy);

                }
            }
        }

        return array('lead_ids' => $lead_ids, 'cb_lead_ids' => $cb_lead_ids);
    }

    public function admin_referral_program() {
        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data;
            $success = 0;
            $error = array();
            $update_array = array();
            $html = '';
            $html .= '<form id="referral_confirm" method="post" action="#">';
            $html .= '<div class="form-data">';
            $html .= '<h4>Your Referrals have been submitted to your Velocify account!</h4>';
            $html .= '<table class="table">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>Referrer Name</th>';
            $html .= '<th>Lead ID</th>';
            $html .= '<th>Phone</th>';
            $html .= '<th>Email</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            $html .= '<tr>';
            $html .= '<td><input class="required" name="submitted[referrer_name]" type="text" value="'.$data["submitted"]["referrer_name"].'" /></td>';
            $html .= '<td><input class="required" name="submitted[referrer_lead_id]" type="text" value="'.$data["submitted"]["referrer_lead_id"].'" /></td>';
            $html .= '<td><input name="submitted[referrer_phone]" type="text" value="'.$data["submitted"]["referrer_phone"].'" /></td>';
            $html .= '<td><input class="email" name="submitted[referrer_email]" type="text" value="'.$data["submitted"]["referrer_email"].'" /></td>';
            $html .= '</tr>';
            $html .= '</tbody>';
            $html .= '</table>';
            $html .= '<table class="table referrals">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>#</th>';
            $html .= '<th>Referral Name(s)</th>';
            $html .= '<th>Phone 1</th>';
            $html .= '<th>Phone 2</th>';
            $html .= '<th>Relationship</th>';
            $html .= '<th>Lead ID</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            // Step 1
            for ($i = 1; $i <= 10; $i++) {
                if ($data["submitted"]["referral_{$i}_name"] == '') {
                    continue;
                }
                $result = $this->referral_program_soap(array($data["submitted"]["referral_{$i}_phone"],$data["submitted"]["referral_{$i}_secondaryphone"]));
                if (!empty($result)) {
                    if (isset($result['WashNumbersReturn']['NumbersSubmitted']['Number']['!Result'])) {
                        $key = '';
                        if ($data["submitted"]["referral_{$i}_phone"] == $result['WashNumbersReturn']['NumbersSubmitted']['Number']['!']) {
                            $key = "referral_{$i}_phone";
                        } else if ($data["submitted"]["referral_{$i}_secondaryphone"] == $result['WashNumbersReturn']['NumbersSubmitted']['Number']['!']) {
                            $key = "referral_{$i}_secondaryphone";
                        }
                        if ($result['WashNumbersReturn']['NumbersSubmitted']['Number']['!Result'] != 'N') {
                            if ($result['WashNumbersReturn']['NumbersSubmitted']['Number']['!Result'] == 'Y') {
                                $error[$key] = $result['WashNumbersReturn']['NumbersSubmitted']['Number']['!'] . ' - this number is on the register';
                            } else if ($result['WashNumbersReturn']['NumbersSubmitted']['Number']['!Result'] == 'I') {
                                $error[$key] = $result['WashNumbersReturn']['NumbersSubmitted']['Number']['!'] . ' - this number is invalid';
                            }
                            continue;
                        } else {
                            $success = 1;
                        }
                    } else {
                        foreach ($result['WashNumbersReturn']['NumbersSubmitted']['Number'] as $rs) {
                            $key = '';
                            if ($data["submitted"]["referral_{$i}_phone"] == $rs['!']) {
                                $key = "referral_{$i}_phone";
                            } else if ($data["submitted"]["referral_{$i}_secondaryphone"] == $rs['!']) {
                                $key = "referral_{$i}_secondaryphone";
                            }
                            if ($rs['!Result'] != 'N') {
                                if ($rs['!Result'] == 'Y') {
                                    $error[$key] = $rs['!'] . ' - this number is on the register';
                                } elseif ($rs['!Result'] == 'I') {
                                    $error[$key] = $rs['!'] . ' - this number is invalid';
                                }
                                continue 2;
                            } else {
                                $success = 1;
                            }
                        }
                    }

                    $dncr_ref = $result['WashNumbersReturn']['NumbersSubmitted']['!TransactionId'];
                }

                $update_array["submitted"]["referral_{$i}_name"] = $data["submitted"]["referral_{$i}_name"];
                $update_array["submitted"]["referral_{$i}_phone"] = $data["submitted"]["referral_{$i}_phone"];

                $import_array = array();
                $import_array["submitted"]["name"] = $data["submitted"]["referral_{$i}_name"];
                $import_array["submitted"]["phone"] = $data["submitted"]["referral_{$i}_phone"];
                $import_array["submitted"]["phone2"] = $data["submitted"]["referral_{$i}_secondaryphone"];
                $import_array["submitted"]["time_to_call"] = $data["submitted"]["referral_{$i}_time_to_call"];
                $import_array["submitted"]["referrer_name"] = $data["submitted"]["referrer_name"];
                $import_array["submitted"]["referrer_lead_id"] = $data["submitted"]["referrer_lead_id"];
                $import_array["submitted"]["referrer_relationship"] = $data["submitted"]["referral_{$i}_relationship"];
                $import_array["submitted"]["DNCR_Ref"] = $dncr_ref;
                $import_array["submitted"]["sales_rep_if_applicable"] = $data["submitted"]["user"];
                $request = http_build_query($import_array, '', '&');
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $this->leads360_url_1.'/Import.aspx?Provider=VoucherStore&Client=41189&CampaignId=20&XmlResponse=True');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
                $response = curl_exec($ch);
                curl_close($ch);
                $lead_id = 0;
                if ($response) {
                    $result = simplexml_load_string($response);
                    foreach ($result->ImportResult[0]->attributes() as $key => $value) {
                        if ($key == 'leadId') {
                            $lead_id = (int)$value;
                        }
                    }
                }
                $this->Submission->create();
                $this->Submission->save(array('Submission' => array(
                    'sid' => time(),
                    'leadid' => $lead_id,
                    'request' => $request,
                    'response' => $response,
                    'submitted' => date('Y-m-d H:i:s'),
                    'source' => 'Referral Program',
                )));

                $html .= '<tr>';
                $html .= '<td><span class="nr">'.$i.'</span></td>';
                $html .= '<td><input class="name-input required" name="submitted[referral_'.$i.'_name]" rel="submitted[name]" type="text" value="'.$data["submitted"]["referral_{$i}_name"].'" /></td>';
                $html .= '<td><input class="phone-input required" name="submitted[referral_'.$i.'_phone]" rel="submitted[phone]" type="text" value="'.$data["submitted"]["referral_{$i}_phone"].'" /></td>';
                $html .= '<td><input class="phone-input" name="submitted[referral_'.$i.'_secondaryphone]" type="text" value="'.$data["submitted"]["referral_{$i}_secondaryphone"].'" /></td>';
                $html .= '<td><input name="submitted[referral_'.$i.'_relationship]" type="text" value="'.$data["submitted"]["referral_{$i}_relationship"].'" /></td>';
                $html .= '<td><h5><a href="https://lm.prod.velocify.com/Web/LeadAddEdit.aspx?LeadId='.$lead_id.'" target="_blank">'.$lead_id.'</a></h5></td>';
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';

            $html .= '<div class="form-group row">';
            $html .= '<div class="col-xs-6 field-col">';
            $html .= '<label>Sales Rep Name</label>';
            $html .= '<input type="text" id="sales_rep_name" name="submitted[user_name]" value="'.$data["submitted"]["user_name"].'">';
            $html .= '<input type="hidden" value="'.$data["submitted"]["user"].'" id="sales_rep_email" name="submitted[user]">';
            $html .= '</div>';
            $html .= '</div>';

            $html .= '</div>';
            $html .= '</form>';

            /*
            // Step 2 after Christmas
            $fields = http_build_query($update_array, '', '&');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://secure.velocify.com/Update.aspx?Provider=VoucherStore&Client=41189&LeadId={$data["submitted"]["referrer_lead_id"]}");
            //curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            $response = curl_exec($ch);
            curl_close($ch);
            */
            $return = array(
                'success' => $success,
                'error' => $error,
                'html' => $html
            );
            return new CakeResponse(array(
                'body' => json_encode($return),
                'type' => 'json',
                'status' => '201'
            ));
        }
    }

    protected function referral_program_soap($numbers) {
        require_once(APP . 'Vendor' . DS . 'nusoap' . DS . 'lib' . DS . 'nusoap.php');

        $endpoint = "https://www.donotcall.gov.au/dncrtelem/rtw/washing.cfc?wsdl";
        $client = new nusoap_client($endpoint, 'wsdl');

        $msg = '<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:rtw="http://rtw.dncrtelem" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/">
       <soapenv:Header/>
       <soapenv:Body>
          <rtw:WashNumbers soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
             <TelemarketerId xsi:type="xsd:string" xs:type="type:string" xmlns:xs="http://www.w3.org/2000/XMLSchema-instance">8387</TelemarketerId>
             <WashOnlyUserId xsi:type="xsd:string" xs:type="type:string" xmlns:xs="http://www.w3.org/2000/XMLSchema-instance"></WashOnlyUserId>
             <TelemarketerPassword xsi:type="xsd:string" xs:type="type:string" xmlns:xs="http://www.w3.org/2000/XMLSchema-instance">Texter1234</TelemarketerPassword>
             <ClientReferenceId xsi:type="xsd:string" xs:type="type:string" xmlns:xs="http://www.w3.org/2000/XMLSchema-instance"></ClientReferenceId>
             <NumbersToWash xsi:type="rtw:ArrayOf_xsd_anyType" soapenc:arrayType="xsd:anyType[]">';
        foreach ($numbers as $number) {
            if (!$number) continue;
            $msg .= '<Number xsi:type="xsd:string">'.$number.'</Number>';
        }
        $msg .= '</NumbersToWash>
          </rtw:WashNumbers>
       </soapenv:Body>
    </soapenv:Envelope>';
        $result = $client->send($msg, $endpoint);

        return $result;
    }

    public function admin_form() {
        if ($this->request->is('post') || $this->request->is('put')) {
            $action = $this->request->data['action'];
            switch ($action) {
                case 'view':
                    if (isset($this->request->data['mobile']) && $this->request->data['mobile']) {
                        $mobile = $this->request->data['mobile'];
                        $submissions = $this->Submission->find('all', array(
                            'conditions' => array(
                                'or' => array(
                                    'Submission.mobile' => $mobile,
                                    'Submission.request LIKE' => "%{$mobile}%",
                                ),
                            ),
                            'order' => array(
                                'Submission.id' => 'DESC',
                            )
                        ));
                        $this->set(compact('mobile', 'submissions'));
                    } elseif (isset($this->request->data['lead_id']) && $this->request->data['lead_id']) {
                        $lead_id = $this->request->data['lead_id'];
                        $this->redirect('/admin/customers/view/' . $lead_id);
                    }
                    break;
                case 'update':
                    $campaign_id = $this->request->data['campaign_id'];
                    $lead_id = $this->request->data['lead_id'];
                    $email = $this->request->data['email'];
                    if ($campaign_id && $lead_id && $email) {
                        $submission['submitted']['eMailå'] = $email;
                        $this->update_lead($campaign_id, $lead_id, $submission);
                        $this->Session->setFlash('Lead has been updated', 'flash_success');
                    }
                    $this->redirect('/admin/customers/form');
                    break;
                case 'update_telco':
                    if (isset($this->request->data['lead_id']) && $this->request->data['lead_id']) {
                        $lead_id = $this->request->data['lead_id'];
                        $customer = $this->Customer->find('first', array(
                            'fields' => array(
                                'Customer.id',
                            ),
                            'conditions' => array(
                                'Customer.is_telco' => 1,
                                'Customer.leadid LIKE' => "%{$lead_id}%",
                                'Customer.signup_data !=' => '',
                                'Customer.signup_data IS NOT NULL',
                            ),
                            'order' => array('Customer.id' => 'DESC')
                        ));
                        if (!$customer) {
                            $this->Session->setFlash('The lead has not been signed up yet', 'flash_error');
                            $this->redirect('/admin/customers/form');
                        }
                        $this->redirect('/admin/customers/signup_telco/' . $customer['Customer']['id']);
                    }
                    break;
            }
        }
    }

    public function admin_form_telco() {
        if ($this->request->is('post') || $this->request->is('put')) {
            $action = $this->request->data['action'];
            switch ($action) {
                case 'update_telco':
                    if (isset($this->request->data['lead_id']) && $this->request->data['lead_id']) {
                        $lead_id = $this->request->data['lead_id'];
                        $customer = $this->Customer->find('first', array(
                            'fields' => array(
                                'Customer.id',
                            ),
                            'conditions' => array(
                                'Customer.is_telco' => 1,
                                'Customer.leadid LIKE' => "%{$lead_id}%",
                                'Customer.signup_data !=' => '',
                                'Customer.signup_data IS NOT NULL',
                            ),
                            'order' => array('Customer.id' => 'DESC')
                        ));
                        if (!$customer) {
                            $this->Session->setFlash('The lead has not been signed up yet', 'flash_error');
                            $this->redirect('/admin/customers/form_telco');
                        }
                        $this->redirect('/admin/customers/signup_telco/' . $customer['Customer']['id']);
                    }
                    break;
            }
        }
    }

    public function admin_view($id) {
        $customer = "";
        $customer = $this->Customer->find('first', array(
            'fields' => array(
                 'Customer.id',
                 'Customer.data',
                 'Customer.plan_data',
                 'Customer.elec_rate_data',
                 'Customer.gas_rate_data',
                 'Customer.signup_data',
             ),
             'conditions' => array(
                 'Customer.leadid LIKE' => "%{$id}%",
                 //'Customer.leadid' => $id,
                 'Customer.signup_data !=' => '',
                 'Customer.signup_data IS NOT NULL',
             ),
             'order' => array('Customer.id' => 'DESC')
        ));
        if (!$customer) {
            $this->Session->setFlash('The lead has not been signed up yet', 'flash_error');
            $this->redirect('/admin/customers/form');
        }
        $user = unserialize($customer['Customer']['data']);
        $plan = unserialize($customer['Customer']['plan_data']);
        $elec_rate = ($customer['Customer']['elec_rate_data']) ? unserialize($customer['Customer']['elec_rate_data']) : '';
        $gas_rate = ($customer['Customer']['gas_rate_data']) ? unserialize($customer['Customer']['gas_rate_data']) : '';
        $lead = unserialize($customer['Customer']['signup_data']);


        $this->set(compact('id', 'customer', 'user', 'plan', 'lead', 'elec_rate', 'gas_rate'));
    }

    public function get_lead_fields() {
        $lead = array();
        if ($this->request->is('post') || $this->request->is('put')) {
            $id = $this->request->data['lead_id'];
            $response = $this->get_lead($id);
            if (strpos($response, '<Leads>') !== false) {
                $lead['id'] = $id;
                $lead['last_name'] = '';
                $lead['email'] = '';
                $xml = simplexml_load_string($response);
                $lead_array = json_decode(json_encode($xml), true);
                $lead['lead_type'] = $lead_array['Lead']['@attributes']['LeadFormType'];
                $lead['campaign_id'] = $lead_array['Lead']['Campaign']['@attributes']['CampaignId'];
                $lead['campaign_name'] = $lead_array['Lead']['Campaign']['@attributes']['CampaignTitle'];
                $lead['first_campaign'] = '';
                $lead['status'] = $lead_array['Lead']['Status']['@attributes']['StatusId'];
                $lead['agent_name'] = $lead_array['Lead']['Agent']['@attributes']['AgentName'];
                $lead['agent_id'] = $lead_array['Lead']['Agent']['@attributes']['AgentId'];
                $lead['sale_completion_date'] = '';
                foreach($lead_array['Lead']['Fields']['Field'] as $field) {
                    if ($field['@attributes']['FieldTitle'] == 'Fuel Type') {
                        $lead['fuel_type'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'New Electricity Retailer') {
                        $lead['elec_retailer'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'New Gas Retailer') {
                        $lead['gas_retailer'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Business or Residential') {
                        $lead['customer_type'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'MoveIn OR Transfer') {
                        $lead['looking_for'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Title') {
                        $lead['title'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'First Name') {
                        $lead['first_name'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Last Name') {
                        $lead['last_name'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Date Of Birth') {
                        $lead['dob'] = '';
                        if ($field['@attributes']['Value']) {
                            $lead['dob'] = date('d/m/Y', strtotime($field['@attributes']['Value']));
                        }
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Mobile Number') {
                        $lead['mobile'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Home Phone') {
                        $lead['home_phone'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'eMail') {
                        $lead['email'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Secondary Contact Title') {
                        $lead['secondary_title'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Secondary Contact First Name') {
                        $lead['secondary_first_name'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Secondary Contact Surname') {
                        $lead['secondary_last_name'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Secondary Contact DOB') {
                        $lead['secondary_dob'] = '';
                        if ($field['@attributes']['Value']) {
                            $lead['secondary_dob'] = date('d/m/Y', strtotime($field['@attributes']['Value']));
                        }
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Secondary Mobile Number') {
                        $lead['secondary_mobile'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Secondary Home Number') {
                        $lead['secondary_phone'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'NMI Code') {
                        $lead['nmi'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'MIRN Number') {
                        $lead['mirn'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Sales Rep Name') {
                        $lead['sales_rep_name'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Campaign Source') {
                        $lead['campaign_source'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Optimizely Variation Name') {
                        $lead['content'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Optimizely Campaign Name') {
                        $lead['lead_age'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Campaign Medium') {
                        $lead['medium'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'How they found us?') {
                        $lead['howtheyfoundus'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Campaign Keyword') {
                        $lead['keyword'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'URL') {
                        $lead['url'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Lead Campaign') {
                        $lead['lead_campaign'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Campaign Adgroup') {
                        $lead['campaign_ad_group'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == '**Website Customer ID**') {
                        $lead['id'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'ABN') {
                        $lead['abn'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Trading Name') {
                        $lead['trading_name'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Legal Name') {
                        $lead['legal_name'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Business Type') {
                        $lead['business_type'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Document Type') {
                        $lead['document_type'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Document ID Number') {
                        $lead['document_id'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Document Expiry') {
                        $lead['document_expiry'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'DL State') {
                        $lead['document_state'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Document Country of Issue') {
                        $lead['document_country'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Sale Completion Date') {
                        if ($field['@attributes']['Value']) {
                            $lead['sale_completion_date'] = date('d/m/Y', strtotime($field['@attributes']['Value']));
                        }
                    }
                    if ($field['@attributes']['FieldTitle'] == 'First Campaign') {
                        $lead['first_campaign'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Tenant / Owner') {
                        $lead['tenant_owner'] = $field['@attributes']['Value'];
                    }

                    // Supply address
                    if ($field['@attributes']['FieldTitle'] == 'Suburb (Supply)') {
                        $lead['suburb_supply'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Postcode (Supply)') {
                        $lead['postcode_supply'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'State (Supply)') {
                        $lead['state_supply'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Address has no street number') {
                        $lead['no_street_number_supply'] = ($field['@attributes']['Value'] == 'False') ? 0 : 1;
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Street Number (Supply)') {
                        $lead['street_number_supply'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'St No Suffix (Supply)') {
                        $lead['street_number_suffix_supply'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Street Name (Supply)') {
                        $lead['street_name_supply'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'St Name Suffix (Supply)') {
                        $lead['street_name_suffix_supply'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Street Type (Supply)') {
                        $lead['street_type_supply'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Unit (Supply)') {
                        $lead['unit_supply'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Unit Type (Supply)') {
                        $lead['unit_type_supply'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Lot (Supply)') {
                        $lead['lot_supply'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Floor (Supply)') {
                        $lead['floor_supply'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Floor Type (Supply)') {
                        $lead['floor_type_supply'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Building Name') {
                        $lead['building_name_supply'] = $field['@attributes']['Value'];
                    }

                    // Billing address
                    if ($field['@attributes']['FieldTitle'] == 'Billing Address Different') {
                        $lead['billing_address_different'] = ($field['@attributes']['Value'] == 'N') ? 0 : 1;
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Suburb (Billing)') {
                        $lead['suburb_billing'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Postcode (Billing)') {
                        $lead['postcode_billing'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'State (Billing)') {
                        $lead['state_billing'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Address has no street number_') {
                        $lead['no_street_number_billing'] = ($field['@attributes']['Value'] == 'False') ? 0 : 1;
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Street Number (Billing)') {
                        $lead['street_number_billing'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'St No Suffix (Billing)') {
                        $lead['street_number_suffix_billing'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Street Name (Billing)') {
                        $lead['street_name_billing'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'St Name Suffix (Billing)') {
                        $lead['street_name_suffix_billing'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Street Type (Billing)') {
                        $lead['street_type_billing'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Unit (Billing)') {
                        $lead['unit_billing'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Unit Type (Billing)') {
                        $lead['unit_type_billing'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Lot (Billing)') {
                        $lead['lot_billing'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Floor (Billing)') {
                        $lead['floor_billing'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Floor Type (Billing)') {
                        $lead['floor_type_billing'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Building Name (Billing)') {
                        $lead['building_name_billing'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'POBOX') {
                        $lead['po_box_billing'] = $field['@attributes']['Value'];
                    }

                    // Telco
                    if ($field['@attributes']['FieldTitle'] == 'Monthly Plan Price') {
                        $lead['telco_price'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Telco Plan Type') {
                        $lead['telco_plan_type'] = $field['@attributes']['Value'];
                    }
                    $lead['telco_reference_number'] = '';
                    if ($field['@attributes']['FieldTitle'] == 'Telco Reference Number') {
                        $lead['telco_reference_number'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'NBN Speed') {
                        $lead['telco_nbn_speed'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Retailer') {
                        $lead['telco_retailer'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Payment Type') {
                        $lead['telco_payment_type'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Pay & Submit') {
                        $lead['telco_pay_submit'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Bundle Discount [Y/N]') {
                        $lead['telco_bundle_discount'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Referred Agent Name') {
                        $lead['telco_referred_agent_name'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Referrer Lead ID') {
                        $lead['referrer_lead_id'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Credit Consent') {
                        $lead['credit_consent'] = $field['@attributes']['Value'];
                    }

                    if ($field['@attributes']['FieldTitle'] == 'Company Industry') {
                        $lead['company_industry'] = $field['@attributes']['Value'];
                    }

                    if ($field['@attributes']['FieldTitle'] == 'Hubspot VID') {
                        $lead['vid'] = $field['@attributes']['Value'];
                    }
                    if ($field['@attributes']['FieldTitle'] == 'Call Medium') {
                        $lead['call_medium'] = $field['@attributes']['Value'];
                    }
                }
            } else {
                $ip = $this->request->clientIp();
                $to = 's.liu@electricitywizard.com.au';
                $subject = 'Velocify API error - GetLead - Signup';
                $message = "Lead ID: {$id}\r\n";
                $message .= "IP: {$ip}\r\n";
                $message .= $response;
                $headers = 'From: api@electricitywizard.com.au' . "\r\n" .
                    'Reply-To: api@electricitywizard.com.au' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

                mail($to, $subject, $message, $headers);
            }
        }
        return new CakeResponse(array(
            'body' => json_encode($lead),
            'type' => 'json',
            'status' => '201'
        ));
    }

    public function get_rates($solar = false) {
        $states_arr = unserialize(AU_STATES);
        if ($this->request->is('put') || $this->request->is('post')) {
            $id = $this->request->data['id'];
            $customer = $this->Customer->findById($id);
            $user = unserialize($customer['Customer']['data']);
            $plan = unserialize($customer['Customer']['plan_data']);
            $plan['Plan']['unit_of_measurement_of_rates'] = '';
            $step1 = $user['step1'];
            if ($customer['Customer']['elec_rate_data']) {
                $elec_rate_data = unserialize($customer['Customer']['elec_rate_data']);
                if ($plan['Plan']['retailer'] == 'Blue NRG') {
                    $plan['Plan']['elec_rate'] = $elec_rate_data['BlueNrgRate'];
                } else {
                    $plan['Plan']['elec_rate'] = $elec_rate_data['ElectricityRate'];
                }
            }
            if ($customer['Customer']['gas_rate_data']) {
                $gas_rate_data = unserialize($customer['Customer']['gas_rate_data']);
                $plan['Plan']['gas_rate'] = $gas_rate_data['GasRate'];
            }
            $solar_rebate_scheme = '';

            if ($step1['nmi_distributor']) {
                if ($step1['tariff1']) {
                    $tariff1 = explode('|', $step1['tariff1']);
                    if ($tariff1[3] == 'Solar') {
                        $tariff_code = $tariff1[0];
                    }
                }
                if ($step1['tariff2']) {
                    $tariff2 = explode('|', $step1['tariff2']);
                    if ($tariff2[3] == 'Solar') {
                        $tariff_code = $tariff2[0];
                    }
                }
                if ($step1['tariff3']) {
                    $tariff3 = explode('|', $step1['tariff3']);
                    if ($tariff3[3] == 'Solar') {
                        $tariff_code = $tariff3[0];
                    }
                }
                if ($step1['tariff4']) {
                    $tariff4 = explode('|', $step1['tariff4']);
                    if ($tariff4[3] == 'Solar') {
                        $tariff_code = $tariff4[0];
                    }
                }
                if ($tariff_code) {
                    $tariff = $this->Tariff->find('first', array(
                        'conditions' => array(
                            'Tariff.tariff_code' => $tariff_code,
                            'Tariff.res_sme' => $step1['customer_type'],
                            'Tariff.distributor' => explode('/', $step1['nmi_distributor']),
                        ),
                    ));
                    $solar_rebate_scheme = $tariff['Tariff']['solar_rebate_scheme'];
                    
                }
            }
            $plan['Plan']['solar_rate'] = array();
            if ($solar_rebate_scheme) {
                if (strpos($solar_rebate_scheme, '/') !== false) {
                    $solar_rebate_scheme = $step1['solar_rebate_scheme'];
                }
                $solar_rebate_scheme_rate = $this->SolarRebateScheme->findByStateAndScheme($states_arr[$customer['Customer']['state']], $solar_rebate_scheme);
                $plan['Plan']['solar_rate']['government'] = $solar_rebate_scheme_rate['SolarRebateScheme']['government'];
                switch ($plan['Plan']['retailer']) {
                    case 'AGL':
                        $plan['Plan']['solar_rate']['retailer'] = $solar_rebate_scheme_rate['SolarRebateScheme']['agl'];
                        break;
                    case 'Lumo Energy':
                        $plan['Plan']['solar_rate']['retailer'] = $solar_rebate_scheme_rate['SolarRebateScheme']['lumo_energy'];
                        break;
                    case 'Momentum':
                        $plan['Plan']['solar_rate']['retailer'] = $solar_rebate_scheme_rate['SolarRebateScheme']['momentum'];
                        break;
                    case 'Origin Energy':
                        $plan['Plan']['solar_rate']['retailer'] = $solar_rebate_scheme_rate['SolarRebateScheme']['origin_energy'];
                        break;
                    case 'Powerdirect':
                        $plan['Plan']['solar_rate']['retailer'] = $solar_rebate_scheme_rate['SolarRebateScheme']['powerdirect'];
                        break;
                    case 'Powershop':
                        $plan['Plan']['solar_rate']['retailer'] = $solar_rebate_scheme_rate['SolarRebateScheme']['powershop'];
                        break;
                    case 'Sumo Power':
                        $plan['Plan']['solar_rate']['retailer'] = $solar_rebate_scheme_rate['SolarRebateScheme']['sumo_power'];
                        break;
                    case 'Alinta Energy':
                        $plan['Plan']['solar_rate']['retailer'] = $solar_rebate_scheme_rate['SolarRebateScheme']['alinta_energy'];
                        break;
                    case 'ERM':
                        $plan['Plan']['solar_rate']['retailer'] = $solar_rebate_scheme_rate['SolarRebateScheme']['erm'];
                        break;
                    case 'Powerdirect and AGL':
                        $plan['Plan']['solar_rate']['retailer'] = $solar_rebate_scheme_rate['SolarRebateScheme']['pd_agl'];
                        break;
                    case 'Energy Australia':
                        $plan['Plan']['solar_rate']['retailer'] = $solar_rebate_scheme_rate['SolarRebateScheme']['energy_australia'];
                        break;
                    case 'Next Business Energy':
                        $plan['Plan']['solar_rate']['retailer'] = $solar_rebate_scheme_rate['SolarRebateScheme']['next_business_energy'];
                        break;
                    case 'ActewAGL':
                        $plan['Plan']['solar_rate']['retailer'] = $solar_rebate_scheme_rate['SolarRebateScheme']['actewagl'];
                        break;
                    case 'Elysian Energy':
                        $plan['Plan']['solar_rate']['retailer'] = $solar_rebate_scheme_rate['SolarRebateScheme']['elysian_energy'];
                        break;
                    case 'Testing Retailer':
                        $plan['Plan']['solar_rate']['retailer'] = $solar_rebate_scheme_rate['SolarRebateScheme']['testing_retailer'];
                        break;
                    case 'Simply Energy':
                        $plan['Plan']['solar_rate']['retailer'] = $solar_rebate_scheme_rate['SolarRebateScheme']['simply_energy'];
                        break;
                    case 'Blue NRG':
                        $plan['Plan']['solar_rate']['retailer'] = $solar_rebate_scheme_rate['SolarRebateScheme']['blue_nrg'];
                        break;
                    case 'Tango Energy':
                        $plan['Plan']['solar_rate']['retailer'] = $solar_rebate_scheme_rate['SolarRebateScheme']['tango_energy'];
                        break;
                    case 'Red Energy':
                        $plan['Plan']['solar_rate']['retailer'] = $solar_rebate_scheme_rate['SolarRebateScheme']['red_energy'];
                        break;
                }
            }
            $this->autoRender = false;
            $view = new View($this, false);
            $view->layout = 'ajax';
            $view->set(compact('user', 'plan', 'step1'));

            if ($solar) {
                $view_output = $view->render('/Elements/signup_rates_solar');
            } else {
                $view_output = $view->render('/Elements/signup_rates');
            }

            return new CakeResponse(array(
                'body' => json_encode(array(
                    'html' => $view_output
                )),
                'type' => 'json',
                'status' => '201'
            ));
        }
    }

    public function get_move_in_info() {
        $states_arr = unserialize(AU_STATES);
        if ($this->request->is('put') || $this->request->is('post')) {
            $id = $this->request->data['id'];
            $customer = $this->Customer->findById($id);
            $user = unserialize($customer['Customer']['data']);
            $plan = unserialize($customer['Customer']['plan_data']);
            $step1 = $user['step1'];

            if (in_array($plan['Plan']['package'], array('Elec'))) {
                $nmi_mirn = $step1['nmi'];
            }

            if (in_array($plan['Plan']['package'], array('Gas'))) {
                $nmi_mirn = $step1['mirn'];
            }

            $move_in_info = $this->MoveInInfo->find('first', array(
				'conditions' => array(
					'MoveInInfo.state' => $plan['Plan']['state'],
					'MoveInInfo.package' => $plan['Plan']['package'],
					'MoveInInfo.nmi_mirn' => $nmi_mirn,
				),
			));
			//$solar_rebate_scheme = $tariff['Tariff']['solar_rebate_scheme'];

            $this->autoRender = false;
            $view = new View($this, false);
            $view->layout = 'ajax';
            $view->set(compact('user', 'plan', 'step1'));

            $view_output = $view->render('/Elements/signup_move_in_info');


            return new CakeResponse(array(
                'body' => json_encode(array(
                    'html' => $view_output
                )),
                'type' => 'json',
                'status' => '201'
            ));
        }
    }

    private function create_lead($campaign_id = 1, $submission = array())
    {
        $is_dncr = false;
        $vid = '';
        $hubspot_response = '';
        if (isset($submission['submitted']['MobileNumber']) && $submission['submitted']['MobileNumber']) {
            $dncr = $this->Dncr->find('first', array(
                'conditions' => array(
                    'Dncr.phone' => $submission['submitted']['MobileNumber'],
                ),
            ));
            if ($dncr) {
                $is_dncr = true;
            }
        }
        if (isset($submission['submitted']['HomePhone']) && $submission['submitted']['HomePhone']) {
            $dncr = $this->Dncr->find('first', array(
                'conditions' => array(
                    'Dncr.phone' => $submission['submitted']['HomePhone'],
                ),
            ));
            if ($dncr) {
                $is_dncr = true;
            }
        }
        if (isset($submission['submitted']['WorkNumber']) && $submission['submitted']['WorkNumber']) {
            $dncr = $this->Dncr->find('first', array(
                'conditions' => array(
                    'Dncr.phone' => $submission['submitted']['WorkNumber'],
                ),
            ));
            if ($dncr) {
                $is_dncr = true;
            }
        }
        if (isset($submission['submitted']['eMail']) && $submission['submitted']['eMail']) {
            $dncr = $this->Dncr->find('first', array(
                'conditions' => array(
                    'Dncr.email' => $submission['submitted']['eMail'],
                ),
            ));
            if ($dncr) {
                $is_dncr = true;
            }
        }
        if (!$is_dncr) {
            $hubspot_response = $this->create_lead_hubspot($submission);
            $data = json_decode($hubspot_response, true);
            if (isset($data['id']) && $data['id']) {
                $vid = $data['id'];
            }
            if ($vid) {
                $submission['submitted']['extrafield8'] = $vid;
            }
        }

        //$middleware_id = $this->velocify_middleware_save($campaign_id, null, $submission);

        $request = http_build_query($submission, '', '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->leads360_url_1."/Import.aspx?Provider=VoucherStore&Client=41189&CampaignId={$campaign_id}&XmlResponse=True");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        $response = curl_exec($ch);
        curl_close($ch);
        $lead_id = 0;
        if ($response) {
            if (strpos($response, 'Success') !== false) {
                $result = simplexml_load_string($response);
                foreach ($result->ImportResult[0]->attributes() as $key => $value) {
                    if ($key == 'leadId') {
                        $lead_id = (int)$value;
                    }
                }
            } else {
                $to      = 'info@seanpro.com';
                $subject = 'Velocify API error - Import';
                $message = $response;
                $headers = 'From: info@seanpro.com' . "\r\n" .
                    'Reply-To: info@seanpro.com' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

                mail($to, $subject, $message, $headers);
            }
        }

        if (!$lead_id) {
            //$lead_id = $middleware_id;
        }

        if (!$vid) {
            $this->add_lead_history($lead_id, 'action', 'Hubspot Lead Creation Failed', $hubspot_response);
        } else {
            $this->add_lead_history($lead_id, 'action', 'Hubspot Lead Created', $hubspot_response);
        }

        $this->Submission->create();
        $this->Submission->save(array('Submission' => array(
            'sid' => time(),
            'leadid' => $lead_id,
            'mobile' => $submission['submitted']['MobileNumber'],
            'email' => $submission['submitted']['eMail'],
            'request' => $request,
            'response' => $response,
            'submitted' => date('Y-m-d H:i:s'),
            'source' => (isset($submission['ew_duplicate']) && $submission['ew_duplicate']) ? 'Re-post Duplicate' : 'Signup Form',
        )));

        return $lead_id;
    }

    private function update_lead($campaign_id = 1, $id = null, $submission = array())
    {
        if (isset($submission['submitted']['extrafield8']) && $submission['submitted']['extrafield8']) {
            $vid = $submission['submitted']['extrafield8'];
            $is_dncr = false;
            if (isset($submission['submitted']['MobileNumber']) && $submission['submitted']['MobileNumber']) {
                $dncr = $this->Dncr->find('first', array(
                    'conditions' => array(
                        'Dncr.phone' => $submission['submitted']['MobileNumber'],
                    ),
                ));
                if ($dncr) {
                    $is_dncr = true;
                }
            }
            if (isset($submission['submitted']['HomePhone']) && $submission['submitted']['HomePhone']) {
                $dncr = $this->Dncr->find('first', array(
                    'conditions' => array(
                        'Dncr.phone' => $submission['submitted']['HomePhone'],
                    ),
                ));
                if ($dncr) {
                    $is_dncr = true;
                }
            }
            if (isset($submission['submitted']['WorkNumber']) && $submission['submitted']['WorkNumber']) {
                $dncr = $this->Dncr->find('first', array(
                    'conditions' => array(
                        'Dncr.phone' => $submission['submitted']['WorkNumber'],
                    ),
                ));
                if ($dncr) {
                    $is_dncr = true;
                }
            }
            if (isset($submission['submitted']['eMail']) && $submission['submitted']['eMail']) {
                $dncr = $this->Dncr->find('first', array(
                    'conditions' => array(
                        'Dncr.email' => $submission['submitted']['EmailM'],
                    ),
                ));
                if ($dncr) {
                    $is_dncr = true;
                }
            }
            if (!$is_dncr) {
                $hubspot_response = $this->update_lead_hubspot($vid, $submission);
                $data = json_decode($hubspot_response, true);
                if (isset($data['id']) && $data['id']) {
                    $updated_vid = $data['id'];
                }
                if (!$updated_vid) {
                    $this->add_lead_history($id, 'action', 'Hubspot Lead Update Failed', $hubspot_response);
                } else {
                    $this->add_lead_history($id, 'action', 'Hubspot Lead Updated', $hubspot_response);
                }
            }
        }

        //$middleware_id = $this->velocify_middleware_save($campaign_id, $id, $submission);

        $request = http_build_query($submission, '', '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->leads360_url_1."/Update.aspx?Provider=VoucherStore&Client=41189&CampaignId={$campaign_id}&XmlResponse=True&LeadId={$id}");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        $response = curl_exec($ch);
        curl_close($ch);
        if ($response) {
            if (strpos($response, 'Success') !== false) {
                //return $id;
            } else {
                $to      = 's.liu@electricitywizard.com.au';
                $subject = 'Velocify API error - Update';
                $message = "Lead ID: {$id}\r\n";
                $message .= $response;
                $headers = 'From: api@electricitywizard.com.au' . "\r\n" .
                    'Reply-To: api@electricitywizard.com.au' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

                mail($to, $subject, $message, $headers);
            }
        }

        $this->Submission->create();
        $this->Submission->save(array('Submission' => array(
            'sid' => time(),
            'leadid' => $id,
            'request' => $request,
            'response' => $response,
            'submitted' => date('Y-m-d H:i:s'),
            'source' => (isset($submission['ew_repost']) && $submission['ew_repost']) ? 'Re-post Update' : 'Signup Form Update',
        )));
    }

    private function velocify_middleware_save($campaign_id, $lead_id = null, $submission)
    {
        $existing = false;
        if ($lead_id) {
            $existing = $this->Lead->find('first', array(
                'conditions' => array(
                    'Lead.id' => $lead_id,
                ),
            ));
        }

        $lead = array();
        $fields = array();

        if (!$existing) {
            $lead['submitted'] = date('Y-m-d H:i:s');
        } else {
            $lead['id'] = $lead_id;
        }

        $lead['lead_campaign'] = $campaign_id;

        if (isset($submission['submitted']['FuelType']) && $submission['submitted']['FuelType']) {
            $lead['fuel_type'] = $submission['submitted']['FuelType'];
            $fields[] = 'fuel_type';
        }
        if (isset($submission['submitted']['SaleType']) && $submission['submitted']['SaleType']) {
            $lead['sale_type'] = $submission['submitted']['SaleType'];
            $fields[] = 'sale_type';
        }
        if (isset($submission['BusOrResidential']) && $submission['BusOrResidential']) {
            $lead['business_or_residential'] = $submission['BusOrResidential'];
            $fields[] = 'business_or_residential';
        }

        $lead['flybuys_number'] = 0;
        $fields[] = 'flybuys_number';

        $lead['flybuys_consent'] = 'No';
        $fields[] = 'flybuys_consent';

        $lead['flybuys_points'] = 0;
        $fields[] = 'flybuys_points';

        $lead['aeo_registration_consent'] = 'No';
        $fields[] = 'aeo_registration_consent';

        $lead['green_energy'] = 0;
        $fields[] = 'green_energy';

        $lead['lumo_package'] = 'Lumo Market Offer';
        $fields[] = 'lumo_package';

        if (isset($submission['submitted']['BillingType']) && $submission['submitted']['BillingType']) {
            $lead['billing_type'] = $submission['submitted']['BillingType'];
            $fields[] = 'billing_type';
        }

        $lead['led_transfer'] = 'No';
        $fields[] = 'led_transfer';

        $lead['led_representative'] = 'NA';
        $fields[] = 'led_representative';

        if (isset($submission['submitted']['AnyHazardsAccessingMeter']) && $submission['submitted']['AnyHazardsAccessingMeter']) {
            $lead['any_hazards_accessing_meter'] = $submission['submitted']['AnyHazardsAccessingMeter'];
            $fields[] = 'any_hazards_accessing_meter';
        }

        if (isset($submission['submitted']['NMIAcqRet']) && $submission['submitted']['NMIAcqRet']) {
            $lead['nmi_acqret'] = $submission['submitted']['NMIAcqRet'];
            $fields[] = 'nmi_acqret';
        }

        if (isset($submission['submitted']['MIRN AcqRet']) && $submission['submitted']['MIRN AcqRet']) {
            $lead['mirn_acqret'] = $submission['submitted']['MIRN AcqRet'];
            $fields[] = 'mirn_acqret';
        }

        if (isset($submission['submitted']['MSATSMIRNAddress']) && $submission['submitted']['MSATSMIRNAddress']) {
            $lead['msatsmirn_address'] = $submission['submitted']['MSATSMIRNAddress'];
            $fields[] = 'msatsmirn_address';
        }

        if (isset($submission['submitted']['NMI']) && $submission['submitted']['NMI']) {
            $lead['nmi_code'] = $submission['submitted']['NMI'];
            $fields[] = 'nmi_code';
        }

        if (isset($submission['submitted']['MIRNNumber']) && $submission['submitted']['MIRNNumber']) {
            $lead['mirn_number'] = $submission['submitted']['MIRNNumber'];
            $fields[] = 'mirn_number';
        }

        $lead['address_override'] = 'No';
        $fields[] = 'address_override';

        if (isset($submission['submitted']['plan_ranking']) && $submission['submitted']['plan_ranking']) {
            $lead['plan_ranking'] = $submission['submitted']['plan_ranking'];
            $fields[] = 'plan_ranking';
        }

        if (isset($submission['submitted']['product_code_elec _new']) && $submission['submitted']['product_code_elec _new']) {
            $lead['product_code_elec_new'] = $submission['submitted']['product_code_elec _new'];
            $fields[] = 'product_code_elec_new';
        }

        if (isset($submission['submitted']['campaign_code_elec']) && $submission['submitted']['campaign_code_elec']) {
            $lead['campaign_code_elec'] = $submission['submitted']['campaign_code_elec'];
            $fields[] = 'campaign_code_elec';
        }

        if (isset($submission['submitted']['product_code_gas _new']) && $submission['submitted']['product_code_gas _new']) {
            $lead['product_code_gas_new'] = $submission['submitted']['product_code_gas _new'];
            $fields[] = 'product_code_gas_new';
        }

        if (isset($submission['submitted']['campaign_code_gas']) && $submission['submitted']['campaign_code_gas']) {
            $lead['campaign_code_gas'] = $submission['submitted']['campaign_code_gas'];
            $fields[] = 'campaign_code_gas';
        }

        if (isset($submission['submitted']['CurrentRetailerElec']) && $submission['submitted']['CurrentRetailerElec']) {
            $lead['current_retailer_elec'] = $submission['submitted']['CurrentRetailerElec'];
            $fields[] = 'current_retailer_elec';
        }

        if (isset($submission['submitted']['CurrentRetailerGas']) && $submission['submitted']['CurrentRetailerGas']) {
            $lead['current_retailer_gas'] = $submission['submitted']['CurrentRetailerGas'];
            $fields[] = 'current_retailer_gas';
        }

        if (isset($submission['submitted']['NewElectricityRetailer']) && $submission['submitted']['NewElectricityRetailer']) {
            $lead['new_electricity_retailer'] = $submission['submitted']['NewElectricityRetailer'];
            $fields[] = 'new_electricity_retailer';
        }

        if (isset($submission['submitted']['ElectricityDistributor']) && $submission['submitted']['ElectricityDistributor']) {
            $lead['electricity_distributor'] = $submission['submitted']['ElectricityDistributor'];
            $fields[] = 'electricity_distributor';
        }

        if (isset($submission['submitted']['ElectricityProduct']) && $submission['submitted']['ElectricityProduct']) {
            $lead['electricity_product '] = $submission['submitted']['ElectricityProduct'];
            $fields[] = 'electricity_product';
        }

        if (isset($submission['submitted']['GasDistributor']) && $submission['submitted']['GasDistributor']) {
            $lead['gas_distributor'] = $submission['submitted']['GasDistributor'];
            $fields[] = 'gas_distributor';
        }

        if (isset($submission['submitted']['GasProduct']) && $submission['submitted']['GasProduct']) {
            $lead['gas_product'] = $submission['submitted']['GasProduct'];
            $fields[] = 'gas_product';
        }

        if (isset($submission['submitted']['ElectricityUsage']) && $submission['submitted']['ElectricityUsage']) {
            $lead['electricity_usage_kwhyear'] = $submission['submitted']['ElectricityUsage'];
            $fields[] = 'electricity_usage_kwhyear';
        }

        if (isset($submission['submitted']['GasAnnualConsumption']) && $submission['submitted']['GasAnnualConsumption']) {
            $lead['gas_annual_consumption'] = $submission['submitted']['GasAnnualConsumption'];
            $fields[] = 'gas_annual_consumption';
        }

        if (isset($submission['submitted']['MoveinOrTransfer']) && $submission['submitted']['MoveinOrTransfer']) {
            $lead['movein_or_transfer'] = $submission['submitted']['MoveinOrTransfer'];
            $fields[] = 'movein_or_transfer';
        }

        if (isset($submission['submitted']['ABN']) && $submission['submitted']['ABN']) {
            $lead['abn'] = $submission['submitted']['ABN'];
            $fields[] = 'abn';
        }

        if (isset($submission['submitted']['TradingName']) && $submission['submitted']['TradingName']) {
            $lead['trading_name'] = $submission['submitted']['TradingName'];
            $fields[] = 'trading_name';
        }

        if (isset($submission['submitted']['LegalName']) && $submission['submitted']['LegalName']) {
            $lead['legal_name'] = $submission['submitted']['LegalName'];
            $fields[] = 'legal_name';
        }

        if (isset($submission['submitted']['LumoEnergyCustomerAC']) && $submission['submitted']['LumoEnergyCustomerAC']) {
            $lead['lumo_energy_customer_ac_no'] = $submission['submitted']['LumoEnergyCustomerAC'];
            $fields[] = 'lumo_energy_customer_ac_no';
        }

        if (isset($submission['submitted']['ElectricityOn']) && $submission['submitted']['ElectricityOn']) {
            $lead['electricity_on'] = $submission['submitted']['ElectricityOn'];
            $fields[] = 'electricity_on';
        }

        if (isset($submission['submitted']['MSATSTariffCode']) && $submission['submitted']['MSATSTariffCode']) {
            $lead['msats_tariff_code'] = $submission['submitted']['MSATSTariffCode'];
            $fields[] = 'msats_tariff_code';
        }

        if (isset($submission['submitted']['contractlength']) && $submission['submitted']['contractlength']) {
            $lead['contract_length'] = $submission['submitted']['contractlength'];
            $fields[] = 'contract_length';
        }

        if (isset($submission['submitted']['MomentumMeterType']) && $submission['submitted']['MomentumMeterType']) {
            $lead['momentum_meter_type'] = $submission['submitted']['MomentumMeterType'];
            $fields[] = 'momentum_meter_type';
        }

        if (isset($submission['submitted']['PropertyType']) && $submission['submitted']['PropertyType']) {
            $lead['property_type'] = $submission['submitted']['PropertyType'];
            $fields[] = 'property_type';
        }

        if (isset($submission['submitted']['SolarPanels']) && $submission['submitted']['SolarPanels']) {
            $lead['solar'] = $submission['submitted']['SolarPanels'];
            $fields[] = 'solar';
        }

        if (isset($submission['submitted']['POC']) && $submission['submitted']['POC']) {
            $lead['poc_opt_in'] = $submission['submitted']['POC'];
            $fields[] = 'poc_opt_in';
        }

        if (isset($submission['submitted']['POC']) && $submission['submitted']['POC']) {
            $lead['poc_opt_in'] = $submission['submitted']['POC'];
            $fields[] = 'poc_opt_in';
        }

        if (isset($submission['submitted']['title']) && $submission['submitted']['title']) {
            $lead['title'] = $submission['submitted']['title'];
            $fields[] = 'title';
        }

        if (isset($submission['submitted']['FirstName']) && $submission['submitted']['FirstName']) {
            $lead['first_name'] = $submission['submitted']['FirstName'];
            $fields[] = 'first_name';
        }

        if (isset($submission['submitted']['surname']) && $submission['submitted']['surname']) {
            $lead['surname'] = $submission['submitted']['surname'];
            $fields[] = 'surname';
        }

        if (isset($submission['submitted']['DateOfBirth']) && $submission['submitted']['DateOfBirth']) {
            $lead['date_of_birth'] = $lead['date_of_birth_date'] = $submission['submitted']['DateOfBirthDate'];
            $fields[] = 'date_of_birth';
            $fields[] = 'date_of_birth_date';
        }

        if (isset($submission['submitted']['MobileNumber']) && $submission['submitted']['MobileNumber']) {
            $lead['mobile_number'] = $submission['submitted']['MobileNumber'];
            $fields[] = 'mobile_number';
        }

        if (isset($submission['submitted']['HomePhone']) && $submission['submitted']['HomePhone']) {
            $lead['home_phone'] = $submission['submitted']['HomePhone'];
            $fields[] = 'home_phone';
        }

        if (isset($submission['submitted']['WorkNumber']) && $submission['submitted']['WorkNumber']) {
            $lead['work_number'] = $submission['submitted']['WorkNumber'];
            $fields[] = 'work_number';
        }

        if (isset($submission['submitted']['company_position']) && $submission['submitted']['company_position']) {
            $lead['company_position'] = $submission['submitted']['company_position'];
            $fields[] = 'company_position';
        }

        if (isset($submission['submitted']['SecondaryContactTitle']) && $submission['submitted']['SecondaryContactTitle']) {
            $lead['secondary_contact_title'] = $submission['submitted']['SecondaryContactTitle'];
            $fields[] = 'secondary_contact_title';
        }

        if (isset($submission['submitted']['SecondaryContactFirstName']) && $submission['submitted']['SecondaryContactFirstName']) {
            $lead['secondary_contact_first_name'] = $submission['submitted']['SecondaryContactFirstName'];
            $fields[] = 'secondary_contact_first_name';
        }

        if (isset($submission['submitted']['SecondaryContactSurname']) && $submission['submitted']['SecondaryContactSurname']) {
            $lead['secondary_contact_surname'] = $submission['submitted']['SecondaryContactSurname'];
            $fields[] = 'secondary_contact_surname';
        }

        if (isset($submission['submitted']['Secondary_Contact_DOB']) && $submission['submitted']['Secondary_Contact_DOB']) {
            $lead['secondary_contact_dob'] = $submission['submitted']['Secondary_Contact_DOB'];
            $fields[] = 'secondary_contact_dob';
        }

        if (isset($submission['submitted']['SecondaryMobileNumber']) && $submission['submitted']['SecondaryMobileNumber']) {
            $lead['secondary_mobile_number'] = $submission['submitted']['SecondaryMobileNumber'];
            $fields[] = 'secondary_mobile_number';
        }

        if (isset($submission['submitted']['SecondaryEmail']) && $submission['submitted']['SecondaryEmail']) {
            $lead['secondary_email'] = $submission['submitted']['SecondaryEmail'];
            $fields[] = 'secondary_email';
        }

        if (isset($submission['submitted']['DocumentType']) && $submission['submitted']['DocumentType']) {
            $lead['document_type'] = $submission['submitted']['DocumentType'];
            $fields[] = 'document_type';
        }

        if (isset($submission['submitted']['DocumentIDNumber']) && $submission['submitted']['DocumentIDNumber']) {
            $lead['document_id_number'] = $submission['submitted']['DocumentIDNumber'];
            $fields[] = 'document_id_number';
        }

        if (isset($submission['submitted']['DocumentExpiry']) && $submission['submitted']['DocumentExpiry']) {
            $lead['document_expiry'] = $submission['submitted']['DocumentExpiry'];
            $fields[] = 'document_expiry';
        }

        if (isset($submission['submitted']['DocumentExpiry1']) && $submission['submitted']['DocumentExpiry1']) {
            $lead['document_expiry_1'] = $submission['submitted']['DocumentExpiry1'];
            $fields[] = 'document_expiry_1';
        }

        if (isset($submission['submitted']['DLState']) && $submission['submitted']['DLState']) {
            $lead['dl_state'] = $submission['submitted']['DLState'];
            $fields[] = 'dl_state';
        }

        if (isset($submission['submitted']['DocumentCountryofIssue']) && $submission['submitted']['DocumentCountryofIssue']) {
            $lead['document_country_of_issue'] = $submission['submitted']['DocumentCountryofIssue'];
            $fields[] = 'document_country_of_issue';
        }

        if (isset($submission['submitted']['SecretQuestion']) && $submission['submitted']['SecretQuestion']) {
            $lead['secret_question'] = $submission['submitted']['SecretQuestion'];
            $fields[] = 'secret_question';
        }

        if (isset($submission['submitted']['SecretAnswer']) && $submission['submitted']['SecretAnswer']) {
            $lead['secret_answer'] = $submission['submitted']['SecretAnswer'];
            $fields[] = 'secret_answer';
        }

        if (isset($submission['submitted']['LifeSupportActive']) && $submission['submitted']['LifeSupportActive']) {
            $lead['life_support'] = $submission['submitted']['LifeSupportActive'];
            $fields[] = 'life_support';
        }

        if (isset($submission['submitted']['ConcessionCardIssuer']) && $submission['submitted']['ConcessionCardIssuer']) {
            $lead['concession_card_issuer'] = $submission['submitted']['ConcessionCardIssuer'];
            $fields[] = 'concession_card_issuer';
        }

        if (isset($submission['submitted']['ConcessionCardType']) && $submission['submitted']['ConcessionCardType']) {
            $lead['concession_card_type'] = $submission['submitted']['ConcessionCardType'];
            $fields[] = 'concession_card_type';
        }

        if (isset($submission['submitted']['concession_title']) && $submission['submitted']['concession_title']) {
            $lead['concession_title'] = $submission['submitted']['concession_title'];
            $fields[] = 'concession_title';
        }

        if (isset($submission['submitted']['concession_first_name']) && $submission['submitted']['concession_first_name']) {
            $lead['concession_first_name'] = $submission['submitted']['concession_first_name'];
            $fields[] = 'concession_first_name';
        }

        if (isset($submission['submitted']['concession_middle_name']) && $submission['submitted']['concession_middle_name']) {
            $lead['concession_middle_name'] = $submission['submitted']['ConcessionMiddleName'];
            $fields[] = 'concession_middle_name';
        }

        if (isset($submission['submitted']['concession_last_name']) && $submission['submitted']['concession_last_name']) {
            $lead['concession_last_name'] = $submission['submitted']['concession_last_name'];
            $fields[] = 'concession_last_name';
        }

        if (isset($submission['submitted']['NameonConcessionCard']) && $submission['submitted']['NameonConcessionCard']) {
            $lead['name_on_concession_card'] = $submission['submitted']['NameonConcessionCard'];
            $fields[] = 'name_on_concession_card';
        }

        if (isset($submission['submitted']['ConcessionCardNumber']) && $submission['submitted']['ConcessionCardNumber']) {
            $lead['concession_card_number'] = $submission['submitted']['ConcessionCardNumber'];
            $fields[] = 'concession_card_number';
        }

        if (isset($submission['submitted']['ConcessionCardStartDate']) && $submission['submitted']['ConcessionCardStartDate']) {
            $lead['concession_card_start_date'] = $submission['submitted']['ConcessionCardStartDate'];
            $fields[] = 'concession_card_start_date';
        }

        if (isset($submission['submitted']['ConcessionCardExpiryDate']) && $submission['submitted']['ConcessionCardExpiryDate']) {
            $lead['concession_card_end_date'] = $submission['submitted']['ConcessionCardExpiryDate'];
            $fields[] = 'concession_card_end_date';
        }

        if (isset($submission['submitted']['ConcessionHasMS']) && $submission['submitted']['ConcessionHasMS']) {
            $lead['concession_has_ms'] = $submission['submitted']['ConcessionHasMS'];
            $fields[] = 'concession_has_ms';
        }

        if (isset($submission['submitted']['ConcessionInGroupHome']) && $submission['submitted']['ConcessionInGroupHome']) {
            $lead['concession_in_group_home'] = $submission['submitted']['ConcessionInGroupHome'];
            $fields[] = 'concession_in_group_home';
        }

        if (isset($submission['submitted']['life_support_machine_type']) && $submission['submitted']['life_support_machine_type']) {
            $lead['life_support_machine_type'] = $submission['submitted']['life_support_machine_type'];
            $fields[] = 'life_support_machine_type';
        }

        if (isset($submission['submitted']['life_support_title']) && $submission['submitted']['life_support_title']) {
            $lead['life_support_title'] = $submission['submitted']['life_support_title'];
            $fields[] = 'life_support_title';
        }

        if (isset($submission['submitted']['life_support_first_name']) && $submission['submitted']['life_support_first_name']) {
            $lead['life_support_first_name'] = $submission['submitted']['life_support_first_name'];
            $fields[] = 'life_support_first_name';
        }

        if (isset($submission['submitted']['life_support_middle_name']) && $submission['submitted']['life_support_middle_name']) {
            $lead['life_support_middle_name'] = $submission['submitted']['life_support_middle_name'];
            $fields[] = 'life_support_middle_name';
        }

        if (isset($submission['submitted']['life_support_last_name']) && $submission['submitted']['life_support_last_name']) {
            $lead['life_support_last_name'] = $submission['submitted']['life_support_last_name'];
            $fields[] = 'life_support_last_name';
        }

        if (isset($submission['submitted']['life_support_username']) && $submission['submitted']['life_support_username']) {
            $lead['life_support_username'] = $submission['submitted']['life_support_username'];
            $fields[] = 'life_support_username';
        }

        if (isset($submission['submitted']['life_support_machine_type_other']) && $submission['submitted']['life_support_machine_type_other']) {
            $lead['life_support_machine_type_other'] = $submission['submitted']['life_support_machine_type_other'];
            $fields[] = 'life_support_machine_type_other';
        }

        if (isset($submission['submitted']['life_support_fuel_type']) && $submission['submitted']['life_support_fuel_type']) {
            $lead['life_support_fuel_type'] = $submission['submitted']['life_support_fuel_type'];
            $fields[] = 'life_support_fuel_type';
        }

        if (isset($submission['submitted']['BusinessType']) && $submission['submitted']['BusinessType']) {
            $lead['business_type'] = $submission['submitted']['BusinessType'];
            $fields[] = 'business_type';
        }

        if (isset($submission['submitted']['CompanyIndustry']) && $submission['submitted']['CompanyIndustry']) {
            $lead['company_industry'] = $submission['submitted']['CompanyIndustry'];
            $fields[] = 'company_industry';
        }

        if (isset($submission['submitted']['BillingAddressDifferent']) && $submission['submitted']['BillingAddressDifferent']) {
            $lead['billing_address_different'] = $submission['submitted']['BillingAddressDifferent'];
            $fields[] = 'billing_address_different';
        }

        if (isset($submission['submitted']['Addresshasnostreetnumber_']) && $submission['submitted']['Addresshasnostreetnumber_']) {
            $lead['address_has_no_street_number_'] = $submission['submitted']['Addresshasnostreetnumber_'];
            $fields[] = 'address_has_no_street_number_';
        }

        if (isset($submission['submitted']['POBOX']) && $submission['submitted']['POBOX']) {
            $lead['pobox'] = $submission['submitted']['POBOX'];
            $fields[] = 'pobox';
        }

        if (isset($submission['submitted']['UnitBilling']) && $submission['submitted']['UnitBilling']) {
            $lead['unit_billing '] = $submission['submitted']['UnitBilling'];
            $fields[] = 'unit_billing';
        }

        if (isset($submission['submitted']['UnitTypeBilling']) && $submission['submitted']['UnitTypeBilling']) {
            $lead['unit_type_billing'] = $submission['submitted']['UnitTypeBilling'];
            $fields[] = 'unit_type_billing';
        }

        if (isset($submission['submitted']['LotBilling']) && $submission['submitted']['LotBilling']) {
            $lead['lot_billing'] = $submission['submitted']['LotBilling'];
            $fields[] = 'lot_billing';
        }

        if (isset($submission['submitted']['FloorBilling']) && $submission['submitted']['FloorBilling']) {
            $lead['floor_billing'] = $submission['submitted']['FloorBilling'];
            $fields[] = 'floor_billing';
        }

        if (isset($submission['submitted']['FloorTypeBilling']) && $submission['submitted']['FloorTypeBilling']) {
            $lead['floor_type_billing'] = $submission['submitted']['FloorTypeBilling'];
            $fields[] = 'floor_type_billing';
        }

        if (isset($submission['submitted']['BuildingNameBilling']) && $submission['submitted']['BuildingNameBilling']) {
            $lead['building_name_billing'] = $submission['submitted']['BuildingNameBilling'];
            $fields[] = 'building_name_billing';
        }

        if (isset($submission['submitted']['StreetNumberBilling']) && $submission['submitted']['StreetNumberBilling']) {
            $lead['street_number_billing'] = $submission['submitted']['StreetNumberBilling'];
            $fields[] = 'street_number_billing';
        }

        if (isset($submission['submitted']['StNoSuffixBilling']) && $submission['submitted']['StNoSuffixBilling']) {
            $lead['st_no_suffix_billing'] = $submission['submitted']['StNoSuffixBilling'];
            $fields[] = 'st_no_suffix_billing';
        }

        if (isset($submission['submitted']['StreetNameBilling']) && $submission['submitted']['StreetNameBilling']) {
            $lead['street_name_billing'] = $submission['submitted']['StreetNameBilling'];
            $fields[] = 'street_name_billing';
        }

        if (isset($submission['submitted']['StNameSuffixBilling']) && $submission['submitted']['StNameSuffixBilling']) {
            $lead['st_name_suffix_billing'] = $submission['submitted']['StNameSuffixBilling'];
            $fields[] = 'st_name_suffix_billing';
        }

        if (isset($submission['submitted']['StreetTypeBilling']) && $submission['submitted']['StreetTypeBilling']) {
            $lead['street_type_billing'] = $submission['submitted']['StreetTypeBilling'];
            $fields[] = 'street_type_billing';
        }

        if (isset($submission['submitted']['SuburbBilling']) && $submission['submitted']['SuburbBilling']) {
            $lead['suburb_billing'] = $submission['submitted']['SuburbBilling'];
            $fields[] = 'suburb_billing';
        }

        if (isset($submission['submitted']['PostcodeBilling']) && $submission['submitted']['PostcodeBilling']) {
            $lead['postcode_billing'] = $submission['submitted']['PostcodeBilling'];
            $fields[] = 'postcode_billing';
        }

        if (isset($submission['submitted']['StateBilling']) && $submission['submitted']['StateBilling']) {
            $lead['state_billing'] = $submission['submitted']['StateBilling'];
            $fields[] = 'state_billing';
        }

        if (isset($submission['submitted']['eBill']) && $submission['submitted']['eBill']) {
            $lead['register_for_ebill'] = $submission['submitted']['eBill'];
            $fields[] = 'register_for_ebill';
        }

        if (isset($submission['submitted']['ElectronicWelcomePack']) && $submission['submitted']['ElectronicWelcomePack']) {
            $lead['electronic_welcome_pack'] = $submission['submitted']['ElectronicWelcomePack'];
            $fields[] = 'electronic_welcome_pack';
        }

        if (isset($submission['submitted']['MarketingOptOut']) && $submission['submitted']['MarketingOptOut']) {
            $lead['marketing_opt_out'] = $submission['submitted']['MarketingOptOut'];
            $fields[] = 'marketing_opt_out';
        }

        if (isset($submission['submitted']['ElectronicMarketingInfo']) && $submission['submitted']['ElectronicMarketingInfo']) {
            $lead['electronic_marketing_info'] = $submission['submitted']['ElectronicMarketingInfo'];
            $fields[] = 'electronic_marketing_info';
        }

        if (isset($submission['submitted']['DirectDebitRequired']) && $submission['submitted']['DirectDebitRequired']) {
            $lead['direct_debit_required'] = $submission['submitted']['DirectDebitRequired'];
            $fields[] = 'direct_debit_required';
        }

        if (isset($submission['submitted']['BatteryStorageEOI']) && $submission['submitted']['BatteryStorageEOI']) {
            $lead['battery_storage_eoi'] = $submission['submitted']['BatteryStorageEOI'];
            $fields[] = 'battery_storage_eoi';
        }

        if (isset($submission['submitted']['BatteryStorageSolarEOI']) && $submission['submitted']['BatteryStorageSolarEOI']) {
            $lead['battery_storage_solar_eoi'] = $submission['submitted']['BatteryStorageSolarEOI'];
            $fields[] = 'battery_storage_solar_eoi';
        }

        if (isset($submission['submitted']['preferred_contact_method_2nd contact']) && $submission['submitted']['preferred_contact_method_2nd contact']) {
            $lead['preferred_contact_method_2nd_contact'] = $submission['submitted']['preferred_contact_method_2nd contact'];
            $fields[] = 'preferred_contact_method_2nd_contact';
        }

        if (isset($submission['submitted']['MoveinDate']) && $submission['submitted']['MoveinDate']) {
            $lead['movein_date'] = $submission['submitted']['MoveinDate'];
            $fields[] = 'movein_date';
        }

        if (isset($submission['submitted']['ConnectionDate']) && $submission['submitted']['ConnectionDate']) {
            $lead['connection_date'] = $submission['submitted']['ConnectionDate'];
            $fields[] = 'connection_date';
        }

        if (isset($submission['submitted']['CustomerMoveinFeeAdvised']) && $submission['submitted']['CustomerMoveinFeeAdvised']) {
            $lead['customer_movein_fee_advised'] = $submission['submitted']['CustomerMoveinFeeAdvised'];
            $fields[] = 'customer_movein_fee_advised';
        }

        if (isset($submission['submitted']['VisualInspectionDetailsQLDRequired']) && $submission['submitted']['VisualInspectionDetailsQLDRequired']) {
            $lead['visual_inspection_details_qld_required'] = $submission['submitted']['VisualInspectionDetailsQLDRequired'];
            $fields[] = 'visual_inspection_details_qld_required';
        }

        if (isset($submission['submitted']['Elec_On']) && $submission['submitted']['Elec_On']) {
            $lead['electricity_on'] = $submission['submitted']['Elec_On'];
            $fields[] = 'electricity_on';
        }

        if (isset($submission['submitted']['Electrical_works']) && $submission['submitted']['Electrical_works']) {
            $lead['electrical_works_completed_since_disconnection'] = $submission['submitted']['Electrical_works'];
            $fields[] = 'electrical_works_completed_since_disconnection';
        }

        if (isset($submission['submitted']['ElectricityMeterLocation']) && $submission['submitted']['ElectricityMeterLocation']) {
            $lead['electricity_meter_location'] = $submission['submitted']['ElectricityMeterLocation'];
            $fields[] = 'electricity_meter_location';
        }

        if (isset($submission['submitted']['GasMeterLocation']) && $submission['submitted']['GasMeterLocation']) {
            $lead['gas_meter_location'] = $submission['submitted']['GasMeterLocation'];
            $fields[] = 'gas_meter_location';
        }

        if (isset($submission['submitted']['ElecConnectionFeeType']) && $submission['submitted']['ElecConnectionFeeType']) {
            $lead['elec_connection_fee_type'] = $submission['submitted']['ElecConnectionFeeType'];
            $fields[] = 'elec_connection_fee_type';
        }

        if (isset($submission['submitted']['GasConnectionFeeType']) && $submission['submitted']['GasConnectionFeeType']) {
            $lead['gas_connection_fee_type'] = $submission['submitted']['GasConnectionFeeType'];
            $fields[] = 'gas_connection_fee_type';
        }

        if (isset($submission['submitted']['AdvisedMainSwitchMustBeTurnedOff']) && $submission['submitted']['AdvisedMainSwitchMustBeTurnedOff']) {
            $lead['advised_main_switch_must_be_turned_off'] = $submission['submitted']['AdvisedMainSwitchMustBeTurnedOff'];
            $fields[] = 'advised_main_switch_must_be_turned_off';
        }

        if (isset($submission['submitted']['MainSwitchOff']) && $submission['submitted']['MainSwitchOff']) {
            $lead['main_switch_off'] = $submission['submitted']['MainSwitchOff'];
            $fields[] = 'main_switch_off';
        }

        if (isset($submission['submitted']['ConnectionDogPremises']) && $submission['submitted']['ConnectionDogPremises']) {
            $lead['connection_dog_premises'] = $submission['submitted']['ConnectionDogPremises'];
            $fields[] = 'connection_dog_premises';
        }

        if (isset($submission['submitted']['ConnectionMeterHazard']) && $submission['submitted']['ConnectionMeterHazard']) {
            $lead['connection_meter_hazard'] = $submission['submitted']['ConnectionMeterHazard'];
            $fields[] = 'connection_meter_hazard';
        }

        if (isset($submission['submitted']['AnyHazardsAccessingMeter']) && $submission['submitted']['AnyHazardsAccessingMeter']) {
            $lead['any_hazards_accessing_meter'] = $submission['submitted']['AnyHazardsAccessingMeter'];
            $fields[] = 'any_hazards_accessing_meter';
        }

        if (isset($submission['submitted']['AccessRequirements']) && $submission['submitted']['AccessRequirements']) {
            $lead['access_requirements'] = $submission['submitted']['AccessRequirements'];
            $fields[] = 'access_requirements';
        }

        if (isset($submission['submitted']['SpecialInstructions']) && $submission['submitted']['SpecialInstructions']) {
            $lead['special_instructions_for_access'] = $submission['submitted']['SpecialInstructions'];
            $fields[] = 'special_instructions_for_access';
        }

        if (isset($submission['submitted']['PreviousStreetAddress']) && $submission['submitted']['PreviousStreetAddress']) {
            $lead['previous_street_address'] = $submission['submitted']['PreviousStreetAddress'];
            $fields[] = 'previous_street_address';
        }

        if (isset($submission['submitted']['PreviousSuburb']) && $submission['submitted']['PreviousSuburb']) {
            $lead['previous_suburb'] = $submission['submitted']['PreviousSuburb'];
            $fields[] = 'previous_suburb';
        }

        if (isset($submission['submitted']['PreviousState']) && $submission['submitted']['PreviousState']) {
            $lead['previous_state'] = $submission['submitted']['PreviousState'];
            $fields[] = 'previous_state';
        }

        if (isset($submission['submitted']['PreviousPostcode']) && $submission['submitted']['PreviousPostcode']) {
            $lead['previous_postcode'] = $submission['submitted']['PreviousPostcode'];
            $fields[] = 'previous_postcode';
        }

        if (isset($submission['submitted']['SalesRepName']) && $submission['submitted']['SalesRepName']) {
            $lead['sales_rep_name'] = $submission['submitted']['SalesRepName'];
            $fields[] = 'sales_rep_name';
        }

        if (isset($submission['submitted']['SaleCompletionDate']) && $submission['submitted']['SaleCompletionDate']) {
            $lead['sale_completion_date'] = $submission['submitted']['SaleCompletionDate'];
            $fields[] = 'sale_completion_date';
        }

        if (isset($submission['submitted']['SaleDateTime']) && $submission['submitted']['SaleDateTime']) {
            $lead['sale_completion_time'] = $submission['submitted']['SaleDateTime'];
            $fields[] = 'sale_completion_time';
        }

        if (isset($submission['submitted']['MomentumFile']) && $submission['submitted']['MomentumFile']) {
            $lead['momentum_file'] = $submission['submitted']['MomentumFile'];
            $fields[] = 'momentum_file';
        }

        if (isset($submission['submitted']['VoiceVerificationNumber']) && $submission['submitted']['VoiceVerificationNumber']) {
            $lead['voice_verification_number'] = $submission['submitted']['VoiceVerificationNumber'];
            $fields[] = 'voice_verification_number';
        }

        if (isset($submission['submitted']['PowershopToken']) && $submission['submitted']['PowershopToken']) {
            $lead['powershop_token'] = $submission['submitted']['PowershopToken'];
            $fields[] = 'powershop_token';
        }

        if (isset($submission['submitted']['Purchase_Reason']) && $submission['submitted']['Purchase_Reason']) {
            $lead['purchase_reason'] = $submission['submitted']['Purchase_Reason'];
            $fields[] = 'purchase_reason';
        }

        if (isset($submission['submitted']['LeadType']) && $submission['submitted']['LeadType']) {
            $lead['lead_type'] = $submission['submitted']['LeadType'];
            $fields[] = 'lead_type';
        }

        if (isset($submission['submitted']['AddresshasnostreetnumberSupply']) && $submission['submitted']['AddresshasnostreetnumberSupply']) {
            $lead['address_has_no_street_number'] = $submission['submitted']['AddresshasnostreetnumberSupply'];
            $fields[] = 'address_has_no_street_number';
        }

        if (isset($submission['submitted']['UnitSupply']) && $submission['submitted']['UnitSupply']) {
            $lead['unit_supply'] = $submission['submitted']['UnitSupply'];
            $fields[] = 'unit_supply';
        }

        if (isset($submission['submitted']['UnitTypeSupply']) && $submission['submitted']['UnitTypeSupply']) {
            $lead['unit_type_supply'] = $submission['submitted']['UnitTypeSupply'];
            $fields[] = 'unit_type_supply';
        }

        if (isset($submission['submitted']['LotSupply']) && $submission['submitted']['LotSupply']) {
            $lead['lot_supply'] = $submission['submitted']['LotSupply'];
            $fields[] = 'lot_supply';
        }

        if (isset($submission['submitted']['FloorSupply']) && $submission['submitted']['FloorSupply']) {
            $lead['floor_supply'] = $submission['submitted']['FloorSupply'];
            $fields[] = 'floor_supply';
        }

        if (isset($submission['submitted']['FloorTypeSupply']) && $submission['submitted']['FloorTypeSupply']) {
            $lead['floor_type_supply'] = $submission['submitted']['FloorTypeSupply'];
            $fields[] = 'floor_type_supply';
        }

        if (isset($submission['submitted']['BuildingName']) && $submission['submitted']['BuildingName']) {
            $lead['building_name'] = $submission['submitted']['BuildingName'];
            $fields[] = 'building_name';
        }

        if (isset($submission['submitted']['StreetNumberSupply']) && $submission['submitted']['StreetNumberSupply']) {
            $lead['street_number_supply'] = $submission['submitted']['StreetNumberSupply'];
            $fields[] = 'street_number_supply';
        }

        if (isset($submission['submitted']['StNoSuffixSupply']) && $submission['submitted']['StNoSuffixSupply']) {
            $lead['st_no_suffix_supply'] = $submission['submitted']['StNoSuffixSupply'];
            $fields[] = 'st_no_suffix_supply';
        }

        if (isset($submission['submitted']['StreetNameSupply']) && $submission['submitted']['StreetNameSupply']) {
            $lead['street_name_supply'] = $submission['submitted']['StreetNameSupply'];
            $fields[] = 'street_name_supply';
        }

        if (isset($submission['submitted']['StNameSuffixSupply']) && $submission['submitted']['StNameSuffixSupply']) {
            $lead['st_name_suffix_supply'] = $submission['submitted']['StNameSuffixSupply'];
            $fields[] = 'st_name_suffix_supply';
        }

        if (isset($submission['submitted']['StreetTypeSupply']) && $submission['submitted']['StreetTypeSupply']) {
            $lead['street_type_supply'] = $submission['submitted']['StreetTypeSupply'];
            $fields[] = 'street_type_supply';
        }

        if (isset($submission['submitted']['SuburbSupply']) && $submission['submitted']['SuburbSupply']) {
            $lead['suburb_supply'] = $submission['submitted']['SuburbSupply'];
            $fields[] = 'suburb_supply';
        }

        if (isset($submission['submitted']['PostcodeSupply']) && $submission['submitted']['PostcodeSupply']) {
            $lead['postcode_supply'] = $submission['submitted']['PostcodeSupply'];
            $fields[] = 'postcode_supply';
        }

        if (isset($submission['submitted']['StateSupply']) && $submission['submitted']['StateSupply']) {
            $lead['state_supply'] = $submission['submitted']['StateSupply'];
            $fields[] = 'state_supply';
        }

        if (isset($submission['submitted']['StateSupply']) && $submission['submitted']['StateSupply']) {
            $lead['state_supply'] = $submission['submitted']['StateSupply'];
            $fields[] = 'state_supply';
        }

        if (isset($submission['submitted']['TenantOwner']) && $submission['submitted']['TenantOwner']) {
            $lead['tenant_owner'] = $submission['submitted']['TenantOwner'];
            $fields[] = 'tenant_owner';
        }

        if (isset($submission['submitted']['AGLSaleType']) && $submission['submitted']['AGLSaleType']) {
            $lead['agl_sale_type'] = $submission['submitted']['AGLSaleType'];
            $fields[] = 'agl_sale_type';
        }

        if (isset($submission['submitted']['MIRNAddressDifferent']) && $submission['submitted']['MIRNAddressDifferent']) {
            $lead['mirn_address_different'] = $submission['submitted']['MIRNAddressDifferent'];
            $fields[] = 'mirn_address_different';
        }

        if (isset($submission['submitted']['MSATSAddressDifferent']) && $submission['submitted']['MSATSAddressDifferent']) {
            $lead['msats_address_different'] = $submission['submitted']['MSATSAddressDifferent'];
            $fields[] = 'msats_address_different';
        }

        if (isset($submission['submitted']['AddresshasnostreetnumberMIRN']) && $submission['submitted']['AddresshasnostreetnumberMIRN']) {
            $lead['address_has_no_street_numbermirn'] = $submission['submitted']['AddresshasnostreetnumberMIRN'];
            $fields[] = 'address_has_no_street_numbermirn';
        }

        if (isset($submission['submitted']['UnitMIRN']) && $submission['submitted']['UnitMIRN']) {
            $lead['unit_mirn'] = $submission['submitted']['UnitMIRN'];
            $fields[] = 'unit_mirn';
        }

        if (isset($submission['submitted']['UnitTypeMIRN']) && $submission['submitted']['UnitTypeMIRN']) {
            $lead['unit_type_mirn'] = $submission['submitted']['UnitTypeMIRN'];
            $fields[] = 'unit_type_mirn';
        }

        if (isset($submission['submitted']['LotMIRN']) && $submission['submitted']['LotMIRN']) {
            $lead['lot_mirn'] = $submission['submitted']['LotMIRN'];
            $fields[] = 'lot_mirn';
        }

        if (isset($submission['submitted']['FloorMIRN']) && $submission['submitted']['FloorMIRN']) {
            $lead['floor_mirn'] = $submission['submitted']['FloorMIRN'];
            $fields[] = 'floor_mirn';
        }

        if (isset($submission['submitted']['FloorTypeMIRN']) && $submission['submitted']['FloorTypeMIRN']) {
            $lead['floor_type_mirn'] = $submission['submitted']['FloorTypeMIRN'];
            $fields[] = 'floor_type_mirn';
        }

        if (isset($submission['submitted']['BuildingNameMIRN']) && $submission['submitted']['BuildingNameMIRN']) {
            $lead['building_name_mirn'] = $submission['submitted']['BuildingNameMIRN'];
            $fields[] = 'building_name_mirn';
        }

        if (isset($submission['submitted']['StreetNumberMIRN']) && $submission['submitted']['StreetNumberMIRN']) {
            $lead['street_number_mirn'] = $submission['submitted']['StreetNumberMIRN'];
            $fields[] = 'street_number_mirn';
        }

        if (isset($submission['submitted']['StNoSuffixMIRN']) && $submission['submitted']['StNoSuffixMIRN']) {
            $lead['st_no_suffix_mirn'] = $submission['submitted']['StNoSuffixMIRN'];
            $fields[] = 'st_no_suffix_mirn';
        }

        if (isset($submission['submitted']['StreetNameMIRN']) && $submission['submitted']['StreetNameMIRN']) {
            $lead['street_name_mirn'] = $submission['submitted']['StreetNameMIRN'];
            $fields[] = 'street_name_mirn';
        }

        if (isset($submission['submitted']['StNameSuffixMIRN']) && $submission['submitted']['StNameSuffixMIRN']) {
            $lead['st_name_suffix_mirn'] = $submission['submitted']['StNameSuffixMIRN'];
            $fields[] = 'st_name_suffix_mirn';
        }

        if (isset($submission['submitted']['StreetTypeMIRN']) && $submission['submitted']['StreetTypeMIRN']) {
            $lead['street_type_mirn'] = $submission['submitted']['StreetTypeMIRN'];
            $fields[] = 'street_type_mirn';
        }

        if (isset($submission['submitted']['SuburbMIRN']) && $submission['submitted']['SuburbMIRN']) {
            $lead['suburb_mirn'] = $submission['submitted']['SuburbMIRN'];
            $fields[] = 'suburb_mirn';
        }

        if (isset($submission['submitted']['PostcodeMIRN']) && $submission['submitted']['PostcodeMIRN']) {
            $lead['postcode_mirn'] = $submission['submitted']['PostcodeMIRN'];
            $fields[] = 'postcode_mirn';
        }

        if (isset($submission['submitted']['StateMIRN']) && $submission['submitted']['StateMIRN']) {
            $lead['state_mirn'] = $submission['submitted']['StateMIRN'];
            $fields[] = 'state_mirn';
        }

        if (isset($submission['submitted']['AddresshasnostreetnumberMSATS']) && $submission['submitted']['AddresshasnostreetnumberMSATS']) {
            $lead['address_has_no_street_numbermsats'] = $submission['submitted']['AddresshasnostreetnumberMSATS'];
            $fields[] = 'address_has_no_street_numbermsats';
        }

        if (isset($submission['submitted']['UnitMSATS']) && $submission['submitted']['UnitMSATS']) {
            $lead['unitflatshop_number_msats'] = $submission['submitted']['UnitMSATS'];
            $fields[] = 'unitflatshop_number_msats';
        }

        if (isset($submission['submitted']['UnitTypeMSATS']) && $submission['submitted']['UnitTypeMSATS']) {
            $lead['unit_type_msats'] = $submission['submitted']['UnitTypeMSATS'];
            $fields[] = 'unit_type_msats';
        }

        if (isset($submission['submitted']['LotMSATS']) && $submission['submitted']['LotMSATS']) {
            $lead['lot_msats'] = $submission['submitted']['LotMSATS'];
            $fields[] = 'lot_msats';
        }

        if (isset($submission['submitted']['FloorMSATS']) && $submission['submitted']['FloorMSATS']) {
            $lead['floor_msats'] = $submission['submitted']['FloorMSATS'];
            $fields[] = 'floor_msats';
        }

        if (isset($submission['submitted']['FloorTypeMSATS']) && $submission['submitted']['FloorTypeMSATS']) {
            $lead['floor_type_msats'] = $submission['submitted']['FloorTypeMSATS'];
            $fields[] = 'floor_type_msats';
        }

        if (isset($submission['submitted']['BuildingNameMSATS']) && $submission['submitted']['BuildingNameMSATS']) {
            $lead['building_name_msats'] = $submission['submitted']['BuildingNameMSATS'];
            $fields[] = 'building_name_msats';
        }

        if (isset($submission['submitted']['StreetNumberMSATS']) && $submission['submitted']['StreetNumberMSATS']) {
            $lead['street_number_msats'] = $submission['submitted']['StreetNumberMSATS'];
            $fields[] = 'street_number_msats';
        }

        if (isset($submission['submitted']['StNoSuffixMSATS']) && $submission['submitted']['StNoSuffixMSATS']) {
            $lead['st_no_suffix_msats'] = $submission['submitted']['StNoSuffixMSATS'];
            $fields[] = 'st_no_suffix_msats';
        }

        if (isset($submission['submitted']['StreetNameMSATS']) && $submission['submitted']['StreetNameMSATS']) {
            $lead['street_name_msats'] = $submission['submitted']['StreetNameMSATS'];
            $fields[] = 'street_name_msats';
        }

        if (isset($submission['submitted']['StNameSuffixMSATS']) && $submission['submitted']['StNameSuffixMSATS']) {
            $lead['st_name_suffix_msats'] = $submission['submitted']['StNameSuffixMSATS'];
            $fields[] = 'st_name_suffix_msats';
        }

        if (isset($submission['submitted']['StreetTypeMSATS']) && $submission['submitted']['StreetTypeMSATS']) {
            $lead['street_type_msats'] = $submission['submitted']['StreetTypeMSATS'];
            $fields[] = 'street_type_msats';
        }

        if (isset($submission['submitted']['SuburbMSATS']) && $submission['submitted']['SuburbMSATS']) {
            $lead['suburb_msats'] = $submission['submitted']['SuburbMSATS'];
            $fields[] = 'suburb_msats';
        }

        if (isset($submission['submitted']['PostcodeMSATS']) && $submission['submitted']['PostcodeMSATS']) {
            $lead['postcode_msats'] = $submission['submitted']['PostcodeMSATS'];
            $fields[] = 'postcode_msats';
        }

        if (isset($submission['submitted']['StateMSATS']) && $submission['submitted']['StateMSATS']) {
            $lead['state_msats'] = $submission['submitted']['StateMSATS'];
            $fields[] = 'state_msats';
        }

        $this->Lead->create();
        $this->Lead->save(array('Lead' => $lead));

        if (!$existing) {
            $lead_id = $this->Lead->getLastInsertId();
        }

        return $lead_id;

    }

    private function get_lead($lead_id = '') {
        $username = LEADS360_USERNAME;
        $password = LEADS360_PASSWORD;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->leads360_url_2."/ClientService.asmx/GetLead?username={$username}&password={$password}&leadId={$lead_id}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    private function moversbuddy($submission = array()) {
        $skey = 'D45824C2DF3BE7D6';
        $request = http_build_query($submission, '', '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://lm.moversbuddy.com/api-ew/?skey={$skey}&{$request}");
        //curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        $response = curl_exec($ch);
        curl_close($ch);
        if (strpos($response, 'Success') !== false) {
            //return $id;
        }
    }

    private function assign_to_agent($lead_id, $agent_id) {
        $username = LEADS360_USERNAME;
        $password = LEADS360_PASSWORD;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->leads360_url_2."/ClientService.asmx/AssignViaDistribution?username={$username}&password={$password}&leadId={$lead_id}&agentId={$agent_id}&programId=121");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        $this->LeadAgent->create();
        $this->LeadAgent->save(array('LeadAgent' => array(
            'sid' => time(),
            'leadid' => $lead_id,
            'agentid' => $agent_id,
            'response' => $response,
            'submitted' => date('Y-m-d H:i:s'),
            'source' => 'Signup Form - assign to agent',
        )));

        return $response;
    }

    private function create_lead_hubspot($submission)
    {
        $arr = array();
        if (isset($submission['submitted']['EmailM']) && $submission['submitted']['EmailM']) {
            $arr['properties']['email'] = $submission['submitted']['EmailM'];
        }
        if (isset($submission['submitted']['FirstName']) && $submission['submitted']['FirstName']) {
            $arr['properties']['firstname'] = $submission['submitted']['FirstName'];
        }
        if (isset($submission['submitted']['surname']) && $submission['submitted']['surname']) {
            $arr['properties']['lastname'] = $submission['submitted']['surname'];
        }
        if (isset($submission['submitted']['HomePhone']) && $submission['submitted']['HomePhone']) {
            $arr['properties']['phone'] = $submission['submitted']['HomePhone'];
        }
        if (isset($submission['submitted']['MobileNumber']) && $submission['submitted']['MobileNumber']) {
            $arr['properties']['mobilephone'] = $submission['submitted']['MobileNumber'];
        }
        if (isset($submission['BusOrResidential']) && $submission['BusOrResidential']) {
            $arr['properties']['customer_type'] = $submission['BusOrResidential'];
        }
        if (isset($submission['submitted']['PostcodeSupply']) && $submission['submitted']['PostcodeSupply']) {
            $arr['properties']['zip'] = $submission['submitted']['PostcodeSupply'];
        }
        if (isset($submission['submitted']['SaleCompletionDate'])) {
            $arr['properties']['sale_completion_year'] = date('Y');
            $arr['properties']['sale_completion_month'] = date('m');
            $arr['properties']['sale_completion_date'] = date('Y-m-d');
            $arr['properties']['sale_status'] = 'Sale';
        }
        if (isset($submission['submitted']['MoveinOrTransfer']) && $submission['submitted']['MoveinOrTransfer']) {
            $arr['properties']['transfer_type'] = ($submission['submitted']['MoveinOrTransfer'] == 'Move In') ? 'MOVEIN' : 'TRANSFER';
        }

        if (empty($arr)) {
            return false;
        }

        $post_json = json_encode($arr);
        $hapikey = HUBSPOT_API_KEY;
        $endpoint = 'https://api.hubapi.com/crm/v3/objects/contacts?hapikey=' . $hapikey;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $post_json,
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
        } else {
            return $response;
        }
    }

    private function update_lead_hubspot($vid, $submission)
    {
        $arr = array();
        if (isset($submission['submitted']['EmailM']) && $submission['submitted']['EmailM']) {
            $arr['properties']['email'] = $submission['submitted']['EmailM'];
        }
        if (isset($submission['submitted']['FirstName']) && $submission['submitted']['FirstName']) {
            $arr['properties']['firstname'] = $submission['submitted']['FirstName'];
        }
        if (isset($submission['submitted']['surname']) && $submission['submitted']['surname']) {
            $arr['properties']['lastname'] = $submission['submitted']['surname'];
        }
        if (isset($submission['submitted']['HomePhone']) && $submission['submitted']['HomePhone']) {
            $arr['properties']['phone'] = $submission['submitted']['HomePhone'];
        }
        if (isset($submission['submitted']['MobileNumber']) && $submission['submitted']['MobileNumber']) {
            $arr['properties']['mobilephone'] = $submission['submitted']['MobileNumber'];
        }
        if (isset($submission['BusOrResidential']) && $submission['BusOrResidential']) {
            $arr['properties']['customer_type'] = $submission['BusOrResidential'];
        }
        if (isset($submission['submitted']['PostcodeSupply']) && $submission['submitted']['PostcodeSupply']) {
            $arr['properties']['zip'] = $submission['submitted']['PostcodeSupply'];
        }
        if (isset($submission['submitted']['SaleCompletionDate'])) {
            $arr['properties']['sale_completion_year'] = date('Y');
            $arr['properties']['sale_completion_month'] = date('m');
            $arr['properties']['sale_completion_date'] = date('Y-m-d');
            $arr['properties']['sale_status'] = 'Sale';
        }
        if (isset($submission['submitted']['MoveinOrTransfer']) && $submission['submitted']['MoveinOrTransfer']) {
            $arr['properties']['transfer_type'] = ($submission['submitted']['MoveinOrTransfer'] == 'Move In') ? 'MOVEIN' : 'TRANSFER';
        }

        if (empty($arr)) {
            return false;
        }

        $post_json = json_encode($arr);
        $hapikey = HUBSPOT_API_KEY;
        $endpoint = "https://api.hubapi.com/crm/v3/objects/contacts/{$vid}?hapikey={$hapikey}";


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PATCH",
            CURLOPT_POSTFIELDS => $post_json,
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
        } else {
            return $response;
        }
    }

    public function street_type() {
        if (isset($this->request->query) && !empty($this->request->query)) {
            $return = array();
            $term = $this->request->query['term'];
            $callback = $this->request->query['callback'];
            $street_types = $this->StreetType->find('all', array(
                'conditions' => array('StreetType.name LIKE' => $term . '%'),
                'order' => array('StreetType.name ASC')
            ));
            if (!empty($street_types)) {
                foreach ($street_types as $street_type) {
                    $return['items'][] = array(
                        'name' => $street_type['StreetType']['name'],
                    );
                }
            }
            return new CakeResponse(array(
                'body' => $callback."(".json_encode($return).");",
                'type' => 'json',
                'status' => '201'
            ));
        }
    }

    public function sales_rep() {
        if (isset($this->request->query) && !empty($this->request->query)) {
            $return = array();
            $term = $this->request->query['term'];
            $callback = $this->request->query['callback'];
            $sales = $this->Sale->find('all', array(
                'conditions' => array('Sale.name LIKE' => $term . '%', 'Sale.status' => 1),
                'order' => array('Sale.name ASC')
            ));
            if (!empty($sales)) {
                foreach ($sales as $sale) {
                    $return['items'][] = array(
                        'name' => $sale['Sale']['name'],
                        'email' => $sale['Sale']['email'],
                    );
                }
            }
            return new CakeResponse(array(
                'body' => $callback."(".json_encode($return).");",
                'type' => 'json',
                'status' => '201'
            ));
        }
    }

    public function origin_lpg_lookup() {
        if ($this->request->is('put') || $this->request->is('post')) {
            $return = array();
            $postcode = $this->request->data['postcode'];
            $suburb = $this->request->data['suburb'];
            $state = $this->request->data['state'];
            $lpg = $this->OriginLpg->find('first', array(
                'conditions' => array(
                    'OriginLpg.postcode' => $postcode,
                    'OriginLpg.suburb' => $suburb,
                    'OriginLpg.state' => $state
                ),
            ));
            if (!empty($lpg)) {
                $return = $lpg['OriginLpg'];
            }
            return new CakeResponse(array(
                'body' => json_encode($return),
                'type' => 'json',
                'status' => '201'
            ));
        }
    }

    public function admin_repost($id)
    {
        $submission = $this->Submission->find('first', array(
            'conditions' => array('leadid' => $id),
        ));
        if (empty($submission)) {
            $this->redirect('http://' . WEBSITE_MAIN_DOMAIN_NAME);
        }
        parse_str($submission['Submission']['request'], $submission_array);
        /*
        $response = $this->get_lead($id);
        if (strpos($response, '<Leads>') !== false) {
            $xml = simplexml_load_string($response);
            $lead_array = json_decode(json_encode($xml), true);
            $campaign_id = $lead_array['Lead']['Campaign']['@attributes']['CampaignId'];
            $submission_array['ew_repost'] = 1;
            $this->update_lead($campaign_id, $id, $submission_array);
        } else {
            $id = $this->create_lead(2, $submission_array);
        }
        */
        $submission_array['ew_duplicate'] = 1;
        $id = $this->create_lead(2, $submission_array);

        return new CakeResponse(array(
            'body' => $id,
            'type' => 'text',
            'status' => '201'
        ));
    }

    public function admin_repost_tmp()
    {
        /*
        $submissions = $this->Submission->find('all', array(
            'conditions' => array(
                'Submission.submitted >=' => '2017-02-08 11:17:00',
                'Submission.submitted <=' => '2017-02-08 11:52:00',
                'Submission.source !=' => 'Signup Form Update',
            ),
        ));
        $ids = array();
        if (!empty($submissions)) {
            foreach ($submissions as $submission) {
                $id = $submission['Submission']['leadid'];
                parse_str($submission['Submission']['request'], $submission_array);
                $submission_array['ew_duplicate'] = 1;
                $id = $this->create_lead(2, $submission_array);
                $ids[] = $id;
            }
        }

        return new CakeResponse(array(
            'body' => json_encode($ids),
            'type' => 'text',
            'status' => '201'
        ));
        */
    }

    public function admin_export()
    {
        /*
        $submissions = $this->Submission->find('all', array(
            'conditions' => array(
                'Submission.submitted >=' => '2017-02-08 00:00:00',
                //'Submission.submitted <=' => '2017-02-08 11:52:00',
                'Submission.source' => array('Signup Form', 'Signup Form Update'),
            ),
        ));
        $csvs = array(array(
			'Lead ID',
			'Mobile',
			'Email',
			'Request',
			'NMI',
			'MIRN',
			'Response',
			'Submitted Date',
			'Source',
		));
		foreach ($submissions as $submission) {
    		parse_str($submission['Submission']['request'], $submission_array);
			$csv = array(
				$submission['Submission']['leadid'],
				$submission['Submission']['mobile'],
				$submission['Submission']['email'],
				$submission['Submission']['request'],
				$submission_array['submitted']['NMICode'],
				$submission_array['submitted']['MIRNNumber'],
				$submission['Submission']['response'],
				$submission['Submission']['submitted'],
				$submission['Submission']['source'],
			);
			$csvs[] = $csv;
		}

		$filename = "submissions_".date("Y-m-d").".csv";
		$csv_file = fopen('php://output', 'w');

		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename="'.$filename.'"');

		// Each iteration of this while loop will be a row in your .csv file where each field corresponds to the heading of the column
		foreach ($csvs as $csv) {
			fputcsv($csv_file, $csv, ',', '"');
		}

		fclose($csv_file);
		$this->autoRender = false;
		$this->layout = false;
		$this->render(false);
        */
    }

    public function admin_save_ovo_payment_token($campaign_id, $lead_id, $cb_lead_id = null) {

        if (!isset($this->request->query['payment_account_token']) || $this->request->query['payment_account_token'] == '') {
            $this->add_lead_action($lead_id, 'Error in Generating Payment Token');

            $this->redirect( 'https://' . WEBSITE_MAIN_DOMAIN_NAME );
        }

        $payment_account_token = $this->request->query['payment_account_token'];

        $submission = array();
        $submission['submitted']['PowershopToken'] = $payment_account_token;


        $this->update_lead($campaign_id, $lead_id, $submission);

        $this->add_lead_action($lead_id, 'OVO Payment Token received');

        $customer = $this->Customer->find('first', array(
            'conditions' => array(
                'Customer.leadid LIKE' => "%{$lead_id}%",
                'Customer.signup_data !=' => '',
                'Customer.signup_data IS NOT NULL',
            ),
            'order' => array('Customer.id' => 'DESC')
        ));
        $user = unserialize($customer['Customer']['data']);
        $plan = unserialize($customer['Customer']['plan_data']);
        $elec_rate = ($customer['Customer']['elec_rate_data']) ? unserialize($customer['Customer']['elec_rate_data']) : '';
        $gas_rate = ($customer['Customer']['gas_rate_data']) ? unserialize($customer['Customer']['gas_rate_data']) : '';
        $lead = unserialize($customer['Customer']['signup_data']);

        $this->set(compact('campaign_id', 'lead_id', 'cb_lead_id', 'user', 'lead'));
    }

    public function admin_add_lead_action() {
        if ($this->request->is('post') || $this->request->is('put')) {
            $lead_id = $this->request->data['lead_id'];
            $note = $this->request->data['note'];
            $this->add_lead_action($lead_id, $note);
        }

        return new CakeResponse(array(
            'body' => 1,
            'type' => 'json',
            'status' => '201'
        ));
    }

    private function add_lead_action($lead_id, $note) {
        $username = LEADS360_USERNAME;
        $password = LEADS360_PASSWORD;
        $action_note = urlencode($note);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->leads360_url_2."/ClientService.asmx/AddLeadAction?username={$username}&password={$password}&leadId={$lead_id}&actionTypeId=162&actionNote={$action_note}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    private function add_lead_history($lead_id, $type, $description = 'Signup', $response = '')
    {
        $this->LeadHistory->create();
        $this->LeadHistory->save(array('LeadHistory' => array(
            'leadid' => $lead_id,
            'type' => $type,
            'description' => $description,
            'response' => $response,
            'source' => 'Signup',
            'submitted' => date('Y-m-d H:i:s'),
        )));

        return $this->LeadHistory->getLastInsertId();
    }

    private function broadband($data)
    {
        $username = 'tester';
        $password = 'k6TakUkusA';
        //$url = "https://cbb2.calldynamics.net.au/api/addreferral/?key=qJeDGpYgOFwVsVOTVPZoRyCIVLJf3S7X"; // staging
        $url = "https://www.comparebroadband.com.au/api/addreferral/?key=qJeDGpYgOFwVsVOTVPZoRyCIVLJf3S7X"; // live
        try {
            $headers = array('Content-Type: application/json', 'Authorization:Basic ' . base64_encode($username . ':' . $password)); // staging
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); // staging
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $curl_response = curl_exec($curl);
            $response = json_decode($curl_response, true);
            curl_close($curl);

            return $response;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
