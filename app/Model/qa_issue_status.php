<?php
App::uses('AppModel', 'Model');

class qa_issue_status extends AppModel {
	public $useTable = 'QA_ISSUE_STATUS';
	public $useDbConfig = 'qa_Signups';
	public $primaryKey = 'STATUS_ID';
}