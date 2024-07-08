<?php
App::uses('Component', 'Controller');

class SubmissionHandlerComponent extends Component{

	private $doc;
	private $docVelocify;
	private $xpath;
	private $xpathVelocify;
	private $snapshotImgDoc;

	protected static $retailer = "";
	protected static $package = "";
	protected static $fuel_type = "";
	protected static $customer_type = "";
	protected static $elec_current_supplier = "";
	protected static $gas_current_supplier = "";
	protected static $elec_new_supplier = "";
	protected static $gas_new_supplier = "";
	protected static $TransitionType = "Transfer";
	protected static $elec_distributor = "";
	protected static $gas_distributor = "";
	protected static $productName = "";
	protected static $contractLength = "";
	protected static $user_state = "";
	protected static $elec_Product_Field_Value = "";
	protected static $gas_Product_Field_Value = "";

	public static $planOptionImg;


	private $model_planOptionSnapShot;
	private $model_TACSnapShot;
	private $model_ConcessionSnapShot;
	private $model_webforms;

	public	$user_id;

	private $TAC_ID = "Reserved_TAC";

	private $submissionConfig = array();

	public $title_key_id = "";
	public $firstname_key_id = "";
	public $surname_key_id = "";
	public $mobile_key_id = "";
	public $email_key_id = "";

	protected $_defaults = array(
		'velocifyxmltxt' => null,
		'snapshottxt' => null,
		'userid' => null
	);

	public function __construct(ComponentCollection $collection, $settings = array()) {

		$this->submissionConfig = array_merge($this->_defaults, $settings);

	}

	function setSubmissionHandler($ewPublicProperties) {

		$this->doc = new DOMDocument();
		$this->doc->formatOutput = true;
		$this->doc->loadXml($this->submissionConfig['snapshottxt']);
		$this->xpath = new DOMXPath($this->doc);

		$this->docVelocify = new DOMDocument();
		$this->docVelocify->loadXml($this->submissionConfig['velocifyxmltxt']);
		$this->xpathVelocify = new DOMXPath($this->docVelocify);

		self::$retailer = $ewPublicProperties['retailer'];
		self::$customer_type = $ewPublicProperties['ussage'];
		self::$package = $ewPublicProperties['package'];
		self::$elec_current_supplier = $ewPublicProperties['elec_current_supplier'];
		self::$gas_new_supplier = $ewPublicProperties['gas_current_supplier'];
		self::$productName = $ewPublicProperties['productName'];
		self::$contractLength = $ewPublicProperties['contractLength'];
		self::$user_state = $ewPublicProperties['user_state'];
		$this -> getProductType();


		/******
		** Okkey Sumiyoshi
		** 22/01/2016
		** preparing id value of information that are required to updated the user table
		**/
		$PrimaryContactNodes = $this->xpath->evaluate("publicSignUp/element[@class='AccountDetails.PrimaryContact']", $this->doc);

		foreach ($PrimaryContactNodes as $PrimaryContactNode){

			if ($PrimaryContactNode -> getAttribute('name') == 'title'){
				$this -> title_key_id = $PrimaryContactNode -> getAttribute('id');
			}
			if ($PrimaryContactNode -> getAttribute('name') == 'firstName'){
				$this -> firstname_key_id = $PrimaryContactNode -> getAttribute('id');

			}
			if ($PrimaryContactNode -> getAttribute('name') == 'surName'){
				$this -> surname_key_id = $PrimaryContactNode -> getAttribute('id');
			}
			if ($PrimaryContactNode -> getAttribute('name') == 'mobileNumber'){
				$this -> mobile_key_id = $PrimaryContactNode -> getAttribute('id');
			}
			if ($PrimaryContactNode -> getAttribute('name') == 'email'){
				$this -> email_key_id = $PrimaryContactNode -> getAttribute('id');
			}
		}

   }

