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
                       $sendResult = $this->_sendEmail($communicationId,$context, $templateId, $id);
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
                       $sendResult = $this->_sendEmail($communicationId,$context, $templateId, $id);
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
                   else if($context === 'organization-training')
                   {
                       $contactId = $this->_getOrganizationTrainingContactEmail($id);
                       $communicationId = $this->_logEmail($context, $templateId, $contactId, $id);
                       $sendResult = $this->_sendEmail($communicationId,$context, $templateId, $id);
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
                   else if($context === 'contact-training')
                   {
                       $this->loadModel('Job');
            $this->Job->unbindModel(array('hasMany' => array('ScheduleEntry','JobTaskList'),
                'belongsTo' => array('Customer')));
            $this->Job->bindModel(array('hasMany' => array(
                'JobAttendee'
            )));
            $this->loadModel('JobAttendee');
            $this->JobAttendee->bindModel(array('belongsTo' => array(
                'Contact', 'Job'
            )));
                       $contactId = $this->_getContactPortalContactEmail($id);
                       $communicationId = $this->_logEmail($context, $templateId, $contactId, $id);
                       $sendResult = $this->_sendEmail($communicationId,$context, $templateId, $id);
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
                   else if($context === 'organization-portal')
                   {
                       $contactId = $this->_getPortalContactEmail($id);
                       $communicationId = $this->_logEmail($context, $templateId, $contactId, $id);
                       $sendResult = $this->_sendEmail($communicationId,$context, $templateId, $id);
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
                   else if($context === 'contact-portal')
                   {
                       
                       $contactId = $this->_getPortalContactEmail($id);
                       $communicationId = $this->_logEmail($context, $templateId, $contactId, $id);
                       $sendResult = $this->_sendEmail($communicationId,$context, $templateId, $id);
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
                   else
                   {
                       pr($context);
                       exit();
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
        
        private function _getOrganizationTrainingContactEmail($id)
        {
            
            $this->loadModel('Job');
            $training = $this->Job->findById($id);
            
            return $this->_getCustomerContactEmail($training['Customer']['id']);
 
        }
        
        private function _getContactTrainingContactEmail($id)
        {
            
            
            $training = $this->JobAttendee->findById($id);
            
            return $training['JobAttendee']['contact_id'];
 
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
        private function _getPortalContactEmail($portalId)
        {
            $this->loadModel('Portal');
            $portal = $this->Portal->findById($portalId);
            
            if($portal['Portal']['customer_id'] !== null ) {
            $this->loadModel('Customer');
            $customer = $this->Customer->findById($portal['Customer']['id']);
            
           
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

            }
            else
            {
                return $portal['Portal']['contact_id'];
            }
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
        private function _getHtml($html, $context)
        {
           
            $this->loadModel('AvailableField');
            $i = 0;
            $htmlArray = preg_split("/([{}])/", $html);
  
            $htmlNewString = "";
           
            foreach($htmlArray as $entry):
                if($i == 0 || $i == 2)
                    $htmlNewString .= $entry;
                else
                {

                    $field = $this->AvailableField->findByPrettyName($entry);
                    
                    if($field['AvailableField']['compact_field'] === "0")
                    {
                        $htmlNewString .= $context[$field['AvailableField']['model_name']]
                                [$field['AvailableField']['field_name']];
                    }
                    else
                    {
                       
                        switch($field['AvailableField']['category'])
                        {
                            case "Contact":
                                $htmlNewString .= $this->_fetchComplexFieldInfoContact(
                                        $field['AvailableField']['id'],
                                        $context['Contact']['id'] 
                                );
                                break;
                            case "Organization":
                                $htmlNewString .= $this->_fetchComplexFieldInfoCustomer(
                                        $field['AvailableField']['id'],
                                        $context['Customer']['id']
                                );
                                break;
                            case "Job":
                                $htmlNewString .= $this->_fetchComplexFieldInfoJob(
                                        $field['AvailableField']['id'],
                                        $context['Job']['id']
                                );
                                break;
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
         
        private function _sendEmail($communicationId,$context, $templateId, $id = null)
        {
            
           
            
            $this->loadModel('EmailTemplate');
            $template = $this->EmailTemplate->findById($templateId);
            $contactId = null;
            $emailHtml = "";
           
            switch($context) { 
                case 'customer': 
                    $this->loadModel('Customer');
                    $customer = $this->Customer->findById($id);
                    $contactId = $this->_getCustomerContactEmail($id);
                    $emailHtml = $this->_getHtml($template['EmailTemplate']['content'], $customer);
                    break;
                case 'contact': 
                    $this->loadModel('Contact');
                    $contactId = $id;
                    $contact = $this->Contact->findById($id);
                    $emailHtml = $this->_getHtml($template['EmailTemplate']['content'],$contact);
                    break;
                case 'accreditation':
                    $this->loadModel('CustomerAccreditation');
                    $accreditation = $this->CustomerAccreditation->findById($id);
                    $contactId = $this->_getAccreditationContactEmail($id);
                    $emailHtml = $this->_getHtml($template['EmailTemplate']['content'],$accreditation);
                    break;
                case 'certification':
                    $this->loadModel('ContactCertification');
                    $certification = $this->ContactCertification->findById($id);
                    $contactId = $certification['Contact']['id'];
                    $emailHtml = $this->_getHtml($template['EmailTemplate']['content'], $certification);
                    break;
                case 'organization-training':
                    $this->loadModel('Job');
                    $training = $this->Job->findById($id);
                    $contactId = $this->_getOrganizationTrainingContactEmail($id);
                    $emailHtml = $this->_getHtml($template['EmailTemplate']['content'],$training);
                    break;
                case 'contact-training':
                    $this->loadModel('Job');
            $this->Job->unbindModel(array('hasMany' => array('ScheduleEntry','JobTaskList'),
                'belongsTo' => array('Customer')));
            $this->Job->bindModel(array('hasMany' => array(
                'JobAttendee'
            )));
            $this->loadModel('JobAttendee');
            $this->JobAttendee->bindModel(array('belongsTo' => array(
                'Contact', 'Job'
            )));
                $training = $this->JobAttendee->findById($id);
                $contactId = $this->_getContactTrainingContactEmail($id);
                $emailHtml = $this->_getHtml($template['EmailTemplate']['content'],$training);
                break;
                case 'organization-portal':
                    $this->loadModel('Portal');
                    $portal = $this->Portal->findById($id);
                    $contactId = $this->_getPortalContactEmail($id);
                    $emailHtml = $this->_getHtml($template['EmailTemplate']['content'], $portal);
                    break;
                case 'contact-portal':
                    $this->loadModel('Portal');
                    $portal = $this->Portal->findById($id);
                    $contactId = $this->_getPortalContactEmail($id);
                    $emailHtml = $this->_getHtml($template['EmailTemplate']['content'], $portal);
                    break;
                
                default:
                    break;
            }
            
             $sendEmail = null;
            if($contactId === null) {
                if(isset($customer))
                    $sendEmail = $customer['Customer']['email'];
                if(isset($accreditation))
                    $sendEmail = $accreditation['Customer']['email'];
            }
            else
            {
                $this->loadModel('Contact');
                $contact = $this->Contact->findById($contactId);
                $sendEmail = $contact['Contact']['email'];
            }
            
            
            App::uses('CakeEmail', 'Network/Email');
                            $to = array($sendEmail);
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
            if($context === 'OrganizationTraining')
            {
                $category = array('Job','Organization');
            }
            if($context === 'ContactTraining')
            {
                $category = array('Job','Contact');
            }
             if($context === 'OrganizationPortal')
            {
                $category = array('Portal','Organization');
            }
            if($context === 'ContactPortal')
            {
                $category = array('Portal','Contact');
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
            
//            
//            else
//                $mainContext = $context;
//            
            $this->loadModel($mainContext);
            
            
            $defaults = $this->$mainContext->find('first');
            if($context ==='Customer')
            {
                unset($defaults['Job']);
              //  unset($defaults['CustomerGroup']);
                
                unset($defaults['Customer']['contact']);
                unset($defaults['CustomerFile']);
                
                
            }
            if($context ==='OrganizationTraining')
            {
               // unset($defaults['Job']);
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
        private function _getSearchFields($fields)
        {
           
            
            $list = array();
            foreach($fields as $f)
            {
                $temp = explode(".", $f);
                $list[] = $temp[1];
            }
            
            $this->loadModel('AvailableField');
            $result = $this->AvailableField->find('all', array(
                'conditions' => array(
                    'AvailableField.id' => $list
                )
            ));
         
            $return = array('compact' => array(), 'search' => array(), 'all' => array());
            foreach($result as $r)
            {
                if($r['AvailableField']['compact_field'] === '1')
                    $return['compact'][] = $r['AvailableField'];
                else
                    $return['search'][] = $r['AvailableField']['model_name'] . "." . 
                        $r['AvailableField']['field_name'];
                
                $return['all'][$r['AvailableField']['model_name'] . "." . 
                        $r['AvailableField']['field_name']] = $r['AvailableField']['pretty_name'];
            }
        //    pr($return);
            return $return;
            
        }
        private function _fetchComplexFieldInfoCustomer($id, $contextId)
        {
            $this->loadModel('Customer');
            $customer = $this->Customer->findById($contextId);
            if(empty($customer))
                return false;
            
            if($id === "18")
            {
                // Customer Primary Contact
                if(isset($customer['Customer']['primary_contact_id']))
                {
                    $this->loadModel('Contact');
                    $primary = $this->Contact->findById($customer['Customer']['primary_contact_id']);
                    $returnVal = $primary['Contact']['first_name'] . " " . $primary['Contact']['last_name'] .
                            "<br />" . $primary['Contact']['email'];
                    return $returnVal;
                }
                else
                {
                    return "--- None Listed ---";
                }
            }
            
            if($id === "19")
            {
                // customer addresses
               
                $i = 0;
                $returnVal = "";
                if(!empty($customer['CustomerAddress']))
                {
                    foreach($customer['CustomerAddress'] as $address){
                if($i>0)
                    $returnVal .= "<br />-----------------------------<br />";
                
                
                $returnVal .= "(" . ucfirst($address['type']) . ")<br />" . 
                        $address['address_1'] . "<br />";
                if(!empty($address['address_2']))
                {
                    $returnVal.= $address['address_2'] . "<br />";
                }
                $returnVal .= $address['city'] . ", " . $address['state'] . " " . $address['zip'];
                $i++;
                
                    }
                }
                else
                {
                    $returnVal = "(QuickBooks Billing)<br />" . 
                            $customer['Customer']['bill_addr1'] . "<br />";
                    if(!empty($customer['Customer']['bill_addr2']))
                {
                    $returnVal.= $customer['Customer']['bill_addr2'] . "<br />";
                }
                $returnVal .= $customer['Customer']['bill_city'] . ", " . 
                        $customer['Customer']['bill_state'] . " " . $customer['Customer']['bill_zip'];
                    
                }

                return $returnVal;
            }
            if($id === "20")
            {
                // customer addresses
              
                $i = 0;
                $returnVal = "";
                if(!empty($customer['CustomerPhone']))
                {
                    foreach($customer['CustomerPhone'] as $phone){
                if($i>0)
                    $returnVal .= "<br />-----------------------------<br />";
                
                
                $returnVal .= "(" . ucfirst($phone['type']) . ")<br />" . 
                        $phone['phone'];
                if(!empty($phone['ext']))
                {
                    $returnVal.= " x" . $phone['ext'];
                }
                $i++;
                    }
                }

                return $returnVal;
            }
            if($id === "21")
            {
                // customer groups
              $this->loadModel('Group');
                $i = 0;
                $returnVal = "";
                if(!empty($customer['CustomerGroup']))
                {
                    foreach($customer['CustomerGroup'] as $group){
                if($i>0)
                    $returnVal .= ", ";
                
                $realGroup = $this->Group->findById($group['group_id']);
                
                $returnVal .= $realGroup['Group']['name'];
                $i++;
                    }
                }

                return $returnVal;
            }
            if($id === "22")
            {
                // customer source
              $this->loadModel('Source');
                $i = 0;
                $returnVal = "";
                if(!empty($customer['Customer']['source']))
                {
               
                $realGroup = $this->Source->findById($customer['Customer']['source']);
                
                $returnVal .= $realGroup['Source']['name'];
                
                    
                }

                return $returnVal;
            }
        }
         private function _fetchComplexFieldInfoContact($id, $contextId)
        {
             
            $this->loadModel('Contact');
            $contact = $this->Contact->findById($contextId);
            
           // pr($contact);
            if($id === "32")
            {
                // Contact Type
                if(!empty($contact['Contact']['contact_type']))
                {
                    $this->loadModel('ContactType');
                    $types = explode("|", $contact['Contact']['contact_type']);
                    $list = $this->ContactType->find('list', array(
                        'conditions' => array(
                            'ContactType.id' => $types
                        ),
                        'fields' => array('ContactType.id', 'ContactType.name')
                    ));
                    $returnVal = implode(", ", $list);
                    return $returnVal;
                }
                else
                {
                    return "--- None Listed ---";
                }
            }
            
            if($id === "39")
            {
                // customer addresses
               
                $i = 0;
                $returnVal = "";
                if(!empty($contact['ContactAddress']))
                {
                    foreach($contact['ContactAddress'] as $address){
                if($i>0)
                    $returnVal .= "<br />-----------------------------<br />";
                
                
                $returnVal .= "(" . ucfirst($address['type']) . ")<br />" . 
                        $address['address_1'] . "<br />";
                if(!empty($address['address_2']))
                {
                    $returnVal.= $address['address_2'] . "<br />";
                }
                $returnVal .= $address['city'] . ", " . $address['state'] . " " . $address['zip'];
                $i++;
                
                    }
                }
                else
                {
                    $returnVal = "--- None Listed ---";
                    
                }

                return $returnVal;
            }
            if($id === "38")
            {
                // contact phone
              
                $i = 0;
                $returnVal = "";
                if(!empty($contact['ContactPhone']))
                {
                    foreach($contact['ContactPhone'] as $phone){
                if($i>0)
                    $returnVal .= "<br />-----------------------------<br />";
                
                
                $returnVal .= "(" . ucfirst($phone['type']) . ")<br />" . 
                        $phone['phone'];
                if(!empty($phone['ext']))
                {
                    $returnVal.= " x" . $phone['ext'];
                }
                $i++;
                    }
                }
                else
                {
                    $returnVal = "--- None Listed ---";
                }

                return $returnVal;
            }
            if($id === "40")
            {
                
               
                // Contact groups
              $this->loadModel('Group');
                $i = 0;
                $returnVal = "";
                if(!empty($contact['ContactGroup']))
                {
                    foreach($contact['ContactGroup'] as $group){
                if($i>0)
                    $returnVal .= ", ";
                
                $realGroup = $this->Group->findById($group['group_id']);
                
                $returnVal .= $realGroup['Group']['name'];
                $i++;
                    }
                    
                }
                else
                    $returnVal = "--- None Listed ---";

                return $returnVal;
            }
            if($id === "36")
            {
                // contact source
              $this->loadModel('Source');
                $i = 0;
                $returnVal = "";
                if(!empty($contact['Contact']['source']))
                {
               
                $realGroup = $this->Source->findById($contact['Contact']['source']);
                
                $returnVal .= $realGroup['Source']['name'];
                
                    
                }

                return $returnVal;
            }
            
            if($id === "37")
            {
                $returnVal = "";
                // Linked Customers
                if(!empty($contact['Customer']))
                {
                    foreach($contact['Customer'] as $customer)
                    {
                        $returnVal .= $customer['name'] . "<br />";
                    }
                   
                }
                else
                {
                    $returnVal = "--- None Listed ---";
                }
                return $returnVal;
                
                
            }
        }
        private function _fetchComplexFieldInfoJob($id, $contextId)
        {
            $this->loadModel('Job');
            $job = $this->Job->findById($contextId);
           
           
            if($id === "10")
            {
                // Job Address
                $string = "";
                if(!empty($job['Job']['state']))
                {
                    // Use actual job address
                    $string = $job['Job']['addr1']. "<br />";
                    if(!empty($job['Job']['addr2']))
                        $string .= $job['Job']['addr2'] . "<br />";
                    $string .= $job['Job']['city'] . ", " . $job['Job']['state'] . " " . 
                            $job['Job']['zip'];
                }
                else
                {
                    // Use listed address for customer
                    $string = $job['Customer']['bill_addr1'] . "<br />";
                    if(!empty($job['Customer']['bill_addr2']))
                        $string .= $job['Customer']['bill_addr2']. "<br />";
                    $string .= $job['Customer']['bill_city'] . ", " . $job['Customer']['bill_state'] . " " . 
                            $job['Customer']['bill_zip'];
                }
                
                return $string;
                        
            }
            
            
            return false;
        }
        public function admin_runOrganizationTrainingReport($context,$criteria=null, $fields=null,$export=null)
        {
            $this->layout = 'ajax';
            
           if($this->request->is('post')) {
            
           $locationLimit = $this->request->data['locationLimit'];
           
            $this->loadModel('Job');
            $this->Job->unbindModel(array('hasMany' => array('ScheduleEntry','JobTaskList')));
            
            if(trim(substr($this->request->data['conditions'],-4)) === "OR )")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . ")";
            if(trim(substr($this->request->data['conditions'],-4)) === "OR")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . "";
           
            $getFields = $this->_getSearchFields($this->request->data['fields']);
            if($locationLimit !== false)
            {
                $searchable = array_merge(array('Job.id',
                    'Job.customer_id', 'Job.state', 
                    'Job.zip','Customer.bill_state',
                    'Customer.bill_zip','ServiceArea.parent_id'), $getFields['search']);
            }
            else
            {
            $searchable = array_merge(array('Job.id', 'Job.customer_id','ServiceArea.parent_id'),$getFields['search']);
            }
           if(isset($locationLimit['byState']))
           {
               if(!empty($this->request->data['conditions']))
                   $this->request->data['conditions'] .= " AND ";
               $this->request->data['conditions'] .= "("
                       . "(Job.state IN (\"" .
                       implode('","', $locationLimit['byState']) . "\")) OR "
                       . "(Job.state = \"\" AND Customer.bill_state IN (\""
                   . implode('","', $locationLimit['byState']) . "\")"
                       . "))";
           }
           
           if(!empty($this->request->data['conditions']))
           {
               $this->request->data['conditions'] .= " AND ";
           }
           $this->request->data['conditions'] .= "(ServiceArea.parent_id = 2)";
       //   pr($this->request->data); exit();
            $results = $this->Job->find('all', array(
               
                'fields' => $searchable,
                
                //'conditions' => 'CustomerAccreditation.expiration_date < "2017-07-04 00:00:00"'));
                'conditions' => $this->request->data['conditions']));
          //  pr($results); exit();
            if(!empty($getFields['compact']))
            {
                 foreach($results as $i => $res)
                    {
                foreach($getFields['compact'] as $compactField)
                {
                    if(!isset($results[$i][$compactField['model_name']]))
                        $results[$i][$compactField['model_name']] = array();
                    
                    $complexValue = $this->_fetchComplexFieldInfoJob($compactField['id'], $res['Job']['id']);
                    if(!$complexValue)
                    {
                       $complexValue = $this->_fetchComplexFieldInfoCustomer($compactField['id'], $res['Job']['customer_id']);
                    }
                      $results[$i][$compactField['model_name']][$compactField['field_name']] = 
                              $complexValue;
                    }
                }
            }
            
            $final = array();
            $returnFields = array();
            $innerFieldArray = array();
            $returnFields[] = array(
                       'title' => "",
                       'orderable' => false,
                       'targets' => 0,
                       
                       'searchable' => false,
                       'data' => 'select-box');
            foreach($getFields['all'] as $name => $field)
               {
                   
                   $innerFieldArray[str_replace(".", "-",$name)] = null;
                   $returnFields[] = array(
                       'title' => $field,
                       'data' => str_replace(".", "-",$name),
                       'class' =>'show-on-export');
                        
               }
              // pr($innerFieldArray); exit();
               $counter =0;
          //  pr($getFields['all']);
            foreach($results as $result)
            {
                
               $final[$counter] = $innerFieldArray;
              $final[$counter]['select-box'] = "<input type='checkbox' class='report-select' data-id='" . $result['Job']['id'] . "' />";
               foreach($getFields['all'] as  $name => $field)
               {
                   
                   $fieldArray = explode(".", $name);
                   // if not a multi-array
                   if(isset($result[$fieldArray[0]][$fieldArray[1]]) && 
                           !empty($result[$fieldArray[0]]) && 
                           (!isset($result[$fieldArray[0]][0] )||
                           !is_array($result[$fieldArray[0]][0] ))) {
                        $final[$counter][str_replace(".", "-",$name)] = $result[$fieldArray[0]][$fieldArray[1]];
                   }
                   // check if first element is another array - this means a multi-dimensional array
                   elseif(!isset($result[$fieldArray[0]][$fieldArray[1]]) && 
                           !empty($result[$fieldArray[0]]) && 
                           (isset($result[$fieldArray[0]][0]) &&
                           is_array($result[$fieldArray[0]][0])))
                   {
                       foreach($result[$fieldArray[0]] as $subresult) 
                       {
                           
                           $final[$counter][str_replace(".", "-",$name)] .= $subresult[$fieldArray[1]] . "<br />";
                           
                       }
                   }
                   else
                   {
                       $final[$counter][str_replace(".", "-",$name)] = "";
                       
                   }
               }
               
                $counter++;
            }
            $return = array('data' => array('data' => $final), 'columns' => $returnFields);
          //  pr($return); exit();
            $this->loadModel('JsonReport');
            $this->JsonReport->create();
            $this->JsonReport->save(array('json' => json_encode($return)));
            echo $this->JsonReport->id;
            exit();
           
           }
           
            $this->set('results', $final);
            $this->set('fields', $this->request->data['fields']);
           }
           
           public function admin_runContactTrainingReport($context,$criteria=null, $fields=null,$export=null)
        {
            $this->layout = 'ajax';
            
           if($this->request->is('post')) {
            
           $locationLimit = $this->request->data['locationLimit'];
           
            $this->loadModel('Job');
            $this->Job->unbindModel(array('hasMany' => array('ScheduleEntry','JobTaskList'),
                'belongsTo' => array('Customer')));
            $this->Job->bindModel(array('hasMany' => array(
                'JobAttendee'
            )));
            $this->loadModel('JobAttendee');
            $this->JobAttendee->bindModel(array('belongsTo' => array(
                'Contact', 'Job'
            )));
            
            $this->loadModel('ServiceArea');
            $trainingServices = $this->ServiceArea->find('list', array('conditions'=>array(
                'parent_id' => "2"
            ),
                'fields' => array('id', 'name')));
            
            if(trim(substr($this->request->data['conditions'],-4)) === "OR )")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . ")";
            if(trim(substr($this->request->data['conditions'],-4)) === "OR")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . "";
           
            $getFields = $this->_getSearchFields($this->request->data['fields']);
            if($locationLimit !== false)
            {
                $searchable = array_merge(array('Job.id',
                    'Job.customer_id', 'Job.state', 
                    'Job.zip','ServiceArea.parent_id'), $getFields['search']);
            }
            else
            {
            $searchable = array_merge(array('Job.id', 'Job.customer_id','Job.service_area_id'),$getFields['search']);
            }
           if(isset($locationLimit['byState']))
           {
               if(!empty($this->request->data['conditions']))
                   $this->request->data['conditions'] .= " AND ";
               $this->request->data['conditions'] .= "("
                       . "(Job.state IN (\"" .
                       implode('","', $locationLimit['byState']) . "\")))";
                       
           }
           
           if(!empty($this->request->data['conditions']))
           {
               $this->request->data['conditions'] .= " AND ";
           }
           $this->request->data['conditions'] .= "(Job.service_area_id IN (\"" .
                   implode('","', array_keys($trainingServices)) . "\"))";
       //   pr($this->request->data); exit();
            $results = $this->JobAttendee->find('all', array(
               'recursive' => 1,
               
                //'conditions' => 'CustomerAccreditation.expiration_date < "2017-07-04 00:00:00"'));
                'conditions' => $this->request->data['conditions']));
          //  pr($results); exit();
            if(!empty($getFields['compact']))
            {
                 foreach($results as $i => $res)
                    {
                foreach($getFields['compact'] as $compactField)
                {
                    if(!isset($results[$i][$compactField['model_name']]))
                        $results[$i][$compactField['model_name']] = array();
                    
                    $complexValue = $this->_fetchComplexFieldInfoJob($compactField['id'], $res['Job']['id']);
                    if(!$complexValue)
                    {
                       $complexValue = $this->_fetchComplexFieldInfoContact($compactField['id'], $res['Contact']['id']);
                    }
                      $results[$i][$compactField['model_name']][$compactField['field_name']] = 
                              $complexValue;
                    }
                }
            }
            
            $final = array();
            $returnFields = array();
            $innerFieldArray = array();
            $returnFields[] = array(
                       'title' => "",
                       'orderable' => false,
                       'targets' => 0,
                       
                       'searchable' => false,
                       'data' => 'select-box');
            foreach($getFields['all'] as $name => $field)
               {
                   
                   $innerFieldArray[str_replace(".", "-",$name)] = null;
                   $returnFields[] = array(
                       'title' => $field,
                       'data' => str_replace(".", "-",$name),
                       'class' =>'show-on-export');
                        
               }
              // pr($innerFieldArray); exit();
               $counter =0;
          //  pr($getFields['all']);
            foreach($results as $result)
            {
            //    pr($result); exit();
               $final[$counter] = $innerFieldArray;
              $final[$counter]['select-box'] = "<input type='checkbox' class='report-select' data-id='" . $result['JobAttendee']['id'] . "' />";
               foreach($getFields['all'] as  $name => $field)
               {
                   
                   $fieldArray = explode(".", $name);
                   // if not a multi-array
                   if(isset($result[$fieldArray[0]][$fieldArray[1]]) && 
                           !empty($result[$fieldArray[0]]) && 
                           (!isset($result[$fieldArray[0]][0] )||
                           !is_array($result[$fieldArray[0]][0] ))) {
                        $final[$counter][str_replace(".", "-",$name)] = $result[$fieldArray[0]][$fieldArray[1]];
                   }
                   // check if first element is another array - this means a multi-dimensional array
                   elseif(!isset($result[$fieldArray[0]][$fieldArray[1]]) && 
                           !empty($result[$fieldArray[0]]) && 
                           (isset($result[$fieldArray[0]][0]) &&
                           is_array($result[$fieldArray[0]][0])))
                   {
                       foreach($result[$fieldArray[0]] as $subresult) 
                       {
                           
                           $final[$counter][str_replace(".", "-",$name)] .= $subresult[$fieldArray[1]] . "<br />";
                           
                       }
                   }
                   else
                   {
                       $final[$counter][str_replace(".", "-",$name)] = "";
                       
                   }
               }
               
                $counter++;
            }
            $return = array('data' => array('data' => $final), 'columns' => $returnFields);
          //  pr($return); exit();
            $this->loadModel('JsonReport');
            $this->JsonReport->create();
            $this->JsonReport->save(array('json' => json_encode($return)));
            echo $this->JsonReport->id;
            exit();
           
           }
           
            $this->set('results', $final);
            $this->set('fields', $this->request->data['fields']);
           }
           
           private function _limitAccreditationsByState($results = array(), $states = array())
           {
               foreach($results as $i => $result)
               {
                   $safe = false;
                   // Customer addresses populated, ignore quickbooks
                   if(!empty($result['Customer']['CustomerAddress']))
                   {
                       foreach($result['Customer']['CustomerAddress'] as $addy)
                       {
                           if(in_array($addy['state'], $states))
                           {
                               $safe = true;
                           }
                       }
                   }
                   else
                   {
                       // use QB data
                       if(in_array($result['Customer']['bill_state'], $states))
                               $safe = true;
                   }
                   
                   if(!$safe)
                   {
                       unset($results[$i]);
                   }
               }
               return $results;
           }
           
           
           
           private function _limitCertificationsByState($results = array(), $states = array())
           {
             //  pr($states);
               foreach($results as $i => $result)
               {
                  // pr($result);
                   
                   $safe = false;
                   // Contact addresses populated, ignore quickbooks
                   if(!empty($result['Contact']['ContactAddress']))
                   {
                       foreach($result['Contact']['ContactAddress'] as $addy)
                       {
                          // pr($addy['state']);
                           if(in_array($addy['state'], $states))
                           {
                               $safe = true;
                           }
                       }
                   }
                   
                   if(!$safe)
                   {
                       unset($results[$i]);
                   }
               }
             //  pr($results);
              // exit();
               return $results;
           }
           
           private function _limitPortalByState($results = array(), $states = array())
           {
             //  pr($states);
               foreach($results as $i => $result)
               {
                  // pr($result);
                   
                   $safe = false;
                   // Contact addresses populated, ignore quickbooks
                   if(!empty($result['Contact']['ContactAddress']))
                   {
                       foreach($result['Contact']['ContactAddress'] as $addy)
                       {
                          // pr($addy['state']);
                           if(in_array($addy['state'], $states))
                           {
                               $safe = true;
                           }
                       }
                   }
                   
                   if(!$safe)
                   {
                       unset($results[$i]);
                   }
               }
             //  pr($results);
              // exit();
               return $results;
           }
           
           private function _limitCustomersByState($results = array(), $states = array())
           {
               foreach($results as $i => $result)
               {
                   $safe = false;
                   // Customer addresses populated, ignore quickbooks
                   if(!empty($result['CustomerAddress']))
                   {
                       foreach($result['CustomerAddress'] as $addy)
                       {
                           if(in_array($addy['state'], $states))
                           {
                               $safe = true;
                           }
                       }
                   }
                   else
                   {
                       // use QB data
                       if(in_array($result['Customer']['bill_state'], $states))
                               $safe = true;
                   }
                   
                   if(!$safe)
                   {
                       unset($results[$i]);
                   }
               }
               return $results;
           }
           
           private function _limitContactsByState($results = array(), $states = array())
           {
               foreach($results as $i => $result)
               {
                   $safe = false;
                   // Customer addresses populated
                   if(!empty($result['ContactAddress']))
                   {
                       foreach($result['ContactAddress'] as $addy)
                       {
                           if(in_array($addy['state'], $states))
                           {
                               $safe = true;
                           }
                       }
                   }
                   
                   
                   if(!$safe)
                   {
                       unset($results[$i]);
                   }
               }
               return $results;
           }
           public function admin_runCustomerReport($context,$criteria=null, $fields=null,$export=null)
        {
            $this->layout = 'ajax';
           if($this->request->is('post')) {
            
               // always necessary fields
              $normalFields = array('DISTINCT Customer.id','Customer.source', 'Customer.archived', 'Customer.bill_state', 'Customer.bill_zip');
               
               // Limit location if available
               $locationLimit = $this->request->data['locationLimit'];
               
            $this->loadModel($context);
            $this->$context->unbindModel(array('hasMany' => array('Job', 'CustomerAccreditation'),
                'hasAndBelongsToMany' => array('Contact')));
            
            if(trim(substr($this->request->data['conditions'],-4)) === "OR )")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . ")";
            if(trim(substr($this->request->data['conditions'],-4)) === "OR")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . "";
            if(trim(substr($this->request->data['conditions'],-5)) === "AND )")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-5) . ")";
            if(trim(substr($this->request->data['conditions'],-5)) === "AND")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-5) . "";
            
            // search fields only - avoid compact fields
            $getFields = $this->_getSearchFields($this->request->data['fields']);
            
                $searchable = array_merge($normalFields, $getFields['search']);
           

            $results = $this->$context->find('all', array(
                'fields' => $searchable,
                
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
                        )
                    
                ),
                //'conditions' => 'CustomerAccreditation.expiration_date < "2017-07-04 00:00:00"'));
                'conditions' => $this->request->data['conditions']));
          
            if(isset($locationLimit['byState']))
            {
                $results = $this->_limitCustomersByState($results, $locationLimit['byState']);
            }
            
            // TODO: Limit Org by state
            
            if(!empty($getFields['compact']))
            {
                 foreach($results as $i => $res)
                    {
                foreach($getFields['compact'] as $compactField)
                {
                    if(!isset($results[$i][$compactField['model_name']]))
                        $results[$i][$compactField['model_name']] = array();
                    
                    
                       $complexValue = $this->_fetchComplexFieldInfoCustomer($compactField['id'], $res['Customer']['id']);
                   
                      $results[$i][$compactField['model_name']][$compactField['field_name']] = 
                              $complexValue;
                    }
                }
            }
      
            $final = array();
            $returnFields = array();
            $innerFieldArray = array();
            $returnFields[] = array(
                       'title' => "",
                       'orderable' => false,
                       'targets' => 0,
                       
                       'searchable' => false,
                       'data' => 'select-box');
            foreach($getFields['all'] as $name => $field)
               {
                   
                   $innerFieldArray[str_replace(".", "-",$name)] = null;
                   $returnFields[] = array(
                       'title' => $field,
                       'data' => str_replace(".", "-",$name),
                       'class' =>'show-on-export');
                        
               }
              
               $counter =0;
            foreach($results as $result)
            {
                
               $final[$counter] = $innerFieldArray;
              $final[$counter]['select-box'] = "<input type='checkbox' class='report-select' data-id='" . $result['Customer']['id'] . "' />";
               foreach($getFields['all'] as  $name => $field)
               {
                   
                   $fieldArray = explode(".", $name);
                   // if not a multi-array
                   if(isset($result[$fieldArray[0]][$fieldArray[1]]) && 
                           !empty($result[$fieldArray[0]]) && 
                           (!isset($result[$fieldArray[0]][0] )||
                           !is_array($result[$fieldArray[0]][0] ))) {
                        $final[$counter][str_replace(".", "-",$name)] = $result[$fieldArray[0]][$fieldArray[1]];
                   }
                   // check if first element is another array - this means a multi-dimensional array
                   elseif(!isset($result[$fieldArray[0]][$fieldArray[1]]) && 
                           !empty($result[$fieldArray[0]]) && 
                           (isset($result[$fieldArray[0]][0]) &&
                           is_array($result[$fieldArray[0]][0])))
                   {
                       foreach($result[$fieldArray[0]] as $subresult) 
                       {
                           
                           $final[$counter][str_replace(".", "-",$name)] .= $subresult[$fieldArray[1]] . "<br />";
                           
                       }
                   }
                   else
                   {
                       $final[$counter][str_replace(".", "-",$name)] = "";
                       
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
            
               // always necessary fields
              $normalFields = array('DISTINCT Contact.id','ContactCustomer.archived', 'Contact.source', 'Contact.contact_type');
               
               // Limit location if available
               $locationLimit = $this->request->data['locationLimit'];
               
            $this->loadModel($context);
            $this->$context->unbindModel(array('hasMany' => array('Job', 'CustomerAccreditation'),
                'hasAndBelongsToMany' => array('Contact')));
            
            if(trim(substr($this->request->data['conditions'],-4)) === "OR )")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . ")";
            if(trim(substr($this->request->data['conditions'],-4)) === "OR")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . "";
            if(trim(substr($this->request->data['conditions'],-5)) === "AND )")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-5) . ")";
            if(trim(substr($this->request->data['conditions'],-5)) === "AND")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-5) . "";
            
            // search fields only - avoid compact fields
            $getFields = $this->_getSearchFields($this->request->data['fields']);
            
                $searchable = array_merge($normalFields, $getFields['search']);
           
            if(empty($this->request->data['conditions']))
                $this->request->data['conditions'] = "(ContactCustomer.archived is null)";
            else
                $this->request->data['conditions'] .= " AND (ContactCustomer.archived is null)";

            $results = $this->$context->find('all', array(
                //'recursive' => -1,
                'fields' => $searchable,
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
                        )
                    
                ),
                //'conditions' => 'CustomerAccreditation.expiration_date < "2017-07-04 00:00:00"'));
                'conditions' => $this->request->data['conditions']));
         //   pr($results); exit();
            
            if(isset($locationLimit['byState']))
            {
                $results = $this->_limitContactsByState($results, $locationLimit['byState']);
            }
            
            // TODO: Limit Org by state
            
            if(!empty($getFields['compact']))
            {
                 foreach($results as $i => $res)
                    {
                foreach($getFields['compact'] as $compactField)
                {
                    if(!isset($results[$i][$compactField['model_name']]))
                        $results[$i][$compactField['model_name']] = array();
                    
                    $complexValue = $this->_fetchComplexFieldInfoContact($compactField['id'], $res['Contact']['id']);
                       
                   
                      $results[$i][$compactField['model_name']][$compactField['field_name']] = 
                              $complexValue;
                    }
                }
            }
            
            $final = array();
            $returnFields = array();
            $innerFieldArray = array();
            $returnFields[] = array(
                       'title' => "",
                       'orderable' => false,
                       'targets' => 0,
                       
                       'searchable' => false,
                       'data' => 'select-box');
            foreach($getFields['all'] as $name => $field)
               {
                   
                   $innerFieldArray[str_replace(".", "-",$name)] = null;
                   $returnFields[] = array(
                       'title' => $field,
                       'data' => str_replace(".", "-",$name),
                       'class' =>'show-on-export');
                        
               }
              
               $counter =0;
            foreach($results as $result)
            {
                
               $final[$counter] = $innerFieldArray;
              $final[$counter]['select-box'] = "<input type='checkbox' class='report-select' data-id='" . $result['Contact']['id'] . "' />";
               foreach($getFields['all'] as  $name => $field)
               {
                   
                   $fieldArray = explode(".", $name);
                   // if not a multi-array
                   if(isset($result[$fieldArray[0]][$fieldArray[1]]) && 
                           !empty($result[$fieldArray[0]]) && 
                           (!isset($result[$fieldArray[0]][0] )||
                           !is_array($result[$fieldArray[0]][0] ))) {
                        $final[$counter][str_replace(".", "-",$name)] = $result[$fieldArray[0]][$fieldArray[1]];
                   }
                   // check if first element is another array - this means a multi-dimensional array
                   elseif(!isset($result[$fieldArray[0]][$fieldArray[1]]) && 
                           !empty($result[$fieldArray[0]]) && 
                           (isset($result[$fieldArray[0]][0]) &&
                           is_array($result[$fieldArray[0]][0])))
                   {
                       foreach($result[$fieldArray[0]] as $subresult) 
                       {
                           
                           $final[$counter][str_replace(".", "-",$name)] .= $subresult[$fieldArray[1]] . "<br />";
                           
                       }
                   }
                   else
                   {
                       $final[$counter][str_replace(".", "-",$name)] = "";
                       
                   }
               }
               
                $counter++;
            }
            $return = array('data' => array('data' => $final), 'columns' => $returnFields);
         // pr($return); exit();
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
           
           public function admin_runContactPortalReport($context,$criteria=null, $fields=null,$export=null)
        {
            
            $this->layout = 'ajax';
           if($this->request->is('post')) {
             
              
               // Limit location if available
               $locationLimit = $this->request->data['locationLimit'];
               
            $this->loadModel('Portal');
          //  $this->$context->unbindModel(array('hasMany' => array('Job')));
            
            if(trim(substr($this->request->data['conditions'],-4)) === "OR )")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . ")";
            if(trim(substr($this->request->data['conditions'],-4)) === "OR")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . "";
            if(trim(substr($this->request->data['conditions'],-5)) === "AND )")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-5) . ")";
            if(trim(substr($this->request->data['conditions'],-5)) === "AND")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-5) . "";
                 
              // search fields only - avoid compact fields
            $getFields = $this->_getSearchFields($this->request->data['fields']);
            
             if(!empty($this->request->data['conditions']))
           {
               $this->request->data['conditions'] .= " AND ";
           }
           $this->request->data['conditions'] .= "(Portal.customer_id is null)";
 
 // pr($this->request->data['conditions']); exit();
            $results = $this->Portal->find('all', array(
                'recursive' => 2, 
                'conditions' => $this->request->data['conditions']));
//pr($results); exit();
            if(isset($locationLimit['byState']))
            {
                $results = $this->_limitPortalByState($results, $locationLimit['byState']);
            }
            
           
            
            // TODO: Limit Org by state
            
            if(!empty($getFields['compact']))
            {
                 foreach($results as $i => $res)
                    {
                foreach($getFields['compact'] as $compactField)
                {
                    if(!isset($results[$i][$compactField['model_name']]))
                        $results[$i][$compactField['model_name']] = array();
                    
                    
                       $complexValue = $this->_fetchComplexFieldInfoContact($compactField['id'], $res['Contact']['id']);
                   
                      $results[$i][$compactField['model_name']][$compactField['field_name']] = 
                              $complexValue;
                    }
                }
            }
            
            $final = array();
            $returnFields = array();
            $innerFieldArray = array();
            $returnFields[] = array(
                       'title' => "",
                       'orderable' => false,
                       'targets' => 0,
                       
                       'searchable' => false,
                       'data' => 'select-box');
            foreach($getFields['all'] as $name => $field)
               {
                   
                   $innerFieldArray[str_replace(".", "-",$name)] = null;
                   $returnFields[] = array(
                       'title' => $field,
                       'data' => str_replace(".", "-",$name),
                       'class' =>'show-on-export');
                        
               }
              
               $counter =0;
            foreach($results as $result)
            {
                
               $final[$counter] = $innerFieldArray;
              $final[$counter]['select-box'] = "<input type='checkbox' class='report-select' data-id='" . $result['Portal']['id'] . "' />";
               foreach($getFields['all'] as  $name => $field)
               {
                   
                   $fieldArray = explode(".", $name);
                   // if not a multi-array
                   if(isset($result[$fieldArray[0]][$fieldArray[1]]) && 
                           !empty($result[$fieldArray[0]]) && 
                           (!isset($result[$fieldArray[0]][0] )||
                           !is_array($result[$fieldArray[0]][0] ))) {
                        $final[$counter][str_replace(".", "-",$name)] = $result[$fieldArray[0]][$fieldArray[1]];
                   }
                   // check if first element is another array - this means a multi-dimensional array
                   elseif(!isset($result[$fieldArray[0]][$fieldArray[1]]) && 
                           !empty($result[$fieldArray[0]]) && 
                           (isset($result[$fieldArray[0]][0]) &&
                           is_array($result[$fieldArray[0]][0])))
                   {
                       foreach($result[$fieldArray[0]] as $subresult) 
                       {
                           
                           $final[$counter][str_replace(".", "-",$name)] .= $subresult[$fieldArray[1]] . "<br />";
                           
                       }
                   }
                   else
                   {
                       $final[$counter][str_replace(".", "-",$name)] = "";
                       
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
        
        public function admin_runOrganizationPortalReport($context,$criteria=null, $fields=null,$export=null)
        {
            
            $this->layout = 'ajax';
           if($this->request->is('post')) {
             
              
               // Limit location if available
               $locationLimit = $this->request->data['locationLimit'];
               
            $this->loadModel('Portal');
          //  $this->$context->unbindModel(array('hasMany' => array('Job')));
            
            if(trim(substr($this->request->data['conditions'],-4)) === "OR )")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . ")";
            if(trim(substr($this->request->data['conditions'],-4)) === "OR")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . "";
            if(trim(substr($this->request->data['conditions'],-5)) === "AND )")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-5) . ")";
            if(trim(substr($this->request->data['conditions'],-5)) === "AND")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-5) . "";
                 
              // search fields only - avoid compact fields
            $getFields = $this->_getSearchFields($this->request->data['fields']);
            
             if(!empty($this->request->data['conditions']))
           {
               $this->request->data['conditions'] .= " AND ";
           }
           $this->request->data['conditions'] .= "(Portal.contact_id is null)";
 
 // pr($this->request->data['conditions']); exit();
            $results = $this->Portal->find('all', array(
                'recursive' => 2, 
                'conditions' => $this->request->data['conditions']));
//pr($results); exit();
            if(isset($locationLimit['byState']))
            {
                $results = $this->_limitAccreditationsByState($results, $locationLimit['byState']);
            }
            
           
            
            // TODO: Limit Org by state
            
            if(!empty($getFields['compact']))
            {
                 foreach($results as $i => $res)
                    {
                foreach($getFields['compact'] as $compactField)
                {
                    if(!isset($results[$i][$compactField['model_name']]))
                        $results[$i][$compactField['model_name']] = array();
                    
                    
                       $complexValue = $this->_fetchComplexFieldInfoCustomer($compactField['id'], $res['Customer']['id']);
                   
                      $results[$i][$compactField['model_name']][$compactField['field_name']] = 
                              $complexValue;
                    }
                }
            }
            
            $final = array();
            $returnFields = array();
            $innerFieldArray = array();
            $returnFields[] = array(
                       'title' => "",
                       'orderable' => false,
                       'targets' => 0,
                       
                       'searchable' => false,
                       'data' => 'select-box');
            foreach($getFields['all'] as $name => $field)
               {
                   
                   $innerFieldArray[str_replace(".", "-",$name)] = null;
                   $returnFields[] = array(
                       'title' => $field,
                       'data' => str_replace(".", "-",$name),
                       'class' =>'show-on-export');
                        
               }
              
               $counter =0;
            foreach($results as $result)
            {
                
               $final[$counter] = $innerFieldArray;
              $final[$counter]['select-box'] = "<input type='checkbox' class='report-select' data-id='" . $result['Portal']['id'] . "' />";
               foreach($getFields['all'] as  $name => $field)
               {
                   
                   $fieldArray = explode(".", $name);
                   // if not a multi-array
                   if(isset($result[$fieldArray[0]][$fieldArray[1]]) && 
                           !empty($result[$fieldArray[0]]) && 
                           (!isset($result[$fieldArray[0]][0] )||
                           !is_array($result[$fieldArray[0]][0] ))) {
                        $final[$counter][str_replace(".", "-",$name)] = $result[$fieldArray[0]][$fieldArray[1]];
                   }
                   // check if first element is another array - this means a multi-dimensional array
                   elseif(!isset($result[$fieldArray[0]][$fieldArray[1]]) && 
                           !empty($result[$fieldArray[0]]) && 
                           (isset($result[$fieldArray[0]][0]) &&
                           is_array($result[$fieldArray[0]][0])))
                   {
                       foreach($result[$fieldArray[0]] as $subresult) 
                       {
                           
                           $final[$counter][str_replace(".", "-",$name)] .= $subresult[$fieldArray[1]] . "<br />";
                           
                       }
                   }
                   else
                   {
                       $final[$counter][str_replace(".", "-",$name)] = "";
                       
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
        
        public function admin_runCertificationReport($context,$criteria=null, $fields=null,$export=null)
        {
            
            $this->layout = 'ajax';
           if($this->request->is('post')) {
             
              
               // Limit location if available
               $locationLimit = $this->request->data['locationLimit'];
               
            $this->loadModel($context);
          //  $this->$context->unbindModel(array('hasMany' => array('Job')));
            
            if(trim(substr($this->request->data['conditions'],-4)) === "OR )")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . ")";
            if(trim(substr($this->request->data['conditions'],-4)) === "OR")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . "";
            if(trim(substr($this->request->data['conditions'],-5)) === "AND )")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-5) . ")";
            if(trim(substr($this->request->data['conditions'],-5)) === "AND")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-5) . "";
                 
              // search fields only - avoid compact fields
            $getFields = $this->_getSearchFields($this->request->data['fields']);
            
 // pr($this->request->data['conditions']); exit();
            $results = $this->$context->find('all', array(
                'recursive' => 2, 
                'conditions' => $this->request->data['conditions']));
//pr($results); exit();
            if(isset($locationLimit['byState']))
            {
                $results = $this->_limitCertificationsByState($results, $locationLimit['byState']);
            }
 
            
            // TODO: Limit Org by state
            
            if(!empty($getFields['compact']))
            {
                 foreach($results as $i => $res)
                    {
                foreach($getFields['compact'] as $compactField)
                {
                    if(!isset($results[$i][$compactField['model_name']]))
                        $results[$i][$compactField['model_name']] = array();
                    
                    
                       $complexValue = $this->_fetchComplexFieldInfoContact($compactField['id'], $res['Contact']['id']);
                   
                      $results[$i][$compactField['model_name']][$compactField['field_name']] = 
                              $complexValue;
                    }
                }
            }
            
            $final = array();
            $returnFields = array();
            $innerFieldArray = array();
            $returnFields[] = array(
                       'title' => "",
                       'orderable' => false,
                       'targets' => 0,
                       
                       'searchable' => false,
                       'data' => 'select-box');
            foreach($getFields['all'] as $name => $field)
               {
                   
                   $innerFieldArray[str_replace(".", "-",$name)] = null;
                   $returnFields[] = array(
                       'title' => $field,
                       'data' => str_replace(".", "-",$name),
                       'class' =>'show-on-export');
                        
               }
              
               $counter =0;
            foreach($results as $result)
            {
                
               $final[$counter] = $innerFieldArray;
              $final[$counter]['select-box'] = "<input type='checkbox' class='report-select' data-id='" . $result['ContactCertification']['id'] . "' />";
               foreach($getFields['all'] as  $name => $field)
               {
                   
                   $fieldArray = explode(".", $name);
                   // if not a multi-array
                   if(isset($result[$fieldArray[0]][$fieldArray[1]]) && 
                           !empty($result[$fieldArray[0]]) && 
                           (!isset($result[$fieldArray[0]][0] )||
                           !is_array($result[$fieldArray[0]][0] ))) {
                        $final[$counter][str_replace(".", "-",$name)] = $result[$fieldArray[0]][$fieldArray[1]];
                   }
                   // check if first element is another array - this means a multi-dimensional array
                   elseif(!isset($result[$fieldArray[0]][$fieldArray[1]]) && 
                           !empty($result[$fieldArray[0]]) && 
                           (isset($result[$fieldArray[0]][0]) &&
                           is_array($result[$fieldArray[0]][0])))
                   {
                       foreach($result[$fieldArray[0]] as $subresult) 
                       {
                           
                           $final[$counter][str_replace(".", "-",$name)] .= $subresult[$fieldArray[1]] . "<br />";
                           
                       }
                   }
                   else
                   {
                       $final[$counter][str_replace(".", "-",$name)] = "";
                       
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
             
              
               // Limit location if available
               $locationLimit = $this->request->data['locationLimit'];
               
            $this->loadModel($context);
          //  $this->$context->unbindModel(array('hasMany' => array('Job')));
            
            if(trim(substr($this->request->data['conditions'],-4)) === "OR )")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . ")";
            if(trim(substr($this->request->data['conditions'],-4)) === "OR")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-4) . "";
            if(trim(substr($this->request->data['conditions'],-5)) === "AND )")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-5) . ")";
            if(trim(substr($this->request->data['conditions'],-5)) === "AND")
                    $this->request->data['conditions'] = substr($this->request->data['conditions'],0,-5) . "";
                 
              // search fields only - avoid compact fields
            $getFields = $this->_getSearchFields($this->request->data['fields']);
            
 // pr($this->request->data['conditions']); exit();
            $results = $this->$context->find('all', array(
                'recursive' => 2, 
                'conditions' => $this->request->data['conditions']));

            if(isset($locationLimit['byState']))
            {
                $results = $this->_limitAccreditationsByState($results, $locationLimit['byState']);
            }
 
            
            // TODO: Limit Org by state
            
            if(!empty($getFields['compact']))
            {
                 foreach($results as $i => $res)
                    {
                foreach($getFields['compact'] as $compactField)
                {
                    if(!isset($results[$i][$compactField['model_name']]))
                        $results[$i][$compactField['model_name']] = array();
                    
                    
                       $complexValue = $this->_fetchComplexFieldInfoCustomer($compactField['id'], $res['Customer']['id']);
                   
                      $results[$i][$compactField['model_name']][$compactField['field_name']] = 
                              $complexValue;
                    }
                }
            }
            
            $final = array();
            $returnFields = array();
            $innerFieldArray = array();
            $returnFields[] = array(
                       'title' => "",
                       'orderable' => false,
                       'targets' => 0,
                       
                       'searchable' => false,
                       'data' => 'select-box');
            foreach($getFields['all'] as $name => $field)
               {
                   
                   $innerFieldArray[str_replace(".", "-",$name)] = null;
                   $returnFields[] = array(
                       'title' => $field,
                       'data' => str_replace(".", "-",$name),
                       'class' =>'show-on-export');
                        
               }
              
               $counter =0;
            foreach($results as $result)
            {
                
               $final[$counter] = $innerFieldArray;
              $final[$counter]['select-box'] = "<input type='checkbox' class='report-select' data-id='" . $result['CustomerAccreditation']['id'] . "' />";
               foreach($getFields['all'] as  $name => $field)
               {
                   
                   $fieldArray = explode(".", $name);
                   // if not a multi-array
                   if(isset($result[$fieldArray[0]][$fieldArray[1]]) && 
                           !empty($result[$fieldArray[0]]) && 
                           (!isset($result[$fieldArray[0]][0] )||
                           !is_array($result[$fieldArray[0]][0] ))) {
                        $final[$counter][str_replace(".", "-",$name)] = $result[$fieldArray[0]][$fieldArray[1]];
                   }
                   // check if first element is another array - this means a multi-dimensional array
                   elseif(!isset($result[$fieldArray[0]][$fieldArray[1]]) && 
                           !empty($result[$fieldArray[0]]) && 
                           (isset($result[$fieldArray[0]][0]) &&
                           is_array($result[$fieldArray[0]][0])))
                   {
                       foreach($result[$fieldArray[0]] as $subresult) 
                       {
                           
                           $final[$counter][str_replace(".", "-",$name)] .= $subresult[$fieldArray[1]] . "<br />";
                           
                       }
                   }
                   else
                   {
                       $final[$counter][str_replace(".", "-",$name)] = "";
                       
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
        public function admin_organizationTraining() {
                 $this->loadModel('Group');
            $this->set('groups', $this->Group->find('list', array('conditions'=> array('is_customer'=> 1))));
            $this->loadModel('CustomerType');
            $this->set('customerTypes', $this->CustomerType->find('list'));
            $this->set('customerSources', explode("|", $this->config['customer.sources']));
            $this->loadModel('EmailTemplate');
            $this->set('templateOptions', $this->EmailTemplate->find('list', array('conditions' => array(
                'context' => 'OrganizationTraining'
            ))));
            $this->loadModel('ServiceArea');
            $this->set('trainingTypes', $this->ServiceArea->find('list', array('conditions' => array(
                'parent_id' => 2
            ))));
            $this->set('fields', $this->_reportingFields('OrganizationTraining'));
            $this->set('defaultExportTitle', 'OrganizationTrainingQuery-' . date("mdY"));
	}
        
         public function admin_contactTraining() {
                
            $this->loadModel('EmailTemplate');
            $this->set('templateOptions', $this->EmailTemplate->find('list', array('conditions' => array(
                'context' => 'ContactTraining'
            ))));
            $this->loadModel('ServiceArea');
            $this->set('trainingTypes', $this->ServiceArea->find('list', array('conditions' => array(
                'parent_id' => 2
            ))));
            $this->set('fields', $this->_reportingFields('ContactTraining'));
            $this->set('defaultExportTitle', 'ContactTrainingQuery-' . date("mdY"));
	}
        
        public function admin_contactPortal() {
                
            $this->loadModel('EmailTemplate');
            $this->set('templateOptions', $this->EmailTemplate->find('list', array('conditions' => array(
                'context' => 'ContactPortal'
            ))));
           
            $accessTypes = array();
            foreach(explode("|", $this->config['portal.access_types']) as $type) {
                $accessTypes[$type] = $type;
            }
           
            $this->set('accessTypes', $accessTypes);
            $this->set('fields', $this->_reportingFields('ContactPortal'));
            $this->set('defaultExportTitle', 'ContactPortalQuery-' . date("mdY"));
	}
        
        public function admin_organizationPortal() {
                
            $this->loadModel('EmailTemplate');
            $this->set('templateOptions', $this->EmailTemplate->find('list', array('conditions' => array(
                'context' => 'OrganizationPortal'
            ))));
            $accessTypes = array();
            foreach(explode("|", $this->config['portal.access_types']) as $type) {
                $accessTypes[$type] = $type;
            }
            $this->set('accessTypes', $accessTypes);
            $this->set('fields', $this->_reportingFields('OrganizationPortal'));
            $this->set('defaultExportTitle', 'OrganizationPortalQuery-' . date("mdY"));
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
    
