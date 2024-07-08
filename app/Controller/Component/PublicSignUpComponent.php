<?php

App::uses('Component', 'Controller');

class PublicSignUpComponent extends Component{

	public $doc;

	protected $xpath;

	private $publicSignUps;
	private $query = 'element';
	private $signupConfig = array();
	private $selectElements = array();
	private $radioElements = array();
	private $planOptionElements;
	private $count_x = 0;

	protected $query_Label = 'label';
	protected $query_Input = 'input';
    protected $query_Select = 'select';
	protected $elements;

	public $concessions;
	public $tac;
	public $planOptions;
	public $FormSnapShot;
	public $components = array('HtmlNodeFactory');
	public $UIElements = array();

	public $concessionsSnapShotImg = array();
	public $planOptionSnapShotImg = array();
	public $snapshot_user_id = 0;
	public $snapshot_timestamp = "";

	protected $_defaults = array(
		'_retailer' => null,
		'_state' => null,
		'_fuel' => null,
		'_entityClass' => null,
		'_plan' => null
	);


	function initialize(Controller $controller) {
	}

	//A method that dynamically creates a set of properties that provide HTML strings representing HTML input elements.
	//This method employs a reference to XML stored in a database table webform_templates with a reference to publicSignUp
    public function createProperty($_concessions = null, $_tac = null, $_planOptions = null){


    	$this -> concessions = $_concessions;
    	$this -> concessions -> setComponent();

    	$this -> tac = $_tac;
		$this -> tac -> setComponent();

		$this -> planOptions = $_planOptions;
		$this -> planOptions -> setComponent();

		$this->doc = new DOMDocument();
		$this->doc->loadXML($this -> FormSnapShot);
		$this->xpath = new DOMXPath($this->doc);
		$this -> publicSignUps = $this->doc->getElementsByTagName('publicSignUp')->item(0);
		$this -> elements = $this->xpath->evaluate($this -> query, $this -> publicSignUps);

		if (!is_null($this -> elements)) {
			foreach ($this -> elements as $element) {

				$inputs = $this->xpath->evaluate($this -> query_Input, $element);

                if ($inputs -> length > 0){
                	$elementNode = $this -> HtmlNodeFactory -> generateNodeString(array(
                	"element_class" => $element-> getAttribute("class"),
                	"element_id" => $element-> getAttribute("id"),
                	"element_name" => $element-> getAttribute("name"),
                	"element_label_value" => $element-> getAttribute("label"),
                	"element_placeholder" => $element-> getAttribute("placeholder"),
                	"node_element" => $this->doc->saveXML($inputs->item(0))));

                	if (strtoupper($inputs->item(0)->getAttribute('type')) == "SELECT"){
                		$this -> selectElements[$element-> getAttribute("name")] = $element-> getAttribute("id");
                	}
                 	if ((strtoupper($inputs->item(0)->getAttribute('type')) == "RADIO")|| (strtoupper($inputs->item(0)->getAttribute('type')) == "RADIO_PLUS")){
                		$this -> radioElements[$element-> getAttribute("name")] = $element-> getAttribute("id");
                	}
                	$this->setProperty($element -> getAttribute("name"),$elementNode);

                }

			}
			//add concessions to the property list
			$this-> setProperty("concessionNodes", $this -> concessions -> getConcessionHTMLString());


			//add terms and conditions to the property list
			$this-> setProperty("tac_txt", $this -> tac -> getOutputHTMLString());

			//add plan options to the property list
			$this-> setProperty("plan_options", $this -> planOptions -> getPlanOptionsHTMLCollection());

			//add concession suppliments
			$this-> setProperty("preConcessionQuestionNodes", $this -> concessions -> getPreConcessionQuestionList());

			//add concession types that are removed
			$this-> setProperty("ConcessionCardTypesToBeRemoved", $this -> concessions -> getConcessionCardTypeList());

			$this-> setProperty("preConcessionQuestionOrder", $this -> concessions -> getPreConcessionQuestionOrder());


		}

    }

