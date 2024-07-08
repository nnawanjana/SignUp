<?php
App::uses('AppModel', 'Model');

class ps_SnapShot_TAC extends AppModel {
	public $useTable = 'tac_snapshots';
	public $useDbConfig = 'publicSignups';
	public $primaryKey = 'tac_snapshot_id';
}