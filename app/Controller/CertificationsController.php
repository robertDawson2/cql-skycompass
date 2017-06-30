<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');
   
    
    class CertificationsController extends AppController {

        public function beforeRender() {
            parent::beforeRender();
            $this->set('section', 'crm');
        }
    	
       public function admin_index()
       {
           $this->set('certs', $this->Certification->find('all'));
          
       }
       
       public function admin_add()
       {
           if(!empty($this->request->data))
           {
               $this->Certification->create();
               if($this->Certification->save($this->request->data))
               {
                   $this->Session->setFlash("New certification successfully created.", 'flash_success');
                   
               }
               else {
                   $this->Session->setFlash("An error occurred. Please try again.", 'flash_error');
               }
               $this->redirect('/admin/certifications');
               
           }
       }
       public function admin_edit($id)
       {
           
           if(!empty($this->request->data))
           {
               $this->Certification->id = $id;
               if($this->Certification->save($this->request->data))
               {
                   $this->Session->setFlash("Certification edited successfully.", 'flash_success');
                   $this->redirect('/admin/certifications');
               }
               else {
                   $this->Session->setFlash("An error occurred. Please try again.", 'flash_error');
               }
               
               $this->redirect('/admin/certifications');
           }
           $this->data = $this->Certification->findById($id);
       }
       
       public function admin_delete($id)
       {
           
         if($this->Certification->delete($id))
             $this->Session->setFlash('Certification removed successfully.', 'flash_success');
         else {
             $this->Session->setFlash('An error occurred. Please try again.', 'flash_error');
         }
         
         $this->redirect('/admin/certifications');
       }
        
        
        }