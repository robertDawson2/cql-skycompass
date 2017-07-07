<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
App::uses('AppController', 'Controller');

class JobTaskListItemsController extends AppController {
    
    
    function admin_ajaxChangeItemStatus($id, $value) {
        $this->layout = 'ajax';
        if($value== "0")
        {
            $this->JobTaskListItem->id = $id;
            $this->JobTaskListItem->saveField('completed', null);
           
        }
        else
        {
             $this->JobTaskListItem->id = $id;
            $this->JobTaskListItem->saveField('completed', date('Y-m-d H:i:s'));
            $this->_handleAlert($id);
            if($this->_getNextAction($id) !== -1)
            {
                $this->_handleAlert($id, true);
            }
        }
        exit(0);
    }
    
    private function _handleAlert($id, $activation = false) 
    {
        $event = $this->JobTaskListItem->findById($id);
        $this->loadModel('JobTaskList');
        $taskList = $this->JobTaskList->findById($event['JobTaskListItem']['job_task_list_id']);
        
        $this->loadModel('TaskListAction');
        $eventActions = $this->TaskListAction->find('list', array('fields' => array('TaskListAction.id', 'TaskListAction.name')));
      
        $action = $activation ? $event['TaskItem']['activate_event'] : $event['TaskItem']['complete_event'];
        
        $action = $eventActions[$action];
        
        switch($action) {
            case 'alert_employees':
                $this->_sendAlerts($taskList['JobTaskList']['job_id'], 'employees', $event['TaskItem']['long_name'], $activation);
                break;
            case 'alert_scheduler':
                $this->_sendAlerts($taskList['JobTaskList']['job_id'], 'scheduler', $event['TaskItem']['long_name'], $activation);
                break;
            case 'email_customer':
                $this->_sendEmail($taskList['JobTaskList']['job_id'], 'customer', $event['TaskItem']['long_name'], $activation);
                break;
            case 'email_employees':
                $this->_sendAlerts($taskList['JobTaskList']['job_id'], 'employees', $event['TaskItem']['long_name'], $activation);
                $this->_sendEmail($taskList['JobTaskList']['job_id'], 'customer', $event['TaskItem']['long_name'], $activation);
                break;
            case 'email_scheduler':
                $this->_sendAlerts($taskList['JobTaskList']['job_id'], 'scheduler', $event['TaskItem']['long_name'], $activation);
                $this->_sendEmail($taskList['JobTaskList']['job_id'], 'scheduler', $event['TaskItem']['long_name'], $activation);
                break;
            
        }
    }
    private function _getNextAction($id)
    {
        // return next tasklistitem id if there is one
        $event = $this->JobTaskListItem->findById($id);
        $this->loadModel('JobTaskList');
        $taskList = $this->JobTaskList->findById($event['JobTaskListItem']['job_task_list_id']);
        
        $lowestStillToComplete = null;
        
        foreach($taskList['JobTaskListItem'] as $item)
        {
            if(!isset($item['completed']))
            {
                if(!isset($lowestStillToComplete))
                {
                    $lowestStillToComplete = $item;
                }
                else
                {
                    if($item['sort_order'] < $lowestStillToComplete['sort_order'])
                        $lowestStillToComplete = $item;
                }
            }
            
        }
        if(isset($lowestStillToComplete))
            return $lowestStillToComplete['id'];
       
        
        // return -1 if there is no next action;
        return -1;
    }
    private function _sendAlerts($jobId, $type, $longName, $activation)
    {
        $terms = $activation ? 'activated' : 'completed';
        $this->loadModel('Job');
        $job = $this->Job->find('first', array('recursive' => 2, 'conditions' => array('Job.id' => $jobId)));
        
        if($type == 'employees') {
        foreach($job['ScheduleEntry'] as $entry)
        {
            $this->Notification->queueNotification($entry['User']['id'], 'TaskItem', '/admin/jobs/dashboard/'.$jobId, 'Item ' . $terms, $longName . " " . $terms . " for job ". 
                    $job['Job']['full_name'], 0);
        }
        }
        else if($type == 'scheduler')
        {
            foreach(explode("|", $this->config['schedulerIds']) as $scheduler) {
            $this->Notification->queueNotification($scheduler, 'TaskItem', '/admin/jobs/dashboard/'.$jobId, 'Item ' . $terms, $longName . " " . $terms . " for job ". 
                    $job['Job']['full_name'], 0);
            }
        }
      
        return true;
    }
    private function _sendEmail($jobId, $type, $longName, $activation)
    {
        $terms = $activation ? 'activated' : 'completed';
        $this->loadModel('Job');
        $job = $this->Job->find('first', array('recursive' => 2, 'conditions' => array('Job.id' => $jobId)));
        App::uses('CakeEmail', 'Network/Email');
        if($type == 'employees') {
        foreach($job['ScheduleEntry'] as $entry)
        {
            
                            $to = array($entry['User']['email']);
                            
                            $email = new CakeEmail('smtp');
                            $email->template('TaskItem', 'default')
                            ->emailFormat('both')
                            ->subject($this->config['site.name'] . ' Task Item Change - Employee Alert')
                            ->viewVars(array('job' => $job,'config' => $this->config, 'longName' => $longName, 'terms' => $terms))
                            ->to($to)
                            ->send();
            
        }
        }
        else if($type == 'scheduler')
        {
            foreach(explode("|", $this->config['schedulerIds']) as $scheduler) {
                $user = $this->User->findById($scheduler);
                $to = array($user['User']['email']);
                            
                            $email = new CakeEmail('smtp');
                            $email->template('TaskItem', 'default')
                            ->emailFormat('both')
                            ->subject($this->config['site.name'] . ' Task Item Change - Scheduler Alert')
                            ->viewVars(array('job' => $job,'config' => $this->config, 'longName' => $longName, 'terms' => $terms))
                            ->to($to)
                            ->send();
            }
        }
        else if($type == 'customer')
        {
            
                if(!empty($job['Customer']['email'])) {
                $to = array($job['Customer']['email']);
                            
                            $email = new CakeEmail('smtp');
                            $email->template('TaskItem', 'default')
                            ->emailFormat('both')
                            ->subject($this->config['site.name'] . ' Event Progress Alert')
                            ->viewVars(array('job' => $job,'config' => $this->config, 'longName' => $longName, 'terms' => $terms))
                            ->to($to)
                            ->send();
                }
            
        }
      
        return true;
    }
}
