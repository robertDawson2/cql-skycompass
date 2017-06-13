<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    App::uses('AppController', 'Controller');
   
    
    class ContactsController extends AppController {

    	public $name = 'Contact';
        public $uses = array('Certification', 'Customer','Group','Contact');
        function beforeFilter() {
		parent::beforeFilter();
		$this->set('section', 'contact');
                $this->Auth->allow('passwordReset', 'firstLogin', 'forgotPassword');
	}
        
        public function admin_index() {
            
            $this->set('contacts', $this->Contact->find('all'));

        }
        public function admin_delete($id)
        {
            if($this->Contact->delete($id,true))
                $this->Session->setFlash('Contact successfully removed.', 'flash_success');
            else
                $this->Session->setFlash('Deletion error occurred. Please try again.', 'flash_error');
            
            $this->redirect('/admin/contacts');
        }
        
        function admin_view($id)
        {
            $this->set('contact', $this->Contact->find('first', array('recursive' => 2, 'conditions' => array('Contact.id' => $id))));
        }
        
        function admin_edit($id = null)
        {
            if(!empty($this->request->data))
            {
               
                // remove all deleted attributes
            $newData = json_decode($this->request->data['Doc']['removed'], true);
            if(!empty($newData))
                foreach($newData as $rid) {
                if(!$this->_removeAttribute('ContactFile', $rid))
                {
                    $error['remove_doc'] = true;
                }
            }
            
            $newData = json_decode($this->request->data['Cert']['removed'], true);
            if(!empty($newData))
                foreach($newData as $rid) {
                if(!$this->_removeAttribute('ContactCertification', $rid))
                {
                    $error['remove_cert'] = true;
                }
            }
            
            $newData = json_decode($this->request->data['Address']['removed'], true);
            if(!empty($newData))
                foreach($newData as $rid) {
                if(!$this->_removeAttribute('ContactAddress', $rid))
                {
                    $error['remove_address'] = true;
                }
            }
            
            $newData = json_decode($this->request->data['Group']['removed'], true);
            if(!empty($newData))
                foreach($newData as $rid) {
                if(!$this->_removeAttribute('ContactGroup', $rid))
                {
                    $error['remove_group'] = true;
                }
            }
            
            $newData = json_decode($this->request->data['Phone']['removed'], true);
            if(!empty($newData))
                foreach($newData as $rid) {
                if(!$this->_removeAttribute('ContactPhone', $rid))
                {
                    $error['remove_phone'] = true;
                }
            }
            
            $newData = json_decode($this->request->data['Customer']['removed'], true);
            if(!empty($newData))
            {
                $this->loadModel('ContactCustomer');
                foreach($newData as $rid) {
                    
                    $this->ContactCustomer->updateAll(
                            array('archived' => '"' . date('Y-m-d H:i:s') . '"'), 
                        array('customer_id' => $rid, 'contact_id' => $id));

            }
            }
            
            // continue just like add
            $data = $this->request->data;
                $error = array('phone' => false, 'customer' => false, 'group' => false, 'address' => false, 'cert' => false, 'doc' => false);
                
                $id = $this->_saveContact($data['Contact']);

                $newData = json_decode($this->request->data['Phone']['list'], true);
                if(!empty($newData))
            foreach($newData as $phone) {
                if(!$this->_savePhoneNumber($phone, $id))
                {
                    $error['phone'] = true;
                }
            }
                
                $newData = json_decode($this->request->data['Customer']['list'], true);
                if(!empty($newData))
                foreach($newData as $customer) {
                if(!$this->_saveCustomerLink($customer, $id))
                {
                    $error['customer'] = true;
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
                if(!$this->_saveCertification($row, $id))
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
                $this->Session->setFlash('Contact Data Modified Successfully!', 'flash_success');
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
            $this->redirect('/admin/contacts');
            
            }
            
            $this->data = $this->Contact->findById($id);
            $this->set('current', $this->data);
           
            
            $this->loadModel('Customer');
            $this->set('customers', $this->Customer->find('list', array('fields'=> array('Customer.id', 'Customer.name'))));
           
            $this->set('certTypes', $this->Certification->find('list', array('fields' => array('Certification.id', 'Certification.name'))));
            $this->set('groupTypes', $this->Group->find('list', array('fields' => array('Group.id', 'Group.name'), 'conditions' => array('Group.is_contact' => 1))));
            $temp =  explode("|", $this->config['certification.levels']);
            $certLevels = array();
            foreach($temp as $t)
                $certLevels[$t] = $t;
            $this->set('certLevels', $certLevels);
            $this->set('phoneTypes', array(
                    'home' => 'Home',
                    'work' => 'Work',
                    'mobile' => 'Mobile',
                    'fax' => 'Fax',
                    'other' => 'Other'
                ));
            $this->set('addressTypes', array(
                    'home' => 'Home',
                    'work' => 'Work',
                    'mailing' => 'Mailing',
                    'other' => 'Other'
                ));
        }
        
        function admin_add() {
            if(!empty($this->request->data))
            {
                $data = $this->request->data;
                $error = array('phone' => false, 'customer' => false, 'group' => false, 'address' => false, 'cert' => false, 'doc' => false);
                
                $id = $this->_saveContact($data['Contact']);

                $newData = json_decode($this->request->data['Phone']['list'], true);
                if(!empty($newData))
            foreach($newData as $phone) {
                if(!$this->_savePhoneNumber($phone, $id))
                {
                    $error['phone'] = true;
                }
            }
                
                $newData = json_decode($this->request->data['Customer']['list'], true);
                if(!empty($newData))
                foreach($newData as $customer) {
                if(!$this->_saveCustomerLink($customer, $id))
                {
                    $error['customer'] = true;
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
                if(!$this->_saveCertification($row, $id))
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
                $this->Session->setFlash('New Contact Data Saved Successfully!', 'flash_success');
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
            $this->redirect('/admin/contacts');
            }
            $this->loadModel('Customer');
            $this->set('customers', $this->Customer->find('list', array('fields'=> array('Customer.id', 'Customer.name'))));
           
            $this->set('certTypes', $this->Certification->find('list', array('fields' => array('Certification.id', 'Certification.name'))));
            $this->set('groupTypes', $this->Group->find('list', array('fields' => array('Group.id', 'Group.name'), 'conditions' => array('Group.is_contact' => 1))));
            $this->set('certLevels', explode("|", $this->config['certification.levels']));
        }
        private function _removeAttribute($context, $id)
        {
            $this->loadModel($context);
            return $this->$context->delete($id);
            
        }
        private function _saveContact($contact)
        {
            $contact['birthday'] = date('Y-m-d H:i:s', strtotime($contact['birthday']));
            if(!isset($contact['id']))
                $this->Contact->create();
            
                $this->Contact->save($contact);
                 return $this->Contact->id;
        }
        
        private function _saveCustomerLink($customer, $id)
        {
            $newData = array('customer_id' => $customer['id'], 'contact_id'=>$id);
            
            $this->loadModel('ContactCustomer');
            $this->ContactCustomer->create();
            return $this->ContactCustomer->save($newData);
        }
        private function _saveGroupLink($group, $id)
        {
            $newData = array('group_id' => $group['group_type_id'], 'contact_id'=>$id);
            $this->loadModel('ContactGroup');
            $this->ContactGroup->create();
            return $this->ContactGroup->save($newData);
        }
        private function _saveAddress($address, $id)
        {
            $this->loadModel('ContactAddress');
            $address['address_1'] = $address['addr1'];
            $address['address_2'] = $address['addr2'];
           unset($address['addr1']);
           unset($address['addr2']);
           $address['contact_id'] = $id;
           
            $this->ContactAddress->create();
            return $this->ContactAddress->save($address);
        }
        private function _savePhoneNumber($phone, $id)
        {
            $this->loadModel('ContactPhone');
            $phone['contact_id'] = $id;
            
            $this->ContactPhone->create();
            return $this->ContactPhone->save($phone);
        }
        private function _saveCertification($cert, $id)
        {
            $this->loadModel('ContactCertification');
            $cert['certification_id'] = $cert['certification_type_id'];
            unset($cert['certification_type_id']);
            $cert['contact_id'] = $id;
            if(empty($cert['renewal_date']))
                unset($cert['renewal_date']);
            else
                $cert['renewal_date'] = date('Y-m-d H:i:s', strtotime($cert['renewal_date']));
            
            if(empty($cert['recertification_date']))
                unset($cert['recertification_date']);
            else
                $cert['recertification_date'] = date('Y-m-d H:i:s', strtotime($cert['recertification_date']));
            if(empty($cert['start_date']))
                unset($cert['start_date']);
            else
                $cert['start_date'] = date('Y-m-d H:i:s', strtotime($cert['start_date']));
            
            $this->ContactCertification->create();
            return $this->ContactCertification->save($cert);
            
        }
        private function _saveLinkedDoc($doc, $id)
        {
            $this->loadModel('ContactFile');
            $doc['contact_id'] = $id;
            
            $this->ContactFile->create();
            return $this->ContactFile->save($doc);
            
                
    
        }
    }
    
    
?>
