<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');
   
    
    class ReportingController extends AppController {

        public function beforeRender() {
            parent::beforeRender();
            $this->set('section', 'crmreporting');
           
        }
        public function admin_ajaxSaveTemplate() {
            $this->layout = 'ajax';
            if($this->request->is('post'))
            {
                $cond = json_decode($this->request->data['conditions'], true);
                foreach($cond as $i => $c)
                {
                    if($c['data'] === null || empty($c['data']))
                        unset($cond[$i]);
                }
                $this->request->data['condtions'] = json_encode($cond);
                
                $this->loadModel('ReportTemplate');
                $matched = $this->ReportTemplate->find('first', array('conditions' => array(
                    'name' => $this->request->data['name'],
                    'context' => $this->request->data['context']
                )));
                
                if(!empty($matched))
                {
                    unset($this->request->data['name']);
                }
                
                if(empty($this->request->data['name']))
                    unset($this->request->data['name']);
                
                
                if(isset($this->request->data['name']) && $this->ReportTemplate->save($this->request->data))
                {
                    exit('success');
                }
                else
                {
                    exit('An error occurred while saving. Check that you have provided a unique template name.');
                }
            }
            exit('Access denied.');
        }
        public function admin_ajaxLoadTemplate($id) {
            $this->loadModel('ReportTemplate');
            $data = $this->ReportTemplate->findById($id);
            $data['ReportTemplate']['fields'] = json_decode($data['ReportTemplate']['fields'], true);
            $data['ReportTemplate']['conditions'] = json_decode($data['ReportTemplate']['conditions'], true);
            
            echo json_encode($data['ReportTemplate']);
            exit();
        }
        public function admin_ajaxGetTemplates($context)
        {
            $this->layout='ajax';
            $this->loadModel('ReportTemplate');
            $templates = $this->ReportTemplate->find('list', array(
                'conditions' => array(
                    'context' => $context
                ),
                'fields' => array('id','name')
            ));
            $this->set('templates', $templates);
        }
         private function _reportingFields($context)
        {
            
            $this->loadModel($context);
            
            $defaults = $this->$context->find('first');
            if($context ==='Customer')
            {
                unset($defaults['Job']);
                unset($defaults['CustomerGroup']);
                unset($defaults['CustomerAccreditation']);
                unset($defaults['Customer']['contact']);
                unset($defaults['CustomerFile']);
                
            }
            if($context ==='Contact')
            {
                unset($defaults['Job']);
                unset($defaults['ContactGroup']);
                unset($defaults['ContactCertification']);
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
               return $fields;
                       
           
        }
        public function admin_runCustomerReport($context,$criteria=null, $fields=null,$export=null)
        {
            $this->layout = 'ajax';
           if($this->request->is('post')) {
             
               
            $this->loadModel($context);
            $this->$context->unbindModel(array('hasMany' => array('Job')));
            
            if(trim(substr($this->request->data['conditions'],-4)) === "OR )")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . ")";
            if(trim(substr($this->request->data['conditions'],-4)) === "OR")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . "";
            
            $results = $this->$context->find('all', array(
                'joins' => array(
                    array(
                    'table' => 'customer_groups',
                    'alias' => 'CustomerGroup',
                    'type' => 'LEFT',
                    'conditions' => '`CustomerGroup`.`customer_id` = `Customer`.`id`'
                    
                        ),
                    array(
                    'table' => 'groups',
                    'alias' => 'Group',
                    'type' => 'LEFT',
                    'conditions' => '`CustomerGroup`.`group_id` = `Group`.`id`'
                        ),
//                    array(
//                    'table' => 'customer_accreditations',
//                    'alias' => 'CustomerAccreditation',
//                    'type' => 'LEFT',
//                    'conditions' => '`CustomerAccreditation`.`customer_id` = `Customer`.`id`'
//                        ),
                    
                ),
                //'conditions' => 'CustomerAccreditation.expiration_date < "2017-07-04 00:00:00"'));
                'conditions' => $this->request->data['conditions']));
            
            $final = array();
            $returnFields = array();
            $innerFieldArray = array();
            $returnFields[] = array(
                       'title' => "",
                       'orderable' => false,
                       'targets' => 0,
                       
                       'searchable' => false,
                       'data' => 'select-box');
            foreach($this->request->data['fields'] as $field)
               {
                   
                   $innerFieldArray[str_replace(".", "-",$field)] = null;
                   $returnFields[] = array(
                       'title' => ucwords(str_replace(".", "<br /> ", str_replace("_", " ", $field))),
                       'data' => str_replace(".", "-",$field),
                       'class' =>'show-on-export');
                        
               }
               
               $counter =0;
            foreach($results as $result)
            {
                
               $final[$counter] = $innerFieldArray;
              $final[$counter]['select-box'] = "<input type='checkbox' class='report-select' data-id='" . $result['Customer']['id'] . "' />";
               foreach($this->request->data['fields'] as $field)
               {
                   
                   $fieldArray = explode(".", $field);
                   // if not a multi-array
                   if(isset($result[$fieldArray[0]][$fieldArray[1]]) && 
                           !empty($result[$fieldArray[0]]) && 
                           (!isset($result[$fieldArray[0]][0] )||
                           !is_array($result[$fieldArray[0]][0] ))) {
                        $final[$counter][str_replace(".", "-",$field)] = $result[$fieldArray[0]][$fieldArray[1]];
                   }
                   // check if first element is another array - this means a multi-dimensional array
                   elseif(!isset($result[$fieldArray[0]][$fieldArray[1]]) && 
                           !empty($result[$fieldArray[0]]) && 
                           (isset($result[$fieldArray[0]][0]) &&
                           is_array($result[$fieldArray[0]][0])))
                   {
                       foreach($result[$fieldArray[0]] as $subresult) 
                       {
                           
                           $final[$counter][str_replace(".", "-",$field)] .= $subresult[$fieldArray[1]] . "<br />";
                           
                       }
                   }
                   else
                   {
                       $final[$counter][str_replace(".", "-",$field)] = "";
                       
                   }
               }
               
                $counter++;
            }
            $return = array('data' => array('data' => $final), 'columns' => $returnFields);
            
            $this->loadModel('JsonReport');
            $this->JsonReport->create();
            $this->JsonReport->save(array('json' => json_encode($return)));
            echo $this->JsonReport->id;
            exit();
           
           }
           
            $this->set('results', $final);
            $this->set('fields', $this->request->data['fields']);
           }
           
           public function admin_ajaxLoadRecent($id, $data = null)
           {
               $this->loadModel('JsonReport');
               $result = $this->JsonReport->findById($id);
               if($data === null){
                   echo $result['JsonReport']['json']; 
               }
              else
              {
                  $try = json_decode($result['JsonReport']['json']);
                  echo json_encode($try->data);
              }
               exit();
           }
        public function admin_runAccreditationReport($context,$criteria=null, $fields=null,$export=null)
        {
            $this->layout = 'ajax';
           if($this->request->is('post')) {
             
            $this->loadModel($context);
            if(!in_array($context . ".id",$this->request->data['fields']))
                $this->request->data['fields'][] = $context . ".id";
            
            if($context === 'CustomerAccreditation')
            {
                if(!in_array($context . ".customer_id",$this->request->data['fields']))
                $this->request->data['fields'][] = $context . ".customer_id";
                
                if(!in_array($context . ".accreditation_id",$this->request->data['fields']))
                $this->request->data['fields'][] = $context . ".accreditation_id";
                
                $contactFields = array();
                foreach($this->request->data['fields'] as $i => $field)
                {
                    if(substr($field, 0, 7) === "Contact")
                    {
                        $contactFields[] = $field;
                        unset($this->request->data['fields'][$i]);
                    }
                }
            }
            
            
            if(trim(substr($this->request->data['conditions'],-4)) === "OR )")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . ")";
            
            $results = $this->$context->find('all', array(
                'conditions' => $this->request->data['conditions'],
                'fields' => $this->request->data['fields']));
            
            if($context === 'CustomerAccreditation' && !empty($contactFields))
            {
                $this->loadModel('Customer');
                foreach($results as $i => $result)
                {
                    $customer = $this->Customer->findById($result['CustomerAccreditation']['customer_id']);
                    if(!empty($customer['Contact']))
                    {
                        foreach($customer['Contact'] as $contact)
                        {
                            if($contact['id'] === $customer['Customer']['primary_contact_id'])
                                $results[$i]['Contact'] = $contact;
                        }
                        if(empty($results[$i]['Contact']))
                            $results[$i]['Contact'] = $customer['Contact'][0];
                        
                        foreach($results[$i]['Contact'] as $j => $res)
                        {
                            if(!in_array('Contact.' . $j, $contactFields))
                                    unset($results[$i]['Contact'][$j]);
                        }
                    }
                    else
                        $results[$i]['Contact'] = array();
                }
            }
            
            $this->set('results', $results);
            $this->set('fields', array_merge($this->request->data['fields'], $contactFields));
           }
           
            
        }
        function admin_customer() {
            $this->loadModel('Group');
            $this->set('groups', $this->Group->find('list', array('conditions'=> array('is_customer'=> 1))));
            $this->loadModel('CustomerType');
            $this->set('customerTypes', $this->CustomerType->find('list'));
            $this->set('customerSources', explode("|", $this->config['customer.sources']));
            $this->loadModel('EmailTemplate');
            $this->set('templateOptions', $this->EmailTemplate->find('list', array('conditions' => array(
                'context' => 'Customer'
            ))));
            $this->set('fields', $this->_reportingFields('Customer'));
            $this->set('defaultExportTitle', 'CustomerQuery-' . date("mdY"));
        }
        public function admin_accreditation()
        {
            $this->loadModel('Accreditation');
            $accred = $this->Accreditation->find('list');
            $this->set('accredTypes', $accred);
            $this->set('fields', $this->_reportingFields('CustomerAccreditation'));
            
            $this->loadModel('EmailTemplate');
            $this->set('templateOptions', $this->EmailTemplate->find('list', array('conditions' => array(
                'context' => 'CustomerAccreditation'
            ))));
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
                unset($defaults['CustomerAccreditation']);
                unset($defaults['Customer']['contact']);
                
            }
            if($context ==='Contact')
            {
                unset($defaults['Job']);
                unset($defaults['ContactGroup']);
                unset($defaults['ContactCertification']);
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
                $string .= "\n<li onclick='loadToEdit(" . $option['EmailTemplate']['id'] . ")'><div style='display: inline-block;'>" . $option['EmailTemplate']['name'] . "<br /><em>(" . $contextOptions[$option['EmailTemplate']['context']] . ")</em></div>";
                
                $string .= '<a role="button" class="btn btn-small btn-danger delete-object" data-toggle="modal" data-object-name="' . $option['EmailTemplate']['name'] . '" data-object-id="' . $option['EmailTemplate']['id'] . '"><i class="fa fa-trash-o"></i> Delete</a>';
                
                $string .= "</li>";
                
            }
            $string .= "</ul>";
            return $string;
        }
    }
    
