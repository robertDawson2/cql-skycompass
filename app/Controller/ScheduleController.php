<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    App::uses('AppController', 'Controller');
   
    
    class ScheduleController extends AppController {

    	public $name = 'Schedule';
        public $uses = array('User', 'ScheduleEntry');
        
        
        
        public function beforeRender() {
            parent::beforeRender();
            $this->set('section', 'scheduling');
        }
        
        public function admin_requestOff()
        {
            if(!empty($this->request->data))
            {
                
                $this->request->data['Schedule'] = $this->request->data['User'];
                
                $employeeId = $this->Auth->user('id');
                $error = false;
                // first, check data for valid data types
                if(!$this->_isValidDate($this->request->data['Schedule']['start_date']) || !$this->_isValidDate($this->request->data['Schedule']['end_date']))
                {
                    $error = true;
                    $this->Session->setFlash('You have entered an invalid date. Please check the format of your date and try again.', 'flash_error');
                }
                else
                {
                    $this->request->data['Schedule']['start_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['Schedule']['start_date']));
                    $this->request->data['Schedule']['end_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['Schedule']['end_date']));
                }
                
                // second, check if employee already scheduled && schedule item is not denied by employee within time period
                $schedule = $this->ScheduleEntry->find('all', array('conditions' => array(
                    'NOT' => array(
                        'OR' => array(
                        'ScheduleEntry.start_date >=' => $this->request->data['Schedule']['end_date'],
                        'ScheduleEntry.end_date <=' => $this->request->data['Schedule']['start_date']
                        )
                    ),
                    'ScheduleEntry.type' => 'scheduling',
                    'OR' => array(
                        'ScheduleEntry.approved is null',
                        'ScheduleEntry.approved = 1'
                    )
                    
            
                    
                )));
               
                if(!empty($schedule))
                {
                    // this means an overlap was found. oops!
                    pr($schedule);
                    $error = true;
                    $this->Session->setFlash('You have already been scheduled for at least 1 job within the date range given. Please deny the scheduling event first, or contact an administrator for further details.', 'flash_error');
                }
                
                // third, make sure request has not already been sent and denied
                $schedule = $this->ScheduleEntry->find('all', array('conditions' => array(
                    'NOT' => array(
                        'ScheduleEntry.type' => 'scheduling'
                        
                    ),
                    'ScheduleEntry.start_date' => $this->request->data['Schedule']['start_date'],
                        'ScheduleEntry.end_date' => $this->request->data['Schedule']['end_date']
                    
                )));
                if(!empty($schedule))
                {
                    // this means an overlap was found. oops!
                    $error = true;
                    $this->Session->setFlash('You have already sent a request for this date range. Please try again.', 'flash_error');
                }
                
                // if all ok, you may submit the request to schedulers for approval or denial.
                if(!$error){
                $this->ScheduleEntry->create();
                $this->request->data['ScheduleEntry'] = $this->request->data['Schedule'];
                $this->request->data['ScheduleEntry']['user_id'] = $employeeId;
                unset($this->request->data['Schedule']);
              
                if($this->ScheduleEntry->save($this->request->data))
                {
                    unset($this->request->data['User']);
                    foreach(explode("|", $this->config['schedulerIds']) as $scheduler)
                        $this->Notification->queueNotification($scheduler, 'Admin_RequestOff', '/admin/schedule/approveTimeOff', 'Requested Time Off', '%i new requests for time off need approval');
                    
                    $this->Session->setFlash('Your request has been submitted for approval!', 'flash_success');
                }
                else
                    $this->Session->setFlash('An error occurred when submitting your request. Please try again. If this error persists, contact an administrator.', 'flash_error');
                }
                    
            }
        }
        
        function admin_mySchedule()
        {
             $this->set('setColors', [
        "training" => 'pink',
        "certification"=> 'lightblue',
        "accreditation"=> 'lightgreen',
        "other" => 'lightgray',
                 "timeoff" => 'tan',
                 'unapproved' => 'red'
    ]);
             $this->User->unbindModel(array('hasMany' => array(
                 'Notification', 'ScheduleEntry', 'TimeEntry'
             )));
             $schedule =  $this->ScheduleEntry->find('all', array(
                 'recursive' => 3,
                 'conditions' => array('ScheduleEntry.user_id' => $this->Auth->user('id'))));
            
             $this->set('schedule', $schedule);
             
        }
        
        function admin_approveTimeOff()
        {
            $denialNotice = array();
            if(!empty($this->request->data))
            {
                
                $error = false;
                $approve = null;
                if($this->request->data['ScheduleEntry']['approveDeny'] === 'approve')
                    $approve = 1;
                else
                    $approve = 0;
                foreach($this->request->data['ScheduleEntry'] as $i => $d)
                {
                    if(isset($d['approved']) && $d['approved'] == 'on')
                    {
                    $entry = array('ScheduleEntry' => array(
                        'id' => $i,
                        'approved' => $approve,
                        'denial_message' => $d['denial_message']
                    ));
                    
                    
                    
                    if(!$this->ScheduleEntry->saveMany($entry))
                    {
                        $error = true;
                    }
                    else
                    {
                        // Only need this if we were uploading always - only generating bills on pay switch
//                        if($approve)
//                            $this->_queueToSave($i);
                        
                        $user = $this->ScheduleEntry->findById($i);
                        
                        
                        if($approve == 1)
                            $this->Notification->queueNotification($user['User']['id'],'RequestApprove','/admin/schedule/mySchedule','Request Approved','%i requests for time off were approved');
                        else
                        {
                            $this->Notification->queueNotification($user['User']['id'],'RequestDeny','/admin/schedule/mySchedule','Request Denied','%i requests for time off were denied');
                            
                            // queue denial notice email for sending
                            if(!empty($d['denial_message']))
                            {
                                $denialNotice[$user['User']['id']][] = array('notice' => $d["denial_message"],
                                    'type' => $user['ScheduleEntry']['type'],
                                    'user_email' => $user['User']['email'],
                                    'date' => $user['ScheduleEntry']['start_date']);
                                
                            }
                        }
                    }
                }
                
                    
            }
            
           
                            
            if(!$error)
            {
                if($approve == 1)
                            $this->Session->setFlash('All selected items set for approval','flash_success');
                        else
                            $this->Session->setFlash('All selected items set for denial','flash_success');
                    }
                    else
                    {
                        $this->Session->setFlash('An error occurred while saving your request.', 'flash_error');
                    }
            
            
            //        $this->_sendDenialNotices($denialNotice);
                    
            }
            
            $entries = $this->ScheduleEntry->find('all', array('conditions' => array(
                'NOT' => array(
                    'ScheduleEntry.type' => 'scheduling'
                ),
                'ScheduleEntry.approved' => null
            )));
            $this->set('entries',$entries);
            
            $approved = $this->ScheduleEntry->find('all', array('conditions' => array(
                'NOT' => array(
                    'ScheduleEntry.type' => 'scheduling',
                    'ScheduleEntry.approved' => null
                )
            )));
            $this->set('approved',$approved);
        }
        
       private function _isValidDate($date) 
       {
           return (bool) strtotime($date);
       }
       
       private function _sendDenialNotices($denials = null)
        {
            if(isset($denials) && !empty($denials))
            {
                
                foreach($denials as $d)
                {
                   
                     App::uses('CakeEmail', 'Network/Email');
                            $to = array($d[0]['user_email']);
                            
                            $email = new CakeEmail('smtp');
                            $email->template('denial', 'default')
                            ->emailFormat('both')
                            ->subject($this->config['site.name'] . ' Expense Denial Notice')
                            ->viewVars(array('full' => $d,'config' => $this->config, 'description' => 'Expense Denial Notice'))
                            ->to($to)
                            ->send();
                }
                return true;
            }
            
            return false;
        }
    }
    
    
?>