	private function getProductType(){
		/***
		** Okkey Sumiyoshi
		** 07/02/2016
		** Product
		******/

		$states_arr = unserialize(AU_STATES);
		$productTypeFieldValueSource = ClassRegistry::init('Product');

		if (strtoupper(self::$package) == "DUAL"){
			$GasProductTypeFieldValue = $productTypeFieldValueSource->find('first', array(
				'conditions' => array('fuel' => "Gas", 'state' => $states_arr[self::$user_state], 'retailer' => self::$retailer, 'res_sme' => self::$customer_type, 'product_name' => self::$productName),
				'fields' => array('field_value')
			));
			self::$gas_Product_Field_Value = $GasProductTypeFieldValue['Product']['field_value'];

			$ElecProductTypeFieldValue = $productTypeFieldValueSource->find('first', array(
				'conditions' => array('fuel' => "Elec", 'state' => $states_arr[self::$user_state], 'retailer' => self::$retailer, 'res_sme' => self::$customer_type, 'product_name' => self::$productName),
				'fields' => array('field_value')
			));
			self::$elec_Product_Field_Value = $ElecProductTypeFieldValue['Product']['field_value'];

		} else {
			$productTypeFieldValue = $productTypeFieldValueSource->find('first', array(
				'conditions' => array('fuel' => self::$package, 'state' => $states_arr[self::$user_state], 'retailer' => self::$retailer, 'res_sme' => self::$customer_type, 'product_name' => self::$productName),
				'fields' => array('field_value')
			));

			if (strtoupper(self::$package) == "ELEC"){
				self::$elec_Product_Field_Value = $productTypeFieldValue['Product']['field_value'];
			}

			if (strtoupper(self::$package) == "GAS"){
				self::$gas_Product_Field_Value = $productTypeFieldValue['Product']['field_value'];
			}

		}


	}

   	private function getXMLDocument(){
   		$xmlDoc = new DOMDocument();
   		$xmlDoc -> formatOutput = true;
   		return $xmlDoc;
   	}

    public function addValueToSnapShot($key, $value){
		//a method adding a valud to the tempalte snapshot XML
		$elm = $this->xpath->query("element[@id='" . $key . "']");
		if (!is_null($elm)){
			$dataNode = $this->doc->createElement('data');
			$dataNode -> setAttribute("value", $value);
			$dataNode -> setAttribute("user", $this->submissionConfig['userid']);
			$dataNode -> setAttribute("date", date("d-m-Y H:i:s"));
			$data_records = $this->xpath->evaluate('data_record', $elm -> item(0));
			$data_records -> item(0) -> appendChild($dataNode);
		}
    }

    public function saveSignupSnapShot($lead_id = null, $target_id = null, $webform_id = null){

     	$doc_root = $this->doc->documentElement;
    	$doc_root ->setAttribute('retailer',self::$retailer);
    	$doc_root ->setAttribute('customer_type',self::$customer_type);
    	$doc_root ->setAttribute('package',self::$package);
    	$doc_root ->setAttribute('productName',self::$productName);

    	$this -> model_webforms = ClassRegistry::init('ps_Webforms');

		$this->model_webforms->save(array('ps_Webforms' => array(
			'leadid' => (int)$lead_id,
			'webform_xml' => $this -> doc -> saveXML($this->doc),
			'date_created' => date('Y-m-d H:i:s'),
			'target_id' => (int)$target_id,
			'user_id' => (int)$this->submissionConfig['userid'],
			'webform_template_id' => (int)$webform_id
		)));

		$outcome = $this->model_webforms->find('first', array(
			'conditions' => array('leadid = ' => (int)$lead_id, 'user_id' => (int)$this->submissionConfig['userid'], 'target_id' => (int)$target_id),
			'fields' => array('webform_id'),
			'order' => array('date_created' => 'desc')
		));
		return $outcome['ps_Webforms']['webform_id'];

    }

