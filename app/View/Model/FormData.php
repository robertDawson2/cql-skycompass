<?php

class FormData extends AppModel {
		
    public $name = 'FormData';
    public $useTable = false;

	public $validate = array(
		'first_name' => array(
           'rule' => 'notEmpty',
           'message' => 'This field is required'
        ),
        'last_name' => array(
           'rule' => 'notEmpty',
           'message' => 'This field is required'
        ),
		'phone_number' => array(
			'rule' => 'phone',
			'message' => 'Please enter a valid phone number',
			'required' => true
        ),
		'email' => array(
			'rule'    => array('email', true),
			'message' => 'Please enter a valid email address',
			'required' => true
		)
	);

}

?>