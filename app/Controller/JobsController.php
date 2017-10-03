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

        public function admin_importAttendeeList()
        {
            if($this->request->is('post'))
            {
                $importMap = array();
                $importArray = array();
                $importFormat = array('Contact' => array()
                    , 'ContactAddress' => array(), 
                    'Customer' => array());
                $csv = array_map('str_getcsv', file($_FILES["csvfile"]["tmp_name"]));
                foreach(array_shift($csv) as $i => $header) {
                    $headerName = str_replace(" ","_", str_replace("-", "", strtolower($header)));
                    //decide where in the format it should go
                    $importMap[$i] = $this->_determineMap($headerName);  
                }
                foreach($csv as $contact)
                {
                    $newImport = $importFormat;
                    foreach($contact as $i => $value)
                    {
                        if(!empty($importMap[$i]))
                        {
                            $fieldArray = explode(".", $importMap[$i]);
                            $newImport[$fieldArray[0]][$fieldArray[1]] = $value;
                        }
                    }
                    $importArray[] = $newImport;
                }
                
                $totalLinks = $this->_parseArray($importArray, $this->request->data['job_id']);
               $this->Session->setFlash($totalLinks['imports'] . " new contacts imported, " . 
                       $totalLinks['customers'] .
                       " new customers imported, and "
                       . $totalLinks['links'] . " links to job created!", "flash_success");
               
               $this->redirect($this->referer('/admin/jobs'));
               exit();
            }
        }
        private function _createNewCustomer($customerName, $contactId)
        {
            $id = sha1("NAME" . time() . rand(0,1000));
            $newCust = array(
                                    'Customer' => array(
                                        'id' => $id,
                                        'name' => $customerName,
                                        'full_name' => $customerName,
                                        'company_name' => "",
                                        'first_name' => "",
                                        'middle_name' => "",
                                        'last_name' => "",
                                        'contact' => "",
                                        'ship_note' => "",
                                        'bill_note' => "",
                                        'primary_contact_id' => $contactId
                                    )
                                );
            $this->loadModel('Customer');
           // $this->Customer->create();
            $this->Customer->save($newCust);
        }
        private function _parseArray($arr, $jobId)
        {
            $return = array('links' => 0, 'imports' => 0, 'customers' => 0);
            foreach($arr as $contact)
            {
                if(isset($contact['Contact']) && !empty($contact['Contact']))
                {
                    $this->loadModel('Contact');
                    $exists = $this->Contact->find('first', array('conditions' => array('Contact.email LIKE ' => $contact['Contact']['email'])));
                    if(empty($exists))
                    {
                        $contact['Contact']['contact_type'] = "";
                        // Contact doesn't exist - must create a new one
                        $this->Contact->create();
                        $this->Contact->save($contact['Contact']);
                        $contactId = $this->Contact->id;
                        
                        $return['imports']++;
                        
                        if(!empty($contact['ContactAddress']))
                        {
                            $contact['ContactAddress']['contact_id'] = $contactId;
                            $contact['ContactAddress']['type'] = "home";
                            $contact['ContactAddress']['state'] = $this->_convertState($contact['ContactAddress']['state']);
                            $this->loadModel('ContactAddress');
                            $this->ContactAddress->create();
                            
                            if($this->ContactAddress->save($contact['ContactAddress']))
                                echo "";
                        }
                        if(!empty($contact['Customer']))
                        {
                            // try to find the customer by name - if we can't, don't worry about it
                            $this->loadModel('Customer');
                            $customer = $this->Customer->find('first', array('conditions' => array('Customer.name LIKE ' => "%" . $contact['Customer']['name'] . "%")));
                            if(empty($customer))
                            {
                                $this->Customer->id = $this->_createNewCustomer($contact['Customer']['name'], $contactId);
                                $customer = $this->Customer->read();
                                $return['customers']++;
                            }
                            if(!empty($customer)) {
                                $this->loadModel('ContactCustomer');
                                $this->ContactCustomer->create();
                                $this->ContactCustomer->save(array('contact_id' => $contactId, 'customer_id' => $customer['Customer']['id']));
                            }
                        }
                        
                        $this->Contact->id = $contactId;
                        $exists = $this->Contact->read();
                    }
 else {
     $contactId = $exists['Contact']['id'];
     // contact exists, but lets make sure we have their address.
     if(empty($exists['ContactAddress'])) {
     if(!empty($contact['ContactAddress']))
                        {
                            $contact['ContactAddress']['contact_id'] = $contactId;
                            $contact['ContactAddress']['type'] = "home";
                            $contact['ContactAddress']['state'] = $this->_convertState($contact['ContactAddress']['state']);
                            $this->loadModel('ContactAddress');
                            $this->ContactAddress->create();
                            
                            if($this->ContactAddress->save($contact['ContactAddress']))
                                echo "";
                        }
     }
     if(empty($exists['Customer'])) {
                        if(!empty($contact['Customer']))
                        {
                            // try to find the customer by name - if we can't, don't worry about it
                            $this->loadModel('Customer');
                            $customer = $this->Customer->find('first', array('conditions' => array('Customer.name LIKE ' => "%" . $contact['Customer']['name'] . "%")));
                             if(empty($customer))
                            {
                                $this->Customer->id = $this->_createNewCustomer($contact['Customer']['name'], $contactId);
                                $customer = $this->Customer->read();
                                $return['customers']++;
                            }
                            if(!empty($customer)) {
                                $this->loadModel('ContactCustomer');
                                $this->ContactCustomer->create();
                                $this->ContactCustomer->save(array('contact_id' => $contactId, 'customer_id' => $customer['Customer']['id']));
                            }
                        }
     }
     
 }
                    
                  
                    $this->loadModel('JobAttendee');
                    $linkExists = $this->JobAttendee->find('first', array('conditions' => array(
                        'contact_id' => $exists['Contact']['id'],
                        'job_id' => $jobId
                    )));

                    if(empty($linkExists))
                    {
                    $this->JobAttendee->create();
                    $this->JobAttendee->save(array('job_id' => $jobId, 'contact_id' => $exists['Contact']['id']));
                    // Now we can save the contact link regardless
                    $return['links']++;
                    }
                }
            }
            return $return;
        }
        private function _convertState($state)
        {
            $return = "";
            $states = array(
			'' => '',
			'AL'=>'Alabama', 
			'AK'=>'Alaska', 
			'AZ'=>'Arizona', 
			'AR'=>'Arkansas', 
			'CA'=>'California', 
			'CO'=>'Colorado', 
			'CT'=>'Connecticut', 
			'DE'=>'Delaware', 
			'DC'=>'District of Columbia', 
			'FL'=>'Florida', 
			'GA'=>'Georgia', 
			'HI'=>'Hawaii', 
			'ID'=>'Idaho', 
			'IL'=>'Illinois', 
			'IN'=>'Indiana', 
			'IA'=>'Iowa', 
			'KS'=>'Kansas', 
			'KY'=>'Kentucky', 
			'LA'=>'Louisiana', 
			'ME'=>'Maine', 
			'MD'=>'Maryland', 
			'MA'=>'Massachusetts', 
			'MI'=>'Michigan', 
			'MN'=>'Minnesota', 
			'MS'=>'Mississippi', 
			'MO'=>'Missouri', 
			'MT'=>'Montana',
			'NE'=>'Nebraska',
			'NV'=>'Nevada',
			'NH'=>'New Hampshire',
			'NJ'=>'New Jersey',
			'NM'=>'New Mexico',
			'NY'=>'New York',
			'NC'=>'North Carolina',
			'ND'=>'North Dakota',
			'OH'=>'Ohio', 
			'OK'=>'Oklahoma', 
			'OR'=>'Oregon', 
			'PA'=>'Pennsylvania', 
			'RI'=>'Rhode Island', 
			'SC'=>'South Carolina', 
			'SD'=>'South Dakota',
			'TN'=>'Tennessee', 
			'TX'=>'Texas', 
			'UT'=>'Utah', 
			'VT'=>'Vermont', 
			'VA'=>'Virginia', 
			'WA'=>'Washington', 
			'WV'=>'West Virginia', 
			'WI'=>'Wisconsin', 
			'WY'=>'Wyoming'
                
		);
            foreach($states as $code => $st)
            {
                if(strtolower($state) === strtolower($st))
                    return $code;
            }
            
            return $return;
            
        }
        
        private function _determineMap($header)
        {
            switch ($header) 
            {
                case 'first_name':
                    return "Contact.first_name";
                    break;
                case 'last_name':
                    return "Contact.last_name";
                    break;
                case 'first':
                    return "Contact.first_name";
                    break;
                case 'last':
                    return "Contact.last_name";
                    break;
                case 'email':
                    return "Contact.email";
                    break;
                case 'email_address':
                    return "Contact.email";
                    break;
                case 'address':
                    return "ContactAddress.address_1";
                    break;
                case 'address_1':
                    return "ContactAddress.address_1";
                    break;
                case 'address_2':
                    return "ContactAddress.address_2";
                    break;
                case 'city':
                    return "ContactAddress.city";
                    break;
                case 'state':
                    return "ContactAddress.state";
                    break;
                case 'state/province':
                    return "ContactAddress.state";
                    break;
                case 'zip':
                    return "ContactAddress.zip";
                    break;
                case 'zip_code':
                    return "ContactAddress.zip";
                    break;
                case 'zipcode':
                    return "ContactAddress.zip";
                    break;
                case 'title':
                    return "Contact.title";
                    break;
                case 'job_title':
                    return "Contact.title";
                    break;
                case 'organization':
                    return "Customer.name";
                    break;
                case 'company':
                    return "Customer.name";
                    break;
                case 'company_name':
                    return "Customer.name";
                    break;
                default:
                    return "";
                    break;
                 
            }
        }
        public function admin_ajaxGetEventDetails($eventId) {
            $this->layout = 'ajax';
            $job = $this->Job->findById($eventId);
            $return = "<p>People Served: <em>" . $job['Job']['people_served_count'] . "</em><br />";
            if(isset($job['Job']['IDD_BH']))
                $return .= "IDD/BH: <em>" . $job['Job']['IDD_BH'] . "</em><br />";
            $return .= "Engagement Fee Paid: <em>" . ($job['Job']['eng_fee_paid'] === 0 ? 'No' : 'Yes') . "</em><br />";
            $return .= "<br />Notes: <br /><em>";
            if(!empty($job['Job']['notes']))
                $return .= $job['Job']['notes'] . "</em></p>";
            else
                $return .= "--- NO NOTES FOUND ---</em></p>";
            
            echo $return; 
            exit();
        }
        public function admin_view($id)
        {
            $this->Job->unbindModel(array('belongsTo' => array('Customer')));
            $job = $this->Job->find('first', array('conditions'=>array('Job.id' => $id), 'recursive' => 2));
           // pr($job); exit();
        }
        public function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow('import','ajax_scheduleEmployees', 'admin_ajaxGetEventDetails');
        }
        
        public function admin_changeEngagementFee($jobId, $value = 1)
        {
            $this->Job->id = $jobId;
            $this->Job->saveField('eng_fee_paid', $value);
            if($value === 1)
                $this->Session->setFlash('Engagement Fee has been marked as paid.', 'flash_success');
            else
                $this->Session->setFlash('Engagement Fee has been marked as unpaid.', 'flash_success');
            
            $this->redirect($this->referer("/admin/jobs/dashboard/" .$jobId));
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
            
            // accounts for new specific job address functionality
            if(!empty($job['Job']['city']) && !empty($job['Job']['state']))
            {
                $dest_city = $job['Job']['city'];
                $dest_state = $job['Job']['state'];
            }
            
            
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
                    
                    if(isset($max) && !empty($max) && $max <= ((int) substr($resultArray->rows[$i]->elements[0]->distance->text,0,-3))) {
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
            $errorList = array();
            $this->Job->id = $eventId;
            
                $currJob = $this->Job->read();
                $this->Job->saveField('start_date', $event['start']);
                $this->Job->saveField('end_date', $event['end']);
                
                // Schedule the job in the task list
                // $this->Job->markScheduled()
                
                // store current schedule, excluding disapprovals
                $currentSchedule = $this->ScheduleEntry->find('list', array('conditions' => array(
                        'ScheduleEntry.job_id' => $eventId,
                    'OR' => array(
                        'ScheduleEntry.approved is null',
                        'ScheduleEntry.approved' => "1")
                    ), 'fields' => array('ScheduleEntry.user_id', 'ScheduleEntry.user_id')));
                    

                
                $needNewList = array();
                $ignoreIds = array();
                
                    // clear all current schedulings 
                // UPDATE, don't do this - it gets rid of approvals already in place, duh...
                  //  $this->ScheduleEntry->deleteAll(array('ScheduleEntry.job_id' => $eventId));
                
                   
                    
                // check each employee and schedule separately
                foreach($event['employees'] as $typeName => $type)
                {
                    if(is_array($type)){
                        $position = (trim($typeName) == 'teamLeaders' ? 'team_leader' : 'employee'); 
                    
                    foreach($type as $employeeId => $employee) {
                        
                        $result = $this->_checkEmployees($event['start'], $event['end'], $employeeId, $eventId, $position);
                        //override function to schedule double bookings
                        
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
                                $needNewList[$this->ScheduleEntry->id] = $position;
                                 // Link schedule entry to task list, after removing current linkings
                                 
                                 
                                 // Find first open list not linked to a user. UPDATE. NO.
                                 //$newListToLink = $this->JobTaskList->find('first', array('conditions' => array(
                                 //    'JobTaskList.job_id' => $eventId,
                                 //    'JobTaskList.type' => $position,
                                 //    'JobTaskList.schedule_entry_id is NULL'
                                // )));
                                
//                                 if(!empty($newListToLink)) {
//                                 $this->JobTaskList->id = $newListToLink['JobTaskList']['id'];
//                                 $this->JobTaskList->saveField('schedule_entry_id', $this->ScheduleEntry->id);
//                                 }
                                 
                                // Queue employee notification if not already scheduled previously
                                 // Also remove from list, because employee remaining must be notified of unscheduling
                                 if(!in_array($employeeId, $currentSchedule))
                                    $this->Notification->queueNotification($employeeId, 'NewScheduling', '/admin/schedule/approveMySchedule', 'New Schedule Item', 'You have %i new pending schedule entries.');
                                 else
                                     unset($currentSchedule[$employeeId]);
                            }
                            // employee is scheduled already in the time frame given... gots a problem dude.                            
                            else if($result == 'error') 
                            {
                                $errorList[] = array('jobId' => $eventId, 'employeeId' => $employeeId);
                               
                            }

                            // employee already on this event and has not denied, we can ignore them.
                            else
                            {
                                 unset($currentSchedule[$employeeId]);
                                 // result will hold the schedule entry id that matches this employee and job
                                $ignoreIds[] = $result;
                            }
                
                           
                    }
                    }
                }
                    
                                    
                    
                    // If any employees are still in the array, then we have to notify them of descheduling
                
                    foreach($currentSchedule as $id)
                    {
                        
                        $this->Notification->queueNotification($id, 'Descheduling', '/admin/schedule/mySchedule', 'Removed Schedule Item', 'You have been removed from ' . $currJob['Job']['name'], 0);
                    }
                    
                    // Remove user schedule entry
                        $removeUs = $this->ScheduleEntry->find('all', array('conditions' => 
                            array('ScheduleEntry.user_id' => $currentSchedule, 
                                'ScheduleEntry.job_id' => $eventId,
                                'OR' => array(
                                    'ScheduleEntry.approved is null',
                                    'ScheduleEntry.approved' => "1"
                                ))
                            ));
                
                        
                        foreach($removeUs as $delete)
                        {
                            $this->ScheduleEntry->id = $delete['ScheduleEntry']['id'];
                            $this->ScheduleEntry->delete();
                            
                            if(!empty($delete['JobTaskList'])) {
                            if(!empty($delete['JobTaskList']['id'])){
                            $this->JobTaskList->id = $delete['JobTaskList']['id'];
                            $this->JobTaskList->saveField('schedule_entry_id', null);
                            }
                            }
                        }
                    
                    // update all lists to have no schedule entry if their name was not ignored
                    $this->JobTaskList->updateAll(array('JobTaskList.schedule_entry_id' => null), array(
                                     'NOT' => array('JobTaskList.type' => 'scheduler',
                                         'JobTaskList.schedule_entry_id' => $ignoreIds),
                                     'JobTaskList.job_id' => $eventId));
                    
                    
                    foreach($needNewList as $schedId => $type)
                    {
                      //   Find first open list not linked to a user. UPDATE. NO.
                                 $newListToLink = $this->JobTaskList->find('first', array('conditions' => array(
                                     'JobTaskList.job_id' => $eventId,
                                     'JobTaskList.type' => $type,
                                     'JobTaskList.schedule_entry_id is null'
                                 )));
                                
                                 if(!empty($newListToLink)) {
                                 $this->JobTaskList->id = $newListToLink['JobTaskList']['id'];
                                 $this->JobTaskList->saveField('schedule_entry_id', $schedId);
                                 }
                    }
                    
                    // Just a clean up method to make sure all the ignoreIds already have a task list. 
                    // If not, give them one
                    foreach($ignoreIds as $checkId)
                    {
                        $this->ScheduleEntry->id = $checkId;
                        $test = $this->ScheduleEntry->read();
                        if(empty($test['JobTaskList']) || empty($test['JobTaskList']['id']))
                        {
                            $newListToLink = $this->JobTaskList->find('first', array('conditions' => array(
                                     'JobTaskList.job_id' => $eventId,
                                     'JobTaskList.type' => $type,
                                     'JobTaskList.schedule_entry_id is null'
                                 )));
                                
                                 if(!empty($newListToLink)) {
                                 $this->JobTaskList->id = $newListToLink['JobTaskList']['id'];
                                 $this->JobTaskList->saveField('schedule_entry_id', $checkId);
                                 }
                        }
                    }
                    
                    if(empty($errorList))
                        return true;
                    else
                        return $errorList;
                
        }
        
        private function _canSchedule($userId, $startDate, $endDate, $jobId)
        {
            
            $user = $this->User->findById($userId);
            if($user['User']['is_active'] === 'false')
                return false;
            
                foreach($user['ScheduleEntry'] as $entry)
                {
                    
                     
                    if(!(strtotime($entry['start_date']) >= strtotime($endDate) || 
                            strtotime($entry['end_date']) <= strtotime($startDate)
                            ))
                    {
                        // overlap
                       
                        if($entry['approved'] != "0" && $entry['job_id'] !== $jobId)
                        {
                            // not a denied record - must be legitimate overlap, but if override is on, return true 
			if($this->config['scheduler.override'])
                        {
                            return true;
                        }
                            return false;
                        
                        }
                    }
                        
                }
                return true;
            
        }
        
        function admin_dashboard($id = null) {
            $this->ScheduleEntry->unbindModel(array('belongsTo' => array('Job')));
            $this->User->unbindModel(array('hasMany' => array('ScheduleEntry', 'TimeEntry')));
            $job = $this->Job->find('first', array('conditions'=>array('Job.id' => $id),'recursive' => 4));
            $this->set('job', $job);
            
            if($this->Auth->user('is_scheduler'))
                $this->set('isScheduler', true);
            else
                $this->set('isScheduler', false);
            
            $this->loadModel('JobAttendee');
            $this->JobAttendee->bindModel(array('belongsTo' => array('Contact')));
            
            $attendees = $this->JobAttendee->find('all', array('recursive' => 2, 
                'conditions' => array('job_id' => $id)));
            
            $this->set('attendees', $attendees);
          //  pr($attendees); exit();
            
        }
        
        private function _rescheduleEvent($data,$event,$eventId)
        {
            
            $errorList = array();
            $this->Job->id = $eventId;
		if(empty($eventId))
		return true;
                $currJob = $this->Job->read();
                $this->Job->saveField('start_date', $event['start']);
                $this->Job->saveField('end_date', $event['end']);
                
                // Schedule the job in the task list
                // $this->Job->markScheduled()
                
                // store current schedule
                $currentSchedule = $this->ScheduleEntry->find('list', array('conditions' => array(
                        'ScheduleEntry.job_id' => $eventId,
                    'OR' => array(
                        'ScheduleEntry.approved is null',
                        'ScheduleEntry.approved' => "1")
                    ), 'fields' => array('ScheduleEntry.user_id', 'ScheduleEntry.user_id')));
                    

                    // clear all current schedulings
                    $this->ScheduleEntry->deleteAll(array('ScheduleEntry.job_id' => $eventId,
                        'OR' => array('ScheduleEntry.approved is null',
                            'ScheduleEntry.approved' => "1")));
                
                    $this->JobTaskList->updateAll(array('JobTaskList.schedule_entry_id' => null), array(
                                     'NOT' => array('JobTaskList.type' => 'scheduler'),
                                     'JobTaskList.job_id' => $eventId));
                    
                // check each employee and schedule separately
                foreach($event['employees'] as $typeName => $type)
                {
                    if(is_array($type)){
                        $position = (trim($typeName) == 'teamLeaders' ? 'team_leader' : 'employee'); 
                    
                    foreach($type as $employeeId => $employee) {
                        $result = $this->_checkEmployees($event['start'], $event['end'], $employeeId, $eventId, $position);
                        //override function to schedule double bookings
                       
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
                                
                                 // Link schedule entry to task list
                                 
                                 
                                 // Find first open list not linked to a user.
                                 $newListToLink = $this->JobTaskList->find('first', array('conditions' => array(
                                     'JobTaskList.job_id' => $eventId,
                                     'JobTaskList.type' => $position,
                                     'JobTaskList.schedule_entry_id' => null
                                 )));
                                 
                                 $this->JobTaskList->id = $newListToLink['JobTaskList']['id'];
                                 $this->JobTaskList->saveField('JobTaskList.schedule_entry_id', $this->ScheduleEntry->id);
                                 
                                
                                // Queue employee notification if not already scheduled previously
                                 // Also remove from list, because employee remaining must be notified of unscheduling
                                 if(!in_array($employeeId, $currentSchedule)) {
                                    $this->Notification->queueNotification($employeeId, 'NewScheduling', '/admin/schedule/approveMySchedule', 'New Schedule Item', 'You have %i new pending schedule entries.');
                                    
                                 }
                                 // else employee was already scheduled, notify of rescheduling
                                 else
                                 {
                                     unset($currentSchedule[$employeeId]);
                                    $this->Notification->queueNotification($employeeId, 'EditScheduling', '/admin/schedule/approveMySchedule', 'Edited Schedule Item', 'The schedule for ' . $currJob['Job']['name'] . ' has changed!', 0); 
                                 }
                            } 
                            // employee already scheduled on that day, cannot double book
                            else
                            {
                                // TODO: handle double booking alert for administrator.
                                $errorList[] = array('jobId' => $eventId, 'employeeId' => $employeeId);
                            }
                
                    }
                    
                    // If any employees are still in the array, then we have to notify them of descheduling
                    foreach($currentSchedule as $id)
                    {
                        $this->Notification->queueNotification($id, 'Descheduling', '/admin/schedule/mySchedule', 'Removed Schedule Item', 'You have been removed from ' . $currJob['Job']['name'], 0);
                    }
                }
                }
                
                if(empty($errorList))
                    return true;
                else
                    return $errorList;
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
                        'ScheduleEntry.job_id' => $eventId,
                    'OR' => array(
                        'ScheduleEntry.approved is null',
                        'ScheduleEntry.approved' => "1")
                    ), 'fields' => array('ScheduleEntry.user_id', 'ScheduleEntry.user_id')));
                    

                    // clear all current schedulings
                    $this->ScheduleEntry->deleteAll(array('ScheduleEntry.job_id' => $eventId,
                        'OR' => array('ScheduleEntry.approved is null',
                            'ScheduleEntry.approved' => "1")));
                    
                    // notify all users of descheduling
                    foreach($currentSchedule as $id)
                    {
                        $this->Notification->queueNotification($id, 'Descheduling', '/admin/schedule/mySchedule', 'Removed Schedule Item', $currJob['Job']['name'] . " has been rescheduled or cancelled.", 0);
                    }
                    
                    return true;
        }
        private function _fixEndDate($data) {
            // We are given an end date that is always one too many.. decrement if not the same as start date
            foreach($data as $i => $event)
            {
                if($event['start'] != $event['end'])
                {
                    $data[$i]['end'] = date('Y-m-d', strtotime($event['end'] . " -1 day"));
                }
            }
            return $data;
                
        }
        public function admin_schedule() {
            $data = json_decode($this->request->data, true);
            $data = $this->_fixEndDate($data);
            $result = null;
            $errorArray = array();
          //  pr($data); exit();
            foreach($data as $eventId => $event)
            {
                $result = false;
                if(!empty($event)) {
                // Definitely schedule the event, regardless of employees BUT FIRST..
                    
                    // check to make sure the event isn't already scheduled but with different dates.
                    // that gets handled separately because employees must be notified of rescheduling
                                       
                $this->Job->id = $eventId;
                $currJob = $this->Job->read();
                
                if(($event['start'] == $currJob['Job']['start_date'] && $event['end'] == $currJob['Job']['end_date']) || 
                       ($currJob['Job']['start_date'] == null && $currJob['Job']['end_date'] == null))
                    $result = $this->_scheduleEvent($data, $event, $eventId);
                else
                    $result = $this->_rescheduleEvent($data, $event, $eventId);
                
                
                }
                else //empty event - this means it WAS scheduled, and then got removed
                    // have to remove employees from schedule, notify them they were removed, and unschedule event
                    // also have to decrement the task list
                {
                    
                    $result = $this->_descheduleEvent($data,$event,$eventId);
                }
                
                if(is_array($result))
                   $errorArray = array_merge($errorArray, $result);
            }
            
             
            if(empty($errorArray))
                $this->Session->setFlash('All schedulings saved successfully!', 'flash_success');
            else
            {
                $flashMessage = "The following entries were not saved because of duplicated entries:\n";
                foreach($errorArray as $entry)
                {
                    $j = $this->Job->findById($entry['jobId']);
                    $u = $this->User->findById($entry['employeeId']);
                    $flashMessage .= $u['User']['first_name'] . " " . $u['User']['last_name'] . " - " . $j['Job']['full_name'] . ", \n\r";
                }
                $this->Session->setFlash($flashMessage, 'flash_error');
            }
              $this->redirect('/admin/jobs/scheduler');
           
        }
