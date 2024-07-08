<?php
App::uses('AppModel', 'Model');

class ps_TAC extends AppModel {
	public $useTable = 'tac_rules_templates';
	public $useDbConfig = 'publicSignups';
}