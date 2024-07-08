<?php

App::uses('Component', 'Controller');

class PlanOptionsComponent extends Component{

	public $doc;
	protected $xpath;
	private $element_Collection = array();

	private $retailer = "";
	private $query = "";
	protected $questions;

	private $planOptionsInputNames = array();
	private $radioElements = array();
	private $selectElements = array();
	private $contextDependentOptions = array();

	private $model_planOptions;

	private $HtmlNodeFactory;

	public $components = array('HtmlNodeFactory');

	private $planOptionsConfig = array();

	protected $_defaults = array(
		'_retailer' => null,
		'_state' => null,
		'_plan' => null,
		'_factory' => null
	);

	public function __construct(ComponentCollection $collection, $settings = array()) {

		$this->planOptionsConfig = array_merge($this->_defaults, $settings);
	}

	public function setComponent(){


		$this -> model_planoptions = ClassRegistry::init('ps_PlanOptions');
		$outcome = $this->model_planoptions->find('first', array(
			'fields' => array('plan_options_xml'),
			'order' => array('date_created' => 'desc')
		));

		$xmltxt = $outcome['ps_PlanOptions']['plan_options_xml'];

		$this -> retailer = strtolower($this->planOptionsConfig['_retailer']) ;
		$this -> retailer = str_replace(" ", "_", $this->retailer);

		$this -> HtmlNodeFactory = $this->planOptionsConfig['_factory'];

		$this -> doc = new DOMDocument();

		$this->doc->loadXml($xmltxt);
		$this->xpath = new DOMXPath($this->doc);
		$this -> xpath->registerNamespace('xhtml', "http://www.w3.org/1999/xhtml");
		$this -> query = 'plan_options/retailers/retailer[@name = "' . $this -> retailer . '"]/questions/question';

		$this -> questions = $this->xpath->evaluate($this -> query, $this -> doc);
		$this -> outputFactory();

	}

	public function getSnapShotSource(){

		$results = array();
		$this -> model_planoptions = ClassRegistry::init('ps_PlanOptions');
		$outcome = $this->model_planoptions->find('first', array(
			'fields' => array('plan_options_xml'),
			'order' => array('date_created' => 'desc')
		));

		$xmltxt = $outcome['ps_PlanOptions']['plan_options_xml'];

		$this -> retailer = strtolower($this->planOptionsConfig['_retailer']) ;
		$this -> retailer = str_replace(" ", "_", $this->retailer);



		$this -> doc = new DOMDocument();

		$this->doc->loadXml($xmltxt);
		$this->xpath = new DOMXPath($this->doc);
		$this -> xpath->registerNamespace('xhtml', "http://www.w3.org/1999/xhtml");
		$this -> query = 'plan_options/retailers/retailer[@name = "' . $this -> retailer . '"]/questions/question';

		$this -> questions = $this->xpath->evaluate($this -> query, $this -> doc);

		if (!is_null($this -> questions)) {

			foreach ($this -> questions as $question) {

				$applicable_state = (int)$question -> getAttribute($this->planOptionsConfig['_state']);

				if ($applicable_state){
					$elements = $this->xpath->evaluate('plan_options/statements/element[@id = "' . $question -> getAttribute("id") . '"]', $this -> doc);

					$inputs = $this->xpath->evaluate('input', $elements -> item(0));
					$lbl_text = str_replace("[retailer]", $this->planOptionsConfig['_retailer'] . " ", $elements  -> item(0) -> getAttribute("label"));
					$lbl_text = str_replace("[plan name]", $this->planOptionsConfig['_plan'] . " ", $lbl_text);

					$results[] = array(
						"id" => $elements  -> item(0) -> getAttribute("id"),
						"label" => $lbl_text
					);

				}



			}
		} else {
			print_r("Null");
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
		if (!is_null($this -> questions)) {

			foreach ($this -> questions as $question) {

				$elements = $this->xpath->evaluate('plan_options/statements/element[@id = "' . $question -> getAttribute("id") . '"]', $this -> doc);

				$inputs = $this->xpath->evaluate('input', $elements -> item(0));

				//must have
				$display_class = "";
				$default_display = $question -> getAttribute("default");
				$applicable_state = (int)$question -> getAttribute($this->planOptionsConfig['_state']);

				if ($default_display == 'false'){
					$display_class = "display_hide";
					$this -> contextDependentOptions[$question -> getAttribute("id")] = "hide";
				}


				$trigger = array();
				$trigger['required'] = false;
				$trigger['methodName'] = "PlanOptionsChainReactor";
				$trigger['invocation'] = "";
				$trigger['action'] = "";



				//could have
				$response_actions = $this->xpath->evaluate('response', $question);
				$required = $response_actions->item(0)-> getAttribute("required");

				if ($required == "true"){

					$trigger['required'] = true;

					$invocation_node = $this->xpath->evaluate('response/invocation', $question);
					$trigger['invocation'] = $invocation_node -> item(0) -> nodeValue;

					$action_node = $this->xpath->evaluate('response/action', $question);
					$trigger['action'] = $action_node -> item(0) -> nodeValue;
				}

				if ($applicable_state){
					$lbl_text = str_replace("[retailer]", $this->planOptionsConfig['_retailer'] . " ", $elements  -> item(0) -> getAttribute("label"));
					$lbl_text = str_replace("[plan name]", $this->planOptionsConfig['_plan'] . " ", $lbl_text);

					$elementNode = $this -> HtmlNodeFactory -> generateNodeString(array(
						"element_class" => $elements  -> item(0) -> getAttribute("class"),
						"element_id_prefix" => 'planoptionLbl_',
						"element_id" => $elements  -> item(0) -> getAttribute("id"),
						"element_name" => $elements  -> item(0) -> getAttribute("name"),
						"element_label_value" => $lbl_text,
						"element_placeholder" => $elements  -> item(0) -> getAttribute("placeholder"),
						"element_trigger" => $trigger,
						"node_element" => $this->doc->saveXML($inputs->item(0))));

					if ((strtoupper($inputs->item(0)->getAttribute('type')) == "RADIO")|| (strtoupper($inputs->item(0)->getAttribute('type')) == "RADIO_PLUS") || (strtoupper($inputs->item(0)->getAttribute('type')) == "RADIO_ACTION_TRIGGER")){
						$this -> radioElements[$elements  -> item(0) -> getAttribute("id")] = $elements  -> item(0) -> getAttribute("id");
					}

					if ((strtoupper($inputs->item(0)->getAttribute('type')) == "SELECT")||(strtoupper($inputs->item(0)->getAttribute('type')) == "SELECT_HORIZONTAL")){
						$this -> selectElements[$elements  -> item(0) -> getAttribute("id")] = $elements  -> item(0) -> getAttribute("id");
					}

					$this -> element_Collection[] = $elementNode;
					$this -> planOptionsInputNames[$elements  -> item(0) -> getAttribute("id")] = $elements  -> item(0) -> getAttribute("id");

				}



			}
		} else {
			print_r("Null");
		}
    }

	public function getRadioElementList(){
		return $this -> radioElements;
	}

	public function getPlanOptionSelectElementList(){
		return $this -> selectElements;
	}

    public function getPlanOptionsHTMLCollection(){
    	return $this -> element_Collection;
    }

    public function getPlanOptionsElementList(){
    	return $this -> planOptionsInputNames;
    }

    public function getChildPlanOptionsElementList(){
    	return $this -> contextDependentOptions;
    }
}