     public function updateUserInformation($userInfo = null){

    	$userTable = ClassRegistry::init('ps_Users');

		$user_row = $userTable ->find('first', array(
			'conditions' => array('user_id' => (int)$this->submissionConfig['userid'])
		));

		$userTable ->id = $user_row['ps_Users']['user_id'];


		$userTable ->set(array(
			'user_email' => $userInfo['user_email'],
			'user_phone' => $userInfo['user_mobile'],
			'user_username' => $userInfo['user_email'],
			'user_password' => $userInfo['user_mobile'],
			'user_class' => 3,
			'user_title' => $userInfo['user_title'],
			'user_firstname' => $userInfo['user_firstname'],
			'user_surname' => $userInfo['user_surname'],
			'date_updated' => date('Y-m-d H:i:s')

		));
		$userTable->save();

    }

    public function setConcessionSnapShot($submissionID = null, $dataImage = null, $mappingTemplate = null, $form_values = null){


    	$xmlDoc = $this -> getXMLDocument();
    	$xmlDoc -> loadXml($mappingTemplate['mappingTxt']);
    	$DocXpath = new DOMXPath($xmlDoc);
    	$ewNode = $xmlDoc  -> createElement("ew");
    	$ewNode -> setAttribute('type','concessions');
    	foreach($dataImage as $image){
    		$concession = $xmlDoc  -> createElement("concession");
    		$label = $xmlDoc  -> createElement("label", $image["label"]);
    		$data_records = $xmlDoc  -> createElement("data_records");

    		$dataValue = "";
    		$dataNode = $xmlDoc  -> createElement("data");
			foreach ($form_values as $key => $value){
				if ($key == $image["id"]){
					$dataValue = $value;
				}
			}

			$dataNode -> setAttribute('value',$dataValue);
			$dataNode -> setAttribute('user',$this->submissionConfig['userid']);
			$dataNode -> setAttribute('date',date("d-m-Y H:i:s"));

			$data_records -> appendChild($dataNode);
			$concession -> appendChild($label);
			$concession -> appendChild($data_records);
			$ewNode -> appendChild($concession);

    	}

    	$this -> model_ConcessionSnapShot = ClassRegistry::init('ps_SnapShot_Concessions');

		$this->model_ConcessionSnapShot->save(array('ps_SnapShot_Concessions' => array(
			'snapshot_xml' => $xmlDoc -> saveXML($ewNode),
			'date_created' => date('Y-m-d H:i:s'),
			'concession_template_id' => (int)$mappingTemplate['id'],
			'webforms_id' => (int)$submissionID
		)));

		return $this->model_ConcessionSnapShot -> id;

    }

    public function setTACSnapShot($submissionID = null, $dataImage = null, $mappingTemplate = null){


        $xmlDoc = $this -> getXMLDocument();
    	$xmlDoc -> loadXml($mappingTemplate['mappingTxt']);
    	$DocXpath = new DOMXPath($xmlDoc);
    	$dataRecord_elements = $DocXpath->evaluate("ew/element/data_records", $xmlDoc);

    	$dataNode = $xmlDoc  -> createElement("data");

		$dataNode -> setAttribute('user',$this->submissionConfig['userid']);
		$dataNode -> setAttribute('date',date("d-m-Y H:i:s"));
		$TACTxt = $xmlDoc -> createTextNode($dataImage['NodeString']);
		$dataNode -> appendChild($TACTxt);

		$dataRecord_elements -> item(0) -> appendChild($dataNode);

    	$this -> model_TACSnapShot = ClassRegistry::init('ps_SnapShot_TAC');
		$this->model_TACSnapShot->save(array('ps_SnapShot_TAC' => array(
			'snapshot_xml' => $xmlDoc -> saveXML($xmlDoc),
			'date_created' => date('Y-m-d H:i:s'),
			'webforms_id' => (int)$submissionID,
			'tac_template_id' => (int)$mappingTemplate['id']
		)));

		return   $this->model_TACSnapShot->id;

    }