	public function createSnapShotProperties($_signup = null, $_concessions = null, $_tac = null, $_planOptions = null){
		$form_doc = new DOMDocument();
		$concession_doc = new DOMDocument();
		$plan_option_doc = new DOMDocument();
		$tac_doc = new DOMDocument();

		$form_doc->loadXML($_signup);
		$concession_doc->loadXML($_concessions);
		$plan_option_doc->loadXML($_planOptions);
		$tac_doc->loadXML($_tac);

		$form_xpath = new DOMXPath($form_doc);
		$concession_xpath = new DOMXPath($concession_doc);
		$plan_option_xpath = new DOMXPath($plan_option_doc);
		$tacxpath = new DOMXPath($tac_doc);

		$form_elements = $form_xpath->evaluate('publicSignUp/element', $form_doc);
		$concession_elements = $concession_xpath->evaluate('ew/concession', $concession_doc);
		$planoption_elements = $plan_option_xpath->evaluate('ew/element', $plan_option_doc);
		$tac_elements = $tacxpath->evaluate('ew/element', $tac_doc);


		//Obtain User id
		if ($form_elements -> length >0){
			$user_data = $form_xpath->evaluate('data_record/data', $form_elements -> item(0));
			if ($user_data -> length > 0) {
				$this -> snapshot_user_id = (int)$user_data-> item(0)->getAttribute("user");
				$this -> snapshot_timestamp = $user_data-> item(0)->getAttribute("date");
			}
		}

		if (!is_null($form_elements)) {
			foreach ($form_elements as $element) {
				$data = $form_xpath->evaluate('data_record/data', $element);
				$output = array();

				if ($data -> length > 0) {
					$output["label_string"] = "<label class='snapshot-label'>" . $element-> getAttribute("label") . "</label>";
					$output["node_string"] = ($data-> item(0)->getAttribute("value"))? "<span class='snapshot-span'>".$data-> item(0)->getAttribute("value")."</span>":"<span class='snapshot-span'>N/A</span>";
				} else {
					$output["label_string"] = "";
					$output["node_string"] = "";
				}
				$this->setProperty($element -> getAttribute("name"),$output);

			}
		}

		if (!is_null($concession_elements)) {
			foreach ($concession_elements as $element) {
				$data = $concession_xpath->evaluate('data_records/data', $element);
				$label = $concession_xpath->evaluate('label', $element);
				$output = array();

				$output["label_string"] = "<label class='snapshot-label'>" . $label-> item(0) -> nodeValue . "</label>";
				$output["node_string"] = ($data-> item(0)->getAttribute("value"))? "<span>".$data-> item(0) -> getAttribute("value")."</span>":"<span class='snapshot-span'>N/A</span>";
				$this -> concessionsSnapShotImg[] = $output;

			}
		}

		if (!is_null($planoption_elements)) {
			foreach ($planoption_elements as $element) {
				$data = $plan_option_xpath->evaluate('data_records/data', $element);
				$label = $plan_option_xpath->evaluate('label', $element);
				$output = array();

				$output["label_string"] = "<label class='snapshot-label'>" . $label-> item(0) -> nodeValue . "</label>";
				$output["node_string"] = ($data-> item(0)->getAttribute("value"))? "<span>".$data-> item(0) -> getAttribute("value")."</span>":"<span class='snapshot-span'>N/A</span>";
				$this -> planOptionSnapShotImg[] = $output;

			}
		}

		if (!is_null($tac_elements)) {
			foreach ($tac_elements as $element) {
				$data = $tacxpath->evaluate('data_records/data', $element);
				$output = array();
				if (!is_null($data)) {
					$output["label_string"] = "";
					$output["node_string"] = $data-> item(0) -> nodeValue;
				} else {
					$output["label_string"] = "";
					$output["node_string"] = "";
				}
				$this->setProperty($element -> getAttribute("type"),$output);
			}
		}
	}

    public function setProperty($name, $value){
            $this->{$name} = $value;
    }

	public function getSelectElementList(){
		return json_encode($this -> selectElements);
	}
	public function getRadioElementList(){
		$radios = array();
		$radios['concessions'] = array();
		$radios['planOptions'] = array();
		$radios['genericUI'] = array();

		$concessionRadios = $this -> concessions -> getRadioElementList();
		$planOptionsRadios = $this -> planOptions -> getRadioElementList();

		foreach($concessionRadios as $key => $value){
			$radios['concessions'][$key] = $value;
		}
		foreach($planOptionsRadios as $key => $value){
			$radios['planOptions'][$key] = $value;
		}
		foreach($this -> radioElements as $key => $value){
			$radios['genericUI'][$key] = $value;
		}
		return json_encode($radios);
	}

	public function getConcessionElementList(){
    	$concessionItems = $this -> concessions -> getConcessionElementList();
    	return json_encode($concessionItems);
    }
	public function getPreConcessionQuestionList(){
    	$concessionItems = $this -> concessions -> getConcessionHideElementList();
    	$return_value;
    	if (sizeof($concessionItems) < 1){
    		$return_value = "";
    	} else {
    		$return_value = $concessionItems;
    	}
    	return $return_value;
    }

 	public function getConcessionCardTypeList(){
    	$concessionItems = $this -> concessions -> getConcessionCardTypeList();
    	//return json_encode($concessionItems);
    }

    public function getPlanOptionsElementLilst(){
    	$cplanOptionsItems = $this -> planOptions -> getPlanOptionsElementList();
    	return json_encode($cplanOptionsItems);
    }

    public function getPlanOptionSelectElementList(){
    	$planoOptionSelects = $this -> planOptions -> getPlanOptionSelectElementList();
    	return json_encode($planoOptionSelects);
    }

 	public function getElementsList(){
 	    /******
		*  A method that returns a list of HTML input names in a JSON string format in which the client JavaScript
		*  to use as a reference to collect input values from the HTML form and to comiple post request strings
		*****/

		$myArray = array();

		if (!is_null($this -> elements)) {
			foreach ($this -> elements as $element) {
				$myArray[$element -> getAttribute("name")] = $element -> getAttribute("id");
			}
		}

		//add concessions to the element list
		/*
		$concessionItems = $this -> concessions -> getConcessionElementList();

		foreach($concessionItems as $item){
			$myArray[$item] = $item;
		}
		*/
		return json_encode($myArray);


	}

	public function getSignUpHTML(){
		return $this -> doc -> saveXML($this -> publicSignUps);
	}

}
