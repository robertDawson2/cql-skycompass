<?php

	class User extends AppModel {
		
		public $name = 'User';
                public $belongsTo = array('Vendor');
                public $actsAs = array('Containable');
                public $hasMany = array('ScheduleEntry', 'TimeEntry', 'Notification',
                    'ApprovalManager' => array(
                        'className' => 'ApprovalManager',
                        'foreignKey' => 'user_id'
                    ));
                
                public $hasAndBelongsToMany = array(
                    'Ability' => array(
                        'className' => 'Ability',
                        'joinTable' => 'user_abilities',
                        'foreignKey' => 'user_id',
                        'associationForeignKey' => 'ability_id',
                        'unique' => true
                    ),
                    'Chat' => array(
                    'className' => 'Chat',
                    'joinTable' => 'chats_users',
                    'foreignKey' => 'user_id',
                    'associationForeignKey' => 'chat_id',
                    'unique' => true
                ));
                
		public $validate = array(
			'first_name' => array(
				'rule' => 'notEmpty',
				'message' => 'This field is required'
			),
			'last_name' => array(
				'rule' => 'notEmpty',
				'message' => 'This field is required'
			),
			'email' => array(
				'email' => array(
					'rule'    => array('email', true),
					'message' => 'Please enter a valid email address',
					'required' => true
				),
				'account_check' => array(
					'rule' => 'noExistingAccount',
					'message' => 'There is already an account on file with this email address'
				)
			),
			'password' => array(
                            'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field is required',
				'on' => 'create'
                                ),
                            'minLength' => array(
                                'rule' => array('minLength', '8'),
                                'message' => 'Password must be at least 8 characters long.'
                            )
                            ),
			'password_confirmation' => array(
				'rule' => array('matchesField', 'password'),
				'message' => 'The passwords you entered do not match',
				'on' => 'create'
			)
		);
		
		function matchesField($field = array(), $comparisonField = null) {
			foreach ($field as $key => $value) {
				if ($value !== $this->data[$this->name][$comparisonField])
					return false;
			} 
			return true;
		}
		
		function noExistingAccount($field = array()) {
			if (isset($this->data['User']['id']))
				$id = $this->data['User']['id'];
			else
				$id = 0;
				
			$client = $this->find('first', array('conditions' => array('User.email' => trim($field['email']), 'User.id <>' => $id)));
			return empty($client);
		}
                
             
	}

?>