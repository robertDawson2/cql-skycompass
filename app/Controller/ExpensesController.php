<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    App::uses('AppController', 'Controller');
   
    
    class ExpensesController extends AppController {

    	
        public $uses = array('TimeEntry','User', 'Customer', 'Bill', 'BillItem','BillExpense');
        
        public function beforeFilter() {
            parent::beforeFilter();
          
        }
          
        function admin_showBill($id)
        {
            pr($this->Bill->findById($id));
            exit();
        }
        
        function generateBills($hash = null)
        {
            if($hash !== '4naer9ANDJ83ana3uEJNe33801')
                exit('not authorized');
            
            $approvedBills = $this->BillItem->find('all', array(
                'conditions' => array(
                    'BillItem.approved' => 1,
                    'BillItem.bill_id' => NULL                
            ),
                'order' => array(
                    'BillItem.vendor_id' => 'ASC',
                    'BillItem.txn_date' => 'ASC'
                )));
            
            // Create organized array with all info needed for QB export
            $bill_data_array = array();
            $id = null;
            foreach($approvedBills as $i => $a)
            {
                if(!isset($bill_data_array[$a['BillItem']['vendor_id']])) {
                $bill_data_array[$a['BillItem']['vendor_id']] = array(
                    'Bill' => array(
                        'id' => sha1('BILL'. $i .time()),
                        'vendor_id' => $a['BillItem']['vendor_id'],
                        'customer_id' => $a['Customer']['id'],
                        'ref_number' => "",
                        'txn_date' => date("Y-m-d H:i:s"),
                        'amount_due' => 0.00,
                        'terms_id' => "",
                        'memo' => "",
                        'is_paid' => "false"
                    )
                );
                
                $this->Bill->create();
                $this->Bill->save($bill_data_array[$a['BillItem']['vendor_id']]);
                
                $id = $this->Bill->id;
                
                
                
                }
                $this->BillItem->id = $a['BillItem']['id'];
                $this->BillItem->saveField('bill_id', $id);
            }
            
            foreach($bill_data_array as $i => $a)
                $this->_queueToSave($a['Bill']['id']);
            
            
            pr($bill_data_array);
            exit('done');
            
        }
        function admin_chooseExpenseCustomer()
        {
            
            if(!empty($this->request->data))
            {
                $customer = $this->request->data['TimeEntry']['customer_id'];
                $this->redirect("/admin/expenses/add/" . $customer);
                
            }
        }
        
        function admin_add()
        {
           
            $classes = $this->_loadClasses();
            $this->set('classes', $classes);
            
            $customers = $this->_loadCustomers();
            $this->set('customers', $customers);
            
            $services = $this->_loadServices();
            $this->set('services', $services);
            
            if(!empty($this->request->data))
            {
                
                $user = $this->Auth->user();
                if(!isset($user['Vendor']) || empty($user['Vendor']))
                {
                    $this->Session->setFlash('User does not have a linked vendor account. Please contact an administrator.', 'flash_error');
                    $this->redirect('/admin');
                    exit();
                }
                
                $newRecord = $this->request->data;
                $newRecord['BillItem']['txn_date'] = date('Y-m-d H:i:s', strtotime($newRecord['BillItem']['txn_date']));
                $newRecord['BillItem']['quantity'] = 1;
                $newRecord['BillItem']['cost'] = $newRecord['BillItem']['amount'];
                $newRecord['BillItem']['vendor_id'] = $user['Vendor']['id'];
                
                // upload the image - fail if it does not upload
                $target_dir = WWW_ROOT . "files/uploads/";
                $imageFileType = pathinfo($newRecord['Image']['image']['name'],PATHINFO_EXTENSION);
$target_file = sha1($newRecord['Image']['image']['name'] . time()) . "." . $imageFileType ;

$uploadOk = 1;


    // Check if file already exists
if (file_exists($target_file)) {
    $this->data = $newRecord;
    $this->Session->setFlash('File already exists!!', 'flash_error');
                   
    $uploadOk = 0;
} 


if($uploadOk) {
    
    if(empty($imageFileType) || move_uploaded_file($newRecord['Image']['image']['tmp_name'], $target_dir . $target_file)) {
        if(empty($imageFileType))
            $newRecord['BillItem']['image'] = "";
        else
            $newRecord['BillItem']['image'] = $target_file;
        
        $newRecord['BillItem']['id'] = $user['Vendor']['id'] . time();
        
        $this->BillItem->create();
        if($this->BillItem->save($newRecord))
        {
            $this->Notification->queueNotification('admin', 'Admin_ExpenseApprove', '/admin/expenses/approve', 'Approval Needed', '%i expenses queued for approval.');
             $this->Session->setFlash('Bill item has been saved and sent for approval.', 'flash_success');
                    $this->redirect('/admin/expenses/add');
                    exit();
        }
        else
        {
            $this->data = $newRecord;
            $this->Session->setFlash('An error occurred saving your record', 'flash_error');
        }
        
    }
    else
    {
        $this->data = $newRecord;
         $this->Session->setFlash('There was an issue uploading the file.', 'flash_error');
                   
    }
}
    

            }
            
        }
        
        function admin_edit($id = null)
        {
           
            $classes = $this->_loadClasses();
            $this->set('classes', $classes);
            
            $customers = $this->_loadCustomers();
            $this->set('customers', $customers);
            
            $services = $this->_loadServices();
            $this->set('services', $services);
            
            
            
            if(!empty($this->request->data))
            {
                $newRecord = $this->request->data;
                $newRecord['BillItem']['txn_date'] = date('Y-m-d H:i:s', strtotime($newRecord['BillItem']['txn_date']));
                $newRecord['BillItem']['approved'] = null;
                $newRecord['BillItem']['bill_id'] = null;
                $newRecord['BillItem']['cost'] = $newRecord['BillItem']['amount'];
               
                
                // upload the image - fail if it does not upload
                $target_dir = WWW_ROOT . "files/uploads/";
                $imageFileType = pathinfo($newRecord['Image']['image']['name'],PATHINFO_EXTENSION);
$target_file = sha1($newRecord['Image']['image']['name'] . time()) . "." . $imageFileType ;

$uploadOk = 1;


    // Check if file already exists
if (file_exists($target_file)) {
    $this->data = $newRecord;
    $this->Session->setFlash('File already exists!!', 'flash_error');
                   
    $uploadOk = 0;
} 


if($uploadOk) {
    
    if(empty($imageFileType) || move_uploaded_file($newRecord['Image']['image']['tmp_name'], $target_dir . $target_file)) {
        if(!empty($imageFileType))
            $newRecord['BillItem']['image'] = $target_file;
        

        if($this->BillItem->save($newRecord))
        {
             $this->Session->setFlash('Bill item has been saved and sent for approval.', 'flash_success');
             $this->Notification->queueNotification('admin', 'Admin_ExpenseApprove', '/admin/expenses/approve', 'Approval Needed', '%i expenses queued for approval.');
                    $this->redirect('/admin/expenses/viewMyExpenses');
                    exit();
        }
        else
        {
           
            $this->Session->setFlash('An error occurred saving your record', 'flash_error');
        }
        
    }
    else
    {
        $this->data = $newRecord;
         $this->Session->setFlash('There was an issue uploading the file.', 'flash_error');
                   
    }
}
    

            }
            
            $current = $this->BillItem->findById($id);
            $current['BillItem']['txn_date'] = date("m/d/Y", strtotime($current['BillItem']['txn_date']));
            $this->data = $current;
            
        }
        
        function admin_viewMyExpenses()
        {
            $id = $this->Auth->user()['Vendor']['id'];
            
            $timeEntries = $this->BillItem->find('all', array('conditions' => array(
                'BillItem.vendor_id' => $id
            )));
            
            $this->set('expenses', $timeEntries);
            $this->set('user', $this->Auth->user());
        }
        
        function admin_delete($id = null)
        {
            if(isset($id) && $id !== null)
            {
                $this->BillItem->id = $id;
                if($this->BillItem->delete())
                {
                    $this->Session->setFlash('Expense entry has been removed.', 'flash_success');
                }
                else
                {
                    $this->Session->setFlash('An error occurred while removing the record.', 'flash_error');
                }
                $this->redirect('/admin/expenses/viewMyExpenses');
                exit();
            }
            else
            {
                 $this->Session->setFlash('An error occurred while removing the record.', 'flash_error');
                $this->redirect('/admin/expenses/viewMyExpenses');
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
                    $entry = array('BillItem' => array(
                        'id' => $i,
                        'approved' => $approve,
                        'billable' => $d['billable']
                    ));
                    
                    if(!$this->BillItem->saveMany($entry))
                    {
                        $error = true;
                    }
                    else
                    {
                        // Only need this if we were uploading always - only generating bills on pay switch
//                        if($approve)
//                            $this->_queueToSave($i);
                        
                        $timeEntry = $this->BillItem->findById($i);
                        
                        $this->loadModel('User');
                        $user = $this->User->find('first', array('conditions'=>array(
                            'User.vendor_id' =>$timeEntry['BillItem']['vendor_id']
                        )));
                        if($approve == 1)
                            $this->Notification->queueNotification($user['User']['id'],'ExpenseAdd','/admin/expenses/viewMyExpenses','Expense Approved','%i expenses were approved');
                        else
                            $this->Notification->queueNotification($user['User']['id'],'ExpenseDeny','/admin/expenses/viewMyExpenses','Expense Denied','%i expenses were denied');
                    }
                }
                
                    
            }
            
            if(!$error)
            {
                if($approve == 1)
                            $this->Session->setFlash('All selected expenses set for approval','flash_success');
                        else
                            $this->Session->setFlash('All selected expenses set for denial','flash_success');
                    }
                    else
                    {
                        $this->Session->setFlash('An error occurred while saving your request.', 'flash_error');
                    }
            
            
            
            }
            $this->User->unbindModel(array('hasMany' => array('Bill', 'TimeEntry', 'Notification')));
            $this->loadModel('Vendor');
            $this->Vendor->unbindModel(array('hasMany' => array('Bill')));
            $entries = $this->BillItem->find('all', array('conditions'=>array(), 'recursive' => 3,
                'order' => 'BillItem.txn_date ASC'));
            foreach($entries as $i => $entry) 
            {
                $allowed = false;
                foreach($entry['Vendor']['User']['ApprovalManager'] as $manager)
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
	$Queue->enqueue(QUICKBOOKS_ADD_BILL, $id);
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
