<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');
   
    
    class CampaignMonitorController extends AppController {
        public $clientId = null;
        
        function beforeRender() {
            parent::beforeRender();
            $this->set('section', 'communications');
        }
        function admin_index() {
            
        }
        function admin_ajaxGetCampaigns()
        {
            $clients = $this->_getClients();
             
            $this->clientId = $clients[0]->ClientID;
            
            $campaigns = $this->_getCampaigns();
            $returnArray = array('data' => array());
//            pr($campaigns[0]);
//            pr($this->_getCampaignDetails($campaigns[0]->CampaignID)); exit();
            
            foreach($campaigns as $index => $campaign) {
                
                if($index>24)
                {
                    echo json_encode($returnArray);
            exit();
            }
                $newRecord = array();
                $newRecord['Name'] = "<a href='" . $campaign->WebVersionURL . "' class='fancyframe'>" .
                        $campaign->Name . "</a>";
                $newRecord['SentDate'] = date('m/d/y', strtotime($campaign->SentDate));
                $newRecord['TotalRecipients'] = $campaign->TotalRecipients;
                
                $results = $this->_getCampaignDetails($campaign->CampaignID);
                $percentage = ($results->UniqueOpened / $newRecord['TotalRecipients']) * 100;
                $percentage = number_format($percentage, 2);
                $newRecord['Opens'] = "" . $results->UniqueOpened . " (" . $percentage . "%)";
                
                $percentage = ($results->Clicks / $newRecord['TotalRecipients']) * 100;
                $percentage = number_format($percentage, 2);
                $newRecord['Clicks'] = "" . $results->Clicks . " (" . $percentage . "%)";
                
                $percentage = ($results->Bounced / $newRecord['TotalRecipients']) * 100;
                $percentage = number_format($percentage, 2);
                $newRecord['Bounces'] = "" . $results->Bounced . " (" . $percentage . "%)";
                
                $percentage = ($results->Unsubscribed / $newRecord['TotalRecipients']) * 100;
                $percentage = number_format($percentage, 2);
                $newRecord['Unsubscribes'] = "" . $results->Unsubscribed . " (" . $percentage . "%)";
                
                $percentage = ($results->SpamComplaints / $newRecord['TotalRecipients']) * 100;
                $percentage = number_format($percentage, 2);
                $newRecord['Spam'] = "" . $results->SpamComplaints . " (" . $percentage . "%)";
                
                $returnArray['data'][] = $newRecord;
            }
           echo json_encode($returnArray);
            exit();
        }
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
        private function _getCampaignDetails($campaignId)
        {
         require_once 'plugins/campaignmonitor/csrest_campaigns.php';

            $auth = array('api_key' => $this->config['campaign_monitor.api_key']);
            $wrap = new CS_REST_Campaigns($campaignId, $auth);

            $result = $wrap->get_summary();
            return $result->response;   
        }
        
        private function _getCampaigns() {
           
            require_once 'plugins/campaignmonitor/csrest_clients.php';

            $auth = array('api_key' => $this->config['campaign_monitor.api_key']);
            $wrap = new CS_REST_Clients($this->clientId, $auth);

            $result = $wrap->get_campaigns();
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