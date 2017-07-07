<?php

class Item extends AppModel {
		
    public $name = 'Item';
    public $useTable = 'items';
	
        public $belongsTo = array(
            'Parent' => array(
                'className' => 'Item',
                'foreignKey' => 'parent_id'
            )
        );
	public $hasMany = array(
            'Children' => array(
                'className' => 'Item',
            'foreignKey' => 'parent_id'
        )
            );

}

?>