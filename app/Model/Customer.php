<?php

class Customer extends AppModel {
		
    public $name = 'Customer';
    public $useTable = 'customers';
	

        public $hasMany = array('Portal','Job','CustomerPhone','CustomerAddress','CustomerGroup','CustomerFile',
            'CustomerAccreditation');
        public $hasAndBelongsToMany = array('Contact' => array(
            'className' => 'Contact',
                'joinTable' => 'contact_customers',
                'foreignKey' => 'customer_id',
                'associationForeignKey' => 'contact_id',
                'unique' => true,
//                'conditions' => '',
//                'fields' => '',
//                'order' => '',
//                'limit' => '',
//                'offset' => '',
//                'finderQuery' => '',
//                'with' => ''
        ));
        
       
}

?>