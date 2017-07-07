<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');
   
    
    class AccreditationsController extends AppController {

        public function beforeRender() {
            parent::beforeRender();
            $this->set('section', 'crm');
        }
    	
       public function admin_index()
       {
           $this->set('certs', $this->Accreditation->find('all'));
          
       }
       
       public function admin_add()
       {
           if(!empty($this->request->data))
           {
               $this->Accreditation->create();
               if($this->Accreditation->save($this->request->data))
               {
                   $this->Session->setFlash("New accreditation successfully created.", 'flash_success');
                   
               }
               else {
                   $this->Session->setFlash("An error occurred. Please try again.", 'flash_error');
               }
               $this->redirect('/admin/accreditations');
               
           }
       }
       public function admin_edit($id)
       {
           
           if(!empty($this->request->data))
           {
               $this->Accreditation->id = $id;
               if($this->Accreditation->save($this->request->data))
               {
                   $this->Session->setFlash("Accreditation edited successfully.", 'flash_success');
                   $this->redirect('/admin/accreditations');
               }
               else {
                   $this->Session->setFlash("An error occurred. Please try again.", 'flash_error');
               }
               
               $this->redirect('/admin/accreditations');
           }
           $this->data = $this->Accreditation->findById($id);
       }
       
       public function admin_delete($id)
       {
           
         if($this->Accreditation->delete($id))
             $this->Session->setFlash('Accreditation removed successfully.', 'flash_success');
         else {
             $this->Session->setFlash('An error occurred. Please try again.', 'flash_error');
         }
         
         $this->redirect('/admin/accreditations');
       }
        
        
        }