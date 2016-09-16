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
            $this->Auth->allow('generateBills');
          
        }
          
        function admin_showBill($id)
        {
            pr($this->Bill->findById($id));
            exit();
        }
        function admin_ajaxUploadReceipts() 
        {
            $this->layout = "ajax";
            if(!empty($_FILES))
            {
                foreach($_FILES['files']['name'] as $i =>  $filename)
                {
                    $target_dir = WWW_ROOT . "files/uploads/";
                $imageFileType = pathinfo($filename,PATHINFO_EXTENSION);

$uploadOk = 1;


    // Check if file already exists
if (file_exists($filename)) {
            
    $uploadOk = 0;
} 


if($uploadOk) {
    
    if(move_uploaded_file($_FILES['files']['tmp_name'][$i], $target_dir . $filename)) {
        $this->_shrinkReceipt($filename, $target_dir);
        
            }
            else
            {
            
            exit('done with errors.');
            }
            
        }
                }
        
        exit('done');
            }
            else
            {
                exit('no files');
            }
        }
        
        private function _shrinkReceipt($filename, $path)
        {
            $target_dir = $path . $filename;
            $fileparts = pathinfo($target_dir);
		$image = null;
		switch($fileparts['extension'])
		{
			case "jpg":
			$image = imagecreatefromjpeg($target_dir);
			break;
			
			case "png":
			$image = imagecreatefrompng($target_dir);
			break;

			case null:
			break;

		}
		
                if(isset($image) && $image != null) {
		$ratio = 500 / imagesx($image);
		$height = imagesy($image) * $ratio;
		$new_image = imagecreatetruecolor(500, $height);
		imagecopyresampled($new_image, $image, 0, 0, 0, 0, 500, $height, imagesx($image), imagesy($image));
		$image = $new_image; // $image has now been replaced with the resized one.
		imagejpeg($image,$target_dir, 90);
		unset($new_image);
    unset($image);
                }
   
                return true;
        }
        function admin_travelSheet()
        {
            
            if(!empty($this->request->data))
            {
                $error = array();
                $toSave = array();
              //  pr($this->request->data);
                $data = $this->request->data;
                $billItem = $data['BillItem'];
                $customer = $this->Customer->findById($billItem['customer_id']);
                $priorDesc = $billItem['description'];
                $baseDescription = "**" . $billItem['dest'] . " ::: " . $customer['Customer']['full_name'] . " ::: " . 
                        $billItem['depart_date'] . " - " . $billItem['return_date'] . "\n" . $priorDesc . "**\n";
                $billItem['description'] = $baseDescription;
                $billItem['txn_date'] = date('Y-m-d H:i:s', strtotime($billItem['depart_date']));
               
                $billItem['quantity'] = 1;
                $user = $this->Auth->user();
                $billItem['vendor_id'] = $user['Vendor']['id'];
                
                //break down corporate items
                if(isset($data['corporate']))
                {
                    
                    foreach($data['corporate'] as $corp)
                    {
                        $corporateItem = $billItem;
                        $corporateItem['company_cc_item'] = 1;
                        $corporateItem['txn_date'] = date('Y-m-d H:i:s', strtotime($corp['date']));
                        $corporateItem['id'] = $user['Vendor']['id'] . time() . rand(0,1000);
                        $corporateItem['cost'] = $corp['amount'];
                        $corporateItem['amount'] = $corp['amount'];
                        // Add description, don't overwrite
                        $corporateItem['description'] .= $corp['note'];
                        $corporateItem['image'] = $corp['receipt'];
                        $corporateItem['item_id'] = $corp['type'];
                        
                        // All fields set, lets save the little feller.
                        $this->BillItem->create($corporateItem);
                        if($this->BillItem->validates($corporateItem))
                            $toSave[] = $corporateItem;
                        else
                            $error[] = "Corporate Item";
                        
                    }
                }
                
                
                
               
                // Break down meal per diem
                if(isset($data['meals']) && !empty($data['meals']))
                {
                $upcharge = $data['meals']['nyc'] * $this->config['expenses.nyc_quarters_upcharge'];
                $quarterCost = $this->config['expenses.quarters'] + $upcharge;
                $quarters = 0;
                unset($data['meals']['nyc']);
                
                //if there are no meals, ['meals'] should be empty
                if(!empty($data['meals']))
                {
                    foreach($data['meals'] as $meal)
                    {
                        foreach($meal as $m => $val)
                        if($m == 'breakfast' || $m == 'lunch')
                            $quarters++;
                        else
                            $quarters += 2;
                    }
                    
                    $total = $quarterCost * $quarters;
                    
                    // Only create a bill if total > 0, obvi...
                    if($total > 0)
                    {
                        $mealItem = $billItem;
                        $mealItem['id'] = $user['Vendor']['id'] . time() . rand(0,1000);
                        $mealItem['quantity'] = $quarters;
                        $mealItem['cost'] = $quarterCost;
                        $mealItem['amount'] = $total;
                        $mealItem['description'] .= "Meal Per Diem";
                        $mealItem['item_id'] = $this->config['expenses.meals'];
                        $mealItem['image'] = "";
                        
                        // this will set all fields, so let's save the meal item!
                        
                       // Don't create - id's are not sequential, so it can't generate - id is gnerated above
                       //  $this->BillItem->create();
                       $this->BillItem->create($mealItem);
                        if($this->BillItem->validates($mealItem))
                            $toSave[] = $mealItem;
                        else
                            $error[] = "Meal Item";
                        
                        
                    }
                }
                }
                
                // Break down and submit transportation items
                if(isset($data['trans']) && !empty($data['trans']))
                {
                    foreach($data['trans'] as $trans)
                    {
                    $transItem = $billItem;
                    $transItem['id'] = $user['Vendor']['id'] . time() . rand(0,1000);
                        $transItem['cost'] = $this->config['expenses.mileage'];
                        $transItem['amount'] = $trans['amount'];
                        // Add description, don't overwrite
                        $transItem['description'] .= $trans['taxi-car'] . ": " . $trans['from'] . " => " . $trans['to'];
                        $transItem['quantity'] = $trans['mileage'];
                        $transItem['image'] = $trans['receipt'];
                        $transItem['item_id'] = $this->config['expenses.mileage_item'];
                        $transItem['txn_date'] = date('Y-m-d H:i:s', strtotime($trans['date']));
                        
                       // Validate and queue for later saving, and save error otherwise.
                        
                        $this->BillItem->create($transItem);
                        if($this->BillItem->validates($transItem))
                            $toSave[] = $transItem;
                        else
                            $error[] = "Transportation Item";
                        
                            
                        
                    
                            
                    }
                }
                
                //Finally we take care of "other" items
                if(isset($data['other']) && !empty($data['other']))
                {
                    foreach($data['other'] as $trans)
                    {
                    $otherItem = $billItem;
                    $otherItem['id'] = $user['Vendor']['id'] . time() . rand(0,1000);
                        $otherItem['cost'] = $trans['amount'];
                        $otherItem['amount'] = $trans['amount'];
                        // Add description, don't overwrite
                        $otherItem['description'] .= $trans['note'];
                        $otherItem['quantity'] = 1;
                        $otherItem['image'] = $trans['receipt'];
                        $otherItem['item_id'] = $trans['type'];
                        $otherItem['txn_date'] = date('Y-m-d H:i:s', strtotime($trans['date']));
                        
                        // And then, obvi, we save. And we save HARD.
                        $this->BillItem->create($otherItem);
                        if($this->BillItem->validates($otherItem))
                            $toSave[] = $otherItem;
                        else
                            $error[] = "Other Item";
                        
                    
                            
                    }
                }
               
                
                // Check validation, and save each item if all validated
                // Return an error message and don't save anything if there is an error.
                if(empty($error))
                {
                    foreach($toSave as $s)
                    {
                        $this->BillItem->save($s);
                    }
                    $this->Session->setFlash('All items successfully saved for approval.', 'flash_success');
                    
                }
                else
                {
                    $message = "Your form could not be saved there were errors with one or more entries in the following sections: <br />";
                    foreach($error as $e)
                        $message .= $e . "<br />";
                    
                    $message .= "If this problem persists, please contact an administrator.";
                    
                    $this->Session->setFlash($message, 'flash_error');
                    
                    $this->redirect('/admin/expenses/travelSheet');
                }
                
               // exit();
                
            }
            $this->set('mileage', $this->config['expenses.mileage']);
            $classes = $this->_loadClasses();
            $this->set('classes', $classes);
            
            $customers = $this->_loadCustomers();
            $this->set('customers', $customers);
            
            $services = $this->_loadServices();
            $this->set('services', $services);
            
            
        }
        
        function generateBills($hash = null)
        {
            if($hash !== '4naer9ANDJ83ana3uEJNe33801')
                exit('not authorized');
            
            $approvedBills = $this->BillItem->find('all', array(
                'conditions' => array(
                    'BillItem.super_approved' => 1,
                    'BillItem.approved' => 1,
                    'BillItem.bill_id' => NULL,
                    'BillItem.company_cc_item' => 0
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
            
            
          //  pr($bill_data_array);
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
        
        $this->_shrinkReceipt($target_file, $target_dir);
        
        $newRecord['BillItem']['id'] = $user['Vendor']['id'] . time();
        
        $this->BillItem->create();
        if($this->BillItem->save($newRecord))
        {
            
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
                 $newRecord['BillItem']['super_approved'] = null;
               
                
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
            $entries = $this->BillItem->find('all', array('conditions'=>array('BillItem.approved'=>null, 'BillItem.bill_id'=>null), 'recursive' => 3,
                'order' => 'BillItem.txn_date ASC'));
            foreach($entries as $i => $entry) 
            {
                $allowed = false;
                if(isset($entry['Vendor']['User']['ApprovalManager']))
                foreach($entry['Vendor']['User']['ApprovalManager'] as $manager)
                    if($manager['manager_id'] == $this->Auth->user('id'))
                        $allowed = true;
                    
                    if(!$allowed)
                        unset($entries[$i]);
            }
           
            
            $this->set('entries',$entries);
            
            
        }
        
        function admin_superApprove() {
            if(!$this->Auth->user('super_user'))
                exit("You are not authorized to use this page.");
            
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
                        'super_approved' => $approve,
                        'approved' => $approve
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
                        if($approve == 0)
                            $this->Notification->queueNotification($user['User']['id'],'ExpenseDeny','/admin/expenses/viewMyExpenses','Expense Denied','%i expenses were denied by super user');
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
            $entries = $this->BillItem->find('all', array('conditions'=>array('BillItem.approved'=>'1', 'BillItem.super_approved' => null, 'BillItem.bill_id'=>null), 'recursive' => 3,
                'order' => 'BillItem.txn_date ASC'));
            
            
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
    $fields = get_class_vars('DATABASE_CONFIG');
    $db = $fields['production']['database'];
    $u = $fields['production']['login'];
    $p = $fields['production']['password'];
    
$dsn = 'mysql://' . $u . ':' . $p . '@localhost/' . $db;
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
