<?php
App::uses('AppController', 'Controller');

class HomeController extends AppController {

	public $uses = array('Customer', 'Submission', 'Plan', 'ElectricityRate', 'GasRate', 'Tariff', 'SolarRebateScheme', 'Product', 'Pdf', 'LeadType', 'Sale');

	public function beforeFilter() {
		
		parent::beforeFilter();

		if (!in_array($this->request->clientIp(), unserialize(STAFF_IPS))) {
			$this->redirect( 'http://' . WEBSITE_MAIN_DOMAIN_NAME );
		}
		
		$this->Auth->allow();
		
		$this->layout = 'default';
	}
	
	public function index() {
    	
	}
}