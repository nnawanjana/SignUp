<?php
App::uses('AppModel', 'Model');

class ps_LeadTypes extends AppModel {
	public $useTable = 'lead_types';
	public $useDbConfig = 'publicSignups';
}