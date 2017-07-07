<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');
   
    
    class ContactTypesController extends AppController {

        public function beforeRender() {
            parent::beforeRender();
            $this->set('section', 'crm');
        }
    	
       public function admin_index()
       {
           $this->set('certs', $this->ContactType->find('all'));
          
       }
       
       public function admin_add()
       {
           if(!empty($this->request->data))
           {
               $this->ContactType->create();
               if($this->ContactType->save($this->request->data))
               {
                   $this->Session->setFlash("New contactType successfully created.", 'flash_success');
                   
               }
               else {
                   $this->Session->setFlash("An error occurred. Please try again.", 'flash_error');
               }
               $this->redirect('/admin/contactTypes');
               
           }
       }
       public function admin_edit($id)
       {
           
           if(!empty($this->request->data))
           {
               $this->ContactType->id = $id;
               if($this->ContactType->save($this->request->data))
               {
                   $this->Session->setFlash("ContactType edited successfully.", 'flash_success');
                   $this->redirect('/admin/contactTypes');
               }
               else {
                   $this->Session->setFlash("An error occurred. Please try again.", 'flash_error');
               }
               
               $this->redirect('/admin/contactTypes');
           }
           $this->data = $this->ContactType->findById($id);
       }
       
       public function admin_delete($id)
       {
           
         if($this->ContactType->delete($id))
             $this->Session->setFlash('ContactType removed successfully.', 'flash_success');
         else {
             $this->Session->setFlash('An error occurred. Please try again.', 'flash_error');
         }
         
         $this->redirect('/admin/contactTypes');
       }
        
        
        }