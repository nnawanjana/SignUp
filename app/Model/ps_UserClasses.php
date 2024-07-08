<?php
App::uses('AppModel', 'Model');

class ps_UserClasses extends AppModel {
	public $useTable = 'userClasses';
	public $useDbConfig = 'publicSignups';
}