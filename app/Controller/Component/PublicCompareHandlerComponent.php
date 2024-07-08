<?php

App::uses('Component', 'Controller');

class PublicCompareHandlerComponent extends Component{
	
	private $doc;
	private $xpath;
	


	protected $_defaults = array(
		'snapshottxt' => null
	);	
	
	public function __construct(ComponentCollection $collection, $settings = array()) {

		$this->submissionConfig = array_merge($this->_defaults, $settings);

	}

	function setSubmissionHandler() {
		
		$this->doc = new DOMDocument();
		$this->doc->formatOutput = true;
		$this->doc->loadXml($this->submissionConfig['snapshottxt']);
		$this->xpath = new DOMXPath($this->doc);
		
   }
   
    public function addValueToSnapShot($key, $value, $user_id){
		//a method adding a valud to the tempalte snapshot XML
		$elm = $this->xpath->query("element[@id='" . $key . "']/data_record");
		if (!is_null($elm)){
			$data_record_Node = $elm -> item(0);
			$dataNode = $this->doc->createElement('data');
			$dataNode -> setAttribute("value", $value);
			$dataNode -> setAttribute("user", $user_id);
			$dataNode -> setAttribute("date", date("d-m-Y H:i:s"));
			$data_record_Node -> appendChild($dataNode);
		}
    }
    
    public function addSupplimentNodeToSnapShot($value, $user_id, $identifier){
		//a method adding a valud to the tempalte snapshot XML used for plan options, TAC and concessions
		$elm = $this->doc->createElement('element');
		$elm -> setAttribute("class", "compare.price");
		$elm -> setAttribute("id", $identifier['id']);
		$elm -> setAttribute("name", $identifier['id']);
		$elm -> setAttribute("label", $identifier['description']);
		$elm -> setAttribute("placeholder", "");
		
		$input = $this->doc->createElement('input');
		$input -> setAttribute("type", "text");
		
		$data_record = $this->doc->createElement('data_record');
		
		$dataNode = $this->doc->createElement('data');
		$dataNode -> setAttribute("value", $value);
		$dataNode -> setAttribute("user", $user_id);
		$dataNode -> setAttribute("date", date("d-m-Y H:i:s"));
		$data_record -> appendChild($dataNode);
		
		$elm -> appendChild($input);
		$elm -> appendChild($data_record);
		
		$publiccompare = $this->xpath->query("/publicCompare");
		$publiccompare -> item(0) -> appendChild($elm);

    }    
    

	public function getDocumentNode(){
		return $this -> doc -> getElementsByTagName('publicCompare') -> item(0);
	}
	

	public function getDocumentString(){
		return $this -> doc -> saveXML($this -> doc -> getElementsByTagName('publicCompare') -> item(0));
	}	
}
