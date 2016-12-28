<?php

class Notification extends AppModel {
		
    public $name = 'Notification';
    public $useTable = 'notifications';
	
        function queueNotification($userId,$context,$href,$title='New Notification',$message = "New notification.", $count=1)
        {
            
            $oldRecord = $this->find('first', array('conditions'=> array(
                'user_id' => $userId,
                'context' => $context,
                'seen' => 0
            )));
            
            if(!empty($oldRecord) && $count > 0)
            {
                $this->id = $oldRecord['Notification']['id'];
                return $this->saveField('count', $oldRecord['Notification']['count']+$count);
            }
            else
            {
                if($count == 0)
                    $count =1;
               $users = ClassRegistry::init('User');
            $userCheck = $users->findById($userId);
            
            if(!empty($userCheck)) {
            $newRecord = array('Notification' => array(
                'user_id' => $userId,
                'context' => $context,
                'href' => $href,
                'title' => $title,
                'message' => $message,
                'count' => $count
                    
            ));
            
            $this->create();
            return $this->save($newRecord);
            }
            elseif($userId == 'scheduler')
            {
                $return = false;
                $userCheck = $users->find('all', array('conditions'=> array(
                    'isScheduler' => 1
                )));
                foreach($userCheck as $user)
                {
                    $newRecord = array('Notification' => array(
                'user_id' => $user['User']['id'],
                'context' => $context,
                'href' => $href,
                'title' => $title,
                'message' => $message,
                'count' => $count
                    
            ));
            
            $this->create();
            $return = $this->save($newRecord);
                }
                
                return $return; 
            }
            }
            
        }

}

?>