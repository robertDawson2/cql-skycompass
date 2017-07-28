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
        function admin_quickReports()
        {
            $this->loadModel('ReportTemplate');
            $templates = $this->ReportTemplate->find('all', array(
                'order' => 'context ASC'
            ));
            $this->set('templates', $templates);
        }
        public function admin_ajaxSaveTemplate() {
            $this->layout = 'ajax';
            if($this->request->is('post'))
            {
                $cond = json_decode($this->request->data['conditions'], true);
                
                foreach($cond as $i => $c)
                {
                    if(isset($c['data'])) {
                    if($c['data'] === null || empty($c['data']))
                        unset($cond[$i]);
                    }
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
        public function admin_ajaxSendEmails($templateId, $context) {
            $this->layout = 'ajax';
            
            $counterSent = 0;
            $counterUnsent = 0;
            if($this->request->is('post'))
            {
               $data = $this->request->data;
               if(empty($data['idList']))
                   exit('e1');
               
               foreach($data['idList'] as $id)
               {
                   if($context === 'customer')
                   {
                       $contactId = $this->_getCustomerContactEmail($id);
                       $communicationId = $this->_logEmail($context, $templateId, $contactId, $id);
                       $sendResult = $this->_sendEmail($communicationId, $context, $templateId, $contactId, $id);
                       if($sendResult)
                       {
                           $counterSent++; 
                       }
                       else
                       {
                           $counterUnsent++;
                           $this->_changeEmailStatus($communicationId, 'error');
                       }
                   }
                   else if($context === 'contact')
                   {
                       $contactId = $this->_getCustomerContactEmail($id);
                       $communicationId = $this->_logEmail($context, $templateId, $id);
                       $sendResult = $this->_sendEmail($communicationId, $context, $templateId, $id);
                       if($sendResult)
                       {
                           $counterSent++;
                           
                       }
                       else
                       {
                           $counterUnsent++;
                           $this->_changeEmailStatus($communicationId, 'error');
                       }
                   }
                   else if($context === 'accreditation')
                   {
                       $contactId = $this->_getAccreditationContactEmail($id);
                       $communicationId = $this->_logEmail($context, $templateId, $contactId, $id);
                       $sendResult = $this->_sendEmail($communicationId,$context, $templateId, $contactId, null, $id);
                       if($sendResult)
                       {
                           $counterSent++;
                           
                       }
                       else
                       {
                           $counterUnsent++;
                           $this->_changeEmailStatus($communicationId, 'error');
                       }
                   }
                   else if($context === 'certification')
                   {
                       $contactId = $this->_getCertificationContactEmail($id);
                       $communicationId = $this->_logEmail($context, $templateId, $contactId, $id);
                       $sendResult = $this->_sendEmail($communicationId,$context, $templateId, $contactId, null, null, $id);
                       if($sendResult)
                       {
                           $counterSent++;
                           
                       }
                       else
                       {
                           $counterUnsent++;
                           $this->_changeEmailStatus($communicationId, 'error');
                       }
                   }
               }
                if($counterUnsent > 0)
                {
                    exit('e2');
                }
                echo $counterSent;
                exit();
            }
        }
        private function _getCustomerContactEmail($customerId)
        {
            $this->loadModel('Customer');
            $customer = $this->Customer->findById($customerId);
            
           
            // has a contact list and a primary contact chosen.
            if(!empty($customer['Contact']) && $customer['Customer']['primary_contact_id'] !== null)
            {
                foreach($customer['Contact'] as $contact)
                {
                    if($contact['id'] === $customer['Customer']['primary_contact_id'])
                    {
                        return $contact['id'];
                    }
                }
            }
            
            // did not find primary contact - use first contact listed
            if(!empty($customer['Contact']))
                return $customer['Contact'][0]['id'];

     // does not - have to use quickbooks information
     return null;
 
        }
        
        
        
        private function _getCertificationContactEmail($certId)
        {
            $this->loadModel('ContactCertification');
            $cert = $this->ContactCertification->findById($certId);
            
           
            // has a contact list and a primary contact chosen.
            if(!empty($cert['Contact']))
            {
                return $cert['Contact']['id'];
            }
            
            // did not find primary contact - use first contact listed
           
     // does not - have to use quickbooks information
     return null;
 
        }
        private function _getAccreditationContactEmail($accredId)
        {
            $this->loadModel('CustomerAccreditation');
            $accreditation = $this->CustomerAccreditation->findById($accredId);
            $this->loadModel('Customer');
            $customer = $this->Customer->findById($accreditation['Customer']['id']);
            
           
            // has a contact list and a primary contact chosen.
            if(!empty($customer['Contact']) && $customer['Customer']['primary_contact_id'] !== null)
            {
                foreach($customer['Contact'] as $contact)
                {
                    if($contact['id'] === $customer['Customer']['primary_contact_id'])
                    {
                        return $contact['id'];
                    }
                }
            }
            
            // did not find primary contact - use first contact listed
            if(!empty($customer['Contact']))
                return $customer['Contact'][0]['id'];

     // does not - have to use quickbooks information
     return null;
 
        }
        private function _getCustomerHtml($html, $customer, $contactId = null)
        {
            $i = 0;
            $htmlArray = preg_split("/([{}])/", $html);
  
            $htmlNewString = "";
            foreach($htmlArray as $entry):
                if($i == 0 || $i == 2)
                    $htmlNewString .= $entry;
                else
                {

                    $anotherArray = explode(".", $entry);
                    if(isset($customer[$anotherArray[0]]) && !empty($customer[$anotherArray[0]]))
                    {
                        if($anotherArray[0] === 'Contact' && isset($contactId))
                            {
                                $this->loadModel('Contact');
                                $cTemp = $this->Contact->findById($contactId);
                                $htmlNewString .= $cTemp['Contact'][$anotherArray[1]];
                            }
                        else if(isset($customer[$anotherArray[0]][0]) && is_array($customer[$anotherArray[0]][0]))
                        {
                            $htmlNewString .= "[";
                            $first = true;
                            
                            foreach($customer[$anotherArray[0]] as $row)
                            {
                                if(!$first)
                                    $htmlNewString .= ", ";
                                
                                $first = false;
                                //check if date, and format.
                                $newInfo = $row[$anotherArray[1]];
                                if((bool)strtotime($newInfo))
                                    $newInfo = date('m/d/Y', strtotime($newInfo));
                                
                                $htmlNewString .= $newInfo;
                            }
                            $htmlNewString .= "]";
                                
                        }
                        else {
                        $htmlNewString .= $customer[$anotherArray[0]][$anotherArray[1]];
                        }
                    }
                }
                
                if($i==2)
                    $i = 1;
                else
                    $i++;
            endforeach;
            
            return $htmlNewString;
        }
        private function _getAccreditationHtml($html, $accreditation, $contactId)
        {
            $i = 0;
            $htmlArray = preg_split("/([{}])/", $html);
            if(isset($contactId))
                            {

                                $this->loadModel('Contact');
                                $cTemp = $this->Contact->findById($contactId);
                                $accreditation['Contact'] = $cTemp['Contact'];
                                
                            }

            $htmlNewString = "";
            foreach($htmlArray as $entry):
                if($i == 0 || $i == 2)
                    $htmlNewString .= $entry;
                else
                {

                    $anotherArray = explode(".", $entry);
                   
                    if(isset($accreditation[$anotherArray[0]]) && !empty($accreditation[$anotherArray[0]]))
                    {
                         if(isset($accreditation[$anotherArray[0]][0]) && is_array($accreditation[$anotherArray[0]][0]))
                        {
                            $htmlNewString .= "[";
                            $first = true;
                            
                            foreach($accreditation[$anotherArray[0]] as $row)
                            {
                                if(!$first)
                                    $htmlNewString .= ", ";
                                
                                $first = false;
                                //check if date, and format.
                                $newInfo = $row[$anotherArray[1]];
                                if((bool)strtotime($newInfo))
                                    $newInfo = date('m/d/Y', strtotime($newInfo));
                                
                                $htmlNewString .= $newInfo;
                            }
                            $htmlNewString .= "]";
                                
                        }
                        else {
                        $htmlNewString .= $accreditation[$anotherArray[0]][$anotherArray[1]];
                        }
                    }
                }
                
                if($i==2)
                    $i = 1;
                else
                    $i++;
            endforeach;
            
            return $htmlNewString;
        }
        
        
        
         private function _getContactHtml($html, $contact)
        {
            $i = 0;
            $htmlArray = preg_split("/([{}])/", $html);
  
            $htmlNewString = "";
            foreach($htmlArray as $entry):
                if($i == 0 || $i == 2)
                    $htmlNewString .= $entry;
                else
                {

                    $anotherArray = explode(".", $entry);
                    if(isset($contact[$anotherArray[0]]) && !empty($contact[$anotherArray[0]]))
                    {
                        if(isset($contact[$anotherArray[0]][0]) && is_array($contact[$anotherArray[0]][0]))
                        {
                            $htmlNewString .= "[";
                            $first = true;
                            
                            foreach($contact[$anotherArray[0]] as $row)
                            {
                                if(!$first)
                                    $htmlNewString .= ", ";
                                
                                $first = false;
                                //check if date, and format.
                                $newInfo = $row[$anotherArray[1]];
                                if((bool)strtotime($newInfo))
                                    $newInfo = date('m/d/Y', strtotime($newInfo));
                                
                                $htmlNewString .= $newInfo;
                            }
                            $htmlNewString .= "]";
                                
                        }
                        else {
                        $htmlNewString .= $contact[$anotherArray[0]][$anotherArray[1]];
                        }
                    }
                }
                
                if($i==2)
                    $i = 1;
                else
                    $i++;
            endforeach;
            
            return $htmlNewString;
        }
        private function _sendEmail($communicationId,$context, $templateId, $contactId = null, $customerId = null, $accreditationId = null, $certificationId = null)
        {
            if($accreditationId !== null)
            {
                $this->loadModel('CustomerAccreditation');
                $accreditation = $this->CustomerAccreditation->findById($accreditationId);
                
                $customerId = $accreditation['CustomerAccreditation']['customer_id'];
            }
             if($certificationId !== null)
            {
                $this->loadModel('ContactCertification');
                $certification = $this->ContactCertification->findById($certificationId);
                
               
            }
            if($customerId !== null)
                $customer = $this->Customer->findById($customerId);
            
            
            if($contactId === null && ($customerId === null || (isset($customer) && empty($customer['Customer']['email']))))
                return false;
            
            $sendEmail = null;
            if($contactId === null)
                $sendEmail = $customer['Customer']['email'];
            else
            {
                $this->loadModel('Contact');
                $contact = $this->Contact->findById($contactId);
                $sendEmail = $contact['Contact']['email'];
            }
            
            $this->loadModel('EmailTemplate');
            $template = $this->EmailTemplate->findById($templateId);
            
            $emailHtml = "";
            switch($context) { 
                case 'customer': 
                    $emailHtml = $this->_getCustomerHtml($template['EmailTemplate']['content'], $customer,$contactId);
                    break;
                case 'contact': 
                    $emailHtml = $this->_getContactHtml($template['EmailTemplate']['content'],$contact);
                    break;
                case 'accreditation':
                    $emailHtml = $this->_getAccreditationHtml($template['EmailTemplate']['content'],$accreditation, $contactId);
                    break;
                case 'certification':
                    $emailHtml = $this->_getContactHtml($template['EmailTemplate']['content'], $certification);
                    break;
                default:
                    break;
            }
            
            App::uses('CakeEmail', 'Network/Email');
                            $to = array('bobby@net2sky.com');
                            $email = new CakeEmail('smtp');
                            $email->template('comm', 'default')
                            ->emailFormat('html')
                            ->subject($template['EmailTemplate']['subject'])
                            ->viewVars(array('description' => $template['EmailTemplate']['subject'],
                                'content' => $emailHtml, 'communicationId' => $communicationId, 'config' => $this->config))
                            ->to($to);
                            
                            if($email->send())
                                return true;
                            else
                                return false;

        }
        private function _logEmail($context, $templateId, $contactId = null, $customerId = null, $error = 'sent')
        {
            $this->loadModel('Communication');
            $newRecord = array('context' => $context,
                    'email_template_id' => $templateId,
                    'user_id' => $this->Auth->user('id'));
            
            $this->loadModel('EmailTemplate');
            $template = $this->EmailTemplate->findById($templateId);
            $newRecord['template_name'] = $template['EmailTemplate']['name'];
            $newRecord['template_subject'] = $template['EmailTemplate']['subject'];
            
                $newRecord['customer_id'] = $customerId;
                $newRecord['contact_id'] = $contactId;
                $newRecord['result'] = $error;
            $this->Communication->create();
            $this->Communication->save($newRecord);
            return $this->Communication->id;
            
        }
        private function _changeEmailStatus($emailId, $status) {
            $this->loadModel('Communication');
            $this->Communication->findById($emailId);
            $this->Communication->saveField('result', 'error');
            return true;
        }
        public function admin_ajaxLoadTemplate($id) {
            $this->loadModel('ReportTemplate');
            $data = $this->ReportTemplate->findById($id);
            $data['ReportTemplate']['fields'] = json_decode($data['ReportTemplate']['fields'], true);
            $data['ReportTemplate']['conditions'] = json_decode($data['ReportTemplate']['conditions'], true);
           // pr($data['ReportTemplate']);
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
              //  unset($defaults['CustomerGroup']);
                
                unset($defaults['Customer']['contact']);
                unset($defaults['CustomerFile']);
                
                
            }
            if($context ==='Contact')
            {
                unset($defaults['Job']);
             //   unset($defaults['ContactGroup']);
             //   unset($defaults['ContactCertification']);
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
                'fields' => 'DISTINCT Customer.id, *',
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
                    array(
                    'table' => 'customer_accreditations',
                    'alias' => 'CustomerAccreditation',
                    'type' => 'LEFT',
                    'conditions' => '`CustomerAccreditation`.`customer_id` = `Customer`.`id`'
                        ),
                    array(
                    'table' => 'accreditations',
                    'alias' => 'Accreditation',
                    'type' => 'LEFT',
                    'conditions' => '`CustomerAccreditation`.`accreditation_id` = `Accreditation`.`id`'
                        ),
                    
                ),
                //'conditions' => 'CustomerAccreditation.expiration_date < "2017-07-04 00:00:00"'));
                'conditions' => $this->request->data['conditions']));
           // pr($results); exit();
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
                       'title' => ucwords(str_replace(".", "<br />\n\r ", str_replace("_", " ", $field))),
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
           public function admin_runContactReport($context,$criteria=null, $fields=null,$export=null)
        {
            $this->layout = 'ajax';
           if($this->request->is('post')) {
             
               
            $this->loadModel($context);
            $this->$context->unbindModel(array('hasMany' => array('Job')));
            
            if(trim(substr($this->request->data['conditions'],-4)) === "OR )")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . ")";
            if(trim(substr($this->request->data['conditions'],-4)) === "OR")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . "";
            
            if(empty($this->request->data['conditions']))
                $this->request->data['conditions'] = "(ContactCustomer.archived is null)";
            else
                $this->request->data['conditions'] .= " AND (ContactCustomer.archived is null)";

            $results = $this->$context->find('all', array(
                //'recursive' => -1,
                'fields' => array('DISTINCT Contact.id','*'),
                'joins' => array(
                    array(
                    'table' => 'contact_groups',
                    'alias' => 'ContactGroup',
                    'type' => 'LEFT',
                    'conditions' => '`ContactGroup`.`contact_id` = `Contact`.`id`'
                    
                        ),
                    array(
                    'table' => 'groups',
                    'alias' => 'Group',
                    'type' => 'LEFT',
                    'conditions' => '`ContactGroup`.`group_id` = `Group`.`id`'
                        ),
                    array(
                    'table' => 'contact_customers',
                    'alias' => 'ContactCustomer',
                    'type' => 'LEFT',
                    'conditions' => 'ContactCustomer.contact_id = Contact.id AND ContactCustomer.archived is null'
                        ),
                    array(
                    'table' => 'customers',
                    'alias' => 'Customer',
                    'type' => 'LEFT',
                    'conditions' => '`ContactCustomer`.`customer_id` = `Customer`.`id`'
                        ),
                    array(
                    'table' => 'contact_certifications',
                    'alias' => 'ContactCertification',
                    'type' => 'LEFT',
                    'conditions' => '`ContactCertification`.`contact_id` = `Contact`.`id`'
                        )
                    
                ),
                //'conditions' => 'CustomerAccreditation.expiration_date < "2017-07-04 00:00:00"'));
                'conditions' => $this->request->data['conditions']));
         //   pr($results); exit();
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
                       'title' => ucwords(str_replace(".", "<br />\n\r ", str_replace("_", " ", $field))),
                       'data' => str_replace(".", "-",$field),
                       'class' =>'show-on-export');
                        
               }
               
               $counter =0;
            foreach($results as $result)
            {
                
               $final[$counter] = $innerFieldArray;
              $final[$counter]['select-box'] = "<input type='checkbox' class='report-select' data-id='" . $result['Contact']['id'] . "' />";
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
     //     pr($final); exit();
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
           
            public function admin_runCertificationReport($context,$criteria=null, $fields=null,$export=null)
        {
            
            $this->layout = 'ajax';
           if($this->request->is('post')) {
             
               
            $this->loadModel($context);
          //  $this->$context->unbindModel(array('hasMany' => array('Job')));
            
            if(trim(substr($this->request->data['conditions'],-4)) === "OR )")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . ")";
            if(trim(substr($this->request->data['conditions'],-4)) === "OR")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . "";
            
            $results = $this->$context->find('all', array(
                'recursive' => 1, 
                'conditions' => $this->request->data['conditions']));
           // pr($results); exit();
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
                       'title' => ucwords(str_replace(".", "<br />\n\r ", str_replace("_", " ", $field))),
                       'data' => str_replace(".", "-",$field),
                       'class' =>'show-on-export');
                        
               }
               
               $counter =0;
            foreach($results as $result)
            {
                
               $final[$counter] = $innerFieldArray;
              $final[$counter]['select-box'] = "<input type='checkbox' class='report-select' data-id='" . $result['ContactCertification']['id'] . "' />";
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
          //  pr($final); exit();
            $this->loadModel('JsonReport');
            $this->JsonReport->create();
            $this->JsonReport->save(array('json' => json_encode($return)));
            echo $this->JsonReport->id;
            exit();
           
           }
           
            $this->set('results', $final);
            $this->set('fields', $this->request->data['fields']);
        }
        
        public function admin_runAccreditationReport($context,$criteria=null, $fields=null,$export=null)
        {
            
            $this->layout = 'ajax';
           if($this->request->is('post')) {
             
               
            $this->loadModel($context);
          //  $this->$context->unbindModel(array('hasMany' => array('Job')));
            
            if(trim(substr($this->request->data['conditions'],-4)) === "OR )")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . ")";
            if(trim(substr($this->request->data['conditions'],-4)) === "OR")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . "";
         //   pr($this->request->data['conditions']); exit();
            $results = $this->$context->find('all', array(
                'recursive' => 2, 
                'conditions' => $this->request->data['conditions']));
         //   pr($results); exit();
            $final = array();
            $returnFields = array();
            $innerFieldArray = array();
            $returnFields[] = array(
                       'title' => "",
                       'orderable' => false,
                       'targets' => 0,
                       
                       'searchable' => false,
                       'data' => 'select-box');
            $contactNeeded = false;
            if(in_array('Contact', array_keys($this->request->data['fields'])))
            {
                $contactNeeded = true;
            }
            foreach($this->request->data['fields'] as $field)
               {
                   
                   $innerFieldArray[str_replace(".", "-",$field)] = null;
                   $returnFields[] = array(
                       'title' => ucwords(str_replace(".", "<br />\n\r ", str_replace("_", " ", $field))),
                       'data' => str_replace(".", "-",$field),
                       'class' =>'show-on-export');
                        
               }
               
               $counter =0;
            foreach($results as $result)
            {
                $contact = null;
                $primary = null;
                if($contactNeeded)
                {
                    $this->loadModel('Contact');
                    $contact = $this->Contact->find('all', array(
                        'Customer.id' => $result['Customer']['id']
                    ));
                    foreach($contact as $c)
                    {
                        if($c['Contact']['id'] === $result['Customer']['primary_contact_id'])
                        {
                            $primary = $c;
                        }
                    }
                    if($primary === null && !empty($contact))
                        $primary = $contact[0];
                    
                    if($primary !== null)
                        $result['Contact'] = $primary['Contact'];
                    unset($primary);
                    unset($contact);
                }
               $final[$counter] = $innerFieldArray;
              $final[$counter]['select-box'] = "<input type='checkbox' class='report-select' data-id='" . $result['CustomerAccreditation']['id'] . "' />";
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
          //  pr($final); exit();
            $this->loadModel('JsonReport');
            $this->JsonReport->create();
            $this->JsonReport->save(array('json' => json_encode($return)));
            echo $this->JsonReport->id;
            exit();
           
           }
           
            $this->set('results', $final);
            $this->set('fields', $this->request->data['fields']);
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
            $this->loadModel('Accreditation');
            $this->set('accredTypes', $this->Accreditation->find('list'));
            $this->set('fields', $this->_reportingFields('Customer'));
            $this->set('defaultExportTitle', 'CustomerQuery-' . date("mdY"));
        }
        function admin_contact() {
            $this->loadModel('Group');
            $this->set('groups', $this->Group->find('list', array('conditions'=> array('is_contact'=> 1))));
            $this->loadModel('ContactType');
            $this->set('types', $this->ContactType->find('list'));
            $this->loadModel('Customer');
            $this->set('organizations', $this->Customer->find('list', array('fields' => array('Customer.id', 'Customer.name'))));
            $this->set('contactSources', explode("|", $this->config['contact.sources']));
            $this->loadModel('EmailTemplate');
            $this->set('templateOptions', $this->EmailTemplate->find('list', array('conditions' => array(
                'context' => 'Contact'
            ))));
            $this->loadModel('Certification');
            $this->set('certTypes', $this->Certification->find('list'));
            $this->set('fields', $this->_reportingFields('Contact'));
            $this->set('defaultExportTitle', 'ContactQuery-' . date("mdY"));
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
            $this->set('defaultExportTitle', 'AccreditationQuery-' . date("mdY"));
        }
        public function admin_certification()
        {
            $this->loadModel('Certification');
            $accred = $this->Certification->find('list');
            $this->set('certTypes', $accred);
            $this->set('fields', $this->_reportingFields('ContactCertification'));
            
            $this->loadModel('EmailTemplate');
            $this->set('templateOptions', $this->EmailTemplate->find('list', array('conditions' => array(
                'context' => 'ContactCertification'
            ))));
            $this->set('defaultExportTitle', 'CertificationQuery-' . date("mdY"));
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
              //  unset($defaults['CustomerAccreditation']);
                unset($defaults['Customer']['contact']);
                
            }
            if($context ==='Contact')
            {
                unset($defaults['Job']);
                unset($defaults['ContactGroup']);
             $defaults['ContactCertification'] = array();
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
           // pr(array_keys($this->ContactCertification->getColumnTypes()));
            
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
            $contextOptions  = array('Contact'=>'Contacts', 'Customer' => 'Customers');           
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
                $string .= "\n<li onclick='loadToEdit(" . $option['EmailTemplate']['id'] . ")'><div style='display: inline-block;'>" . $option['EmailTemplate']['name'] . "<br /><em>(" . $contextOptions[$option['EmailTemplate']['context']] . ")</em></div>";
                
                $string .= '<a role="button" class="btn btn-small btn-danger delete-object" data-toggle="modal" data-object-name="' . $option['EmailTemplate']['name'] . '" data-object-id="' . $option['EmailTemplate']['id'] . '"><i class="fa fa-trash-o"></i> Delete</a>';
                
                $string .= "</li>";
                
            }
            $string .= "</ul>";
            return $string;
        }
    }
    
