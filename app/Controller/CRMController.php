<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');
   
    
    class CRMController extends AppController {

    	
        public $uses = array('Group','Certification','Accreditation','Contact','Customer','User');
        
        
        public function admin_ajaxSiteSearch($searchString = "", $userContext = 'true',$contactContext = 'true', $customerContext = 'true', $jobContext = 'true', $referral="global")
        {
            $this->layout = 'ajax';
            $searchString = urldecode($searchString);
            // min string length of 2 to prevent crazy returns
            if(strlen($searchString)<2)
                exit('done');
            $find = array('users' => array(), 'contacts' => array(), 'customers' => array(), 'jobs' => array());
            if($userContext !== 'false')
            {
                $find['users'] = $this->User->find('all', array(
                    'recursive' => -1,
                    'conditions' => array(
                        'is_active' => 'true',
                        'OR' => array(
                            'name LIKE ' => "%" . $searchString . "%",
                            'first_name LIKE ' => "%" . $searchString . "%",
                            'AND' => array(
                                'first_name LIKE ' => "%" . trim(substr($searchString, 0, strpos($searchString,' '))) . "%",
                                'last_name LIKE ' => "%". trim(substr($searchString, strpos($searchString,' '))) . "%"
                                ),
                            'last_name LIKE ' => "%" . $searchString . "%",
                            'email LIKE ' => "%" . $searchString . "%"
                            )
                        ),
                    'fields' => array('id','first_name','last_name')
                    )
                );
            }
 else {
     unset($find['users']);
 }
            
            if($contactContext !== 'false') 
            {
                $find['contacts'] = $this->Contact->find('all', array(
                    'recursive' => -1,
                    'conditions' => array(
                        
                        'OR' => array(
                            'first_name LIKE ' => "%" . $searchString . "%",
                            'AND' => array(
                                'first_name LIKE ' => "%" . trim(substr($searchString, 0, strpos($searchString,' '))) . "%",
                                'last_name LIKE ' => "%". trim(substr($searchString, strpos($searchString,' '))) . "%"
                                ),
                            'last_name LIKE ' => "%" . $searchString . "%",
                            'email LIKE ' => "%" . $searchString . "%"
                            )
                        ),
                    'fields' => array('id','first_name','last_name')
                    )
                );
            }
            else {
     unset($find['contacts']);
 }
            
             if($customerContext !== 'false') 
            {
                $find['customers'] = $this->Customer->find('all', array(
                    'recursive' => -1,
                    'conditions' => array(
                        
                        'OR' => array(
                            'name LIKE ' => "%" . $searchString . "%",
                            
                            'company_name LIKE ' => "%" . $searchString . "%",
                            'email LIKE ' => "%" . $searchString . "%"
                            )
                        ),
                    'fields' => array('id','name')
                    )
                );
            }
            else {
     unset($find['customers']);
 }
 
 if($jobContext !== 'false') 
            {
                $find['jobs'] = $this->Job->find('all', array(
                    'recursive' => -1,
                    'conditions' => array(
                        
                        'OR' => array(
                            'name LIKE ' => "%" . $searchString . "%",
                            
                            )
                        ),
                    'fields' => array('id','name')
                    )
                );
            }
            else {
     unset($find['jobs']);
 }
            
          //  pr($find); exit();
            
            $this->set('data', $find);
            if($referral !== 'global')
            {
                $this->render('admin_ajax_todo_search','ajax');
            }
            
        }
        
        }