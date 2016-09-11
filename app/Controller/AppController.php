<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public $uses = array('Config', 'Content','Notification');
	public $components = array('Auth', 'Session');
	public $config = array();
        public $user = array();

        
        public function _loadServices()
        {
            $this->loadModel('Item');
            $itemList = $this->Item->find('all', array('conditions' => array(
                'Item.type' => 'Service',
                'Item.parent_id' => ''
            )));
             $returnArray = array();
            foreach($itemList as $class)
            {
               
                
                $returnArray[] = array(
                    'id' => $class['Item']['id'],
                    'name' => $class['Item']['name'],
                    'class' => 'main-item'
                    
                );
                
                if(!empty($class['Children']))
                {
                    foreach($class['Children'] as $job)
                    {
                        $returnArray[] = array(
                         'id' => $job['id'],
                            'name' => $job['name'],
                            'class' => 'child-item'
                        );
                    }
                }
                
               
                
            }
             return $returnArray;
        }
        
        public function _loadClasses() {
           
            $this->loadModel('Classes');
            $items = $this->Classes->find('all', array('conditions' => array(
                'Classes.parent_id' => ''
            )));
            
            $returnArray = array();
            foreach($items as $class)
            {
               
                
                $returnArray[] = array(
                    'id' => $class['Classes']['id'],
                    'name' => $class['Classes']['name'],
                    'class' => 'main-item'
                    
                );
                
                if(!empty($class['Children']))
                {
                    foreach($class['Children'] as $job)
                    {
                        $returnArray[] = array(
                         'id' => $job['id'],
                            'name' => $job['name'],
                            'class' => 'child-item'
                        );
                    }
                }
                
               
                
            }
             return $returnArray;
              
        }
        
        public function _loadPayrollItems()
        {
            $this->loadModel('PayrollItem');
            $items = $this->PayrollItem->find('all');
            
            $returnArray = array();
            foreach($items as $item)
            {
               
                
                $returnArray[] = array(
                    'id' => $item['PayrollItem']['id'],
                    'name' => $item['PayrollItem']['name'],
                    'class' => ''
                    
                );
           
            }
            return $returnArray;
        }
        
        public function _loadCustomers() {
            $this->loadModel('Customer');
            $customers = $this->Customer->find('all');
            
            $returnArray = array();
            foreach($customers as $customer)
            {
               
                
                $returnArray[] = array(
                    'id' => $customer['Customer']['id'],
                    'name' => $customer['Customer']['name'],
                    'class' => 'main-item'
                    
                );
                
                if(!empty($customer['Jobs']))
                {
                    foreach($customer['Jobs'] as $job)
                    {
                        $returnArray[] = array(
                         'id' => $job['id'],
                            'name' => $job['name'],
                            'class' => 'child-item'
                        );
                    }
                }
                
               
                
            }
            return $returnArray;
        }
        
	public function beforeFilter() {	
		

		date_default_timezone_set('America/New_York');
		
		$this->Auth->authenticate = array(
			'Form' => array('userModel' => 'User', 'fields' => array('username' => 'email'))
		);
		$this->Auth->authorize = 'Controller';
		$this->Auth->loginError = '<div class="alert alert-warning">Login failed. Please try again.</div>';
		$this->Auth->authError = ''; // <div class="alert alert-warning">You must log in to access that page.</div>';
		$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login', 'admin' => true);
		$this->Auth->loginRedirect = array('controller' => 'users', 'action' => 'dashboard', 'admin' => true);
		$this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'dashboard', 'admin' => true);
		
		if ($this->uses != null)
			$this->config = $this->Config->find('list', array('fields' => array('Config.option', 'Config.value')));

		require_once(APP . 'Vendor' . DS . 'Mobile_Detect.php');
		$detect = new Mobile_Detect;
		$this->isMobile = $detect->isMobile();
		$this->isTablet = $detect->isTablet();

		$states = array(
			'' => 'Please select...',
			'AL'=>'Alabama', 
			'AK'=>'Alaska', 
			'AZ'=>'Arizona', 
			'AR'=>'Arkansas', 
			'CA'=>'California', 
			'CO'=>'Colorado', 
			'CT'=>'Connecticut', 
			'DE'=>'Delaware', 
			'DC'=>'District of Columbia', 
			'FL'=>'Florida', 
			'GA'=>'Georgia', 
			'HI'=>'Hawaii', 
			'ID'=>'Idaho', 
			'IL'=>'Illinois', 
			'IN'=>'Indiana', 
			'IA'=>'Iowa', 
			'KS'=>'Kansas', 
			'KY'=>'Kentucky', 
			'LA'=>'Louisiana', 
			'ME'=>'Maine', 
			'MD'=>'Maryland', 
			'MA'=>'Massachusetts', 
			'MI'=>'Michigan', 
			'MN'=>'Minnesota', 
			'MS'=>'Mississippi', 
			'MO'=>'Missouri', 
			'MT'=>'Montana',
			'NE'=>'Nebraska',
			'NV'=>'Nevada',
			'NH'=>'New Hampshire',
			'NJ'=>'New Jersey',
			'NM'=>'New Mexico',
			'NY'=>'New York',
			'NC'=>'North Carolina',
			'ND'=>'North Dakota',
			'OH'=>'Ohio', 
			'OK'=>'Oklahoma', 
			'OR'=>'Oregon', 
			'PA'=>'Pennsylvania', 
			'RI'=>'Rhode Island', 
			'SC'=>'South Carolina', 
			'SD'=>'South Dakota',
			'TN'=>'Tennessee', 
			'TX'=>'Texas', 
			'UT'=>'Utah', 
			'VT'=>'Vermont', 
			'VA'=>'Virginia', 
			'WA'=>'Washington', 
			'WV'=>'West Virginia', 
			'WI'=>'Wisconsin', 
			'WY'=>'Wyoming'
		);
		$this->states = $states;
		$this->set('states', $states);

	}
	
	public function beforeRender() {
		$misc = $this->Session->read('Misc');
		if (isset($misc['notification']) && !empty($misc['notification'])) {
			$this->set('notification', $misc['notification']);
			$this->Session->delete('Misc.notification');
		}
		 $customers = $this->_loadCustomers();
                $this->set('customerList', $customers);
		if ($this->request['prefix'] == 'admin' || $this->request['prefix'] == 'ajax')
			$this->layout = $this->request['prefix'];
		
		$this->set('config', $this->config);

		$this->set('isMobile', $this->isMobile);
		$this->set('isTablet', $this->isTablet);

    	$menu = array();
		$menuContent = $this->Content->find('all', array('fields' => array('Content.id', 'Content.parent_id', 'Content.tag', 'Content.title'), 'conditions' => array('Content.tag' => array('/news-and-events', '/services', '/accreditation', '/training-and-certification', '/resource-library', '/the-cql-difference', '/about'), 'Content.status' => 'live')));
		foreach ($menuContent as $mc) {
			$menu[$mc['Content']['tag']] = array('title' => $mc['Content']['title'], 'menu' => $this->_generateMenu($mc['Content']['id'], false, $this->isMobile));
		}
		$this->set('menu', $menu);

		if ($this->isMobile && $this->request['prefix'] != 'admin') {
			$this->layout = 'mobile';
        	//$this->set('navigation', $this->Content->find('threaded', array('fields' => array('Content.id', 'Content.tag', 'Content.parent_id', 'Content.lft', 'Content.rght'), 'conditions' => array('Content.status' => 'live'), 'order' => array('Content.lft ASC'))));
		}
                
                
                $user = $this->Auth->user();
                if(!empty($user))
                {
                    if($this->Auth->user('super_user'))
                    {
                        $this->loadModel('TimeEntry');
                        $superApprovals = array('expenses' => 0, 'entries' => 0);
                        $list = $this->TimeEntry->find('list', array('conditions' => array(
                            'TimeEntry.approved' => '1',
                            'TimeEntry.super_approved' => null,
                            'NOT' => array('TimeEntry.imported' => '1')
                        )));
                        $superApprovals['entries'] = count($list);
                        
                        $this->loadModel('BillItem');
                        $list = $this->BillItem->find('list', array('conditions' => array(
                            'BillItem.approved' => '1',
                            'BillItem.super_approved' => null,
                            
                                'BillItem.bill_id' => null
                            
                            
                        )));
                        $superApprovals['expenses'] = count($list);
                        $this->set('superUser', $superApprovals);
                    }
                    
                    $user['pmArray'] = unserialize($user['permissions']);
                    $this->set('currentUser', $user);
                   
                    if($this->Auth->user('web_user_type') == 'admin')
                    {
                        $timeEntryApprovals = $this->_getTimeEntryApprovals($this->Auth->user('id'));
                        $expenseApprovals = $this->_getExpenseApprovals($this->Auth->user('id'));
                    }
                     $notifications = $this->Notification->find('all', array(
                        'conditions' => array(
                            'Notification.user_id'=> array($user['id'], $user['web_user_type']),
                   //         'Notification.allowed_admins LIKE' => "%" . $user['id'] . "%"
                            
                        ),
                            'order' => array('Notification.seen' => 'ASC', 'Notification.created' => 'DESC'),
                        'limit' => 10)
                    );
                    $this->user = $user;
                    $this->set('notifications', $notifications);
                    
                    $this->set('newEmployees', $this->_newEmployeesSinceLogin($this->Auth->user('last_login')));
                   
                    
                }
                
                if(isset($this->user['pmArray'][$this->request['controller']][$this->request['action']]) && !$this->user['pmArray'][$this->request['controller']][$this->request['action']])
                {
                    $this->Session->setFlash('You are not authorized to view this section', 'flash_warning');
                    $this->redirect('/admin');
                }
                
                
	}

        private function _getTimeEntryApprovals($compareId)
        {
              $this->Notification->deleteAll(array(
                            'Notification.user_id'=> array($compareId),
 
                            'Notification.context' => 'Admin_TimeApprove'
            ));
            
                        $this->loadModel('ApprovalManager');
                        $approvalIds = $this->ApprovalManager->find('list', array(
                            'conditions' => array(
                                'manager_id' => $compareId
                            ),
                            'fields' => array('user_id', 'user_id')
                        ));
                        $this->loadModel('TimeEntry');
                        $adminApprovals = $this->TimeEntry->find('list', array(
                            'conditions' => array(
                                'approved' => NULL
                                
                            ),
                            'fields' => array('TimeEntry.id', 'TimeEntry.user_id')
                        ));
                       if(!empty($adminApprovals))
                        foreach($adminApprovals as $i => $a)
                        {
                            if(!in_array($a, $approvalIds))
                            {
                                unset($adminApprovals[$i]);
                            }
                        }
                        
                        if(!empty($adminApprovals))
                        {
                            $return = array('Notification' => array(
                                'user_id' => $compareId,
                                'context' => 'Admin_TimeApprove',
                                'href' => '/admin/timeEntry/approve',
                                'title' => 'Approval Needed',
                                'message' => '%i records queued for approval',
                                'count' => count($adminApprovals),
                                'seen' => 0
                            ));
                            $this->Notification->create();
                            $this->Notification->save($return);
                            
                            return true;
                        }
                        
                        return false;
                        
        }
        private function _swapVendorForUser($arr)
        {
            $this->loadModel('User');
            $usersToVendors = $this->User->find('list', array('fields' => array(
                'User.vendor_id', 'User.id'
            ),
              'conditions' => array('NOT' => array('User.vendor_id' => NULL) )));
            
            foreach($arr as $i => $a)
            {
                $arr[$i] = $usersToVendors[$a];
            }
            return $arr;
        }
        private function _getExpenseApprovals($compareId)
        {
            
            $this->Notification->deleteAll(array(
                            'Notification.user_id'=> array($compareId),
 
                            'Notification.context' => 'Admin_ExpenseApprove'
            ));
            
            
                        $this->loadModel('ApprovalManager');
                        $approvalIds = $this->ApprovalManager->find('list', array(
                            'conditions' => array(
                                'manager_id' => $compareId
                            ),
                            'fields' => array('user_id', 'user_id')
                        ));
                        $this->loadModel('BillItem');
                       
                        $adminApprovals = $this->BillItem->find('list', array(
                            
                            'conditions' => array(
                                'approved' => NULL
                                
                            ),
                            'recursive' => -1,
                            'fields' => array('BillItem.id', 'BillItem.vendor_id')
                        ));
                        $adminApprovals = $this->_swapVendorForUser($adminApprovals);
                        
                       if(!empty($adminApprovals))
                        foreach($adminApprovals as $i => $a)
                        {
                            if(!in_array($a, $approvalIds))
                            {
                                unset($adminApprovals[$i]);
                            }
                        }
                        
                        if(!empty($adminApprovals))
                        {
                            $return = array('Notification' => array(
                                'user_id' => $compareId,
                                'context' => 'Admin_ExpenseApprove',
                                'href' => '/admin/expenses/approve',
                                'title' => 'Approval Needed',
                                'message' => '%i expenses queued for approval',
                                'count' => count($adminApprovals),
                                'seen' => 0
                            ));
                            $this->Notification->create();
                            $this->Notification->save($return);
                            
                            return true;
                        }
                        
                        return false;
                        
        }
        private function _newEmployeesSinceLogin($lastLogin) {
            $this->loadModel('User');
            $count = $this->User->find('count', array('conditions' => array(
                'User.web_user_type' => 'employee',
                'User.created >=' => $lastLogin
            )));
            return($count);
        }
    public function isAuthorized($user = null) {
        return true;
    }
		
	public function queueNotification($notification, $redirect = null, $status = 'success') {
		$this->Session->write('Misc.notification', array('notification' => $notification, 'status' => $status));
		if (isset($redirect))
			$this->redirect($redirect);
	}

    public function _generateMenu($parentId = null, $dig = true, $mobile = false) {
    	$parent = $this->Content->find('first', array('conditions' => array('Content.id' => $parentId, 'Content.status' => 'live', 'Content.hide_from_sidebar' => false), 'fields' => array('Content.id', 'Content.parent_id', 'Content.tag', 'Content.name', 'Content.sidebar_title')));
    	$content = $this->Content->find('all', array('order' => 'Content.order_id ASC', 'conditions' => array('Content.parent_id' => $parentId, 'Content.status' => 'live', 'Content.hide_from_sidebar' => false), 'fields' => array('Content.id', 'Content.parent_id', 'Content.tag', 'Content.name', 'Content.sidebar_title')));
    	
    	if (empty($content)) {
    		if ($mobile) {
    			$title = !empty($parent['Content']['sidebar_title']) ? $parent['Content']['sidebar_title'] : $parent['Content']['name'];
				return '<li><a href="' . $parent['Content']['tag'] . '">' . $title . '</a></li>';
    		} else {
    			return;
    		}
    	}

    	if ($mobile) {
    		$title = !empty($parent['Content']['sidebar_title']) ? $parent['Content']['sidebar_title'] : $parent['Content']['name'];
    		$tree = '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">' . $title . ' <span class="caret"></span></a><ul class="dropdown-menu" role="menu">';
    		$tree .= '<li><a href="' . $parent['Content']['tag'] . '">' . $title . '</a>';
    		foreach ($content as $c) {
    			$title = !empty($c['Content']['sidebar_title']) ? $c['Content']['sidebar_title'] : $c['Content']['name'];
				$tree .= '<li><a href="' . $c['Content']['tag'] . '">' . $title . '</a></li>';
    		}
    		$tree .= '</ul></li>';
    	} else {
	    	if ($parentId === null)
	    		$tree = '<ul class="nav-main sf-menu">';
	    	else
	    		$tree = '<ul>';
	    	foreach ($content as $c) {
	    		$tree .= '<li><a href="' . $c['Content']['tag'] . '">' . (!empty($c['Content']['sidebar_title']) ? $c['Content']['sidebar_title'] : $c['Content']['name']) . '</a>';
	    		if ($dig)
	    			$tree .= $this->_generateMenu($c['Content']['id'], false);
	    		$tree .= '</li>';
	    	}
	    	$tree .= '</ul>';
	    }
    	return $tree;
    }
	

}
