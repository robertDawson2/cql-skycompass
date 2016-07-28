<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    App::uses('AppController', 'Controller');
   
    
    class TimeEntryController extends AppController {

    	public $name = 'TimeEntry';

        
        public function beforeFilter() {
            parent::beforeFilter();
          
        }
                public function admin_ajaxGetPayrollItemList() {
            $this->layout = 'ajax';
            $this->loadModel('PayrollItem');
            $items = $this->PayrollItem->find('all');
            
            $returnArray = array();
            foreach($items as $item)
            {
               
                
                $returnArray[] = array(
                    'id' => $item['PayrollItem']['id'],
                    'name' => $item['PayrollItem']['name'],
                    'class' => ''
                    
                );
           
            }
             echo json_encode($returnArray);
                exit();
        }
        
          public function admin_ajaxGetClassList() {
            $this->layout = 'ajax';
            $this->loadModel('Classes');
            $items = $this->Classes->find('all', array('conditions' => array(
                'Classes.parent_id' => ''
            )));
            
            $returnArray = array();
            foreach($items as $class)
            {
               
                
                $returnArray[] = array(
                    'id' => $class['Classes']['id'],
                    'name' => $class['Classes']['name'],
                    'class' => 'main-item'
                    
                );
                
                if(!empty($class['Children']))
                {
                    foreach($class['Children'] as $job)
                    {
                        $returnArray[] = array(
                         'id' => $job['id'],
                            'name' => $job['name'],
                            'class' => 'child-item'
                        );
                    }
                }
                
               
                
            }
             echo json_encode($returnArray);
                exit();
        }
        
        public function beforeRender() {
            parent::beforeRender();
            $this->set('section', 'time');
        }
        public function admin_ajaxGetServiceItemlist()
        {
            $this->loadModel('Item');
            $itemList = $this->Item->find('all', array('conditions' => array(
                'Item.type' => 'Service',
                'Item.parent_id' => ''
            )));
             $returnArray = array();
            foreach($itemList as $class)
            {
               
                
                $returnArray[] = array(
                    'id' => $class['Item']['id'],
                    'name' => $class['Item']['name'],
                    'class' => 'main-item'
                    
                );
                
                if(!empty($class['Children']))
                {
                    foreach($class['Children'] as $job)
                    {
                        $returnArray[] = array(
                         'id' => $job['id'],
                            'name' => $job['name'],
                            'class' => 'child-item'
                        );
                    }
                }
                
               
                
            }
             echo json_encode($returnArray);
                exit();
        }
        public function admin_single() {
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
                $newRecord['TimeEntry']['approved'] = 0;
                $newRecord['TimeEntry']['is_billed'] = "";
                $newRecord['TimeEntry']['is_billable'] = "";
                $newRecord['TimeEntry']['billable_status'] = "Billable";
                $newRecord['TimeEntry']['txn_number'] = 0;
                $newRecord['TimeEntry']['id'] = sha1(serialize($newRecord) . time());
                if($this->TimeEntry->save($newRecord))
                {
                    $this->Session->setFlash('Your time entry has been successfully logged for approval.', 'flash_success');
                    
                }
                else
                {
                    $this->Session->setFlash('There was an error processing your request. Please try again.', 'flash_error');
                }
                $this->redirect('/admin/timeEntry/single');
            }
            
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
