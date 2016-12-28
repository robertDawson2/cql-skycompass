<?php

Class JobTaskList extends AppModel {
    public $hasMany = array(
        'JobTaskListItem' => array(
            'order' => 'sort_order ASC'
        )
        );
  //  public $belongsTo = array('ScheduleEntry');
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

