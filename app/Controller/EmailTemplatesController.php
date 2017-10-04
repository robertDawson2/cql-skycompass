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
            $contextOptions  = array('CustomerAccreditation'=> 'Accreditation',
                'ContactCertification' => 'Certification', 
                'Contact'=>'Contacts', 'Customer' => 'Customers', 
                'OrganizationTraining' => 'Organization Training',
                'ContactTraining' => 'Contact Training',
                'ContactPortal' => 'Contact Portal',
                'OrganizationPortal' => 'Organization Portal');            
            $this->set('contextOptions', $contextOptions);
            $fromEmails = explode(",", $this->config['reporting.from_emails']);
            $replyToEmails = explode(",", $this->config['reporting.reply_to_emails']);
            foreach($fromEmails as $i => $e)
            {
                $fromEmails[$i] = trim($e);
            }
            foreach($replyToEmails as $i => $e)
            {
                $replyToEmails[$i] = trim($e);
            }
            $this->set('fromEmails', $fromEmails);
            $this->set('replyToEmails', $replyToEmails);
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
        
        private function _reportingFields($context)
        {
             if($context === 'OrganizationPortal')
            {
                $category = array('Portal','Organization');
            }
            if($context === 'ContactPortal')
            {
                $category = array('Portal','Contact');
            }
            if($context === 'OrganizationTraining')
            {
                $category = array('Job','Organization');
            }
            if($context === 'ContactTraining')
            {
                $category = array('Job','Contact');
            }
            if($context === 'Customer')
            {
                $category = array('Organization');
            }
            
            if($context === 'Contact') {
                $category = array('Contact');
            }
            if($context === "CustomerAccreditation" || $context === "Accreditation")
            {
                $category = array('Accreditation', 'Organization');
            }
            
            if($context === "ContactCertification" || $context === "Certification")
            {
                $category = array('Certification', 'Contact');
            }
            
            $this->loadModel('AvailableField');
                $fields = $this->AvailableField->find('all', array(
                    'conditions' => array(
                        'category' => $category
                    ),
                    'order' => array('AvailableField.category ASC')
                ));
                $return = array();
                foreach($fields as $field)
                {
                 if(!isset($return[$field['AvailableField']['category']]))
                     $return[$field['AvailableField']['category']] = array();
                 
                    $return[$field['AvailableField']['category']][] = $field['AvailableField'];
                }
                return $return;
        }
        
        public function admin_ajaxAvailableFields($context)
        {
            $return = "";
            $fields = $this->_reportingFields($context);
           
           
                foreach($fields as $a => $d){
                    foreach($d as $e)
                      $return .= "<span class='insertMe' data-val='" .
                            $e['id'] . "' onclick='insertText(this);'>{" . $e['pretty_name'] . "}</span>, \n";
                }
            
            $return = substr($return, 0,-4);
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
            $contextOptions  = array('CustomerAccreditation'=> 'Accreditation',
                'ContactCertification' => 'Certification', 
                'Contact'=>'Contacts', 'Customer' => 'Customers', 
                'OrganizationTraining' => 'Organization Training',
                'ContactTraining' => 'Contact Training',
                'ContactPortal' => 'Contact Portal',
                'OrganizationPortal' => 'Organization Portal'
                );            
            
            $string = "";
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
                
                $string .= '<a role="button" class="btn btn-xs btn-danger delete-object" data-toggle="modal" data-object-name="' . $option['EmailTemplate']['name'] . '" data-object-id="' . $option['EmailTemplate']['id'] . '"><i class="fa fa-trash-o"></i></a>';
                $string .= '<a role="button" onclick="cloneToEdit(' . $option['EmailTemplate']['id'] . ', this)" class="btn btn-xs btn-info clone-object"><i class="fa fa-copy"></i></a>';
                $string .= '<a role="button" onclick="loadToEdit(' . $option['EmailTemplate']['id'] . ', this)" class="btn btn-xs btn-success clone-object"><i class="fa fa-edit"></i></a>';
                $string .= "</li>";
                
            }
            $string .= "</ul>";
            return $string;
        }
    }
    
