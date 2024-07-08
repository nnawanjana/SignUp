<?php

App::uses('Component', 'Controller');

class PreSignUpTextComponent  extends Component{
	
	public $doc;
	protected $xpath;

	private $retailer = "";
	private $plan = "";
	private $query = "";
	
	private $outputNode;

	protected $elements;

	private $model_preTxt;
	
	private $count = 0;


	
	private $preTxtConfig = array();

	protected $_defaults = array(
		'_retailer' => null
	);

	public function __construct(ComponentCollection $collection, $settings = array()) {

		//$settings = array_merge($this->_defaults, $settings);
		$this->preTxtConfig = array_merge($this->_defaults, $settings);

	}



	function initialize(Controller $controller){
	}
	
	public function setComponent(){
	
		$this -> model_preTxt = ClassRegistry::init('ps_PreSignUpText');
		$outcome = $this->model_preTxt->find('first', array(
			'fields' => array('pre_signup_text_xml'),
			'order' => array('date_created' => 'desc')
		));	
		
		$xmltxt = $outcome['ps_PreSignUpText']['pre_signup_text_xml'];
		
		$this -> retailer = strtolower($this->preTxtConfig['_retailer']) ;
		
		$this -> retailer = str_replace(' ', '_', $this -> retailer);
		
		$this -> plan = $this->preTxtConfig['_plan'];
		
		$this -> query = "ew/pre_singup_text[@retailer ='" .  $this -> retailer . "']";
		
		$this -> doc = new DOMDocument();
		
		$this->doc->loadXml($xmltxt);
		
		$this->xpath = new DOMXPath($this->doc);

		$this -> elements = $this->xpath->evaluate($this -> query, $this -> doc);
		
		$this -> elements_universal = $this->xpath->evaluate("ew/pre_singup_text[@retailer ='all']", $this -> doc);
		
		$this -> outputFactory();
	
	
	}
		


    private function outputFactory(){

		$this -> outputNode = $this -> doc -> createElement("ul");

		if (!is_null($this -> elements_universal)) {

			foreach ($this -> elements_universal as $element) {

				$p = $element -> getAttribute("plan");
			
			
				if 	($p == "all"){
					$this -> count++;
					$liNode = $this -> doc -> createElement("li");
					$li_text = $this->doc->createTextNode($element -> textContent); 
					$liNode -> appendChild($li_text);
					$this -> outputNode -> appendChild($liNode);
					
				}

			}

		}
			
		if (!is_null($this -> elements)) {

			foreach ($this -> elements as $element) {

				$p = $element -> getAttribute("plan");
			
			
				if 	( $p == $this -> plan|| $p == "all"){
					$this -> count++;
					$liNode = $this -> doc -> createElement("li");
					$li_text = $this->doc->createTextNode($element -> textContent); 
					$liNode -> appendChild($li_text);
					$this -> outputNode -> appendChild($liNode);
					
				}

			}

		}
		
		
    }
    
    public function getPreSignUpText(){
    	$outcome = array();
    	$outcome['result'] = ($this -> count == 0)? false:true;
    	$outcome['contents'] = $this -> doc -> saveHTML($this -> outputNode);
    	return $outcome;
    }

}
