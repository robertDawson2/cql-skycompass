<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');
   
    
    class EmailTemplatesController extends AppController {

        public function beforeRender() {
            parent::beforeRender();
            $this->set('section', 'communications');
         //   $this->layout = 'admin';
        }
        
        public function admin_preview($id)
        {
            $this->layout = 'Emails/html/default';
            $active = $this->EmailTemplate->findById($id);
            $this->set('active', $active);
            $this->set('description', $active['EmailTemplate']['subject']);
        }
        
        public function admin_index()
        {
            $templates = $this->EmailTemplate->find('all');
        }
        public function admin_ajaxSave($id = null)
        {
            $this->layout = 'ajax';
            if($this->request->is('post'))
            {
                if(!isset($id) && empty($this->request->data['EmailTemplate']['id']))
                
                {
                    unset($this->request->data['EmailTemplate']['id']);
                    $this->EmailTemplate->create();
                    
                    $exists = $this->EmailTemplate->find('first', array('conditions'=> array(
                        'name' => $this->request->data['EmailTemplate']['name'],
                        'context' => $this->request->data['EmailTemplate']['context']
                    )));
                    if(!empty($exists))
                    {
                        echo "renameError";
                        exit();
                    }
                }
                
                
                if(empty($this->request->data['EmailTemplate']['name']))
                {
                    echo "saveError";
                    exit();
                }
                
                
                if($this->EmailTemplate->save($this->request->data))
                {
                    echo $this->_templateList();
                }
                else
                {
                    echo "saveError";
                }
            }
            
            exit();
        }
        public function admin_ajaxLoad($id = null)
        {
            $this->layout = 'ajax';
            if($id !== null) {
                $template = $this->EmailTemplate->findById($id);
                echo json_encode($template);
            }
 else {
     echo $this->_templateList();
 }
            exit();
        }
        
        public function admin_ajaxAvailableFields($context)
        {
            $return = "";
            $this->loadModel($context);
            
            $defaults = $this->$context->find('first');
            if($context ==='Customer')
            {
                unset($defaults['Job']);
                unset($defaults['CustomerGroup']);
             //   unset($defaults['CustomerAccreditation']);
                unset($defaults['Customer']['contact']);
                
            }
            if($context ==='Contact')
            {
                unset($defaults['Job']);
                unset($defaults['ContactGroup']);
            //    unset($defaults['ContactCertification']);
                unset($defaults['Customer']['contact']);
            }
            if($context === 'User')
            {
                unset($defaults['Vendor']);
                unset($defaults['ApprovalManager']);
                unset($defaults['ScheduleEntry']);
                unset($defaults['TimeEntry']);
                unset($defaults['Ability']);
                unset($defaults['Chat']);
                unset($defaults['Notification']);
                unset($defaults['User']['password']);
                unset($defaults['User']['id']);
                unset($defaults['User']['vendor_id']);
                unset($defaults['User']['reset_hash']);
                unset($defaults['User']['scheduling_admin_notes']);
                unset($defaults['User']['scheduling_employee_notes']);
               
            }
            if($context === 'CustomerAccreditation')
            {
                $defaults['Contact'] = array();
            }
            

            foreach($defaults as $i => $e)
            {
                $this->loadModel($i);
                $fields[$i] = array_keys($this->$i->getColumnTypes());
                
            }
                foreach($fields as $a => $d){
                    foreach($d as $e)
                      $return .= "<span class='insertMe' onclick='insertText(this);'>{" . $a . "." . $e . "}</span>, ";
                }
            
            $return = substr($return, 0,-2);
            echo $return;
            exit();
                       
           
        }
        function admin_delete($id = null)
        {
            if(isset($id))
            {
                if($this->EmailTemplate->delete($id))
                    $this->Session->setFlash('Template deleted successfully.', 'flash_success');
                else
                    $this->Session->setFlash('Template deletion failed.', 'flash error');
            }
            else
            {
                $this->Session->setFlash('No template supplied.', 'flash_error');
            }
            $this->redirect('/admin/emailTemplates');
        }
        
        private function _templateList()
        {
            $contextOptions  = array('CustomerAccreditation'=> 'Accreditation','ContactCertification' => 'Certification', 'Contact'=>'Contacts', 'Customer' => 'Customers', 'User' => 'Employees');            $string = "";
            $options = $this->EmailTemplate->find('all', array('order' => 'context ASC'));
            if(empty($options))
            {
                $string = "<em>--- No Templates Exist! ---</em>";
                return $string;
            }
            $string = "<ul class='template-list'>";
            
            foreach($options as $option)
            {
                $string .= "\n<li><div style='display: inline-block;'>" . $option['EmailTemplate']['name'] . "<br /><em>(" . $contextOptions[$option['EmailTemplate']['context']] . ")</em></div>";
                
                $string .= '<a role="button" class="btn btn-small btn-danger delete-object" data-toggle="modal" data-object-name="' . $option['EmailTemplate']['name'] . '" data-object-id="' . $option['EmailTemplate']['id'] . '"><i class="fa fa-trash-o"></i></a>';
                $string .= '<a role="button" onclick="cloneToEdit(' . $option['EmailTemplate']['id'] . ', this)" class="btn btn-small btn-info clone-object"><i class="fa fa-copy"></i></a>';
                $string .= '<a role="button" onclick="loadToEdit(' . $option['EmailTemplate']['id'] . ', this)" class="btn btn-small btn-success clone-object"><i class="fa fa-edit"></i></a>';
                $string .= "</li>";
                
            }
            $string .= "</ul>";
            return $string;
        }
    }
    
