<div class="container-fluid">
    <?php echo $this->element('signup_view_header'); ?>
    <section class="contact-deatils">
        <div class="contact-form"><div class="container"><div class="row"><div class="col-sm-12">
            <input type="hidden" id="id" name="id" value="<?php echo $customer['Customer']['id']; ?>">

            <div>
                <h3 class="form-hdg">
                    <div class="fh-inr">Final Info</div>
                </h3>
                <div class="form clearfix">
                    <div class="form-data">
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label>Purchase Reason</label>
                            </div>
                            <div class="col-xs-6">
                                <?php echo $lead['Final']['purchase_reason']; ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label>Sales Rep Name</label>
                            </div>
                            <div class="col-xs-6">
                                <?php echo $lead['Final']['sales_rep_name']; ?>
                            </div>
                        </div>
                        <?php if ($lead['Final']['eic_pdf']): ?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>EIC PDF</label>
                                </div>
                                <div class="col-xs-6">
                                    <a href="/pdf/<?php echo $lead['Final']['eic_pdf']; ?>"
                                       target="_blank"><?php echo $lead['Final']['eic_pdf']; ?></a>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label>EIC Recording</label>
                            </div>
                            <div class="col-xs-6">
                                <?php echo $lead['Final']['eic']; ?>
                            </div>
                        </div>
                        <?php if ($lead['Plan']['supplier'] == 'Powershop'): ?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Powershop token</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Final']['powershop_token']; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($lead['Plan']['supplier'] == 'OVO Energy'): ?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Are you happy to proceed with payment(s) to be debited from your account?</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo ($lead['Final']['ovo_energy_payments'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label>Now that I've helped you save on your energy, we also work with Health Deal Expert, they specialise in health insurance comparisons - Did you want me to get them to give you a call and see if you can save on health insurance as well?</label>
                            </div>
                            <div class="col-xs-6">
                                <?php echo ($lead['Final']['healthdeal'] > 0) ? 'Yes' : 'No'; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <h3 class="form-hdg">
                    <div class="fh-inr">Plan Data</div>
                </h3>
                <div class="form clearfix">
                    <div class="form-data">
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label>Fuel</label>
                            </div>
                            <div class="col-xs-6">
                                <?php echo $user['step1']['plan_type']; ?>
                            </div>
                        </div>
                        <?php if (in_array($user['step1']['plan_type'], array('Dual', 'Elec'))): ?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Current electricity retailer</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo ($user['step1']['elec_supplier']) ? $user['step1']['elec_supplier'] : $user['step1']['elec_supplier2']; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (in_array($user['step1']['plan_type'], array('Dual', 'Gas'))): ?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Current gas retailer</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo ($user['step1']['gas_supplier']) ? $user['step1']['gas_supplier'] : $user['step1']['gas_supplier2']; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label>New retailer</label>
                            </div>
                            <div class="col-xs-6">
                                <?php echo $lead['Plan']['supplier']; ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label>Deal Expert Disclaimer</label>
                            </div>
                            <div class="col-xs-12">
                                <p>This is (your name) from Deal Expert, on behalf of <?php echo $lead['Plan']['supplier']; ?>. Can you please confirm that you are happy for me to record this conversation with a clear yes or no?</p>
                                <p>We work with a number of retailers to help compare a number of deals available in your area. The deals we compare are the best offers we have available to us from our panel.</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label>Product summary</label>
                            </div>
                            <div class="col-xs-6">
                                <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL', 'OVO Energy', 'Blue NRG'))):?>
                                    <?php echo nl2br($plan['Plan']['signup_summary']); ?>
                                <?php else:?>
                                <?php if (!in_array($lead['Plan']['supplier'], array('Origin Energy', 'Sumo Power', 'Alinta Energy'))):?>Their <strong><?php echo $lead['Plan']['product_name']; ?></strong> plan gives you <?php endif;?><?php echo nl2br($plan['Plan']['signup_summary']); ?>
                                <?php endif;?>
                                <?php //echo $lead['Plan']['product_summary']; ?>
                            </div>
                        </div>
                        <?php if ($user['step1']['looking_for'] == 'Move In' && in_array($plan['Plan']['res_sme'], array('RES', 'SOHO'))): ?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>So this means that the bills will be in your name moving forward.</label>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if ($user['step1']['looking_for'] == 'Move In' && in_array($plan['Plan']['res_sme'], array('SME'))): ?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>So this means that the bills will be in the business trading name moving forward.</label>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if ($lead['Plan']['supplier'] == 'Alinta Energy' && $lead['Plan']['state'] == 'VIC'): ?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Alinta Energy may pay commissions to the marketing representative as a result of you entering into this energy contract.</label>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if (in_array($user['step1']['plan_type'], array('Dual', 'Elec'))):?>
                        <?php if ($plan['Plan']['product_code_elec']):?>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label>Product code elec</label>
                            </div>
                            <div class="col-xs-6">
                                <?php echo $plan['Plan']['product_code_elec']; ?>
                            </div>
                        </div>
                        <?php endif;?>
                        <?php if ($plan['Plan']['campaign_code_elec']):?>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label>Campaign code elec</label>
                            </div>
                            <div class="col-xs-6">
                                <?php echo $plan['Plan']['campaign_code_elec']; ?>
                            </div>
                        </div>
                        <?php endif;?>
                        <?php endif;?>
                        <?php if (in_array($user['step1']['plan_type'], array('Dual', 'Gas'))):?>
                        <?php if ($plan['Plan']['product_code_gas']):?>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label>Product code gas</label>
                            </div>
                            <div class="col-xs-6">
                                <?php echo $plan['Plan']['product_code_gas']; ?>
                            </div>
                        </div>
                        <?php endif;?>
                        <?php if ($plan['Plan']['campaign_code_gas']):?>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label>Campaign code gas</label>
                            </div>
                            <div class="col-xs-6">
                                <?php echo $plan['Plan']['campaign_code_gas']; ?>
                            </div>
                        </div>
                        <?php endif;?>
                        <?php endif;?>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label>Customer type</label>
                            </div>
                            <div class="col-xs-6">
                                <?php echo $plan['Plan']['res_sme']; ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label>Looking for</label>
                            </div>
                            <div class="col-xs-6">
                                <?php echo ($user['step1']['looking_for'] == 'Compare Plans') ? 'Transfer' : 'Move In'; ?>
                            </div>
                        </div>
                        <?php if (in_array($user['step1']['plan_type'], array('Dual', 'Elec'))): ?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Electricity product</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Plan']['ElectricityProduct']; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (in_array($user['step1']['plan_type'], array('Dual', 'Gas'))): ?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Gas product</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Plan']['GasProduct']; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label>State</label>
                            </div>
                            <div class="col-xs-6">
                                <?php echo $lead['Plan']['state']; ?>
                            </div>
                        </div>
                        <?php if (in_array($user['step1']['plan_type'], array('Dual', 'Elec'))): ?>
                            <?php
                            $tariffs = array();
                            if ($user['step1']['tariff1']) {
                                $tariff1 = explode('|', $user['step1']['tariff1']);
                                if ($tariff1[3] == 'Solar') {
                                    if (strpos($tariff1[4], '/') !== false) {
                                        $tariff1[0] .= ' (' . $user['step1']['solar_rebate_scheme'] . ')';
                                    } else {
                                        $tariff1[0] .= ' (' . $tariff1[4] . ')';
                                    }
                                }
                                $tariffs[] = $tariff1[0];
                            }
                            if ($user['step1']['tariff2']) {
                                $tariff2 = explode('|', $user['step1']['tariff2']);
                                if ($tariff2[3] == 'Solar') {
                                    if (strpos($tariff2[4], '/') !== false) {
                                        $tariff2[0] .= ' (' . $user['step1']['solar_rebate_scheme'] . ')';
                                    } else {
                                        $tariff2[0] .= ' (' . $tariff2[4] . ')';
                                    }
                                }
                                $tariffs[] = $tariff2[0];
                            }
                            if ($user['step1']['tariff3']) {
                                $tariff3 = explode('|', $user['step1']['tariff3']);
                                if ($tariff3[3] == 'Solar') {
                                    if (strpos($tariff3[4], '/') !== false) {
                                        $tariff3[0] .= ' (' . $user['step1']['solar_rebate_scheme'] . ')';
                                    } else {
                                        $tariff3[0] .= ' (' . $tariff3[4] . ')';
                                    }
                                }
                                $tariffs[] = $tariff3[0];
                            }
                            if ($user['step1']['tariff4']) {
                                $tariff4 = explode('|', $user['step1']['tariff4']);
                                if ($tariff4[3] == 'Solar') {
                                    if (strpos($tariff4[4], '/') !== false) {
                                        $tariff4[0] .= ' (' . $user['step1']['solar_rebate_scheme'] . ')';
                                    } else {
                                        $tariff4[0] .= ' (' . $tariff4[4] . ')';
                                    }
                                }
                                $tariffs[] = $tariff4[0];
                            }
                            ?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Tariffs</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo implode(' & ', $tariffs); ?>
                                </div>
                            </div>
                            <?php if ($lead['Plan']['retailer'] == 'Momentum'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Meter Type</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Plan']['meter_type']; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Property Type</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Plan']['property_type']; ?>
                                </div>
                            </div>
                        <?php if ($lead['Plan']['supplier'] == 'Blue NRG'):?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Based on our discussions, we are recommending Blue NRG Pty Ltd located in the Melbourne CBD. For Blue NRG to establish your electricity account(s), a voice recording is used to confirm your agreement. You are bound by the terms and conditions of this agreement if you accept it verbally at the conclusion of this agreement.</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>The electricity to be used at your supply address must be for business purposes only. Blue NRG does not provide electricity for residential purposes. Can you please confirm for the purposes of this agreement, that nobody uses the property as their place of residence?</label>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo ($lead['Plan']['bluenrg_elec_business'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Based on your (meter data or previous invoice) we are estimating your annual electricity consumption to be <?php echo round($lead['Plan']['elec_consumption_data'] / 1000); ?> MWh/year  which makes you eligible for this electricity business plan.</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>This agreement has no minimum supply period and no exit fees. Would you like to hear the rates and charges now or would you prefer to review them in your welcome pack?</label>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo ($lead['Plan']['reconfirm_rates'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                            <?php if ($lead['Plan']['reconfirm_rates'] > 0): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12 field-col table-rate"></div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (in_array($lead['Plan']['supplier'], array('Lumo Energy', 'Origin Energy'))):?>
                            <?php if ($lead['Plan']['supplier'] == 'Lumo Energy'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Before I read out the offer conditions, I am required to read to you the rates & charges of this plan, all of which will be inclusive of GST.</label>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($lead['Plan']['supplier'] == 'Origin Energy'):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Before I read out the offer conditions, I am required to read to you the rates & charges of this plan, all of which will be inclusive of GST.<br>Please bear in mind you will receive confirmation of these energy rates, tariff type and all the details of your plan in your welcome pack.</label>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($lead['Plan']['supplier'] == 'Lumo Energy' && $lead['Plan']['state'] != 'VIC'):?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Lumo's Basic Plan Information Document, which is an information sheet that contains all the key details about this offer is available online at lumoenergy.com.au</label>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($lead['Plan']['supplier'] == 'Lumo Energy' && $lead['Plan']['state'] == 'VIC'):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Lumo's Victorian Energy Fact Sheet document, which is an information sheet that contains all the key details about this offer is available online at lumoenergy.com.au</label>
                                    </div>
                                </div>
                            <?php endif;?>
                            <div class="form-group row">
                                <div class="col-xs-12 field-col table-rate"></div>
                            </div>
                            <?php if ($user['step1']['plan_type'] == 'Elec'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Electricity usage rates are measured in Cents per KWH.</label>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if ($user['step1']['plan_type'] == 'Gas'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Gas usage rates are measured in Cents per mj.</label>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if ($user['step1']['plan_type'] == 'Dual'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Electricity usage rates are measured in Cents per KWH & Gas usage rates are measured in Cents per mj.</label>
                                    </div>
                                </div>
                            <?php endif;?>
                        <?php endif;?>
                        <?php if ($lead['Plan']['supplier'] == 'ERM'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>The offer you have accepted today is ERM Business Energy's <?php echo $lead['Plan']['product_name']; ?> offer and your new rates will be:</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-12 field-col table-rate"></div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>These will be sent out in your contract pack which you will receive via post from ERM Business Energy in the next 48 hours. Energy Price Fact Sheets are available on ERM Business Energy’s website or upon request.</label>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if ($lead['Plan']['supplier'] == 'Powershop'): ?>
                            <?php if ($user['step1']['looking_for'] == 'Move Properties'):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Has the electricity at the property you're moving to been disconnected for more than 12 months?</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php echo ($lead['Plan']['electricity_disconnected'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                            <?php endif;?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Full details of your rates and charges will be sent to you via email and are also available at powershop.com.au Would you like to hear the rates now?</label>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo ($lead['Plan']['reconfirm_rates'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                            <?php if ($user['step1']['plan_type'] == 'Elec'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Electricity usage rates are measured in Cents per KWH. These rates are including GST</label>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if ($lead['Plan']['reconfirm_rates'] > 0 && in_array($user['step1']['plan_type'], array('Gas', 'Dual'))) :?>
                                <div class="col-xs-12">
                                    <label>Gas rates are subject to confirmation of distribution zone at your supply address.</label>
                                </div>
                            <?php endif;?>
                            <?php if ($lead['Plan']['reconfirm_rates'] > 0): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12 field-col table-rate"></div>
                                </div>
                            <?php endif; ?>
                        <?php endif;?>
                        <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL', 'Momentum', 'Sumo Power', 'Next Business Energy', 'Alinta Energy', 'Energy Australia', 'ActewAGL', 'OVO Energy', 'Blue NRG'))):?>
                            <?php if (!in_array($lead['Plan']['supplier'], array('Blue NRG'))):?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL'))):?>
                                        <label>You will receive confirmation of your energy rates and all the details of your plan in your <?php echo $lead['Plan']['supplier'];?> Plan Confirmation. Would you like to hear those rates now?</label>
                                        <?php else:?>
                                        <label>You will receive confirmation of your energy rates and all the details of your plan in your welcome pack. Would you like to hear those rates now?</label>
                                        <?php endif;?>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo ($lead['Plan']['reconfirm_rates'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($lead['Plan']['reconfirm_rates'] > 0): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12 field-col table-rate"></div>
                                </div>
                                <?php if (in_array($lead['Plan']['supplier'], array('Momentum', 'AGL', 'Alinta Energy', 'Energy Australia', 'Origin Energy', 'Powershop', 'Sumo Power')) && in_array($user['step1']['plan_type'], array('Gas', 'Dual'))) :?>
                                    <div class="col-xs-12">
                                        <label>Gas rates are subject to confirmation of distribution zone at your supply address.</label>
                                    </div>
                                <?php endif;?>
                                <?php if (in_array($lead['Plan']['supplier'], array('Lumo Energy')) && in_array($user['step1']['plan_type'], array('Gas', 'Dual'))) :?>
                                    <div class="col-xs-12">
                                        <label>Gas rates are subject to confirmation of distribution zone at your supply address.</label>
                                    </div>
                                <?php endif;?>
                            <?php endif; ?>
                            <?php if (in_array($lead['Plan']['supplier'], array('AGL')) && $lead['Plan']['state'] != 'VIC'):?>
                                <div class="col-xs-12">
                                    <label>Basic Plan Information Documents are available by visiting www.agl.com.au/bpid or upon request.</label>
                                </div>
                            <?php endif;?>
                            <?php if (in_array($lead['Plan']['supplier'], array('AGL')) && $lead['Plan']['state'] == 'VIC'):?>
                                <div class="col-xs-12">
                                    <label>Victorian Energy Fact Sheets are available by visiting agl.com.au or upon request.</label>
                                </div>
                            <?php endif;?>
                            <?php if (in_array($lead['Plan']['supplier'], array('Powerdirect')) && $lead['Plan']['state'] != 'VIC'):?>
                                <div class="col-xs-12">
                                    <label>Basic Plan Information Documents are available by visiting www.powerdirect.com.au or upon request.</label>
                                </div>
                            <?php endif;?>
                            <?php if (in_array($lead['Plan']['supplier'], array('Powerdirect')) && $lead['Plan']['state'] == 'VIC'):?>
                                <div class="col-xs-12">
                                    <label>Victorian Energy Fact Sheets are available by visiting powerdirect.com.au or upon request.</label>
                                </div>
                            <?php endif;?>
                            <?php if (in_array($lead['Plan']['supplier'], array('Origin Energy')) && $lead['Plan']['state'] != 'VIC'):?>
                                <div class="col-xs-12">
                                    <label>Basic Plan Information Documents are information sheets that contain all the key details about a plan and are available at: <span style="text-decoration: underline; color: #07C; font-weight: bold;">public.compareconnectsave.com.au/BPID-search</span> or on request.<br><br>
                                        Link to be read as: Compare dot Deal Expert dot com dot AU forward slash BPID hyphen search.</label>
                                </div>
                            <?php endif;?>
                            <?php if (in_array($lead['Plan']['supplier'], array('Origin Energy')) && $lead['Plan']['state'] == 'VIC'):?>
                                <div class="col-xs-12">
                                    <label>Victorian Energy Fact Sheets are information sheets that contain all the key details about a plan and are available at public.compareconnectsave.com.au/VEFS or on request.<br><br>
                                            Link to be read as: Compare dot Deal Expert dot com dot AU forward slash VEFS</label>
                                </div>
                            <?php endif;?>
                        <?php endif;?>

                        <?php if ($lead['Plan']['supplier'] != 'Lumo Energy' && $lead['Plan']['reconfirm_rates'] > 0):?>
                            <?php if ($user['step1']['plan_type'] == 'Elec'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Electricity usage rates are measured in Cents per KWH</label>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if ($user['step1']['plan_type'] == 'Gas'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Gas usage rates are measured in Cents per mj</label>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if ($user['step1']['plan_type'] == 'Dual'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Electricity usage rates are measured in Cents per KWH & Gas usage rates are measured in Cents per mj</label>
                                    </div>
                                </div>
                            <?php endif;?>
                        <?php endif;?>

                        <?php if ($lead['Plan']['supplier'] == 'Simply Energy'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <?php if ($lead['Plan']['state'] == 'VIC'):?>
                                    <label>our network’s tariff price change are dependent on the regulator and change's once per year. Simply Energy can only increase tariff prices for this energy plan 1 month after your network changes their tariff pricing. We will let you know in advance of any rate change. Your rates are Simply Energy’s market contract rates. Your rates will be in your Welcome Pack and you can view Simply Energy’s Victorian Energy Fact Sheet at simplyenergy.com.au. We can send you a copy via email or post. Would you like me to read the rates, fees and charges to you now or would you like to review them on your own later?</label>
                                    <?php else:?>
                                    <label>Your rates are Simply Energy’s market contract rates.<br>Your rates will be in your Welcome Pack and you can view Simply Energy’s Basic Plan Information Documentnat simplyenergy.com.au. We can send you a copy via email or post.<br>Would you like me to read the rates, fees and charges to you now or would you like to review them on your own later?</label>
                                    <?php endif;?>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo ($lead['Plan']['market_contract_rates'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                                <?php if ($lead['Plan']['market_contract_rates'] > 0):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>The rates and charges I have discussed with you is based on the information in front of me. The rates and charges Simply Energy will charge you depend on the network tariff at your site, and this will be indicated in your welcome pack.</label>
                                    </div>
                                </div>
                                <?php endif;?>
                            </div>
                        <?php endif; ?>

                        <?php if ($lead['Plan']['supplier'] == 'Lumo Energy' || ($lead['Plan']['supplier'] == 'Energy Australia' && $lead['Plan']['rates_charges'] == 1) || ($lead['Plan']['supplier'] == 'Powershop' && $lead['Plan']['reconfirm_rates'] == 1)): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Do you understand and agree to these rates?</label>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo ($lead['Plan']['understand_agree_rates'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($lead['Plan']['supplier'] == 'Origin Energy'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <h5>Please read verbatim to customer:</h5>
                                    <p>You can find further information on pricing, distribution zone, and metering configuration information on your Origin bill or meter, or alternatively through Origin’s website: www.originenergy.com.au/pricing</p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($lead['Plan']['supplier'] == 'ActewAGL' && in_array($user['step1']['plan_type'], array('Dual', 'Elec'))):?>
                        <div class="form-group row">
                            <div class="col-xs-12">
                                <h5>Please read verbatim to customer:</h5>
                                <p>Subject to confirmation of the meter type at the supply address, the daily supply charge may be less than originally quoted to you today. Your welcome pack from ActewAGL Retail will reflect the correct charge.</p>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($lead['Plan']['supplier'] == 'Simply Energy' && $lead['Plan']['product_name'] == 'Simply NRMA' && $lead['Plan']['state'] == 'NSW'): ?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>NRMA Member Number</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Plan']['nrma']; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($lead['Plan']['supplier'] == 'Momentum'): ?>
                            <?php if (in_array($user['step1']['plan_type'], array('Dual', 'Elec'))): ?>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Electricity consumption data</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Plan']['elec_consumption_data']; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (in_array($user['step1']['plan_type'], array('Dual', 'Gas'))): ?>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Gas consumption data</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Plan']['gas_consumption_data']; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (in_array($user['step1']['plan_type'], array('Dual', 'Elec'))): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Do you have solar panels installed at your property?</label>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo ($lead['Plan']['solar_panels'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                            <?php if (isset($lead['Plan']['solar_panels']) && $lead['Plan']['solar_panels'] > 0): ?>
                                <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect')) && $lead['Plan']['product_name'] != 'AGL Solar Savers' && in_array($lead['Plan']['state'], array('NSW', 'QLD', 'SA'))): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>This solar feed-in tariff is variable and can change with notice to you at any time.</label>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect')) && $lead['Plan']['product_name'] != 'AGL Solar Savers' && in_array($lead['Plan']['state'], array('VIC'))): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>This solar feed-in tariff is variable and can change with notice to you at any time. If we vary your solar feed-in tariff, we will give you at least five business days prior notice of the variation.</label>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="form-group row">
                                    <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect'))): ?>
                                        <?php if ((($user['step1']['elec_supplier'] = 'AGL' || $user['step1']['elec_supplier2'] == 'AGL') && $lead['Plan']['supplier'] == 'AGL') || (($user['step1']['elec_supplier'] = 'Powerdirect' || $user['step1']['elec_supplier2'] == 'Powerdirect') && $lead['Plan']['supplier'] == 'Powerdirect')):?>
                                        <div class="col-xs-12">
                                            <h5>READ if electricity is retention</h5>
                                            <p>(STATE CUSTOMER NAME) As you are a current <?php echo $lead['Plan']['supplier'];?> customer, your Feed-in Tariff may be different, by signing up to this product the feed-in tariff will be:</p>
                                        </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if ($lead['Plan']['supplier'] == 'Simply Energy' && $user['step1']['plan_type'] != 'Gas'):?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>If you're eligible for a Government Feed-in tariff, we'll credit you the relevant amount, otherwise you'll receive Simply Energy's standard feed-in tariff</label>
                                        </div>
                                    </div>
                                    <?php if ($lead['Plan']['state'] == 'VIC'):?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>In Victoria the Solar Feed in rate change is dependent on the regulator. The rate typically changes once per year and we have no plans to change your Solar Feed in rate in the coming months. However, we will let you know in advance of any rate change.</label>
                                        </div>
                                    </div>
                                    <?php endif;?>
                                    <?php endif;?>
                                    <div class="col-xs-12 field-col table-rate-solar"></div>
                                    <?php if ($lead['Plan']['supplier'] == 'Origin Energy'): ?>
                                        <div class="col-xs-12">
                                            <h5>READ VERBATIM TO CUSTOMER </h5>
                                            <p>You can review the solar feed in tariffs discussed today in your welcome pack. If you have any questions you can also contact Origin Solar Resolutions team on 1300 791 468.</p>
                                        </div>
                                    <?php elseif ($lead['Plan']['supplier'] == 'Momentum'): ?>
                                        <div class="col-xs-12">
                                            <h5>READ VERBATIM TO CUSTOMER </h5>
                                            <p>If this application’s approved, you’ve agreed to enter into a solar feed-in tariff agreement with Momentum, with terms that are additional to the terms of your electricity Market Contract.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($lead['Plan']['supplier'] == 'AGL' && $lead['Plan']['product_name'] == 'AGL Solar Savers' && in_array($lead['Plan']['state'], array('NSW', 'QLD', 'SA'))): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Please be advised that the rates are subject to change in July 2021, tariff structures may also change.</label>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($lead['Plan']['supplier'] == 'AGL' && in_array($plan['Plan']['res_sme'], array('SOHO'))): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>FOR AGL HOME OFFICE EVERYDAY ONLY : Can you please confirm that you are operating a business from your premises?</label>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo ($lead['Plan']['operating_business'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($lead['Plan']['supplier'] == 'Lumo Energy' && in_array($lead['Plan']['state'], array('NSW', 'QLD', 'SA')) && in_array($user['step1']['plan_type'], array('Dual', 'Elec'))): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>While you are with Lumo, they may replace your meter with a smart meter. This could occur at your request, or where Lumo are advised it needs to be replaced under the National Electricity Rules. Lumo may also decide to install a smart meter as part of a new meter deployment. Do you consent to this?</label>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo ($lead['Plan']['smart_meter'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                            <?php if ($lead['Plan']['smart_meter'] <= 0):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <p>Lumo may still need to replace your meter while you are their customer if required under the National Electricity Rules, for example, if your meter is faulty or coming to the end of its life. If so, Lumo will install a smart meter without active telecommunications. This means the smart meter will not communicate with Lumo’s system and will need to be manually read. There are costs you will incur if you request telecommunications turned off, these include a de-activation fee and ongoing meter reading fees. These fees can be found on Lumo's website at: https://lumoenergy.com.au/help-centre</p>
                                    </div>
                                </div>
                            <?php endif;?>
                        <?php endif; ?>
                    </div>
                </div>
                <h3 class="form-hdg">
                    <div class="fh-inr">Contact</div>
                </h3>
                <div class="form clearfix">
                    <div class="form-data">
                        <?php if ($user['step1']['looking_for'] == 'Compare Plans'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <?php if ($plan['Plan']['res_sme'] == 'SME'):?>
                                        <label>Are you authorised to enter into this agreement on behalf of the business?</label>
                                    <?php else:?>
                                        <label>Are you authorised to make this change to the energy account/s at this property?</label>
                                    <?php endif;?>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo ($lead['Contact']['authorised'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                        <?php else:?>
                            <?php if ($plan['Plan']['res_sme'] == 'SME'):?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Are you authorised to enter into this agreement on behalf of the business?</label>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo ($lead['Contact']['authorised'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                            <?php endif;?>
                        <?php endif; ?>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label>Title</label>
                            </div>
                            <div class="col-xs-6">
                                <?php echo $lead['Contact']['title']; ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label>First name</label>
                            </div>
                            <div class="col-xs-6">
                                <?php echo $lead['Contact']['first_name']; ?>
                            </div>
                        </div>
                        <?php if ($lead['Plan']['supplier'] == 'Alinta Energy'):?>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label>Middle name</label>
                            </div>
                            <div class="col-xs-6">
                                <?php echo $lead['Contact']['middle_name']; ?>
                            </div>
                        </div>
                        <?php endif;?>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label>Last name</label>
                            </div>
                            <div class="col-xs-6">
                                <?php echo $lead['Contact']['last_name']; ?>
                            </div>
                        </div>
                        <?php if (isset($lead['Contact']['company_position']) && $lead['Contact']['company_position']): ?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Company position</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Contact']['company_position']; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label>Date of birth</label>
                            </div>
                            <div class="col-xs-6">
                                <?php echo $lead['Contact']['dateofbirth']; ?>
                            </div>
                        </div>
                        <?php if (isset($lead['Contact']['mobile']) && $lead['Contact']['mobile']): ?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Mobile number</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Contact']['mobile']; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($lead['Contact']['home_phone']) && $lead['Contact']['home_phone']): ?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Home number</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Contact']['home_phone']; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label>Email</label>
                            </div>
                            <div class="col-xs-6">
                                <?php echo $lead['Contact']['email']; ?>
                            </div>
                        </div>
                        <?php if ($lead['Plan']['supplier'] == 'Alinta Energy'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>What is your preferred contact method?</label>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo $lead['Billing']['contact_method']; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($lead['Plan']['product_name'] == 'ACT Energy Rewards 25 (E-Comms Mandatory)'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>(E-mail Mandatory Plan) You will receive your bills and all communications from ActewAGL via Email.</label>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php if ($lead['Plan']['supplier'] == 'Blue NRG'): ?>
                            <?php if ($lead['Contact']['email'] == 'no@email.com.au'):?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>As you don’t have an email, you will receive your welcome pack, invoices & other important communication about your account via post.</label>
                                </div>
                            </div>
                            <?php else:?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Blue NRG will send your welcome pack, invoices & other important communication about your account via email, is that ok?</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo ($lead['Contact']['bluenrg_email_bill'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                            <?php if ($lead['Contact']['bluenrg_email_bill'] <= 0 && $lead['Plan']['state'] == 'VIC'):?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Postal invoices in Victoria will incur a admin fee of $5 inclusive of GST.</label>
                                </div>
                            </div>
                            <?php endif;?>
                            <?php endif;?>
                            <?php endif;?>
                            <?php if ($lead['Plan']['supplier'] == 'ActewAGL' && $lead['Contact']['email'] != 'no@email.com.au' && !in_array($lead['Plan']['state'], array('VIC', 'SA'))):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Would you like to receive your bills and all communications from ActewAGL via Email?</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo ($lead['Contact']['actewagl_email_bill'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($lead['Plan']['supplier'] == 'ActewAGL' && $lead['Contact']['email'] == 'no@email.com.au' && !in_array($lead['Plan']['state'], array('VIC', 'SA'))): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>As you do not have an email your bills and all communications from ActewAGL will come via the post.</label>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if ($lead['Plan']['supplier'] == 'Simply Energy' && $lead['Contact']['email'] != 'no@email.com.au'):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>We will be sending you a welcome pack, which covers everything we have spoken about today in greater detail, this includes things like your offer details, energy rates. Would you like to receive your bills and other correspondence via email?<br>(Advise the customer that they can change this any time by contacting customer service)</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo ($lead['Contact']['welcome_pack_simply_energy'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if ($lead['Plan']['supplier'] == 'Simply Energy' && $lead['Contact']['email'] == 'no@email.com.au' && $lead['Plan']['state'] != 'NSW'):?>
                            <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Okay, I do need to let you know that by receiving your bills via post you will be charged a fee of $1.65 including GST. So are you happy for me to proceed with the paper bill setup?</label>
                                    </div>
                                </div>
                            <?php endif;?>
                        <?php endif; ?>
                        <?php if ($lead['Plan']['supplier'] == 'AGL' && $lead['Contact']['email'] != 'no@email.com.au'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Do you want to receive bills, your Plan Confirmation and other important communication about your account by email?</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo ($lead['Contact']['agl_email_bill'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($lead['Plan']['supplier'] == 'AGL' && $lead['Contact']['email'] == 'no@email.com.au'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>As you don't have an email AGL will send your bills, Plan Confirmation and other important communication about your account by post.</label>
                                </div>
                            </div>
                            <?php if ($lead['Plan']['state'] != 'NSW'):?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Customers receiving a paper bill may incur a $1.75 fee. You can avoid this fee if you hold a concession card or by supplying AGL with an email address in the future.</label>
                                </div>
                            </div>
                            <?php endif;?>
                        <?php endif; ?>
                        <?php if ($lead['Plan']['supplier'] == 'Powerdirect' && $lead['Contact']['email'] != 'no@email.com.au'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Do you want to receive bills, your Plan Confirmation and other important communication about your account by email?</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo ($lead['Contact']['powerdirect_email_bill'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($lead['Plan']['supplier'] == 'Powerdirect' && $lead['Contact']['email'] == 'no@email.com.au'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>As you don't have an email Powerdirect will send your bills, Plan Confirmation and other important communication about your account by post.</label>
                                </div>
                            </div>
                            <?php if ($lead['Plan']['state'] != 'NSW'):?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Customers receiving a paper bill may incur a $1.75 fee. You can avoid this fee if you hold a concession card or by supplying Powerdirect with an email address in the future.</label>
                                </div>
                            </div>
                            <?php endif;?>
                        <?php endif; ?>
                        <?php if ($lead['Plan']['supplier'] == 'Powerdirect and AGL' && $lead['Contact']['email'] != 'no@email.com.au'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Do you want to receive bills, your Plan Confirmation and other important communication about your account by email?</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo ($lead['Contact']['pd_agl_email_bill'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($lead['Plan']['supplier'] == 'Powerdirect and AGL' && $lead['Contact']['email'] == 'no@email.com.au'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>As you don't have an email Powerdirect and AGL will send your bills, Plan Confirmation and other important communication about your account by post.</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo ($lead['Contact']['pd_agl_email_bill'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                            <?php if ($lead['Plan']['state'] != 'NSW'):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Customers receiving a paper bill may incur a $1.75 fee. You can avoid this fee if you hold a concession card or by supplying Powerdirect and AGL with an email address in the future.</label>
                                    </div>
                                </div>
                            <?php endif;?>
                        <?php endif;?>
                        <?php if ($lead['Plan']['supplier'] == 'Next Business Energy' && $lead['Contact']['email'] == 'no@email.com.au'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>An email is required for Next Business Energy without an email we can not complete the agreement.</label>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($lead['Plan']['supplier'] == 'Momentum' && $lead['Contact']['email'] == 'no@email.com.au'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>As you don't have an email Momentum will send your bills and all communication via the post. This includes notices like disconnection warnings and changes to your pricing.</label>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ((($lead['Plan']['supplier'] == 'AGL' && $lead['Contact']['agl_email_bill'] == 0) || ($lead['Plan']['supplier'] == 'Powerdirect' && $lead['Contact']['powerdirect_email_bill'] == 0) || ($lead['Plan']['supplier'] == 'Powerdirect and AGL' && $lead['Contact']['pd_agl_email_bill'] == 0)) && $lead['Plan']['state'] != 'NSW'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Customers receiving a paper bill will incur a $1.75 fee unless they hold a concession card. Do you still want to receive the bills by post?</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo ($lead['Contact']['receive_bills_by_post'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (strpos($plan['Plan']['product_name'], 'Origin Max Saver') !== false):?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <p>You will receive correspondence and bills by email unless Origin advise otherwise. If Origin are unable to reach you by email, they will send correspondence to your nominated postal address.<br>
                                        Once you receive your agreement pack, please ensure you contact Origin to set up direct debit (and monthly billing if you have a smart meter); or alternatively, you can set up direct debit via the Origin app or Origin’s My Account online</p>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if ($lead['Plan']['supplier'] == 'Origin Energy' && $lead['Contact']['email'] != 'no@email.com.au'): ?>
                            <?php if (strpos($plan['Plan']['product_name'], 'Origin Max Saver') === false):?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>You will receive correspondence and bills by email unless Origin advise otherwise. Is this ok?</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo ($lead['Contact']['origin_email_bill'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($lead['Contact']['origin_email_bill'] > 0):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <p>If Origin are unable to reach you by email, they will send correspondence to your nominated postal address.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($lead['Contact']['origin_email_bill'] == 0 && $lead['Plan']['state'] != 'NSW'):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <p>All correspondence will arrive by post and a $1.75 incl GST paper bill fee will apply.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($lead['Plan']['supplier'] == 'Origin Energy' && $lead['Contact']['email'] == 'no@email.com.au' && $lead['Plan']['state'] != 'NSW' && $lead['Plan']['state'] != 'VIC'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <p>All correspondence will arrive by post and a $1.75 incl GST paper bill fee will apply.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($lead['Plan']['supplier'] == 'Origin Energy' && $lead['Plan']['state'] == 'VIC'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <p>If you choose to receive a paper bill, a $1.75 incl GST paper bill fee will apply.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($lead['Plan']['supplier'] == 'Energy Australia' && $lead['Contact']['email'] == 'no@email.com.au' && $lead['Plan']['state'] != 'NSW'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <p>As you do not have an email address, your invoices and any changes regarding your account will be sent via the post.</p>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if ($lead['Plan']['supplier'] == 'Energy Australia' && $lead['Contact']['email'] != 'no@email.com.au'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Would you like to receive your welcome pack and bills via email?</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo ($lead['Contact']['EA_email_bill'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($lead['Plan']['supplier'] == 'ERM' && $lead['Contact']['email'] != 'no@email.com.au'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Would you like to receive bills and other correspondence regarding your contract via email to this address?</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo ($lead['Contact']['erm_email_bill'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ((strpos($plan['Plan']['product_name'], 'Lumo Plus') !== false || strpos($plan['Plan']['product_name'], 'Lumo Movers') !== false) && $lead['Plan']['state'] == 'SA'):?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label><span style="font-weight: bold; color: red">An email address is mandatory for Lumo Movers & Lumo Plus in SA Read: </span><br>Lumo will issue your bills quarterly and you will receive your bills and certain communications via email.</label>
                                </div>
                            </div>
                        <?php elseif ($lead['Plan']['supplier'] == 'Lumo Energy' && $lead['Contact']['email'] == 'no@email.com.au'):?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <p>Lumo Energy will send your energy bills and notices via post. You can update your preference to email at any time by contacting Lumo or on their website via MyAccount.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($lead['Plan']['supplier'] == 'Lumo Energy' && $lead['Contact']['email'] != 'no@email.com.au'): ?>
                            <?php if (((strpos($plan['Plan']['product_name'], 'Lumo Value') !== false || strpos($plan['Plan']['product_name'], 'Lumo Movers') !== false) && $lead['Plan']['state'] == 'VIC') || (strpos($plan['Plan']['product_name'], 'Lumo Basic') !== false)):?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Lumo Energy will send your energy bills and notices via email. Certain notices may be delivered via post. If they are unable to deliver an email to your nominated address, they will send it via post and may update your delivery preferenc<br><br>Are you happy to receive electronic communications?</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo ($lead['Contact']['lumo_email_invoices'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (($lead['Plan']['supplier'] == 'AGL' || $lead['Plan']['supplier'] == 'Powerdirect') && $lead['Contact']['Secondary'] == 1): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>Unfortunately we can not add a secondary contact at this point of the sign up. However, once you receive your welcome pack you can call <?php echo $lead['Plan']['supplier']; ?> and they will gladly add a secondary contact to your account.</p>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if ($lead['Plan']['supplier'] == 'Alinta Energy'): ?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Would you like to add a secondary account holder?
                                        (A secondary contact can access the account for enquiries only & is not financially responsible)</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo ($lead['Contact']['alinta_secondary_account'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($lead['Contact']['Secondary']): ?>
                    <h3 class="form-hdg">
                        <div class="fh-inr">Secondary Contact</div>
                    </h3>
                    <div class="form clearfix">
                        <div class="form-data">
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Title</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Secondary']['title']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>First name</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Secondary']['first_name']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Last name</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Secondary']['last_name']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Date of birth</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Secondary']['dateofbirth']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Mobile number</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Secondary']['mobile']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Home phone</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Secondary']['home_phone']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Email</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Secondary']['email']; ?>
                                </div>
                            </div>
                            <?php if ($lead['Plan']['supplier'] == 'Alinta Energy' && $lead['Contact']['alinta_secondary_account'] > 0): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Do you have a preferred contact method for the secondary contact?</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php echo $lead['Billing']['secondary_contact_method']; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($lead['Plan']['supplier'] == 'Origin Energy'):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>A joint account holder has the same access to the account as you. Please note that because you're the primary account holder, you're still solely financially responsible for the Origin account. Since you have given us [STATE THE JOINT ACC HOLDER NAME] details today, please let them know that their information has been provided to Origin and tell them about Origin’s privacy and credit reporting statement, which can be found at www.originenergy.com.au/privacy.</label>
                                    </div>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (in_array($plan['Plan']['res_sme'], array('SME', 'SOHO'))): ?>
                    <h3 class="form-hdg">
                        <div class="fh-inr">Business Details</div>
                    </h3>
                    <div class="form clearfix">
                        <div class="form-data">
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>ABN</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Business']['abn']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Trading name</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Business']['trading_name']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Legal name</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Business']['legal_name']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Business type</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Business']['type']; ?>
                                </div>
                            </div>
                            <?php if ($lead['Plan']['supplier'] == 'Alinta Energy'):?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Trustee Type</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Business']['trustee_type']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Trustee Company Name</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Business']['trustee_company_name']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Trustee ACN</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Business']['trustee_acn']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Trustee Company Type</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Business']['trustee_company_type']; ?>
                                </div>
                            </div>
                            <?php endif;?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Company Industry</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Business']['company_industry']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php
                $hide_identification = false;
                if (in_array($lead['Plan']['supplier'], array('Origin Energy', 'ERM')) || ($lead['Plan']['supplier'] == 'AGL' && in_array($plan['Plan']['res_sme'], array('SME', 'SOHO')) && $lead['Business']['type'] != 'Sole Trader') || ($lead['Plan']['supplier'] == 'Alinta Energy' && in_array($plan['Plan']['res_sme'], array('SME', 'SOHO')) && $lead['Business']['type'] != 'Sole Trader') || ($lead['Plan']['supplier'] == 'Next Business Energy' && $lead['Business']['type'] != 'Sole Trader') || ($lead['Plan']['supplier'] == 'Blue NRG' && $lead['Business']['type'] == 'Private')) {
                    $hide_identification = true;
                }
                ?>
                <?php if (!$hide_identification): ?>
                    <h3 class="form-hdg">
                        <div class="fh-inr">Identification</div>
                    </h3>
                    <div class="form clearfix">
                        <div class="form-data">
                            <?php if (($lead['Plan']['supplier'] == 'Alinta Energy' && $plan['Plan']['res_sme'] == 'RES') || ($lead['Plan']['supplier'] == 'Alinta Energy' && $plan['Plan']['res_sme'] == 'SME' && $lead['Business']['type'] == 'Sole Trader') || $lead['Plan']['supplier'] == 'Lumo Energy' || $lead['Plan']['supplier'] == 'Energy Australia' || $lead['Plan']['supplier'] == 'Momentum' || ($lead['Plan']['supplier'] == 'AGL' && $plan['Plan']['res_sme'] == 'RES') || ($lead['Plan']['supplier'] == 'AGL') || $lead['Plan']['supplier'] == 'Powerdirect'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>
                                            <?php if ($lead['Plan']['supplier'] == 'Momentum'): ?>
                                                Do you understand and agree that Momentum may conduct a credit check on you, and that your application may not be accepted if you do not meet Momentum’s credit requirements?
                                            <?php elseif ($lead['Plan']['supplier'] == 'Lumo Energy'): ?>
                                                Do you consent to Lumo performing a credit check with their external credit service?
                                            <?php elseif ($lead['Plan']['supplier'] == 'Alinta Energy'): ?>
                                                Please confirm that you are authorised to provide the personal details that you have presented today and that you consent to your information being checked with the document issuer or official record holder via third party systems for the purpose of confirming your identity?
                                            <?php elseif ($lead['Plan']['supplier'] == 'AGL' || $lead['Plan']['supplier'] == 'Alinta Energy' || $lead['Plan']['supplier'] == 'Powerdirect'): ?>
                                                <?php echo $lead['Plan']['supplier']; ?> will conduct a credit check and consider your history with them. <?php echo $lead['Plan']['supplier']; ?> will use your details safely in accordance with their privacy and credit reporting policy which is available at

                                                <?php if ($lead['Plan']['supplier'] == 'AGL'):?>
                                            		www.agl.com.au/privacy-policy.
                                            	<?php endif;?>
                                            	<?php if ($lead['Plan']['supplier'] == 'Powerdirect'):?>
                                                	www.powerdirect.com.au/privacy-policy.
                                            	<?php endif;?>

                                                Do you consent to a credit check?

                                            <?php else: ?>
                                                I will need to obtain some Identification from you, as <?php echo $plan['Plan']['retailer']; ?> will need to complete a credit check before they will take you on board as a customer. Do you give your consent for them to perform a credit check?
                                            <?php endif; ?>
                                        </label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo ($lead['Identification']['credit_check'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                            <?php endif ?>
                            <?php if ($lead['Identification']['document_id']):?>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Document type</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Identification']['document_type']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Document ID number</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Identification']['document_id']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Document expiry</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Identification']['document_expiry']; ?>
                                    </div>
                                </div>
                                <?php if ($lead['Identification']['document_type'] == 'DRV'):?>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Document state</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Identification']['document_state']; ?>
                                    </div>
                                </div>
                                <?php endif;?>
                                <?php if ($lead['Identification']['document_type'] == 'PP'):?>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Document country</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Identification']['document_country']; ?>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if ($lead['Identification']['document_type'] == 'MED'):?>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Document medicare colour</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Identification']['document_medicare_colour']; ?>
                                        </div>
                                    </div>
                                <?php endif;?>
                            <?php endif;?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (in_array($plan['Plan']['res_sme'], array('SME', 'SOHO')) && ($user['step1']['plan_type'] == 'Gas' || $lead['Plan']['supplier'] == 'Alinta Energy')): ?>
                <?php else: ?>
                    <h3 class="form-hdg">
                        <div class="fh-inr">Concession</div>
                    </h3>
                    <div class="form clearfix">
                        <div class="form-data">
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <?php if (in_array($lead['Plan']['supplier'], array('Origin Energy'))):?>
                                        <label>Does anyone residing at the premises, or intending to reside at your premises, require life support equipment for the fuel(s) that you are taking up today with Origin?</label>
                                    <?php else:?>
                                        <label>Does anyone intending to reside at the property require Life Support <strong>or medical equipment</strong> that needs Electricity or Gas?</label>
                                    <?php endif;?>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo ($lead['Concession']['household_require'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                            <?php if ($lead['Concession']['household_require'] > 0 && (in_array($lead['Plan']['supplier'], array('Origin Energy'))) && $user['step1']['plan_type'] != 'Dual'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>If you have taken up one fuel today with Origin and require life support for the other fuel, you must inform your other retailer that a person residing, or intending to reside at your premises, requires life support equipment.</label>
                                </div>
                            </div>
                            <?php endif;?>
                            <?php if ($lead['Concession']['household_require'] > 0 && (in_array($lead['Plan']['supplier'], array('Lumo Energy', 'AGL', 'Powerdirect', 'Powerdirect and AGL')))): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>I need to ask you a few questions regarding the life support requirements at the property. What is the full name of the life support user?</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Life Support Title</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php echo $lead['Concession']['life_support_user_title']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Life Support First Name</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php echo $lead['Concession']['life_support_user_first_name']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Life Support Middle Name</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php echo $lead['Concession']['life_support_user_middle_name']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Life Support Last Name</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php echo $lead['Concession']['life_support_user_last_name']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>What is the Life Support Machine Type?</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL'))):?>
                                            <?php echo $lead['Concession']['machine_type2']; ?>
                                        <?php else:?>
                                            <?php echo $lead['Concession']['machine_type']; ?>
                                        <?php endif;?>
                                    </div>
                                </div>
                                <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL'))):?>
                                <?php if ($lead['Concession']['machine_type2'] == 'G. Other'):?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>Other - Please type machine type as customer knows it</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <?php echo $lead['Concession']['machine_type_other']; ?>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php endif;?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Does the machine run by electricity or gas?</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php
                                        if ($lead['Concession']['machine_run_by'] == 1) {
                                            $machine_run_by = 'Elec';
                                        } elseif ($lead['Concession']['machine_run_by'] == 2) {
                                            $machine_run_by = 'Gas';
                                        } elseif ($lead['Concession']['machine_run_by'] == 3) {
                                            $machine_run_by = 'Dual';
                                        }
                                        echo $machine_run_by;
                                        ?>
                                    </div>
                                </div>
                                <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL')) && $user['step1']['plan_type'] != 'Dual' && $machine_run_by != $user['step1']['plan_type']):?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>If your life support equipment requires both gas and electricity to operate, please inform your <?php echo $machine_run_by;?> retailer that you or someone at your property relies on life support equipment.</label>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if ($lead['Plan']['supplier'] =='Lumo Energy'):?>
                                    <?php if (in_array($lead['Plan']['state'], array('VIC', 'SA'))): ?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <p>Please be aware that Lumo Energy will be sending you a Life Support Application form to be filled out by you and your doctor. This form must be returned to Lumo Energy as soon as possible, you can refer to your registration pack for further details.</p>
                                                <p>In the case of an emergency, there is a Faults & Emergency line, which you can call at any time. This number will be available in your Life Support registration pack and on the front page of your energy bill.</p>
                                                <p>Any Life Support Concessions will be applied once the completed form is returned to Lumo Energy. Even if you are not eligible for a concession, you are still required to complete a Life Support form to register your property with the distributor as Life Support.</p>
                                            </div>
                                        </div>
                                    <?php endif;?>
                                <?php endif;?>
                            <?php endif;?>
                            <?php if ($lead['Plan']['supplier'] == 'Momentum'): ?>
                                <?php if ($lead['Concession']['household_require'] > 0):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Unfortunately we are unable to continue with the signup process, you will need to contact Momentum Energy directly on 1300 662 778 to take up this offer.</label>
                                    </div>
                                </div>
                                <?php endif;?>
                            <?php endif;?>
                            <?php if ($lead['Concession']['household_require'] > 0 && $lead['Plan']['supplier'] == 'Alinta Energy'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <h5>PLEASE INFORM CUSTOMER</h5>
                                        <p>Due to the requirement of Life Support or medical equipment at your property, we will need to refer you to our dedicated Alinta Energy Life Support team who will be able to process this sign up for you. The contact number is 1300 721 092, and the team will be available Monday to Friday from 8am to 9pm and Saturdays from 9am to 6pm (AEST).</p>
                                        <p><strong>Working Instructions</strong></p>
                                        <p><strong>If the agent can’t warm transfer the call to the Life Support team, then they would process with this process below.</strong></p>
                                        <ul>
                                            <li>ALL fields in the template below must be completed.</li>
                                            <li>Once completed please email it to lifeSupport@alintaenergy.com.au copy in salessupport@alintaenergy.com.au and your Alinta Energy Channel Manager.</li>
                                            <li>Please note your Sales Channel will still be paid for the sale if the sale is successfully transferred to our dedicated Alinta Energy Life Support team and the sale has successfully been processed.</li>
                                            <li>Please ensure you email salessupport@alintaenergy.com.au and copy in your Alinta Energy Sales Channel Manager with all the customer details and in the subject line enter “Life Support Customer” so we can assign the sale to your Sales Channel.</li>
                                        </ul>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if ($lead['Concession']['household_require'] > 0 && $lead['Plan']['supplier'] == 'Energy Australia'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <h5>PLEASE INFORM CUSTOMER</h5>
                                        <p>As you require life support we will not proceed with the sale. Please call Energy Australia directly on 133 466. </p>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if ($plan['Plan']['res_sme'] == 'RES') :?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Do you hold a valid concession card?</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php echo ($lead['Concession']['valid_concession_card'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if ($lead['Concession']['valid_concession_card'] > 0 && $lead['Plan']['supplier'] == 'Momentum' && $lead['Plan']['state'] != 'SA'): ?>
                            <?php else: ?>
                                <?php if ($lead['Plan']['state'] == 'ACT'): ?>
                                    <?php if ($lead['Concession']['valid_concession_card'] > 0 && $lead['Plan']['supplier'] != 'Sumo Power'): ?>
                                        <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL'))):?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label><?php echo $lead['Plan']['supplier'];?>, will collect and use your name, postcode and concession card information to electronically confirm that you are eligible for concession. This authority is only effective for the period that you are a customer of <?php echo $lead['Plan']['supplier'];?>. You can also withdraw your consent at any time by contacting them. If you do not provide your consent you may not be eligible for the concession.</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>Do you understand and authorise <?php echo $lead['Plan']['supplier'];?> to provide and access this information?</label>
                                            </div>
                                            <div class="col-xs-3">
                                                <?php echo ($lead['Concession']['act_retailer'] > 0) ? 'Yes' : 'No'; ?>
                                            </div>
                                        </div>
                                        <?php if ($lead['Concession']['act_retailer'] > 0):?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>Just to let you know, if your circumstances change you’ll need to let <?php echo $lead['Plan']['supplier'];?> know.</label>
                                            </div>
                                        </div>
                                        <?php endif;?>
                                        <?php endif;?>
                                    <?php endif; ?>
                                    <?php if ($lead['Plan']['supplier'] == 'Alinta Energy'):?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>Do you have medical heating or cooling?</label>
                                            </div>
                                            <div class="col-xs-3">
                                                <?php echo ($lead['Concession']['medical_heating_cooling'] > 0) ? 'Yes' : 'No'; ?>
                                            </div>
                                        </div>
                                    <?php endif;?>
                                    <?php if ($lead['Plan']['supplier'] == 'Simply Energy' && $plan['Plan']['res_sme'] == 'RES'):?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                            <label>Is this your principal place of residence and the only residence for which the rebate is claimed?<br>
                                            <i>(If "Yes" continue with script, If "No" do not continue with adding concession)</i><br>
                                            -Just to let you know, Simply Energy will confirm your name, postcode, payment,  concession details and eligibility with the Department of Human Services, Department of Veterans’ Affairs, and other government authorities, as required.
                                            <br>Upon confirming your eligibility, any energy concessions that you are entitled to receive will be applied to your account/s.<br>
                                            It is important that you let Simply Energy and your card issuer know of any changes in your circumstances which may affect your eligibility for a concession.</label>
                                            This consent is valid while you are a customer of Simply Energy. At any point you can withdraw your consent, but please be aware that you may no longer be entitled to receive an energy concession.<br>
                                            If you don’t want your information shared in this way, or withdraw consent, you must get proof of your details directly from the department<br>
                                            Do you consent to Simply Energy performing this check?</label>
                                            </div>
                                            <div class="col-xs-3">
                                                <?php echo ($lead['Concession']['confirm_concession'] > 0) ? 'Yes' : 'No'; ?>
                                            </div>
                                        </div>
                                    <?php endif;?>
                                <?php elseif ($lead['Plan']['state'] == 'VIC'): ?>
                                    <?php if ($lead['Concession']['valid_concession_card'] > 0 && $lead['Plan']['supplier'] != 'Sumo Power'): ?>
                                        <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL', 'Energy Australia', 'Origin Energy'))):?>
                                        <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL'))):?>
                                            <div class="form-group row">
                                                <div class="col-xs-12">
                                                    <label><?php echo $lead['Plan']['supplier'];?>, will collect and use your name, postcode and concession card information to electronically confirm that you are eligible for concession. This authority is only effective for the period that you are a customer of <?php echo $lead['Plan']['supplier'];?>. You can also withdraw your consent at any time by contacting them. If you do not provide your consent you may not be eligible for the concession.</label>
                                                </div>
                                            </div>
                                        <?php endif;?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL'))): ?>
                                                    <label>Do you understand and authorise <?php echo $lead['Plan']['supplier'];?> to provide and access this information?</label>
                                                <?php elseif ($lead['Plan']['supplier'] == 'Energy Australia'): ?>
                                                    <label>If you have an eligible form of concession, please contact EnergyAustralia directly on 136 102 once you have received your Welcome pack. As a third party partner we cannot apply it at this point in the sale.</label>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-xs-3">
                                                <?php echo ($lead['Concession']['vic_retailer'] > 0) ? 'Yes' : 'No'; ?>
                                            </div>
                                        </div>
                                        <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL')) && $lead['Concession']['vic_retailer'] > 0):?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>Just to let you know, if your circumstances change you’ll need to let <?php echo $lead['Plan']['supplier'];?> know.</label>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        <?php endif;?>
                                        <?php if ($lead['Concession']['vic_retailer'] > 0 && $lead['Plan']['supplier'] == 'Origin Energy'):?>
                                            <div class="form-group row">
                                                <div class="col-xs-12">
                                                    <label>Can You please confirm this is your principal place of residence and the only residence in VIC for which you claim the rebate?</label>
                                                </div>
                                                <div class="col-xs-3">
                                                    <?php echo ($lead['Concession']['vic_residence'] > 0) ? 'Yes' : 'No'; ?>
                                                </div>
                                            </div>
                                        <?php endif;?>
                                        <?php if ($lead['Concession']['vic_residence'] > 0 && in_array($lead['Plan']['supplier'], array('Origin Energy'))): ?>
                                            <div class="form-group row">
                                                <div class="col-xs-12">
                                                    <label>Is the name and address on the card your current full name and address?</label>
                                                </div>
                                                <div class="col-xs-3">
                                                    <?php echo ($lead['Concession']['vic_customerdetial'] > 0) ? 'Yes' : 'No'; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if ($lead['Concession']['valid_concession_card'] > 0 && $lead['Plan']['supplier'] == 'Lumo Energy'): ?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>To confirm your eligibility and provide you with a concession, Lumo Energy will need to disclose the information you have provided with the Department of Human Services. Do you agree to Lumo Energy contacting DHS to confirm your name, address and concession card information?</label>
                                            </div>
                                            <div class="col-xs-3">
                                                <?php echo ($lead['Concession']['vic_disclose'] > 0) ? 'Yes' : 'No'; ?>
                                            </div>
                                        </div>
                                        <?php if ($lead['Concession']['vic_disclose'] > 0): ?>
                                            <div class="form-group row">
                                                <div class="col-xs-12">
                                                    <label>Thank you, your consent will be valid while you are a Lumo Customer or until you withdraw it by contacting them.</label>
                                                </div>
                                            </div>
                                        <?php else:?>
                                            <div class="form-group row">
                                                <div class="col-xs-12">
                                                    <label>Lumo Energy will be unable to provide you with a concession unless you obtain proof of your eligibility from DHS and provide this to them.</label>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if ($lead['Concession']['valid_concession_card'] > 0 && $lead['Plan']['supplier'] == 'Powershop'): ?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>Thank you, Powershop will contact you in regards to your concession card details.</label>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($lead['Concession']['valid_concession_card'] > 0 && $lead['Plan']['supplier'] == 'Sumo Power'): ?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>Sumo Power will send you out a form regarding your concession which you will need to fill in and send back to them. Do you understand this?</label>
                                            </div>
                                            <div class="col-xs-3">
                                                <?php echo ($lead['Concession']['sumo_power_understand_form'] > 0) ? 'Yes' : 'No'; ?>
                                            </div>
                                        </div>
                                        <?php if ($lead['Concession']['sumo_power_understand_form'] <= 0): ?>
                                            <div class="form-group row">
                                                <div class="col-xs-12">
                                                    <h5>PLEASE INFORM CUSTOMER </h5>
                                                    <p>Sorry, we cannot apply concession with Sumo Power</p>
                                                </div>
                                            </div>
                                        <?php endif;?>
                                    <?php endif;?>
                                    <?php if ($lead['Plan']['supplier'] == 'Alinta Energy'):?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>Do you have medical heating or cooling?</label>
                                            </div>
                                            <div class="col-xs-3">
                                                <?php echo ($lead['Concession']['medical_heating_cooling'] > 0) ? 'Yes' : 'No'; ?>
                                            </div>
                                        </div>
                                    <?php endif;?>
                                    <?php if ($lead['Plan']['supplier'] == 'Simply Energy' && $plan['Plan']['res_sme'] == 'RES'):?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                            <label>Is this your principal place of residence and the only residence for which the rebate is claimed?<br>
                                            <i>(If "Yes" continue with script, If "No" do not continue with adding concession)</i><br>
                                            -Just to let you know, Simply Energy will confirm your name, postcode, payment,  concession details and eligibility with the Department of Human Services, Department of Veterans’ Affairs, and other government authorities, as required.
                                            <br>Upon confirming your eligibility, any energy concessions that you are entitled to receive will be applied to your account/s.<br>
                                            It is important that you let Simply Energy and your card issuer know of any changes in your circumstances which may affect your eligibility for a concession.</label>
                                            This consent is valid while you are a customer of Simply Energy. At any point you can withdraw your consent, but please be aware that you may no longer be entitled to receive an energy concession.<br>
                                            If you don’t want your information shared in this way, or withdraw consent, you must get proof of your details directly from the department<br>
                                            Do you consent to Simply Energy performing this check?</label>
                                            </div>
                                            <div class="col-xs-3">
                                                <?php echo ($lead['Concession']['confirm_concession'] > 0) ? 'Yes' : 'No'; ?>
                                            </div>
                                        </div>
                                    <?php endif;?>
                                <?php elseif ($lead['Plan']['state'] == 'NSW'): ?>
                                    <?php if ($lead['Concession']['valid_concession_card'] > 0 && $lead['Plan']['supplier'] != 'Sumo Power'): ?>
                                        <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Energy Australia', 'Origin Energy'))):?>
                                        <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL'))):?>
                                            <div class="form-group row">
                                                <div class="col-xs-12">
                                                    <label><?php echo $lead['Plan']['supplier'];?>, will collect and use your name, postcode and concession card information to electronically confirm that you are eligible for concession. This authority is only effective for the period that you are a customer of <?php echo $lead['Plan']['supplier'];?>. You can also withdraw your consent at any time by contacting them. If you do not provide your consent you may not be eligible for the concession.</label>
                                                </div>
                                            </div>
                                        <?php endif;?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL'))): ?>
                                                    <label>Do you understand and authorise <?php echo $lead['Plan']['supplier'];?> to provide and access this information?</label>
                                                <?php elseif ($lead['Plan']['supplier'] == 'Energy Australia'): ?>
                                                    <label>If you have an eligible form of concession, please contact EnergyAustralia directly on 136 102 once you have received your Welcome pack. As a third party partner we cannot apply it at this point in the sale.</label>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-xs-3">
                                                <?php echo ($lead['Concession']['nsw_retailer'] > 0) ? 'Yes' : 'No'; ?>
                                            </div>
                                        </div>
                                        <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL')) && $lead['Concession']['nsw_retailer'] > 0):?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>Just to let you know, if your circumstances change you’ll need to let <?php echo $lead['Plan']['supplier'];?> know.</label>
                                            </div>
                                        </div>
                                        <?php endif;?>
                                        <?php endif;?>
                                        <?php if ($lead['Concession']['nsw_retailer'] > 0 && $lead['Plan']['supplier'] == 'Origin Energy'):?>
                                            <div class="form-group row">
                                                <div class="col-xs-12">
                                                    <label>Can You please confirm this is your principal place of residence and the only residence in NSW for which you claim the rebate?</label>
                                                </div>
                                                <div class="col-xs-3">
                                                    <?php echo ($lead['Concession']['nsw_residence'] > 0) ? 'Yes' : 'No'; ?>
                                                </div>
                                            </div>
                                        <?php endif;?>
                                        <?php if ($lead['Concession']['nsw_residence'] > 0 && in_array($lead['Plan']['supplier'], array('Origin Energy'))): ?>
                                            <div class="form-group row">
                                                <div class="col-xs-12">
                                                    <label>Is the name and address on the card your current full name and address?</label>
                                                </div>
                                                <div class="col-xs-3">
                                                    <?php echo ($lead['Concession']['nsw_customerdetial'] > 0) ? 'Yes' : 'No'; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if ($lead['Concession']['valid_concession_card'] > 0 && $lead['Plan']['supplier'] == 'Powershop'): ?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>Thank you, Powershop will contact you in regards to your concession card details.</label>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($lead['Concession']['valid_concession_card'] > 0 && $lead['Plan']['supplier'] == 'Sumo Power'): ?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>Sumo Power will send you out a form regarding your concession which you will need to fill in and send back to them. Do you understand this?</label>
                                            </div>
                                            <div class="col-xs-3">
                                                <?php echo ($lead['Concession']['sumo_power_understand_form'] > 0) ? 'Yes' : 'No'; ?>
                                            </div>
                                        </div>
                                        <?php if ($lead['Concession']['sumo_power_understand_form'] <= 0): ?>
                                            <div class="form-group row">
                                                <div class="col-xs-12">
                                                    <h5>PLEASE INFORM CUSTOMER </h5>
                                                    <p>Sorry, we cannot apply concession with Sumo Power</p>
                                                </div>
                                            </div>
                                        <?php endif;?>
                                    <?php endif;?>
                                    <?php if ($lead['Plan']['supplier'] == 'Alinta Energy'):?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>Do you have medical heating or cooling?</label>
                                            </div>
                                            <div class="col-xs-3">
                                                <?php echo ($lead['Concession']['medical_heating_cooling'] > 0) ? 'Yes' : 'No'; ?>
                                            </div>
                                        </div>
                                    <?php endif;?>
                                    <?php if ($lead['Plan']['supplier'] == 'Simply Energy' && $plan['Plan']['res_sme'] == 'RES'):?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                            <label>Is this your principal place of residence and the only residence for which the rebate is claimed?<br>
                                            <i>(If "Yes" continue with script, If "No" do not continue with adding concession)</i><br>
                                            -Just to let you know, Simply Energy will confirm your name, postcode, payment,  concession details and eligibility with the Department of Human Services, Department of Veterans’ Affairs, and other government authorities, as required.
                                            <br>Upon confirming your eligibility, any energy concessions that you are entitled to receive will be applied to your account/s.<br>
                                            It is important that you let Simply Energy and your card issuer know of any changes in your circumstances which may affect your eligibility for a concession.</label>
                                            This consent is valid while you are a customer of Simply Energy. At any point you can withdraw your consent, but please be aware that you may no longer be entitled to receive an energy concession.<br>
                                            If you don’t want your information shared in this way, or withdraw consent, you must get proof of your details directly from the department<br>
                                            Do you consent to Simply Energy performing this check?</label>
                                            </div>
                                            <div class="col-xs-3">
                                                <?php echo ($lead['Concession']['confirm_concession'] > 0) ? 'Yes' : 'No'; ?>
                                            </div>
                                        </div>
                                    <?php endif;?>
                                <?php elseif ($lead['Plan']['state'] == 'QLD'): ?>
                                    <?php if ($lead['Concession']['valid_concession_card'] > 0 && in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL'))): ?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <p><strong>To assess electricity or gas rebate eligibility for Queensland I need to confirm a few things:</strong></p>
                                                <p>• Is this your primary place of residence? (If Yes, proceed to next question. If No, do not add concession)<br>
                                                    • Does anyone live in the house that receives an income and pays rent that is not a dependent on you? (If Yes, proceed to next question, If No add concession)<br>
                                                    • Do they hold a concession? (If Yes, add concession & read the following consent script. If No, do not add concession)</p>
                                            </div>
                                        </div>
                                        <?php if ($lead['Concession']['qld_add_concession'] > 0):?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label><?php echo $lead['Plan']['supplier'];?>, will collect and use your name, postcode and concession card information to electronically confirm that you are eligible for concession. This authority is only effective for the period that you are a customer of <?php echo $lead['Plan']['supplier'];?>. You can also withdraw your consent at any time by contacting them. If you do not provide your consent you may not be eligible for the concession.</label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>Do you understand and authorise <?php echo $lead['Plan']['supplier'];?> to provide and access this information?</label>
                                            </div>
                                            <div class="col-xs-3">
                                                <?php echo ($lead['Concession']['qld_retailer'] > 0) ? 'Yes' : 'No'; ?>
                                            </div>
                                        </div>
                                        <?php endif;?>
                                        <?php if ($lead['Concession']['qld_retailer'] > 0):?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>Just to let you know, if your circumstances change you’ll need to let <?php echo $lead['Plan']['supplier'];?> know.</label>
                                            </div>
                                        </div>
                                        <?php endif;?>
                                    <?php elseif ($lead['Concession']['valid_concession_card'] > 0 && $lead['Plan']['supplier'] == 'Energy Australia'): ?>
                                        <label>If you have an eligible form of concession, please contact EnergyAustralia directly on 136 102 once you have received your Welcome pack. As a third party partner we cannot apply it at this point in the sale.</label>
                                    <?php elseif ($lead['Concession']['valid_concession_card'] > 0 && $lead['Plan']['supplier'] != 'Sumo Power'): ?>
                                    <?php endif; ?>
                                    <?php if ($lead['Plan']['supplier'] == 'Powershop'): ?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>Thank you, Powershop will contact you in regards to your concession card details.</label>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($lead['Plan']['supplier'] == 'Alinta Energy'):?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>Do you have medical heating or cooling?</label>
                                            </div>
                                            <div class="col-xs-3">
                                                <?php echo ($lead['Concession']['medical_heating_cooling'] > 0) ? 'Yes' : 'No'; ?>
                                            </div>
                                        </div>
                                    <?php endif;?>
                                    <?php if ($lead['Plan']['supplier'] == 'Simply Energy' && $plan['Plan']['res_sme'] == 'RES'):?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                            <label>Is this your principal place of residence and the only residence for which the rebate is claimed?<br>
                                            <i>(If "Yes" continue with script, If "No" do not continue with adding concession)</i><br>
                                            -Just to let you know, Simply Energy will confirm your name, postcode, payment,  concession details and eligibility with the Department of Human Services, Department of Veterans’ Affairs, and other government authorities, as required.
                                            <br>Upon confirming your eligibility, any energy concessions that you are entitled to receive will be applied to your account/s.<br>
                                            It is important that you let Simply Energy and your card issuer know of any changes in your circumstances which may affect your eligibility for a concession.</label>
                                            This consent is valid while you are a customer of Simply Energy. At any point you can withdraw your consent, but please be aware that you may no longer be entitled to receive an energy concession.<br>
                                            If you don’t want your information shared in this way, or withdraw consent, you must get proof of your details directly from the department<br>
                                            Do you consent to Simply Energy performing this check?</label>
                                            </div>
                                            <div class="col-xs-3">
                                                <?php echo ($lead['Concession']['confirm_concession'] > 0) ? 'Yes' : 'No'; ?>
                                            </div>
                                        </div>
                                    <?php endif;?>
                                <?php elseif ($lead['Plan']['state'] == 'SA'): ?>
                                    <?php if (!in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL', 'ERM', 'Next Business Energy', 'Origin Energy', 'Alinta Energy'))) : ?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>As you are in South Australia, you'll need to contact the Department of Human Services on 1800 307 758 to have the concession applied.</label>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($lead['Concession']['valid_concession_card'] > 0 && in_array($lead['Plan']['supplier'], array('Origin Energy'))) : ?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>To apply for a concession, contact the SA concessions hotline on 1800 307 758</label>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($lead['Concession']['valid_concession_card'] > 0 && in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL'))) : ?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>We cannot update your concession information directly, but you can do it via the Department for Communities and Social Inclusion on 1800 307 758 to have the concession applied.</label>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($lead['Plan']['supplier'] == 'Powershop'): ?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>Thank you, Powershop will contact you in regards to your concession card details.</label>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($lead['Plan']['supplier'] == 'Alinta Energy'):?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>Do you have medical heating or cooling?</label>
                                            </div>
                                            <div class="col-xs-3">
                                                <?php echo ($lead['Concession']['medical_heating_cooling'] > 0) ? 'Yes' : 'No'; ?>
                                            </div>
                                        </div>
                                    <?php endif;?>
                                    <?php if ($lead['Plan']['supplier'] == 'Simply Energy' && $plan['Plan']['res_sme'] == 'RES'):?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                            <label>Is this your principal place of residence and the only residence for which the rebate is claimed?<br>
                                            <i>(If "Yes" continue with script, If "No" do not continue with adding concession)</i><br>
                                            -Just to let you know, Simply Energy will confirm your name, postcode, payment,  concession details and eligibility with the Department of Human Services, Department of Veterans’ Affairs, and other government authorities, as required.
                                            <br>Upon confirming your eligibility, any energy concessions that you are entitled to receive will be applied to your account/s.<br>
                                            It is important that you let Simply Energy and your card issuer know of any changes in your circumstances which may affect your eligibility for a concession.</label>
                                            This consent is valid while you are a customer of Simply Energy. At any point you can withdraw your consent, but please be aware that you may no longer be entitled to receive an energy concession.<br>
                                            If you don’t want your information shared in this way, or withdraw consent, you must get proof of your details directly from the department<br>
                                            Do you consent to Simply Energy performing this check?</label>
                                            </div>
                                            <div class="col-xs-3">
                                                <?php echo ($lead['Concession']['confirm_concession'] > 0) ? 'Yes' : 'No'; ?>
                                            </div>
                                        </div>
                                    <?php endif;?>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if (isset($lead['Concession']['card_number']) && $lead['Concession']['card_number']): ?>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Title</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Concession']['title']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>First Name</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Concession']['first_name']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Middle Name</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Concession']['middle_name']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Last Name</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Concession']['last_name']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Concession card issuer</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Concession']['card_issuer']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Concession card type</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Concession']['card_type']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Concession card number</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Concession']['card_number']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Concession card start date</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Concession']['card_start']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Concession card expiry</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Concession']['card_expiry']; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php $retention = $acquisition = false; $tenant_owner = ''; ?>
                <?php if (is_array($lead['Supply']['state'])): ?>
                    <?php foreach ($lead['Supply']['state'] as $key => $value): ?>
                        <?php
                        if (isset($lead['Supply']['tenant_owner'][$key]) || isset($lead['Supply']['renant_owner'][$key])) {
                            if ($lead['Supply']['tenant_owner'][$key] > 0 || $lead['Supply']['renant_owner'][$key] > 0) {
								$tenant_owner = 'Owner';
                            } elseif ($lead['Supply']['tenant_owner'][$key] == 0 || $lead['Supply']['renant_owner'][$key] == 0) {
                                $tenant_owner = 'Tenant';
                            }
                        }
                        ?>
                        <h3 class="form-hdg">
                            <div class="fh-inr">Supply Address</div>
                        </h3>
                        <div class="form clearfix">
                            <div class="form-data">
                                <?php if (in_array($user['step1']['plan_type'], array('Elec'))) : ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <h5>PLEASE INFORM CUSTOMER </h5>
                                            <p>I'm now going to read to you, your NMI (National Meter Identifier) this will be shown on your bill and welcome pack.</p>
                                        </div>
                                    </div>
                                <?php elseif (in_array($user['step1']['plan_type'], array('Gas'))) : ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <h5>PLEASE INFORM CUSTOMER </h5>
                                            <p>I'm now going to read to you, your MIRN (Meter Installation Registration Number) this will be shown on your bill and welcome pack.</p>
                                        </div>
                                    </div>
                                <?php elseif (in_array($user['step1']['plan_type'], array('Dual'))) : ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <h5>PLEASE INFORM CUSTOMER </h5>
                                            <p>I'm now going to read to you, your NMI (National Meter Identifier) & MIRN (Meter Installation Registration Number) these will be shown on your bill and welcome pack.</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (in_array($user['step1']['plan_type'], array('Dual', 'Elec'))): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>NMI</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['nmi'][$key]; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (in_array($user['step1']['plan_type'], array('Dual', 'Gas'))): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>MIRN</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['mirn'][$key]; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Address Has no Street Number</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo ($lead['Supply']['no_street_number'][$key] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Unit</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['unit'][$key]; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Unit type</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['unit_type'][$key]; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Lot</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['lot'][$key]; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Floor</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['floor'][$key]; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Floor type</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['floor_type'][$key]; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Building name</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['building_name'][$key]; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Street number</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['street_number'][$key]; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Street number suffix</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['street_number_suffix'][$key]; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Street name</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['street_name'][$key]; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Street name suffix</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['street_name_suffix'][$key]; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Street type</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['street_type'][$key]; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Suburb</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['suburb'][$key]; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Postcode</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['postcode'][$key]; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>State</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['state'][$key]; ?>
                                    </div>
                                </div>
                                <?php if (in_array($user['step1']['plan_type'], array('Dual', 'Elec'))): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>NMI Acquisition/Retention</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['nmi_acq_ret'][$key]; ?>
                                            <?php
                                            if ($lead['Supply']['nmi_acq_ret'][$key] == 'Retention') {
                                                $retention = true;
                                            } else {
                                                $acquisition = true;
                                            }
                                            ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (in_array($user['step1']['plan_type'], array('Dual', 'Gas'))): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>MIRN Acquisition/Retention</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['mirn_acq_ret'][$key]; ?>
                                            <?php
                                            if ($lead['Supply']['mirn_acq_ret'][$key] == 'Retention') {
                                                $retention = true;
                                            } else {
                                                $acquisition = true;
                                            }
                                            ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($retention && $user['step1']['looking_for'] == 'Compare Plans' && (in_array($lead['Plan']['supplier'], array('AGL', 'Energy Australia', 'Powerdirect', 'Powerdirect and AGL', 'Origin Energy', 'Next Business Energy', 'ActewAGL')))): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>Please confirm you understand these changes to your account will be updated when <?php echo $lead['Plan']['supplier']; ?> approve and update your details in their system.</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <?php echo (isset($lead['Supply']['transfer_retention'][$key]) && $lead['Supply']['transfer_retention'][$key] > 0) ? 'Yes' : 'No'; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($user['step1']['plan_type'] == 'Dual'): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>MIRN address is different</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo ($lead['Supply']['mirn_is_different'][$key] > 0) ? 'Yes' : 'No'; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>MSATS address is different</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo ($lead['Supply']['msats_is_different'][$key] > 0) ? 'Yes' : 'No'; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($lead['Plan']['supplier'] == 'AGL'): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>MSATS/MIRN Address</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['msats_mirn_address'][$key]; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($user['step1']['plan_type'] == 'Dual' && $lead['Supply']['mirn_is_different'][$key]): ?>
                            <h3 class="form-hdg">
                                <div class="fh-inr">MIRN Address</div>
                            </h3>
                            <div class="form clearfix">
                                <div class="form-data">
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Address Has no Street Number</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo ($lead['Supply']['no_street_number_mirn'][$key] > 0) ? 'Yes' : 'No'; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Unstructured MIRN Address</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['unstructured_mirn_address'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Unit</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['unit_mirn'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Unit type</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['unit_type_mirn'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Lot</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['lot_mirn'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Floor</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['floor_mirn'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Floor type</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['floor_type_mirn'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Building name</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['building_name_mirn'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Street number</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['street_number_mirn'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Street number suffix</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['street_number_suffix_mirn'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Street name</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['street_name_mirn'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Street name suffix</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['street_name_suffix_mirn'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Street type</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['street_type_mirn'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Suburb</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['suburb_mirn'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Postcode</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['postcode_mirn'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>State</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['state_mirn'][$key]; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($user['step1']['plan_type'] == 'Dual' && $lead['Supply']['msats_is_different'][$key]): ?>
                            <h3 class="form-hdg">
                                <div class="fh-inr">MSATS Address</div>
                            </h3>
                            <div class="form clearfix">
                                <div class="form-data">
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Address Has no Street Number</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo ($lead['Supply']['no_street_number_msats'][$key] > 0) ? 'Yes' : 'No'; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Unstructured MSATS Address</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['unstructured_msats_address'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Unit</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['unit_msats'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Unit type</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['unit_type_msats'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Lot</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['lot_msats'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Floor</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['floor_msats'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Floor type</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['floor_type_msats'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Building name</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['building_name_msats'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Street number</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['street_number_msats'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Street number suffix</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['street_number_suffix_msats'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Street name</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['street_name_msats'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Street name suffix</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['street_name_suffix_msats'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Street type</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['street_type_msats'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Suburb</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['suburb_msats'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Postcode</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['postcode_msats'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>State</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['Supply']['state_msats'][$key]; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <h3 class="form-hdg">
                        <div class="fh-inr">Supply Address</div>
                    </h3>
                    <div class="form clearfix">
                        <div class="form-data">
                            <?php if (in_array($user['step1']['plan_type'], array('Dual', 'Elec'))): ?>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>NMI</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['nmi']; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (in_array($user['step1']['plan_type'], array('Dual', 'Gas'))): ?>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>MIRN</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['mirn']; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Address Has no Street Number</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo ($lead['Supply']['no_street_number'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Unit</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Supply']['unit']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Unit type</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Supply']['unit_type']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Lot</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Supply']['lot']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Floor</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Supply']['floor']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Floor type</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Supply']['floor_type']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Building name</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Supply']['building_name']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Street number</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Supply']['street_number']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Street number suffix</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Supply']['street_number_suffix']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Street name</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Supply']['street_name']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Street name suffix</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Supply']['street_name_suffix']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Street type</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Supply']['street_type']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Suburb</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Supply']['suburb']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Postcode</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Supply']['postcode']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>State</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Supply']['state']; ?>
                                </div>
                            </div>
                            <?php if (in_array($user['step1']['plan_type'], array('Dual', 'Elec'))): ?>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>NMI Acquisition/Retention</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['nmi_acq_ret']; ?>
                                        <?php
                                        if ($lead['Supply']['nmi_acq_ret'] == 'Retention') {
                                            $retention = true;
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (in_array($user['step1']['plan_type'], array('Dual', 'Gas'))): ?>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>MIRN Acquisition/Retention</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['mirn_acq_ret']; ?>
                                        <?php
                                        if ($lead['Supply']['mirn_acq_ret'] == 'Retention') {
                                            $retention = true;
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($user['step1']['plan_type'] == 'Dual'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>MIRN address is different</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo ($lead['Supply']['mirn_is_different'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>MSATS address is different</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo ($lead['Supply']['msats_is_different'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($lead['Plan']['supplier'] == 'AGL'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>MSATS/MIRN Address</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['msats_mirn_address']; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ($user['step1']['plan_type'] == 'Dual' && $lead['Supply']['mirn_is_different']): ?>
                        <h3 class="form-hdg">
                            <div class="fh-inr">MIRN Address</div>
                        </h3>
                        <div class="form clearfix">
                            <div class="form-data">
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Address Has no Street Number</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo ($lead['Supply']['no_street_number_mirn'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Unit</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['unit_mirn']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Unit type</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['unit_type_mirn']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Lot</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['lot_mirn']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Floor</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['floor_mirn']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Floor type</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['floor_type_mirn']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Building name</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['building_name_mirn']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Street number</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['street_number_mirn']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Street number suffix</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['street_number_suffix_mirn']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Street name</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['street_name_mirn']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Street name suffix</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['street_name_suffix_mirn']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Street type</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['street_type_mirn']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Suburb</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['suburb_mirn']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Postcode</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['postcode_mirn']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>State</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['state_mirn']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($user['step1']['plan_type'] == 'Dual' && $lead['Supply']['msats_is_different']): ?>
                        <h3 class="form-hdg">
                            <div class="fh-inr">MSATS Address</div>
                        </h3>
                        <div class="form clearfix">
                            <div class="form-data">
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Address Has no Street Number</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo ($lead['Supply']['no_street_number_msats'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Unit</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['unit_msats']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Unit type</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['unit_type_msats']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Lot</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['lot_msats']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Floor</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['floor_msats']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Floor type</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['floor_type_msats']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Building name</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['building_name_msats']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Street number</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['street_number_msats']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Street number suffix</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['street_number_suffix_msats']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Street name</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['street_name_msats']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Street name suffix</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['street_name_suffix_msats']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Street type</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['street_type_msats']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Suburb</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['suburb_msats']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Postcode</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['postcode_msats']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>State</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['Supply']['state_msats']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <h3 class="form-hdg">
                    <div class="fh-inr">Billing Address</div>
                </h3>
                <div class="form clearfix">
                    <div class="form-data">
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label>Billing address is different</label>
                            </div>
                            <div class="col-xs-6">
                                <?php echo ($lead['Supply']['billing_address_is_different'] > 0) ? 'Yes' : 'No'; ?>
                            </div>
                        </div>
                        <?php if ($lead['Supply']['billing_address_is_different']): ?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>PO Box</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['SupplySecondary']['po_box']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Address Has no Street Number</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo ($lead['SupplySecondary']['no_street_number'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Unit</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['SupplySecondary']['unit']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Unit type</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['SupplySecondary']['unit_type']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Lot</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['SupplySecondary']['lot']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Floor</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['SupplySecondary']['floor']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Floor type</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['SupplySecondary']['floor_type']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Building name</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['SupplySecondary']['building_name']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Street number</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['SupplySecondary']['street_number']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Street number suffix</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['SupplySecondary']['street_number_suffix']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Street name</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['SupplySecondary']['street_name']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Street name suffix</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['SupplySecondary']['street_name_suffix']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Street type</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['SupplySecondary']['street_type']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Suburb</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['SupplySecondary']['suburb']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>Postcode</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['SupplySecondary']['postcode']; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label>State</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['SupplySecondary']['state']; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <h3 class="form-hdg">
                    <div class="fh-inr">Billing Information</div>
                </h3>
                <div class="form clearfix">
                    <div class="form-data">
                        <?php if ($plan['Plan']['res_sme'] == 'RES') :?>
                            <?php if (in_array($lead['Plan']['supplier'], array('AGL')) && $user['step1']['plan_type'] == 'Dual'):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>(Customer Name) You now have the option to choose to go Carbon Neutral on AGL'S Residential electricity plans for $1 per week and AGL'S Residential Gas plans for 50 cents per week. Would you like to opt into that now?</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo ($lead['Billing']['carbon_neutral_consent'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect')) && $user['step1']['plan_type'] == 'Elec'):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>(Customer Name) You now have the option to choose to go Carbon Neutral on <?php echo $plan['Plan']['retailer'];?> Residential electricity plans for $1 per week. Would you like to opt into that now?</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo ($lead['Billing']['carbon_neutral_consent'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if (in_array($lead['Plan']['supplier'], array('AGL')) && $user['step1']['plan_type'] == 'Gas'):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>(Customer Name), you now have the option to choose to go Carbon Neutral on AGL'S Residential Gas plans for 50 cents per week. Would you like to opt into that now?</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo ($lead['Billing']['carbon_neutral_consent'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                            <?php endif;?>
                        <?php else:?>
                            <?php if (in_array($lead['Plan']['supplier'], array('AGL'))):?>
                                <?php if ($user['step1']['plan_type'] == 'Dual'):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>(Customer Name), you now have the option to choose to go Carbon Neutral on Retailer’s Name Small Business electricity plans for $4 per week and AGL's Small Business Gas plans for $7 per week. Would you like to opt into that now?</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo ($lead['Billing']['carbon_neutral_consent'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                                <?php elseif ($user['step1']['plan_type'] == 'Elec'):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>(Customer Name), you now have the option to choose to go Carbon Neutral on AGL's Small Business electricity plans for $4 per week. Would you like to opt into that now?</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo ($lead['Billing']['carbon_neutral_consent'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                                <?php elseif ($user['step1']['plan_type'] == 'Gas'):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>(Customer Name), you now have the option to choose to go Carbon Neutral on AGL'S Small Business Gas plans for $7 per week. Would you like to opt into that now?</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo ($lead['Billing']['carbon_neutral_consent'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                                <?php endif;?>
                            <?php endif;?>
                        <?php endif;?>
                        <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect'))):?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Do you understand and agree to <?php echo $lead['Plan']['supplier'];?> accessing your usage, meter and related information from the market operator and your distributor, to arrange the transfer of your energy to <?php echo $lead['Plan']['supplier'];?>?</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo ($lead['Billing']['understand_accessing'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if ($lead['Plan']['supplier'] == 'Origin Energy'):?>
                        <div class="form-group row">
                            <div class="col-xs-12">
                                <label>Your meter details will be verified with the distributor, and your charges may change if any details are incorrect. Origin will confirm these once they’ve identified your meter type and processed your application and will notify you by letter about any such change.</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-12">
                                <label>If you or someone you know is in a family domestic violence situation Origin have support available, you can call Origin directly on 13 24 61 for further information and a confidential conversation.</label>
                            </div>
                        </div>
                        <?php endif;?>
                        <?php if ($lead['Plan']['supplier'] == 'Origin Energy' && in_array($user['step1']['plan_type'], array('Elec'))):?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Is there LPG at the property?</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo ($lead['Supply']['origin_lpg_property'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                            <?php if ($lead['Supply']['origin_lpg_property'] > 0):?>
                                <?php if (isset($lead['Supply']['lpg_date']) && $lead['Supply']['lpg_date']):?>
                                <div class="col-xs-12">
                                    <label>Origin can provide an LPG service in the area, I can organise for an Origin LPG specialist to give you a call to discuss this further. What time best suits you?</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['Supply']['lpg_date']; ?>
                                </div>
                                <?php else:?>
                                    <div class="col-xs-12">
                                        <p>Unfortunately, Origin doesn't deliver to your area</p>
                                    </div>
                                <?php endif;?>
                            <?php endif;?>
                        <?php endif;?>
                        <?php if ($lead['Plan']['supplier'] == 'ActewAGL' && in_array($lead['Plan']['state'], array('ACT', 'NSW'))): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>(Marketing) Do you allow ActewAGL to use and disclose your personal information to provide you with special offers from time-to-time?</label>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo ($lead['Billing']['allow_actewagl_use'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($lead['Plan']['supplier'] == 'Powershop' && in_array($lead['Plan']['state'], array('VIC', 'SA'))): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Your agreement starts at the end of the cooling off period.</label>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php if ($lead['Contact']['email'] != 'no@email.com.au' && in_array($lead['Plan']['supplier'], array('Alinta Energy', 'ERM'))): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Do you want to register for e-billing?</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php echo ($lead['Billing']['e_billing'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($lead['Contact']['email'] != 'no@email.com.au' && $lead['Plan']['supplier'] == 'Momentum' && strpos($plan['Plan']['product_name'], 'Bill Boss') === false): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Would you like to receive your bills and other notices, like disconnection warnings and price change notifications, by email?</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php echo ($lead['Billing']['e_billing_momentum'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($lead['Plan']['supplier'] == 'Next Business Energy' && $user['step1']['plan_type'] == 'Elec' && $lead['Plan']['state'] != 'NSW'):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>All bills and communication will be sent via email</label>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if ($acquisition && $lead['Plan']['supplier'] == 'Next Business Energy' && $user['step1']['plan_type'] == 'Elec' && $lead['Plan']['state'] != 'QLD' && $plan['Plan']['res_sme'] == 'SME'):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>You agree to transfer from your current retailer for your electricity account to Next Business Energy for a supply period of 24 months. (There is no early termination fee for small customers)</label>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL', 'OVO Energy')) && $lead['Contact']['email'] != 'no@email.com.au'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Would you like to receive marketing information from <?php echo $lead['Plan']['supplier'];?> about special offers or new products?</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php echo ($lead['Billing']['electronic'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                                <?php if ($lead['Contact']['agl_email_bill'] > 0 || $lead['Contact']['powerdirect_email_bill'] > 0 || $lead['Contact']['pd_agl_email_bill'] > 0):?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>(State customer name) if you have not received your Plan Confirmation in 5 business days please make sure you have checked your email SPAM or junk folders as these communications may be filtered by your email provider.</label>
                                        </div>
                                    </div>
                                <?php endif;?>
                            <?php endif;?>
                            <?php if ($lead['Plan']['supplier'] == 'Momentum'): ?>
                                <?php if ($user['step1']['looking_for'] == 'Compare Plans'): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>How long have you lived at this property?</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <?php echo $lead['Billing']['how_long_lived']; ?>
                                        </div>
                                    </div>
                                <?php endif;?>
                            <?php endif;?>
                            <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Momentum', 'Alinta Energy'))):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Do you want direct debit on your account?</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php echo ($lead['Billing']['direct_debit'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if ($lead['Plan']['supplier'] == 'Origin Energy' && $lead['Contact']['email'] == 'no@email.com.au'):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>As you do not have an email your welcome pack will be posted to you.</label>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if ($lead['Plan']['supplier'] == 'Sumo Power' && in_array($lead['Plan']['state'], array('VIC', 'NSW'))): ?>
                                <div class="form-group row">
                                    <?php if ($lead['Contact']['email'] != 'no@email.com.au'):?>
                                        <div class="col-xs-12">
                                            <label>Your welcome pack will be sent to you via email. Would you like to receive your bills and other notices by email too?</label>
                                        </div>
                                    <?php else:?>
                                        <div class="col-xs-12">
                                            <label>As you don't have an email your Welcome pack and bills will be default to Postal. We will send this information to the postal address you have provided.</label>
                                        </div>
                                    <?php endif;?>
                                    <div class="col-xs-3">
                                        <?php echo ($lead['Billing']['how_receive_welcome_pack'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                    <?php if (($lead['Billing']['how_receive_welcome_pack'] == 0 || $lead['Contact']['email'] == 'no@email.com.au') && $lead['Plan']['state'] == 'VIC'):?>
                                    <div class="col-xs-12">
                                        <p>There is a $3.10 fee for paper bills per fuel. <br>That is $37.20 per year for electricity and if applicable $18.60 for gas.</p>
                                    </div>
                                    <?php endif;?>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Do you pay your bills at Australia Post?</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php echo ($lead['Billing']['australia_post_sumo'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                                <?php if ($lead['Billing']['australia_post_sumo'] > 0 && $lead['Plan']['state'] != 'NSW'):?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>There is a $2 fee per bill for paying over the counter at Australia Post.<br>
                                                That would be<br>
                                                $24 per year for your electricity account if you pay every bill at Australia Post.<br>
                                                and If applicable, $12 per year for your gas account if you pay every bill at Australia Post.</label>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Would you like Sumo Power to keep you updated with other great products and offers, from Sumo Power and other suppliers?</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php echo ($lead['Billing']['marketing_opt_out_sumo'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                                <?php if ($lead['Billing']['marketing_opt_out_sumo'] > 0):?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>If you change your mind, you can opt out at any time.</label>
                                        </div>
                                    </div>
                                <?php endif;?>
                            <?php endif; ?>
                            <?php if (in_array($lead['Plan']['supplier'], array('Lumo Energy'))) : ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Do you want to opt out of marketing material from <?php echo $lead['Plan']['supplier']; ?>?</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php echo ($lead['Billing']['marketing_opt_out'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($lead['Plan']['supplier'] == 'Lumo Energy'):?>
                                <?php if ($lead['Plan']['state'] == 'VIC' && in_array($user['step1']['plan_type'], array('Dual', 'Elec'))):?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>Electricity bills will be issued monthly if and when you have an active Smart Meter, otherwise they will be issued quarterly.</label>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if ($lead['Plan']['state'] == 'VIC' && in_array($user['step1']['plan_type'], array('Dual', 'Gas'))):?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>Your gas bills will be issued bi-monthly.</label>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if ($lead['Plan']['state'] == 'SA'):?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>You will receive your bills on a quarterly basis for electricity and/or gas.</label>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if ($user['step1']['looking_for'] == 'Compare Plans'):?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>Your electricity (and/or gas) account will switch to Lumo Energy as of the next scheduled meter read. Once your Cooling Off Period has completed, LUMO Energy will request for your account. You will receive one last bill from your current provider before switching.</label>
                                        </div>
                                    </div>
                                <?php endif;?>
                            <?php endif; ?>
                            <?php if ($lead['Plan']['supplier'] == 'Energy Australia'):?>
                                <?php if ($lead['Plan']['state'] == 'VIC') :?>
                                    <?php if ($user['step1']['plan_type'] == 'Dual') :?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>You agree to be billed monthly if your local distributor sends us monthly meter reads. If we don't receive monthly reads, we will send you a bill at least once every 3 months for electricity and/or at least once every 2 months for gas</label>
                                            </div>
                                        </div>
                                    <?php elseif ($user['step1']['plan_type'] == 'Elec') :?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>You agree to be billed monthly if your local distributor sends us monthly meter reads. If we don't receive monthly reads, we will send you a bill at least once every 3 months for electricity</label>
                                            </div>
                                        </div>
                                    <?php elseif ($user['step1']['plan_type'] == 'Gas') :?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>You agree to be billed monthly if your local distributor sends us monthly meter reads. If we don't receive monthly reads, we will send you a bill at least once every 2 months for gas</label>
                                            </div>
                                        </div>
                                    <?php endif;?>
                                <?php endif;?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Payment fees and charges will be detailed in your Welcome pack. Would you like to hear these now?</label>
                                    </div>
                                    <div class="col-xs-12">
                                        <?php echo ($lead['Billing']['hear_fees_charges_now'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                    <?php if ($lead['Billing']['hear_fees_charges_now'] > 0):?>
                                        <div class="col-xs-12">
                                            <a class="butn" href="#" target="_blank">Show Fees PDF</a>
                                        </div>
                                    <?php endif;?>
                                </div>
                            <?php endif;?>
                        <?php endif; ?>
                        <?php if ($lead['Plan']['supplier'] == 'ERM'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>If you are charged an exit fee from your current retailer ERM Business Energy will reimburse you for this through a credit on your invoice.  However you must provide evidence of being charged an exit fee such as a copy of the invoice. Do you understand this?</label>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo ($lead['Billing']['erm_understand_exit_fee'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>This offer is subject to a successful credit check. Do you consent to ERM Business Energy completing a credit check as part of the customer sign-up process?</label>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo ($lead['Billing']['erm_credit_check'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Do you understand that the structure of your tariff may be varied as a result of changes made by your distributor?</label>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo ($lead['Billing']['erm_understand_structure'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Do you consent to the transfer of your Electricity account to ERM Business Energy?</label>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo ($lead['Billing']['erm_consent_transfer'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Are you happy to receive marketing communications from ERM Business Energy?</label>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo ($lead['Billing']['erm_receive_communications'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                        <?php endif;?>
                        <div class="form-group row">
                            <div class="col-xs-12">
                                <label>Do you have any other family or friends I could call to offer a comparison?</label>
                            </div>
                            <div class="col-xs-3">
                                <?php echo ($lead['Billing']['other_family_friends'] > 0) ? 'Yes' : 'No'; ?>
                            </div>
                        </div>
                        <?php if ($lead['Billing']['other_family_friends'] > 0): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <a class="butn" href="/admin/customers/referral_program" target="_blank">Open Referral Page</a>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($lead['Plan']['product_name'] == 'BusinessSaver HC'): ?>
                            <?php if (in_array($user['step1']['plan_type'], array('Dual', 'Gas'))): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Can you please confirm that your annual cost for gas is higher or equal to $5500 per annum or use higher or equal to 20GJ per annum?</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php echo ($lead['Billing']['confirm_annual_gas'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($user['step1']['looking_for'] == 'Move Properties') : ?>
                    <h3 class="form-hdg">
                        <div class="fh-inr">Move In Details</div>
                    </h3>
                    <div class="form clearfix">
                        <div class="form-data">
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Move In Date</label>
                                </div>
                                <div class="col-xs-6">
                                    <?php echo $lead['MoveInDetail']['date']; ?>
                                </div>
                                <div class="col-xs-6">
                                    <button class="movein-fees-btn">Move-In Fees</button>
                                </div>
                            </div>
                            <?php if ($lead['Plan']['supplier'] == 'Sumo Power' && in_array($lead['Plan']['state'], array('VIC', 'NSW'))): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Previous Street Address</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php echo $lead['MoveInDetail']['previous_street']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Previous Suburb</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php echo $lead['MoveInDetail']['previous_suburb']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Previous State</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php echo $lead['MoveInDetail']['previous_state']; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Previous Postcode</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php echo $lead['MoveInDetail']['previous_postcode']; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <?php if ($user['step1']['plan_type'] == 'Dual'): ?>
                                        <?php if ($user['step1']['plan_type'] == 'Dual' && $lead['Plan']['state'] == 'VIC'): ?>
                                        <label>If your electricity meter is a remote read the connection fee will be $_____ including GST or if it is a manual read a fee of $______ including GST will be applied to your first electricity bill. A gas connection fee of $_____ including GST will be applied to your first gas bill. Do you understand this?</label>
                                        <?php else:?>
                                            <label>Can you please confirm you understand a connection fee of $_______ including GST will be applied to your first electricity bill and a connection fee of $_____ including GST will be applied to your first gas bill?</label>
                                        <?php endif;?>
                                    <?php elseif ($user['step1']['plan_type'] == 'Elec'):?>
                                        <?php if ($user['step1']['plan_type'] == 'Elec' && $lead['Plan']['state'] == 'VIC'):?>
                                        <label>Can you please confirm you understand If your meter is a remote read the connection fee will be $_____ including GST or if it is a manual read a fee of $______ including GST will be applied to your first electricity bill?</label>
                                        <?php else:?>
                                            <label>Can you please confirm you understand a connection fee of $_______ including GST will be applied to your first electricity bill?</label>
                                        <?php endif;?>
                                    <?php elseif ($user['step1']['plan_type'] == 'Gas'):?>
                                        <label>Can you please confirm you understand a connection fee of $_______ including GST will be applied to your first gas bill?</label>
                                    <?php endif;?>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo ($lead['MoveInDetail']['fee_advised'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                            <?php if (in_array($lead['Plan']['supplier'], array('Alinta Energy'))): ?>
                                <?php if (in_array($lead['Plan']['state'], array('VIC'))) :?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>A connection fee may apply, which will appear on your first Alinta Energy bill. These fees are set by the distributor and are subject to changes as determined by your distributor. Information on all fees and charges are located on their website at www.alintaenergy.com.au.</label>
                                        </div>
                                    </div>
                                <?php else:?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>A connection fee may apply, which will appear on your first Alinta Energy bill. These fees are set by the distributor or metering co-ordinator and are subject to changes as determined by your distributor or metering co-ordinator. Information on all fees and charges are located on their website at www.alintaenergy.com.au.</label>
                                        </div>
                                    </div>
                                <?php endif;?>
                            <?php endif;?>
                            <?php if (in_array($lead['Plan']['state'], array('NSW', 'VIC', 'SA', 'QLD')) && in_array($user['step1']['plan_type'], array('Dual', 'Elec'))): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>NMI Status</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php echo $lead['MoveInDetail']['nmi_status']; ?>
                                    </div>
                                </div>
                                <?php if ($lead['Plan']['state'] == 'QLD' && $lead['MoveInDetail']['nmi_status'] == 'De-energised (D)'): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>Energex is required a 5 hour window/timeframe to do a visual safety inspection to your new property. The examination requires full access to all buildings on the premises, including all electrical points inside the premises and any shed or a garage. Energex require someone over the age of 18 to be present for this timeframe.</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <?php echo ($lead['MoveInDetail']['visual_inspectioncon_confirm'] > 0) ? 'Yes' : 'No'; ?>
                                        </div>
                                    </div>
                                    <?php if ($lead['MoveInDetail']['visual_inspectioncon_confirm'] > 0): ?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>The time frames for a visual inspection are 8am to 1pm and 1pm to 6pm. Which would you prefer?</label>
                                            </div>
                                        </div>
                                    <?php else:?>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                <label>The only alternative is if the premise is completely vacant, with no personal items inside, you can leave the key in the meter box. If there are ANY items inside the property this may prevent or disrupt your connection.</label>
                                            </div>
                                        </div>
                                    <?php endif;?>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label>Visual Inspection Details</label>
                                        </div>
                                        <div class="col-xs-6">
                                            <?php echo $lead['MoveInDetail']['visual_inspection']; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if (in_array($user['step1']['plan_type'], array('Dual', 'Elec'))): ?>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Electricity meter location</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo ($lead['MoveInDetail']['electricity_meter'] == 'I') ? 'Inside' : 'Outside'; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Electricity connection fee type</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['MoveInDetail']['elec_connection_fee_type']; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (in_array($user['step1']['plan_type'], array('Dual', 'Gas'))): ?>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Gas meter location</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo ($lead['MoveInDetail']['gas_meter'] == 'I') ? 'Inside' : 'Outside'; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (in_array($user['step1']['plan_type'], array('Dual', 'Elec'))): ?>
                                <?php if ($lead['MoveInDetail']['nmi_status'] == 'Active (A)'): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>Your electricity is currently active, for your reading to occur there will need to be clear access to the meter(s) and main switch between 7AM and 10PM with no access restrictions such as locked meter, gates or unrestrained animals. You may also be connected remotely IF you have an active digital meter. Do you Understand?</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <?php echo ($lead['MoveInDetail']['clear_access'] > 0) ? 'Yes' : 'No'; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($lead['MoveInDetail']['nmi_status'] == 'Active (A)' && in_array($lead['Plan']['state'], array('QLD'))): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>If for any reason your power becomes de-energised between today and the move in date, please contact me back as we will need to make amendments to the connection request.</label>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if ($lead['MoveInDetail']['nmi_status'] == 'Active (A)' && in_array($lead['Plan']['state'], array('ACT', 'VIC', 'NSW', 'SA')) && $lead['Plan']['supplier'] != 'Origin Energy'): ?>
                                    <?php if (isset($elec_rate['ElectricityRate']['distributor']) && in_array($elec_rate['ElectricityRate']['distributor'], array('Ausgrid'))):?>
                                    <?php if (!in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL'))):?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>If for any reason your power becomes de-energised between today and the move in date, please turn your main switch to the off position between 7am & 10pm & ensure access is clear.</label>
                                        </div>
                                    </div>
                                    <?php endif;?>
                                    <?php else:?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>If for any reason your power becomes de-energised between today and the move in date, please turn your main switch to the off position between 7am & 10pm & ensure access is clear.</label>
                                        </div>
                                    </div>
                                    <?php endif;?>
                                <?php endif;?>
                                <?php if ($lead['MoveInDetail']['nmi_status'] == 'Active (A)' && in_array($lead['Plan']['state'], array('ACT', 'VIC', 'NSW', 'SA')) && $lead['Plan']['supplier'] == 'Origin Energy' && isset($elec_rate['ElectricityRate']['distributor']) && !in_array($elec_rate['ElectricityRate']['distributor'], array('Ausgrid'))): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>If for any reason your power becomes de-energised between today and the move in date, contact me back as we will need to make amendments to the connection request.</label>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if ($lead['MoveInDetail']['nmi_status'] == 'Active (A)' && in_array($lead['Plan']['state'], array('ACT', 'NSW')) && in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL', 'Origin Energy')) && isset($elec_rate['ElectricityRate']['distributor']) && in_array($elec_rate['ElectricityRate']['distributor'], array('Ausgrid'))): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>If for any reason your power becomes de-energised between today and the move in date, Someone over the age of 18 will need to remain on site on the day of connection.</label>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if (in_array($lead['Plan']['state'], array('VIC')) && $lead['Plan']['supplier'] == 'Powershop'): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>Charges may apply if multiple visits are required.</label>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if (in_array($lead['Plan']['state'], array('SA')) && $lead['MoveInDetail']['elec_connection_fee_type'] == 'SDFI'): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>A same day connection may take up to 12am midnight.</label>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if (in_array($lead['Plan']['state'], array('SA')) && $lead['Plan']['supplier'] == 'Origin Energy'): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>You may be charged a service order cancellation fee if you cancel or change your move request. This fee is a pass through from your distributor.</label>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if (in_array($lead['Plan']['state'], array('VIC', 'SA')) && $lead['MoveInDetail']['nmi_status'] == 'De-energised (D)'): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>As your electricity is currently de-energised you will need to have your main switch in the "Off" Position between 7am & 10pm on the day of connection, if it is not, this may affect or prevent your connection by the date required, you may also be connected remotely IF you have an active digital meter. Do you understand?</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <?php echo ($lead['MoveInDetail']['understand_main_switch_off'] > 0) ? 'Yes' : 'No'; ?>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if ($lead['Plan']['supplier'] == 'Origin Energy' && $lead['Plan']['state'] == 'NSW' && $lead['MoveInDetail']['nmi_status'] == 'De-energised (D)' && isset($elec_rate['ElectricityRate']['distributor']) && !in_array($elec_rate['ElectricityRate']['distributor'], array('Ausgrid'))):?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>As your electricity is De-Energised your distributor advises you MUST turn off all appliances in the property by 7am to 10pm on the date of connection to ensure there is no load on your meter. If this requirement is not actioned this may affect or prevent your connection of electricity by the date required.</label>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if ($lead['Plan']['supplier'] == 'Origin Energy' && $lead['Plan']['state'] == 'QLD' && $lead['MoveInDetail']['nmi_status'] == 'De-energised (D)'):?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>You won't be charged a connection fee for a Visual Safety Inspection.</label>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if (in_array($lead['Plan']['state'], array('ACT', 'NSW')) && !in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL', 'Origin Energy')) && $lead['MoveInDetail']['nmi_status'] == 'De-energised (D)' && isset($elec_rate['ElectricityRate']['distributor']) && in_array($elec_rate['ElectricityRate']['distributor'], array('Ausgrid'))) : ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>Your electricity is currently de-energised you will need to have your main switch in the "Off" Position between 7am & 10pm on the day of connection, if it is not, this may affect or prevent your connection by the date required, You may also be connected remotely IF you have an active digital meter. Do you understand?</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <?php echo ($lead['MoveInDetail']['understand_main_switch_off_act_nsw'] > 0) ? 'Yes' : 'No'; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (in_array($lead['Plan']['state'], array('ACT', 'NSW')) && !in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL', 'Origin Energy')) && $lead['MoveInDetail']['nmi_status'] == 'De-energised (D)' && isset($elec_rate['ElectricityRate']['distributor']) && !in_array($elec_rate['ElectricityRate']['distributor'], array('Ausgrid'))) : ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>Your electricity is currently de-energised you will need to have your main switch in the "Off" Position between 7am & 10pm on the day of connection, if it is not, this may affect or prevent your connection by the date required, You may also be connected remotely IF you have an active digital meter. Do you understand?</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <?php echo ($lead['MoveInDetail']['understand_main_switch_off_act_nsw2'] > 0) ? 'Yes' : 'No'; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (in_array($lead['Plan']['state'], array('ACT', 'NSW')) && in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL')) && $lead['MoveInDetail']['nmi_status'] == 'De-energised (D)' && isset($elec_rate['ElectricityRate']['distributor']) && !in_array($elec_rate['ElectricityRate']['distributor'], array('Ausgrid'))) : ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>Power is currently de-energised you will need to have your main switch in the "Off" Position between 7am & 10pm on the day of connection, if it is not, this may affect or prevent your connection by the date required, You may also be connected remotely IF you have an active digital meter. Do you understand?</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <?php echo ($lead['MoveInDetail']['understand_main_switch_off_act_nsw3'] > 0) ? 'Yes' : 'No'; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (in_array($lead['Plan']['state'], array('VIC','SA',)) && $lead['MoveInDetail']['nmi_status'] == 'De-energised (D)'): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>During the advised time frame, there will need to be clear access to the meter(s) & main switch with no access restrictions such as locked meter, gates or unrestrained animals. Do you understand?</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <?php echo ($lead['MoveInDetail']['understand_clear_access_vic_sa'] > 0) ? 'Yes' : 'No'; ?>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if (!in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL', 'Origin Energy', 'Energy Australia', 'Sumo Power')) && in_array($lead['Plan']['state'], array('NSW', 'ACT')) && $lead['MoveInDetail']['nmi_status'] == 'De-energised (D)'): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>During the advised time frame, there will need to be clear access to the meter(s) with no access restrictions such as locked meter, gates or unrestrained animals. Do you Understand?</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <?php echo ($lead['MoveInDetail']['understand_clear_access_act_nsw'] > 0) ? 'Yes' : 'No'; ?>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if (in_array($lead['Plan']['supplier'], array('AGL', 'Powerdirect', 'Powerdirect and AGL')) && in_array($lead['Plan']['state'], array('NSW', 'ACT')) && $lead['MoveInDetail']['nmi_status'] == 'De-energised (D)' && isset($elec_rate['ElectricityRate']['distributor']) && !in_array($elec_rate['ElectricityRate']['distributor'], array('Ausgrid'))): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>You'll need to have clear access to the meter(s) between this time frame with no access restrictions such as locked meter, gates or unrestrained animals. Do you Understand?</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <?php echo ($lead['MoveInDetail']['understand_clear_access_act_nsw2'] > 0) ? 'Yes' : 'No'; ?>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if ($lead['Plan']['state'] == 'QLD' && $lead['MoveInDetail']['nmi_status'] == 'De-energised (D)') : ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                    <?php if ($lead['Plan']['supplier'] == 'Origin Energy'):?>
                                            <label>If access arrangements are not kept, Energex will charge a Wasted Truck fee of up to $110.39 and will not reconnect the power.</label>
                                    <?php else:?>
                                            <label>If access arrangements are not kept, Energex will charge a Wasted Truck fee of up to ($110.39 for BAU connections OR $154.60 for SDFI connections) they will not reconnect the power.</label>
                                    <?php endif;?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if (in_array($lead['Plan']['supplier'], array('Momentum')) && $lead['Plan']['state'] == 'VIC' && $user['step1']['plan_type'] != 'Gas') : ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>To your knowledge, from the date the power at the property was (or will be) disconnected to the proposed date of re-connection are there any works at these premises which might involve someone coming into contact with exposed wiring?</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php echo ($lead['MoveInDetail']['works_planned'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (!in_array($lead['Plan']['supplier'], array('Momentum', 'Energy Australia', 'ERM')) && $lead['Plan']['state'] == 'VIC' && $user['step1']['plan_type'] != 'Gas') : ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Are there any electrical works planned prior to connection? (rewiring, switchboard works or replacement)</label>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo ($lead['MoveInDetail']['works_planned'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($user['step1']['plan_type'] == 'Gas'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>For your connection to occur there will need to be clear access to your gas meter with no access restrictions such as locked meter, gates or unrestrained animals.</label>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($user['step1']['plan_type'] == 'Dual'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>The same clear access requirements are needed for your gas connection to be completed.</label>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($user['step1']['plan_type'] != 'Gas' && $lead['MoveInDetail']['nmi_status'] == 'Active (A)' && $lead['Plan']['supplier'] == 'Momentum'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Your network has up to four business days from the requested service order date to attend the site and obtain a read. Momentum will commence billing from the date this read is obtained.</label>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (!in_array($lead['Plan']['supplier'], array('Next Business Energy', 'ActewAGL'))) : ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Are there any hazards near the meter that would prevent connection?</label>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo ($lead['MoveInDetail']['meter_hazard'] > 0) ? (($lead['MoveInDetail']['meter_hazard'] == 1) ? 'Yes' : 'Unsure') : 'No'; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($lead['MoveInDetail']['meter_hazard'] > 0): ?>
                                <?php if ($lead['Plan']['supplier'] == 'Momentum'): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>Hazards</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <?php echo ($lead['MoveInDetail']['hazards_momentum'] > 0) ? 'Yes' : 'No'; ?>
                                        </div>
                                    </div>
                                <?php elseif (!in_array($lead['Plan']['supplier'], array('Elysian Energy'))): ?>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>Hazards</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <?php echo ($lead['MoveInDetail']['hazards'] > 0) ? 'Yes' : 'No'; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($lead['Plan']['supplier'] == 'Energy Australia' && $lead['Plan']['state'] == 'VIC' && $user['step1']['plan_type'] != 'Gas'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Are there any alterations or renovations currently in progress at the property?</label>
                                    </div>
                                    <div class="col-xs-3">
                                        <?php echo ($lead['MoveInDetail']['alterations'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>Special Instructions For Access?</label>
                                </div>
                                <div class="col-xs-3">
                                    <?php echo ($lead['MoveInDetail']['special'] > 0) ? 'Yes' : 'No'; ?>
                                </div>
                            </div>
                            <?php if ($lead['MoveInDetail']['special'] > 0 || $lead['MoveInDetail']['works_planned'] > 0): ?>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>Any Special Instructions for access?</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo $lead['MoveInDetail']['special_details']; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($lead['Plan']['state'] == 'NSW' && in_array($lead['Plan']['supplier'], array('Origin Energy', 'AGL', 'Powerdirect', 'Powerdirect and AGL')) && $lead['MoveInDetail']['nmi_status'] == 'De-energised (D)' && isset($elec_rate['ElectricityRate']['distributor']) && in_array($elec_rate['ElectricityRate']['distributor'], array('Ausgrid'))): ?>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <label>As your power is De-energised, someone over the age of 18 will need to remain onsite on the day of connection. This is to ensure there are no electrical faults inside the property prior to connection. There will also need to be clear access to the meter. Can you confirm this?</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo ($lead['MoveInDetail']['confirm_remain_onsite'] > 0) ? 'Yes' : 'No'; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <label>As a 3rd party, we are not responsible for any personal reimbursements where your energy has not been connected due to main switch issues or access restrictions. If this happens You may be charged an additional fee for another connection</label>
                                </div>
                            </div>
                            <?php if (($user['step1']['plan_type'] == 'Dual' || $user['step1']['plan_type'] == 'Gas') && $lead['Plan']['supplier'] == 'Momentum'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>If the gas is currently on at your property, your network may attend the site up to 2 business days before or on the requested move in date to obtain a read. If there is no gas supply currently at the property, you will be invoiced for the network fees associated with your connection. Momentum will commence billing from the date the read is obtained.</label>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (($user['step1']['plan_type'] == 'Dual' || $user['step1']['plan_type'] == 'Gas') && $lead['Plan']['supplier'] != 'Momentum'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <label>Where there is no gas supply, the gas connection can take 5 to 15 business days to be completed by your distributor. If Gas is currently active the meter will generally be read in 5 business days.</label>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div></div></div></div>
    </section>
</div>
