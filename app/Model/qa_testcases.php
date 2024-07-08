<?php
App::uses('AppModel', 'Model');

class qa_testcases extends AppModel {
	public $useTable = 'QA_TEST_CASES';
	public $useDbConfig = 'qa_Signups';
	public $primaryKey = 'QA_TEST_CASE_ID';
}