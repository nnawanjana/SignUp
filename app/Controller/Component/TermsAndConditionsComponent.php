<?php

App::uses('Component', 'Controller');

class TermsAndConditionsComponent  extends Component{

	public $doc;
	protected $xpath;
	private $groups = array();
	private $outputHTML;
	private $outputHTMLString;
	private $retailer = "";
	private $state = "";
	private $fuel = "";
	private $entityClass = "";
	private $plan = "";
	private $query = "";
	private $queryByGroupID = "tac/instance[@group='_xx_']";
	protected $elements;
	protected $EW_elements;
	private $tacSnapShotImage;
	private $helperDoc;
	private $model_tac;
	private $tac_id;



	private $tacConfig = array();

	protected $_defaults = array(
		'_retailer' => null,
		'_state' => null,
		'_fuel' => null,
		'_entityClass' => null,
		'_plan' => null
	);

	public function __construct(ComponentCollection $collection, $settings = array()) {

		//$settings = array_merge($this->_defaults, $settings);
		$this->tacConfig = array_merge($this->_defaults, $settings);

	}



	function initialize(Controller $controller){
	}

	public function setComponent(){

		$this -> model_tac = ClassRegistry::init('ps_TAC');
		$outcome = $this->model_tac->find('first', array(
			'fields' => array('tac_id', 'tac_rules_xml'),
			'order' => array('date_created' => 'desc')
		));

		$xmltxt = $outcome['ps_TAC']['tac_rules_xml'];
		$this -> tac_id = $outcome['ps_TAC']['tac_id'];


		$this -> retailer = strtolower($this->tacConfig['_retailer']) ;
		$this -> retailer = str_replace(' ', '_', $this -> retailer);

		$this -> state = $this->tacConfig['_state'];
		$this -> fuel = $this->tacConfig['_fuel'];
		$this -> entityClass = $this->tacConfig['_entityClass'];
		$this -> plan = $this->tacConfig['_plan'];
		$this -> query = "ew/" . $this -> retailer . "/instance";

		$this -> doc = new DOMDocument();
		$this -> helperDoc = new DOMDocument();

		$test = $this->doc->loadXml($xmltxt);
		$this->xpath = new DOMXPath($this->doc);
		$this -> xpath->registerNamespace('xhtml', "http://www.w3.org/1999/xhtml");

		//Retrieving TAC instances that is applicable to the given retailer
		$this -> elements = $this->xpath->evaluate($this -> query, $this -> doc);

		//Retrieving TAC instances that is constant to Deal Expert
		$this -> EW_elements = $this->xpath->evaluate("ew/dealexpert/instance", $this -> doc);

		$this -> outputFactory();


	}



	private static function SortBySort($a,$b) {
		if ((int)$a -> getAttribute("sort") == (int)$b -> getAttribute("sort")) {
			return 0;
		}
		return ((int)$a -> getAttribute("sort") < (int)$b -> getAttribute("sort")) ? -1 : 1;
	}

	/* Function that sort groups (sections) applicable to the given retailers and plans*/
	private function evaluateGroupID($_value){

		$flg = 0;
		foreach ($this -> groups as $group){
			if ($group == $_value){
				$flg = 1;
			}
		}

		if ($flg == 0){
			$this -> groups[] = $_value;
		}
	}

    private function outputFactory(){

		/*
		<element class="PersonalDetails" id="Reserved_TAC" name="PersonalDetails_termsconditions">
			<label>Terms and Conditions</label>
			<input type="checkbox" name="Reserved_TAC" />
		</element>

		*/

		$debug_count = 0;

		$helper_root = $this -> helperDoc -> createElement("tac");

		if (!is_null($this -> elements)) {

			foreach ($this -> elements as $element) {
				$s = $element -> getAttribute("state");
				$r = $element -> getAttribute("res_sme");
				$f = $element -> getAttribute("fuel");
				$p = $element -> getAttribute("plan");




				if 	(( $s == $this -> state || $s == "all") &&
					( $r == $this -> entityClass|| $r == "all") &&
					( $f == $this -> fuel|| $f == "all") &&
					( $p == $this -> plan|| $p == "all"))
					{

						$this -> evaluateGroupID($element -> getAttribute("group"));
						$node = $this -> helperDoc->importNode($element, true);
						$helper_root -> appendChild($node);


						//for debugging only
						$debug_count = $debug_count + 1;

					}

			}

			/********
			* appending Electricitywizard terms and conditions to the helper_root
			* an operation begins
			** note:  as this operation is a replicate of above, this is quite inefficient way of doing.
			** In furutre, let's look to refactor this to combine two together in the first place and
			**/
			if (!is_null($this -> EW_elements)) {

				foreach ($this -> EW_elements as $element) {
					$s = $element -> getAttribute("state");
					$r = $element -> getAttribute("res_sme");
					$f = $element -> getAttribute("fuel");
					$p = $element -> getAttribute("plan");


					if 	(( $s == $this -> state || $s == "all") &&
						( $r == $this -> entityClass|| $r == "all") &&
						( $f == $this -> fuel|| $f == "all") &&
						( $p == $this -> plan|| $p == "all"))
						{
							$this -> evaluateGroupID($element -> getAttribute("group"));
							$node = $this -> helperDoc->importNode($element, true);
							$helper_root -> appendChild($node);

						}

				}

			}

			/** an operation ends */



			$this -> helperDoc -> appendChild($helper_root);

			//Preparing XPath object for further querying
			$helperXPath = new DOMXPath($this -> helperDoc);

			//Preparing a container
			$this -> outputHTML = $this -> helperDoc -> createElement("ul");

			//sorting the accumulate groups by accending order
			asort($this -> groups);

			foreach ($this -> groups as $group){

				//retrieving nodes that matches the group id
				$newQuery = str_replace("_xx_",$group,$this -> queryByGroupID);
				$tacunits = $helperXPath -> evaluate($newQuery, $this -> helperDoc);

				$array_heloper = array();

				foreach ($tacunits as $tacunit){
					$array_heloper[] = $tacunit;

				}
				usort($array_heloper, array("TermsAndConditionsComponent","SortBySort"));

				foreach($array_heloper as $term){

					$results = $helperXPath ->evaluate("term", $term);
					$paragraph = $this -> helperDoc -> createElement("li");
					$paragraph  -> setAttribute("ref", $group);
					$paragraph  -> setAttribute("ref_sort", $term -> getAttribute("sort"));
					$txt = str_replace("[retailer]", $this->tacConfig['_retailer'] . " ", $results -> item(0) -> textContent);
					$paragraph -> appendChild($this -> helperDoc -> createTextNode ($txt));
					$this -> outputHTML -> appendChild($paragraph) ;

				}

			}
			$this -> tacSnapShotImage = $this -> helperDoc;
			$this -> outputHTMLString = $this -> helperDoc -> saveHTML($this -> outputHTML);


		} else {
			print_r("Null");
		}
    }

	public function getOutputHTMLString(){
		return $this -> outputHTMLString;
	}

	public function getTACSnapShotNodeImage(){
		$result = array();
		$result['id'] = $this -> tac_id;
		$result['NodeString'] = $this -> outputHTMLString;
		return $result;
	}
}
