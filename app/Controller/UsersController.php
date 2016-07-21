<?php

App::uses('AppController', 'Controller');

class UsersController extends AppController {

	public $uses = array('User', 'Content');

	function beforeRender() {
		parent::beforeRender();
	}
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->set('section', 'users');
                $this->Auth->allow('passwordReset', 'firstLogin');
	}
	
        function admin_timeEntries($id = null) {
            $user = $this->User->find('first', array('conditions' => array('User.id' => $id), 'recursive'=>2));
       //  pr($user); exit();
            $this->set('user', $user);
        }
	function admin_index() {
		// TODO: add security
                 $type = 'employee';
		$this->set('users', $this->User->find('all'));
               $this->set('type', $type);
               
	}
        function firstLogin($hash = null, $id = null)
        {
            
            $this->layout = 'no_menu';
            
            if(!empty($this->request->data))
            {
                $this->User->id = $this->request->data['User']['id'];
                if($this->User->saveField('password',$this->Auth->password($this->request->data['User']['password'])))
                {
                    $this->_generateResetHash($this->request->data['User']['id']);
                    $this->Session->setFlash('Your new password has been saved. Please Login.', 'flash_success');
                    $this->redirect('/admin');
                }
                else
                {
                    $this->Session->setFlash('There was an error saving your information. Please contact an adminstrator', 'flash_error');
                    $this->redirect($this->referer());
                }
            }
            else
            {
                if(!isset($hash) || !isset($id))
            {
                exit('You are not authorized to view this page');
            }
            $user = $this->User->find('first', array('conditions'=> array(
                'reset_hash' => $hash,
                'id' => $id
            )));
            if(empty($user))
                exit('You are not authorized to view this page');
            $this->set('hash', $hash);
            $this->set('id', $id);
            }
            
            
        }
        function admin_sendWelcomeEmail($id = null)
        {
            if(isset($id))
            {
                $user = $this->User->findById($id);
                if($user['User']['password'] !== null)
                {
                    $this->Session->setFlash('User has already set up their account. Please have user reset password, or reset from the "Employees" tab',
                            'flash_error');
                    $this->redirect('/admin/users');
                    return false;
                }
                else
                {
                    App::uses('CakeEmail', 'Network/Email');
                            $to = array($user['User']['email'] => $user['User']['first_name'] . " " . $user['User']['last_name']);
                            pr($to);
                            $email = new CakeEmail('smtp');
                            $email->template('welcome', 'default')
                            ->emailFormat('both')
                            ->subject('New ' . $this->config['site.name'] . ' Employee Registration')
                            ->viewVars(array('user' => $user, 'config' => $this->config, 'description' => 'New Employee Registration'))
                            ->to($to)
                            ->send();
                    $this->Session->setFlash('Welcome email has been sent to ' . $user['User']['first_name'] . " " . $user['User']['last_name'] . ".",
                            'flash_success');
                    $this->redirect('/admin/users');
                    return true;
                }
            
            }
            else
            {
                App::uses('CakeEmail', 'Network/Email');
                
                $users = $this->User->find('all', array('conditions' => array(
                    'User.password' => NULL,
                    
                )));
                $count = sizeof($users);
                foreach($users as $user):
                            $to = array($user['User']['email'] => $user['User']['first_name'] . " " . $user['User']['last_name']);
                            
                            $email = new CakeEmail('smtp');
                            $email->template('welcome', 'default')
                            ->emailFormat('both')
                            ->subject('New ' . $this->config['site.name'] . ' Employee Registration')
                            ->viewVars(array('user' => $user, 'config' => $this->config, 'description' => 'New Employee Registration'))
                            ->to($to)
                            ->send();
                endforeach;
                    $this->Session->setFlash('Welcome email has been sent to ' . $count . " employees.",
                            'flash_success');
                    $this->redirect('/admin/users');
                    return true;
            }
        }
	
        function admin_generateResetHash()
        {
            $this->_generateResetHash();
            $this->Session->setFlash('New reset hashes generated for all users');
            $this->redirect('/admin');
        }
        function passwordReset($hash = null, $id =null)
        {
            // pre-submission
            if(isset($hash) && isset($id))
            {
                $this->set('id', $id);
                $this->set('hash', $hash);
            }
            else if(!empty($this->request->data)) 
            {
                // Form submission
                $user = $this->User->findById($this->request->data['User']['id']);
                if($user['User']['reset_hash'] === $this->request->data['User']['hash'])
                {
                    $this->User->id = $user['User']['id'];
                    $this->_generateResetHash($user['User']['id']);
                    $this->User->saveField('password', $this->Auth->password($this->request->data['User']['password']));
                    $this->Session->setFlash('Password Updated!');
                    $this->redirect('/admin');
                }
                else
                {
                    exit('Invalid Reset Hash Supplied.');
                }
            }
            else
            {
                exit('You are not authorized to view this page');
            }
        }
        
        private function _generateResetHash($userId = null)
        {
            if(!isset($userId))
            {
                // generate for all users
                $users = $this->User->find('all');
                foreach($users as $u){
                $new_hash=sha1($u['User']['email'].rand(0,100));
                $this->User->id = $u['User']['id'];
                $this->User->saveField('reset_hash', $new_hash);
                
                }
            }
            else
            {
                $u = $this->User->findById($userId);
                $new_hash = sha1($u['User']['email'].rand(0,100));
                $this->User->id = $u['User']['id'];
                $this->User->saveField('reset_hash',$new_hash);
            }
            
            return true;
        }
	function admin_create() {
		// TODO: add security
		if (!empty($this->request->data)) {
			$this->User->set($this->request->data);
			if ($this->User->validates()) {
				$this->request->data['User']['permissions'] = serialize($this->request->data['User']['permissions']);
				$this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);
				if ($this->User->save($this->request->data, false)) {
					$this->queueNotification('The user record you entered has been saved.', '/admin/users');
				} else {
					$this->queueNotification('The user record you entered could not be saved.', null, 'danger');
				}
			}
		}
		$this->set('content', $this->_treeContent());
	}
	
	function admin_edit($id = null) {
		// TODO: add security
		$id = isset($id) ? $id : $this->request->data['User']['id'];
		if (!empty($this->request->data)) {
			$this->User->set($this->request->data);
			if ($this->User->validates()) {
				$this->request->data['User']['permissions'] = serialize($this->request->data['User']['permissions']);
				if ($this->User->save($this->request->data)) {
					$this->queueNotification(sprintf('Your changes to the user record for <b>%s %s</b> have been saved.', $this->request->data['User']['first_name'], $this->request->data['User']['last_name']), '/admin/users');
				} else {
					$this->queueNotification('Your changes could not be saved.', null, 'danger');
				}
			}
		} else {
			$this->User->id = $id;
			$this->request->data = $this->User->read();
			$this->request->data['User']['permissions'] = unserialize($this->request->data['User']['permissions']);
		}
		$this->set('content', $this->_treeContent());
	}
	
	function admin_delete($id) {
		// TODO: add security
		$this->User->id = $id;
		$user = $this->User->read();
		$this->User->delete($id);
		$this->queueNotification('The user record for <b>' . $user['User']['first_name'] . ' ' . $user['User']['last_name'] . '</b> has been deleted.', '/admin/users');
	}
	
	function _treeContent($parentId = null, $depth = 0) {
		$tree = array();
		$contents = $this->Content->find('all', array('conditions' => array('Content.parent_id' => $parentId, 'Content.status' => 'live'), 'fields' => array('Content.id', 'Content.name', 'Content.parent_id')));
		foreach ($contents as $content) {
			$tree[$content['Content']['id']] = $depth . '|' . $content['Content']['name'];
			$t = $this->_treeContent($content['Content']['id'], $depth + 1);
			$tree += $t;
		}
		return $tree;
	}
	
	
	function admin_view($id) {
		// TODO: add security
		$client = $this->Client->find('first', array('conditions' => array('Client.id' => $id)));
		$this->set('client', $client);
	}
	
	public function admin_dashboard() {
		$this->set('section', 'dashboard');
                $userType = $this->Auth->user('web_user_type');
                if($userType === 'employee')
                    $this->render('admin_employee_dashboard');
	}
	
	public function admin_login() {
		if ($this->request->is('post')) {
			$this->request->data['User'] = $this->request->data['Login'];
			if ($this->Auth->login()) {
				$this->User->id = $this->Auth->user('id');
				$this->User->saveField('last_login', date('Y-m-d H:i:s'));
				$this->User->saveField('last_login_ip', $_SERVER['REMOTE_ADDR']);
				return $this->redirect('/admin');
			} else {
				$this->set('failedLogin', true);
				$this->request->data['Login']['password'] = '';
				$this->request->data['User'] = array();
			}
		}
	}
	
	public function admin_logout() {
		$this->redirect($this->Auth->logout());
	}

}
