<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');
   
    
    class TrainingController extends AppController {

        public function beforeRender() {
            parent::beforeRender();
            $this->set('section', 'crm');
        }
    	
       public function admin_index()
       {
           $this->set('certs', $this->Training->find('all'));
          
       }
       
       public function admin_add()
       {
           if(!empty($this->request->data))
           {
               $this->Training->create();
               if($this->Training->save($this->request->data))
               {
                   $this->Session->setFlash("New training successfully created.", 'flash_success');
                   
               }
               else {
                   $this->Session->setFlash("An error occurred. Please try again.", 'flash_error');
               }
               $this->redirect('/admin/training');
               
           }
       }
       public function admin_edit($id)
       {
           
           if(!empty($this->request->data))
           {
               $this->Training->id = $id;
               if($this->Training->save($this->request->data))
               {
                   $this->Session->setFlash("Training edited successfully.", 'flash_success');
                   $this->redirect('/admin/training');
               }
               else {
                   $this->Session->setFlash("An error occurred. Please try again.", 'flash_error');
               }
               
               $this->redirect('/admin/training');
           }
           $this->data = $this->Training->findById($id);
       }
       
       public function admin_delete($id)
       {
           
         if($this->Training->delete($id))
             $this->Session->setFlash('Training removed successfully.', 'flash_success');
         else {
             $this->Session->setFlash('An error occurred. Please try again.', 'flash_error');
         }
         
         $this->redirect('/admin/training');
       }
        
        
        }