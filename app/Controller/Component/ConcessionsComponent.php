<?php

App::uses('Component', 'Controller');

class ConcessionsComponent extends Component{

	public $doc;
	protected $xpath;
	private $statements = array();
	private $element_Collection = array();

	private $state = "";
	private $retailer = "";
	private $query = "";
	protected $elements;

	private $concesInputNames = array();
	private $radioElements = array();

	private $model_concessions;

	private $HtmlNodeFactory;

	private $transitionalQuestionElements = array();
	private $transitionalQuestionDisplayOrder = array();

	private $hide_elements_by_default = array();

	public $components = array('HtmlNodeFactory');


	private $concessionConfig = array();

	protected $_defaults = array(
		'_state' => null,
		'_retailer' => null,
		'_factory' => null
	);

	public function __construct(ComponentCollection $collection, $settings = array()) {
		$settings = array_merge($this->_defaults, $settings);
		$this->concessionConfig = $settings;
	}



	public function setComponent(){

		/*
        foreach ($this->components as $component_name)
        {
            App::import('Component', $component_name);
            $component_class = "{$component_name}Component";
            $this->$component_name = new $component_class();

        }
        */


		$this -> model_concessions = ClassRegistry::init('ps_Concessions');
		$outcome = $this->model_concessions->find('first', array(
			'fields' => array('concession_id','concession_rule_xml'),
			'order' => array('date_created' => 'desc')
		));


		$xmltxt = $outcome['ps_Concessions']['concession_rule_xml'];
		$this -> concession_temp_id = $outcome['ps_Concessions']['concession_id'];
		$this -> state = $this->concessionConfig['_state'];
		$this -> retailer = str_replace(" ", "_", $this->concessionConfig['_retailer']);
		$this -> HtmlNodeFactory = $this->concessionConfig['_factory'];
		$this -> doc = new DOMDocument();
		$this -> concesSnapShotImage = new DOMDocument();
		$this->doc->loadXml($xmltxt);
		$this->xpath = new DOMXPath($this->doc);
		$this -> xpath->registerNamespace('xhtml', "http://www.w3.org/1999/xhtml");

		$flg = 0;

		if ($this -> state == "SA"){
			$this -> query = '//concession[@state = "' . $this -> state . '"]/questions/question';
			$flg++;
		}
		if ($this -> retailer == "Sumo_Power"){
			$this -> query = '//concession[@retailer = "' . $this -> retailer . '"]/questions/question';
			$flg++;
		}
		if ($flg == 0){
			$this -> query = '//concession[@state = "all"]/questions/question';
		}

		$this -> elements = $this->xpath->evaluate($this -> query, $this -> doc);
		$this -> outputFactory();

	}

	public function getSnapShotSource(){

		$results = array();
		$this -> model_concessions = ClassRegistry::init('ps_Concessions');
		$outcome = $this->model_concessions->find('first', array(
			'fields' => array('concession_id','concession_rule_xml'),
			'order' => array('date_created' => 'desc')
		));


		$xmltxt = $outcome['ps_Concessions']['concession_rule_xml'];
		$this -> state = $this->concessionConfig['_state'];
		$this -> retailer = str_replace(" ", "_", $this->concessionConfig['_retailer']);

		$this -> doc = new DOMDocument();
		$this->doc->loadXml($xmltxt);
		$this->xpath = new DOMXPath($this->doc);



		$flg = 0;

		if ($this -> state == "SA"){
			$this -> query = '//concession[@state = "' . $this -> state . '"]/questions/question';
			$flg++;
		}
		if ($this -> retailer == "Sumo_Power"){
			$this -> query = '//concession[@retailer = "' . $this -> retailer . '"]/questions/question';
			$flg++;
		}
		if ($flg == 0){
			$this -> query = '//concession[@state = "all"]/questions/question';
		}

		$this -> elements = $this->xpath->evaluate($this -> query, $this -> doc);

		if (!is_null($this -> elements)) {

			foreach ($this -> elements as $element) {
				$statements = $this->xpath->evaluate('concessions/statements/statement[@id = "' . $element -> getAttribute("id") . '"]', $this -> doc);
				$scriptElement = $this->xpath->evaluate('element', $statements -> item(0));
				$results[] = array(
					"id" => $scriptElement  -> item(0) -> getAttribute("id"),
					"label" => str_replace("[retailer] ", $this->concessionConfig['_retailer'] . " ", $scriptElement  -> item(0) -> getAttribute("label"))
				);
			}
		}
		$preConcessionElements = $this->xpath->evaluate('concessions/pre_concessions/retailer[@name="'.strtoupper($this -> retailer) . '"]/questions/question', $this -> doc);
		if (!is_null($preConcessionElements)) {

			foreach ($preConcessionElements as $question) {
				$questionElements = $this->xpath->evaluate('concessions/transitionalQuestions/question[@name="'.$question -> getAttribute('id') . '"]/element', $this -> doc);
				if (!is_null($questionElements)) {

					$results[] = array(
						"id" => $questionElements  -> item(0) -> getAttribute("id"),
						"label" => str_replace("[retailer] ", $this->concessionConfig['_retailer'] . " ", $questionElements  -> item(0) -> getAttribute("label"))
					);

				}
			}

		}
		return $results;

	}

