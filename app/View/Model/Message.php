<?php

class Message extends AppModel {
		
    public $name = 'Message';
    public $useTable = 'messages';

    public $actsAs = array('Containable');
    public $belongsTo = array('Chat', 'User');

    
    
    function sendMessage($userIds = null, $message="", $chatId = null)
    {
        $senderId = AuthComponent::user('id');
        
        $chats = ClassRegistry::init('Chat');
        
        if(isset($chatId))
        $chat = $chats->find('first', array(
            'conditions' => array(
                'id' => $chatId
        )));
        
            if(isset($chat))
            {
                $message = array(
                    'chat_id' => $chat['Chat']['id'],
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
       // pr($userId);
        $chatUser = ClassRegistry::init('ChatUser');
        $chats = $chatUser->find('list', array('conditions' => array(
            'user_id' => $userId
        )));
       // pr($chats);
        $unread = $this->find('all', array(
            'conditions' => array(
                'Chat.id' => $chats,
                'Message.seen' => 0,
                'NOT' => array(
                    'Message.user_id' => $userId
                )
            ),
            'order' => 'Message.created DESC'
        ));
        
        return $unread;
    }

    function getRead($limit = 500) {
        $userId = AuthComponent::user('id');
       // pr($userId);
        $chatUser = ClassRegistry::init('ChatUser');
        $chats = $chatUser->find('list', array('conditions' => array(
            'user_id' => $userId
        ),'fields' => 'chat_id', 'chat_id'));
       // pr($chats);
        $read = $this->find('all', array(
            'conditions' => array(
                'Chat.id' => $chats,
                'Message.seen' => 1,
                'NOT' => array(
                    'Message.user_id' => $userId
                )
            ),
            'order' => 'Message.created DESC',
            'limit' => $limit
        ));
        
        return $read;
    }

}

?>