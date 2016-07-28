<?php

class TimeEntry extends AppModel {
		
    public $name = 'TimeEntry';
    public $useTable = 'time_entries';
	

        public $belongsTo = array('User', 'Customer', 'Item');

        
        
}

?>