<?php
App::uses('AppModel', 'Model');

class qa_testresults extends AppModel {
	public $useTable = 'QA_TEST_RESULTS';
	public $useDbConfig = 'qa_Signups';
	public $primaryKey = 'QA_RESULT_ID';
}