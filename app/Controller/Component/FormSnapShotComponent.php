<?php

App::uses('Component', 'Controller');

class FormSnapShotComponent extends Component{

	public $doc;
	private $model_velocify;
	private $model_leadType;
	private $model_webform;
	private $model_target;



	function initialize(Controller $controller){

		$this->doc = new DOMDocument();
		$this -> model_velocify = ClassRegistry::init('ps_VelocifyMapper');
		$this -> model_leadType = ClassRegistry::init('ps_LeadTypes');
		$this -> model_webform = ClassRegistry::init('ps_WebformTemplates');
		$this -> model_target = ClassRegistry::init('ps_ApplicationTarget');
		$this -> model_snapshot = ClassRegistry::init('ps_Webforms');
		$this -> model_snapshot_plan_options = ClassRegistry::init('ps_SnapShot_PlanOptions');
		$this -> model_snapshot_concessions = ClassRegistry::init('ps_SnapShot_Concessions');
		$this -> model_snapshot_tac = ClassRegistry::init('ps_SnapShot_TAC');
		$this -> model_snapshot_user = ClassRegistry::init('ps_Users');
	}

	function startup(Controller $controller){
	}



   private function fetchResultsByAssociation($outcome){
 		$max = sizeof($outcome);
 		$result;
		if ($max > 0){
			$result = mysqli_fetch_array($outcome,MYSQLI_ASSOC);
			return $result;
		} else {
			$result = "";
			return $result;
		}

   }

	public function getSnapShotTemplate($appTargetName = null){
		$targets = $this->model_target->find('first', array(
			'conditions' => array('target = ' => $appTargetName)
		));

		$outcome = $this->model_webform->find('first', array(
			'conditions' => array('target_id = ' => (int)$targets["ps_ApplicationTarget"]["target_id"]),
			'fields' => array('webform_template_id', 'webform_template_xml'),
			'order' => array('date_created' => 'desc')
		));

		$result = array();
		$result['id'] = $outcome['ps_WebformTemplates']['webform_template_id'];
		$result['mappingTxt'] = $outcome['ps_WebformTemplates']['webform_template_xml'];
		return $result;

	}

	public function getVelocifyMappingFields(){
		// At first, retrieve leadtype specific map


		$lead_type_id_all = $this->model_leadType->find('first', array(
				'conditions' => array('lead_type_name =' => 'Residential'),
				'fields' => array('lead_type_id')
		));


		$mapper_xml_all = $this->model_velocify->find('first', array(
				'conditions' => array('lead_type_id =' => (int)$lead_type_id_all["ps_LeadTypes"]["lead_type_id"]),
				'fields' => array('mapper_xml')
		));


		$result = array();
		$result['lead_type_id'] = (int)$lead_type_id_all["ps_LeadTypes"]["lead_type_id"];
		$result['mappingTxt'] = $mapper_xml_all['ps_VelocifyMapper']['mapper_xml'];
		return $result;


	}


	public function getPublicSignUpTargetID(){

		$targets = $this->model_target->find('first', array(
			'conditions' => array('target = ' => 'publicSignUp')
		));
		return $targets["ps_ApplicationTarget"]["target_id"];
	}

	public function getPublicCompareTargetID(){

		$targets = $this->model_target->find('first', array(
			'conditions' => array('target = ' => 'publicCompare')
		));
		return $targets["ps_ApplicationTarget"]["target_id"];
	}

   public function getPublicSignUpSnapShot(){
		$targets = $this->model_target->find('first', array(
			'conditions' => array('target = ' => 'publicSignUp')
		));

		//retriving XML template
		$outcome = $this->model_webform->find('first', array(
			'conditions' => array('target_id = ' => (int)$targets["ps_ApplicationTarget"]["target_id"]),
			'fields' => array('webform_template_id', 'webform_template_xml', 'target_id'),
			'order' => array('date_created' => 'desc')
		));

		$result = array();
		$result['id'] = $outcome['ps_WebformTemplates']['webform_template_id'];
		$result['target_id'] = $outcome['ps_WebformTemplates']['target_id'];
		$result['mappingTxt'] = $outcome['ps_WebformTemplates']['webform_template_xml'];
		return $result;

   }

public function getPublicSignUpSnapShotImage($leadid = null){
		$targets = $this->model_target->find('first', array(
			'conditions' => array('target = ' => 'publicSignUp')
		));

		//retriving XML template
		$outcome = $this->model_snapshot->find('first', array(
			'conditions' => array('target_id = ' => (int)$targets["ps_ApplicationTarget"]["target_id"], 'leadid' => (int)$leadid),
			'fields' => array('webform_id', 'webform_xml', 'target_id'),
			'order' => array('date_created' => 'desc')
		));

		$result = array();
		$result['id'] = $outcome['ps_Webforms']['webform_id'];
		$result['mappingTxt'] = $outcome['ps_Webforms']['webform_xml'];
		return $result;
   }

   public function getPublicSignUpPlanOptionsSnapShotImage($leadid = null){

		//retriving XML template
		$outcome = $this->model_snapshot_plan_options->find('first', array(
			'conditions' => array('webforms_id' => (int)$leadid),
			'fields' => array('snapshot_xml')
		));

		$result = array();
		$result['mappingTxt'] = $outcome['ps_SnapShot_PlanOptions']['snapshot_xml'];
		return $result;
   }

   public function getPublicSignUpConcessionsSnapShotImage($leadid = null){

		//retriving XML template
		$outcome = $this->model_snapshot_concessions->find('first', array(
			'conditions' => array('webforms_id' => (int)$leadid),
			'fields' => array('snapshot_xml')
		));

		$result = array();
		$result['mappingTxt'] = $outcome['ps_SnapShot_Concessions']['snapshot_xml'];
		return $result;
   }

   public function getPublicSignUpTACSnapShotImage($leadid = null){

		//retriving XML template
		$outcome = $this->model_snapshot_tac->find('first', array(
			'conditions' => array('webforms_id' => (int)$leadid),
			'fields' => array('snapshot_xml')
		));

		$result = array();
		$result['mappingTxt'] = $outcome['ps_SnapShot_TAC']['snapshot_xml'];
		return $result;
   }

   public function getPublicSignUpUseerImage($userid = null){

		//retriving XML template
		$outcome = $this->model_snapshot_user->find('first', array(
			'conditions' => array('user_id' => (int)$userid),
			'fields' => array('user_id','user_email','user_phone','user_title','user_firstname','user_surname')
		));

		$result = array(
			'id'=>$outcome['ps_Users']['user_id'],
			'email'=>$outcome['ps_Users']['user_email'],
			'phone'=>$outcome['ps_Users']['user_phone'],
			'name'=>$outcome['ps_Users']['user_title'] . '. ' .$outcome['ps_Users']['user_firstname']. ' '.$outcome['ps_Users']['user_surname']
		);
		return $result;
   }

   public function getPublicCompareSnapShot(){
		$targets = $this->model_target->find('first', array(
			'conditions' => array('target = ' => 'publicCompare')
		));
		//retriving XML template
		$outcome = $this->model_webform->find('first', array(
			'conditions' => array('target_id = ' => (int)$targets["ps_ApplicationTarget"]["target_id"]),
			'fields' => array('webform_template_id', 'webform_template_xml'),
			'order' => array('date_created' => 'desc')
		));
		$result = array();
		$result['id'] = $outcome['ps_WebformTemplates']['webform_template_id'];
		$result['mappingTxt'] = $outcome['ps_WebformTemplates']['webform_template_xml'];
		return $result;

   }


}
