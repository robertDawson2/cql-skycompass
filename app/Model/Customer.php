<?php

class Customer extends AppModel {
		
    public $name = 'Customer';
    public $useTable = 'customers';
	

        public $hasMany = array('Jobs');

}

?>