<?php

class ApprovalManager extends AppModel {
    public $useTable = 'approval_managers';
    
    public $hasOne = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'manager_id'
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