<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    App::uses('AppController', 'Controller');
   
    
    class CustomersController extends AppController {

    	public $name = 'Customer';
        public $uses = array('Customer', 'CustomerAddress', 'Company');
        
        public function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow('import');
            
        }
        
        public function admin_ajaxGetCustomerList() {
            $this->layout = 'ajax';
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
             echo json_encode($returnArray);
                exit();
        }
        public function beforeRender() {
            parent::beforeRender();
            $this->set('section', 'customers');
        }
        
        public function admin_index() {
            
            $this->set('customers', $this->Customer->find('all'));
        }
        public function admin_view($id = null) {
            pr($this->Customer->findById($id));
            exit();
        }
        public function admin_edit($id = null) {
            $this->Session->setFlash('This feature has not been added yet!', 'flash_error');
            $this->redirect('/admin/customers');
        }
        public function import($hash = null)
        {
           if($hash != "asdn")
           {
               exit('incorrect hash has been supplied');
           }
           
           $this->redirect('/importQBdata.php');
           
           // Correct hash supplied - begin import of customers
           
           
           

/*
// If you wanted, you could do something with $response here for debugging

$fp = fopen('/path/to/file.log', 'a+');
fwrite($fp, $response);
fclose($fp);
*/

exit(0);
        }
        
   
        
        
    }
    
    
?>
