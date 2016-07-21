<?php

class Config extends AppModel {
		
    public $name = 'Config';
    public $useTable = 'config';
    
    public function saveValue($option, $value) {
    	$option = $this->find('first', array('conditions' => array('Config.option' => $option)));
    	$this->id = $option['Config']['id'];
    	if ($this->saveField('value', $value))
    		return true;
    	else
    		return false;
    }

}

?>