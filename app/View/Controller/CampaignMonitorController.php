<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');
   
    
    class CampaignMonitorController extends AppController {
        public $clientId = null;
        public function admin_refreshApiInfo($key = null)
        {
            
            $clients = $this->_getClients();
             
            $this->clientId = $clients[0]->ClientID;
           
            
           $this->loadModel('Customer');
           $customers = $this->Customer->find('list', array(
                'conditions' => array(
                    'NOT' => array(
                        'email is null',
                        'email' => ''
                    )
                ),
                'limit' => 10,
                'fields' => array(
                    'id', 'email'
                )
            ));
           
           $tempSaved = array();
            foreach($customers as $id => $email)
            {
                $lists = $this->_getLists($email);
                
                if(!empty($lists)) {
                    foreach($lists as $list) {
                $tempSaved[] = array(
                    'customer_id' => $id,
                    'customer_email' => $email,
                    'list_id' => $list->ListID,
                    'list_name' => $list->ListName,
                    'subscriber_state' => $list->SubscriberState,
                    'date_added' => $list->DateSubscriberAdded
                );
                    }
                }
                
            }
            
            foreach($tempSaved as $entry)
            {
                $history = $this->_getHistory($entry['customer_email'], $entry['list_id']);
                pr($history);
            }
            
            
        }
        
        private function _getHistory($customerEmail, $listId)
        {
            require_once 'plugins/campaignmonitor/csrest_subscribers.php';

            $auth = array('api_key' => $this->config['campaign_monitor.api_key']);
            $wrap = new CS_REST_Subscribers($listId,$auth);

            $result = $wrap->get_history($customerEmail);
            return $result->response;
        }
        
        private function _getClients() {
           
            require_once 'plugins/campaignmonitor/csrest_general.php';

            $auth = array('api_key' => $this->config['campaign_monitor.api_key']);
            $wrap = new CS_REST_General($auth);

            $result = $wrap->get_clients();
            return $result->response;
            
           
        }
        
        private function _getLists($email_address) {
            require_once 'plugins/campaignmonitor/csrest_clients.php';

            $auth = array('api_key' => $this->config['campaign_monitor.api_key']);
            $wrap = new CS_REST_Clients($this->clientId,$auth);

            $result = $wrap->get_lists_for_email($email_address);
            return $result->response;
        }
    }