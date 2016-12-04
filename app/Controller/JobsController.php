<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    App::uses('AppController', 'Controller');
   
    
    class JobsController extends AppController {

    	public $name = 'Job';
        public $uses = array('Job', 'User', 'Customer', 'TaskItem', 'TaskListTemplate', 'JobTaskList','JobTaskListItem');

        
        public function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow('import','ajax_scheduleEmployees');
        }
        
        
        public function beforeRender() {
            parent::beforeRender();
            $this->set('section', 'jobs');
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
        public function ajax_scheduleEmployees($id = null) 
        {
            $this->layout = 'ajax';
            $job = $this->Job->findById($id);
            if(!isset($id))
            $employeeList = $this->User->find('all');
            else
                $employeeList = $this->User->find('all', array('conditions'=> array('User.first_name LIKE ' => "%e%")));
            
            $return = array('draw' => 1, 'recordsTotal' => sizeof($employeeList), 'recordsFiltered' => sizeof($employeeList),
                'data' => array());
                    foreach($employeeList as $emp)
            {
                $return['data'][] = array('first' => $emp['User']['first_name'], 'last' => $emp['User']['last_name'],
                    'location' => $emp['User']['city'] . ", " . $emp['User']['state'], 'abilities' => '{list of all areas}', 'notes' => '{pertinent notes}',
                    'team-leader' => '<a onclick="teamLeaderAdd(this);" data-id="' . $emp['User']['id'] . '" class="addLeader" href="#"><i class="fa fa-plus-circle"></i></a>',
                    'employee' => '<a onclick="employeeAdd(this);" data-id="' . $emp['User']['id'] . '" class="addEmployee" href="#"><i class="fa fa-plus-circle"></i></a>');
                    }
                    echo json_encode($return);
             exit();
        }
        public function admin_scheduler() {
            $openJobs = $this->Job->find('all', array('recursive' => 2,  'conditions' => array(
                'review_start' => null
            )));
            foreach($openJobs as  $i => $job) {
                unset($openJobs[$i]['Customer']['Jobs']);
            }
            $scheduledJobs = $this->Job->find('all', array('conditions'=> array(
                'NOT' => array(
                    'review_start' => null
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
