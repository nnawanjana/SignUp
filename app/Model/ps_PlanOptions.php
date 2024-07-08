<?php
App::uses('AppModel', 'Model');

class ps_PlanOptions extends AppModel {
	public $useTable = 'plan_options';
	public $useDbConfig = 'publicSignups';
}