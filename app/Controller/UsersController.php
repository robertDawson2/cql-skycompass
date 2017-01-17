<?php

App::uses('AppController', 'Controller');

class UsersController extends AppController {

	public $uses = array('User', 'Content', 'Chat');

	function beforeRender() {
		parent::beforeRender();
	}
        
        
        
        function admin_profile()
        {
            $this->User->bindModel(array('hasMany'=>array('EmployeeEarning'=>array(
                'foreignKey' => 'employee_id'
            ))));
            $user = $this->User->findById($this->Auth->user('id'));
            $this->set('user', $user);
        }
	
        function admin_printRUser($id)
        {
            pr($this->User->findById($id));
            exit();
        }
	function beforeFilter() {
		parent::beforeFilter();
		$this->set('section', 'employees');
                $this->Auth->allow('passwordReset', 'firstLogin', 'forgotPassword');
	}
        public function admin_viewPermissions($id) {
            pr(unserialize($this->User->findById($id)['User']['permissions'])); exit();
        }
        public function admin_resetAllPermissions() {
            $users = $this->User->find('all');
            foreach($users as $u)
            {
                $this->User->id = $u['User']['id'];
                $userPerm = $this->_setPermissions($u['User']['id'], $u['User']['web_user_type']);
                $this->User->saveField('permissions',serialize($userPerm));
                
            }
        }
        public function admin_setPermissions($id, $level)
        {
            $p = $this->_setPermissions($id, $level);
            $this->User->id = $id;
            $this->User->saveField('permissions',serialize($p));
            exit('done');
        }
	private function _setPermissions($id = null, $level = 'employee')
        {
            if($level == 'admin') {
            $permissionsArray = array('site' => array(
                'content' => 1,
                'home_page_features' => 1,
                'events' => 1,
                'news' => 1,
                'galleries' => 1,
                'users' => 1
            ),
                'customers' => array(
                    'admin_index' => 1,
                    'admin_edit' => 1,
                    'admin_add' => 1,
                    'admin_delete' => 1
                ),
                'jobs' => array(
                    'admin_viewAll' => 1,
                    'admin_index' => 1,
                    'admin_edit' => 1,
                    'admin_view' => 1,
                    'admin_add' => 1,
                    'admin_delete' => 1
                ),
                'users' => array(
                    'admin_index' => 1,
                    'admin_edit' => 1,
                    'admin_timeEntries' => 1,
                    'admin_add' => 1,
                    'admin_delete' => 1,
                    'resetPassword' => 1,
                    'setPermissions' => 1
                ),
                'time_entries' => array(
                    'admin_approve' => 1,
                    'admin_index' => 1
                ),
                'bills' => array(
                    'admin_approve' => 1,
                    'admin_index' => 1
                ),
                'config' => array(
                    'admin_index' => 1
                ));
            
            }
            else
            {
                $permissionsArray = array('site' => array(
                'content' => 0,
                'home_page_features' => 0,
                'events' => 0,
                'news' => 0,
                'galleries' => 0,
                'users' => 0
            ),
                'customers' => array(
                    'admin_index' => 1,
                    'admin_edit' => 0,
                    'admin_add' => 0,
                    'admin_delete' => 0
                ),
                'jobs' => array(
                    'admin_viewAll' => 1,
                    'admin_index' => 1,
                    'admin_edit' => 0,
                    'admin_view' => 1,
                    'admin_add' => 0,
                    'admin_delete' => 0
                ),
                'users' => array(
                    'admin_index' => 0,
                    'admin_edit' => 0,
                    'admin_timeEntries' => 0,
                    'admin_add' => 0,
                    'admin_delete' => 0,
                    'resetPassword' => 0,
                    'setPermissions' => 0
                ),
                'time_entries' => array(
                    'admin_approve' => 0,
                    'admin_index' => 0
                ),
                'bills' => array(
                    'admin_approve' => 0,
                    'admin_index' => 0
                ),
                'config' => array(
                    'admin_index' => 0
                ));
            }
            
            return $permissionsArray;
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
                'User.reset_hash' => $hash,
                'User.id' => $id
            )));
            if(empty($user))
                exit('You are not authorized to view this page');
            $this->set('hash', $hash);
            $this->set('id', $id);
            }
            
            
        }
        
        function forgotPassword() {
            $this->layout = 'no_menu';
            if(!empty($this->request->data))
            {
                $user = $this->User->find('first', array('conditions' => array('User.email' => $this->request->data['User']['email'])));
                if(empty($user))
                {
                    $this->Session->setFlash('No user found that matches this email address. Please try again.', 'flash_error');
                    
                }
                else
                {
                    $this->User->id = $user['User']['id'];
                    $this->User->saveField('password', null);
                    App::uses('CakeEmail', 'Network/Email');
                            $to = array($user['User']['email']);
                            
                            $email = new CakeEmail('smtp');
                            $email->template('reset', 'default')
                            ->emailFormat('both')
                            ->subject($this->config['site.name'] . ' Password Reset')
                            ->viewVars(array('user' => $user, 'config' => $this->config, 'description' => 'Password Reset Request'))
                            ->to($to)
                            ->send();
                    $this->Session->setFlash('Password reset request has been sent to ' . $user['User']['first_name'] . " " . $user['User']['last_name'] . ".",
                            'flash_success');
                    $this->redirect('/admin');
                }
            }
        }
        
        function admin_quickEmail() {
            if(!empty($this->request->data))
            {
                
                $error = false;
                $message = "Email successfully sent to ";
          
 
                if(!isset($this->request->data['to']) || empty($this->request->data['to'])
                        || (strpos($this->request->data['to'], "@") === false) || 
                        (strpos($this->request->data['to'], ".") === false))
                {
                    $error = true;
                    $message = "You have not provided a valid email address. (" . $this->request->data['to'] . ")";
                }
                if(!isset($this->request->data['message']) || empty($this->request->data['message']))
                {
                    $error = true;
                    $message = "No message provided.";
                }
                
                $from = null;
                if($this->request->data['from'] == "company")
                    $from = 'noreply@c-q-l.org';
                else
                    $from = $this->Auth->user('email');
                
              
                if(!$error)
                {
                    App::uses('CakeEmail', 'Network/Email');
                            $to = $this->request->data['to'];
                            
                            $email = new CakeEmail('smtp');
                            $email->emailFormat('html')
                            ->subject($this->request->data['subject'])
                            ->from($from)
                            ->to($this->request->data['to'])
                            ->send($this->request->data['message']);
                    $this->Session->setFlash('Message has been sent to ' . $this->request->data['to'] . ".",
                            'flash_success');
                           
                    
                    
                }
                else
                    $this->Session->setFlash($message, 'flash_error');
                
                
            }
            $this->redirect($this->referer('/admin'));
        }
        function admin_addNewPermissions($tag = "")
        {
            if($tag === "blue42setHIKE")
            {
                // Make array here to add
                $addArray = array(
                    'jobs' => array(
                        'admin_scheduler' => 0,
                        
                    ),
                    'schedule' => array(
                        'admin_alertAllUsers' => 0,
                        'admin_approveTimeOff' => 0,
                        'admin_viewServiceAreas' => 0
                    ),
                    'taskListTemplates' => array(
                        'admin_index' => 0,
                        'admin_create' => 0
                    ));
                $users = $this->User->find('all');
                
                foreach($users as $user) {
                   
                    $oldArray = unserialize($user['User']['permissions']);
                    if(isset($oldArray['time_entries'])) {
                    $addArray['timeEntries'] = $oldArray['time_entries'];
                    unset($oldArray['time_entries']);
                    }
                    else
                    {
                        $addArray['timeEntries'] = array(
                    'admin_approve' => 0,
                        'admin_index' => 0
                );
                    }
                    $oldArray['schedule']['admin_viewServiceAreas'] = 0;
                    $newArray = $oldArray + $addArray;
                    $this->User->id = $user['User']['id'];
                    $this->User->saveField('permissions', serialize($newArray));
                     
                }
                
                exit("Done");
            }
            exit("NICE TRY HAUS");
        }
        function admin_sendWelcomeEmail($id = null)
        {
            if(isset($id))
            {
                $user = $this->User->findById($id);
                
                if($user['User']['password'] != null)
                {
                    $this->Session->setFlash('User has already set up their account. Please have user reset password, or reset from the "Employees" tab',
                            'flash_error');
                    $this->redirect('/admin/users');
                    return false;
                }
                else
                {
                    App::uses('CakeEmail', 'Network/Email');
                            $to = array($user['User']['email']);
                            
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
                set_time_limit(0);
                App::uses('CakeEmail', 'Network/Email');
                
                $users = $this->User->find('all', array('conditions' => array(
                    'User.password' => NULL,
                    'User.email LIKE' => '%@%.%',
                    'NOT' => array(
                        'User.email' => '',
                        'User.email' => null
                    )
                )));
               
                $count = sizeof($users);
                foreach($users as $user):
                            $to = array(trim($user['User']['email']));
                            
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
                    if($this->User->saveField('password', $this->Auth->password($this->request->data['User']['password'])))
                    {
                    $this->Session->setFlash('Password Updated!', 'flash_success');
                    }
                    else
                    {
                        $this->Session->setFlash('An error occurred.', 'flash_error');
                    }
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
        function admin_ajaxAddManager($userId, $managerId)
        {
            $this->layout = 'ajax';
            $newRecord = array('ApprovalManager' => array(
                'user_id' => $userId,
                'manager_id' => $managerId
            ));
            $this->loadModel('ApprovalManager');
            $this->ApprovalManager->create();
            if($this->ApprovalManager->save($newRecord))
                exit('ok');
            else
                exit('error');
        }
        function admin_ajaxRemoveManager($userId, $managerId)
        {
            $this->layout = 'ajax';
            
            $this->loadModel('ApprovalManager');
            
            if($this->ApprovalManager->deleteAll(array('user_id' => $userId, 'manager_id' => $managerId)))
                exit('ok');
            else
                exit('error');
        }
        
        function admin_ajaxUpdateSchedulingNotes()
        {
            $this->layout = 'ajax';

            $this->User->id = $this->Auth->user('id');
            if($this->User->saveField('scheduling_employee_notes', $this->request->data['notes']))
                    echo 'success';
            else
                echo 'error';
            
            exit();
        }
        
        function admin_ajaxAddAbility($userId, $managerId)
        {
            $this->layout = 'ajax';
            $newRecord = array(
                    
                        'user_id' => $userId,
                        'ability_id' => $managerId
                    
                );
            
            $this->loadModel('UserAbility');
           $this->UserAbility->create();
            if($this->UserAbility->save($newRecord))
                exit('ok');
            else
                exit('error');
        }
        function admin_ajaxRemoveAbility($userId, $managerId)
        {
            $this->layout = 'ajax';
            
            $this->loadModel('UserAbility');
            
            if($this->UserAbility->deleteAll(array('user_id' => $userId, 'ability_id' => $managerId)))
                exit('ok');
            else
                exit('error');
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
            $admins = $this->User->find('all', array('conditions' => array('OR' => array(
                'User.web_user_type' => 'admin',
                'User.permissions LIKE' => '%s:12:"time_entries";a:2:{s:13:"admin_approve";s:1:"1"%'
        )
                
            ), 'recursive' => -1,
                'fields' => array(
                    'User.id', 'User.first_name', 'User.last_name'
                ),
                'order' => 'User.id ASC'));
           
            
            $this->loadModel('ApprovalManager');
            $currentManagerIds = $this->ApprovalManager->find('list', array('conditions'=>array(
                'user_id' => $id
            
            ),
                'fields' => array('manager_id', 'manager_id')));
           
            $currentManagers = $this->User->find('all', array('conditions' => array(
                'User.id' => $currentManagerIds
            ),
                'recursive' => -1,
                'fields' => array('User.id','User.first_name', 'User.last_name')));
            
            foreach($admins as $i => $admin)
            {
                if(in_array($admin['User']['id'], $currentManagerIds))
                        unset($admins[$i]);
            }
             $this->set('admins', $admins);
             $this->set('currentAdmins', $currentManagers);
            
		$id = isset($id) ? $id : $this->request->data['User']['id'];
		if (!empty($this->request->data)) {
			$this->User->set($this->request->data);
			if ($this->User->validates()) {
                            if(!empty($this->request->data['User']['new_password']))
                                $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['new_password']);
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
                $this->set('user', $this->User->findById($id));
                
                $this->loadModel('Ability');
                $this->set('abilities', $this->Ability->find('list'));
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
                $this->set('upcoming', $this->_getUpcomingScheduleEvents(3));
                if($userType === 'employee')
                {
                    $this->set('jobsProgress', $this->_getOpenJobProgress($this->Auth->user('id')));
                    $this->render('admin_employee_dashboard');
                    
                }
                else
                {
                   $this->set('jobsProgress', $this->_getOpenJobProgress()); 
                }
               
                
                
                
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