    public function setPlanOptionsSnapShot($submissionID = null, $dataImage = null, $mappingTemplate = null, $form_values = null){

     	$xmlDoc = $this -> getXMLDocument();
    	$xmlDoc -> loadXml($mappingTemplate['mappingTxt']);
    	$DocXpath = new DOMXPath($xmlDoc);
    	$ewNode = $xmlDoc  -> createElement("ew");
    	foreach($dataImage as $image){
    		$element = $xmlDoc  -> createElement("element");
    		$element -> setAttribute('id', $image["id"]);
    		$label = $xmlDoc  -> createElement("label", $image["label"]);
    		$data_records = $xmlDoc  -> createElement("data_records");

    		$dataValue = "";
    		$dataNode = $xmlDoc  -> createElement("data");
			foreach ($form_values as $key => $value){
				if ($key == $image["id"]){
					$dataValue = $value;
				}
			}

			$dataNode -> setAttribute('value',$dataValue);
			$dataNode -> setAttribute('user',$this->submissionConfig['userid']);
			$dataNode -> setAttribute('date',date("d-m-Y H:i:s"));

			$data_records -> appendChild($dataNode);
			$element -> appendChild($label);
			$element -> appendChild($data_records);
			$ewNode -> appendChild($element);

    	}

    	$this -> model_planOptionSnapShot = ClassRegistry::init('ps_SnapShot_PlanOptions');

		$this->model_planOptionSnapShot->save(array('ps_SnapShot_PlanOptions' => array(
			'snapshot_xml' => $xmlDoc -> saveXML($ewNode),
			'date_created' => date('Y-m-d H:i:s'),
			'webforms_id' => (int)$submissionID,
			'planOptions_template_id' => (int)$mappingTemplate['id']
		)));

		/********
		** Okkey Sumiyoshi
		** 21/01/2016
		** Storing user submitted plan option information in the private property
		** which is used at compliling Velocify Request
		*/

		$reflectionClass = new ReflectionClass('SubmissionHandlerComponent');

		$reflectionClass->getProperty('planOptionImg')->setValue($xmlDoc -> saveXML($ewNode));





		return $this->model_planOptionSnapShot -> id;

    }




	private static function setAGLSaleType($_param){
	/*
	if current retailer is not AGL	if fuel is dual fuel	ADF	(acquisition dual fuel)
	if current retailer is not AGL	if fuel is elec	AEO	(acquisition electricity only)
	if current retailer is not AGL	if fuel is gas	AGO	(acquisition gas only)
	if current retailer is AGL	if fuel is dual fuel	RDF	(retention dual fuel)
	if current retailer is AGL	if fuel is elec	REO	(retention electricity only)
	if current retailer is AGL	if fuel is gas	RGO	(retention gas only)
	*/
		if (strtoupper(self::$retailer) == "AGL"){
			if (strtoupper(self::$package) == "ELEC"){
				if (strtoupper(self::$elec_current_supplier) == "AGL"){
					return "REO";
				} else {
					return "AEO";
				}
			}
			if (strtoupper(self::$package) == "GAS"){
				if (strtoupper(self::$gas_current_supplier) == "AGL"){
					return "RGO";
				} else {
					return "AGO";
				}
			}
			if (strtoupper(self::$package) == "DUAL"){
				if ((strtoupper(self::$gas_current_supplier) == "AGL") || (strtoupper(self::$elec_current_supplier) == "AGL")){
					return "RDF";
				} else {
					return "ADF";
				}

			}

		} else {
			return "";
		}
	}


	private static function setFuelType($_param){
		return self::$package;
	}

	private static function setYesNo($_param){
		if (empty($_param['data_value'])){
			return "";
		} else {
			if (strtoupper($_param['data_value']) == "YES"){
				return "Y";
			}
			if (strtoupper($_param['data_value']) == "NO"){
				return "N";
			}
		}

	}

	private static function setGreenEnergy($_param){

		//if AGL, then map default, otherwise, return empty string
		if ((strtoupper(self::$retailer) == "AGL")||
		(strtoupper(self::$retailer) == "ORIGIN ENERGY")||
		(strtoupper(self::$retailer) == "POWERDIRECT")){
			return $_param['default_val'];
		} else {
			return "";
		}

	}
	private static function setMSATSTariffCode($_param){

		//if AGL, then map default, otherwise, return empty string
		if ((strtoupper(self::$retailer) == "MOMENTUM")||
		(strtoupper(self::$retailer) == "LUMO ENERGY")||
		(strtoupper(self::$retailer) == "POWERDIRECT")){
			return $_param['default_val'];
		} else {
			return "";
		}

	}

