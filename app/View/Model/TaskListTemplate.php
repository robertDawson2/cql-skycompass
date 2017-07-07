<?php

Class TaskListTemplate extends AppModel {
    public $hasAndBelongsToMany = array(
        'TaskItem' => array(
            'className' => 'TaskItem',
            'joinTable' => 'task_list_template_items',
            'foreign_key' => 'task_list_template_id',
            'associationForeignKey' => 'task_item_id'
        )
    );
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

