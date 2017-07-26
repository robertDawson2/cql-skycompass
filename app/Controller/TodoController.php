<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');
   
    
    class TodoController extends AppController {

        public function beforeRender() {
            parent::beforeRender();
            $this->set('section', 'none');
            
        }
        
        function admin_ajaxCheckReminders()
        {
            $this->layout = 'ajax';
            $userId = $this->Auth->user('id');
            // if expired login, exit loop
            if(empty($userId) || $userId === null)
                exit('done');
            
            $expiring = $this->Todo->find('all', array(
                'conditions' => array(
                    'NOT' => array('reminder_date is null'),
                    'reminder_sent' => 0,
                    'user_id' => $userId,
                    'reminder_date <= ' => date('Y-m-d H:i:s'),
                    'completion_date is null'),
                'order' => array(
                    'reminder_date' => 'ASC',
                    'priority' => 'ASC'
                )
                   
                )
            );
            // if expired has records we have reminders to send!
            if(!empty($expiring)) {
               
            foreach($expiring as $record)
            {
                // queue notification
                $this->loadModel('Notification');
                $this->Notification->queueNotification($userId, 'TodoItemReminder', '/admin/todo', 'Reminder Alert', '%i todo list items due soon');
                
                // save notification as alert sent
                $this->Todo->id = $record['Todo']['id'];
                $this->Todo->saveField('reminder_sent', 1);
            }
            
            
            
            // get view var ready for display return
            $this->set('context', 'reminders');
            $this->set('expired', $expiring);
            }
            else
            {
                // no reminders, let's try for expired
                $expiring = $this->Todo->find('all', array(
                'conditions' => array(
                    'NOT' => array('due_date is null'),
                    'reminder_sent <' => 2,
                    'user_id' => $userId,
                    'due_date <= ' => date('Y-m-d H:i:s'),
                    'completion_date is null'),
                'order' => array(
                    'due_date' => 'ASC',
                    'priority' => 'ASC'
                )
                   
                )
            );
            // if expired has records we have reminders to send!
            if(!empty($expiring)) {
                
            foreach($expiring as $record)
            {
                // queue notification
                $this->loadModel('Notification');
                $this->Notification->queueNotification($userId, 'TodoItemExpired', '/admin/todo', 'Expired Alert', '%i todo list items overdue');
                
                // save notification as alert sent
                $this->Todo->id = $record['Todo']['id'];
                $this->Todo->saveField('reminder_sent', 2);
            }
            
            
            
            // get view var ready for display return
            $this->set('context', 'expired');
            $this->set('expired', $expiring);
                
            }
            else {
                exit('done');
            }
            
        }
        }
        function admin_ajaxChangeItemStatus($id, $value) {
        $this->layout = 'ajax';
        if($value== "0")
        {
            $this->Todo->id = $id;
            $this->Todo->saveField('completion_date', null);
           
        }
        else
        {
             $this->Todo->id = $id;
            $this->Todo->saveField('completion_date', date('Y-m-d H:i:s'));
//            $this->_handleAlert($id);
//            if($this->_getNextAction($id) !== -1)
//            {
//                $this->_handleAlert($id, true);
//            }
        }
        exit(0);
    }
    function admin_index() {
        
    }
    function admin_ajaxGetJsonDetails($id)
    {
        $this->layout = 'ajax';
        $item = $this->Todo->findById($id);
        if($item['Todo']['due_date'] !== null)
            $item['Todo']['due_date'] = date('m/d/Y h:i A', strtotime($item['Todo']['due_date']));
        if($item['Todo']['reminder_date'] !== null)
            $item['Todo']['reminder_date'] = date('m/d/Y h:i A', strtotime($item['Todo']['reminder_date']));
        echo json_encode($item);
        exit();
    }
    function admin_ajaxFullAdd() {
        $this->layout = 'ajax';
           if($this->request->is('post'))
           {
               if(isset($this->request->data['Todo']['reminder']) && !empty($this->request->data['Todo']['reminder']))
                   $this->request->data['Todo']['reminder_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['Todo']['reminder']));
               unset($this->request->data['Todo']['reminder']);
               
               if(isset($this->request->data['Todo']['due']) && !empty($this->request->data['Todo']['due']))
                   $this->request->data['Todo']['due_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['Todo']['due']));
               unset($this->request->data['Todo']['due']);
               
               
               unset($this->request->data['Todo']['search']);
               if($this->request->data['Todo']['contact'] !== null && !empty($this->request->data['Todo']['contact']))
               {
                   $this->request->data['Todo']['customer_id'] = null;
                   $this->request->data['Todo']['contact_id'] = $this->request->data['Todo']['contact'];
               }
               
               elseif($this->request->data['Todo']['customer'] !== null && !empty($this->request->data['Todo']['customer']))
               {
                    $this->request->data['Todo']['contact_id'] = null;
                   $this->request->data['Todo']['customer_id'] = $this->request->data['Todo']['customer'];
               }
               else
               {
                   $this->request->data['Todo']['contact_id'] = null;
                    $this->request->data['Todo']['customer_id'] = null;
               }
               $this->request->data['Todo']['reminder_sent'] = 0;
               unset($this->request->data['Todo']['customer']);
               unset($this->request->data['Todo']['contact']);
               
               $this->request->data['Todo']['user_id'] = $this->Auth->user('id');
               if(isset($this->request->data['Todo']['hidden_id']) && !empty($this->request->data['Todo']['hidden_id']))
               {
                   $this->request->data['Todo']['id'] = $this->request->data['Todo']['hidden_id'];
               }
               else 
               {
               $this->Todo->create();
               }
               unset($this->request->data['Todo']['hidden_id']);
               if($this->Todo->save($this->request->data))
               {
                   echo $this->_getList(true,500,true);
               }
               else
                   echo "An error occurred refreshing your list. Please check back later!";
           }
           else
           {
               echo "An error occurred refreshing your list. Please check back later!";;
           }
           exit();
       }
    
    private function _getList($completed=false, $limit=25,$full=false)
    {
        $list = "";
        if(!$completed)
            $conditions = array(
                    'completion_date is null',
                    'user_id' => $this->Auth->user('id')
                );
        else
            $conditions = array(
                    'user_id' => $this->Auth->user('id')
                );
        
        $openTodo = $this->Todo->find('all', array(
            'conditions' => $conditions,
                'order' => array(
                    'completion_date' => 'DESC',
                    'priority' => 'ASC',
                    'due_date' => 'ASC'
                ),
                'limit' => $limit
            ));
        foreach($openTodo as $item): 
                    $list .= "<li ";
                    if(!($item['Todo']['completion_date']===null))
                        $list .= "class='done' "; 
                    $list .= "data-id='" . $item['Todo']['id'] . "'>"; 
                    $list .= '<input onclick="toggleChecked(\'' . $item['Todo']['id'] . '\', this)" value="" ';
                    if(!($item['Todo']['completion_date']===null))
                   $list .= "checked "; 
                    $list .= 'type="checkbox">';
                    if($item['Todo']['priority'] === "1")
                        $list .= "<i title=\"Highest Priority\" class='fa fa-xs fa-warning red'></i> ";
                         
                        if($item['Todo']['priority'] === "2")
                            $list .= "<i title='High Priority' class='fa fa-xs fa-warning orange'></i> ";
                        if($item['Todo']['priority'] === "3")
                            $list .= "<i title='Normal Priority' class='fa fa-xs fa-warning yellow'></i> ";
                        if($item['Todo']['priority'] === "4")
                            $list .= "<i title='Low Priority' class='fa fa-xs fa-warning green'></i> ";
                        if($item['Todo']['priority'] === "5")
                            $list .= "<i title='Lowest Priority' class='fa fa-xs fa-warning gray'></i> ";
                   
                 $list .= ' <span class="text ';
                if(isset($item['Todo']['due_date']) && $item['Todo']['completion_date'] === null) {
                    if(time() > strtotime($item['Todo']['due_date']))
                    {
                        $list .= "late";
                    }
                }
                 $list .= '" style="max-width: 200px;">'. $item['Todo']['description'].'</span>';
                    if(!($item['Todo']['completion_date']===null))
                        $list .= "<div class='completion-div'><em>Completed: " . date('m/d/Y H:i', strtotime($item['Todo']['completion_date'])) . "</em></div>"; 
                       $list .= " <div class='info-div'>\n"
                               . "";
                       if($full) {
                                 $list .= '<a role="button" style="margin-right: 10px;" class="btn btn-xs btn-info" onclick="editRow(\'' . $item['Todo']['id'] . '\', this);"><i class="full-detail fa fa-edit"></i> Edit</a>';
                             }
                              if(isset($item['Todo']['contact_id'])) {
                            $list .= "<a class='green' href='/admin/contacts/view/" . $item['Todo']['contact_id'] . "'><i class='fa fa-lg fa-user' title='Contact record attached'></i></a>";
                              } 
                              elseif(isset($item['Todo']['customer_id'])) { 
                            $list .= "<a class='green' href='/admin/customers/view/" . $item['Todo']['customer_id']. "'><i class='fa fa-lg fa-building-o' title='Organization record attached'></i></a>";
                              } 
                              else { 
                            $list .= "<i class='fa fa-lg fa-ban grey' title='No Records Assigned'></i>";
                             } 
                             
                             if(isset($item['Todo']['todo_type_id'])) { 
                            $list .= "<i class='fa fa-lg " . $item['TodoType']['fa_icon'] . " lightblue' title='" .  $item['TodoType']['name'] . "'></i>";
                             } 
                             else { 
                            $list .= "<i class='fa fa-lg fa-remove grey' title='No Type Assigned'></i>";
                             } 
                             
                             if(isset($item['Todo']['due_date'])) { 
                            $list .= "<i class='fa fa-lg fa-clock-o red' title='Due By " . date('m/d/y \a\t h:i A', strtotime($item['Todo']['due_date']))."'></i>";
                             } 
                             else { 
                            $list .= "<i class='fa fa-lg fa-clock-o grey' title='No due date assigned'></i>";
                             } 
                             
                             if(isset($item['Todo']['reminder_date'])) { 
                            $list .= "<i class='fa fa-lg fa-bell yellow' title='Reminder: ". date('m/d/y \a\t h:i A', strtotime($item['Todo']['reminder_date'])). "'></i>";
                             } 
                             else { 
                            $list .= "<i class='fa fa-lg fa-bell-slash grey' title='No reminder set'></i>";
                             } 
                             if($full) {
                                 $list .= '<a role="button" onclick="toggleFullDetails(\'' . $item['Todo']['id'] . '\', this);"><i class="full-detail fa fa-lg fa-angle-double-down"></i> View Full Details</a>';
                             }
                        $list .= "</div>\n" .
                    "</li>";
                        
                    endforeach;   
                    return $list;
    }
    	public function  admin_ajaxGetList($completed = false,$limit=25,$full=false) {
            $list = $this->_getList($completed,$limit,$full);
        
  
                    
                    echo $list;
                    exit();
            
        }
        public function admin_ajaxGetFullDetails($id)
        {
            $this->layout='ajax';
            $item = $this->Todo->findById($id);
            
            $this->set('item', $item);
            
        }
       public function admin_ajaxQuickAdd() {
           $this->layout = 'ajax';
           if($this->request->is('post'))
           {
               $this->request->data['Todo']['user_id'] = $this->Auth->user('id');
               $this->Todo->create();
               if($this->Todo->save($this->request->data))
               {
                   echo $this->_getList();
               }
               else
                   echo "An error occurred refreshing your list. Please check back later!";
           }
           else
           {
               echo "An error occurred refreshing your list. Please check back later!";;
           }
           exit();
       }
        
        }