	private static function setBillingType($_param){

		//if AGL, then map default, otherwise, return empty string
		if (strtoupper(self::$retailer) == "LUMO ENERGY"){
			return $_param['default_val'];
		} else {
			return "";
		}

	}

	private static function ElectricityOn($_param){
		//if AGL, then map default, otherwise, return empty string
		if (strtoupper(self::$retailer) == "POWERSHOP"){
			return $_param['default_val'];
		} else {
			return "";
		}
	}

	private static function setABN($_param){
		//if AGL, then map default, otherwise, return empty string
		if (strtoupper(self::$retailer) == "AGL"){
			return $_param['default_val'];
		} else {
			return "";
		}
	}

	private static function setTradingName($_param){
		//if AGL, then map default, otherwise, return empty string
		if (strtoupper(self::$retailer) == "AGL"){
			return $_param['default_val'];
		} else {
			return "";
		}
	}

	private static function setLegalName($_param){
		//if AGL, then map default, otherwise, return empty string
		if ((strtoupper(self::$retailer) == "AGL")||
		(strtoupper(self::$retailer) == "LUMO ENERGY")||
		(strtoupper(self::$retailer) == "MOMENTUM")||
		(strtoupper(self::$retailer) == "POWERDIRECT")||
		(strtoupper(self::$retailer) == "POWERSHOP")){
			return $_param['default_val'];
		} else {
			return "";
		}

	}

	private static function default_alt($_param){
		//a method that first looks for data value in the UI Post request. if the data value exists, then map to the velocify field.  Otherwise return default  value
		if (empty($_param['data_value'])){
			return $_param['default_val'];
		} else {
			return $_param['data_value'];
		}
	}

	private static function transformDate($_param){
		$value_data = str_replace('/', '-', $_param['data_value']);
		return date('m/d/Y', strtotime($value_data));
	}

	private static function date_alt($_param){
		if (empty($_param['data_value'])){
			return $_param['default_val'];
		} else {
			$value_data = str_replace('/', '-', $_param['data_value']);
			return date('m/d/Y', strtotime($value_data));
		}
	}

	private static function currentDate($_param){
		return date('m/d/Y');
	}

	private static function setElectricityProduct($_param){

		if (strtoupper(self::$package) == "GAS"){
			return $_param['default_val'];
		} else {
			return self::$elec_Product_Field_Value;
		}

	}

	private static function setGasProduct($_param){

		if (strtoupper(self::$package) == "ELEC"){
			return $_param['default_val'];
		} else {
			return self::$gas_Product_Field_Value;
		}
	}


	private static function setCurrentRetailerElec($_param){
		if (strtoupper(self::$package) == "GAS"){
			return $_param['default_val'];
		} else {
			if (self::$elec_current_supplier == ""){
				return $_param['default_val'];
			} else {
				return self::$elec_current_supplier;
			}
		}
	}

	private static function setCurrentRetailerGas($_param){

		if (strtoupper(self::$package) == "ELEC"){
			return $_param['default_val'];
		} else {
			if (self::$gas_current_supplier == ""){
				return $_param['default_val'];
			} else {
				return self::$gas_current_supplier;
			}
		}
	}

	private static function setNMIAcqRet($_param){
		/*
		if current retailer (Elec) is not new electricity retailer - Acquisition
		if current retailer (Elec) is new electricity retailer - 		Retention
		or "NA" as default value
		*/
		if (strtoupper(self::$package) == "GAS"){
			return $_param['default_val'];
		} else {
			if (strtoupper(self::$elec_current_supplier) == strtoupper(self::$retailer)){
				return "Retention";
			} else {
				return "Acquisition";
			}
		}
	}


