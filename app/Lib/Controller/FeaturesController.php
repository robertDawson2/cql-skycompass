<?php

    App::uses('AppController', 'Controller');

    class FeaturesController extends AppController {

        public $name = 'Features';
        public $uses = array('Feature');
        public $helpers = array('Js');

        public function beforeFilter() {
            parent::beforeFilter();
            $this->set('section', 'web');
        }

        public function admin_sort() {
            $this->set('features', $this->Feature->find('all',array('conditions' => array('Feature.archived' => '1'),
                    'order' => 'order_id ASC'
                )
            ));
        }

        public function admin_reorder() {
            foreach ($this->data['Feature'] as $key => $value) {
                $this->Feature->id = $value;
                $this->Feature->saveField("order_id",$key + 1);
                echo ($value);

            }
            $this->queueNotification('Your order has been saved.', '/admin/features');
            //$this->log(print_r($this->data,true));

        }

        public function admin_index() {
            $this->set('features', $this->Feature->find('all'));
        }

        public function admin_create() {
            if (!empty($this->request->data)) {
                if (empty($this->request->data['Feature']['width']))
                    $this->request->data['Feature']['width'] = 0;
                if (empty($this->request->data['Feature']['height']))
                    $this->request->data['Feature']['height'] = 0;
                if (empty($this->request->data['Feature']['order_id']))
                    $this->request->data['Feature']['order_id'] = 0;
                if (empty($this->request->data['Feature']['begin_showing']))
                    $this->request->data['Feature']['begin_showing'] = '0000-00-00 00:00:00';
                if (empty($this->request->data['Feature']['stop_showing']))
                    $this->request->data['Feature']['stop_showing'] = '0000-00-00 00:00:00';
                
                if (empty($this->request->data['Feature']['begin_showing']))
                    $this->request->data['Feature']['begin_showing'] = '0000-00-00 00:00:00';
                else
                    $this->request->data['Feature']['begin_showing'] = date('Y-m-d H:i:s', strtotime($this->request->data['Feature']['begin_showing']));

                if (empty($this->request->data['Feature']['stop_showing']))
                    $this->request->data['Feature']['stop_showing'] = '0000-00-00 00:00:00';
                else
                    $this->request->data['Feature']['stop_showing'] = date('Y-m-d H:i:s', strtotime($this->request->data['Feature']['stop_showing']));            

                $this->Feature->save($this->request->data['Feature']);
                $this->queueNotification('Your new feature has been saved.', '/admin/features');
            }
        }

        function admin_edit($id = null) {
            if (!empty($this->request->data)) {

                if (empty($this->request->data['Feature']['width']))
                    $this->request->data['Feature']['width'] = 0;
                if (empty($this->request->data['Feature']['height']))
                    $this->request->data['Feature']['height'] = 0;
                if (empty($this->request->data['Feature']['order_id']))
                    $this->request->data['Feature']['order_id'] = 0;
                
                if (empty($this->request->data['Feature']['begin_showing']))
                    $this->request->data['Feature']['begin_showing'] = '0000-00-00 00:00:00';
                else
                    $this->request->data['Feature']['begin_showing'] = date('Y-m-d H:i:s', strtotime($this->request->data['Feature']['begin_showing']));

                if (empty($this->request->data['Feature']['stop_showing']))
                    $this->request->data['Feature']['stop_showing'] = '0000-00-00 00:00:00';
                else
                    $this->request->data['Feature']['stop_showing'] = date('Y-m-d H:i:s', strtotime($this->request->data['Feature']['stop_showing']));

                $this->Feature->save($this->request->data['Feature']);
                    $this->queueNotification('Your changes have been saved.', '/admin/features');
            } else {
                $this->Feature->id = ($id != null ? $id : $this->data['Feature']['id']);
                $this->request->data = $this->Feature->read();

                if ($this->request->data['Feature']['begin_showing'] == '0000-00-00 00:00:00')
                    $this->request->data['Feature']['begin_showing'] = '';
                else
                    $this->request->data['Feature']['begin_showing'] = date('n/j/Y g:i A', strtotime($this->request->data['Feature']['begin_showing']));

                if ($this->request->data['Feature']['stop_showing'] == '0000-00-00 00:00:00')
                    $this->request->data['Feature']['stop_showing'] = '';
                else
                    $this->request->data['Feature']['stop_showing'] = date('n/j/Y g:i A', strtotime($this->request->data['Feature']['stop_showing']));
            }
        }
        
        function admin_delete($id) {
            $this->Feature->id = $id;
            $feature = $this->Feature->read();
            $this->Feature->delete();
            $this->queueNotification(sprintf('The <b>%s</b> feature has been deleted.', $feature['Feature']['name']), '/admin/features');
        }

    }

?>