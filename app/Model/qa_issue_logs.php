<?php
App::uses('AppModel', 'Model');

class qa_issue_logs extends AppModel {
	public $useTable = 'QA_ISSUE_LOGS';
	public $useDbConfig = 'qa_Signups';
	public $primaryKey = 'ISSUE_LOG_ID';
}