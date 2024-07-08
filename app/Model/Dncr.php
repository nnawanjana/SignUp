<?php
App::uses('AppModel', 'Model');

class Dncr extends AppModel {
    public $useTable = 'dncr';
    public $actsAs = array('Containable');
}
