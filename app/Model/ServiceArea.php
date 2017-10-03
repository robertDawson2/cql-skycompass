<?php

Class ServiceArea extends AppModel {
    
    var $belongsTo = array(
        'Parent' => array(
            'className' => 'ServiceArea',
            'foreignKey' => 'parent_id'
        )
    );
    
    var $hasMany = array(
        'Children' => array(
            'className' => 'ServiceArea',
            'foreignKey' => 'parent_id'
        )
    );
    
   
   
}