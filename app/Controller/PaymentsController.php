<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    App::uses('AppController', 'Controller');
    
   
            
          
    class PaymentsController extends AppController {
        
            
        function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow();
        }
        
        function index() {

           // App::import('Vendor', 'authNet/autoload');
            if(!empty($this->request->data)) {
                $data = $this->request->data;
                $error = false;
                // break down and error check
                $data['card_num'] = trim(str_replace("-", "", str_replace(" ", "", $data['card_num'])));
                
                $data['amount'] = trim(str_replace("$", "", $data['amount']));
                $data['company'] = $data['company_name'];
                unset($data['company_name']);
                if(strlen($data['card_num'])<13 || strlen($data['card_num']) > 16 || 
                        !is_numeric($data['card_num']) || !is_numeric($data['card_code']) ||
                        !is_numeric($data['amount']))
                    $error = true;
                
               // recaptcha integration
		$url = "https://www.google.com/recaptcha/api/siteverify";
		$data1 = array('secret' => '6LfsZR0UAAAAAP97xsWlidgV8VVY3CN0eQI0pjLS',
				'response' => $this->request->data['g-recaptcha-response'],
				'remoteip' => $_SERVER['REMOTE_ADDR']);
		
		$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data1)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$result = json_decode($result, true);
if(!$result['success'])
	$error = true;

unset($data['g-recaptcha-response']);

                $data['exp_date'] = $data['exp_month'] . $data['exp_year'];
                
                unset($data['exp_month']);
                unset($data['exp_year']);
                if(!$error)
                {
            App::import('Vendor', 'AuthNet/AuthorizeNet');
            
  // SANDBOX
  //    $sale = new AuthorizeNetAIM("782dKrnWPR", "595b6Mu7ah4Z7nGW");
            
  // LIVE
        $sale = new AuthorizeNetAIM("5Zuc276SGb6", "77rDE64c86Qu9Qj4");
  
        $sale->setSandbox(false);
  $sale->setFields(
      $data 
  );
  $response = $sale->authorizeAndCapture();
  //pr($response);
  $data['response'] = $response;
  if ($response->approved) {
      $this->Session->setFlash("Your payment was successful!", 'flash_success');
      $this->_logPayment($data); 
      $this->_sendReceipt($data); 
      $this->redirect('/payments');
  } else {
       $this->Session->setFlash($response->response_reason_text, 'flash_error');
  }

                }
                else
                {
                    $this->Session->setFlash('An error has occurred with your input. Please check all inputted boxes and try again.', 'flash_error');
                }
            
            }
        }
        
        private function _logPayment($data)
        {
            $this->Payment->create();
            $this->Payment->save(array('data' => print_r($data, true)));
            return true;
        }
        
        private function _sendReceipt($data)
        {
            App::uses('CakeEmail', 'Network/Email');
        
                            $to = array($data['email']);
                            
                            $email = new CakeEmail('smtp');
                            $email->template('payment_receipt', 'default')
                            ->emailFormat('html')
                            ->subject($this->config['site.name'] . ' Receipt')
                            ->viewVars(array('data' => $data,'config' => $this->config, 'description' => 'Payment Receipt'))
                            ->to($to)
                            ->send();
                            
                            return true;
        }
    }