    private function outputFactory(){
		/*
		<element class="PersonalDetails" id="statement_x" name="PersonalDetails_concessionResponse">
			<label>text descriptions</label>
			<input type="text" name="statement_x"/>
		</element>
		*/
		if (!is_null($this -> elements)) {



			foreach ($this -> elements as $element) {


				$statements = $this->xpath->evaluate('concessions/statements/statement[@id = "' . $element -> getAttribute("id") . '"]', $this -> doc);
				$scriptElement = $this->xpath->evaluate('element', $statements -> item(0));
				$inputs = $this->xpath->evaluate('input', $scriptElement -> item(0));

				$elementNode = $this -> HtmlNodeFactory -> generateNodeString(array(
                	"element_class" => $scriptElement  -> item(0) -> getAttribute("class"),
                	"element_id_prefix" => '',
                	"element_id" => $scriptElement  -> item(0) -> getAttribute("id"),
                	"element_name" => $scriptElement  -> item(0) -> getAttribute("name"),
                	"element_label_value" => str_replace("[retailer] ", $this->concessionConfig['_retailer'] . " ", $scriptElement  -> item(0) -> getAttribute("label")),
                	"element_placeholder" => $scriptElement  -> item(0) -> getAttribute("placeholder"),
                	"node_element" => $this->doc->saveXML($inputs->item(0))));

				if ((strtoupper($inputs->item(0)->getAttribute('type')) == "RADIO")|| (strtoupper($inputs->item(0)->getAttribute('type')) == "RADIO_PLUS")){
					$this -> radioElements[$statements  -> item(0) -> getAttribute("name")] = $scriptElement  -> item(0) -> getAttribute("id");
				}

				$this -> element_Collection[] = $elementNode;
				$this -> concesInputNames[$statements  -> item(0) -> getAttribute("name")] = $scriptElement  -> item(0) -> getAttribute("id");

			}

		} else {
			print_r("Null");
		}
    }

