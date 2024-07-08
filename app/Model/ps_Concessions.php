<?php
App::uses('AppModel', 'Model');

class ps_Concessions extends AppModel {
	public $useTable = 'concession_rules_templates';
	public $useDbConfig = 'publicSignups';
}