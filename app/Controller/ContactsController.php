<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    App::uses('AppController', 'Controller');
   
    
    class ContactsController extends AppController {

    	public $name = 'Contact';
        
        function beforeFilter() {
		parent::beforeFilter();
		$this->set('section', 'contact');
                $this->Auth->allow('passwordReset', 'firstLogin', 'forgotPassword');
	}
        
        function admin_add() {
            $this->loadModel('Customer');
            $this->set('customers', $this->Customer->find('list', array('fields'=> array('Customer.id', 'Customer.name'))));
        }
    }
    
    
?>
