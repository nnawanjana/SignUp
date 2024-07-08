<?php
App::uses('AppModel', 'Model');

class LeadHistory extends AppModel {
	public $useTable = 'lead_history';
	public $actsAs = array('Containable');
}