<?php

class Chat extends AppModel {
		
    public $name = 'Chat';
    public $useTable = 'chats';
	public $actsAs = array('Containable');

    public $hasMany = array('Message');
    
    public $hasAndBelongsToMany = array('User' => array(
                    'className' => 'User',
                    'joinTable' => 'chats_users',
                    'foreignKey' => 'chat_id',
                    'associationForeignKey' => 'user_id',
                    'unique' => true
                ));

    function getUnread($id)
    {
        
        return $this->find('all', array('contain' => array(
    'Message' => array(
        'conditions' => array('Message.message LIKE ' => "%Rock%"),
        'order' => 'Message.created DESC'
    )
)));
    }
}

?>