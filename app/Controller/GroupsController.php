<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');
   
    
    class GroupsController extends AppController {

        public function beforeRender() {
            parent::beforeRender();
            $this->set('section', 'crm');
        }
    	
       public function admin_index()
       {
           $this->set('allgroups', $this->Group->find('all'));
          
       }
       
       public function admin_add()
       {
           if(!empty($this->request->data))
           {
               $this->Group->create();
               if($this->Group->save($this->request->data))
               {
                   $this->Session->setFlash("New group successfully created.", 'flash_success');
                   
               }
               else {
                   $this->Session->setFlash("An error occurred. Please try again.", 'flash_error');
               }
               $this->redirect('/admin/groups');
               
           }
       }
       public function admin_edit($id)
       {
           
           if(!empty($this->request->data))
           {
               $this->Group->id = $id;
               if($this->Group->save($this->request->data))
               {
                   $this->Session->setFlash("Group edited successfully.", 'flash_success');
                   $this->redirect('/admin/groups');
               }
               else {
                   $this->Session->setFlash("An error occurred. Please try again.", 'flash_error');
               }
               
               $this->redirect('/admin/groups');
           }
           $this->data = $this->Group->findById($id);
       }
       
       public function admin_delete($id)
       {
           
         if($this->Group->delete($id))
             $this->Session->setFlash('Group removed successfully.', 'flash_success');
         else {
             $this->Session->setFlash('An error occurred. Please try again.', 'flash_error');
         }
         
         $this->redirect('/admin/groups');
       }
        
        
        }