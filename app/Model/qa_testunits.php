<?php
App::uses('AppModel', 'Model');

class qa_testunits extends AppModel {
	public $useTable = 'QA_TEST_UNITS';
	public $useDbConfig = 'qa_Signups';
	public $primaryKey = 'QA_TEST_UNIT_ID';
}