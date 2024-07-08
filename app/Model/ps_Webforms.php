<?php
App::uses('AppModel', 'Model');

class ps_Webforms extends AppModel {
	public $useTable = 'webforms';
	public $useDbConfig = 'publicSignups';
	public $primaryKey = 'webform_id';
}