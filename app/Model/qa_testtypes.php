<?php
App::uses('AppModel', 'Model');

class qa_testtypes extends AppModel {
	public $useTable = 'QA_TEST_TYPES';
	public $useDbConfig = 'qa_Signups';
	public $primaryKey = 'QA_TYPES_ID';
}