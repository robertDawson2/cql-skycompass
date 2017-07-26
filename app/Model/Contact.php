<?php

class Contact extends AppModel {
		
    public $name = 'Contact';
    public $useTable = 'contacts';
  //  public $actsAs = array('Containable');

        public $hasMany = array('ContactAddress', 'ContactFile', 'ContactPhone','ContactGroup','ContactCertification');
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