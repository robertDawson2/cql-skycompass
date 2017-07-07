<?php

    App::uses('AppController', 'Controller');

    class EventsController extends AppController {

        public $name = 'Events';
        public $uses = array('Event', 'Content');
        public $helpers = array('Js');

        public function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow('index');
            $this->set('section', 'web');
        }

        public function beforeRender() {
            parent::beforeRender();
        }

        public function index() {
            $this->set('events', $this->Event->find('all', array('order' => 'Event.stickied DESC, Event.order_id ASC, Event.start_date ASC', 'conditions' => array(
                array('OR' => array(
                    array('Event.begin_showing' => '0000-00-00 00:00:00'),
                    array('Event.begin_showing <=' => date('Y-m-d H:i:s'))
                )),
                array('OR' => array(
                    array('Event.end_date' => '0000-00-00 00:00:00'),
                    array('Event.end_date >=' => date('Y-m-d H:i:s'))
                ))
            ))));
        }

        public function admin_index() {
            $this->set('events', $this->Event->find('all'));
            
        }

        public function admin_create() {
            if (!empty($this->request->data)) {
                if (empty($this->request->data['Event']['start_date']))
                    $this->request->data['Event']['start_date'] = '0000-00-00 00:00:00';
                else
                    $this->request->data['Event']['start_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['Event']['start_date']));
                
                if (empty($this->request->data['Event']['begin_showing']))
                    $this->request->data['Event']['begin_showing'] = '0000-00-00 00:00:00';
                else
                    $this->request->data['Event']['begin_showing'] = date('Y-m-d H:i:s', strtotime($this->request->data['Event']['begin_showing']));

                if (empty($this->request->data['Event']['end_date']))
                    $this->request->data['Event']['end_date'] = '0000-00-00 00:00:00';
                else
                    $this->request->data['Event']['end_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['Event']['end_date']));            

                $this->Event->save($this->request->data['Event']);
                $this->queueNotification('Your new event has been saved.', '/admin/events');
            }
        }

        function admin_edit($id = null) {
            if (!empty($this->request->data)) {                
                if (empty($this->request->data['Event']['start_date']))
                    $this->request->data['Event']['start_date'] = '0000-00-00 00:00:00';
                else
                    $this->request->data['Event']['start_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['Event']['start_date']));
                
                if (empty($this->request->data['Event']['begin_showing']))
                    $this->request->data['Event']['begin_showing'] = '0000-00-00 00:00:00';
                else
                    $this->request->data['Event']['begin_showing'] = date('Y-m-d H:i:s', strtotime($this->request->data['Event']['begin_showing']));

                if (empty($this->request->data['Event']['end_date']))
                    $this->request->data['Event']['end_date'] = '0000-00-00 00:00:00';
                else
                    $this->request->data['Event']['end_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['Event']['end_date']));

                $this->Event->save($this->request->data['Event']);
                    $this->queueNotification('Your changes have been saved.', '/admin/events');
            } else {
                $this->Event->id = ($id != null ? $id : $this->data['Event']['id']);
                $this->request->data = $this->Event->read();

                if ($this->request->data['Event']['start_date'] == '0000-00-00 00:00:00')
                    $this->request->data['Event']['start_date'] = '';
                else
                    $this->request->data['Event']['start_date'] = date('n/j/Y g:i A', strtotime($this->request->data['Event']['start_date']));

                if ($this->request->data['Event']['end_date'] == '0000-00-00 00:00:00')
                    $this->request->data['Event']['end_date'] = '';
                else
                    $this->request->data['Event']['end_date'] = date('n/j/Y g:i A', strtotime($this->request->data['Event']['end_date']));

                if ($this->request->data['Event']['begin_showing'] == '0000-00-00 00:00:00')
                    $this->request->data['Event']['begin_showing'] = '';
                else
                    $this->request->data['Event']['begin_showing'] = date('n/j/Y g:i A', strtotime($this->request->data['Event']['begin_showing']));
            }
        }
        
        function admin_delete($id) {
            $this->Event->id = $id;
            $event = $this->Event->read();
            $this->Event->delete();
            $this->queueNotification(sprintf('The <b>%s</b> event has been deleted.', $event['Event']['title']), '/admin/events');
        }

    }

?>