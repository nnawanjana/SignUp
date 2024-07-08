<?php
App::uses('AppModel', 'Model');

class ps_SnapShot_Concessions extends AppModel {
	public $useTable = 'concession_snapshot';
	public $useDbConfig = 'publicSignups';
	public $primaryKey = 'concessions_snapshot_id';
}