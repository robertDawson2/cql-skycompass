<?php

    App::uses('AppController', 'Controller');

    class NewsController extends AppController {

        public $name = 'News';
        public $uses = array('News', 'Content');
        public $components = array('RequestHandler');
        public $helpers = array('Js', 'Text');

        public function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow('index', 'view');
            $this->set('section', 'news');
        }

        public function beforeRender() {
            parent::beforeRender();
        }

        public function index() {
            if ($this->RequestHandler->isRss() ) {
                $this->set('news', $this->News->find('all', array('limit' => 20, 'order' => 'News.stickied DESC, News.order_id ASC, News.begin_showing ASC', 'conditions' => array(
                    array('OR' => array(
                        array('News.begin_showing' => '0000-00-00 00:00:00'),
                        array('News.begin_showing <=' => date('Y-m-d H:i:s'))
                    )),
                    array('OR' => array(
                        array('News.stop_showing' => '0000-00-00 00:00:00'),
                        array('News.stop_showing >=' => date('Y-m-d H:i:s'))
                    ))
                ))));
            } else {
                $this->set('news', $this->News->find('all', array('order' => 'News.stickied DESC, News.order_id ASC, News.begin_showing ASC', 'conditions' => array(
                    array('OR' => array(
                        array('News.begin_showing' => '0000-00-00 00:00:00'),
                        array('News.begin_showing <=' => date('Y-m-d H:i:s'))
                    )),
                    array('OR' => array(
                        array('News.stop_showing' => '0000-00-00 00:00:00'),
                        array('News.stop_showing >=' => date('Y-m-d H:i:s'))
                    ))
                ))));
            }
        }

        public function view($id) {
            $newsItem = $this->News->find('first', array('conditions' => array('News.id' => $id)));
            if (empty($newsItem))
                throw new NotFoundException('Page Not Found');
            $this->set('newsItem', $newsItem);

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
        }

        public function admin_index() {
            $this->set('news', $this->News->find('all'));
        }

        public function admin_create() {
            if (!empty($this->request->data)) {
                if (empty($this->request->data['News']['begin_showing']))
                    $this->request->data['News']['begin_showing'] = '0000-00-00 00:00:00';
                if (empty($this->request->data['News']['stop_showing']))
                    $this->request->data['News']['stop_showing'] = '0000-00-00 00:00:00';
                
                if (empty($this->request->data['News']['begin_showing']))
                    $this->request->data['News']['begin_showing'] = '0000-00-00 00:00:00';
                else
                    $this->request->data['News']['begin_showing'] = date('Y-m-d H:i:s', strtotime($this->request->data['News']['begin_showing']));

                if (empty($this->request->data['News']['stop_showing']))
                    $this->request->data['News']['stop_showing'] = '0000-00-00 00:00:00';
                else
                    $this->request->data['News']['stop_showing'] = date('Y-m-d H:i:s', strtotime($this->request->data['News']['stop_showing']));            

                $this->News->save($this->request->data['News']);
                $this->queueNotification('Your new news item has been saved.', '/admin/news');
            }
        }

        function admin_edit($id = null) {
            if (!empty($this->request->data)) {
                
                if (empty($this->request->data['News']['begin_showing']))
                    $this->request->data['News']['begin_showing'] = '0000-00-00 00:00:00';
                else
                    $this->request->data['News']['begin_showing'] = date('Y-m-d H:i:s', strtotime($this->request->data['News']['begin_showing']));

                if (empty($this->request->data['News']['stop_showing']))
                    $this->request->data['News']['stop_showing'] = '0000-00-00 00:00:00';
                else
                    $this->request->data['News']['stop_showing'] = date('Y-m-d H:i:s', strtotime($this->request->data['News']['stop_showing']));

                $this->News->save($this->request->data['News']);
                    $this->queueNotification('Your changes have been saved.', '/admin/news');
            } else {
                $this->News->id = ($id != null ? $id : $this->data['News']['id']);
                $this->request->data = $this->News->read();

                if ($this->request->data['News']['begin_showing'] == '0000-00-00 00:00:00')
                    $this->request->data['News']['begin_showing'] = '';
                else
                    $this->request->data['News']['begin_showing'] = date('n/j/Y g:i A', strtotime($this->request->data['News']['begin_showing']));

                if ($this->request->data['News']['stop_showing'] == '0000-00-00 00:00:00')
                    $this->request->data['News']['stop_showing'] = '';
                else
                    $this->request->data['News']['stop_showing'] = date('n/j/Y g:i A', strtotime($this->request->data['News']['stop_showing']));
            }
        }
        
        function admin_delete($id) {
            $this->News->id = $id;
            $news = $this->News->read();
            $this->News->delete();
            $this->queueNotification(sprintf('The <b>%s</b> news item has been deleted.', $news['News']['headline']), '/admin/news');
        }

    }

?>