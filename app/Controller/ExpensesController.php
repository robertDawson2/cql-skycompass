<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    App::uses('AppController', 'Controller');
   
    
    class ExpensesController extends AppController {

    	
        public $uses = array('TimeEntry','User', 'Customer', 'Bill', 'BillItem','BillExpense', 'Approvallog');
        
        public function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow('generateBills');
          
        }
        
        function admin_approveMultiple($vendor, $customer, $dt)
                
        {
            
                $data = $this->BillItem->find('all', array('conditions' => array(
                        'BillItem.vendor_id' => $vendor,
                        'BillItem.customer_id' => $customer,
                        'BillItem.txn_date >' => date('Y-m-d H:i:s', strtotime($dt . " -2 weeks")),
                        'BillItem.txn_date <' => date('Y-m-d H:i:s', strtotime($dt . " +2 weeks")),
                        'BillItem.bill_id' => null,
                        'BillItem.approved' => null
                        
                )));
                
                foreach($data as $item)
                {
                    $this->BillItem->id = $item['BillItem']['id'];
                    $this->BillItem->saveField('approved', 1);
                    
                    $this->Approvallog->create();
                    $this->Approvallog->save(
                            array(
                                'approver' => $this->Auth->user('id'),
                                'bill_item' => $item['BillItem']['id'],
                                'value' => 1,
                                'super' => 0
                            ));
                }
                $this->Session->setFlash('The Selected Bill Has Been Approved.', 'flash_success');
                $this->redirect($this->referer());
        }
        
        function admin_superApproveMultiple($vendor, $customer, $dt)
                
        {
            
                $data = $this->BillItem->find('all', array('conditions' => array(
                        'BillItem.vendor_id' => $vendor,
                        'BillItem.customer_id' => $customer,
                        'BillItem.txn_date >' => date('Y-m-d H:i:s', strtotime($dt . " -2 weeks")),
                        'BillItem.txn_date <' => date('Y-m-d H:i:s', strtotime($dt . " +2 weeks")),
                        'BillItem.bill_id' => null,
                        'BillItem.approved' => 1,
                    'OR'=>array(
                   'BillItem.drew_approved' => null,
                   'BillItem.mary_approved' => null
                   )                     
                        
                )));
                if($this->Auth->user('id') === "80000302-1459626390")
                            $superuser = "drew_approved";
                        else
                            $superuser = "mary_approved";
                        
                    
                foreach($data as $item)
                {
                    $this->BillItem->id = $item['BillItem']['id'];
                    $this->BillItem->saveField($superuser, 1);
                    $this->BillItem->saveField('super_approved', 1);
                    
                    $this->Approvallog->create();
                    $this->Approvallog->save(
                            array(
                                'approver' => $this->Auth->user('id'),
                                'bill_item' => $item['BillItem']['id'],
                                'value' => 1,
                                'super' => 1
                            ));
                }
                $this->Session->setFlash('The Selected Bill Has Been Sent To QuickBooks.', 'flash_success');
                $this->redirect($this->referer());
        }
        
        function ajaxViewBill($vendor = null, $customer = null, $dt = null, $super = 0)
        {
            if(isset($vendor) && isset($customer))
            {
                if(!$super)
                {
                $data = $this->BillItem->find('all', array('conditions' => array(
                        'BillItem.vendor_id' => $vendor,
                        'BillItem.customer_id' => $customer,
                        'BillItem.txn_date >' => date('Y-m-d H:i:s', strtotime($dt . " -2 weeks")),
                        'BillItem.txn_date <' => date('Y-m-d H:i:s', strtotime($dt . " +2 weeks")),
                        'BillItem.bill_id' => null,
                        'BillItem.approved' => null
                        
                ),
                    'order' => array('BillItem.company_cc_item' => 'DESC', 'BillItem.item_id' => "ASC")));
                }
                else
                {
      
                     $data = $this->BillItem->find('all', array('conditions' => array(
                        'BillItem.vendor_id' => $vendor,
                        'BillItem.customer_id' => $customer,
                        'BillItem.txn_date >' => date('Y-m-d H:i:s', strtotime($dt . " -2 weeks")),
                        'BillItem.txn_date <' => date('Y-m-d H:i:s', strtotime($dt . " +2 weeks")),
                        'BillItem.bill_id' => null,
                        'BillItem.approved' => 1,
                         'OR' =>array(
                             'BillItem.drew_approved' => null,
                             'BillItem.mary_approved' => null
                         )
                        
                ),
                    'order' => array('BillItem.company_cc_item' => 'DESC', 'BillItem.item_id' => "ASC")));
                }
                $first = true;

                $return  = "<div id='approveMultiInfo' style='display: none;'><div id='vendor-id'>" . $vendor . "</div><div id='customer-id'>" .
                        $customer . "</div><div id='selected-date'>" . $dt . "</div></div>";
                if(strpos($data[0]['BillItem']['description'], "**") !== false)
                        $descr = substr($data[0]['BillItem']['description'], strpos($data[0]['BillItem']['description'], "**") + 2,
                                strrpos($data[0]['BillItem']['description'], "**") - 2);
                else
                    $descr =$data[0]['BillItem']['description'];
                
                $return .= "<h1>" . $data[0]['Customer']['full_name'] . "<br /><small>" . 
                        $descr ."</small></h1>";
                $separated = array('corporate' => array(), 'meals' => array(), 'transportation' => array(), 'other' => array());
                // separate into more managable array
                foreach($data as $item)
                {
                    if($item['BillItem']['company_cc_item'] == 1)
                    {
                        $separated['corporate'][] = $item;
                    }
                    elseif(strpos(strtolower($item['Item']['full_name']),'meal'))
                    {
                        $separated['meals'][] = $item;
                    }
                    elseif(strpos(strtolower($item['Item']['full_name']),'mileage'))
                    {
                        $separated['transportation'][] = $item;
                    }
                    else
                    {
                        $separated['other'][] = $item;
                    }
                }
                
                $amount = 0.00;
   
                foreach($separated['corporate'] as $item)
                {
                    
                    if($first)
                    {
                        $return .= "<h2>Corporate Card Items</h2><table class='table table-responsive table-striped table-hover'>" .
                                "<tr><td>Date</td><td>Amount</td><td>Item</td><td>Class</td><td>Description</td><td>Billable</td></tr>";
                        $first = false;
                    }
                    $amount += $item['BillItem']['amount'];
                    if(strpos($data[0]['BillItem']['description'], "**") !== false)
                        $descr = substr($item['BillItem']['description'], strrpos($item['BillItem']['description'], "**") + 3);
                else
                    $descr =$item['BillItem']['description'];
                
                    $return .= "<tr><td>" . date("m-d-Y", strtotime($item['BillItem']['txn_date'])) . "</td><td>" . $item['BillItem']['amount'] . "</td><td>" . $item['Item']['full_name'] . 
                            "</td><td>" . $item['Classes']['full_name'] . "</td><td>" . $descr . 
                            "</td><td>" . ($item['BillItem']['billable'] == 'Billable' ? "<i class='fa fa-check'></i>" : "") . "</td></tr>";
                }
                if(!$first){
                    $return .= "</table>";
                    $return .= "<h4 style='color: red;'>Corporate Card Total: <strong>$" . $amount . "</strong></h4><hr>";
                }
                
                
                $first = true;
                $amount = 0.00;
                
                foreach($separated['meals'] as $item)
                {
                    if($first)
                    {
                        $return .= "<h2>Meal Per Diem</h2>";
                        $first = false;
                    }
                    $amount += $item['BillItem']['amount'];
                    $return .= "<h5>" . $item['BillItem']['quantity'] . " quarters @ $" . $item['BillItem']['cost'] . " rate: <strong>$" . $item['BillItem']['amount'] . "</strong> <small>(Not Billable)</small></h5>";
                }
                
                $first = true;
               
                foreach($separated['transportation'] as $item)
                {
                    if($first)
                    {
                        $return .= "<h2>Transportation</h2><table class='table table-responsive table-striped table-hover'>" .
                                "<tr><td>Date</td><td>Amount</td><td>Item</td><td>Class</td><td>Description</td><td>Billable</td></tr>";
                        $first = false;
                    }
                    $amount += $item['BillItem']['amount'];
                    if(strpos($data[0]['BillItem']['description'], "**") !== false)
                        $descr = substr($item['BillItem']['description'], strrpos($item['BillItem']['description'], "**") + 3);
                else
                    $descr =$item['BillItem']['description'];
                    $return .= "<tr><td>" . date("m-d-Y", strtotime($item['BillItem']['txn_date'])) . "</td><td>" . $item['BillItem']['amount'] . "</td><td>" . $item['Item']['full_name'] . 
                            "</td><td>" . $item['Classes']['full_name'] . "</td><td>" . $descr . 
                            "</td><td>" . ($item['BillItem']['billable'] ? "<i class='fa fa-check'></i>" : "") . "</td></tr>";
                    
                }
                if(!$first){
                    $return .= "</table>";
                }
                
                $first = true;
                
                foreach($separated['other'] as $item)
                {
                    if($first)
                    {
                        $return .= "<h2>Other Charges</h2><table class='table table-responsive table-striped table-hover'>" .
                                "<tr><td>Date</td><td>Amount</td><td>Item</td><td>Class</td><td>Description</td><td>Billable</td></tr>";
                        $first = false;
                    }
                    $amount += $item['BillItem']['amount'];
                    if(strpos($data[0]['BillItem']['description'], "**") !== false)
                        $descr = substr($item['BillItem']['description'], strrpos($item['BillItem']['description'], "**") + 3);
                else
                    $descr =$item['BillItem']['description'];
                    $return .= "<tr><td>" . date("m-d-Y", strtotime($item['BillItem']['txn_date'])) . "</td><td>" . $item['BillItem']['amount'] . "</td><td>" . $item['Item']['full_name'] . 
                            "</td><td>" . $item['Classes']['full_name'] . "</td><td>" . $descr . 
                            "</td><td>" . ($item['BillItem']['billable'] ? "<i class='fa fa-check'></i>" : "") . "</td></tr>";
                    
                }
                if(!$first){
                    $return .= "</table>";
                }
                
                $return .= "<h4 style='color: red;'>Total Owed To Employee: <strong>$" . $amount . "</strong></h4>";
                
            }
            else
            {
                $return = "error";
            }
            echo $return;
            exit();
        }
        function admin_userExpenses($vendor_id = null)
        {
            $expenses = $this->BillItem->find('all', array('conditions' => array(
                'BillItem.vendor_id' => $vendor_id
            )));
            $this->set('expenses', $expenses);
            
            $u = $this->User->find('first', array('conditions' => array(
                'vendor_id' => $vendor_id
            )));
                    
                    $this->set('user', $u['User']);
        }
        function admin_approved() {
            $expenses = $this->BillItem->find('all', array('conditions' => array(
                'drew_approved' => 1,
                'mary_approved' => 1,
                'NOT' => array(
                    'bill_id' => NULL
                )
            )));
            $this->set('expenses', $expenses);
        }
        function admin_expenseExport($startDate = "2016-09-01", $endDate = "2016-11-01")
        {
            echo date('Y-m-d H:i:s', strtotime($startDate)) . " TO " . date('Y-m-d H:i:s', strtotime($endDate)) . "<br /> <br />";
            $result = $this->BillItem->query('SELECT BillItem.id, Item.full_name, BillItem.txn_date, BillItem.image, BillItem.customer_id, Classes.full_name, Customer.full_name, BillItem.vendor_id, Vendor.first_name, Vendor.last_name, BillItem.cost, BillItem.quantity, BillItem.amount, BillItem.description
FROM bill_items AS BillItem
JOIN customers AS Customer ON BillItem.customer_id = Customer.id
JOIN vendors AS Vendor ON Vendor.id = BillItem.vendor_id
JOIN classes AS Classes ON Classes.id = BillItem.class_id 
JOIN items AS Item ON Item.id = BillItem.item_id
WHERE BillItem.txn_date
BETWEEN "' . date('Y-m-d H:i:s', strtotime($startDate)) . '"
AND "' . date('Y-m-d H:i:s', strtotime($endDate)) . '"
AND BillItem.mary_approved =1
AND BillItem.drew_approved =1
AND BillItem.company_cc_item =1
ORDER BY vendor_id ASC , customer_id ASC , txn_date ASC ');
            $user = "";
            $cust = "";
            $count = 0;
            echo "<html><body><style>@page {
  size: 8.5in 11in;
} body{position absolute; left: 0; top: 0; margin: 0; width: 450px;}
 td {border: 1px solid black; padding: 5px 10px;} th {font-weight: bold; background-color: #ddd; padding: 5px 10px; border: 1px solid black;}";
            echo "</style>";
            echo "<div style='width: 100%;'>";
            foreach($result as $r)
            {
                $vendorName = $r['Vendor']['first_name'] . " " . $r['Vendor']['last_name'];
               
                if($vendorName !== $user) {
                //     echo "NEWVENDOR";
                    if($count > 0)
                        echo "</table>";
                    
                    echo "<h1>" . $vendorName . "</h1>";
                   $customer = "";
                   $user = $vendorName;
                }
                $count++;
                
                if($r['Customer']['full_name'] !== $cust)
                {
                    if($count > 1)
                        echo "</table>";
                        
                    $cust = $r['Customer']['full_name'];
                    echo "<h3>" . $cust . "</h3>";
                            echo "<table style='width: 100%;'>";
                            echo "<tr>";
                            echo "<th>Date</th><th>Class</th><th>Item</th><th>Receipt</th><th>Cost</th><th>Quantity</th><th>Total</th><th style='max-width: 150px;'>Description</th></tr>";
                           
                }
                echo "<tr><td>" . date('m/d/Y', strtotime($r['BillItem']['txn_date'])) . "</td><td>" . $r['Classes']['full_name'] . "</td>" .
                       "<td>" . $r['Item']['full_name'] . "</td>" . "<td><a href='/files/uploads/" . $r['BillItem']['image'] . "'>Link</a></td>" .
                        "<td>" . $r['BillItem']['cost'] . "</td>" . "<td>" . $r['BillItem']['quantity'] . "</td>" .
                        "<td>" . $r['BillItem']['amount'] . "</td>" . "<td>" . $r['BillItem']['description'] . "</td></tr>";
            }
            echo "</table>";
            echo "</div></body></html>";
            
            exit();
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
                pr($_FILES);
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
                $totalSpan = 0;
                $count = 0;
                $error = array();
                $toSave = array();
              //  pr($this->request->data);
                $data = $this->request->data;
                
                // break down each customer item
                $billItem = $data['BillItem'];
                $count = sizeof($billItem);
                foreach($billItem as $i => $bill) 
                    {
                $customer = $this->Customer->findById($bill['customer_id']);
                $priorDesc = $bill['description'];
                $baseDescription = "**" . $bill['dest'] . " ::: " . $customer['Customer']['full_name'] . " ::: " . 
                        $bill['depart_date'] . " - " . $bill['return_date'] . "\n" . $priorDesc . "**\n";
                $billItem[$i]['description'] = $baseDescription;
                $billItem[$i]['txn_date'] = date('Y-m-d H:i:s', strtotime($bill['depart_date']));
               
                $billItem[$i]['quantity'] = 1;
                $user = $this->Auth->user();
                $billItem[$i]['vendor_id'] = $user['Vendor']['id'];
                
                // number of days between the two dates
                $depart = strtotime($bill['depart_date']);
                $return = strtotime($bill['return_date']);
                $billItem[$i]['range'] = floor(abs($depart-$return) / (60 * 60 * 24)) + 1;
                $totalSpan += $billItem[$i]['range'];
                }
                
                
                //break down corporate items
                if(isset($data['corporate']))
                {
                    
                    foreach($data['corporate'] as $corp)
                    {
                        
                        $runningAmount = 0.00;
                        
                        // if air item, going to divide 50/50
                        $this->loadModel('Item');
                        $item = $this->Item->findById($corp['type']);
                        
                        // Must check item for the string "air" - dumb I know but it works.
                        $airItem = false;
                        if(strpos(strtolower($item['Item']['full_name']), 'air') !== false)
                        {
                            $airItem = true;
                        }
                       
                        foreach($billItem as $bill)
                        {
                            if(!$airItem)
                                $amount = ($corp['amount'] * $bill['range'])/$totalSpan;
                            else
                                $amount = $corp['amount']/$count;
                            
                            $amount = round($amount, 2);
                            $runningAmount += $amount;
                            
                            // clean up difference on last item
                            $difference = $corp['amount'] - $runningAmount;
                            if($difference != 0 && abs($difference)<0.10)
                            {
                                // edits the amount for the last company to pick up the slack.
                                // sorry, suckers.
                                $amount += $difference;
                            }
                                
                            
                        $corporateItem = $bill;
                        $corporateItem['company_cc_item'] = 1;
                        $corporateItem['txn_date'] = date('Y-m-d H:i:s', strtotime($corp['date']));
                        $corporateItem['id'] = $user['Vendor']['id'] . time() . rand(0,1000);
                        $corporateItem['cost'] = $amount;
                        $corporateItem['amount'] = $amount;
                        // Add description, don't overwrite
                        $corporateItem['description'] .= $corp['note'];
                        $corporateItem['image'] = $corp['receipt'];
                        $corporateItem['item_id'] = $corp['type'];
                        $corporateItem['billable'] = $this->_checkBillableStatus($corp['type']);
                        
                        // All fields set, lets save the little feller.
                        $this->BillItem->create($corporateItem);
                        if($this->BillItem->validates($corporateItem))
                            $toSave[] = $corporateItem;
                        else
                            $error[] = "Corporate Item";
                        }
                        
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
                        // run for each company
                        $quartersRemaining = $quarters;
                        foreach($billItem as $bill) {
                            
                            // total possible number of quarters for this company
                            $possibleQuarters = $bill['range'] * 4;
                            
                            // if the number of possible quarters is greater than quarters remaining,
                            // then less than a day of quarters was eaten, or we are on the last company, 
                            // and not a full day is accounted for. Otherwise, decrement remaining quarters by
                            // possible quarters
                            if($quartersRemaining < $possibleQuarters)
                                $possibleQuarters = $quartersRemaining;
                            else
                                $quartersRemaining -= $possibleQuarters;
                            
                            
                        $mealItem = $bill;
                        $mealItem['id'] = $user['Vendor']['id'] . time() . rand(0,1000);
                        $mealItem['quantity'] = $possibleQuarters;
                        $mealItem['cost'] = $quarterCost;
                        $mealItem['amount'] = ($possibleQuarters * $quarterCost);
                        $mealItem['description'] .= "Meal Per Diem";
                        $mealItem['item_id'] = $this->config['expenses.meals'];
                        $mealItem['image'] = "";
                        $mealItem['billable'] = 'NotBillable';
                        
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
                }
                
                
                
                // Break down and submit transportation items
                if(isset($data['trans']) && !empty($data['trans']))
                {
                    foreach($data['trans'] as $trans)
                    {
                        
                        $runningAmount = 0.00;
                        foreach($billItem as $bill)
                        {
                            // if it's a taxi, break up as normal.
                            if($trans['taxi-car'] == 'Taxi')
                        {
                            $amount = ($trans['amount'] * $bill['range'])/$totalSpan;
                            $amount = round($amount, 2);
                            $runningAmount += $amount;
                            
                            // clean up difference on last item
                            $difference = $trans['amount'] - $runningAmount;
                            if($difference != 0 && abs($difference)<0.10)
                            {
                                // edits the amount for the last company to pick up the slack.
                                // sorry, suckers.
                                $amount += $difference;
                            }
                        }
                        else
                        {
                            // dealing with a car and mileage. ugh.
                        
                        
                        $amount = ($trans['mileage'] * $bill['range'])/$totalSpan;
                            $amount = round($amount, 0);
                            $runningAmount += $amount;
                            
                            // clean up difference on last item
                            $difference = $trans['mileage'] - $runningAmount;
                            if($difference != 0 && abs($difference)<5)
                            {
                                // edits the amount for the last company to pick up the slack.
                                // sorry, suckers.
                                $amount += $difference;
                            }
                        
                        }
                        
                            
                                
                    $transItem = $bill;
                    $transItem['id'] = $user['Vendor']['id'] . time() . rand(0,10000);
                        if($trans['taxi-car'] == 'Taxi')
                        {
                            $transItem['cost'] = $amount;
                        $transItem['amount'] = $amount;
                        $transItem['quantity'] = 1;
                        }
                        else
                        {
                            $transItem['cost'] = $this->config['expenses.mileage'];
                        $transItem['amount'] = round(($transItem['cost']*$amount), 2);
                        $transItem['quantity'] = $amount;
                        }
                        
                        // Add description, don't overwrite
                        $transItem['description'] .= $trans['taxi-car'] . ": " . $trans['from'] . " => " . $trans['to'];
                        
                        $transItem['image'] = $trans['receipt'];
                        
                        $transItem['item_id'] = $this->_determineTransportationItem($transItem['class_id']);
                        $transItem['txn_date'] = date('Y-m-d H:i:s', strtotime($trans['date']));
                        $transItem['billable'] = 'NotBillable';
                       // Validate and queue for later saving, and save error otherwise.
                   
                        $this->BillItem->create($transItem);
                        if($this->BillItem->validates($transItem))
                            $toSave[] = $transItem;
                        else
                            $error[] = "Transportation Item";
                        
                            
                        
                        }
                            
                    }
               
                }
                
                
                
                //Finally we take care of "other" items
                if(isset($data['other']) && !empty($data['other']))
                {
                    foreach($data['other'] as $trans)
                    {
                        $runningAmount = 0.00;
                        
                        // if air item, going to divide 50/50
                        $this->loadModel('Item');
                        $item = $this->Item->findById($trans['type']);
                        
                        // Must check item for the string "air" - dumb I know but it works.
                        $airItem = false;
                        if(strpos(strtolower($item['Item']['full_name']), 'air') !== false)
                        {
                            $airItem = true;
                        }
                        
                        foreach($billItem as $bill)
                        {
                            if(!$airItem)
                                $amount = ($trans['amount'] * $bill['range'])/$totalSpan;
                            else
                                $amount = $trans['amount']/$count;
                            
                            $amount = round($amount, 2);
                            $runningAmount += $amount;
                            
                            // clean up difference on last item
                            $difference = $trans['amount'] - $runningAmount;
                            if($difference != 0 && abs($difference)<0.10)
                            {
                                // edits the amount for the last company to pick up the slack.
                                // sorry, suckers.
                                $amount += $difference;
                            }
                        
                    $otherItem = $bill;
                    $otherItem['id'] = $user['Vendor']['id'] . time() . rand(0,1000);
                        $otherItem['cost'] = $amount;
                        $otherItem['amount'] = $amount;
                        // Add description, don't overwrite
                        $otherItem['description'] .= $trans['note'];
                        $otherItem['quantity'] = 1;
                        $otherItem['image'] = $trans['receipt'];
                        $otherItem['item_id'] = $trans['type'];
                        $otherItem['txn_date'] = date('Y-m-d H:i:s', strtotime($trans['date']));
                        $otherItem['billable'] = $this->_checkBillableStatus($trans['type']);
                        
                        // And then, obvi, we save. And we save HARD.
                        $this->BillItem->create($otherItem);
                        if($this->BillItem->validates($otherItem))
                            $toSave[] = $otherItem;
                        else
                            $error[] = "Other Item";
                        
                    
                        }  
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
            
            $services = $this->_loadServices(1);
            $this->set('services', $services);
            
            
        }
        
        private function _determineTransportationItem($classId)
        {
            $this->loadModel('Classes');
            $this->loadModel('Item');
            $class = $this->Classes->findById($classId)['Classes']['full_name'];
            $items = $this->Item->find('all', array('conditions' => array('Item.full_name LIKE' => "%Staff Items:Mileage%"                
            )));
            $item = array();
            foreach($items as $i)
            {
                if(strpos(strtolower($i['Item']['name']), 'bip'))
                        $item['bip'] = $i['Item']['id'];
                elseif(strpos(strtolower($i['Item']['name']), 'admin'))
                        $item['admin'] = $i['Item']['id'];
                elseif(strpos(strtolower($i['Item']['name']), 'accred'))
                        $item['accred'] = $i['Item']['id'];
                else
                    $item['core'] = $i['Item']['id'];
            }
            $class = " " . $class;
        
             if(strpos(strtolower($class), 'bip'))
             {
                 $ret = $item['bip'];
                
             }
             elseif(strpos(strtolower($class), 'admin'))
             {
                 $ret = $item['admin'];

             }
             elseif(strpos(strtolower($class), 'accred'))
             {
                 $ret = $item['accred'];

             }
             else
             {
                 $ret = $item['core'];

             }
             
             return $ret;
        }
        
        function generateBills($hash = null)
        {
            if($hash !== '4naer9ANDJ83ana3uEJNe33801')
                exit('not authorized');
            
            $approvedBills = $this->BillItem->find('all', array(
                'conditions' => array(
                    'BillItem.super_approved' => 1,
                    'BillItem.drew_approved' => 1,
                    'BillItem.mary_approved' => 1,
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
                $bill_data_array[$a['BillItem']['vendor_id']]['newId'] = $id;
                
                
                }
                $this->BillItem->id = $a['BillItem']['id'];
                
                $this->BillItem->saveField('bill_id', $bill_data_array[$a['BillItem']['vendor_id']]['newId']);
            }
            
            foreach($bill_data_array as $i => $a)
                $this->_queueToSave($a['newId']);
            
            $this->_generateCCBills();
            
          //  pr($bill_data_array);
            exit('done');
            
        }
        
        private function _generateCCBills() {
            $approvedBills = $this->BillItem->find('all', array(
                'conditions' => array(
                    'BillItem.super_approved' => 1,
                    'BillItem.drew_approved' => 1,
                    'BillItem.mary_approved' => 1,
                    'BillItem.approved' => 1,
                    'BillItem.bill_id' => NULL,
                    'BillItem.company_cc_item' => 1
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
                        'is_paid' => "false",
                        "isPersonal" => 0
                    )
                );
                
                $this->Bill->create();
                $this->Bill->save($bill_data_array[$a['BillItem']['vendor_id']]);
                
                $id = $this->Bill->id;
                $bill_data_array[$a['BillItem']['vendor_id']]['newId'] = $id;
                
                
                }
                $this->BillItem->id = $a['BillItem']['id'];
                $this->BillItem->saveField('bill_id', $bill_data_array[$a['BillItem']['vendor_id']]['newId']);
            }
            
            foreach($bill_data_array as $i => $a)
                $this->_queueToSave($a['newId'], 'credit-charge');
            
            return true;
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
            
            $services = $this->_loadServices(1);
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
                
                
                // If Air or Hotel Item, billable is default, else it is not
                   $newRecord['BillItem']['billable'] = $this->_checkBillableStatus($newRecord['BillItem']['item_id']);
                       
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
        
        private function _checkBillableStatus($itemId)
        {
            $this->loadModel('Item');
            $item = $this->Item->findById($itemId);
           
            $n = strtolower($item['Item']['full_name']);
            
             
            if(strpos($n, 'hotel') === false && strpos($n, 'air') === false)
                    return 'NotBillable';
            
            return 'Billable';
        }
        function admin_edit($id = null)
        {
           
            $classes = $this->_loadClasses();
            $this->set('classes', $classes);
            
            $customers = $this->_loadCustomers();
            $this->set('customers', $customers);
            
            $services = $this->_loadServices(1);
            $this->set('services', $services);
            
            
            
            if(!empty($this->request->data))
            {
                $newRecord = $this->request->data;
                $newRecord['BillItem']['txn_date'] = date('Y-m-d H:i:s', strtotime($newRecord['BillItem']['txn_date']));
                $newRecord['BillItem']['approved'] = null;
                $newRecord['BillItem']['bill_id'] = null;
                $newRecord['BillItem']['cost'] = $newRecord['BillItem']['amount'];
                 $newRecord['BillItem']['super_approved'] = null;
                 $newRecord['BillItem']['drew_approved'] = null;
                 $newRecord['BillItem']['mary_approved'] = null;
               
                
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
           
             // Redirect to either viewMyTime or approval screen based on who edited
                
                if($this->Auth->user('id') !== $newRecord['BillItem']['user_id'])
                    $this->redirect('/admin/expenses/approve');
                else
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
            
            $denialNotice = array();
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
                    
                    $this->Approvallog->create();
                    $this->Approvallog->save(
                            array(
                                'approver' => $this->Auth->user('id'),
                                'bill_item' => $i,
                                'value' => $approve,
                                'super' => 0
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
                        {
                            $this->Notification->queueNotification($user['User']['id'],'ExpenseDeny','/admin/expenses/viewMyExpenses','Expense Denied','%i expenses were denied');
                            
                            // queue denial notice email for sending
                            if(!empty($d['denial_message']))
                            {
                                $denialNotice[$user['User']['id']][] = array('notice' => $d["denial_message"],
                                    'customer' => $timeEntry['Customer']['name'],
                                    'user_email' => $timeEntry['Vendor']['email'],
                                    'date' => $timeEntry['BillItem']['txn_date']);
                                
                            }
                        }
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
            
            
                    $this->_sendDenialNotices($denialNotice);
            
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
                        if($this->Auth->user('id') === "80000302-1459626390")
                            $superuser = "drew_approved";
                        else
                            $superuser = "mary_approved";
                        
                    $entry = array('BillItem' => array(
                        'id' => $i,
                        'super_approved' => $approve,
                        'approved' => $approve,
                        'billable' => $d['billable'],
                        $superuser => $approve
                    ));
                    
                    $this->Approvallog->create();
                    $this->Approvallog->save(
                            array(
                                'approver' => $this->Auth->user('id'),
                                'bill_item' => $i,
                                'value' => $approve,
                                'super' => 1
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
            if($this->Auth->user('id') == "80000302-1459626390"){
            $entries = $this->BillItem->find('all', array('conditions'=>array('BillItem.approved'=>'1',  'BillItem.bill_id'=>null, 
               'OR'=>array(
                   'BillItem.drew_approved' => null,
                   'BillItem.mary_approved' => null
                   )
            ), 'recursive' => 3,
                'order' => 'BillItem.txn_date ASC'));
            $this->set("super_user", 'drew');
            }
            else
            {
            $entries = $this->BillItem->find('all', array('conditions'=>array('BillItem.approved'=>'1',  'BillItem.bill_id'=>null, 
              'OR'=>array(
                   'BillItem.drew_approved' => null,
                   'BillItem.mary_approved' => null
                   )
            ), 'recursive' => 3,
                'order' => 'BillItem.txn_date ASC'));
            $this->set("super_user", 'mary');
            }
            
            $this->set('entries',$entries);
            
            
        }
        
        private function _queueToSave($id, $type = 'personal')
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
        if($type === "personal")
            $Queue->enqueue(QUICKBOOKS_ADD_BILL, $id);
        else
            $Queue->enqueue(QUICKBOOKS_ADD_CREDIT_CARD_CHARGE, $id);
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
