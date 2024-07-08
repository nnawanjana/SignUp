<?php
App::uses('AppModel', 'Model');

class qa_velocifypost extends AppModel {
	public $useTable = 'QA_VELOCIFYPOST';
	public $useDbConfig = 'qa_Signups';
	public $primaryKey = 'QA_VELOCIFYPOST_ID';
}
