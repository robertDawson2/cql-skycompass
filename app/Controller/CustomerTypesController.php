<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');
   
    
    class CustomerTypesController extends AppController {

        public function beforeRender() {
            parent::beforeRender();
            $this->set('section', 'crm');
        }
    	
       public function admin_index()
       {
           $this->set('certs', $this->CustomerType->find('all'));
          
       }
       
       public function admin_add()
       {
           if(!empty($this->request->data))
           {
               $this->CustomerType->create();
               if($this->CustomerType->save($this->request->data))
               {
                   $this->Session->setFlash("New customer type successfully created.", 'flash_success');
                   
               }
               else {
                   $this->Session->setFlash("An error occurred. Please try again.", 'flash_error');
               }
               $this->redirect('/admin/customerTypes');
               
           }
       }
       public function admin_edit($id)
       {
           
           if(!empty($this->request->data))
           {
               $this->CustomerType->id = $id;
               if($this->CustomerType->save($this->request->data))
               {
                   $this->Session->setFlash("Organization Type edited successfully.", 'flash_success');
                   $this->redirect('/admin/customerTypes');
               }
               else {
                   $this->Session->setFlash("An error occurred. Please try again.", 'flash_error');
               }
               
               $this->redirect('/admin/customerTypes');
           }
           $this->data = $this->CustomerType->findById($id);
       }
       
       public function admin_delete($id)
       {
           
         if($this->CustomerType->delete($id))
             $this->Session->setFlash('Organization Type removed successfully.', 'flash_success');
         else {
             $this->Session->setFlash('An error occurred. Please try again.', 'flash_error');
         }
         
         $this->redirect('/admin/customerTypes');
       }
        
        
        }