    private function getTransitionalQuestionElements(){
		/***
		** 	25/02/2016
		**	Okkey Sumiyoshi
		**	Catering the requirements from Origin to have transitional questinos
		**
		**
		*************/
		$this -> log("retailer:::::".$this->concessionConfig['_retailer'], 'debug');

		$transitionalQuestions = $this->xpath->evaluate('concessions/pre_concessions/retailer[@name="'.strtoupper($this->concessionConfig['_retailer']) . '"]/questions/question', $this -> doc);

		if (!is_null($transitionalQuestions)) {

			foreach ($transitionalQuestions as $question) {

				//must have
				$display_class = "";
				$default_display = $question -> getAttribute("default");
				$applicable_state = (int)$question -> getAttribute($this->concessionConfig['_state']);
				$invocation_collection = array();

				if ($default_display == 'false'){
					$display_class = "display_hide";
					$this -> hide_elements_by_default[$question -> getAttribute("id")] = "hide";
				}


				$question_responses = $this->xpath->evaluate('response', $question);

				if (!is_null($question_responses)){
					foreach ($question_responses as $response) {
						if (strtoupper($response -> getAttribute('required')) == "TRUE"){
							$invocation = $this->xpath->evaluate('invocation', $response);

							$target = $this->xpath->evaluate('target', $response);
							$event = $this->xpath->evaluate('event', $response);

							$pieces = explode(" ", $target -> item(0) -> nodeValue);
							$invocation_collection[] = array(
								"invocation" => $invocation -> item(0) -> nodeValue,
								"target" => $target -> item(0) -> nodeValue,
								"event" => $event -> item(0) -> nodeValue
							);
						}
					}
				}
				$trigger = array();
				$trigger['required'] = true;
				$trigger['methodName'] = "preConcessionChainReactor";
				$trigger['invocation'] = json_encode($invocation_collection);
				$trigger['action'] = "";

				if ((int)$applicable_state){
					$questionElements = $this->xpath->evaluate('concessions/transitionalQuestions/question[@name="'.$question -> getAttribute('id') . '"]/element', $this -> doc);
					if (!is_null($questionElements)) {
						foreach ($questionElements as $element) {

							$inputs = $this->xpath->evaluate('input', $element);

							$label_value = str_replace("[retailer] ", $this->concessionConfig['_retailer'] . " ", $element  -> getAttribute("label"));
							$label_value = str_replace("[retailer]", $this->concessionConfig['_retailer'] . " ", $label_value);
							$label_value = str_replace("[br]", "<br/>", $label_value);

							$elementNode = $this -> HtmlNodeFactory -> generateNodeString(array(
								"element_class" => $element -> getAttribute("class"),
								"element_id_prefix" => 'concession_lbl_',
								"element_id" => $element  -> getAttribute("id"),
								"element_name" => $question  -> getAttribute("name"),
								"element_label_value" => $label_value,
								"element_placeholder" => $element  -> getAttribute("placeholder"),
								"element_trigger" => $trigger,
								"node_element" => $this->doc->saveXML($inputs->item(0))));
							$this -> transitionalQuestionElements[] = $elementNode;
							$this -> transitionalQuestionDisplayOrder[] = array('name' => $question  -> getAttribute("name"), 'id' => $question  -> getAttribute("id"));
						}

					}
				}
			}
		}
    }


 	public function getRadioElementList(){
		return $this -> radioElements;
	}

    public function getConcessionHTMLString(){
    	return $this -> element_Collection;
    }

    public function getConcessionElementList(){
    	return $this -> concesInputNames;
    }
    public function getPreConcessionQuestionList(){
    	$this -> getTransitionalQuestionElements();
		return $this -> transitionalQuestionElements;
    }

    public function getConcessionHideElementList(){
    	return $this -> hide_elements_by_default;
    }

    public function getConcessionCardTypeList(){
    	$card_to_remove = array();
		$this -> model_concessions = ClassRegistry::init('ps_SiteConfiguration');
		$outcome = $this->model_concessions->find('first', array(
			'conditions' => array('configuration_type = ' => 'UI_DISPLAY'),
			'fields' => array('configuration_xml'),
			'order' => array('date_created' => 'desc')
		));
		$xmltxt = $outcome['ps_SiteConfiguration']['configuration_xml'];
		$configurations = new DOMDocument();
		$configurations->loadXml($xmltxt);
		$configurationsXpath = new DOMXPath($configurations);
		$concession_cards = $configurationsXpath->evaluate('ew/display_rules/concession_card/card', $configurations);

		if (!is_null($concession_cards)) {
			foreach ($concession_cards as $element) {
				if ((int)$element -> getAttribute(strtoupper($this->concessionConfig['_state'])) == 0){
					$card_to_remove[$element -> getAttribute('name')] = $element -> getAttribute('displayValue');
				}
			}

		} else {
			print_r("Null");
		}

		return json_encode($card_to_remove);


    }
	public function getPreConcessionQuestionOrder(){
		return json_encode($this -> transitionalQuestionDisplayOrder);
	}

}
