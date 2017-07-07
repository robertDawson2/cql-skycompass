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
           $this->set('section', 'notifications');
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
       
       function admin_index($offset = 0)
       {
           $notifications = $this->Notification->find('all', array(
                        'conditions' => array(
                            'Notification.user_id'=> array($this->Auth->user('id'), $this->Auth->user('web_user_type')),
                   //         'Notification.allowed_admins LIKE' => "%" . $user['id'] . "%"
                            
                        ),
                            'order' => array('Notification.seen' => 'ASC', 'Notification.created' => 'DESC'),
                            
                        )
                    );
           foreach($notifications as $i => $not)
           {
               $notifications[$i]['elapsed'] = $this->_time_elapsed_string($not['Notification']['created']);
               
           }
          
           $this->set('allNotifications', $notifications);
           
       }
       
       private function _time_elapsed_string($datetime) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }
    
    $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

    }
    
    ?>