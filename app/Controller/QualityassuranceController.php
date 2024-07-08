<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'htmlpurifier', array('file' => 'htmlpurifier-4.7.0'.DS.'library'.DS.'HTMLPurifier.auto.php'));
App::import('Vendor', 'nusoap', array('file' => 'nusoap'.DS.'lib'.DS.'nusoap.php'));

Class QualityassuranceController extends AppController {

	public $components = array('Encryptor','FormSnapShot', 'PublicSignUp');
	public $uses = array('qa_testunits', 'qa_testcases', 'qa_testresults', 'qa_verfication_outcomes', 'qa_issues', 'qa_issue_logs', 'qa_issue_status', 'ps_SnapShot_Concessions','ps_SnapShot_PlanOptions','ps_SnapShot_TAC','ps_Webforms', 'qa_users', 'qa_issues', 'qa_issue_status','qa_issue_response_logs', 'qa_testtypes','ps_Retailers', 'qa_velocifypost', 'ps_Users', 'ps_ApplicationTarget', 'Plan');
	private $qaConfig;
	private $helper_array;

	private $proxyhost = 'https://service.leads360.com/ClientService.asmx';
	private $proxyport = '';
	private $proxyusername = 'api@electricitywizard.com.au';
	private $proxypassword = '*Pa55Elec!';

	private $temp_memory;
	private $plan_state = '';
	private $plan_retailer = '';
	private $plan_res_sme = '';
	private $plan_package = '';
	private $plan_product_name = '';



	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow();
		$this->layout = 'qualityassurance';
	}

	public function index() {
	}

	public function QA_GetEncryptedText(){

		if ($this->request->is('post') || $this->request->is('put')) {
			if (!$this->Session->check('QA')){

				//error

				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'unsuccessful',
						'RESULT' => 'access denied'
					)),
					'type' => 'json',
					'status' => '200'
				));

			} else {
				$data = json_decode(file_get_contents("php://input"), true);
				$pw = $data["pw"];
				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'successful',
						'RESULT' => $this -> Encryptor -> encryptor('encrypt',$pw)
					)),
					'type' => 'json',
					'status' => '200'
				));
			}

		}
	}

	public function QA_Login(){

		if ($this->Session->check('QA')){
			$this->redirect( '/QA_Main' );
		}



		if ($this->request->is('post') || $this->request->is('put')) {
			$flg = 0;
			$error_msg = "";
			if (empty($_POST["password"]) || empty($_POST["username"])){
				$flg = 1;
				//error
				$error_msg = "Both Username and Password are required";

			} else {

				$this-> qa_users -> create();
				$QA_TEST_USER = $this->qa_users->find('first', array(
					'conditions' => array('QA_USER_NAME' => $_POST["username"], 'USER_STATUS' => 1),
					'fields' => array('QA_USER_ID', 'QA_PASSWORD', 'USER_FIRSTNAME', 'USER_SURNAME', 'ROLE_ID', )
				));

				if (empty($QA_TEST_USER)){
					$flg = 1;
					$error_msg = "mmm....  the system can not find username you specified";
				}

				$encryped_string = $this -> Encryptor -> encryptor('encrypt',$_POST["password"]);

				if ($encryped_string != $QA_TEST_USER['qa_users']['QA_PASSWORD']){
					$flg = 1;
					$error_msg = "mmm....  the password you supplied does not match the username, though";
				}


				if ($flg == 0){
					$this->Session->write('QA',array(
						'QA' => true,
						'USER_ID' => (int)$QA_TEST_USER['qa_users']['QA_USER_ID'],
						'PROJECT_ID' => 0,
						'TEST_TYPE_ID' => 0,
						'TEST_UNIT_ID' => 0,
						'TEST_CASES_IDs' => array(),
						'CONFIG' => array(
							'VELOCIFY_LEAD' => 0,
							'OPERATION_HOURS'=>0,
							'OPERATION_BEGINS'=>8,
							'OPERATION_ENDS' => 18,
							'RETAILER_QUALIFIER' => 0
						),
						'FORCE_VELOCIFY_LEAD_GEN' => 0,
						'MODE' => array(
							'UAT' => 0
						)
					));
					$this->redirect( '/QA_Main' );
				} else {

				}


				//$hashed_pw = password_hash($_POST["password"], PASSWORD_DEFAULT);
				//$this -> log('hashed pw:::'. $hashed_pw, 'debug');
				//$this -> log('hashed pw verify:::'. password_verify($_POST["password"], $hashed_pw), 'debug');

			}



		}
		$this -> set('');
	}

	public function QA_Main(){

		if (!$this->Session->check('QA')){
			$this->redirect( '/about-you' );
		}

		$QA_RETAILERS = $this->ps_Retailers->find('all', array(
			'fields' => array('retailer_name','retailer_abbreviation')
		));
		$qa_retailers_list = json_encode($QA_RETAILERS);

		$this -> set(compact('qa_retailers_list'));
	}

	public function QA_View_Snapshot(){

		$this -> set('');
	}

	public function QA_SetQA_Mode(){
		if ($this->request->is('post') || $this->request->is('put')) {

			if (!$this->Session->check('QA')){

				//error

				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'unsuccessful',
						'RESULT' => 'access denied'
					)),
					'type' => 'json',
					'status' => '200'
				));

			} else {
				$data = json_decode(file_get_contents("php://input"), true);
				$qa = $this->Session->read('QA');

				$qa['MODE']['UAT'] = (int)$data['mode_value'];

				$this->Session->write('QA', $qa);

				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'successful',
						'RESULT' => json_encode($data)
					)),
					'type' => 'json',
					'status' => '200'
				));

			}

		}

	}

	public function QA_SetQACriteria(){
		if ($this->request->is('post') || $this->request->is('put')) {

			if (!$this->Session->check('QA')){

				//error

				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'unsuccessful',
						'RESULT' => 'access denied'
					)),
					'type' => 'json',
					'status' => '200'
				));

			} else {
				$data = json_decode(file_get_contents("php://input"), true);
				$qa = $this->Session->read('QA');

				$qa['CONFIG'] = array();
				//$this -> log('data:::'.json_encode($data), 'debug');

				$ratiler_qualifier = array();

				foreach($data['retailers'] as $key => $value){
					$ratiler_qualifier[$value[0]] = ($value[1] == 'INCLUDE')? 1:0;

				}


				$qa['CONFIG']['VELOCIFY_LEAD'] = $data['velocify_lead'];
				$qa['CONFIG']['OPERATION_HOURS'] = $data['operation_hours'];
				$qa['CONFIG']['OPERATION_BEGINS'] = $data['operation_begins'];
				$qa['CONFIG']['OPERATION_ENDS'] = $data['operation_ends'];
				$qa['CONFIG']['RETAILER_QUALIFIER'] = $ratiler_qualifier;

				$this->Session->write('QA', $qa);

				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'successful',
						'RESULT' => json_encode($data)
					)),
					'type' => 'json',
					'status' => '200'
				));

			}

		}
	}

	public function QA_ClearQACriteria(){
		if ($this->request->is('post') || $this->request->is('put')) {

			if (!$this->Session->check('QA')){

				//error

				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'unsuccessful',
						'RESULT' => 'access denied'
					)),
					'type' => 'json',
					'status' => '200'
				));

			} else {
				$data = json_decode(file_get_contents("php://input"), true);
				$qa = $this->Session->read('QA');

				$qa['CONFIG'] = array(
							'VELOCIFY_LEAD' => 0,
							'OPERATION_HOURS'=>0,
							'OPERATION_BEGINS'=>8,
							'OPERATION_ENDS' => 18,
							'RETAILER_QUALIFIER' => 0
						);

				$this->Session->write('QA', $qa);

				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'successful',
						'RESULT' => json_encode($data)
					)),
					'type' => 'json',
					'status' => '200'
				));

			}

		}
	}

	public function QA_GetQAConfig(){
		$qa = $this->Session->read('QA');

		return new CakeResponse(array(
			'body' => json_encode(array(
				'status' => 'successful',
				'RESULT' => json_encode($qa)
			)),
			'type' => 'json',
			'status' => '200'
		));
	}

	public function QA_Signup(){

		if (!$this->Session->check('QA')){

			$this->redirect( '/about-you' );

		} else {

			$qaconfig = $this->Session->read('QA');

		}

		if ($this->request->is('put') || $this->request->is('post')) {


			if ((int)$qaconfig['TEST_TYPE_ID'] == 1){
				$this -> QA_LoadAvailabilityTestData();

			} else {
				$this -> QA_LoadSignUpTestData();
			}

			$this->redirect( '/QA_Snapshot' );

		} else {

			//@ GET access, collect the test type
			$qaconfig['TEST_TYPE_ID'] = (int)$_GET["test_type"];
			$qaconfig['FORCE_VELOCIFY_LEAD_GEN'] = 1;
			$this->Session->write('QA', $qaconfig);




		}
		$this -> set('');
	}

	public function QA_Snapshot(){

		$qaconfig = $this->Session->read('QA');


		$operation = '';
		$btn_class = '';
		//Velocify request string (field/data mapping)
		if ((int)$qaconfig['TEST_TYPE_ID'] == 9){
			$operation = 'velocify';
			$btn_class = 'velocifyPostVar';
		}

		//Velocify request string (field/data mapping)
		if ((int)$qaconfig['TEST_TYPE_ID'] == 8){
			$operation = 'signup';
			$btn_class = 'loadSignUpVar';
		}
		//Snapshot images
		if ((int)$qaconfig['TEST_TYPE_ID'] == 7){
			$operation = 'snapshot';
			$btn_class = 'loadSignUpVar';
		}
		//Service availability test
		if ((int)$qaconfig['TEST_TYPE_ID'] == 1){
			$operation = 'availability';
			$btn_class = 'loadVar';
		}
		//Field validation test
		if ((int)$qaconfig['TEST_TYPE_ID'] == 3){
			$operation = 'validation';
			$btn_class = 'loadVar';
		}

		$testData = $this -> getTestSource();

		$ew = $testData['ew'];
		$local_qa_config = $testData['local_qa_config'];

		$this -> set(compact('ew', 'local_qa_config', 'operation', 'btn_class'));

	}

	public function QA_Retrieve_Testcase(){
		if ($this->request->is('post') || $this->request->is('put')) {
			$return_value = array();


			$QA_TEST_RESULT = $this->qa_testresults->find('first', array(
				'conditions' => array('QA_RESULT_ID' => (int)$_POST['RESULT_ID']),
				'fields' => array('QA_RESULT_XML')
			));

			$return_value['RESULT'] = $QA_TEST_RESULT['qa_testresults']['QA_RESULT_XML'];

			$QA_TEST_CASE = $this->qa_testcases->find('first', array(
				'conditions' => array('QA_TEST_CASE_ID' => (int)$_POST['CASE_ID']),
				'fields' => array('QA_TEST_CASE_XML')
			));

			$return_value['CASE'] = $QA_TEST_CASE['qa_testcases']['QA_TEST_CASE_XML'];

			return new CakeResponse(array(
				'body' => json_encode(array(
					'status' => 'successful',
					'QA_CASE_ID' => (int)$_POST['CASE_ID'],
					'QA_RESULT_ID' => (int)$_POST['RESULT_ID'],
					'RESULT' => $QA_TEST_RESULT['qa_testresults']['QA_RESULT_XML'],
					'CASE' => $QA_TEST_CASE['qa_testcases']['QA_TEST_CASE_XML']
				)),
				'type' => 'json',
				'status' => '200'
			));

		}
	}

	public function QA_GetVelocifyPostResponse(){
		if ($this->request->is('post') || $this->request->is('put')) {

			$this->qa_velocifypost->create();

			$VELOCIFY_RESPONSE = $this->qa_velocifypost->find('first', array(
				'conditions' => array('lead_id' => (int)$_POST["lead_id"], 'command' => 'POST'),
				'fields' => array('post_string','response_string')
			));
			return new CakeResponse(array(
				'body' => json_encode(array(
					'status' => 'successful',
					'POST_STRING' => $VELOCIFY_RESPONSE['qa_velocifypost']['post_string'],
					'RESPONSE_XML' => $VELOCIFY_RESPONSE['qa_velocifypost']['response_string'],
				)),
				'type' => 'json',
				'status' => '200'
			));


		} else {
			return new CakeResponse(array(
				'body' => json_encode(array(
					'status' => 'unsuccessful',
					'msg' => 'contract violation'
				)),
				'type' => 'json',
				'status' => '200'
			));
		}

	}

	public function QA_GetLeadIdBySignUpImage(){
		if ($this->request->is('post') || $this->request->is('put')) {

			$this->ps_Webforms->create();

			$LEAD_ID = $this->ps_Webforms->find('first', array(
				'conditions' => array('webform_id' => (int)$_POST["signup_image_id"]),
				'fields' => array('leadid')
			));
			return new CakeResponse(array(
				'body' => json_encode(array(
					'status' => 'successful',
					'LEAD_ID' => $LEAD_ID['ps_Webforms']['leadid']
				)),
				'type' => 'json',
				'status' => '200'
			));


		} else {
			return new CakeResponse(array(
				'body' => json_encode(array(
					'status' => 'unsuccessful',
					'msg' => 'contract violation'
				)),
				'type' => 'json',
				'status' => '200'
			));
		}


	}

	public function QA_Assessment(){

		$test_cases = $_GET["results"];
		$qa = $this->Session->read('QA');
		$local_qa_config = json_encode($qa);
		$this -> set(compact('test_cases', 'local_qa_config'));
	}

	public function QA_AssessSnapshot(){

		$test_cases = $_GET["results"];
		$qa = $this->Session->read('QA');
		$local_qa_config = json_encode($qa);
		$this -> set(compact('test_cases', 'local_qa_config'));
	}

	public function QA_AssessVelocifyRequest(){

		$test_cases = $_GET["results"];
		$qa = $this->Session->read('QA');
		$local_qa_config = json_encode($qa);
		$this -> set(compact('test_cases', 'local_qa_config'));
	}

	public function QA_AssessVelocifyPost(){
		$test_cases = $_GET["results"];
		$qa = $this->Session->read('QA');
		$local_qa_config = json_encode($qa);
		$this -> set(compact('test_cases', 'local_qa_config'));

	}

	public function QA_Retrieve_SnapshotImages(){
		if ($this->request->is('post') || $this->request->is('put')) {

			$data = json_decode(file_get_contents("php://input"), true);

			$SNAPSHOT_CONCESSION = $this->ps_SnapShot_Concessions->find('first', array(
				'conditions' => array('concessions_snapshot_id' => (int)$data['concession_id']),
				'fields' => array('snapshot_xml')
			));

			$SNAPSHOT_PLANOPTIONS = $this->ps_SnapShot_PlanOptions->find('first', array(
				'conditions' => array('planOption_snapshot_id' => (int)$data['planoption_id']),
				'fields' => array('snapshot_xml')
			));

			$SNAPSHOT_TAC = $this->ps_SnapShot_TAC->find('first', array(
				'conditions' => array('tac_snapshot_id' => (int)$data['tac_id']),
				'fields' => array('snapshot_xml')
			));
			$SNAPSHOT_SIGNUP = $this->ps_Webforms->find('first', array(
				'conditions' => array('webform_id' => (int)$data['signup_id']),
				'fields' => array('webform_xml')
			));

			$SNAPSHOT_COMPARE = $this->ps_Webforms->find('first', array(
				'conditions' => array('webform_id' => (int)$data['comapre_image_id']),
				'fields' => array('webform_xml')
			));

			$response_value = array();
			$response_value['SNAPSHOT_CONCESSION'] = $SNAPSHOT_CONCESSION['ps_SnapShot_Concessions']['snapshot_xml'];
			$response_value['SNAPSHOT_PLANOPTIONS'] = $SNAPSHOT_PLANOPTIONS['ps_SnapShot_PlanOptions']['snapshot_xml'];
			$response_value['SNAPSHOT_TAC'] = $SNAPSHOT_TAC['ps_SnapShot_TAC']['snapshot_xml'];
			$response_value['SNAPSHOT_SIGNUP'] = $SNAPSHOT_SIGNUP['ps_Webforms']['webform_xml'];
			$response_value['SNAPSHOT_COMPARE'] = $SNAPSHOT_COMPARE['ps_Webforms']['webform_xml'];

			return new CakeResponse(array(
				'body' => json_encode(array(
					'snapshotimages' => $response_value,
					'status' => 'successful'
				)),
				'type' => 'json',
				'status' => '200'
			));

		}




	}

	public function QA_Confirm_PASS(){
		if ($this->request->is('post') || $this->request->is('put')) {

			$data = json_decode(file_get_contents("php://input"), true);




			$QA_VERIF_OUTCOME = $this->qa_verfication_outcomes->find('first', array(
				'conditions' => array('OUTCOME' => 'PASSED'),
				'fields' => array('VERIFICATION_OUTCOME_ID')
			));
			$verf_id = $QA_VERIF_OUTCOME['qa_verfication_outcomes']['VERIFICATION_OUTCOME_ID'];

			$this->qa_testresults->save(array(
				"QA_RESULT_ID" => (int)$data["RESULT_ID"],
				"VERIFICATION_OUTCOME" => (int)$verf_id,
				"VERIFIED_BY" => (int)$data["USER_ID"],
				"DATE_VERIFIED" => date('Y-m-d H:i:s')
			));


			return new CakeResponse(array(
				'body' => json_encode(array(
					'status' => 'successful',
					'QA_CASE_ID' => $data["CASE_ID"],
					'QA_RESULT_ID' => $data["RESULT_ID"],
					'RESULT' => "WOW"
				)),
				'type' => 'json',
				'status' => '200'
			));

		}

	}

	public function QA_ReportIssue(){
		$issue_cases = $_GET["issue"];

		$this -> set(compact('issue_cases'));

	}

	public function QA_Register_Issue(){
		if ($this->request->is('post') || $this->request->is('put')) {
			$data = json_decode(file_get_contents("php://input"), true);

			$qa = $this->Session->read('QA');


			$QA_ISSUE_STATUS = $this->qa_issue_status->find('first', array(
				'conditions' => array('STATUS' => 'OPEN'),
				'fields' => array('STATUS_ID')
			));


			$this->qa_issues->save(array(
				"ISSUE_RESULT_ID" => (int)$data["RESULT_ID"],
				"ISSUE_STATUS" => (int)$QA_ISSUE_STATUS['qa_issue_status']['STATUS_ID'],
				"ISSUE_TITLE" => $data["ISSUE_TITLE"],
				"ISSUE_DESCRIPTION" => $data["ISSUE_DESCRIPTION"],
				"ISSUE_OWNER" => (int)$qa['USER_ID'],
				"RESOLUTION_ACCOUNTABLE" => 1,
				"PROJECT_ID" => (int)$qa['PROJECT_ID'],
				"ISSUED_DATE" => date('Y-m-d H:i:s'),
			));

			$this->qa_issue_logs->save(array(
				"ISSUE_ID" => (int)$this->qa_issues->id,
				"ISSUE_STATUS" => (int)$QA_ISSUE_STATUS['qa_issue_status']['STATUS_ID'],
				"UPDATED_BY" => (int)$qa['USER_ID'],
				"COMMENTS" => "ISSUE FIRST OPENED",
				"DATE_UPDATED" => date('Y-m-d H:i:s')
			));

			return new CakeResponse(array(
				'body' => json_encode(array(
					'status' => 'successful',
					'QA_ISSUE_ID' => $this->qa_issues->id,
					'RESULT' => array()
				)),
				'type' => 'json',
				'status' => '200'
			));

		}


	}

	public function QA_Issues(){
		if (!$this->Session->check('QA')){

			$this->redirect( '/about-you' );

		}
		$this -> set('');
	}

	private function query_issue_by_id($issue_id = null){

			$qaconfig = $this->Session->read('QA');


			$this -> qa_issues -> create();
			$QA_ISSUES = $this->qa_issues->find('first', array(
				'conditions' => array('ISSUE_ID' => (int)$issue_id),
				'fields' => array('ISSUE_ID','ISSUE_RESULT_ID','ISSUE_STATUS','ISSUE_TITLE','ISSUE_DESCRIPTION','ISSUED_DATE')
			));


			return array('issues' => $QA_ISSUES);

	}

	private function query_issues(){

			$qaconfig = $this->Session->read('QA');

			$users = array();

			$this-> qa_users -> create();
			$this -> qa_issues -> create();

			$QA_TEST_USER = $this->qa_users->find('first', array(
				'conditions' => array('QA_USER_ID' => (int)$qaconfig["USER_ID"]),
				'fields' => array('ROLE_ID')
			));

			if ((int)$QA_TEST_USER['qa_users']['ROLE_ID'] < 3){
				//developer authorisation
				$QA_ISSUES = $this->qa_issues->find('all', array(
					'conditions' => array('PROJECT_ID' => (int)$qaconfig['PROJECT_ID']),
					'fields' => array('ISSUE_ID','ISSUE_OWNER','ISSUE_RESULT_ID','ISSUE_STATUS','ISSUE_TITLE','ISSUE_DESCRIPTION','ISSUED_DATE')
				));
			} else {
				$QA_ISSUES = $this->qa_issues->find('all', array(
					'conditions' => array('ISSUE_OWNER' => (int)$qaconfig['USER_ID'], 'PROJECT_ID' => (int)$qaconfig['PROJECT_ID']),
					'fields' => array('ISSUE_ID','ISSUE_OWNER','ISSUE_RESULT_ID','ISSUE_STATUS','ISSUE_TITLE','ISSUE_DESCRIPTION','ISSUED_DATE')
				));
			}

			if (!empty($QA_ISSUES)){

				foreach( $QA_ISSUES as $key => $value){

					$QA_TEST_USER = $this->qa_users->find('first', array(
						'conditions' => array('QA_USER_ID' => (int)$QA_ISSUES[$key]['qa_issues']['ISSUE_OWNER']),
						'fields' => array('USER_FIRSTNAME', 'USER_SURNAME')
					));

					$users['ISSUE_ID_'.$QA_ISSUES[$key]['qa_issues']['ISSUE_ID']] = $QA_TEST_USER['qa_users']['USER_FIRSTNAME']. ' ' . $QA_TEST_USER['qa_users']['USER_SURNAME'];

				}

			}





			$this -> qa_issue_status -> create();
			$QA_ISSUE_STATUS = $this->qa_issue_status->find('all', array(
				'fields' => array('STATUS_ID','STATUS')
			));


			return array('issues' => $QA_ISSUES, 'list_status'	=> $QA_ISSUE_STATUS, 'users'=>$users);

	}

	private function query_issue_reponses($issue_id = null){

		$qaconfig = $this->Session->read('QA');
		$users = array();


		$this -> qa_issue_response_logs -> create();
		$QA_ISSUE_RESPONSES = $this->qa_issue_response_logs->find('all', array(
			'conditions' => array('ISSUE_ID' => (int)$issue_id),
			'fields' => array('RESPONSE_LOG_ID','ISSUE_ID','RESPONSE_DESCRIPTION','RESPONSE_RECORDED_BY','RESPONSE_DATE','ACTION_STATUS')
		));


		if (!empty($QA_ISSUE_RESPONSES)){
			$this-> qa_users -> create();
			foreach( $QA_ISSUE_RESPONSES as $key => $value){

				$QA_TEST_USER = $this->qa_users->find('first', array(
					'conditions' => array('QA_USER_ID' => (int)$QA_ISSUE_RESPONSES[$key]['qa_issue_response_logs']['RESPONSE_RECORDED_BY']),
					'fields' => array('USER_FIRSTNAME', 'USER_SURNAME')
				));
				$users['RESPONSE_LOG_ID_'.$QA_ISSUE_RESPONSES[$key]['qa_issue_response_logs']['RESPONSE_LOG_ID']] = $QA_TEST_USER['qa_users']['USER_FIRSTNAME']. ' ' . $QA_TEST_USER['qa_users']['USER_SURNAME'];
			}

		}


		return array('responses' => $QA_ISSUE_RESPONSES, 'users' => $users);

	}

	public function QA_getIssueByID(){

		if (!$this->Session->check('QA')){

			//error

			return new CakeResponse(array(
				'body' => json_encode(array(
					'status' => 'unsuccessful',
					'RESULT' => 'access denied'
				)),
				'type' => 'json',
				'status' => '200'
			));

		} else {
			return new CakeResponse(array(
				'body' => json_encode(array(
					'status' => 'successful',
					'RESULT' => json_encode($this -> query_issue_by_id($_GET["issue_id"]))
				)),
				'type' => 'json',
				'status' => '200'
			));

		}


	}

	public function QA_getIssueList(){

		if (!$this->Session->check('QA')){

			//error

			return new CakeResponse(array(
				'body' => json_encode(array(
					'status' => 'unsuccessful',
					'RESULT' => 'access denied'
				)),
				'type' => 'json',
				'status' => '200'
			));

		} else {
			return new CakeResponse(array(
				'body' => json_encode(array(
					'status' => 'successful',
					'RESULT' => json_encode($this -> query_issues())
				)),
				'type' => 'json',
				'status' => '200'
			));

		}


	}

	public function QA_getIssueResponses(){

		if (!$this->Session->check('QA')){

			//error

			return new CakeResponse(array(
				'body' => json_encode(array(
					'status' => 'unsuccessful',
					'RESULT' => 'access denied'
				)),
				'type' => 'json',
				'status' => '200'
			));

		} else {
			return new CakeResponse(array(
				'body' => json_encode(array(
					'status' => 'successful',
					'RESULT' => json_encode($this -> query_issue_reponses($_GET["issue_id"]))
				)),
				'type' => 'json',
				'status' => '200'
			));

		}


	}

	public function QA_Issue_Response(){
		$issue_id = '';
		if (!$this->Session->check('QA')){

			$this->redirect( '/about-you' );

		}  else {
			$issue_id = $_GET["issue_id"];

		}


		$this -> set(compact('issue_id'));


	}

	public function QA_Register_Reponse(){
		if ($this->request->is('post') || $this->request->is('put')) {

			if (!$this->Session->check('QA')){

				//error

				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'unsuccessful',
						'RESULT' => 'access denied'
					)),
					'type' => 'json',
					'status' => '200'
				));

			} else {
				$data = json_decode(file_get_contents("php://input"), true);
				$qa = $this->Session->read('QA');


				$response_timestamp = date('Y-m-d H:i:s');
				$this -> qa_issue_response_logs -> create();
				$this -> qa_issue_response_logs -> save(array(
					'RESPONSE_DESCRIPTION' => $data['RESPONSE_DESCRIPTION'],
					'ISSUE_ID' => $data['ISSUE_ID'],
					'RESPONSE_RECORDED_BY' => (int)$qa['USER_ID'],
					'RESPONSE_DATE' => $response_timestamp,
					'ACTION_STATUS' => (int)$data['STATUS']
				));

				$this-> qa_issues -> create();
				$this->qa_issues->save(array('ISSUE_ID' => (int)$data['ISSUE_ID'], 'ISSUE_STATUS' => (int)$data['STATUS']));


				$this-> qa_users -> create();
				$QA_TEST_USER = $this->qa_users->find('first', array(
					'conditions' => array('QA_USER_ID' => $qa["USER_ID"]),
					'fields' => array('USER_FIRSTNAME', 'USER_SURNAME')
				));





				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'successful',
						'RESULT' => json_encode(array('response_id' => $this -> qa_issue_response_logs -> id, 'timestamp' => $response_timestamp, 'recorded_by' => $QA_TEST_USER['qa_users']['USER_FIRSTNAME']. ' ' .$QA_TEST_USER['qa_users']['USER_SURNAME']))
					)),
					'type' => 'json',
					'status' => '200'
				));

			}






		}
	}

	public function QA_Set_ProjectID(){
		if ($this->request->is('put') || $this->request->is('post')) {

			if (!$this->Session->check('QA')){

				//error

				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'unsuccessful',
						'RESULT' => 'access denied'
					)),
					'type' => 'json',
					'status' => '200'
				));

			} else {

				$qaconfig = $this->Session->read('QA');

				$data = json_decode(file_get_contents("php://input"), true);

				$qaconfig['PROJECT_ID'] = (int)$data['PROJECT_ID'];

				$this->Session->write('QA', $qaconfig);



				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'successful',
						'RESULT' => $qaconfig['PROJECT_ID']
					)),
					'type' => 'json',
					'status' => '200'
				));




			}


		}
	}

	public function QA_VelocifyRequestString(){
		$testData = $this -> getTestSource();

		$ew = $testData['ew'];
		$local_qa_config = $testData['local_qa_config'];

		$this -> set(compact('ew', 'local_qa_config'));
	}

	private function QA_LoadSignUpTestData(){

		$ew  = "";
		if ($this->request->is('put') || $this->request->is('post')) {

			$qaConfig = $this->Session->read('QA');
			//$this -> qaConfig['TEST_TYPE_ID'] = 8;


			$file = $_FILES['SignUpCSVToUpload']['tmp_name'];

			$file_x = file_get_contents($file, true);

			$separator = ",";
			$length = 0;
			$inputFile = fopen($file, "r");

			$xdata = str_getcsv($file_x, "\n");


			// Get the headers of the file

			$headers = str_getcsv($xdata[3], ",");



			// Create a new dom document with pretty formatting
			$doc  = new DomDocument();
			$doc->formatOutput   = true;

			// Add a root node to the document
			$root = $doc->createElement('rows');
			$root = $doc->appendChild($root);

			// Loop through each row creating a <row> node with the correct data
			$count = 0;
			while (($row = fgetcsv($inputFile)) !== FALSE)
			{
				$count++;

    			if (($count == 1)||($count == 2)||($count == 3)||($count == 4)) { continue; }

				$container = $doc->createElement('row');
				$count_x = 0;
				foreach ($headers as $i => $header)
				{
					$child = $doc->createElement($header);
					$child = $container->appendChild($child);
					$value = $doc->createTextNode($row[$i]);
					$value = $child->appendChild($value);
				}

				$root->appendChild($container);
			}

			//$this -> log($doc -> saveXML(), 'debug');


			$this -> qa_testunits -> create();
			$this -> qa_testunits -> save(array(
				'QA_TEST_SOURCE' => $doc ->saveXML(),
				'QA_PROJECT_ID' => $qaConfig['PROJECT_ID'],
				'QA_TEST_TYPE' => $qaConfig['TEST_TYPE_ID'],
				'QA_USER_ID' => $qaConfig['USER_ID'],
				'QA_TEST_COMMENCE' => date('Y-m-d H:i:s')
			));

			$qaConfig['TEST_UNIT_ID'] = $this -> qa_testunits -> id;
			$this->Session->write('QA', $qaConfig);

		}
	}

	private function QA_LoadAvailabilityTestData(){

		$ew  = "";

		if ($this->request->is('put') || $this->request->is('post')) {

			$qaconfig = $this->Session->read('QA');
			//print_r(sys_get_temp_dir());

			$file = $_FILES['SignUpCSVToUpload']['tmp_name'];


			$firstFile = "";
			if (empty($file)){
				$firstFile = "empty";
			}


			$separator = ",";
			$length = 0;
			$inputFile = fopen($file, "r");

			// Get the headers of the file
			$headers = fgetcsv($inputFile);

			// Create a new dom document with pretty formatting
			$doc  = new DomDocument();
			$doc->formatOutput   = true;

			// Add a root node to the document
			$root = $doc->createElement('rows');
			$root = $doc->appendChild($root);

			// Loop through each row creating a <row> node with the correct data

			while (($row = fgetcsv($inputFile)) !== FALSE)
			{
				$container = $doc->createElement('row');

				foreach ($headers as $i => $header)
				{
					$child = $doc->createElement($header);
					$child = $container->appendChild($child);
					$value = $doc->createTextNode($row[$i]);
					$value = $child->appendChild($value);
				}

				$root->appendChild($container);
			}

			$this -> qa_testunits -> create();
			$this -> qa_testunits -> save(array(
				'QA_TEST_SOURCE' => $doc ->saveXML(),
				'QA_PROJECT_ID' => $qaconfig['PROJECT_ID'],
				'QA_TEST_TYPE' => $qaconfig['TEST_TYPE_ID'],
				'QA_USER_ID' => $qaconfig['USER_ID'],
				'QA_TEST_COMMENCE' => date('Y-m-d H:i:s')
			));
			$qaconfig = $this->Session->read('QA');

			$qaconfig['TEST_UNIT_ID'] = $this -> qa_testunits -> id;
			$this->Session->write('QA', $qaconfig);

		}



	}

	private function array_to_xml( $data, &$xml_data, &$xml_doc ) {
		foreach( $data as $key => $value ) {
			if( is_array($value) ) {
				if( is_numeric($key) ){
					$key = 'item'.$key; //dealing with <0/>..<n/> issues
				}
				$element = $xml_doc ->createElement($key, $value);
				$node = $xml_data -> getElementsByTagName('data') -> item(0);
				$node -> appendChild($element);
				$this -> array_to_xml($value, $element, $xml_doc);
			} else {
				$element = $xml_doc ->createElement($key, $value);
				$node = $xml_data -> getElementsByTagName('data') -> item(0);
				$node ->appendChild($element);
			}
		 }

		 return $xml_doc;
	}

	private function getTestSource(){
		$qa = $this->Session->read('QA');
		$unit_id = (int)$qa['TEST_UNIT_ID'];

		$this-> qa_testunits -> create();
		$QA_TEST_UNIT = $this->qa_testunits->find('first', array(
			'conditions' => array('QA_TEST_UNIT_ID' => $unit_id),
			'fields' => array('QA_TEST_SOURCE')
		));

		$local_qa_config = json_encode($qa);
		$simpleXml = simplexml_load_string($QA_TEST_UNIT['qa_testunits']['QA_TEST_SOURCE']);
		$ew = json_encode($simpleXml);

		return array('local_qa_config' => $local_qa_config, 'ew'=> $ew);


	}

	public function QA_Testdata_Register(){

		$testData = $this -> getTestSource();

		$ew = $testData['ew'];
		$local_qa_config = $testData['local_qa_config'];

		$this -> set(compact('ew', 'local_qa_config'));
	}

	public function QA_Submit() {
		if ($this->request->is('post') || $this->request->is('put')) {

			$data = json_decode(file_get_contents("php://input"), true);

			$xml_data = new DOMDocument();
			$xml_data->loadXML('<data></data>');


			$doc = new DOMDocument();

			$xml_string = '';


			if (($data['type'] == 'compare') || ($data['type'] == 'fieldvalidation')){
				$config = HTMLPurifier_Config::createDefault();
				$purifier = new HTMLPurifier($config);
				$clean_html = $purifier->purify($data['compare']['outcome']);

				$doc->loadXml('<div id="purified">'.$clean_html.'</div>');
				$xml_string = $doc ->saveXML();
			}

			if ($data['type'] == 'signup'){

				$node = $xml_data -> getElementsByTagName('data') -> item(0);
				$node -> setAttribute('comapre_image_id', $data['compare']['snapshot']['comapre_image_id']);
				$node -> setAttribute('signup_concession_image_id', $data['compare']['snapshot']['signup_concession_image_id']);
				$node -> setAttribute('signup_image_id', $data['compare']['snapshot']['signup_image_id']);
				$node -> setAttribute('signup_planOptions_image_id', $data['compare']['snapshot']['signup_planOptions_image_id']);

				$xml_data = $this -> array_to_xml($data['compare']['outcome'],$xml_data, $xml_data);
				$xml_string = $xml_data ->saveXML();
			}


			if (($data['type'] == 'snapshot')||($data['type'] == 'velocify')){
				$node = $xml_data -> getElementsByTagName('data') -> item(0);
				$node -> setAttribute('comapre_image_id', $data['compare']['snapshot']['comapre_image_id']);
				$node -> setAttribute('signup_concession_image_id', $data['compare']['snapshot']['signup_concession_image_id']);
				$node -> setAttribute('signup_image_id', $data['compare']['snapshot']['signup_image_id']);
				$node -> setAttribute('signup_planOptions_image_id', $data['compare']['snapshot']['signup_planOptions_image_id']);
				$node -> setAttribute('signup_tac_image_id', $data['compare']['snapshot']['signup_tac_image_id']);

				$xml_data = $this -> array_to_xml($data['compare']['outcome'],$xml_data, $xml_data);
				$xml_string = $xml_data ->saveXML();
			}



			$this -> qa_testcases -> create();
			$this -> qa_testcases -> save(array(
				'QA_TEST_CASE_XML' => json_encode($data['compare']['qadata']),
				'QA_TEST_UNIT_ID' => (int)$data['compare']['qaconfig']['TEST_UNIT_ID'],
				'DATE_PERFORMED' => date('Y-m-d H:i:s')
			));
			$case_id = $this -> qa_testcases -> id;

			$this -> qa_testresults -> create();
			$this -> qa_testresults -> save(array(
				'QA_RESULT_XML' => $xml_string,
				'QA_TEST_CASE_ID' => (int)$case_id
			));
			$result_id = $this -> qa_testresults -> id;


			$responseKey = array('CASE_ID' => $case_id, 'RESULT_ID' => $result_id);



			//$xpath = new DOMXPath($doc);
			//$anchors = $xpath->evaluate("//div[@class='col-md-12 compare-box no-pd']/div[@class='bottom-box col-md-12']/a", $doc);

			// Output
			//echo $anchors -> item(0) -> getAttribute('href');


			return new CakeResponse(array(
				'body' => json_encode(array(
					'status' => 'successful',
					'QA_CASE_ID' => $case_id,
					'QA_RESULT_ID' => $result_id,
					'RESULT' => json_encode($responseKey)
				)),
				'type' => 'json',
				'status' => '200'
			));




		}
	}

	public function QA_getTestTypeID(){
		if ($this->request->is('post') || $this->request->is('put')) {
			if (!$this->Session->check('QA')){

				//error

				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'unsuccessful',
						'RESULT' => 'access denied'
					)),
					'type' => 'json',
					'status' => '200'
				));

			} else {

				$qa = $this->Session->read('QA');

				$this-> qa_testtypes -> create();
				$QA_TEST_TYPE = $this->qa_testtypes->find('first', array(
					'conditions' => array('QA_TYPE_SHORTNAME' => $_POST["TYPE_ABBREB"]),
					'fields' => array('QA_TYPES_ID')
				));


				$qa['TEST_TYPE_ID'] = $this -> qa_testtypes -> id;
				$this->Session->write('QA', $qa);

				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'successful',
						'RESULT' => json_encode(array('test_type_id' => $this -> qa_testtypes -> id))
					)),
					'type' => 'json',
					'status' => '200'
				));

			}

		}

	}

	public function QA_Invoke_FieldValidation(){
		//create unit
		if ($this->request->is('post') || $this->request->is('put')) {


			if (!$this->Session->check('QA')){

				//error

				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'unsuccessful',
						'RESULT' => 'access denied'
					)),
					'type' => 'json',
					'status' => '200'
				));

			} else {
				$qaconfig = $this->Session->read('QA');

				$data = json_decode(file_get_contents("php://input"), true);

				$xml_data = new DOMDocument();
				$xml_data->loadXML('<data>'.$data['URL'].'</data>');

				$this-> qa_testtypes -> create();
				$QA_TEST_TYPE = $this->qa_testtypes->find('first', array(
					'conditions' => array('QA_TYPE_SHORTNAME' => 'UVLD'),
					'fields' => array('QA_TYPES_ID')
				));


				$qaconfig['TEST_TYPE_ID'] = $this -> qa_testtypes -> id;


				$this -> qa_testunits -> create();
				$this -> qa_testunits -> save(array(
					'QA_TEST_SOURCE' => $xml_data ->saveXML(),
					'QA_PROJECT_ID' => $qaconfig['PROJECT_ID'],
					'QA_TEST_TYPE' => $qaconfig['TEST_TYPE_ID'],
					'QA_USER_ID' => $qaconfig['USER_ID'],
					'QA_TEST_COMMENCE' => date('Y-m-d H:i:s')
				));

				$qaconfig['TEST_UNIT_ID'] = $this -> qa_testunits -> id;
				$this->Session->write('QA', $qaconfig);

				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'successful',
						'TEST_UNIT_ID' => $this -> qa_testunits -> id
					)),
					'type' => 'json',
					'status' => '200'
				));

			}

		}


	}

	public function QA_getTestInformation(){

		if ($this->request->is('post') || $this->request->is('put')) {


			if (!$this->Session->check('QA')){

				//error

				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'unsuccessful',
						'RESULT' => 'access denied'
					)),
					'type' => 'json',
					'status' => '200'
				));

			} else {
				$qaconfig = $this->Session->read('QA');

				$data = json_decode(file_get_contents("php://input"), true);


				$this-> qa_testresults -> create();
				$QA_TEST_RESULT = $this->qa_testresults->find('first', array(
					'conditions' => array('QA_RESULT_ID' => $data['RESULT_ID']),
					'fields' => array('QA_RESULT_XML', 'QA_TEST_CASE_ID')
				));

				$this -> qa_testcases -> create();
				$QA_TEST_CASE = $this->qa_testcases->find('first', array(
					'conditions' => array('QA_TEST_CASE_ID' => (int)$QA_TEST_RESULT['qa_testresults']['QA_TEST_CASE_ID']),
					'fields' => array('QA_TEST_CASE_XML')
				));

				$response_testcase = array('id'=> $QA_TEST_RESULT['qa_testresults']['QA_TEST_CASE_ID'], 'content' => $QA_TEST_CASE);
				$response_result = array('id'=> $data['RESULT_ID'], 'content' => $QA_TEST_RESULT['qa_testresults']['QA_RESULT_XML']);


				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'successful',
						'RESULT' => array('QA_TEST_RESULT' => json_encode($response_result), 'QA_TEST_CASE' => json_encode($response_testcase))
					)),
					'type' => 'json',
					'status' => '200'
				));

			}

		}

	}

	private function SOAP_Operation($instruction = null){


		$client = new nusoap_client('https://service.leads360.com/ClientService.asmx?WSDL', 'wsdl', '', '', $this -> proxyusername, $this -> proxypassword);
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

		if ($instruction['operation'] == 'GetFullLead'){
			$param = array('username' => $this -> proxyusername, 'password' => $this -> proxypassword, 'leadId' => (int)$instruction['leadid']);
			$result = $proxy->GetLead($param);
			$executionFlg = 1;
		}

		if ($instruction['operation'] == 'GetLeadIds'){
			$param = array('username' => $this -> proxyusername, 'password' => $this -> proxypassword, 'from' => $instruction['date_from'], 'to' => $instruction['date_to']);
			$result = $proxy->GetLeads($param);
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

		if ($instruction['operation'] == 'GetStatuseID'){
			$param = array('username' => $this -> proxyusername, 'password' => $this -> proxypassword);
			$result = $proxy->GetStatuses($param);
			$executionFlg = 1;
		}

		if ($instruction['operation'] == 'ModifyLeadField'){
			$param = array('username' => $this -> proxyusername, 'password' => $this -> proxypassword, 'leadId' => (int)$instruction['leadid'], 'fieldId' => (int)$instruction['fieldid'], 'newValue' => $instruction['fieldvalue']);
			$result = $proxy->ModifyLeadField($param);
			$executionFlg = 1;
		}
		if ($instruction['operation'] == 'LeadFormTypes'){
			$param = array('username' => $this -> proxyusername, 'password' => $this -> proxypassword);
			$result = $proxy->GetLeadFormTypes($param);
			$executionFlg = 1;
		}
		if ($instruction['operation'] == 'GetFields'){
			$param = array('username' => $this -> proxyusername, 'password' => $this -> proxypassword);
			$result = $proxy->GetFields($param);
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
				if ($instruction['operation'] == 'GetLead'){
					$return_data = $proxy->responseData;
				}

				if ($instruction['operation'] == 'GetLeadIds'){
					$return_data = $proxy->responseData;
				}

				if ($instruction['operation'] == 'GetFullLead'){
					$return_data = $proxy->responseData;
				}

				if ($instruction['operation'] == 'ModifyLeadStatus'){
					$return_data = $proxy->responseData;
				}

				if (($instruction['operation'] == 'LeadFormTypes')||($instruction['operation'] == 'GetFields')){

					$doc = new DOMDocument();
					$doc->loadXml($proxy->responseData);
					$xpath = new DOMXPath($doc);
					$xpath->registerNamespace("soap","http://schemas.xmlsoap.org/soap/envelope/");
					$xpath->registerNamespace("xmlns","https://service.leads360.com");

					//$query = '//Statuses/Status[@StatusTitle="AGL/ PD Residential Movein - Warm Transfer"]';
					//$xPath_result = $xpath->query($query, $doc);

					$return_data = $doc -> saveXML();
				}


				if ($instruction['operation'] == 'GetStatuses'){

					$doc = new DOMDocument();
					$doc->loadXml($proxy->responseData);
					$xpath = new DOMXPath($doc);
					$xpath->registerNamespace("soap","http://schemas.xmlsoap.org/soap/envelope/");
					$xpath->registerNamespace("xmlns","https://service.leads360.com");

					$query = '//Statuses/Status[@StatusTitle="AGL/ PD Residential Movein - Warm Transfer"]';
					$xPath_result = $xpath->query($query, $doc);

					$return_data = '<GetLead>' . $doc -> saveXML($xPath_result -> item(0)) . '</GetLead>';
				}

				if ($instruction['operation'] == 'GetStatuseID'){
					$doc = new DOMDocument();
					$doc->loadXml($proxy->responseData);
					$xpath = new DOMXPath($doc);
					$xpath->registerNamespace("soap","http://schemas.xmlsoap.org/soap/envelope/");
					$xpath->registerNamespace("xmlns","https://service.leads360.com");

					$query = '//Statuses/Status[@StatusTitle="'. $instruction['fieldvalue'] .'"]';
					$xPath_result = $xpath->query($query, $doc);
					if (!is_null($xPath_result)) {
						$return_data = $xPath_result -> item(0) -> getAttribute('StatusId');
					}
				}
				if ($instruction['operation'] == 'ModifyLeadField'){
					$return_data = $proxy->responseData;
				}



			}
		}
		return $return_data;


	}

	public function QA_DoSOAPtoVelocify(){
		if ($this->request->is('post') || $this->request->is('put')) {


			if (!$this->Session->check('QA')){

				//error

				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'unsuccessful',
						'RESULT' => 'access denied'
					)),
					'type' => 'json',
					'status' => '200'
				));

			} else {
				$request = array(
				'operation' => $_POST['operation'],
				'leadid' => (int)$_POST['leadid'],
				'signup_status' => $_POST['signup_status'],
				'fieldId' => $_POST['fieldId'],
				'fieldvalue' => $_POST['fieldvalue'],
				'date_from' => $_POST['date_from'],
				'date_to' => $_POST['date_to']
				);

				$outcome = $this -> SOAP_Operation($request);

				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'successful',
						'outcome' => $outcome
					)),
					'type' => 'json',
					'status' => '200'
				));

			}

		}
	}

	public function QA_GetCurrentUser(){

		if ($this->request->is('post') || $this->request->is('put')) {


			if (!$this->Session->check('QA')){

				//error

				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'unsuccessful',
						'RESULT' => 'access denied'
					)),
					'type' => 'json',
					'status' => '200'
				));

			} else {
				$qa = $this->Session->read('QA');
				$this-> qa_users -> create();
				$QA_TEST_USER = $this->qa_users->find('first', array(
					'conditions' => array('QA_USER_ID' => (int)$qa['USER_ID']),
					'fields' => array('USER_FIRSTNAME', 'USER_SURNAME')
				));

				if (empty($QA_TEST_USER)){
					$flg = 1;
					$msg = "mmm....  the system can not find username you specified";
				} else {
					$msg = $QA_TEST_USER['qa_users']['USER_FIRSTNAME'].' '.$QA_TEST_USER['qa_users']['USER_SURNAME'];
				}

				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'successful',
						'outcome' => $msg
					)),
					'type' => 'json',
					'status' => '200'
				));

			}

		}




	}

	public function QA_GetVelocifyStatusList(){
		if ($this->request->is('post') || $this->request->is('put')) {


			if (!$this->Session->check('QA')){

				//error

				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'unsuccessful',
						'RESULT' => 'access denied'
					)),
					'type' => 'json',
					'status' => '200'
				));

			} else {
				$request = array(
				'operation' => 'GetStatuses',
				'leadid' => '',
				'signup_status' => '',
				'fieldId' => '',
				'fieldvalue' => ''
				);

				$outcome = $this -> SOAP_Operation($request);

				return new CakeResponse(array(
					'body' => json_encode(array(
						'status' => 'successful',
						'outcome' => $outcome
					)),
					'type' => 'json',
					'status' => '200'
				));

			}

		}
	}



	private function getElementsInSnapShotByType($typs = null){

	}

	private function getDataRevisionProfileInSnapShot($element_id = null){

	}

	private function getUserProfileById($user_id = null){

	}

	private function getOriginalSnapShotImage($lead_id){

	}

	private function getSnapShotTypeName($target_id){

	}

	private function QA_GetSnapShotImages($_leadid = null){

		if (!$this->Session->check('QA')){

			//error

			return new CakeResponse(array(
				'body' => json_encode(array(
					'status' => 'unsuccessful',
					'RESULT' => 'access denied'
				)),
				'type' => 'json',
				'status' => '200'
			));

		} else {

			$output_file_name = 'data_as_csv.csv';
			$delimiter = ',';
			$this -> temp_memory = fopen('php://memory', 'w+');

			$lead_ids = explode(",", $_leadid);

			$this -> log("lead_ids::::". json_encode($lead_ids),'debug');

			for($i = 0; $i < sizeof($lead_ids); $i++){
				$the_leadid = str_replace(" ","",$lead_ids[$i]);

			$request = array(
				'leadid' => (int)$the_leadid
			);


			$this -> ps_Webforms -> create();

			$QA_SNAPSHOTS = $this->ps_Webforms->find('all', array(
				'conditions' => array('leadid' => (int)$request['leadid']),
				'fields' => array('webform_id', 'webform_xml', 'date_created', 'target_id', 'user_id')
			));

			$response = array();

			foreach ($QA_SNAPSHOTS as $QA_SNAPSHOT => $value){

				$webform_id = (int)$value['ps_Webforms']['webform_id'];

				$this -> ps_Users -> create();

				$QA_APP_USER = $this->ps_Users->find('first', array(
					'conditions' => array('user_id' => (int)$value['ps_Webforms']['user_id']),
					'fields' => array('user_title', 'user_firstname', 'user_surname', 'date_updated', 'user_email', 'user_phone')
				));

				$this -> ps_ApplicationTarget -> create();

				$QA_APP_TARGET = $this->ps_ApplicationTarget->find('first', array(
					'conditions' => array('target_id' => (int)$value['ps_Webforms']['target_id']),
					'fields' => array('target')
				));

				$type = str_replace(" ","_",$QA_APP_TARGET['ps_ApplicationTarget']['target']);

				$response[$type] = array();
				$csv_response[$type] = array();
				$response[$type]['user'] = array(
					'title' => $QA_APP_USER['ps_Users']['user_title'],
					'firstname' => $QA_APP_USER['ps_Users']['user_firstname'],
					'surname' => $QA_APP_USER['ps_Users']['user_surname'],
					'email' => $QA_APP_USER['ps_Users']['user_email'],
					'phone' => $QA_APP_USER['ps_Users']['user_phone'],
					'date_updated' => $QA_APP_USER['ps_Users']['date_updated']
				);
				$csv_response[$type][]= array(
					'LEAD ID',
					'CATEGORY',
					'TITLE',
					'FIRST NAME',
					'LAST NAME',
					'EMAIL',
					'PHONE NUMBER',
					'DATE CREATED',
					'RETAILER',
					'PRODUCT NAME',
					'PACKAGE',
					'STATE',
					'RES_SME',
					'LABEL VALUE',
					'DATA'
				);


				$doc = new DOMDocument();
				if (!$doc->loadXml($value['ps_Webforms']['webform_xml'])){
					$this -> log("ERRROR",'debug');
				}
				$docElement = $doc->documentElement -> nodeName;
				$this -> log("nodename ::: ".$docElement,'debug');
				$xpath = new DOMXPath($doc);
				$elements = $xpath->evaluate($doc->documentElement -> nodeName.'/element', $doc);

				$plan_id_elements = $xpath->evaluate($doc->documentElement -> nodeName.'/element[@id ="planid"]', $doc);
				$plan_id_value = "";



				if ($plan_id_elements->length > 0) {
					$plan_id_data = $xpath->evaluate('data_record/data[last()]', $plan_id_elements -> item(0));
					$plan_id_value = $plan_id_data -> item(0) -> getAttribute('value');
				}
				if ($plan_id_value != ""){
					$this -> Plan -> create();

					$QA_PLAN_TYPE = $this->Plan->find('first', array(
						'conditions' => array('id' => (int)$plan_id_value),
						'fields' => array('state','retailer','res_sme','package','product_name')
					));
					$this -> plan_state = $QA_PLAN_TYPE['Plan']['state'];
					$this -> plan_retailer = $QA_PLAN_TYPE['Plan']['retailer'];
					$this -> plan_res_sme = $QA_PLAN_TYPE['Plan']['res_sme'];
					$this -> plan_package = $QA_PLAN_TYPE['Plan']['package'];
					$this -> plan_product_name = $QA_PLAN_TYPE['Plan']['product_name'];

				}

				$csv_response[$type][]= array(
					$request['leadid'],
					$type,
					$QA_APP_USER['ps_Users']['user_title'],
					$QA_APP_USER['ps_Users']['user_firstname'],
					$QA_APP_USER['ps_Users']['user_surname'],
					$QA_APP_USER['ps_Users']['user_email'],
					$QA_APP_USER['ps_Users']['user_phone'],
					$QA_APP_USER['ps_Users']['date_updated'],
					($type == 'publicSignUp')? $this -> plan_retailer:"",
					($type == 'publicSignUp')? $this -> plan_product_name:"",
					($type == 'publicSignUp')? $this -> plan_package:"",
					($type == 'publicSignUp')? $this -> plan_state:"",
					($type == 'publicSignUp')? $this -> plan_res_sme:"",
					'',
					''
				);

				$table = $doc -> createElement('table');
				$table -> setAttribute('style', 'width:170mm; table-layout:fixed');
				$tr_header = $doc -> createElement('tr');
				$th_left = $doc -> createElement('th');
				$th_right = $doc -> createElement('th');
				$tr_header -> appendChild($th_left);
				$tr_header -> appendChild($th_right);
				$table -> appendChild($tr_header);

				//$this -> log("length::::". $elements->length,'debug');


				if ($elements->length > 0) {
					foreach ($elements as $element) {
						$id = "";
						$label = "";
						$value = "";
						$user = "";
						$date = "";
						$class = "";
						$id = $element -> getAttribute('id');
						$label = $element -> getAttribute('label');
						if ($type == 'publicSignUp'){
							$class = $element -> getAttribute('class');
							if ($class != ""){
								$pieces = explode(".", $class);
								if (sizeof($pieces) > 1){
									$label = $label.'-'.$pieces[1];
								}

							}

						}
						/*



						if (&& ($type == 'publicSignUp')){
							$label = $pieces[1] . "-" . $label;
						}
						*/
						$data = $xpath->evaluate('data_record/data[last()]', $element);
						//$this -> log("data length::::". $data->length,'debug');
						if ($data ->length > 0) {



							$value = $data -> item(0) -> getAttribute('value');
							$user = $data -> item(0) -> getAttribute('user');
							$date = $data -> item(0) -> getAttribute('date');
							$lbl_txt = $label;
							if (($label!= '')||($value!= '')){
								$tr = $doc -> createElement('tr');
								$td_left = $doc -> createElement('td');
								$td_right = $doc -> createElement('td');

								$lbl_txt = ($label!= '')? $label:$element -> getAttribute('name');
								$td_left -> appendChild($doc -> createTextNode($lbl_txt));
								$td_right -> appendChild($doc -> createTextNode($value));
								$tr -> appendChild($td_left);
								$tr -> appendChild($td_right);
								$table -> appendChild($tr);
							}
							if (!(($label== '')&&($value== ''))){
								$csv_response[$type][] = array(
									'',
									'',
									'',
									'',
									'',
									'',
									'',
									'',
									'',
									'',
									'',
									'',
									'',
									$lbl_txt,
									$value
								);
							}



						}

					}
				}

				$plan_detail_elements = $xpath->evaluate($doc->documentElement -> nodeName.'/plan_info', $doc);
				if ($plan_detail_elements->length > 0) {
					foreach($plan_detail_elements -> item(0)->childNodes as $child) {
						$csv_response[$type][] = array(
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							$child -> tagName,
							$child -> nodeValue
						);

					}
				}

				$plan_rates_elements = $xpath->evaluate($doc->documentElement -> nodeName.'/rates', $doc);
				if ($plan_rates_elements->length > 0) {

					$elec_rates = $xpath->evaluate('elec',$plan_rates_elements -> item(0));
					if ($elec_rates -> length > 0){
						foreach($elec_rates as $rate){
							$rate_name = $rate -> getAttribute('type');
							foreach($rate->childNodes as $child) {
								$csv_response[$type][] = array(
									'',
									'',
									'',
									'',
									'',
									'',
									'',
									'',
									'',
									'',
									'',
									'',
									'',
									$rate_name.'_'.$child -> tagName,
									$child -> nodeValue
								);
							}
						}
					}
					$gas_rates = $xpath->evaluate('gas',$plan_rates_elements -> item(0));
					if ($gas_rates -> length > 0){
						foreach($gas_rates as $rate){
							$rate_name = $rate -> getAttribute('type');
							foreach($rate->childNodes as $child) {
								$csv_response[$type][] = array(
									'',
									'',
									'',
									'',
									'',
									'',
									'',
									'',
									'',
									'',
									'',
									'',
									'',
									$rate_name.'_'.$child -> tagName,
									$child -> nodeValue
								);
							}
						}
					}
				}

				$plan_estimatedCost_elements = $xpath->evaluate($doc->documentElement -> nodeName.'/estimated_cost', $doc);
				if ($plan_estimatedCost_elements->length > 0) {

					foreach($plan_estimatedCost_elements->item(0)->childNodes as $child) {
						$csv_response[$type][] = array(
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							$child -> tagName,
							$child -> nodeValue
						);
					}
				}

				if ($type == 'publicSignUp'){
					$this -> ps_SnapShot_Concessions -> create();
					$QA_CONCE_SNAPSHOT = $this->ps_SnapShot_Concessions->find('first', array(
							'conditions' => array('webforms_id' => (int)$webform_id),
							'fields' => array('snapshot_xml')
					));
					$this -> log(json_encode($QA_CONCE_SNAPSHOT), 'debug');
					$concession_doc = new DOMDocument();
					if (!$concession_doc->loadXml($QA_CONCE_SNAPSHOT['ps_SnapShot_Concessions']['snapshot_xml'])){
						$this -> log("ERRROR",'debug');
					}
					$concession_docElement = $concession_doc->documentElement -> nodeName;

					$concession_xpath = new DOMXPath($concession_doc);
					$concession_label = $concession_xpath->evaluate($concession_doc->documentElement -> nodeName.'/concession/label', $concession_doc);
					$concession_data = $concession_xpath->evaluate($concession_doc->documentElement -> nodeName.'/concession/data_records/data', $concession_doc);
					$concession_lbl = "";
					$concession_val = "";

					if ($concession_label->length > 0) {
						$concession_lbl = $concession_label -> item(0) -> textContent;
					}
					if ($concession_data->length > 0) {
						$concession_val = $concession_data -> item(0) -> getAttribute('value');
					}

					$csv_response[$type][] = array(
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						$concession_lbl,
						$concession_val
					);

					$this -> ps_SnapShot_PlanOptions -> create();
					$QA_PLANOPT_SNAPSHOT = $this->ps_SnapShot_PlanOptions->find('first', array(
							'conditions' => array('webforms_id' => (int)$webform_id),
							'fields' => array('snapshot_xml')
					));

					$planOption_doc = new DOMDocument();

					if (!$planOption_doc->loadXml($QA_PLANOPT_SNAPSHOT['ps_SnapShot_PlanOptions']['snapshot_xml'])){
						$this -> log("ERRROR",'debug');
					}

					$planOption_docElement = $planOption_doc->documentElement -> nodeName;

					$planOption_xpath = new DOMXPath($planOption_doc);
					$planOption_elements = $planOption_xpath->evaluate($planOption_doc->documentElement -> nodeName.'/element', $planOption_doc);

					if ($planOption_elements->length > 0) {
						$plan_option_lbl_val = "";
						$plan_option_data_val = "";

						foreach ($planOption_elements as $planOption_element) {
							$plan_option_lbls = $planOption_xpath->evaluate('label', $planOption_element);
							$plan_option_data = $planOption_xpath->evaluate('data_records/data', $planOption_element);

							$plan_option_lbl_val = $plan_option_lbls -> item(0) -> textContent;
							$plan_option_data_val = $plan_option_data -> item(0) -> getAttribute("value");

							$csv_response[$type][] = array(
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								'',
								$plan_option_lbl_val,
								$plan_option_data_val
							);


						}
					}

					$this -> ps_SnapShot_TAC -> create();
					$QA_TAC_SNAPSHOT = $this->ps_SnapShot_TAC->find('first', array(
							'conditions' => array('webforms_id' => (int)$webform_id),
							'fields' => array('snapshot_xml')
					));

					$tac_doc = new DOMDocument();

					if (!$tac_doc->loadXml($QA_TAC_SNAPSHOT['ps_SnapShot_TAC']['snapshot_xml'])){
						$this -> log("ERRROR",'debug');
					}

					$tac_docElement = $tac_doc->documentElement -> nodeName;

					$tac_xpath = new DOMXPath($tac_doc);
					$tac_data = $tac_xpath->evaluate($tac_doc->documentElement -> nodeName.'/element/data_records/data', $tac_doc);
					if ($tac_data->length > 0) {

						$sanitised_tac_data = $tac_data -> item(0) -> textContent;
						$pattern_a = '/<li[^>]*>/';
						$pattern_b = '/<\/li>/';
						$pattern_c = '/<ul>/';
						$pattern_d = '/<\/ul>/';
						$sanitised_tac_data = preg_replace($pattern_a, "", $sanitised_tac_data);
						$sanitised_tac_data = preg_replace($pattern_b, "", $sanitised_tac_data);
						$sanitised_tac_data = preg_replace($pattern_c, "", $sanitised_tac_data);
						$sanitised_tac_data = preg_replace($pattern_d, "", $sanitised_tac_data);
						$csv_response[$type][] = array(
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							"Terms and Conditions",
							$sanitised_tac_data
						);


					}



				}


				foreach ($csv_response[$type] as $line) {
					fputcsv($this -> temp_memory, $line, $delimiter);
				}

			}



			}


		}
	}

	public function snapshot(){
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->autoRender = false;
			$view = new View($this, false);
			$view->layout = 'ajax';
			$lead_id = (int)$_POST["leadid"];

			$PublicSnapshotImg = $this -> FormSnapShot -> getPublicSignUpSnapShotImage($lead_id);
			$planOptionImg = $this -> FormSnapShot -> getPublicSignUpPlanOptionsSnapShotImage($PublicSnapshotImg['id']);
			$concessionImg = $this -> FormSnapShot -> getPublicSignUpConcessionsSnapShotImage($PublicSnapshotImg['id']);
			$tacImg = $this -> FormSnapShot -> getPublicSignUpTACSnapShotImage($PublicSnapshotImg['id']);
			$this -> PublicSignUp -> createSnapShotProperties($PublicSnapshotImg['mappingTxt'], $concessionImg['mappingTxt'], $tacImg['mappingTxt'], $planOptionImg['mappingTxt']);

			$user_info = $this -> FormSnapShot -> getPublicSignUpUseerImage($this -> PublicSignUp -> snapshot_user_id);

			$time_stamp = $this -> PublicSignUp -> snapshot_timestamp;

			$ew = $this -> PublicSignUp;

			$view->set(compact('ew', 'user_info', 'time_stamp'));
			$view_output = $view->render('/Elements/snapshot');
			return new CakeResponse(array(
				'body' => json_encode(array(
					'html' => $view_output,
					'user' => json_encode($user_info)
				)),
				'type' => 'json',
				'status' => '201'
			));

		}

	}


	public function QA_PDF(){
		// create new PDF document
		$snapshot_info = $this -> QA_GetSnapShotImages($_POST["leadid"]);
		fseek($this -> temp_memory, 0);
		return new CakeResponse(array(
			'body' => json_encode(array(
				'status' => 'successful',
				'RESULT' => stream_get_contents($this -> temp_memory)
			)),
			'type' => 'json',
			'status' => '200'
		));
		//$this -> set('');
	}

	public function QA_SearchLeadsByDateRange(){
			$begin = new DateTime('02/01/2016');
			$end = new DateTime('02/02/2016');
			$begin_formatted = date_format($begin, 'Y-m-d H:i:s');
			$end_formatted = date_format($end, 'Y-m-d H:i:s');

			$this -> ps_Webforms -> create();

			$QA_SNAPSHOTS = $this->ps_Webforms->find('all', array(
				'conditions' => array(
				'AND' => array(array('date_created >' => $begin_formatted),array('date_created <' => $end_formatted))),
				'fields' => array('DISTINCT leadid')
			));

		return new CakeResponse(array(
			'body' => json_encode(array(
				'status' => 'successful',
				'RESULT' => json_encode($QA_SNAPSHOTS)
			)),
			'type' => 'json',
			'status' => '200'
		));


	}

}
?>
