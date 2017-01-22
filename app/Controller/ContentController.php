<?php

    App::uses('AppController', 'Controller');

    class ContentController extends AppController {

    	public $name = 'Content';
        public $uses = array('Content', 'Feature', 'News', 'Event');

        public function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow('home', 'display', 'import', 'map', 'tag', 'forms', 'search', 'event','s');
			$this->set('section', 'web');
        }

        public function beforeRender() {
        	parent::beforeRender();
           
        }
        
        public function s($urlId) {
            $this->loadModel('AutoLink');
            $record = $this->AutoLink->find('first', array('conditions' => array('BINARY (tiny_url) LIKE' => "%".$urlId)));
            if(!empty($record))
                $this->redirect($record['AutoLink']['url']);
            else
                echo "The URL you requested cannot be found. Please contact CQL for more information.";
            
            exit();
            
        }

        public function herp() {
        	exit();
        	//$this->Content->recover('parent');
        	exit('ok');
        }
        public function home() {
        	//$this->set('columns', $this->Content->find('all', array('conditions' => array('Content.parent_id' => 8, 'Content.status' => 'live'))));
        	$columns = $this->Content->find('all', array('conditions' => array('Content.parent_id' => 8, 'Content.status' => 'live')));
        	$columnData = array();
        	foreach ($columns as $c) {
        		$tag = explode('-', $c['Content']['tag']);
        		if (!isset($tag[0]) || !is_numeric($tag[0]))
        			$tag[0] = 1;
        		if (!isset($tag[1]) || !is_numeric($tag[1]))
        			$tag[1] = 1;
        		if (!isset($tag[2]) || !is_numeric($tag[2]))
        			$tag[2] = 1;
        		
        		if (!isset($columnData[$tag[0]]))
        			$columnData[$tag[0]] = array();

        		$c['Content']['tag'] = $tag[2];
        		$columnData[$tag[0]][$tag[1]] = $c;
        	}
        	ksort($columnData);
        	$this->set('columns', $columnData);

        	//$this->set('mainColumn', $this->Content->find('first', array('conditions' => array('Content.tag' => '/home/main-column', 'Content.status' => 'live'))));
        	//$this->set('secondColumn', $this->Content->find('first', array('conditions' => array('Content.tag' => '/home/second-column', 'Content.status' => 'live'))));
        	//$this->set('thirdColumn', $this->Content->find('first', array('conditions' => array('Content.tag' => '/home/third-column', 'Content.status' => 'live'))));
			$this->set('title_for_layout', 'Home');
			
			$this->set('features', $this->Feature->find('all', array('order' => 'Feature.order_id ASC', 'conditions' => array(
				array('OR' => array(
    				array('Feature.begin_showing' => '0000-00-00 00:00:00'),
				    array('Feature.begin_showing <=' => date('Y-m-d H:i:s'))
				)),
				array('OR' => array(
    				array('Feature.stop_showing' => '0000-00-00 00:00:00'),
				    array('Feature.stop_showing >=' => date('Y-m-d H:i:s'))
				))
			))));

			$menu = array();
			$menuContent = $this->Content->find('all', array('fields' => array('Content.id', 'Content.parent_id', 'Content.tag', 'Content.title'), 'conditions' => array('Content.tag' => array('/news-and-events', '/services', '/accreditation', '/training-and-certification', '/resource-library', '/the-cql-difference', '/about'), 'Content.status' => 'live')));
			foreach ($menuContent as $mc) {
				$menu[$mc['Content']['tag']] = array('title' => $mc['Content']['title'], 'menu' => $this->_generateMenu($mc['Content']['id'], false));
			}
			$this->set('menu', $menu);
        }

        public function display($tag, $page = null) {
        	//$this->Content->recover('parent');
        	//exit();
            $content = $this->Content->find('first', array('conditions' => array('Content.tag' => $tag, 'Content.status' => 'live')));
            if ($content['Content']['form_id'] != 0) {
            	$form = $this->Form->find('first', array('conditions' => array('Form.id' => $content['Content']['form_id'])));
            	$this->set('form', $form);
            }

            if ($content['Content']['slidedown'] == 1) {
            	throw new NotFoundException('That page could not be found.');
            }

            if (substr($tag, 0, 16) == '/news-and-events') {
            	$this->set('news', $this->News->find('all', array('limit' => 6, 'order' => 'News.stickied DESC, News.order_id ASC, News.begin_showing ASC', 'conditions' => array(
					array('OR' => array(
	    				array('News.begin_showing' => '0000-00-00 00:00:00'),
					    array('News.begin_showing <=' => date('Y-m-d H:i:s'))
					)),
					array('OR' => array(
	    				array('News.stop_showing' => '0000-00-00 00:00:00'),
					    array('News.stop_showing >=' => date('Y-m-d H:i:s'))
					))
				))));
				$this->set('events', $this->Event->find('all', array('limit' => 6, 'order' => 'Event.stickied DESC, Event.order_id ASC, Event.start_date ASC', 'conditions' => array(
					array('OR' => array(
	    				array('Event.begin_showing' => '0000-00-00 00:00:00'),
					    array('Event.begin_showing <=' => date('Y-m-d H:i:s'))
					)),
					array('OR' => array(
	    				array('Event.end_date' => '0000-00-00 00:00:00'),
					    array('Event.end_date >=' => date('Y-m-d H:i:s'))
					))
				))));
            } else if ($tag == '/sitemap') {
            	$ccc = array('Content' => array(
            		'hide_sidebar' => 1
            	));
            	$sitemap = array();
            	$sections = $this->Content->find('all', array('fields' => array('Content.id', 'Content.parent_id', 'Content.tag', 'Content.title'), 'conditions' => array('Content.tag' => array('/news-and-events', '/services', '/accreditation', '/training-and-certification', '/resource-library', '/the-cql-difference', '/about'), 'Content.status' => 'live')));
				foreach ($sections as $c) {
					$sitemap[$c['Content']['tag']] = array('title' => $c['Content']['title'], 'menu' => $this->_generateMenu($c['Content']['id'], true, false));
				}
				$content['Content']['hide_sidebar'] = 1;
				$this->set('sitemap', $sitemap);
            }

			//prd($_SERVER['DOCUMENT_ROOT'] . html_entity_decode($content['Content']['link']));
            
            if (!empty($content['Content']['link'])) {
            	$content['Content']['link'] = str_replace('%20', ' ', $content['Content']['link']);
            	if (substr($content['Content']['link'], 0, 1) == '/' && substr($content['Content']['link'], -3) == 'pdf') {
	            	$file = $_SERVER['DOCUMENT_ROOT'] . $content['Content']['link'];
					$finfo = new finfo(FILEINFO_MIME, '/usr/share/file/magic');
					header("Content-Disposition: attachment; filename=\"" . basename($_SERVER['DOCUMENT_ROOT'] . $content['Content']['link']) . "\"");
					header('Content-type: ' . $finfo->file($file));
					echo file_get_contents($file);
				} else {
					header('HTTP/1.1 301 Moved Permanently');
					header('Location: ' . $content['Content']['link']);
				}
				exit();
            }

            if (!empty($this->request->data)) {
            	$this->FormData->set($this->request->data);
				if ($this->FormData->validates()) {
					
					$formResults = array(
						'form' => $form['Form']['title'],
						'data' => serialize($this->request->data['FormData']),
						'ip_address' => $_SERVER['REMOTE_ADDR']
					);
					if ($this->FormResult->save($formResults)) {
						App::uses('CakeEmail', 'Network/Email');
						/*
						$to = array();
						$addresses = explode(',', $this->config['form_recipients.contact_' . $section]);
						foreach ($addresses as $email) {
							$email = explode('|', $email);
							if (!isset($email[1]))
								$email[1] = $email[0];
							$to[$email[1]] = $email[0];
						}
						*/
						$addresses = explode(',', $form['Form']['recipients']);
						foreach ($addresses as $email) {
							$email = explode('|', $email);
							if (!isset($email[1]))
								$email[1] = $email[0];
							$to[$email[1]] = $email[0];
						}

						$email = new CakeEmail('smtp');
						$email->template('form', 'default')
						->emailFormat('both')
						->subject('Website Form (' . $form['Form']['title'] . ')')
						->viewVars(array('title' => 'Website Form (' . $form['Form']['title']  . ')', 'description' => 'Website Form (' . $form['Form']['title']  . ')', 'data' => $this->request->data['FormData']))
						->to($to)
						->send();
	            		$content['Content']['content'] = '<p>Thank you! Your form has been submitted.</p>';
	            		$this->set('submitted', true);
					} else {

	            		$content['Content']['content'] = '<p>There was an error while saving your request. Please go back and try again.</p>';
	            		$this->set('submitted', true);
					}
	            }
	        }


            // Redirect if necessary
            if (strlen(trim($content['Content']['redirect_url'])) > 0) {
            	//prd($content['Content']['redirect_url']);
            	$this->redirect($content['Content']['redirect_url']);

            }
            /*
            if (strlen(trim($content['Content']['link'])) > 0) {
            	prd($content['Content']['link'])
            	header('HTTP/1.1 301 Moved Permanently');
            	header('Location: ' . $content['Content']['link']);
            	exit();
            }
            */

			$this->set('content', $content);
			if(!empty($content['Content']['sidebar_image'])) {
			$fbFeatured = $content['Content']['sidebar_image'];
			}
			else{
				$fbFeatured = "none";
			}
			$fbFeatured = "http://c-q-l.org". str_replace(" ", "%20", $fbFeatured);
			$this->set('fbFeatured', $fbFeatured);
			$tag = explode('/', $content['Content']['tag']);
			
			//exit(implode(PHP_EOL, $this->Content->generateTreeList(null, null, null, '')));
			$top = $this->Content->find('first', array('conditions' => array('Content.tag' => '/' . $tag[1], 'Content.status' => 'live')));
			$this->Content->id = $top['Content']['id'];
			$this->set('sidebar', $this->Content->find('threaded', array('conditions' => array('Content.status' => 'live', 'Content.hide_from_sidebar' => false, 'Content.lft >' => $top['Content']['lft'], 'Content.rght <' => $top['Content']['rght']), 'order' => 'Content.order_id ASC, Content.name ASC', 'fields' => array('Content.id', 'Content.parent_id', 'Content.tag', 'Content.name', 'Content.title', 'Content.sidebar_title', 'Content.slidedown', 'Content.link'))));
			$this->set('top', $top);
			
			$this->set('lineage', $this->_generateLineage($content));
			$this->set('breadcrumbs', $this->_generateBreadcrumbs($content));

			//$this->set('forms', $this->Form->find('list', array('fields' => array('Form.id', 'Form.form'))));
            if ($tag == 'contact') {
				$this->render($tag);
			} else {
				if (isset($page))
					$this->render($page);
			}

			

        }

        public function search() {
        	$db = $this->Content->getDataSource();
        	// Prepared statement to handle sanitization and prevent SQL injection
        	if (isset($this->request->data['section'])) {
                    
	        	$this->set('results', $db->fetchAll(
	    			"SELECT id, title, tag, content, MATCH (title,content) AGAINST ('?' IN BOOLEAN MODE) AS score FROM content WHERE MATCH (title,content) AGAINST ('?' IN BOOLEAN MODE) and hide_from_search = 0 and status = ? and tag like ?",
	    			array($this->request->data['query'], $this->request->data['query'], 'live', $this->request->data['section'] . '%')
				));
				$this->set('searchSection', $this->request->data['section']);
				$content = $this->Content->find('first', array('conditions' => array('Content.tag' => $this->request->data['section'], 'Content.status' => 'live')));
				$parent = $this->Content->find('first', array('conditions' => array('Content.id' => $content['Content']['parent_id'], 'Content.status' => 'live')));
				if ($this->request->data['section'] == '/the-cql-difference/personal-outcome-measures/pom-blog')
					$parent = $this->Content->find('first', array('conditions' => array('Content.id' => $parent['Content']['parent_id'], 'Content.status' => 'live')));
				$this->set('content', $content);
				$this->set('sidebar', $this->Content->find('threaded', array('conditions' => array('Content.status' => 'live', 'Content.hide_from_sidebar' => false, 'Content.lft >' => $parent['Content']['lft'], 'Content.rght <' => $parent['Content']['rght']), 'order' => 'Content.order_id ASC, Content.name ASC', 'fields' => array('Content.id', 'Content.parent_id', 'Content.tag', 'Content.name', 'Content.title', 'Content.sidebar_title', 'Content.slidedown', 'Content.link'))));
				$this->set('lineage', $this->_generateLineage($content));
	        } else {
                    
	        	$this->set('results', $this->Content->query(
    				"SELECT id, title, tag, content, MATCH (title,content) AGAINST (\"".$this->request->data['query']."\" IN BOOLEAN MODE) AS score FROM content WHERE MATCH (title,content) AGAINST (\"". $this->request->data['query'] . "\" IN BOOLEAN MODE) and hide_from_search = 0 and status = \"live\" HAVING score > 0.5 ORDER BY score DESC"
    				
				));
	        }
//                $log = $db->getLog(false, false);       
//debug($log); exit();
			$this->set('query',$this->request->data['query']);
        }

        private function _listParents($parentId = null, $depth = 0) {
        	$results = array();
        	$content = $this->Content->find('list', array('fields' => array('Content.id', 'Content.name'), 'conditions' => array('Content.status' => 'live', 'Content.parent_id' => $parentId)));
        	foreach ($content as $id => $name) {
        		$results[$id] = str_pad($name, strlen($name) + $depth * 2, '- ', STR_PAD_LEFT);
        		$results += $this->_listParents($id, $depth + 1);
        	}
        	return $results;
        }

        private function _listRedirects($parentId = null, $depth = 0) {
        	$results = array();
        	$content = $this->Content->find('all', array('fields' => array('Content.id', 'Content.tag', 'Content.name'), 'conditions' => array('Content.status' => array('live', 'draft'), 'Content.parent_id' => $parentId)));
        	foreach ($content as $c) {
        		$results[$c['Content']['tag']] = str_pad($c['Content']['name'], strlen($c['Content']['name']) + $depth * 2, '- ', STR_PAD_LEFT);
        		$results += $this->_listRedirects($c['Content']['id'], $depth + 1);
        	}
        	return $results;
        }

        private function _generateLineage($content) {
        	$parents = array($content['Content']['tag'] => $content['Content']['parent_id']);
        	while ($content['Content']['parent_id'] !== null) {
        		$content = $this->Content->find('first', array('conditions' => array('Content.id' => $content['Content']['parent_id'], 'Content.status' => 'live'), 'fields' => array('Content.id', 'Content.parent_id', 'Content.tag', 'Content.title')));
        		$parents[$content['Content']['tag']] = $content['Content']['parent_id'];
        	}
        	$parents['/'] = 0;
        	return array_reverse($parents);
        }

        private function _generateBreadcrumbs($content) {
        	$crumbs = array($content['Content']['tag'] => $content['Content']['title']);
        	while ($content['Content']['parent_id'] !== null) {
        		$content = $this->Content->find('first', array('conditions' => array('Content.id' => $content['Content']['parent_id'], 'Content.status' => 'live'), 'fields' => array('Content.id', 'Content.parent_id', 'Content.tag', 'Content.title')));
        		$crumbs[$content['Content']['tag']] = $content['Content']['title'];
        	}
        	$crumbs['/'] = 'Home';
        	return array_reverse($crumbs);
        }

		public function renderMenu($array,$root=true,$hasChildren=false) { 
			if (count($array)) { 
				if ($root) 
					echo "\n<ul>\n"; 
				else 
					if ($hasChildren) 
						echo "\n<ul>\n" ; 
					else 
						echo "\n<ul>\n"; 
				foreach ($array as $vals) { 

					if (count($vals['children'])) 
						$liClass="dropdown" ; 
					else 
						$liClass=null ; 

					if ($hasChildren) 
						$liClass.= ' submenu' ; 


					echo "<li>".$this->Html->link($vals['MenuItem']['title'],$vals['MenuItem']['link'],array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown')); 
					if (count($vals['children'])) { 
						$this->renderMenu($vals['children'],false,true); 
					} 
					echo "</li>\n"; 
				} 
				echo "</ul>\n"; 
			}  
		} 
		
		public function import() {
			exit();
			set_time_limit(300);
			$content = file($_SERVER['DOCUMENT_ROOT'] . '/content.txt');
			$parents = array(0 => 0);
			foreach ($content as $c) {
				$this->Content2->create();
				$c = trim($c);
				$c = explode('|', $c);
				if (left($c[0], 1) != '-') {
					$parentId = $parents[0];
					$this->Content2->save(array('parent_id' => $parentId, 'tag' => $c[1], 'name' => $c[0], 'title' => $c[0], 'content' => '<p>' . $c[0] . ' Content</p>', 'status' => 'live'));
					$parents[1] = $this->Content2->id;
				} else if (left($c[0], 4) == '----') {
					$parentId = $parents[4];
					$c[0] = substr($c[0], 4);
					$this->Content2->save(array('parent_id' => $parentId, 'tag' => $c[1], 'name' => $c[0], 'title' => $c[0], 'content' => '<p>' . $c[0] . ' Content</p>', 'status' => 'live'));
					$parents[5] = $this->Content2->id;
				} else if (left($c[0], 3) == '---') {
					$parentId = $parents[3];
					$c[0] = substr($c[0], 3);
					$this->Content2->save(array('parent_id' => $parentId, 'tag' => $c[1], 'name' => $c[0], 'title' => $c[0], 'content' => '<p>' . $c[0] . ' Content</p>', 'status' => 'live'));
					$parents[4] = $this->Content2->id;
				} else if (left($c[0], 2) == '--') {
					$parentId = $parents[2];
					$c[0] = substr($c[0], 2);
					$this->Content2->save(array('parent_id' => $parentId, 'tag' => $c[1], 'name' => $c[0], 'title' => $c[0], 'content' => '<p>' . $c[0] . ' Content</p>', 'status' => 'live'));
					$parents[3] = $this->Content2->id;
				} else if (left($c[0], 1) == '-') {
					$parentId = $parents[1];
					$c[0] = substr($c[0], 1);
					$this->Content2->save(array('parent_id' => $parentId, 'tag' => $c[1], 'name' => $c[0], 'title' => $c[0], 'content' => '<p>' . $c[0] . ' Content</p>', 'status' => 'live'));
					$parents[2] = $this->Content2->id;
				}
				if (isset($c[2]))
					$this->Content2->saveField('content', $c[2]);
				
				echo 'Saving ' . $c[0] . ' (' . $c[1] . ') as id ' . $this->Content2->id . ', parent id ' . $parentId . '<br>';
			}
			exit();
		}
		
		public function map() {
			$this->_map(null);
			exit();
		}
		private function _map($id) {
			$content = $this->Content->find('all', array('conditions' => array('Content.parent_id' => $id)));
			if (!empty($content)) {
				echo '<ul>';
				foreach ($content as $c) {
					echo '<li>' . $c['Content']['name'] . ' (' . $c['Content']['tag'] . ')';
					$this->_map($c['Content']['id']);
					echo '</li>';
				}
				echo '</ul>';
			}
		}
		public function tag() {
			exit();
			$this->_tag(null, '/');
			exit();
		}
		private function _tag($id, $path) {
			$content = $this->Content->find('all', array('conditions' => array('Content.parent_id' => $id)));
			if (!empty($content)) {
				echo '<ul>';
				foreach ($content as $c) {
					echo '<li>Changing ' . $c['Content']['tag'] . ' to ' . $path . $c['Content']['tag'];
					$this->Content->id = $c['Content']['id'];
					$this->Content->saveField('tag', $path . $c['Content']['tag']);
					$this->_tag($c['Content']['id'], $path . $c['Content']['tag'] . '/');
					echo '</li>';
				}
				echo '</ul>';
			}
		}

		public function admin_index($parent = 0) {
			$parent = $this->Content->find('first', array('conditions' => array('Content.id' => $parent)));
			if (empty($parent))
				$parent = array('Content' => array('id' => null, 'name' => 'Content'));
			$this->set('contents', $this->Content->find('all', array('conditions' => array('Content.parent_id' => $parent['Content']['id'], 'Content.status' => array('live', 'draft')))));
			$this->set('parentId', $parent['Content']['id']);
			$this->set('title_for_layout', $parent['Content']['name']);
			if ($parent['Content']['id'] != 0)
				$this->set('breadcrumbs', array('/admin/content' => 'Content') + $this->_fetchBreadcrumbs($parent['Content']['id']));
			else
				$this->set('breadcrumbs', array('-' => 'Content'));
		}
		
		function admin_create($parentId = null) {
			if (!empty($this->request->data)) {
				if (!isset($this->request->data['Content']['order_id']) || !is_numeric($this->request->data['Content']['order_id']))
					$this->request->data['Content']['order_id'] = 0;
                                
				$this->request->data['Content']['parent_id'] = $parentId;
                $this->Content->save($this->request->data['Content']);
                $this->queueNotification('Your page has been created.', '/admin/content/index/' . $parentId);
            } else {
            	$this->request->data['Content'] = array('show_sharing_options' => true, 'gallery_timing' => 5000);
            }
			$this->set('parentId', $parentId);
            $this->set('redirects', array('' => 'No redirect') + $this->_listRedirects());
			$this->set('parent', $this->Content->find('first', array('conditions' => array('Content.id' => $parentId))));
			$this->set('breadcrumbs', array('/admin/content' => 'Content') + $this->_fetchBreadcrumbs($parentId, true) + array('-' => 'New Page'));
		}
		
        function admin_edit($id = null) {
            if (!empty($this->request->data)) {
            	if (!isset($this->request->data['Content']['order_id']) || !is_numeric($this->request->data['Content']['order_id']))
					$this->request->data['Content']['order_id'] = 0;

                $this->Content->id = $this->data['Content']['id'];
                $content = $this->Content->read();
                if ($this->Content->save($this->data)) {
					// Save an archive copy
					unset($content['Content']['id']);
					$content['Content']['status'] = 'archive';
					$content['Content']['modified'] = date('Y-m-d h:s:i');
					$this->Content->create();
					$this->Content->save($content['Content']);
						$this->queueNotification('Your changes have been saved.', '/admin/content/index/' . $content['Content']['parent_id']);
				}
            } else {
                $this->Content->id = ($id != null ? $id : $this->data['Content']['id']);
                $this->data = $this->Content->read();
            }
            $this->Content->id = $this->data['Content']['id'];
            $this->set('redirects', array('' => 'No redirect') + $this->_listRedirects());
            $this->set('content', $this->Content->read());
			$this->set('breadcrumbs', array('/admin/content' => 'Content') + $this->_fetchBreadcrumbs($id));
			//$this->set('forms', array(0 => 'None') + $this->Form->find('list', array('conditions' => array('Form.active' => 1), 'order' => 'Form.title ASC')));
        }

        function admin_ajaxSave() {
        	$this->Content->id = $_POST['id'];
			$content = $this->Content->read();
            if ($this->Content->saveField('content', $_POST['content'])) {
				// Save an archive copy
				unset($content['Content']['id']);
				$content['Content']['status'] = 'archive';
				$content['Content']['modified'] = date('Y-m-d h:s:i');
				$this->Content->create();
				$this->Content->save($content['Content']);
        		exit('ok');
        	}
        }
		
		function admin_delete($id) {
			$this->Content->id = $id;
			$content = $this->Content->read();
			$this->Content->delete();
			$this->queueNotification(sprintf('The <b>%s</b> content has been deleted.', $content['Content']['name']), '/admin/content/index/' . $content['Content']['parent_id']);
		}

		function admin_move($id) {
			$this->Content->id = $id;
			$content = $this->Content->read();
			if (!empty($this->request->data)) {
				$this->Content->id = $id;
				$this->Content->saveField('parent_id', $this->request->data['Content']['parent_id']);
				$parent = $this->Content->find('first', array('conditions' => array('Content.id' => $this->request->data['Content']['parent_id'])));
				if (!empty($parent))
					$basePath = $parent['Content']['tag'];
				else
					$basePath = '';
				$tag = explode('/', $content['Content']['tag']);
				$tag = array_pop($tag);
				$this->Content->saveField('tag', $basePath . '/' . $tag);
				$this->queueNotification(sprintf('The <b>%s</b> content has been moved.', $content['Content']['name']), '/admin/content/index/' . $this->request->data['Content']['parent_id']);
			} else {
				$this->data = $content;
			}
			$this->set('content', $content);
			$this->set('parents', array(null => 'TOP LEVEL') + $this->_listParents());

			$this->set('breadcrumbs', array('/admin/content' => 'Content') + $this->_fetchBreadcrumbs($id, true) + array('-' => 'Move Page'));
		}
		
		function _fetchBreadcrumbs($id, $linkParent = false) {
			$content = $this->Content->find('first', array('conditions' => array('Content.id' => $id), 'fields' => array('Content.id', 'Content.name', 'Content.parent_id')));
			if (empty($content))
				return array();
			$breadcrumbs = array($linkParent == true ? '/admin/content/index/' . $content['Content']['id'] : '-' => $content['Content']['name']);
			while ($content['Content']['parent_id'] != 0) {
				$content = $this->Content->find('first', array('conditions' => array('Content.id' => $content['Content']['parent_id']), 'fields' => array('Content.id', 'Content.name', 'Content.parent_id')));
				$breadcrumbs['/admin/content/index/' . $content['Content']['id']] = $content['Content']['name'];
			}
			return array_reverse($breadcrumbs);
		}
		
		function _fetchChildNames($id) {
			$children = $this->Content->find('list', array('Content.parent_id' => $id));
			if (empty($children))
				return;
			return $children;
		}
		
		function admin_navigation() {
			if (!empty($this->request->data)) {
				$contents = $this->Content->find('all', array('conditions' => array('Content.status' => 'live'), 'fields' => array('Content.id', 'Content.name', 'Content.tag')));
				$temp = array();
				foreach ($contents as $content)
					$temp[$content['Content']['id']] = array('name' => $content['Content']['name'], 'tag' => $content['Content']['tag']);
				$content = $temp;
				$json = json_decode($this->request->data['navigation'], true);
				$navigation = $this->_buildNavigation($json, $content);
				$this->Config->saveValue('site.navigation', serialize($navigation));
				$this->Config->saveValue('site.navigation.mode', $this->request->data['mode']);
				$this->queueNotification('Your changes have been saved.', '/admin/content/navigation');
				
			}
			$this->set('content', $this->_treeContent());
			$this->set('navigation', unserialize($this->config['site.navigation']));
			$this->set('section', 'navigation');
		}
		
		function _buildNavigation($elements, $content) {
			if (empty($elements))
				return;
			$navigation = array();
			foreach ($elements as $element) {
				$data = $content[$element['id']];
				$navigation[$element['id']] = array('name' => $data['name'], 'tag' => $data['tag']);
				if (isset($element['children']))
					$navigation[$element['id']]['children'] = $this->_buildNavigation($element['children'], $content);
			}
			return $navigation;
		}
			
		function _treeContent($parentId = 0, $depth = 0) {
			$tree = array();
			$contents = $this->Content->find('all', array('conditions' => array('Content.parent_id' => $parentId, 'Content.status' => 'live'), 'fields' => array('Content.id', 'Content.name', 'Content.parent_id')));
			foreach ($contents as $content) {
				$tree[$content['Content']['id']] = array('name' => $content['Content']['name']);
				$t = $this->_treeContent($content['Content']['id'], $depth + 1);
				if (!empty($t))
					$tree[$content['Content']['id']]['children'] = $t;
			}
			return $tree;
		}

        function admin_revert($id, $revisionId = null) {
            if (isset($revisionId)) {
                $this->Content->id = $id;
                $this->Content->delete();
                $this->Content->id = $revisionId;
                $this->Content->saveField('status', 'live');
                $content = $this->Content->read();
                $this->queueNotification('Your content has been reverted.', '/admin/content/view/' . $content['Content']['category_id']);
            } else {
                $content = $this->Content->find('first', array('conditions' => array('Content.id' => $id)));
                $this->set('liveContent', $content);
                $this->set('content', $this->Content->find('all', array('conditions' => array('Content.tag' => $content['Content']['tag'], 'Content.status' => 'archive'), 'order' => 'Content.modified DESC')));
            }
        }

        function admin_viewRevision($id) {
            $this->layout = 'admin_lightbox';
            $this->set('content', $this->Content->find('first', array('conditions' => array('Content.id' => $id))));
        }
		
		public function video() {
			$this->layout = 'shadowbox';
		}

        function ajax_files() {
            $this->layout = 'empty';
        }
		
        function ajax_listFiles() {
			$_POST['dir'] = urldecode($_POST['dir']);
			
			if ($_POST['dir'] == '/')
				$directory = array('CmsDirectory' => array('id' => 0));
			else
				$directory = $this->CmsDirectory->find('first', array('conditions' => array('CmsDirectory.full_path' => substr($_POST['dir'], 0, strlen($_POST['dir']) - 1))));
			
			$directories = $this->CmsDirectory->find('all', array('order' => 'CmsDirectory.directory ASC', 'conditions' => array('CmsDirectory.directory_id' => $directory['CmsDirectory']['id'])));
			$files = $this->CmsFile->find('all', array('order' => 'CmsFile.file ASC', 'conditions' => array('CmsFile.directory_id' => $directory['CmsDirectory']['id'])));
			
			echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
			foreach ($directories as $directory)
				echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . $directory['CmsDirectory']['full_path'] . "/\">" . htmlentities($directory['CmsDirectory']['directory']) . "</a></li>";
			foreach ($files as $file) {
				$ext = preg_replace('/^.*\./', '', $file['CmsFile']['file']);
				echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . $file['CmsFile']['full_path'] . "\">" . $file['CmsFile']['file'] . "</a></li>";
			}
			echo "</ul>";
			exit();
        }
		
        function ajax_updateFiles() {
			if ($this->Auth->user('id') == null)
                exit();
            
            if (empty($_POST['action']))
                exit();
                        
            if ($_POST['action'] == 'new_folder') {
				$directory = $this->CmsDirectory->find('first', array('conditions' => array('CmsDirectory.full_path' => $_POST['path'] . (!empty($_POST['path']) ? '/' : '') . $_POST['name'])));
                if (!empty($directory))
                    exit('error:exists');
				
				$directory = $this->CmsDirectory->find('first', array('conditions' => array('CmsDirectory.full_path' => $_POST['path'])));
				if (empty($directory))
					$directory['CmsDirectory']['id'] = 0;
				if ($this->CmsDirectory->save(array(
					'directory_id' => $directory['CmsDirectory']['id'],
					'directory' => $_POST['name'],
					'full_path' => $_POST['path'] . (!empty($_POST['path']) ? '/' : '') . $_POST['name']
				)))
                    echo 'OK|' . $_POST['name'];
                else
                    echo 'error';
                
            } else if ($_POST['action'] == 'delete_folder') {
				$directory = $this->CmsDirectory->find('first', array('conditions' => array('CmsDirectory.full_path' => $_POST['path'])));
				if (empty($directory))
					exit('error');
					
				$this->_deleteDirectory($directory['CmsDirectory']['id']);
				exit('OK');
			
            } else if ($_POST['action'] == 'delete_file') {
                $file = $this->CmsFile->find('first', array('conditions' => array('CmsFile.full_path' => $_POST['file'])));

                if (unlink($_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $file['CmsFile']['id'] . '_' . $file['CmsFile']['file'])) {
                    $this->CmsFile->id = $file['CmsFile']['id'];
					$this->CmsFile->delete();
					echo 'OK';
                } else {
                    echo 'error';
                }
            }
            exit();
        }
		
		function _deleteDirectory($id) {
			// Find subdirectories
			$subdirectories = $this->CmsDirectory->find('all', array('conditions' => array('CmsDirectory.directory_id' => $id)));
			foreach ($subdirectories as $directory)
				$this->_deleteDirectory($directory['CmsDirectory']['id']);
				
			// Find files in directory
			$files = $this->CmsFile->find('all', array('conditions' => array('CmsFile.directory_id' => $id)));
			foreach ($files as $file) {
				unlink($_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $file['CmsFile']['id'] . '_' . $file['CmsFile']['file']);
				$this->CmsFile->id = $file['CmsFile']['id'];
				$this->CmsFile->delete();
			}
			
			$this->CmsDirectory->id = $id;
			$this->CmsDirectory->delete();
		}
		
        function ajax_uploadFile() {
			if (!isset($_POST['currentPath']))
				$_POST['currentPath'] = '';
			
			// Make sure file doesn't already exist
			$file = $this->CmsFile->find('first', array('conditions' => array('CmsFile.full_path' => $_POST['currentPath'] . (!empty($_POST['currentPath']) ? '/' : '') . $_FILES['Filedata']['name'])));
			if (!empty($file))
				exit('error:exists');
			
			// Find directory	
			$directory = $this->CmsDirectory->find('first', array('conditions' => array('CmsDirectory.full_path' => $_POST['currentPath'])));
			if (empty($directory))
				$directory['CmsDirectory']['id'] = 0;
			
			// Save file data to database
			$this->CmsFile->save(array(
				'directory_id' => $directory['CmsDirectory']['id'],
				'file' => $_FILES['Filedata']['name'],
				'full_path' => $_POST['currentPath'] . (!empty($_POST['currentPath']) ? '/' : '') . $_FILES['Filedata']['name']
			));
			
                        $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
$detectedType = exif_imagetype($_FILES['Filedata']['tmp_name']);
$error = !in_array($detectedType, $allowedTypes);

if(!$error) {
			// Save physical file
            if (move_uploaded_file($_FILES['Filedata']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $this->CmsFile->id . '_' . $_FILES['Filedata']['name'])) {
                echo $_POST['currentPath'] . (!empty($_POST['currentPath']) ? '/' : '') . $_FILES['Filedata']['name'];
            } else {
                echo 'Invalid file type.';
            }
}
else
{
    echo "The file type is not allowed.";
}
            exit();
        }
		
		public function file($path) {
			$file = $this->CmsFile->find('first', array('conditions' => array('CmsFile.full_path' => html_entity_decode($path))));
			if (empty($file))
				throw new NotFoundException();
			
			$file = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $file['CmsFile']['id'] . '_' . $file['CmsFile']['file'];
			$finfo = new finfo(FILEINFO_MIME);
			
			header('Content-type: ' . $finfo->file($file));
			echo file_get_contents($file);
			
			exit();
		}

		public function forms($form) {
			$this->render('forms/' . str_replace('-', '_', $form));
		}



        public function admin_viewFormResults($form, $id = null) {
            if ($form == 'classnotes') {
                if (!isset($id)) {
                    $this->set('forms', $this->ClassNotesForm->find('all'));
                    $this->render('FormResults/classnotes');
                } else {
                    $form = $this->ClassNotesForm->find('first', array('conditions' => array('ClassNotesForm.id' => $id)));
                    $this->request->data['ClassNotesForm'] = unserialize($form['ClassNotesForm']['data']);
                    $this->render('FormResults/classnotes_specific');
                }
            } else if ($form == 'openhouse') {
                if (!isset($id)) {
                    $this->set('forms', $this->RegistrationForm->find('all'));
                    $this->render('FormResults/openhouse');
                } else {
                    $form = $this->RegistrationForm->find('first', array('conditions' => array('RegistrationForm.id' => $id)));
                    $this->request->data['RegistrationForm'] = unserialize($form['RegistrationForm']['data']);
                    $this->render('FormResults/openhouse_specific');
                }
            }
        }

        public function admin_manageFormNotifications() {
            if (!empty($this->request->data)) {
                $this->Config->saveValue('form_recipients.class_notes', $this->request->data['class_notes']);
                $this->Config->saveValue('form_recipients.registration', $this->request->data['registration']);
                $this->queueNotification('Your changes have been saved.', '/admin/content/manageFormNotifications');
            } else {
                $this->request->data['class_notes'] = $this->config['form_recipients.class_notes'];
                $this->request->data['registration'] = $this->config['form_recipients.registration'];
            }
        }

		
		
		
		
		
        public function admin_features() {
            $this->set('features', $this->Feature->find('all', array('order' => 'Feature.order_id ASC')));
        }

        public function admin_addFeature() {
            if ($this->request->is('post')) {
                        $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
$detectedType = exif_imagetype($_FILES['Filedata']['tmp_name']);
$error = !in_array($detectedType, $allowedTypes);

if(!$error) {
                if (move_uploaded_file($this->request->data['Feature']['image']['tmp_name'], WWW_ROOT . 'images/content/features/' . $this->request->data['Feature']['image']['name'])) {
                    $highestFeature = $this->Feature->find('all', array('limit' => 1, 'order' => 'Feature.order_id DESC'));
                    $this->request->data['Feature']['order_id'] = $highestFeature[0]['Feature']['order_id'] + 1;
                    $this->request->data['Feature']['image'] = $this->data['Feature']['image']['name'];
                    if ($this->Feature->save($this->data['Feature'])) {
                        $this->queueNotification('Your new feature photo has been saved.', '/admin/content/features');
                        $this->redirect('/admin/content/features');
                    } else {
                        exit('Error saving data');
                    }
                } else {
                    exit("Error saving file");
                }
}
else {
    exit("Error saving file");
}
            }
        }
        public function admin_removeFeature($id) {
            $this->Feature->id = $id;
            $this->Feature->delete();
            // Reorder all features
            $features = $this->Feature->find('all', array('order' => 'order_id ASC'));
            $i = 1;
            foreach ($features as $feature) {
                $this->Feature->id = $feature['Feature']['id'];
                $this->Feature->saveField('order_id', $i);
                $i++;
            }
            $this->queueNotification('The feature photo you specified has been removed.', '/admin/content/features');
            $this->redirect('/admin/content/features');
        }
        public function admin_moveFeatureUp($id) {
            $feature = $this->Feature->find('first', array('conditions' => array('Feature.id' => $id)));
            if ($feature['Feature']['order_id'] == 1)
                exit("Cannot move up.");
            $feature2 = $this->Feature->find('first', array('conditions' => array('Feature.order_id' => $feature['Feature']['order_id'] - 1)));
            $oldOrderId = $feature['Feature']['order_id'];
            $newOrderId = $feature2['Feature']['order_id'];

            $this->Feature->id = $feature['Feature']['id'];
            $this->Feature->saveField('order_id', $newOrderId);

            $this->Feature->id = $feature2['Feature']['id'];
            $this->Feature->saveField('order_id', $oldOrderId);

            $this->redirect('/admin/content/features');
        }
        public function admin_moveFeatureDown($id) {
            $feature = $this->Feature->find('first', array('conditions' => array('Feature.id' => $id)));
            $highestFeature = $this->Feature->find('all', array('limit' => 1, 'order' => 'Feature.order_id DESC'));
            if ($feature['Feature']['order_id'] == $highestFeature[0]['Feature']['order_id'])
                exit("Cannot move down.");
            $feature2 = $this->Feature->find('first', array('conditions' => array('Feature.order_id' => $feature['Feature']['order_id'] + 1)));
            $oldOrderId = $feature['Feature']['order_id'];
            $newOrderId = $feature2['Feature']['order_id'];

            $this->Feature->id = $feature['Feature']['id'];
            $this->Feature->saveField('order_id', $newOrderId);

            $this->Feature->id = $feature2['Feature']['id'];
            $this->Feature->saveField('order_id', $oldOrderId);

            $this->redirect('/admin/content/features');
        }

        public function event($id) {
			$event = $this->Event->find('first', array('conditions' => array('Event.id' => $id)));

			if (empty($event)) {
				exit('Invalid event.');
			}

			header("Content-Type: text/Calendar");
			header("Content-Disposition: inline; filename=CQL-Event-" . $event['Event']['id'] . ".ics");
			
			echo 'BEGIN:VCALENDAR' . "\n";
			echo 'VERSION:1.0' . "\n";
			echo 'BEGIN:VEVENT' . "\n";
			if ($event['Event']['all_day'] == 1) {
				echo 'DTSTART;VALUE=DATE:' . date('Ymd', strtotime($event['Event']['start_date'])) . "\n";
				echo 'DTEND;VALUE=DATE:' . date('Ymd', strtotime($event['Event']['end_date']) + 60) . "\n";
			} else {
				echo 'DTSTART:' . date('Ymd\TGis', strtotime($event['Event']['start_date'])) . "\n";
				echo 'DTEND:' . date('Ymd\TGis', strtotime($event['Event']['end_date'])) . "\n";
			}
			echo 'DESCRIPTION:' . trim(strip_tags(str_replace("\r\n", '\n', str_replace('<br>', ' ', str_replace('&nbsp;', ' ', $event['Event']['description']))))) . "\n";
			echo 'SUMMARY:' . $event['Event']['title'] . "\n";
			echo 'PRIORITY:3' . "\n";
			echo 'END:VEVENT' . "\n";
			echo 'END:VCALENDAR';
			exit();
        }

    }

?>
