<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    App::uses('AppController', 'Controller');
   
    
    class CustomersController extends AppController {

    	public $name = 'Customer';
        public $uses = array('Customer', 'CustomerAddress', 'Company','CustomerType', 'Accreditation');
        
        public function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow('import');
            
        }
        
        public function ajaxReturnAddress($id) {
            $this->layout = 'ajax';
            $cust = $this->Customer->findById($id);
            if(!empty($cust['Customer']['bill_addr2']))
                echo json_encode($cust['Customer']);
            else
                echo "false";
            exit();
        }
        
        public function beforeRender() {
            parent::beforeRender();
            $this->set('section', 'customers');
        }
        
        public function admin_index() {
            $this->Customer->unbindModel(array('hasMany' => array('CustomerAccreditation', 'CustomerFile')));
            
            $this->set('customers', $this->Customer->find('all', array(
                'conditions' => array('Customer.archived is null'),
                'contain' => array('Contact' => array('limit' => 5)))));

        }
        public function admin_view($id = null) {
            $customer = $this->Customer->find('first', array('conditions'=>array('Customer.id' => $id),
                'recursive' => 2));
            foreach($customer['Contact'] as $i => $contact)
            {

                if($contact['ContactCustomer']['archived'] !== null)
                    unset($customer['Contact'][$i]);
            }
            $this->Job->unbindModel(array('belongsTo' => array('Customer')));
            $jobs = $this->Job->find('all', array('conditions'=> array('Job.customer_id' => $id), 
                'recursive' => 3, 'order' => 'Job.start_date DESC', 'limit' => 10));
            $this->set('jobs', $jobs);
            $this->set('customer', $customer);
            $this->set('types', $this->Accreditation->find('list', array('fields' => array('Accreditation.id', 'Accreditation.name'))));
            $custTypes =  $this->CustomerType->find('list', array('fields' => array('CustomerType.id', 'CustomerType.name')));
            $this->set('custTypes', $custTypes);
        }
        function admin_delete($id = null)
        { 
            // actually an archive function to keep QB compatibility
            $this->Customer->id = $id;
            $this->Customer->saveField('archived', date("Y-m-d H:i:s"));
            
            // remove jobs to keep DB clean.
            $this->loadModel('Job');
            $this->Job->deleteAll(array('Job.customer_id' => $id));
            $this->Session->setFlash('Customer has been archived for deletion.', 'flash_success');
            
            $this->redirect('/admin/customers');
        }
       function admin_edit($id = null)
        {
            if(!empty($this->request->data))
            {
               
                // remove all deleted attributes
            $newData = json_decode($this->request->data['Doc']['removed'], true);
            if(!empty($newData))
                foreach($newData as $rid) {
                if(!$this->_removeAttribute('CustomerFile', $rid))
                {
                    $error['remove_doc'] = true;
                }
            }
            
            $newData = json_decode($this->request->data['Cert']['removed'], true);
            if(!empty($newData))
                foreach($newData as $rid) {
                if(!$this->_removeAttribute('CustomerAccreditation', $rid))
                {
                    $error['remove_accred'] = true;
                }
            }
            
            $newData = json_decode($this->request->data['Address']['removed'], true);
            if(!empty($newData))
                foreach($newData as $rid) {
                if(!$this->_removeAttribute('CustomerAddress', $rid))
                {
                    $error['remove_address'] = true;
                }
            }
            
            $newData = json_decode($this->request->data['Group']['removed'], true);
            if(!empty($newData))
                foreach($newData as $rid) {
                if(!$this->_removeAttribute('CustomerGroup', $rid))
                {
                    $error['remove_group'] = true;
                }
            }
            
            $newData = json_decode($this->request->data['Phone']['removed'], true);
            if(!empty($newData))
                foreach($newData as $rid) {
                if(!$this->_removeAttribute('CustomerPhone', $rid))
                {
                    $error['remove_phone'] = true;
                }
            }
            
            $newData = json_decode($this->request->data['Contact']['removed'], true);
            if(!empty($newData))
            {
                $this->loadModel('ContactCustomer');
                foreach($newData as $rid) {
                    
                    $this->ContactCustomer->updateAll(
                            array('archived' => '"' . date('Y-m-d H:i:s') . '"'), 
                        array('customer_id' => $id, 'contact_id' => $rid));

            }
            }
            
            // continue just like add
            $data = $this->request->data;
                $error = array('phone' => false, 'contact' => false, 'group' => false, 'address' => false, 'accred' => false, 'doc' => false);
                
                $id = $this->_saveCustomer($data['Customer']);

                $newData = json_decode($this->request->data['Phone']['list'], true);
                if(!empty($newData))
            foreach($newData as $phone) {
                if(!$this->_savePhoneNumber($phone, $id))
                {
                    $error['phone'] = true;
                }
            }
                
            
                 $tempCust = $this->Customer->read();
                 $currentPrimary = $tempCust['Customer']['primary_contact_id'];
                 $primaryFound = 0;
                 $primaryReplaced = 0;
                $newData = json_decode($this->request->data['Contact']['list'], true);
                if(!empty($newData)) {
                    
                    
                foreach($newData as $customer) {
                if(!$this->_saveContactLink($customer, $id))
                {
                    $error['contact'] = true;
                }
                // if primary contact, save contact id in customer field
                elseif(isset($customer['primary']))
                {
                    if($currentPrimary === $customer['id']) {
                    $primaryFound = 1;}
                    else
                    {$primaryReplaced = 1;
                    $currentPrimary = $customer['id']; }
                    
                    
                }
            }
                }
                
                if($primaryReplaced)
                {
                    
                    $this->Customer->saveField('primary_contact_id', $currentPrimary);
                }
                elseif(!$primaryReplaced && !$primaryFound)
                {
                    $this->Customer->saveField('primary_contact_id', null);
                }
                
            
                $newData = json_decode($this->request->data['Group']['list'], true);
                if(!empty($newData))
                foreach($newData as $row) {
                if(!$this->_saveGroupLink($row, $id))
                {
                    $error['group'] = true;
                }
            }
                $newData = json_decode($this->request->data['Address']['list'], true);
                if(!empty($newData))
                foreach($newData as $row) {
                if(!$this->_saveAddress($row, $id))
                {
                    $error['address'] = true;
                }
            }
            
                $newData = json_decode($this->request->data['Cert']['list'], true);
                if(!empty($newData))
                foreach($newData as $row) {
                if(!$this->_saveAccreditation($row, $id))
                {
                    $error['accred'] = true;
                }
            }
            $newData = json_decode($this->request->data['Doc']['list'], true);
            if(!empty($newData))
                foreach($newData as $row) {
                if(!$this->_saveLinkedDoc($row, $id))
                {
                    $error['doc'] = true;
                }
            }
            
            
            
            if(!in_array(true,$error))
            {
                $this->Session->setFlash('Customer Data Modified Successfully!', 'flash_success');
            }
            else
            {
                $errorMessage = "";
                foreach($error as $type => $e)
                {
                    if($e)
                        $errorMessage .= $type . ", ";
                }
                $this->Session->setFlash('Data saved successfully with the following errors: ' . substr($errorMessage,0,-2), 'flash_error');
            }
            $this->redirect('/admin/customers/view/' . $id);
            
            }
            
            $this->data = $this->Customer->findById($id);
          
            $this->set('current', $this->data);
           
            $custTypes =  $this->CustomerType->find('list', array('fields' => array('CustomerType.id', 'CustomerType.name')));
            
            $this->loadModel('Contact');
            $this->loadModel('Group');
            $this->set('contacts', $this->Contact->find('all', array('fields'=> array('Contact.id', 'Contact.first_name', 'Contact.last_name'))));
           
            $this->set('accredTypes', $this->Accreditation->find('list', array('fields' => array('Accreditation.id', 'Accreditation.name'))));
            $this->set('groupTypes', $this->Group->find('list', array('fields' => array('Group.id', 'Group.name'), 'conditions' => array('Group.is_customer' => 1))));
            $this->set('sources' , explode("|",$this->config['customer.sources']));
            $this->set('custTypes', $custTypes);
            $this->set('accredTerms', explode("|", $this->config['accreditation.terms']));
        }
        
        private function _removeAttribute($context, $id)
        {
            $this->loadModel($context);
            return $this->$context->delete($id);
            
        }
        private function _saveCustomer($customer)
        {
            if(!empty($customer['types']))
            {
            $customer['organization_type'] = implode("|", $customer['types']);
            }
 else {
     $customer['organization_type'] = "";
 }
            if($customer['organization_type'] === null)
                $customer['organization_type'] = "";
            unset($customer['types']);
            $customer['full_name'] = $customer['name'];
            $customer['company_name'] =  $customer['name'];
            $customer['ship_note'] = $customer['bill_note'] = $customer['first_name'] = $customer['middle_name'] = $customer['last_name'] = "";
            if(!isset($customer['id']))
            {
                $customer['id'] = sha1("NAME" . time() . rand(0,1000));
                // $this->Customer->create();
                
            }
            
                $this->Customer->save($customer);
                 return $this->Customer->id;
        }
        
        private function _saveContactLink($contact, $id)
        {
            $newData = array('contact_id' => $contact['id'], 'customer_id'=>$id);
            
            $this->loadModel('ContactCustomer'); 
            $exists = $this->ContactCustomer->find('first', array('conditions' => array(
                'contact_id' => $contact['id'],
                'customer_id' => $id,
                'archived is null'
                )));
            if(empty($exists)) {
            $this->ContactCustomer->create();
            return $this->ContactCustomer->save($newData);
            }
            return true;
           
        }
        private function _saveGroupLink($group, $id)
        {
            $newData = array('group_id' => $group['group_type_id'], 'customer_id'=>$id);
            $this->loadModel('CustomerGroup');
            $this->CustomerGroup->create();
            return $this->CustomerGroup->save($newData);
        }
        private function _saveAddress($address, $id)
        {
            $this->loadModel('CustomerAddress');
            $address['address_1'] = $address['addr1'];
            $address['address_2'] = $address['addr2'];
           unset($address['addr1']);
           unset($address['addr2']);
           $address['customer_id'] = $id;
           
            $this->CustomerAddress->create();
            return $this->CustomerAddress->save($address);
        }
        private function _savePhoneNumber($phone, $id)
        {
            $this->loadModel('CustomerPhone');
            $phone['customer_id'] = $id;
            
            $this->CustomerPhone->create();
            return $this->CustomerPhone->save($phone);
        }
        private function _saveAccreditation($cert, $id)
        {
            $this->loadModel('CustomerAccreditation');
           
            $cert['customer_id'] = $id;
            if(empty($cert['expiration_date']))
                unset($cert['expiration_date']);
            else
                $cert['expiration_date'] = date('Y-m-d H:i:s', strtotime($cert['expiration_date']));
            
           if(empty($cert['extension_date']))
                unset($cert['extension_date']);
            else
                $cert['extension_date'] = date('Y-m-d H:i:s', strtotime($cert['extension_date']));
            
            if(empty($cert['visit_1_start_date']))
                unset($cert['visit_1_start_date']);
            else
                $cert['visit_1_start_date'] = date('Y-m-d H:i:s', strtotime($cert['visit_1_start_date']));
            
           if(empty($cert['visit_1_end_date']))
                unset($cert['visit_1_end_date']);
            else
                $cert['visit_1_end_date'] = date('Y-m-d H:i:s', strtotime($cert['visit_1_end_date']));
            
            if(empty($cert['visit_2_18_mo']))
                unset($cert['visit_2_18_mo']);
            else
                $cert['visit_2_18_mo'] = date('Y-m-d H:i:s', strtotime($cert['visit_2_18_mo']));
            
            if(empty($cert['visit_2_start_date']))
                unset($cert['visit_2_start_date']);
            else
                $cert['visit_2_start_date'] = date('Y-m-d H:i:s', strtotime($cert['visit_2_start_date']));
            
            if(empty($cert['visit_2_end_date']))
                unset($cert['visit_2_end_date']);
            else
                $cert['visit_2_end_date'] = date('Y-m-d H:i:s', strtotime($cert['visit_2_end_date']));
            
            if(empty($cert['visit_3_36_mo']))
                unset($cert['visit_3_36_mo']);
            else
                $cert['visit_3_36_mo'] = date('Y-m-d H:i:s', strtotime($cert['visit_3_36_mo']));
            
            if(empty($cert['visit_3_start_date']))
                unset($cert['visit_3_start_date']);
            else
                $cert['visit_3_start_date'] = date('Y-m-d H:i:s', strtotime($cert['visit_3_start_date']));
            
            if(empty($cert['visit_3_end_date']))
                unset($cert['visit_3_end_date']);
            else
                $cert['visit_3_end_date'] = date('Y-m-d H:i:s', strtotime($cert['visit_3_end_date']));
            
            if(empty($cert['pce_start_date']))
                unset($cert['pce_start_date']);
            else
                $cert['pce_start_date'] = date('Y-m-d H:i:s', strtotime($cert['pce_start_date']));
            
            if(empty($cert['pce_end_date']))
                unset($cert['pce_end_date']);
            else
                $cert['pce_end_date'] = date('Y-m-d H:i:s', strtotime($cert['pce_end_date']));
            
            if(empty($cert['9_mo_due_date']))
                unset($cert['9_mo_due_date']);
            else
                $cert['9_mo_due_date'] = date('Y-m-d H:i:s', strtotime($cert['9_mo_due_date']));
            
            if(empty($cert['9_mo_actual_date']))
                unset($cert['9_mo_actual_date']);
            else
                $cert['9_mo_actual_date'] = date('Y-m-d H:i:s', strtotime($cert['9_mo_actual_date']));
            
            if(empty($cert['18_mo_due_date']))
                unset($cert['18_mo_due_date']);
            else
                $cert['18_mo_due_date'] = date('Y-m-d H:i:s', strtotime($cert['18_mo_due_date']));
            
            if(empty($cert['18_mo_actual_date']))
                unset($cert['18_mo_actual_date']);
            else
                $cert['18_mo_actual_date'] = date('Y-m-d H:i:s', strtotime($cert['18_mo_actual_date']));
            
            if(!isset($cert['notes']))
                $cert['notes'] = "";
            $this->CustomerAccreditation->create();
            return $this->CustomerAccreditation->save($cert);
            
        }
        private function _saveLinkedDoc($doc, $id)
        {
            $this->loadModel('CustomerFile');
            $doc['customer_id'] = $id;
            
            $this->CustomerFile->create();
            return $this->CustomerFile->save($doc);
            
                
    
        }
        
        function admin_add() {
            $custTypes =  $this->CustomerType->find('list', array('fields' => array('CustomerType.id', 'CustomerType.name')));
            if(!empty($this->request->data))
            {
                $data = $this->request->data;
              
                $error = array('phone' => false, 'contact' => false, 'group' => false, 'address' => false, 'cert' => false, 'doc' => false);
                
                $id = $this->_saveCustomer($data['Customer']);

                $newData = json_decode($this->request->data['Phone']['list'], true);
                if(!empty($newData))
            foreach($newData as $phone) {
                if(!$this->_savePhoneNumber($phone, $id))
                {
                    $error['phone'] = true;
                }
            }
                
                $newData = json_decode($this->request->data['Contact']['list'], true);
                if(!empty($newData))
                foreach($newData as $contact) {
                if(!$this->_saveContactLink($contact, $id))
                {
                    $error['contact'] = true;
                }
                // if primary contact, save contact id in customer field
                elseif(isset($contact['primary']))
                {
                    $this->Customer->id = $id;
                    $this->Customer->saveField('primary_contact_id', $contact['id']);
                }
            }
            
                $newData = json_decode($this->request->data['Group']['list'], true);
                if(!empty($newData))
                foreach($newData as $row) {
                if(!$this->_saveGroupLink($row, $id))
                {
                    $error['group'] = true;
                }
            }
                $newData = json_decode($this->request->data['Address']['list'], true);
                if(!empty($newData))
                foreach($newData as $row) {
                if(!$this->_saveAddress($row, $id))
                {
                    $error['address'] = true;
                }
            }
            
                $newData = json_decode($this->request->data['Cert']['list'], true);
                if(!empty($newData))
                foreach($newData as $row) {
                if(!$this->_saveAccreditation($row, $id))
                {
                    $error['cert'] = true;
                }
            }
            $newData = json_decode($this->request->data['Doc']['list'], true);
            if(!empty($newData))
                foreach($newData as $row) {
                if(!$this->_saveLinkedDoc($row, $id))
                {
                    $error['doc'] = true;
                }
            }
            
            
            
            if(!in_array(true,$error))
            {
                $this->Session->setFlash('New Customer Data Saved Successfully!', 'flash_success');
            }
            else
            {
                $errorMessage = "";
                foreach($error as $type => $e)
                {
                    if($e)
                        $errorMessage .= $type . ", ";
                }
                $this->Session->setFlash('Data saved successfully with the following errors: ' . substr($errorMessage,0,-2), 'flash_error');
            }
            $this->redirect('/admin/customers');
            }
            $this->loadModel('Contact');
            $this->loadModel('Group');
            $this->set('contacts', $this->Contact->find('all', array('fields'=> array('Contact.id', 'Contact.first_name', 'Contact.last_name'))));
           
            $this->set('accredTypes', $this->Accreditation->find('list', array('fields' => array('Accreditation.id', 'Accreditation.name'))));
            $this->set('groupTypes', $this->Group->find('list', array('fields' => array('Group.id', 'Group.name'), 'conditions' => array('Group.is_customer' => 1))));
            $this->set('sources' , explode("|",$this->config['customer.sources']));
            $this->set('custTypes', $custTypes);
            $this->set('accredTerms', explode("|", $this->config['accreditation.terms']));
        }
        public function import($hash = null)
        {
           if($hash != "asdn")
           {
               exit('incorrect hash has been supplied');
           }
           
           $this->redirect('/importQBdata.php');
           
           // Correct hash supplied - begin import of customers
           
           
           

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
