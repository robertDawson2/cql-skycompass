<?php

Class TaskItem extends AppModel {
//    public $hasAndBelongsToMany = array(
//        'TaskListTemplate' => array(
//            'className' => 'TaskListTemplate',
//            'joinTable' => 'task_list_template_items',
//            'foreign_key' => 'task_item_id',
//            'associationForeignKey' => 'task_list_template_id'
//        )
//    );
    public $belongsTo = array('ActivateEvent' => array(
        'className' => 'TaskListAction',
        'foreignKey' => 'activate_event'
        
    ),
        'CompleteEvent' => array(
        'className' => 'TaskListAction',
            'foreignKey' => 'complete_event'
        
    ));
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

