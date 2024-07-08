<?php
App::uses('AppModel', 'Model');

class qa_issue_response_logs extends AppModel {
	public $useTable = 'QA_ISSUE_RESPONSE_LOGS';
	public $useDbConfig = 'qa_Signups';
	public $primaryKey = 'RESPONSE_LOG_ID';
}