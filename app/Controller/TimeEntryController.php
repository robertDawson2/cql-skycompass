<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    App::uses('AppController', 'Controller');
   
    
    class TimeEntryController extends AppController {

    	public $name = 'TimeEntry';
        public $uses = array('TimeEntry','Notification', 'Config', 'User');
        
        public function beforeFilter() {
            parent::beforeFilter();
          
        }
           
        function admin_delete($id = null)
        {
            if(isset($id) && $id !== null)
            {
                $this->TimeEntry->id = $id;
                if($this->TimeEntry->delete())
                {
                    $this->Session->setFlash('Time Entry has been removed.', 'flash_success');
                }
                else
                {
                    $this->Session->setFlash('An error occurred while removing the record.', 'flash_error');
                }
                $this->redirect('/admin/timeEntry/viewMyTime');
                exit();
            }
            else
            {
                 $this->Session->setFlash('An error occurred while removing the record.', 'flash_error');
                $this->redirect('/admin/timeEntry/viewMyTime');
            }
        }
        function admin_viewMyTime()
        {
            $id = $this->Auth->user('id');
            $timeEntries = $this->TimeEntry->find('all', array('conditions' => array(
                'TimeEntry.user_id' => $id
            )));
            
            $this->set('timeEntries', $timeEntries);
            $this->set('user', $this->Auth->user());
        }
          
        
        public function beforeRender() {
            parent::beforeRender();
            $this->set('section', 'time');
            $this->layout = 'admin';
        }
        
        function admin_approve() {
            
            if(!empty($this->request->data))
            {
               
                $error = false;
                $approve = null;
                if($this->request->data['TimeEntry']['approveDeny'] === 'approve')
                    $approve = 1;
                else
                    $approve = 0;
                foreach($this->request->data['entries'] as $i => $d)
                {
                    if(isset($d['approved']) && $d['approved'] == 'on')
                    {
                    $entry = array('TimeEntry' => array(
                        'id' => $i,
                        'approved' => $approve,
                        'billable_status' => $d['billable_status']
                    ));
                    
                    if(!$this->TimeEntry->saveMany($entry))
                    {
                        $error = true;
                    }
                    else
                    {
                        if($approve)
                            $this->_queueToSave($i);
                        
                        $timeEntry = $this->TimeEntry->findById($i);
                        
                        if($approve == 1)
                            $this->Notification->queueNotification($timeEntry['TimeEntry']['user_id'],'TimeAdd','/admin/timeEntry/viewMyTime','Time Approved','%i time records were approved');
                        else
                            $this->Notification->queueNotification($timeEntry['TimeEntry']['user_id'],'TimeDeny','/admin/timeEntry/viewMyTime','Time Denied','%i time records were denied');
                    }
                }
                
                    
            }
            
            if(!$error)
            {
                if($approve == 1)
                            $this->Session->setFlash('All selected entries set for approval','flash_success');
                        else
                            $this->Session->setFlash('All selected entries set for denial','flash_success');
                    }
                    else
                    {
                        $this->Session->setFlash('An error occurred while saving your request.', 'flash_error');
                    }
            
            
            
            }
            $this->User->unbindModel(array('hasMany' => array('TimeEntry')));
            $entries = $this->TimeEntry->find('all', array('recursive' => 2, 'conditions'=>array('TimeEntry.approved'=>null),
                'order' => 'TimeEntry.txn_date ASC', 'contain' => array(
                    'User.ApprovalManager.manager_id = "'.$this->Auth->user('id').'"',
                    'Customer',
                    'User',
                    'Item')));
            foreach($entries as $i => $entry)
            {
                
                $allowed = false;
                foreach($entry['User']['ApprovalManager'] as $manager)
                    if($manager['manager_id'] == $this->Auth->user('id'))
                        $allowed = true;
                    
                    if(!$allowed)
                        unset($entries[$i]);
            }
            $this->set('entries',$entries);
        }
        
        private function _queueToSave($id)
        {
           require_once WWW_ROOT . '/QuickBooks/QuickBooks.php'; 
           $user = 'quickbooks';
            $pass = 'password';
            
            if($_SERVER['SERVER_ADDR'] == '127.0.0.1')
{
    $dsn = 'mysql://cql:St8!VwARH49pW#eh3P@localhost/cql';
}
else
{
$dsn = 'mysql://cqldev:St8!VwARH49pW#eh3P@localhost/cqldev';
}
//$dsn = 'mysql://testuser:testpassword@localhost/testdatabase';

/**
 * Constant for the connection string (because we'll use it in other places in the script)
 */

$Queue = new QuickBooks_WebConnector_Queue($dsn);
	$Queue->enqueue(QUICKBOOKS_ADD_TIMETRACKING, $id);
        }
        
        
        
        
        public function admin_weekly() {
            $payrolls = $this->_loadPayrollItems();
            $this->set('payrolls', $payrolls);
            
            $classes = $this->_loadClasses();
            $this->set('classes', $classes);
            
            $customers = $this->_loadCustomers();
            $this->set('customers', $customers);
            
            $services = $this->_loadServices();
            $this->set('services', $services);
            
            if(!empty($this->request->data))
            {
                $date = null;
                $daysArray = array(
                    0 => 'sunday',
                    1 => 'monday',
                    2 => 'tuesday',
                    3 => 'wednesday',
                    4 => 'thursday',
                    5 => 'friday',
                    6 => 'saturday'
                );
                $first = true;
                $noerrors = true;
                foreach($this->request->data as $i => $d)
                {
                  //  pr($this->request->data);
                    $newTimeEntry = array();
                    
                    echo ($i); echo "<BR>";
                    if($first)
                    {
                       $first=false;
                        pr($d);
                        $date = strtotime($d['datepicker']);
                        $date = strtotime('last sunday, 12am', $date);
                    }
                    else
                    {
                        
                        $this->loadModel('PayrollItem');
                $payrollItem = $this->PayrollItem->findById($d['TimeEntry']['payroll_item_id']);
                $payrollName = $payrollItem['PayrollItem']['name'];
                
                $this->loadModel('Classes');
                $classItem = $this->Classes->findById($d['TimeEntry']['class_id']);
                $className = $classItem['Classes']['name'];
                
                        $newTimeEntry = array(
                            'user_id' => $this->Auth->user('id'),
                            'customer_id' => $d['TimeEntry']['customer_id'],
                            'item_id' => $d['TimeEntry']['item_id'],
                            'txn_number' => 0,
                            'txn_date' => null,
                            'duration' => null,
                            'class_id' => $d['TimeEntry']['class_id'],
                            'payroll_item_id' => $d['TimeEntry']['payroll_item_id'],
                            'notes' => null,
                            'billable_status' => 'Billable',
                            'approved' => null,
                            'imported' => 0,
                            'is_billable' => "",
                            'is_billed' => "",
                            'class_name' => $className,
                            'payroll_item_name' => $payrollName,
                            'id' => null
                            
                        );
                        for($j=0; $j<7; $j++)
                        {
                            if($j=== 0)
                                $newTimeEntry['txn_date'] = date('Y-m-d', $date);
                            else if($j===1)
                                $newTimeEntry['txn_date'] = date('Y-m-d', strtotime('+'.$j.' day', $date));
                            else
                                $newTimeEntry['txn_date'] = date('Y-m-d', strtotime('+'.$j.' days', $date));
                                
                            $minutes = $d['TimeEntry'][$daysArray[$j]] * 60;
                            $hours = intval($minutes/60);
                            $minutes = $minutes - ($hours * 60);
                            $newTimeEntry['notes'] = $d['TimeEntry']['notes'];
                            $newTimeEntry['duration'] = "PT" . $hours . "H" . $minutes . "M";
                            $newTimeEntry['id'] = sha1(serialize($newTimeEntry) . time());
                            $newTimeEntry = $this->_checkRecord($newTimeEntry);
                            
                    if($minutes > 0 || $hours > 0)
                    {
                        $this->TimeEntry->create();
                        if(!$this->TimeEntry->save($newTimeEntry))
                            $noerrors = false;
                    }
                        }
                    }
                }
               
                if($noerrors)
                {
                    $this->Session->setFlash('All Time Entries have been logged for approval.', 'flash_success');
                   
                }
                else
                {
                    $this->Session->setFlash('An error occurred saving one or more of your entries. Please check your entries for accuracy.', 'flash_error');
                }
                 $this->redirect('/admin/timeEntry/viewMyTime');
                    
            }
        }
        public function admin_single() {
            $payrolls = $this->_loadPayrollItems();
            $this->set('payrolls', $payrolls);
            
            $classes = $this->_loadClasses();
            $this->set('classes', $classes);
            
            $customers = $this->_loadCustomers();
            $this->set('customers', $customers);
            
            $services = $this->_loadServices();
            $this->set('services', $services);
            
            if(!empty($this->request->data))
            {
                
                $newRecord = $this->request->data;
                $newRecord['TimeEntry']['duration'] = "PT" . $newRecord['TimeEntry']['hours'] .
                        "H" . $newRecord['TimeEntry']['minutes'] . "M";
                $newRecord['TimeEntry']['user_id'] = $this->Auth->user('id');
                
                $this->loadModel('PayrollItem');
                $payrollItem = $this->PayrollItem->findById($newRecord['TimeEntry']['payroll_item_id']);
                $newRecord['TimeEntry']['payroll_item_name'] = $payrollItem['PayrollItem']['name'];
                
                $this->loadModel('Classes');
                $classItem = $this->Classes->findById($newRecord['TimeEntry']['class_id']);
                $newRecord['TimeEntry']['class_name'] = $classItem['Classes']['name'];
                
                $newRecord['TimeEntry']['txn_date'] = date('Y-m-d', strtotime($this->request->data['TimeEntry']['txn_date']));
                $newRecord['TimeEntry']['approved'] = null;
                $newRecord['TimeEntry']['is_billed'] = "";
                $newRecord['TimeEntry']['is_billable'] = "";
                $newRecord['TimeEntry']['billable_status'] = "Billable";
                $newRecord['TimeEntry']['txn_number'] = 0;
                $newRecord['TimeEntry']['id'] = sha1(serialize($newRecord) . time());
                $newRecord['TimeEntry'] = $this->_checkRecord($newRecord['TimeEntry']);
                
                if($this->TimeEntry->save($newRecord))
                {
                    $this->Session->setFlash('Your time entry has been successfully logged for approval.', 'flash_success');
                    $this->Notification->queueNotification('admin', 'Admin_TimeApprove', '/admin/timeEntry/approve', 'Approval Needed', '%i records queued for approval.');
                    
                }
                else
                {
                    $this->Session->setFlash('There was an error processing your request. Please try again.', 'flash_error');
                }
                $this->redirect('/admin/timeEntry/single');
            }
            
        }
        
        
        private function _checkRecord($record = null)
        {
            $return = $record;
            
            // Check if service date falls before current pay period and 
            // if so, change the date to the first date of current pay period
            // with a note of the actual date of action
           
            $payrollStart = strtotime($this->config['admin.payroll_start']);
            $dateOfService = strtotime($record['txn_date']);
            
            if($dateOfService < $payrollStart)
            {
                $return['txn_date'] = $this->config['admin.payroll_start'];
                $return['notes'] .= "\n***BACKLOGGED TIME ENTRY***\nActual date of completed service: " . $record['txn_date'];
            }
            return $return;
            
        }
        
        public function admin_edit($id = null) {
            $payrolls = $this->_loadPayrollItems();
            $this->set('payrolls', $payrolls);
            
            $classes = $this->_loadClasses();
            $this->set('classes', $classes);
            
            $customers = $this->_loadCustomers();
            $this->set('customers', $customers);
            
            $services = $this->_loadServices();
            $this->set('services', $services);
            
            if(!empty($this->request->data))
            {
                
                $newRecord = $this->request->data;
                
                $newRecord['TimeEntry']['duration'] = "PT" . $newRecord['TimeEntry']['hours'] .
                        "H" . $newRecord['TimeEntry']['minutes'] . "M";
                $newRecord['TimeEntry']['user_id'] = $this->Auth->user('id');
                
                $this->loadModel('PayrollItem');
                $payrollItem = $this->PayrollItem->findById($newRecord['TimeEntry']['payroll_item_id']);
                $newRecord['TimeEntry']['payroll_item_name'] = $payrollItem['PayrollItem']['name'];
                
                $this->loadModel('Classes');
                $classItem = $this->Classes->findById($newRecord['TimeEntry']['class_id']);
                $newRecord['TimeEntry']['class_name'] = $classItem['Classes']['name'];
                
                $newRecord['TimeEntry']['txn_date'] = date('Y-m-d', strtotime($this->request->data['TimeEntry']['txn_date']));
                $newRecord['TimeEntry']['approved'] = null;
                $newRecord['TimeEntry']['is_billed'] = "";
                $newRecord['TimeEntry']['is_billable'] = "";
                $newRecord['TimeEntry']['billable_status'] = "Billable";
                $newRecord['TimeEntry']['txn_number'] = 0;
                
                $newRecord['TimeEntry'] = $this->_checkRecord($newRecord['TimeEntry']);
                $this->TimeEntry->id = $newRecord['TimeEntry']['id'];
                if($this->TimeEntry->saveMany($newRecord))
                {
                    $this->Session->setFlash('Your time entry has been successfully logged for approval.', 'flash_success');
                    $this->Notification->queueNotification('admin', 'Admin_TimeApprove', '/admin/timeEntry/approve', 'Approval Needed', '%i records queued for approval.');
                    
                }
                else
                {
                    $this->Session->setFlash('There was an error processing your request. Please try again.', 'flash_error');
                }
                $this->redirect('/admin/timeEntry/viewMyTime');
            }
            
            $timeEntry = $this->TimeEntry->findById($id);
            $duration = $timeEntry['TimeEntry']['duration'];
            $timeEntry['TimeEntry']['hours'] = substr($duration,2,strpos($duration,"H")-2);
            $timeEntry['TimeEntry']['minutes'] = substr($duration,strpos($duration,"H")+1,-1);
            $timeEntry['TimeEntry']['txn_date'] = date("m/d/Y", strtotime($timeEntry['TimeEntry']['txn_date']));
            $this->data = $timeEntry;
            $this->set('data', $timeEntry);
            
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
                        'Job.end_date' => "0000-00-00"
                    )
                ),
                    'order' => 'Customer.full_name ASC')));
            $this->set('past', 'Open ');
            }
            
        }
        
        function updateConfiguration($hash = null)
        {
            if($hash !== 'addnri232naa83alk3ASD3A3han3kqln3')
            {
                exit('NOT AUTHORIZED');
            }
            
            
            // if it is past the admin payroll cutoff, time to advance everything by 2 weeks
            if(time() > strtotime($this->config['admin.payroll_cutoff']))
            {
                $config = array(
                    'admin.payroll_start' => date('Y-m-d H:i:s', strtotime($this->config['admin.payroll_start'] . " +2 weeks")),
                    'admin.payroll_end' => date('Y-m-d H:i:s', strtotime($this->config['admin.payroll_end'] . " +2 weeks")),
                    'admin.payroll_cutoff' => date('Y-m-d H:i:s', strtotime($this->config['admin.payroll_cutoff'] . " +2 weeks")),
                    'admin.pay_date' => date('Y-m-d H:i:s', strtotime($this->config['admin.pay_date'] . " +2 weeks")),
                );
               
                foreach($config as $i => $c)
                {
                    
                    $this->Config->saveValue($i, $c);
                }
                
                exit('done');
            }
            
            exit('unnecessary');
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
