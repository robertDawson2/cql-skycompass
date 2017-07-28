<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');
   
    
    class TodoTypesController extends AppController {

        public function beforeRender() {
            parent::beforeRender();
            $this->set('section', 'crm');
        }
    	
       public function admin_index()
       {
           $this->set('certs', $this->TodoType->find('all'));
          
       }
       
       public function admin_add()
       {
           if(!empty($this->request->data))
           {
               $this->TodoType->create();
               if($this->TodoType->save($this->request->data))
               {
                   $this->Session->setFlash("New todoType successfully created.", 'flash_success');
                   
               }
               else {
                   $this->Session->setFlash("An error occurred. Please try again.", 'flash_error');
               }
               $this->redirect('/admin/todoTypes');
               
           }
       }
       public function admin_edit($id)
       {
           
           if(!empty($this->request->data))
           {
               $this->TodoType->id = $id;
               if($this->TodoType->save($this->request->data))
               {
                   $this->Session->setFlash("TodoType edited successfully.", 'flash_success');
                   $this->redirect('/admin/todoTypes');
               }
               else {
                   $this->Session->setFlash("An error occurred. Please try again.", 'flash_error');
               }
               
               $this->redirect('/admin/todoTypes');
           }
           $this->data = $this->TodoType->findById($id);
       }
       
       public function admin_delete($id)
       {
           
         if($this->TodoType->delete($id))
             $this->Session->setFlash('TodoType removed successfully.', 'flash_success');
         else {
             $this->Session->setFlash('An error occurred. Please try again.', 'flash_error');
         }
         
         $this->redirect('/admin/todoTypes');
       }
        
        
        }