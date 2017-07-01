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
    private function _getList($completed=null, $limit=25)
    {
        $list = "";
        $openTodo = $this->Todo->find('all', array(
            'conditions' => array(
                    'completion_date is null',
                    'user_id' => $this->Auth->user('id')
                ),
                'order' => array(
                    'priority' => 'ASC',
                    'due_date' => 'ASC'
                ),
                'limit' => $limit
            ));
        foreach($openTodo as $item): 
                    $list .= "<li data-id='" . $item['Todo']['id'] . "'>"; 
                    $list .= '<input onclick="toggleChecked(\'' . $item['Todo']['id'] . '\', this)" value="" ';
                    if(isset($item['Todo']['completed']) && !empty($item['Todo']['completed'])) {   
                   $list .= "checked "; } 
                    $list .= 'type="checkbox">';
                    if($item['Todo']['priority'] === "1")
                        $list .= "<i title=\"High Priority\" class='fa fa-xs fa-warning red'></i> ";
                         
                        if($item['Todo']['priority'] === "2")
                            $list .= "<i title='High Priority' class='fa fa-xs fa-warning yellow'></i> ";
                   
                 $list .= ' <span class="text" style="max-width: 200px;">'. $item['Todo']['description'].'</span>';

                       $list .= " <div class='info-div'>\n"
                               . "";
                              if(isset($item['Todo']['contact_id'])) {
                            $list .= "<a class='green' href='/admin/contacts/view/" . $item['Todo']['contact_id'] . "'><i class='fa fa-lg fa-user' title='Contact record attached'></i></a>";
                              } 
                              elseif(isset($item['Todo']['customer_id'])) { 
                            $list .= "<a class='green' href='/admin/customers/view/" . $item['Todo']['customer_id']. "'><i class='fa fa-lg fa-building-o' title='Customer record attached'></i>";
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
                        $list .= "</div>\n" .
                    "</li>";
                        
                    endforeach;   
                    return $list;
    }
    	public function  admin_ajaxGetList($completed = null) {
            $list = $this->_getList($completed);
        
  
                    
                    echo $list;
                    exit();
            
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