<?php

class Contact extends AppModel {
		
    public $name = 'Contact';
    public $useTable = 'contacts';
	

        public $hasMany = array('ContactAddress', 'ContactFile', 'ContactPhone');
        public $hasAndBelongsToMany = array('Customer' => array(
            'className' => 'Customer',
                'joinTable' => 'contact_customers',
                'foreignKey' => 'contact_id',
                'associationForeignKey' => 'customer_id',
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