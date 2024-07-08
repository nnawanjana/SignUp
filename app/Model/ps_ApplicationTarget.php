<?php
App::uses('AppModel', 'Model');

class ps_ApplicationTarget extends AppModel {
	public $useTable = 'application_targets';
	public $useDbConfig = 'publicSignups';

}