	private static function setMIRNAcqRet($_param){
		/*
		if current retailer (Gas) is not new electricity retailer - Acquisition
		if current retailer (Gas) is new electricity retailer - Retention
		or "NA" as default value
		*/

		if (strtoupper(self::$package) == "ELEC"){
			return $_param['default_val'];
		} else {
			if (strtoupper(self::$gas_current_supplier) == strtoupper(self::$retailer)){
				return "Retention";
			} else {
				return "Acquisition";
			}
		}
	}

	private static function setNewElectricityRetailer($_param){

		if (strtoupper(self::$package) == "GAS"){
			return $_param['default_val'];
		} else {
			return self::$retailer;
		}
	}
	private static function setNewGasRetailer($_param){

		if (strtoupper(self::$package) == "ELEC"){
			return $_param['default_val'];
		} else {
			return self::$retailer;
		}
	}

	private static function setcontractlength($_param){
		if (strtoupper(self::$retailer) == "MOMENTUM"){
			return $_param['default_val'];
		} else {
			return "";
		}
	}

	private static function setLumoPackage($_param){
		if (strtoupper(self::$retailer) == "LUMO ENERGY"){
			return $_param['default_val'];
		} else {
			return "";
		}
	}


	private static function setFlyBuysPoints($_param){
		if (strtoupper(self::$retailer) == "AGL"){
			return $_param['default_val'];
		} else {
			return "";
		}
	}

	private static function setFlyBuysNumber($_param){
		if (strtoupper(self::$retailer) == "AGL"){
			$response = "";
			$xmlDoc = new DOMDocument();
			$xmlDoc -> formatOutput = true;
			$xmlDoc -> loadXml(self::$planOptionImg);
			$DocXpath = new DOMXPath($xmlDoc);

			$elms = $DocXpath->evaluate("ew/element[@id='".$_param['default_val']."']/data_records/data", $xmlDoc);
			foreach($elms as $elm){
				$fieldValue = strtoupper($elm -> getAttribute("value"));
			}
			if (strtoupper($fieldValue) == ""){
				return "0";
			} else {
			 	return $fieldValue;
			}
		} else {
			return "";
		}
	}

	private static function setAEORegistrationConsent($_param){
		if (strtoupper(self::$retailer) == "AGL"){
			return $_param['default_val'];
		} else {
			return "";
		}
	}


	private static function setPowershopToken($_param){
		if (strtoupper(self::$retailer) == "POWERSHOP"){
			return $_param['default_val'];
		} else {
			return "";
		}
	}

	private static function setCompanyPosition($_param){
		if (strtoupper(self::$retailer) == "MOMENTUM"){
			return $_param['default_val'];
		} else {
			return "";
		}
	}

	private static function setElectricityUsage($_param){
		if (strtoupper(self::$retailer) == "MOMENTUM"){
			return $_param['default_val'];
		} else {
			return "";
		}
	}

	private static function setMomentumFile($_param){
		if (strtoupper(self::$retailer) == "MOMENTUM"){
			return $_param['default_val'];
		} else {
			return "";
		}
	}

	private static function setDocumentCountryofIssue($_param){
		if (strtoupper(self::$retailer) == "LUMO ENERGY"){
			return $_param['data_value'];
		} else {
			return "";
		}
	}

	private static function setElectricityOn($_param){
		if (strtoupper(self::$retailer) == "POWERSHOP"){
			return $_param['default_val'];
		} else {
			return "";
		}
	}


	private static function setEBilling($_param){


		if ((strtoupper(self::$retailer) == "POWERSHOP")||(strtoupper(self::$retailer) == "SUMO POWER")){
			return "Y";
		}
		if (strtoupper(self::$retailer) == "POWERDIRECT"){
			return "N";
		} else {
			$response = "";
			$xmlDoc = new DOMDocument();
			$xmlDoc -> formatOutput = true;
			$xmlDoc -> loadXml(self::$planOptionImg);
			$DocXpath = new DOMXPath($xmlDoc);


			$fieldValue = 'NO';
			$elms = $DocXpath->evaluate("ew/element[@id='".$_param['default_val']."']/data_records/data", $xmlDoc);
			foreach($elms as $elm){
				$fieldValue = strtoupper($elm -> getAttribute("value"));
			}
			if (strtoupper($fieldValue) == 'NO'){
				return 'N';
			} else {
			 	return 'Y';
			}

		}
	}



