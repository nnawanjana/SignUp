<?php
App::uses('AppModel', 'Model');

class qa_verfication_outcomes extends AppModel {
	public $useTable = 'VERIFICATION_OUTCOMES';
	public $useDbConfig = 'qa_Signups';
	public $primaryKey = 'VERIFICATION_OUTCOME_ID';
}