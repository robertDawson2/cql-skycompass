<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    App::uses('AppController', 'Controller');
    
    class NotificationsController extends AppController {

         
        public function beforeFilter() {
            parent::beforeFilter();
           $this->Auth->allow();
        }
       public function admin_ajaxMarkViewed($userId)
       {
           $this->layout = ajax;
           $this->loadModel('User');
           $user = $this->User->findById($userId);
           $notifications = $this->Notification->find('all', array(
               'conditions' => array(
                   'Notification.user_id' => array($userId, $user['User']['web_user_type']),
                   'seen' => 0
               )
           ));
           foreach($notifications as $notification)
           {
               $this->Notification->id = $notification['Notification']['id'];
               $this->Notification->saveField('seen', 1);
           }
          
           echo 'done';
           exit();
       }

    }
    
    ?>