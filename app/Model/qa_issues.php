<?php
App::uses('AppModel', 'Model');

class qa_issues extends AppModel {
	public $useTable = 'QA_ISSUES';
	public $useDbConfig = 'qa_Signups';
	public $primaryKey = 'ISSUE_ID';
}