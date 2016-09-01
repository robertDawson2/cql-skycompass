<?php

class BillItem extends AppModel {
		
    public $name = 'BillItem';
    public $useTable = 'bill_items';
	
    
    public $belongsTo = array('Customer','Classes' => array(
        'foreignKey' => 'class_id'
    ),'Item', 'Vendor', 'Bill');



}

?>