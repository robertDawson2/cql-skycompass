<?php

class Classes extends AppModel {
		
    public $name = 'Classes';
    public $useTable = 'classes';
	
        
	public $hasMany = array(
            'Children' => array(
                'className' => 'Classes',
            'foreignKey' => 'parent_id'
        )
            );

}

?>