<?php

class Vendor extends AppModel {
		
    public $name = 'Vendor';
    public $useTable = 'vendors';
	
    public $hasMany = array('Bill');
    public $hasOne = array('User');


}

?>