<?php
App::uses('AppModel', 'Model');

class ps_LeadTypeDefinition extends AppModel {
	public $useTable = 'lead_type_definition';
	public $useDbConfig = 'publicSignups';
}