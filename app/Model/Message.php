<?php

class Message extends AppModel {
		
    public $name = 'Message';
    public $useTable = 'messages';


    public $belongsTo = array('Chat', 'User');

    
    
    function sendMessage($userIds = array(), $message="")
    {
        $senderId = AuthComponent::user('id');
        $userIds[] = $senderId;
       
        $chats = ClassRegistry::init('Chat');
        $chat = $chats->find('all', array(
            'contain' => array('User' => array(
            'conditions' => array(
            'User.id' => $userIds
                ))
        )));
        foreach($chat as $i => $c)
            if(count($c['User']) < count($userIds))
            {
                unset($chat[$i]);
            }
            if(!empty($chat))
                $chat = $chat[0]["Chat"];
            else
                $chat = null;
            
            if(isset($chat))
            {
                $message = array(
                    'chat_id' => $chat['id'],
                    'user_id' => $senderId,
                    'message' => $message
                );
                $this->create();
                $this->save($message);
                     
                return true;
            }
            else
            {
                $chats->create();
                $chats->save();
                $chatId = $chats->id;
                
                $users = ClassRegistry::init('ChatsUser');
                foreach($userIds as $u)
                {
                    
                   $users->create();
                   $users->save(array(
                       'chat_id' => $chatId,
                       'user_id' => $u
                   ));
                   
                }
                $message = array(
                    'chat_id' => $chatId,
                    'user_id' => $senderId,
                    'message' => $message
                );
                $this->create();
                $this->save($message);
                     
                return true;
            }
       
        
        return false;
    }
    
    function getUnread() {
        $userId = AuthComponent::user('id');
        
        $unread = $this->find('all', array(
            'contain' => array(
                'Chat' => array(
                    'User' => array(
                        'conditions' => array(
                            'User.id' => $userId
                        )
                    )
                ),
                'User'
            ),
            'conditions' => array( 'NOT' => array(
                'Message.user_id' => $userId),
                'Message.seen' => 0
            ),
            'order' => 'Message.created DESC'
        ));
        
        return $unread;
    }


}

?>