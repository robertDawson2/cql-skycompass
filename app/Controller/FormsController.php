<?php

    App::uses('AppController', 'Controller');

    class FormsController extends AppController {

        public $name = 'Forms';
        public $uses = array('Form', 'FormResult', 'Content', 'RFEForm');

        public function beforeFilter() {
            parent::beforeFilter();
            $this->set('section', 'forms');
            $this->Auth->allow('requestForEngagement');
        }

        public function beforeRender() {
            parent::beforeRender();
        }

        public function admin_index() {
            $this->set('forms', $this->Form->find('all', array('order' => 'Form.title ASC')));
        }

        public function admin_results($formId) {
            $form = $this->Form->find('first', array('conditions' => array('Form.id' => $formId)));
            $this->set('form', $form);
            $this->set('results', $this->FormResult->find('all', array('conditions' => array(
                array('AND' => array(
                    'FormResult.form' => $form['Form']['title'],
                    'FormResult.archived = "0"'))
            ),
                'order' => 'FormResult.created DESC')));
        }

        public function admin_archive($formId) {
            $form = $this->Form->find('first', array('conditions' => array('Form.id' => $formId)));
            $this->FormResult->updateAll(
                array('FormResult.archived' => 1),
                array('FormResult.form' => $form['Form']['title']));

                $msg = "All '";
                $msg.= $form['Form']['title'];
                $msg.= "' forms have been removed.";

                $this->queueNotification($msg, "/admin/forms/");



        }
        public function admin_export($formId) {
            
            $form = $this->Form->find('first', array('conditions' => array('Form.id' => $formId)));
            $results = $this->FormResult->find('all', array('conditions' => array(
                    array('AND' => array(
                        'FormResult.form' => $form['Form']['title'],
                        'FormResult.archived = "0"'))
                ),
                'order' => 'FormResult.created DESC'));
            if (!empty($results)) {

                $data = array();
                
                // Build header row
                $row = array();
                $row[] = "Date/Time";
                $result = unserialize($results[0]['FormResult']['data']);

                foreach ($result as $key => $value) {
                    if (is_array($value)) {
                        foreach ($value as $k => $v)
                            $row[] = Inflector::humanize($key) . ' - ' . Inflector::humanize($k);
                    } else {
                        $row[] = Inflector::humanize($key);
                    }
                }
                $data[] = $row;

                // Build data rows
                foreach ($results as $result) {
                    $row = array();
                    $row[] = date('M j Y, h:i', strtotime($result['FormResult']['created']));
                    $result = unserialize($result['FormResult']['data']);

                    foreach ($result as $key => $value) {
                        
                        if (is_array($value)) {
                            foreach ($value as $k => $v)
                                $row[] = $v == 1 ? 'Yes' : 'No';
                        } else {
                            $row[] = $value;
                        }

                    }
                    $data[] = $row;
                }
                
                App::import('Vendor', 'PHPExcel');

                $objPHPExcel = new PHPExcel();

                // Add some data
                $objPHPExcel->setActiveSheetIndex(0)
                    ->fromArray($data);

                // Rename worksheet
                $objPHPExcel->getActiveSheet()->setTitle($form['Form']['title']);
                $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A1'), 'B1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1');
                
                foreach (range(0, count($data[0])) as $col) {
                    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);
                }

                // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                $objPHPExcel->setActiveSheetIndex(0);


                
                // Redirect output to a client’s web browser (Excel5)
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $form['Form']['title'] . ' Submissions - ' . date('M j, Y') . '.xls"');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');

                // If you're serving to IE over SSL, then the following may be needed
                header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header ('Pragma: public'); // HTTP/1.0

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');
                exit();
            } else {
                $this->set('form', $form);
            }
        }

        public function admin_viewResult($id) {
            $this->set('result', $this->FormResult->find('first', array('conditions' => array('FormResult.id' => $id))));
        }

        public function requestForEngagement() {
            if (!empty($this->request->data)) {
                $currentValidation = 'step' . $this->request->data['step'] . 'Validation';
                $this->RFEForm->validate = $this->RFEForm->$currentValidation;
                $this->RFEForm->set($this->request->data);
                if ($this->RFEForm->validates()) {
                    $nextStep = $this->request->data['step'] + 1;
                    $this->set('step', $nextStep);
                    if ($nextStep == 10) {
                        // All done
                        $formResults = array(
                            'form_id' => 1,
                            'data' => serialize(unserialize($this->request->data['formData']) + $this->request->data['RFEForm']),
                            'ip_address' => $_SERVER['REMOTE_ADDR']
                        );
                        $form = $this->Form->find('first', array('conditions' => array('Form.title' => 'Request for Engagement Form')));
                        if ($this->FormResult->save($formResults)) {
                            App::uses('CakeEmail', 'Network/Email');
                            $to = array();
                            $addresses = explode(',', $form['Form']['recipients']);
                            foreach ($addresses as $email) {
                                $email = explode('|', $email);
                                if (!isset($email[1]))
                                    $email[1] = $email[0];
                                $to[$email[1]] = $email[0];
                            }
                            $email = new CakeEmail('smtp');
                            $email->template('rfe', 'default')
                            ->emailFormat('both')
                            ->subject('New Request for Engagement Form')
                            ->viewVars(array('title' => 'Request for Engagement Form', 'description' => 'Request for Engagement Form', 'id' => $this->FormResult->id))
                            ->to($to)
                            ->send();
                        } else {
                            exit('There was an error while saving your request. Please go back and try again.');
                        }
                    } else {
                        $nextValidation = 'step' . $nextStep . 'Validation';    
                        $this->RFEForm->validate = $this->RFEForm->$nextValidation;
                    }
                    $this->set('formData', serialize(unserialize($this->request->data['formData']) + $this->request->data['RFEForm']));
                } else {
                    $this->set('formData', $this->request->data['formData']);    
                    $this->set('step', $this->request->data['step']);
                }
            } else {
                $this->RFEForm->validate = $this->RFEForm->step1Validation;
                $this->set('formData', serialize(array()));
            }
        }


    }

?>