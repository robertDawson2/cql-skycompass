<?php

class Job extends AppModel {
		
    public $name = 'Job';
    public $useTable = 'jobs';
	

        public $belongsTo = array('Customer');

}

?>