	/********
	This is a reserved method intended to cater plan options.
	*****/
	private static function setPlanOptionsByYN($_param){
		$response = "";
   		$xmlDoc = new DOMDocument();
   		$xmlDoc -> formatOutput = true;
    	$xmlDoc -> loadXml(self::$planOptionImg);
    	$DocXpath = new DOMXPath($xmlDoc);

		$elms = $DocXpath->evaluate("ew/element[@id='".$_param['default_val']."']/data_records/data", $xmlDoc);
		foreach($elms as $elm){
			if (strtoupper($elm -> getAttribute("value")) == "YES"){
				$response = "Y";
			} else {
				$response = "N";
			}

		}
		return $response;
	}

	private static function setPlanOptions($_param){
		$response = "";
   		$xmlDoc = new DOMDocument();
   		$xmlDoc -> formatOutput = true;
    	$xmlDoc -> loadXml(self::$planOptionImg);
    	$DocXpath = new DOMXPath($xmlDoc);

		$elms = $DocXpath->evaluate("ew/element[@id='".$_param['default_val']."']/data_records/data", $xmlDoc);
		foreach($elms as $elm){
			$response = strtoupper($elm -> getAttribute("value"));

		}
		return $response;
	}

	private static function setPlanOptionsNoCharacterConversion($_param){
		$response = "";
   		$xmlDoc = new DOMDocument();
   		$xmlDoc -> formatOutput = true;
    	$xmlDoc -> loadXml(self::$planOptionImg);
    	$DocXpath = new DOMXPath($xmlDoc);

		$elms = $DocXpath->evaluate("ew/element[@id='".$_param['default_val']."']/data_records/data", $xmlDoc);
		foreach($elms as $elm){
			$response = $elm -> getAttribute("value");

		}
		return $response;
	}


	private static function ui_data($_param){
		/*******
		A method that retrieves data value from the UI post, and simply map it back
		**/
		return $_param['data_value'];
	}




	public function mapVelocify(){


		$output = array();

		//shortlist elements only signup attribute is set to true
		$elms = $this->xpathVelocify->evaluate("ew/field[@signup='true']", $this->docVelocify);


		foreach($elms as $elm){
			$ui_value = "";
			$datanodes = $this->xpathVelocify->evaluate("maping_rules/data_source/source", $elm);
			$this -> log('datanode::::'.$datanodes -> item(0) -> getAttribute("ref"),'debug');
			if (($datanodes -> item(0) -> getAttribute("ref") != '')){
				//retrieving data from what's been mapped to the signup snapshot image
				//should no reference to the UI post value, then pass empty string

				$HTMLvalues = $this->xpath->evaluate("publicSignUp/element[@id='" . $datanodes -> item(0) -> getAttribute("ref") . "']/data_record/data", $this -> doc);
				if ($HTMLvalues -> length != 0){
					$ui_value = $HTMLvalues -> item(0) -> getAttribute("value");
				}
			}

			$maping_rules = $this->xpathVelocify->evaluate('maping_rules', $elm);

			$velocifyFieldName = $maping_rules -> item(0) -> getAttribute("mapping_target");

			$mapMethod = $maping_rules -> item(0) -> getAttribute("methods");

			$param = array(
				'data_value' => $ui_value,
				'default_val' => $maping_rules -> item(0) -> getAttribute("default_value")
			);

			if (($mapMethod == 'default') || (empty($mapMethod))){
				$output[$velocifyFieldName] = $param['default_val'];
			} else {
				$output[$velocifyFieldName] = $this -> $mapMethod($param);
			}



		}


		return $output;
	}

	public function parseDocument(){
		return $this -> docVelocify -> saveXML($this -> docVelocify);
	}
	public function getSnapShotXML(){
		return $this -> doc -> saveXML($this -> doc);
	}

}
