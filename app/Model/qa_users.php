<?php
App::uses('AppModel', 'Model');

class qa_users extends AppModel {
	public $useTable = 'QA_USERS';
	public $useDbConfig = 'qa_Signups';
	public $primaryKey = 'QA_USER_ID';
}