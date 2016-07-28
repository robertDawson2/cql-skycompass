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
            
            if(!empty($oldRecord))
            {
                $this->id = $oldRecord['Notification']['id'];
                return $this->saveField('count', $oldRecord['Notification']['count']+$count);
            }
            else
            {
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
            
        }

}

?>