<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    App::uses('AppController', 'Controller');
   
    
    class JobsController extends AppController {

    	public $name = 'Job';
        public $uses = array('Job', 'User', 'ScheduleEntry', 'Customer', 'TaskItem', 'TaskListTemplate', 'JobTaskList','JobTaskListItem');

        
        public function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow('import','ajax_scheduleEmployees');
        }
        
        
        public function beforeRender() {
            parent::beforeRender();
            $this->set('section', 'jobs');
        }
        
        public function admin_d()
        {
            $employees = $this->User->find('all');
            pr($this->_distanceFromJob("dd472677110393e382e95252b3fab046e95ed379", $employees));
            exit();
        }
        private function _distanceFromJob($jobId, $employees, $max = null)
        {
            
            $this->layout = 'ajax';
            $job = $this->Job->findById($jobId);
            $dest_city = $job['Customer']['bill_city'];
            $dest_state = $job['Customer']['bill_state'];
            
            
            $origin = array();
            foreach($employees as $user)
                $origin[] = $user['User']['city'] . "+" . $user['User']['state'];
            
            
            $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=";
                   
            $first = true;
            foreach($origin as $o)
            {
                if(!$first)
                    $url .= "|";
                else
                    $first = false;
                
                    $url .= urlencode($o);
                    
            }
                    $url .= "&destinations=" . 
                    urlencode($dest_city) . "+" . urlencode($dest_state) . "&units=imperial&mode=driving&key=AIzaSyDdgof__00FT0RRTX1usO93WORD_5Dh5g4";
//            $ch = curl_init();
//            curl_ssetopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($ch, CURLOPT_URL, $url);
//            $result = curl_exec($ch);
//            curl_close($ch);
            $result = file_get_contents($url);
            $resultArray = json_decode($result);
            
         //   return $resultArray;
            foreach($employees as $i => $user){
                if(isset($resultArray->rows[$i]->elements[0]->distance->text)){
                    
                    if(isset($max) && !empty($max) && $max <= $resultArray->rows[$i]->elements[0]->distance->text) {
                        unset($employees[$i]);
                    }
                    else
                    {
                        $employees[$i]['distance'] = $resultArray->rows[$i]->elements[0]->distance->text;
                        
                    }
                }
                    
                    
            }
          
           
            return $employees;
        
            
            
        }
        public function ajax_schedulerArray($id = null)
        {
            $this->layout = 'ajax';
            $job = $this->Job->findById($id);
            $return = array(
                'team_leaders_count' => $job['Job']['team_leader_count'],
                'employees_count' => $job['Job']['employee_count'],
                'location' => $job['Customer']['bill_city'] . ", " . $job['Customer']['bill_state'],
                'job_name' => $job['Job']['name'],
                'cust_name' => $job['Customer']['name'],
                'current_team_leaders' => array(),
                'current_employees' => array()
                    
            );
            echo json_encode($return);
            exit();
        }
        public function admin_checkSchedule() {
            $data = $this->request->input('json_decode',true);
            pr(($data), true);
            
            exit();
        }
        
        private function _scheduleEvent($data,$event,$eventId)
        {
            $this->Job->id = $eventId;
                $currJob = $this->Job->read();
                $this->Job->saveField('start_date', $event['start']);
                $this->Job->saveField('end_date', $event['end']);
                
                // Schedule the job in the task list
                // $this->Job->markScheduled()
                
                // store current schedule
                $currentSchedule = $this->ScheduleEntry->find('list', array('conditions' => array(
                        'ScheduleEntry.job_id' => $eventId
                    ), 'fields' => array('ScheduleEntry.user_id', 'ScheduleEntry.user_id')));
                    
                    // clear all current schedulings
                    $this->ScheduleEntry->deleteAll(array('ScheduleEntry.job_id' => $eventId));
                
                // check each employee and schedule separately
                foreach($event['employees'] as $typeName => $type)
                {
                    if(is_array($type)){
                        $position = (trim($typeName) == 'teamLeaders' ? 'team_leader' : 'employee'); 
                    
                    foreach($type as $employeeId => $employee) {
                        $result = $this->_checkEmployees($event['start'], $event['end'], $employeeId, $eventId);
                        if($result == 'ok')
                            {
                                // EMPLOYEE not scheduled, free to schedule and queue notification
                                 $this->ScheduleEntry->create();
                                $newSchedule = array('ScheduleEntry' => array(
                                    'user_id' => $employeeId,
                                    'start_date' => $event['start'],
                                    'end_date' => $event['end'],
                                    'job_id' => $eventId,
                                    'position' => $position
                                    
                                ));
                                 $this->ScheduleEntry->save($newSchedule);
                                
                                // Queue employee notification if not already scheduled previously
                                 // Also remove from list, because employee remaining must be notified of unscheduling
                                 if(!in_array($employeeId, $currentSchedule))
                                    $this->Notification->queueNotification($employeeId, 'NewScheduling', '/admin/jobs/approveScheduling', 'New Schedule Item', 'You have %i new pending schedule entries.');
                                 else
                                     unset($currentSchedule[$employeeId]);
                            }
                
                    }
                    
                    // If any employees are still in the array, then we have to notify them of descheduling
                    foreach($currentSchedule as $id)
                    {
                        $this->Notification->queueNotification($id, 'Descheduling', '/admin/jobs/viewSchedule', 'Removed Schedule Item', 'You have been removed from ' . $currJob['Job']['name'], 0);
                    }
                }
                }
        }
        
        private function _rescheduleEvent($data,$event,$eventId)
        {
            $this->Job->id = $eventId;
                $currJob = $this->Job->read();
                $this->Job->saveField('start_date', $event['start']);
                $this->Job->saveField('end_date', $event['end']);
                
                // Schedule the job in the task list
                // $this->Job->markScheduled()
                
                // store current schedule
                $currentSchedule = $this->ScheduleEntry->find('list', array('conditions' => array(
                        'ScheduleEntry.job_id' => $eventId
                    ), 'fields' => array('ScheduleEntry.user_id', 'ScheduleEntry.user_id')));
                    
                    // clear all current schedulings
                    $this->ScheduleEntry->deleteAll(array('ScheduleEntry.job_id' => $eventId));
                
                // check each employee and schedule separately
                foreach($event['employees'] as $typeName => $type)
                {
                    if(is_array($type)){
                        $position = (trim($typeName) == 'teamLeaders' ? 'team_leader' : 'employee'); 
                    
                    foreach($type as $employeeId => $employee) {
                        $result = $this->_checkEmployees($event['start'], $event['end'], $employeeId, $eventId);
                        if($result == 'ok')
                            {
                                // EMPLOYEE not scheduled, free to schedule and queue notification
                                 $this->ScheduleEntry->create();
                                $newSchedule = array('ScheduleEntry' => array(
                                    'user_id' => $employeeId,
                                    'start_date' => $event['start'],
                                    'end_date' => $event['end'],
                                    'job_id' => $eventId,
                                    'position' => $position,
                                        'approved' => null, // must unapprove because of rescheduling
                                    'denial_mesage' => null
                                ));
                                 $this->ScheduleEntry->save($newSchedule);
                                
                                
                                
                                // Queue employee notification if not already scheduled previously
                                 // Also remove from list, because employee remaining must be notified of unscheduling
                                 if(!in_array($employeeId, $currentSchedule)) {
                                    $this->Notification->queueNotification($employeeId, 'NewScheduling', '/admin/jobs/approveScheduling', 'New Schedule Item', 'You have %i new pending schedule entries.');
                                    
                                 }
                                 // else employee was already scheduled, notify of rescheduling
                                 else
                                 {
                                     unset($currentSchedule[$employeeId]);
                                    $this->Notification->queueNotification($employeeId, 'EditScheduling', '/admin/jobs/approveScheduling', 'Edited Schedule Item', 'The schedule for ' . $currJob['Job']['name'] . ' has changed!', 0); 
                                 }
                            } 
                
                    }
                    
                    // If any employees are still in the array, then we have to notify them of descheduling
                    foreach($currentSchedule as $id)
                    {
                        $this->Notification->queueNotification($id, 'Descheduling', '/admin/jobs/viewSchedule', 'Removed Schedule Item', 'You have been removed from ' . $currJob['Job']['name'], 0);
                    }
                }
                }
        }
        
        private function _descheduleEvent($data,$event,$eventId)
        {
            // start with unscheduling the job
                    $this->Job->id = $eventId;
                    $currJob = $this->Job->read();
                    $this->Job->saveField('start_date', null);
                    $this->Job->saveField('end_date', null);
                    
                    // see current schedule, deschedule all and then notify all employees of descheduling
                     $currentSchedule = $this->ScheduleEntry->find('list', array('conditions' => array(
                        'ScheduleEntry.job_id' => $eventId
                    ), 'fields' => array('ScheduleEntry.user_id', 'ScheduleEntry.user_id')));
                   
                    // clear all current schedulings
                    $this->ScheduleEntry->deleteAll(array('ScheduleEntry.job_id' => $eventId));
                    
                    // notify all users of descheduling
                    foreach($currentSchedule as $id)
                    {
                        $this->Notification->queueNotification($id, 'Descheduling', '/admin/jobs/viewSchedule', 'Removed Schedule Item', $currJob['Job']['name'] . " has been rescheduled or cancelled.", 0);
                    }
        }
        
        public function admin_schedule() {
            $data = json_decode($this->request->data, true);
            
            foreach($data as $eventId => $event)
            {
                if(!empty($event)) {
                // Definitely schedule the event, regardless of employees BUT FIRST..
                    
                    // check to make sure the event isn't already scheduled but with different dates.
                    // that gets handled separately because employees must be notified of rescheduling
                                       
                $this->Job->id = $eventId;
                $currJob = $this->Job->read();
                
                if(($event['start'] == $currJob['Job']['start_date'] && $event['end'] == $currJob['Job']['end_date']) || 
                       ($currJob['Job']['start_date'] == null && $currJob['Job']['end_date'] == null))
                    $this->_scheduleEvent($data, $event, $eventId);
                else
                    $this->_rescheduleEvent($data, $event, $eventId);
                
                
                }
                else //empty event - this means it WAS scheduled, and then got removed
                    // have to remove employees from schedule, notify them they were removed, and unschedule event
                    // also have to decrement the task list
                {
                    
                    $this->_descheduleEvent($data,$event,$eventId);
                }
            }
              $this->Session->setFlash('All schedulings saved successfully!', 'flash_success');
              $this->redirect('/admin/jobs/scheduler');
           
        }
        
        private function _checkEmployees($start, $end, $userId, $jobId)
        {
            $return = 'ok';
           
            
            return $return;
        }
        public function ajax_scheduleEmployees($id = null, $options=null) 
        {
           
            $opts = array();
        
            if(isset($options))
            {
            $options = explode("|", $options);
            
            foreach($options as $i => $opt)
            {
                $temp = explode(":", $opt);
                $opts[$temp[0]] = $temp[1];
            } 
            
            }
            
            $this->layout = 'ajax';
            $job = $this->Job->findById($id);
            if(!isset($id))
                $employeeList = $this->User->find('all');
            else
                $employeeList = $this->User->find('all', array('conditions'=> array()));
            
            if(isset($opts['distance']) && !empty($opts['distance']))
                $employeeList = $this->_distanceFromJob($id, $employeeList, $opts['distance']);
            else
                $employeeList = $this->_distanceFromJob($id, $employeeList);
            
  
            $return = array('draw' => 1, 'recordsTotal' => sizeof($employeeList), 'recordsFiltered' => sizeof($employeeList),
                'data' => array());
                    foreach($employeeList as $emp)
            {
                       
                $return['data'][] = array('first' => $emp['User']['first_name'], 'last' => $emp['User']['last_name'],
                    'location' => $emp['User']['city'] . ", " . $emp['User']['state'], 'distance' => (isset($emp['distance']) ? substr($emp['distance'],0,-3) : "999999"), 'abilities' => '{list of all areas}', 'notes' => '{pertinent notes}',
                    'team-leader' => '<a onclick="teamLeaderAdd(this);" data-id="' . $emp['User']['id'] . '" class="addLeader" href="#"><i class="fa fa-plus-circle"></i></a>',
                    'employee' => '<a onclick="employeeAdd(this);" data-id="' . $emp['User']['id'] . '" class="addEmployee" href="#"><i class="fa fa-plus-circle"></i></a>');
                    }
                    echo json_encode($return);
             exit();
        }
        public function admin_scheduler() {
            $this->set('setColors', [
        "training" => 'pink',
        "certification"=> 'lightblue',
        "accreditation"=> 'lightgreen',
        "other" => 'lightgray'
    ]);
$this->set('pendingColors', [
        "training"=> 'purple',
        "certification" => 'darkblue',
        "accreditation" => 'darkred',
        "other" => 'black'
    ]);
            $openJobs = $this->Job->find('all', array('recursive' => 2,  'conditions' => array(
                'review_start' => null,
                'start_date' => null,
                'end_date' => null
            )));
            
            foreach($openJobs as  $i => $job) {
                unset($openJobs[$i]['Customer']['Jobs']);
            }
            $scheduledJobs = $this->Job->find('all', array('recursive' => 2, 'conditions'=> array(
                'review_start' => null,
                'NOT' => array(
                    'start_date' => null,
                    'end_date' => null
                )
            )));
            
            $this->set('jobs', array('open' => $openJobs, 'set' => $scheduledJobs));
            
            
        }
        
        public function admin_showById($id){
            pr($this->Job->find('first', array('conditions' => array('Job.id' => $id), 'recursive' => 3)));
            exit();
        }
    
        public function admin_delete($id)
        {
            $this->Job->delete($id);
            $this->redirect('/admin/jobs');
        }
        public function admin_add($customerId = null)
        {
            if(!empty($this->request->data))
            {
                // get submitted info
                $job = $this->request->data['Job'];
                
                // set defaults based on info
                $cust = $this->Customer->findById($job['customer_id']);
                $job['company_name'] = $cust['Customer']['name'];
                $job['full_name'] = $job['company_name'] . ":" . $job['name'];
                $job['total_balance'] = $job['balance'];
                $job['job_status'] = 'InProgress';
                $job['description'] = $job['notes'];
                $job['job_type_id'] = $job['job_type'] = "";
                $job['id'] = sha1(time() . $job['name']);
                // save job and use id for other creations
                $this->Job->create();
                $this->Job->save($job);
                
                $id = $this->Job->id;
                
                // create new task list based on the template
               $newTaskList = $this->TaskListTemplate->find('first', array('conditions' => array('id' => $job['task_list_template_id']),'recursive' => 0));
               
               $newList = array('name' => $newTaskList['TaskListTemplate']['name'],
                   'job_id' => $id,
                   'description' => $newTaskList['TaskListTemplate']['description']);
               
               $this->JobTaskList->create();
               $this->JobTaskList->save($newList);
               
               $id = $this->JobTaskList->id;
               
               // Add all items to the new list
               $this->loadModel('TaskListTemplateItem');
               
               $taskItems = $this->TaskListTemplateItem->find('all', array('conditions' => array(
                   'task_list_template_id' => $job['task_list_template_id']
               )));
               
               foreach($taskItems as $item)
               {
                   $save = array('job_task_list_id' => $id, 'task_item_id' => $item['TaskListTemplateItem']['task_item_id'],
                      'sort_order' => $item['TaskListTemplateItem']['sort_order'] );
                   $this->JobTaskListItem->create();
                   $this->JobTaskListItem->save($save);
               }
                
               
            }
            // If customer ID is passed in, we will use that to autofill some parts
            if(isset($customerId))
            {
                
                $customer = $this->Customer->findById($customerId);
            }
            
               
            
            // if customer is found, set for the view
            if(isset($customer) && !empty($customer))
            {
                $this->set('customer', $customer);
                $this->set('customers', array($customer['Customer']['id'] => array('id' => $customer['Customer']['id'],'name' => $customer['Customer']['name'], 'class' => 'main-item')));
            }
            else
                $this->set('customers', $this->_loadCustomers());
            
            // Get all possible service areas for the view
            $this->loadModel('ServiceArea');
            $serviceAreas = $this->ServiceArea->find('all', array('conditions'=> array('ServiceArea.parent_id' => null)));
     
            $this->set('serviceAreas', $serviceAreas);
            
            // Load task list templates to choose for this Job
            $this->loadModel('TaskListTemplate');
            $taskLists = $this->TaskListTemplate->find('all');
            
            $this->set('taskLists', $taskLists);
        }
        
        public function admin_index($past = null) {
            if(isset($past)) {
                $this->set('jobs', $this->Job->find('all', array('conditions' => array(
                    'Job.end_date <' => date('m-d-Y'),
                    'NOT' => array(
                        'Job.end_date' => "0000-00-00"
                    )
                ),
                    'order' => 'Customer.full_name ASC')));
             $this->set('past', 'Past ');  
            }
            else{
                $this->set('jobs', $this->Job->find('all', array('conditions' => array(
                    'OR' => array(
                    'Job.end_date >=' => date('m-d-Y'),
                        'Job.end_date' => "0000-00-00",
                        'Job.end_date' => NULL
                    )
                ),
                    'order' => 'Customer.full_name ASC')));
            $this->set('past', 'Open ');
            }
            
        }
        
        public function admin_edit($id = null) {
            $this->Session->setFlash('This feature has not been added yet!', 'flash_error');
            $this->redirect('/admin/jobs');
        }
        public function import($hash = null)
        {
           if($hash != "asdn")
           {
               exit('incorrect hash has been supplied');
           }
           
           $this->redirect('/importQBdata.php');
           
           // Correct hash supplied - begin import of jobs
           
           
           

/*
// If you wanted, you could do something with $response here for debugging

$fp = fopen('/path/to/file.log', 'a+');
fwrite($fp, $response);
fclose($fp);
*/

exit(0);
        }
        
   
        
        
    }
    
    
?>
