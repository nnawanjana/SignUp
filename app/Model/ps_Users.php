<?php
App::uses('AppModel', 'Model');

class ps_Users extends AppModel {
	public $useTable = 'users';
	public $useDbConfig = 'publicSignups';
	public $primaryKey = 'user_id';
}
