<?php
App::uses('AppModel', 'Model');

class BlueNrgRate extends AppModel {
	public $useTable = 'blue_nrg_rates';
	public $actsAs = array('Containable');
}