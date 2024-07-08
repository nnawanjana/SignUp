<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'nusoap', array('file' => 'nusoap'.DS.'lib'.DS.'nusoap.php'));

Class SubmissionsController extends AppController {

	public $uses = array('ps_ApplicationTarget', 'ps_LeadTypes', 'Plan', 'ps_Users', 'ps_Webforms', 'Submission', 'StreetType', 'LeadType', 'ps_LeadTypeDefinition', 'qa_velocifypost');

	private $step1 = array();
	private $step2 = array();
	private $step3 = array();
	private $step4 = array();
	private $lead_id = "";
	private $user_state = "";
	private $user_plan = array();
	private $user_id;
	private $preFix_planDescriptionTemp = "The plan you're signing up for today is the [retailer] [product name] plan which gives you ";

	private $doc;
	private $xpath;

	private $proxyhost = 'https://service.leads360.com/ClientService.asmx';
	private $proxyport = '';
	private $proxyusername = LEADS360_USERNAME;
	private $proxypassword = LEADS360_PASSWORD;
	private $PublicSignupStatus = array('FieldID'=>507,'Activated'=>'Started', 'Complete'=>'Complete');
	private $PublicSignupStatusFieldId = 507;
	private $OnlineSignupInProgressStatusId = 279;
	private $OnlineNewId = 1;

	private $PROD_MODE = 1;
	private $UAT_MODE = 0;
	private $UAT_SPECIAL_MODE = 1;


	public $uiElms;

	public $components = array('FormSnapShot', 'PublicSignUp', 'Encryptor');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow();
		$this->layout = 'submissions';
	}

	public function index() {
	}

	private function SOAP_Operation($instruction = null){


		$client = new nusoap_client('https://service.velocify.com/ClientService.asmx?WSDL', 'wsdl', '', '', $this -> proxyusername, $this -> proxypassword);
		$client->soap_defencoding = 'UTF-8';
		$err = $client->getError();
		if ($err) {
			$this->log('<error1>' . $err . '</error1>', 'debug');
			exit();
		}

		$client->setUseCurl($this -> proxyhost);
		$proxy = $client->getProxy();

		$executionFlg = 0;

		if ($instruction['operation'] == 'GetLead'){
			$param = array('username' => $this -> proxyusername, 'password' => $this -> proxypassword, 'leadId' => (int)$instruction['leadid']);
			$result = $proxy->GetLead($param);
			$executionFlg = 1;
		}

		if ($instruction['operation'] == 'GetLeadCampaignValue'){
			$param = array('username' => $this -> proxyusername, 'password' => $this -> proxypassword, 'leadId' => (int)$instruction['leadid']);
			$result = $proxy->GetLead($param);
			$executionFlg = 1;
		}

		if ($instruction['operation'] == 'GetFullLead'){
			$param = array('username' => $this -> proxyusername, 'password' => $this -> proxypassword, 'leadId' => (int)$instruction['leadid']);
			$result = $proxy->GetLead($param);
			$executionFlg = 1;
		}

		if ($instruction['operation'] == 'ModifyLeadStatus'){
			$param = array('username' => $this -> proxyusername, 'password' => $this -> proxypassword, 'leadId' => (int)$instruction['leadid'], 'statusId' => (int)$instruction['signup_status']);
			$result = $proxy->ModifyLeadStatus($param);
			$executionFlg = 1;
		}

		if ($instruction['operation'] == 'GetStatuses'){
			$param = array('username' => $this -> proxyusername, 'password' => $this -> proxypassword);
			$result = $proxy->GetStatuses($param);
			$executionFlg = 1;
		}

		if ($instruction['operation'] == 'ModifyLeadField'){
			$param = array('username' => $this -> proxyusername, 'password' => $this -> proxypassword, 'leadId' => (int)$instruction['leadid'], 'fieldId' => (int)$instruction['fieldid'], 'newValue' => $instruction['fieldvalue']);
			$result = $proxy->ModifyLeadField($param);
			$executionFlg = 1;
		}
		if ($instruction['operation'] == 'GetStatuseID'){
			$param = array('username' => $this -> proxyusername, 'password' => $this -> proxypassword);
			$result = $proxy->GetStatuses($param);
			$executionFlg = 1;
		}
		/*
			$param = array('username' => $this -> proxyusername, 'password' => $this -> proxypassword);
			$result = $proxy->GetFields($param);
		*/

		if ($executionFlg == 0){
			exit();
		}
		// Check for a fault
		$return_data = "";

		if ($proxy->fault) {
			$return_data = '<fault>' . print_r($result) . '</fault>';

			$this->log('<fault>' . print_r($result) . '</fault>', 'debug');
		} else {
			// Check for errors
			$err = $proxy->getError();
			if ($err) {
				$return_data = '<error2>' . $err . '</error2>';

				$this->log('<error2>' . $err . '</error2>', 'debug');
			} else {
				$local_flg = 0;

				if ($instruction['operation'] == 'GetStatuseID'){
					$doc = new DOMDocument();
					$doc->loadXml($proxy->responseData);
					$xpath = new DOMXPath($doc);
					$xpath->registerNamespace("soap","http://schemas.xmlsoap.org/soap/envelope/");
					$xpath->registerNamespace("xmlns","https://service.velocify.com");

					$query = '//Statuses/Status[@StatusTitle="'. $instruction['fieldvalue'] .'"]';
					$xPath_result = $xpath->query($query, $doc);
					if (!is_null($xPath_result)) {
						$return_data = $xPath_result -> item(0) -> getAttribute('StatusId');
					}
					$local_flg = 1;
				}

				if ($instruction['operation'] == 'GetLeadCampaignValue') {
					$doc = new DOMDocument();
					$doc->loadXml($proxy->responseData);
					$xpath = new DOMXPath($doc);
					$xpath->registerNamespace("soap","http://schemas.xmlsoap.org/soap/envelope/");
					$xpath->registerNamespace("xmlns","https://service.velocify.com");

					$query = '//Lead/Campaign';
					$xPath_result = $xpath->query($query, $doc);

					$return_data = '<GetLead>' . $doc -> saveXML($xPath_result -> item(0)) . '</GetLead>';

					$local_flg = 1;

				}
				if ($instruction['operation'] == 'GetLead') {
					$doc = new DOMDocument();
					$doc->loadXml($proxy->responseData);
					$xpath = new DOMXPath($doc);
					$xpath->registerNamespace("soap","http://schemas.xmlsoap.org/soap/envelope/");
					$xpath->registerNamespace("xmlns","https://service.velocify.com");

					$query = '//Lead/Status';
					$xPath_result = $xpath->query($query, $doc);

					$return_data = '<GetLead>' . $doc -> saveXML($xPath_result -> item(0)) . '</GetLead>';
					$local_flg = 1;

				}

				if ($local_flg == 0) {
					$return_data = $proxy->responseData;
				}
			}
		}

		if ($this -> UAT_MODE){

			$this -> qa_velocifypost -> create();
			$this->qa_velocifypost->save(array('qa_velocifypost' => array(
				'lead_id' => (int)$this -> lead_id,
				'command' => $instruction['operation'],
				'post_string' => "",
				'response_string' => $return_data,
				'date_posted' => date('Y-m-d H:i:s')
			)));


		}

		return $return_data;
	}


	public function submissions($submission_key = null){

		/********
		* $ew contians a set of HTML fields and JavaScript variables that are to be rendered within the front end
		* $$concessions is an object that encapsulates services to produce tailored XML along with a given state criteria.  The XML is to be parsed into HTML elements by the front end JavaScript
		* $tac is an object that encapsulates services to produce a HTML string that describe terms and conditions n accordance with given criteria
		*/


		$decryped_string = $this -> Encryptor -> encryptor('decrypt',$submission_key);
		if (empty($decryped_string)){
			$this->redirect( '/about-you' );
		}



		$id_pair = explode("/",$decryped_string);
		$submission_id = $id_pair[0];
		$plan_id = $id_pair[1];

		$qa_mode = false;
		$qa_html_navigator = "qa_modal";

		if ($this->Session->check('QA')){
			$qa_mode = true;
			$qa_html_navigator = '';
		}



		$outcome = $this->ps_Webforms->find('first', array(
		    'conditions' => array('webform_id =' => (int) $submission_id),
            'fields' => array('webform_xml','leadid')
		));

		$l_id = $outcome['ps_Webforms']['leadid'];
		$signup_completion_status = array('leadid' => $l_id, 'status' =>'In Process');
		$this->Session->write('signup_completion_status', $signup_completion_status);

		$this->doc = new DOMDocument();
		$this->doc->formatOutput = true;
		$this->doc->loadXml($outcome['ps_Webforms']['webform_xml']);
		$this->xpath = new DOMXPath($this->doc);

		$elm_elec = $this->xpath->query("element[@id='total_inc_discount_elec']/data_record/data");
		$elm_gas = $this->xpath->query("element[@id='total_inc_discount_gas']/data_record/data");
		$plan_ids = $this->xpath->query("element[@id='planid']/data_record/data");
		$elm_states = $this->xpath->query("element[@id='state']/data_record/data");
		$elm_fuels = $this->xpath->query("element[@id='plan_type']/data_record/data");
		$elm_res_smes = $this->xpath->query("element[@id='customer_type']/data_record/data");
		$elm_mobiles = $this->xpath->query("element[@id='mobile']/data_record/data");
		$elm_emails = $this->xpath->query("element[@id='email']/data_record/data");
		$elm_suburbs = $this->xpath->query("element[@id='suburb']/data_record/data");
		$elm_postcodes = $this->xpath->query("element[@id='postcode']/data_record/data");

		$planSource = $this->Plan->find('first', array(
			'conditions' => array('id' => (int)$plan_id),
			'fields' => array('retailer', 'package', 'product_name', 'signup_summary', 'res_sme')
		));

		$this->Session->write('submissionID', (int) $submission_id);

		$preFix_planDescription = str_replace("[retailer]", $planSource['Plan']['retailer'], $this -> preFix_planDescriptionTemp);
		$preFix_planDescription = str_replace("[product name]", $planSource['Plan']['product_name'], $preFix_planDescription);

		$plan_estimates['elec'] = $elm_elec -> item(0) -> getAttribute('value');
		$plan_estimates['gas'] = $elm_gas -> item(0) -> getAttribute('value');
		$plan_estimates['retailer'] = $planSource['Plan']['retailer'];
		$plan_estimates['product_summary'] = $preFix_planDescription . " " . lcfirst ($planSource['Plan']['signup_summary']);
		$this->Session->write('plan_estimates', $plan_estimates);


		$HtmlNodeFactory = $concessions = $this->Components->load('HtmlNodeFactory');

		$concessions = $this->Components->load('Concessions', array('_state'=>$elm_states -> item(0) -> getAttribute('value'), '_retailer' => $plan_estimates['retailer'], '_factory' => $HtmlNodeFactory));

		$tac = $this->Components->load('TermsAndConditions', array('_retailer'=>$plan_estimates['retailer'],'_state'=>$elm_states -> item(0) -> getAttribute('value'),'_fuel'=>$planSource['Plan']['package'],'_entityClass'=>$planSource['Plan']['res_sme'],'_plan'=>$planSource['Plan']['product_name']));

		$planOptions = $this->Components->load('PlanOptions', array('_retailer' => $plan_estimates['retailer'], '_state'=>$elm_states -> item(0) -> getAttribute('value'), '_plan'=>$planSource['Plan']['product_name'], '_factory' => $HtmlNodeFactory));

		/******
		* Invoking getXMLFieldsSnapShot method in FormSnapShot to provide a template for PublicSignUp component to generate UI control elements
		*/
		$PublicUISnapshotImg = $this -> FormSnapShot -> getPublicSignUpSnapShot();
		$this -> PublicSignUp -> FormSnapShot = $PublicUISnapshotImg['mappingTxt'];

		/*******
		* Invoking createProperty in PublicSignUp component to merge Concessions, Terms and Conditions, and Plan Options
		* By doing so, all HTML Control elements are encapsulated within the PublicSignUp object and are each exposed as its public property
		*/
		$this -> PublicSignUp -> createProperty($concessions, $tac, $planOptions);

		/*********
		*  Generating UI rules in JSON.  UI rules are dicated by variants such as retailers, plan types etc.  This will be transmitted to the front end layer
		*  for invocation by JavaScript
		*  Client JavaScript is responsible for invoking UI rules eg; show/hide, pre-determined values, inheritated value mapping etc
		*  Note for Future enhancement: the way each condition is specified by hard-coded retailers needs to be transformed into a rule based instruction, which
		*  effectively passed in variable than the hard-coded text
		*/

		$uiInstructor = array();
		$uiInstructor["retailer"] = $plan_estimates['retailer'];
		$uiInstructor["hide_show"] = array();

		if (($plan_estimates['retailer'] == "Origin Energy") || ($plan_estimates['retailer'] == "Powershop")){
			$uiInstructor["hide_show"][] = array("f_56"=>"hide", "f_57"=>"hide", "f_58"=>"hide", "f_59"=>"hide", "f_60"=>"hide");
		}

		if (($plan_estimates['retailer'] == "Sumo Energy") || ($elm_states -> item(0) -> getAttribute('value') == "SA")){
			$uiInstructor["hide_show"][] = array("f_61"=>"hide", "f_62"=>"hide", "f_63"=>"hide", "f_64"=>"hide", "f_65"=>"hide", "f_66"=>"hide");
		}

		if ($planSource['Plan']['package'] == "Elec"){
			$uiInstructor["hide_show"][] = array("f_95" => "hide", "f_60" => "hide");
		}

		if ($planSource['Plan']['package'] == "Gas"){
			$uiInstructor["hide_show"][] = array("f_94" => "hide", "f_60" => "hide");
		}
		if ($planSource['Plan']['package'] == "Dual"){
			$uiInstructor["hide_show"][] = array("f_60" => "hide");
		}

		$uiInstructor["preconcession_show_hide"]= $this -> PublicSignUp -> getPreConcessionQuestionList();

		$uiInstructor["planOptions_show_hide"] = $planOptions -> getChildPlanOptionsElementList();

		$uiInstructor["pre_select"] = array();
		$uiInstructor["pre_select"]["input"] = array("f_47"=>$elm_mobiles -> item(0) -> getAttribute('value'), "f_49"=>$elm_emails -> item(0) -> getAttribute('value'), "f_74"=>$elm_suburbs -> item(0) -> getAttribute('value'), "f_75"=>$elm_postcodes -> item(0) -> getAttribute('value'));
		$uiInstructor["pre_select"]["select"] = array("f_76"=>$elm_states -> item(0) -> getAttribute('value'));

		$uiInstructor["display_cost_estimate"] = $planSource['Plan']['package'];

		$ui_instruction_String = json_encode($uiInstructor);

		$this->Session->write('ui_instruction_String', $ui_instruction_String);

		/****  UI customisation ends ******/

		$ew = $this -> PublicSignUp;


		$this -> set(compact('ew', 'plan_estimates', 'ui_instruction_String', 'qa_html_navigator', 'l_id'));
	}

	public function finalise(){

		//TO do: A user is in the online signup form.  Got a call from the customer servixe, but decided to submit anyway
		//Should be prevent from happening (add a process (methods) in the finalise method of the Submission class


		$tempReturn = array();
		$qa_mode = false;
		if ($this->request->is('post') || $this->request->is('put')) {

			$data = json_decode(file_get_contents("php://input"), true);

			$submission_id = $this->Session->read('submissionID');

			if ($this->Session->check('QA')){
				$temp_qa = $this->Session ->read('QA');
				if ((int)$temp_qa['MODE']['UAT'] == 1){
					$this -> UAT_MODE = 1;
				}
				$qa_mode = true;

				$submission_id = $this->Session->read('submissionID');
			}


			$this-> ps_ApplicationTarget -> create();
			$target_id = $this->ps_ApplicationTarget->find('first', array(
				'conditions' => array('target' => 'publicCompare'),
				'fields' => array('target_id')
			));


			$this->ps_Webforms->create();

			//add condition with the web template and app target

			$outcome = $this->ps_Webforms->find('first', array(
				'conditions' => array('webform_id =' => (int) $submission_id, 'target_id' => (int)$target_id['ps_ApplicationTarget']['target_id']),
				'fields' => array('webform_xml', 'user_id', 'leadid')
			));

			$this -> user_id = (int)$outcome['ps_Webforms']['user_id'];
			$this -> lead_id = (int)$outcome['ps_Webforms']['leadid'];



			/*****
			** Okkey Sumiyoshi
			** 25/01/2016
			** making sure that the sale is not complete on the current lead id by on-floor sales agents
			**/
			$status_mode = "";
			$submission_permit = 0;

			$velodify_lead = $this -> SOAP_Operation(array(
				'operation' => 'GetLead',
				'leadid' => (int)$this -> lead_id,
				'signup_status' => 0
			));

			$doc = new DOMDocument();
			$doc->loadXml($velodify_lead);
			$xpath = new DOMXPath($doc);

			$lead_status = 	$xpath ->evaluate('/GetLead/Status', $doc);

			if (!is_null($lead_status)) {
				foreach ($lead_status as $lead) {
					$status_mode = $lead -> getAttribute('StatusTitle');
				}
			} else {
				//nothing has come back from Velocify so to throw an exception
			}

			if ((strtoupper($status_mode) == '*TESTSTATUS')||(strtoupper($status_mode) == '*TESTSTATUS2')){
				$status_mode = 'NEW';
			}

			if ((strtoupper($status_mode) == 'NEW')||(strtoupper($status_mode) == 'ONLINE SIGNUP IN PROGRESS')){
				$submission_permit = 1;
			}




			if ($submission_permit == 0){
				return new CakeResponse(array(
					'body' => json_encode(array(
						'outcomes' => "unsuccessful",
						'message' => $this -> lead_id
					)),
					'type' => 'json',
					'status' => '201'
				));
			}


			$this->doc = new DOMDocument();
			$this->doc->loadXml($outcome['ps_Webforms']['webform_xml']);
			$this->xpath = new DOMXPath($this->doc);

			$elm_states = $this->xpath->query("element[@id='state']/data_record/data");
			$this -> user_state = $elm_states -> item(0) -> getAttribute('value');

			$elec_current_suppliers = $this->xpath->query("element[@id='elec_supplier']/data_record/data");

			$gas_current_suppliers = $this->xpath->query("element[@id='gas_supplier']/data_record/data");


			$plan_ids = $this->xpath->query("element[@id='planid']/data_record/data");

			$planSource = $this->Plan->find('first', array(
				'conditions' => array('id' => (int)$plan_ids -> item(0) -> getAttribute('value')),
				'fields' => array('retailer', 'package', 'product_name', 'product_summary', 'signup_summary', 'res_sme', 'contract_length')
			));


			$plan_estimates['retailer'] = $planSource['Plan']['retailer'];
			$plan_estimates['product_summary'] = $planSource['Plan']['product_summary'];


			$planName = $planSource['Plan']['product_name'];
			$usage = $planSource['Plan']['res_sme'];
			$plan_id = (int)$plan_ids -> item(0) -> getAttribute('value');
			$plan_retailer = $planSource['Plan']['retailer'];
			$plan_energy_type = $planSource['Plan']['package'];
			$plan_transition_type = "Transfer";




			$velocifyxmltxt = $this -> FormSnapShot -> getVelocifyMappingFields();




			/*******
			 Date: 08/11/2015
			 Okkey Sumiyoshi
			 The following code exclusively designed to backend processing on Velocify mapping, thus no affect on UI
			 These lines are commented while a review on Velocify mapping scheme is underway
			 */

			/* Getting Signup form Snapshot template image for signup form*/
			$snapshottxt = $this -> FormSnapShot -> getPublicSignUpSnapShot();

			/* Generating a Snapshot template image for comapre price process */
			$publicCompareSnapshottxt = $this -> FormSnapShot -> getPublicCompareSnapShot();


			/* Generating a Snapshot template image concerning Terms and Conditions */
			$tac = $this->Components->load('TermsAndConditions', array('_retailer'=>$plan_retailer,'_state'=>$this -> user_state,'_fuel'=>$plan_energy_type,'_entityClass'=>$usage,'_plan'=>$planName));
			$tac -> setComponent();
			$tacDataImage = $tac -> getTACSnapShotNodeImage();
			$tacMapperTemplate = $this -> FormSnapShot -> getSnapShotTemplate('tacSnapShot');

			/* Generating a Snapshot template image Concerning Concessions */
			$concessions = $this->Components->load('Concessions', array('_state'=>$this -> user_state, '_retailer'=>$plan_retailer));
			$concessionDataImage = $concessions -> getSnapShotSource();
			$concessionMapperTemplate = $this -> FormSnapShot -> getSnapShotTemplate('concessionsSnapShot');

			/* Generating a Snapshot template image for Plan options */
			$planOptions = $this->Components->load('PlanOptions', array('_retailer'=>$plan_retailer, '_state'=>$this -> user_state,'_plan'=>$planName));
			$planOptionsDataImage = $planOptions -> getSnapShotSource();
			$planOptionsMapperTemplate = $this -> FormSnapShot -> getSnapShotTemplate('planOptionsSnapShot');


			/* Generate SubmissionHandler object for processing data mapping */
			$ew = $this->Components->load('SubmissionHandler', array('velocifyxmltxt'=>$velocifyxmltxt['mappingTxt'], 'snapshottxt'=>$snapshottxt['mappingTxt'], 'userid'=>$this -> user_id));

			$ewPublicProperties = array(
				'retailer' => $plan_retailer,
				'ussage' => $usage,
				'package' => $plan_energy_type,
				'elec_current_supplier' => $elec_current_suppliers -> item(0) -> getAttribute('value'),
				'gas_current_supplier' => $gas_current_suppliers -> item(0) -> getAttribute('value'),
				'productName' => $planName,
				'contractLength' => $planSource['Plan']['contract_length'],
				'user_state' => $this -> user_state
			);
			$ew -> setSubmissionHandler($ewPublicProperties);

			$outcomes = array();


			$submission = array();
			$submission['submitted'] = array();
			$submitString = "";
			$new_lead_status = '';

			/* Process data mapping on signup form snapshot image */
			foreach($data['submission'] as $key => $value){
				$ew -> addValueToSnapShot($key, $value);
			}


			/* update the user table with the submitted account information */

			$ew -> updateUserInformation(array(
				'user_title' => $data['submission'][$ew -> title_key_id],
				'user_firstname' => $data['submission'][$ew -> firstname_key_id],
				'user_surname' => $data['submission'][$ew -> surname_key_id],
				'user_mobile' => $data['submission'][$ew -> mobile_key_id],
				'user_email' => $data['submission'][$ew -> email_key_id]
			));


			/* Save the snapshot image to the databsase, return value is the id in the webform table */
			$snapshot_id = $ew -> saveSignupSnapShot($this -> lead_id, $snapshottxt['target_id'], $snapshottxt['id']);

			/* Add Terms and Conditions, Plan Options, and Concessions to the signup form snapshot */
			$savedConcessionSnapshot_id = $ew -> setConcessionSnapShot($snapshot_id, $concessionDataImage, $concessionMapperTemplate, $data['concessions']);
			$savedTACSnapshot_id = $ew -> setTACSnapShot($snapshot_id, $tacDataImage, $tacMapperTemplate);
			$savedPlanOptionSnapshot_id = $ew -> setPlanOptionsSnapShot($snapshot_id, $planOptionsDataImage, $planOptionsMapperTemplate, $data['planOptions']);

			//Process generating Velocify post request string
			$outcomes = $ew -> mapVelocify();

			//generate a submit string to Velocify

			foreach ($outcomes as $key => $value){
				$submission['submitted'][$key] =  $value;
			}

			$this->ps_LeadTypeDefinition->create();

			$lead_type = $this->ps_LeadTypeDefinition->find('first', array(
				'conditions' => array(
					'retailer' => $planSource['Plan']['retailer'],
					'res_sme' => $planSource['Plan']['res_sme'],
					'looking_for' => 'Compare Plans'
				)
			));

			if ($lead_type) {



				$submission['submitted']['submitted[LeadType]']= $lead_type['ps_LeadTypeDefinition']['lead_type'];
				$submission['submitted']['submitted[status]']= $lead_type['ps_LeadTypeDefinition']['lead_status'];

				$new_lead_status = $lead_type['ps_LeadTypeDefinition']['lead_status'];

			}

			if ($qa_mode){
				if (!$this -> UAT_MODE){
					unset($submission['submitted']);
					$submission['submitted'] = array();
					//to look into this on 16/01/2016
					foreach ($outcomes as $key => $value){
						$keyString = str_replace("submitted[", "", $key);
						$keyString = str_replace("]", "", $keyString);
						$submission['submitted'][$keyString] =  $value;
					}
					$submission['submitted']['LeadType'] = $lead_type['ps_LeadTypeDefinition']['lead_type'];

				}

			}


			if ($qa_mode){

				$saved_ids = array(
					'submission'=>$snapshot_id,
					'concession'=>$savedConcessionSnapshot_id,
					'tac'=>$savedTACSnapshot_id,
					'planOptions' =>$savedPlanOptionSnapshot_id,
					'lead_type' => $lead_type['ps_LeadTypeDefinition']['lead_type']);
			} else {
				$saved_ids = array(
					'submission'=>0,
					'concession'=>0,
					'tac'=>0,
					'planOptions' =>0);
			}

		if ($this -> UAT_MODE){

			$this -> qa_velocifypost -> create();
			$this->qa_velocifypost->save(array('qa_velocifypost' => array(
				'lead_id' => (int)$this -> lead_id,
				'command' => 'POST_JSON',
				'post_string' => json_encode($submission),
				'response_string' => "",
				'date_posted' => date('Y-m-d H:i:s')
			)));


		}
			$request = http_build_query($submission['submitted'], '', '&');

			if (($this -> PROD_MODE)||($this -> UAT_MODE)){
				$updateLeadStatusResponse = '';
			} else {
				$updateLeadStatusResponse = 1;
			}

			$velocifyResponseString = '';

			if ($this -> invokeVelocifyTransaction($request)){

				if ($qa_mode){
					$velocifyResponseString = $this->Session ->read("velocifyResponse");
					$this->Session ->write("velocifyResponse","");
				}

				if (($this -> PROD_MODE)||($this -> UAT_MODE)){

					$leadStatusID = '';
					//Update the filed value of public signup status
					$leadUpdates = $this -> SOAP_Operation(array(
						'operation' => 'ModifyLeadField',
						'leadid' => (int)$this -> lead_id,
						'fieldid' => $this -> PublicSignupStatusFieldId,
						'fieldvalue' => 'Completed'
					));

					/*
					if (!empty($new_lead_status)){
						$leadStatusID = $this -> SOAP_Operation(array(
						'operation' => 'GetStatuseID',
						'fieldvalue' => $new_lead_status
						));
						if (!empty($leadStatusID)){
							$updateLeadStatusResponse = $this -> SOAP_Operation(array(
								'operation' => 'ModifyLeadStatus',
								'leadid' => (int)$this -> lead_id,
								'fieldid' => $this -> PublicSignupStatusFieldId,
								'signup_status' => (int)$leadStatusID
							));
						}

					}
					*/

				}

				//The session variable "signup_completed" is consumed at "addLeadToVelocify" method in ToolsController class
				//to determine whether it should force to generate a new lead id.
				$this->Session->write('signup_completed', 'completed');


				$signup_completion_status = $this->Session->read('signup_completion_status');
				$signup_completion_status['status'] = 'Completed';
				$this->Session->write('signup_completion_status', $signup_completion_status);
				/********/


				return new CakeResponse(array(
					'body' => json_encode(array(
						'outcomes' => ($qa_mode)? $submission:$outcomes,
						'velocify' => $submission,
						'savedIDs'=> $saved_ids,
						'compare_snapshot_id' => $submission_id,
						'velocifyResponse' =>$velocifyResponseString
					)),
					'type' => 'json',
					'status' => '201'
				));



			} else {
				$this->Session->write('signup_completed', 'failed due to Velocify Error');




				return new CakeResponse(array(
					'body' => json_encode(array(
						'outcomes' => "unsuccessful",
						'message' => (int)$this -> lead_id,
						'velocifyResponse' =>$velocifyResponseString
					)),
					'type' => 'json',
					'status' => '201'
				));

			}
		}
	}

	private function invokeVelocifyTransaction($requestStr = null){
    	$lead_id = $this->lead_id;





    	/**** SHOULD NEVER TRUST an external service to respond with value that we expect ****/
    	$campaign_id = "x_default";

    	$request = array(
			'operation' => 'GetLeadCampaignValue',
			'leadid' => (int)$lead_id
		);




		$velodify_lead = $this  -> SOAP_Operation($request);




		$doc = new DOMDocument();
		$doc->loadXml($velodify_lead);
		$xpath = new DOMXPath($doc);
		$campaign = $xpath ->evaluate('/GetLead/Campaign', $doc);
		if (!is_null($campaign)) {
    		foreach ($campaign as $lead) {
        		$campaign_id = $lead -> getAttribute('CampaignId');
            }
        }

		if ($this -> UAT_MODE){

			$this -> qa_velocifypost -> create();
			$this->qa_velocifypost->save(array('qa_velocifypost' => array(
				'lead_id' => (int)$lead_id,
				'command' => 'campaign_ID',
				'post_string' => '',
				'response_string' => $campaign_id,
				'date_posted' => date('Y-m-d H:i:s')
			)));


		}

		if (($this -> PROD_MODE)||($this -> UAT_SPECIAL_MODE)){
			if ($campaign_id != "x_default"){
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "https://secure.velocify.com/Update.aspx?Provider=VoucherStore&Client=41189&CampaignId={$campaign_id}&XmlResponse=True&LeadId={$lead_id}");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $requestStr);
				$response = curl_exec($ch);
				curl_close($ch);

				if ($this -> UAT_MODE){

					$this -> qa_velocifypost -> create();
					$this->qa_velocifypost->save(array('qa_velocifypost' => array(
						'lead_id' => (int)$lead_id,
						'command' => 'POST',
						'post_string' => $requestStr,
						'response_string' => $response,
						'date_posted' => date('Y-m-d H:i:s')
					)));


				}


				if ($this->Session->check('QA')){
					$this->Session ->write("velocifyResponse", $this -> qa_velocifypost -> id);
				}


			}

			if ((strpos($response, 'Success') !== false)&&($campaign_id != "x_default")){
				return true;

			} else {
				return false;
			}

		} else {
			//escape for transactions under NON-PROD mode
			return true;
		}
	}

	/*********************
	* This function is a copy from the toolscontroller
	* It is recommended to be moved to component providing an access from multuple controller class
	*/
	public function street_type() {
		if (isset($this->request->query) && !empty($this->request->query)) {
        	$return = array(
            	'items' => array()
        	);
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

	public function signup_complete(){
		$ui_instruction_String = "";
		$plan_estimates = array();
		$plan_estimates['elec'] = "-";
		$plan_estimates['gas'] = "-";
		$plan_estimates['retailer'] = "-";
		$plan_estimates['product_summary'] = "-";
		if ($this->Session->check('plan_estimates')){
			$plan_estimates = ($this->Session->read('plan_estimates'));
		}
		if ($this->Session->check('ui_instruction_String')){
			$ui_instruction_String = ($this->Session->read('ui_instruction_String'));
		}

		$this->set(compact('plan_estimates', 'ui_instruction_String'));
	}

	public function getCurrentTime(){

		return new CakeResponse(array(
			'body' => json_encode(array(
				'status' => 'successful',
				'message' => date("H:i:s")
			)),
			'type' => 'json',
			'status' => '200'
		));
	}

	public function forceTimeOut(){
		//update the Velocify Field

		$request = array(
			'operation' => 'ModifyLeadField',
			'leadid' => (int)$_POST['leadid'],
			'signup_status' => '',
			'fieldId' => $this -> PublicSignupStatusFieldId,
			'fieldvalue' => ''
		);

		$outcome = $this -> SOAP_Operation($request);

		$request_new = array(
			'operation' => 'ModifyLeadStatus',
			'leadid' => (int)$_POST['leadid'],
			'signup_status' => $this -> OnlineNewId,
			'fieldId' => '',
			'fieldvalue' => ''
		);

		$outcome_new = $this -> SOAP_Operation($request_new);

		//look for success

		return new CakeResponse(array(
			'body' => json_encode(array(
				'status' => 'complete',
				'message' => 'Check Velocify Lead status by QA Tool'
			)),
			'type' => 'json',
			'status' => '200'
		));
	}
}