public function admin_removeDupes()
{
$sql = 'select o.*, oc.dupeCount, oc.*
from schedule_entries o
inner join (
    SELECT id, user_id, job_id, start_date, end_date, position, type, approved, created, COUNT(*) AS dupeCount
    FROM schedule_entries
    GROUP BY user_id, job_id, start_date, end_date,position, type
    HAVING COUNT(*) > 1
) oc on o.user_id = oc.user_id
AND o.job_id = oc.job_id
AND o.start_date = oc.start_date
AND o.end_date = oc.end_date
AND o.type = oc.type
AND o.position = oc.position
AND o.type = "scheduling"

AND (o.approved = null or o.approved = "1")  
ORDER BY `o`.`created`  ASC';
$result =  $this->ScheduleEntry->query($sql);

$result = $this->ScheduleEntry->find('all', array('conditions' => array('ScheduleEntry.type' => 'scheduling', 'OR' => array('ScheduleEntry.approved' => '1', 'ScheduleEntry.approved is null')), 'order' => 'ScheduleEntry.created ASC'));
$deleteArray = array();
$keepArray = array();
foreach($result as $r)
{
	if(!in_array($r['ScheduleEntry']['id'], $deleteArray)){
		$keepArray[] = $r['ScheduleEntry']['id'];

	$similar = $this->ScheduleEntry->find('all', array('conditions' => array(
			'ScheduleEntry.user_id' => $r['ScheduleEntry']['user_id'],
		'ScheduleEntry.job_id' => $r['ScheduleEntry']['job_id'],
		'ScheduleEntry.start_date' => $r['ScheduleEntry']['start_date'],
		'ScheduleEntry.end_date' => $r['ScheduleEntry']['end_date'],
		'ScheduleEntry.type' => 'scheduling',
		'ScheduleEntry.position' => $r['ScheduleEntry']['position'],
		'OR' => array('ScheduleEntry.approved' => "1", 'ScheduleEntry.approved is null'),
		'NOT' => array('ScheduleEntry.id' => $r['ScheduleEntry']['id'])
			), 'recursive' => -1));
	foreach($similar as $s)
	{
		if(!in_array($s['ScheduleEntry']['id'], $deleteArray) && !in_array($s['ScheduleEntry']['id'], $keepArray))
		{
			$deleteArray[] = $s['ScheduleEntry']['id'];
		}
	}

	}
		
}

echo ("<pre>");
echo sizeof($keepArray) . "<br>" . sizeof($deleteArray);
echo ("</pre>");

$this->ScheduleEntry->deleteAll(array('ScheduleEntry.id' => $deleteArray));
exit();
foreach($result as $r) {
	echo $r['o']['id'] . " => " . $r['oc']['id'];
$this->ScheduleEntry->deleteAll(array(
		'ScheduleEntry.user_id' => $r['o']['user_id'],
		'ScheduleEntry.job_id' => $r['o']['job_id'],
		'ScheduleEntry.start_date' => $r['o']['start_date'],
		'ScheduleEntry.end_date' => $r['o']['end_date'],
		'ScheduleEntry.type' => 'scheduling',
		'ScheduleEntry.position' => $r['o']['position'],
		'OR' => array('ScheduleEntry.approved' => "1", 'ScheduleEntry.approved is null'),
		'NOT' => array('ScheduleEntry.id' => $r['o']['id'])
));
}
exit();

}
	
        private function _checkEmployees($start, $end, $userId, $jobId, $type = 'employee')
        {
            $return = 'ok';
           
            // check for exact entry
            $entry = $this->ScheduleEntry->find('first', array('conditions' => array(
                'ScheduleEntry.user_id' => $userId,
                'ScheduleEntry.job_id' => $jobId,
                'ScheduleEntry.start_date' => $start,
                'ScheduleEntry.end_date' => $end,
                'ScheduleEntry.position' => $type,
                'ScheduleEntry.type' => 'scheduling',
                'OR' => array(
                    'ScheduleEntry.approved' => "1",
                    'ScheduleEntry.approved is null'
                )
            )));
            
            if(!empty($entry))
            {
                $return = $entry['ScheduleEntry']['id'];
            }
            else
            {
                // Check for other entry in the same range
                $answer = $this->_canSchedule($userId, $start, $end, $jobId);
                if(!$answer)
                    $return = "error";
            }
                
           
            
            return $return;
        }
        public function ajax_scheduleEmployees($id = null, $options=null) 
        {
           
            $opts = array();
            $test = $options;
            if(isset($options))
            {
            $options = explode("&", $options);
            
            foreach($options as $i => $opt)
            {
                $temp = explode("=", $opt);
                if(substr($temp[0], 0, 9) != 'abilities')
                    $opts[$temp[0]] = $temp[1];
                else
                    $opts['abilities'][] = substr($temp[0],10);
            } 
            
            }
            
            $this->layout = 'ajax';
            $job = $this->Job->findById($id);
            
            if(!isset($id))
                $employeeList = $this->User->find('all', array('conditions' => array('NOT' => array('User.is_active' => 'false'))));
            else
            {
                if(isset($opts['abilities']) && !empty($opts['abilities'])){
                    $employeeList = $this->User->find('all', array(
                        'fields' => array(
                            'DISTINCT User.id',
                            'User.first_name', 'User.last_name',
                            'User.city', 'User.state','User.zip',
                            'User.scheduling_admin_notes', 'User.scheduling_employee_notes'
                        ),
                        'joins' => array(array(
                            'table' => 'user_abilities',
                            'alias' => 'UserAbility',
                            'type' => 'INNER',
                            'conditions' => array('User.id = UserAbility.user_id'),
                            'limit' => 1
                        )),
                        'conditions'=> array(
                            'NOT' => array('User.is_active'=>'false'),
                        'UserAbility.ability_id' => $opts['abilities']
                            
                    )));
                }
                else
                    $employeeList = $this->User->find('all', array('conditions'=> array('NOT' => array('User.is_active' => 'false'))));
                
            }
            
            if(isset($opts['distance']) && !empty($opts['distance']))
                $employeeList = $this->_distanceFromJob($id, $employeeList, $opts['distance']);
            else
                $employeeList = $this->_distanceFromJob($id, $employeeList);
            
            if(isset($opts['start']) && !empty($opts['start']) && $opts['start'] != 'null')
                $start =  date('Y-m-d', $opts['start']/1000 + 86400);
            else
                $start = date('Y-m-d');
            
            if(isset($opts['end']) && !empty($opts['end']) && $opts['end'] != 'null')
                $end =  date('Y-m-d', $opts['end']/1000 + 86400);
            else
                $end = $start;
            
            if(!$this->config['scheduler.override']) {
            $employeeList = $this->_removeConflicts($employeeList, $start, $end);
            }
 
            $return = array('draw' => 1, 'recordsTotal' => sizeof($employeeList), 'recordsFiltered' => sizeof($employeeList),
                'data' => array());
                    foreach($employeeList as $emp)
                        
            {           
                        
                    $schedulingNotes = "<strong>Admin: <br/></strong>" . $emp['User']['scheduling_admin_notes'] .
                            "<br /><strong>Employee: <br /></strong>" . $emp['User']['scheduling_employee_notes'];
                    $abilitiesList = "{None Listed}";
                    if(isset($emp['Ability']) && !empty($emp['Ability'])){ 
                        $abilitiesList = "";
                    $count = 0;
                    foreach($emp['Ability'] as $ability)
                    {
                        if($count > 0)
                            $abilitiesList .= ", ";
                        
                        $count++;
                        $abilitiesList .= $ability['name'];
                    }
                    
                    
            } 

                $return['data'][] = array('first' => $emp['User']['first_name'], 'last' => $emp['User']['last_name'],
                    'location' => $emp['User']['city'] . ", " . $emp['User']['state'], 'distance' => (isset($emp['distance']) ? substr($emp['distance'],0,-3) : "999999"), 'score' => $this->_calculateScore($emp['User']['id'], $start), 'abilities' => $abilitiesList, 'notes' => $schedulingNotes,
                    'team-leader' => '<a onclick="teamLeaderAdd(this);" data-id="' . $emp['User']['id'] . '" class="addLeader" href="#"><i class="fa fa-plus-circle"></i></a>',
                    'employee' => '<a onclick="employeeAdd(this);" data-id="' . $emp['User']['id'] . '" class="addEmployee" href="#"><i class="fa fa-plus-circle"></i></a>');
                       
                    }
                    echo json_encode($return);
             exit();
        }
        private function _removeConflicts($list, $start, $end)
        {
            // If override is active, this will not remove conflicting users.
            if($this->config['scheduler.override'])
                return $list;
            
            foreach($list as $i => $user)
            {
                foreach($user['ScheduleEntry'] as $entry)
                {
                     
                    if(!(strtotime($entry['start_date']) > strtotime($end . " -1 day") || 
                            strtotime($entry['end_date']) < strtotime($start)
                            ))
                    {
                        // overlap
                       
                        if(($entry['approved'] != "0" && $entry['type'] == 'scheduling') || ($entry['approved'] == "1" && $entry['type'] != 'scheduling'))
                        {
                            // not a denied record - must remove overlap
                       
                        unset($list[$i]);
                        }
                    }
                        
                }
            }
            
            return($list);
        }
        
        function admin_score($id, $start)
        {
            echo $this->_calculateScore($id, $start);
            exit();
        }
        private function _calculateScore($id, $start)
        {
            $this->ScheduleEntry->unbindModel(array('belongsTo' => array(
                'Job', 'User'
            )));
            $schedule = $this->ScheduleEntry->find('all', array('conditions' => array(
                'ScheduleEntry.user_id' => $id,
                'ScheduleEntry.start_date >=' => date('Y-m-d H:i:s', strtotime($start . " -2 month")),
                'ScheduleEntry.type' => 'scheduling',
                'ScheduleEntry.start_date <=' => date('Y-m-d H:i:s', strtotime($start))
            )));
           $score = 0;
            foreach($schedule as $entry)
            {
                $diff = date_diff(date_create(date('Y-m-d',strtotime($entry['ScheduleEntry']['start_date']))), date_create($start));
                
                if($diff->days <= 28)
                    $score++;
                if($diff->days <= 21)
                    $score++;
                if($diff->days <= 14)
                    $score++;
                if($diff->days <= 7)
                    $score++;
                
            }
            return $score;
           
        }
	public function admin_toggleWeekends() {
		if($this->config['scheduler.show_weekends'] == 'false')
			$this->Config->saveValue('scheduler.show_weekends', 'true');
		else
			$this->Config->saveValue('scheduler.show_weekends', 'false');


		$this->redirect('/admin/jobs/scheduler');
	}
        
        public function admin_scheduler() {
            $override = $this->config['scheduler.override'] == 0 ? 'off' : 'on';
	    $showWeekends = $this->config['scheduler.show_weekends'];
	
           $this->set('dataOverride', $override);
		$this->set('showWeekends', $showWeekends);
            $this->set('setColors', [
        "training" => 'black',
        "certification"=> 'darkgreen',
        "accreditation"=> 'saddlebrown',
        "other" => 'darkslategray'
    ]);
$this->set('pendingColors', [
        "training" => 'blue',
        "certification"=> 'blueviolet',
        "accreditation"=> 'brown',
        "other" => 'cadetblue'
    ]);
	$this->Customer->unbindModel(array('hasMany' => array('Job')));
	$this->Job->unbindModel(array('hasMany' => array('JobTaskList')));
            $openJobs = $this->Job->find('all', array('recursive' => 2,  'conditions' => array(
                'review_start' => null,
                'start_date' => null,
                'end_date' => null
            )));
           set_time_limit(0);
            foreach($openJobs as  $i => $job) {
                unset($openJobs[$i]['Customer']['Jobs']);
            }
            $scheduledJobs = $this->Job->find('all', array('recursive' => 2, 'order' => 'Job.start_date ASC', 'conditions'=> array(
                'review_start' => null,
		'start_date >=' => date('Y-m-d H:i:s', strtotime("-8 weeks")),
                'NOT' => array(
                    'start_date' => null,
                    'end_date' => null
                )
            )));
	
            
            $this->set('jobs', array('open' => $openJobs, 'set' => $scheduledJobs));
            $this->loadModel('Ability');
            $this->set('abilities', $this->Ability->find('list'));
            
        }
        
        public function admin_showById($id){
            pr($this->Job->find('first', array('conditions' => array('Job.id' => $id), 'recursive' => 3)));
            exit();
        }
    
        public function admin_delete($id)
        {
            $this->Job->delete($id);
            $this->ScheduleEntry->deleteAll(array('ScheduleEntry.job_id' => $id));
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
               $this->_saveList($id, $job['SchedulerTaskList'], 'scheduler');
               
               // same for team leader(s)
               for($i = 0; $i < $job['team_leader_count']; $i ++)
                    $this->_saveList($id, $job['TeamLeaderTaskList'], 'team_leader');
               
               // same for schedulers
               for($i = 0; $i < $job['employee_count']; $i++)
                    $this->_saveList($id, $job['EmployeeTaskList'], 'employee');
                
               $this->Session->setFlash('New Job Created Successfully!', 'flash_success');
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
   
        
        private function _saveList($jobId,$templateId, $type)
        {
            $newTaskList = $this->TaskListTemplate->find('first', array('conditions' => array('id' => $templateId),'recursive' => 0));
               
               $newList = array('name' => $newTaskList['TaskListTemplate']['name'],
                   'job_id' => $jobId,
                   'type' => $type,
                   'description' => $newTaskList['TaskListTemplate']['description']);
               
               $this->JobTaskList->create();
               $this->JobTaskList->save($newList);
               
               $id = $this->JobTaskList->id;
               
               // Add all items to the new list
               $this->loadModel('TaskListTemplateItem');
               
               $taskItems = $this->TaskListTemplateItem->find('all', array('conditions' => array(
                   'task_list_template_id' => $templateId
               )));
               
               foreach($taskItems as $item)
               {
                   $save = array('job_task_list_id' => $id, 'task_item_id' => $item['TaskListTemplateItem']['task_item_id'],
                      'sort_order' => $item['TaskListTemplateItem']['sort_order'] );
                   $this->JobTaskListItem->create();
                   $this->JobTaskListItem->save($save);
               }
        }
        
         private function _updateList($jobId,$templateId, $type, $oldTemplateId)
        {
             // find an old Task List to change
             $oldTaskList = $this->TaskListTemplate->find('first', array('conditions' => 
                 array('id' => $oldTemplateId),'recursive' => 0));
            
             $oldList = $this->JobTaskList->find('first', array(
                 'conditions' => array(
                     'job_id' => $jobId,
                     'type' => $type,
                     'name' => $oldTaskList['TaskListTemplate']['name']
                 )
             ));
             if(!empty($oldList)) {
             $this->JobTaskListItem->deleteAll(array(
                 'job_task_list_id' => $oldList['JobTaskList']['id']
             ));
             }
             
            $newTaskList = $this->TaskListTemplate->find('first', array('conditions' => array('id' => $templateId),'recursive' => 0));
               
            if(!empty($oldList)) {
               $newList = array(
                   'id' => $oldList['JobTaskList']['id'],
                   'name' => $newTaskList['TaskListTemplate']['name'],
                   'job_id' => $jobId,
                   'type' => $type,
                   'description' => $newTaskList['TaskListTemplate']['description']);
               
               $this->JobTaskList->save($newList);
            }
            else
            {
                 $newList = array(
                   'name' => $newTaskList['TaskListTemplate']['name'],
                   'job_id' => $jobId,
                   'type' => $type,
                   'description' => $newTaskList['TaskListTemplate']['description']);
               
                 $this->JobTaskList->create();
               $this->JobTaskList->save($newList);
            }
               
               $id = $this->JobTaskList->id;
               
               // Add all items to the new list
               $this->loadModel('TaskListTemplateItem');
               
               $taskItems = $this->TaskListTemplateItem->find('all', array('conditions' => array(
                   'task_list_template_id' => $templateId
               )));
               
               foreach($taskItems as $item)
               {
                   $save = array('job_task_list_id' => $id, 'task_item_id' => $item['TaskListTemplateItem']['task_item_id'],
                      'sort_order' => $item['TaskListTemplateItem']['sort_order'] );
                   $this->JobTaskListItem->create();
                   $this->JobTaskListItem->save($save);
               }
        }
        public function admin_index($past = null) {
            $this->loadModel('ServiceArea');
            $areas = $this->ServiceArea->find('all', array(
               
            ));
            $serviceAreas = array();
            foreach($areas as $a)
            {
                $serviceAreas[$a['ServiceArea']['id']] = $a['ServiceArea']['name'];
                
                      $serviceAreas[$a['ServiceArea']['id']] .=
                              empty($a['Parent']['name']) ? "" : " (" . $a['Parent']['name'] . ")";
            }
            $this->set('serviceArea', $serviceAreas);
            if(isset($past)) {
                $this->set('jobs', $this->Job->find('all', array('conditions' => array(
                    'Job.end_date <' => date('Y-m-d'),
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
                    'Job.end_date >=' => date('Y-m-d'),
                        'Job.end_date' => "0000-00-00",
                        'Job.end_date' => NULL
                    )
                ),
                    'order' => 'Customer.full_name ASC')));
            $this->set('past', 'Open ');
            }
            
        }
        
        public function admin_edit($id = null) {
             
            if(!empty($this->request->data))
            {
                
                // get submitted info
                $job = $this->request->data['Job'];
                $oldJob = $this->Job->findById($job['id']);
                
                // set defaults based on info
                $cust = $this->Customer->findById($job['customer_id']);
                $job['company_name'] = $cust['Customer']['name'];
                $job['full_name'] = $job['company_name'] . ":" . $job['name'];
                $job['total_balance'] = $job['balance'];
                $job['job_status'] = 'InProgress';
                $job['description'] = $job['notes'];
                $job['job_type_id'] = $job['job_type'] = "";
                
                // save job and use id for other creations
               
                $this->Job->save($job);
                
                $id = $this->Job->id;
                
                // update task list based on the template only if different
                if($oldJob['Job']['SchedulerTaskList'] !== $job['SchedulerTaskList'])
                    $this->_updateList($id, $job['SchedulerTaskList'], 'scheduler', $oldJob['Job']['SchedulerTaskList']);
               
               // same for team leader(s)
               for($i = 0; $i < $job['team_leader_count']; $i ++)
               {
                   if($oldJob['Job']['TeamLeaderTaskList'] !== $job['TeamLeaderTaskList'])
                    $this->_updateList($id, $job['TeamLeaderTaskList'], 'team_leader', $oldJob['Job']['TeamLeaderTaskList']);
               }
               
               // same for schedulers
               for($i = 0; $i < $job['employee_count']; $i++)
               {
                   if($oldJob['Job']['EmployeeTaskList'] !== $job['EmployeeTaskList'])
                    $this->_updateList($id, $job['EmployeeTaskList'], 'employee', $oldJob['Job']['EmployeeTaskList']);
               }
                
               $this->Session->setFlash('Job Successfully Updated!', 'flash_success');
               $this->redirect('/admin/jobs');
            }
            
            $this->data = $this->Job->findById($id);
            $jobTaskLists = $this->JobTaskList->find('all', array('conditions' => array(
                'job_id' => $id
            )));
            

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
