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
        
        public function ajaxReturnAddress($id) {
            $this->layout = 'ajax';
            $cust = $this->Customer->findById($id);
            if(!empty($cust['Customer']['bill_addr2']))
                echo json_encode($cust['Customer']);
            else
                echo "false";
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
            $customer = $this->Customer->find('first', array('conditions'=>array('Customer.id' => $id),
                'recursive' => 0));
            $this->Job->unbindModel(array('belongsTo' => array('Customer')));
            $jobs = $this->Job->find('all', array('conditions'=> array('Job.customer_id' => $id), 
                'recursive' => 3, 'order' => 'Job.start_date DESC', 'limit' => 10));
            $this->set('jobs', $jobs);
            $this->set('customer', $customer);
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
