<?php
App::uses('AppModel', 'Model');

class ps_SnapShot_PlanOptions extends AppModel {
	public $useTable = 'planOptions_snapshot';
	public $useDbConfig = 'publicSignups';
	public $primaryKey = 'planOption_snapshot_id';
}