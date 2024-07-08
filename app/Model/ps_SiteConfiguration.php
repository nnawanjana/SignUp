<?php
App::uses('AppModel', 'Model');

class ps_SiteConfiguration extends AppModel {
	public $useTable = 'site_configurations';
	public $useDbConfig = 'publicSignups';
	public $primaryKey = 'config_id';
}
