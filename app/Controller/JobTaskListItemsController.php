<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
App::uses('AppController', 'Controller');

class JobTaskListItemsController extends AppController {
    
    
    function admin_ajaxChangeItemStatus($id, $value) {
        $this->layout = 'ajax';
        if($value== "0")
        {
            $this->JobTaskListItem->id = $id;
            $this->JobTaskListItem->saveField('completed', null);
           
        }
        else
        {
             $this->JobTaskListItem->id = $id;
            $this->JobTaskListItem->saveField('completed', date('Y-m-d H:i:s'));
        }
        exit(0);
    }
}
