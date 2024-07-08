<?php
App::uses('AppModel', 'Model');

class ps_PreSignUpText extends AppModel {
	public $useTable = 'pre_signup_text';
	public $useDbConfig = 'publicSignups';
}