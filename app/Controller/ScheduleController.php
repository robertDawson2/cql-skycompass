<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    App::uses('AppController', 'Controller');
   
    
    class ScheduleController extends AppController {

    	public $name = 'Schedule';
        public $uses = array('User', 'ScheduleEntry', 'ServiceArea');
        
        
        public function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow('mySchedule', 'autoApprove');
        }
        
        public function admin_addServiceArea()
        {
            if(!empty($this->request->data))
            {
                $this->ServiceArea->create();
                $this->ServiceArea->save($this->request->data);
                $this->Session->setFlash("New service area saved!", 'flash_success');
                $this->redirect("/admin/schedule/viewServiceAreas");
            }
            
            
        }
        public function admin_removeServiceArea($id)
        {
            $this->ServiceArea->delete($id);
            $this->Session->setFlash("Service area has been successfully removed", 'flash_success');
            $this->redirect("/admin/schedule/viewServiceAreas");
        }
        public function admin_viewServiceAreas()
        {
            $this->set('serviceAreas', $this->ServiceArea->find('threaded'));
            $possibleParents = $this->ServiceArea->find('list', array('conditions' => array(
                'parent_id is NULL'
            ),
                'fields' => array('id','name')));
            $this->set('possibleParents', $possibleParents);
        }
        
        public function beforeRender() {
            parent::beforeRender();
            $this->set('section', 'scheduling');
        }
        
        public function mySchedule($uniqueId)
        {
            $this->layout = 'ajax';
           $schedule = $this->ScheduleEntry->find('all', array(
                'conditions' => array(
                    'ScheduleEntry.user_id' => $uniqueId,
                    'ScheduleEntry.approved' => "1"
                    
                ),
                'recursive' => 2
            ));
           
           $ical = "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN";
           foreach($schedule as $entry):
$ical.= "\nBEGIN:VEVENT
UID:" . $entry['ScheduleEntry']['id'] . "
DTSTAMP:" . gmdate('Ymd').'T'. gmdate('His') . "Z
DTSTART:" . gmdate('Ymd', strtotime($entry['ScheduleEntry']['start_date'])).'T'. 
                   gmdate('His', strtotime($entry['ScheduleEntry']['start_date'])) . "Z
DTEND:" . gmdate('Ymd', strtotime($entry['ScheduleEntry']['end_date'] . " +23 hours")).'T'. 
                   gmdate('His', strtotime($entry['ScheduleEntry']['end_date'] . " +23 hours")) . "Z\n";
           
if($entry['ScheduleEntry']['type'] == "scheduling"):
$ical .= "LOCATION:" . preg_replace('/([\,;])/','\\\$1', ($entry['Job']['Customer']['bill_addr2'] . ", " . $entry['Job']['Customer']['bill_addr2'] . ", " . $entry['Job']['Customer']['bill_city'] .
                   ", " . $entry['Job']['Customer']['bill_state'] . " " . $entry['Job']['Customer']['bill_zip'])) . "
DESCRIPTION:Service Area: " . $entry['Job']['ServiceArea']['name'] . ", Customer: " . $entry['Job']['Customer']['full_name'] . "
SUMMARY:" . $entry['Job']['name'] . " - " .  " (" .
                   ucwords(str_replace("_", " ", $entry['ScheduleEntry']['position'])) . ")";
else:
    $ical .= "SUMMARY:Time Off (" . ucwords(str_replace("_", " ", $entry['ScheduleEntry']['type'])) . ")";
endif;
$ical .= "\nEND:VEVENT";
               endforeach;
$ical .= "\n"
        . "END:VCALENDAR";

//set correct content-type-header
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename=mySchedule.ics');
echo $ical;
exit;
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
                    'ScheduleEntry.user_id' => $employeeId,
                    'ScheduleEntry.type' => 'scheduling',
                    'OR' => array(
                        'ScheduleEntry.approved is null',
                        'ScheduleEntry.approved = 1'
                    )
                    
            
                    
                )));
               
                if(!empty($schedule))
                {
                    
                    // this means an overlap was found. oops!
                   // pr($schedule);
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
        function admin_cancelTORequest($id)
        {
        $this->ScheduleEntry->delete($id);
        $this->Session->setFlash('Your request has been removed.', 'flash_success');
        $this->redirect($this->referer('/admin/schedule/mySchedule'));
        
        }
        
        function admin_mySchedule($full = "")
        {
             $this->set('setColors', [
        "training" => 'pink',
        "certification"=> 'lightblue',
        "accreditation"=> 'lightgreen',
        "other" => 'lightgray',
                 "timeoff" => 'tan',
                 'unapproved' => 'red',
                 'pending' => 'black',
                 'employees' => 'darkblue'
    ]);
             $this->User->unbindModel(array('hasMany' => array(
                 'Notification', 'ScheduleEntry', 'TimeEntry'
             )));
             $schedule =  $this->ScheduleEntry->find('all', array(
                 'recursive' => 3,
                 'conditions' => array('ScheduleEntry.user_id' => $this->Auth->user('id'))));
            
             $this->set('schedule', $schedule);
             
             if(!empty($full)) {
             $schedule =  $this->ScheduleEntry->find('all', array(
                 'recursive' => 3,
                 'conditions' => array(
                     'ScheduleEntry.start_date >=' => date('Y-m-d H:i:s', strtotime('-1 month')),
                     'ScheduleEntry.type' => 'scheduling',
                 //   'ScheduleEntry.approved' => '1',
                     'NOT' => array(
                     'ScheduleEntry.user_id' => $this->Auth->user('id'))
                     )));
             
             $this->set('fullSchedule', $schedule);
             $full = true;
             }
             else
                 $full = false;
             
             $this->set('full', $full);
             
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
        
            function admin_approveMySchedule()
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
                        'email_alert_delivered' => 1,
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
                        {
                            foreach(explode("|", $this->config['schedulerIds']) as $scheduler)
                                $this->Notification->queueNotification($scheduler,'RequestApprove','/admin/jobs/scheduler','Request Approved','%i schedule entries were approved');
                        }
                        else
                        {
                            foreach(explode("|", $this->config['schedulerIds']) as $scheduler)
                                $this->Notification->queueNotification($scheduler,'RequestDeny','/admin/scheduler','Request Denied',$user['User']['first_name'] . " " . $user['User']['last_name'] . " denied entry for " . $user['Job']['full_name'] . "(" . $d['denial_message'] . ")", 0);
                            
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
                    'ScheduleEntry.user_id' => $this->Auth->user('id'),
                    'ScheduleEntry.type' => 'scheduling'
                ,
                'ScheduleEntry.approved' => null
            ), 'recursive' => 2));
            $this->set('entries',$entries);
            
            $approved = $this->ScheduleEntry->find('all', array('conditions' => array(
                'NOT' => array(
                   
                    'ScheduleEntry.approved' => null
                ),
                 'ScheduleEntry.user_id' => $this->Auth->user('id'),
                'ScheduleEntry.type' => 'scheduling'
            )));
           
            $this->set('approved',$approved);
        }
       private function _isValidDate($date) 
       {
           return (bool) strtotime($date);
       }
       
       function admin_alertAllUsers()
       {
           $a = 1;
           $b = 0;
           $c = 1;
           
           
           $this->User->unbindModel(array('hasMany' => array(
               'TimeEntry', 'Notification', 'ApprovalManager'
           ),'belongsTo'=> array('Vendor')));
           $users = $this->User->find('all', array('recursive' => 2));
           foreach($users as $j => $user)
           {
               foreach($user['ScheduleEntry'] as $i => $entry)
               {
                   if(!($entry['approved'] === null 
                           && !$entry['email_alert_delivered'] 
                           && $entry['type'] ==='scheduling'))
                   {
                       unset($user['ScheduleEntry'][$i]);
                   }
               }
               if(!empty($user['ScheduleEntry']))
               {
                   
                   $this->_sendScheduleAlertEmail($user);
                   foreach($user['ScheduleEntry'] as $setToSent)
                   {
                       $this->ScheduleEntry->id = $setToSent['id'];
                       $this->ScheduleEntry->saveField('email_alert_delivered', 1);
                   }
               }
               else
               {unset($users[$j]);
               
               }
              
           }
           $this->Session->setFlash(count($users) . " notified of scheduling by email!", 'flash_success');
           $this->redirect('/admin/jobs/scheduler');
           
       }
       
       function autoApprove($userId, $schedulingId)
       {
           $entry = $this->ScheduleEntry->findById($schedulingId);
          if($entry['ScheduleEntry']['user_id'] === $userId)
          {
              $this->ScheduleEntry->id = $schedulingId;
              $this->ScheduleEntry->saveField('approved', 1);
              exit('done');
          }
          exit('error');
       }
       private function _sendScheduleAlertEmail($user)
       {
//           pr($user);
//           exit();
           $this->loadModel('ServiceArea');
           $serviceAreas = $this->ServiceArea->find('list', array('fields' => array('id', 'name')));
           
           App::uses('CakeEmail', 'Network/Email');
                            $to = array($user['User']['email']);
                            
                            $email = new CakeEmail('smtp');
                            $email->template('schedule_notification', 'default')
                            ->emailFormat('html')
                            ->subject($this->config['site.name'] . ' Schedule Notification System')
                            ->viewVars(array('description' => 'Scheduling Alert System', 'user' => $user,'config' => $this->config,'serviceAreas' => $serviceAreas))
                            ->to($to)
                            ->send();
                            
                            return true;
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
