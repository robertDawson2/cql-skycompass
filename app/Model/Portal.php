<?php

class Portal extends AppModel {
		
    public $name = 'Portal';
    public $useTable = 'portal';
  //  public $actsAs = array('Containable');

        public $belongsTo = array('Customer', 'Contact');
}

?>