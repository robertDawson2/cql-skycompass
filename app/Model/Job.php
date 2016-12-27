<?php

class Job extends AppModel {
		
    public $name = 'Job';
    public $useTable = 'jobs';
	

        public $belongsTo = array('Customer', 'ServiceArea');
        

        public $hasMany = array('ScheduleEntry' => array(
            'conditions' => array('OR' => array('approved' => '1', 'approved is null'))
        ), 'JobTaskList');

}

?>