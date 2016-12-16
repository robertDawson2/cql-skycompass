<?php

class Job extends AppModel {
		
    public $name = 'Job';
    public $useTable = 'jobs';
	

        public $belongsTo = array('Customer', 'ServiceArea');
        
        public $hasOne = array('JobTaskList');
        public $hasMany = array('ScheduleEntry');

}

?>