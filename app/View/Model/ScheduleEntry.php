<?php

Class ScheduleEntry extends AppModel {
    public $belongsTo = array(
        'Job', 'User'
        );
    public $hasOne = array('JobTaskList');
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

