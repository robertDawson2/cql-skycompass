<?php

class Manager extends AppModel {
    public $useTable = 'users';
    
    public $hasAndBelongsToMany = array(
        'User' => array(
            'className' => 'User',
            'joinTable' => 'approval_managers',
            'foreignKey' => 'id',
            'associationForeignKey' => 'user_id',
            //'conditions' => array('Administrator.web_user_type' => 'admin')
        )
    );
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>