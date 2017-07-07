<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
App::uses('AppController', 'Controller');

class TaskListTemplatesController extends AppController {
    
    public $uses = array('TaskItem', 'TaskListTemplate', 'TaskListAction', 'TaskListTemplateItem');
    
    function beforeFilter() {
		parent::beforeFilter();
		$this->set('section', 'scheduling');
               
	}
        function admin_delete($id)
        {
            $this->TaskListTemplate->delete($id, true);
            $this->Session->setFlash('The selected template has been removed.', 'flash_success');
            $this->redirect('/admin/taskListTemplates');
        }
        function admin_index() {
            $this->set('templates', $this->TaskListTemplate->find('all'));
        }
        function admin_ajaxLoadCurrentTemplate($id) {
            $this->layout = 'ajax';
            $data = $this->TaskListTemplate->find('first', array('conditions' => array('TaskListTemplate.id' => $id)
                ,
                'recursive' => 2));
            echo json_encode($data);
            exit();
        }
        function admin_edit($id = null) {
       
        if(!empty($this->request->data))
        {
            
            $this->request->data['TaskListTemplate']['items'] = json_decode($this->request->data['TaskListTemplate']['items']);
           
            $data = $this->request->data;
           
            if($this->TaskListTemplate->save($data))
            {
                
                $id = $this->TaskListTemplate->id;
                $this->TaskListTemplateItem->deleteAll(array('TaskListTemplateItem.task_list_template_id' => $id));
                foreach($data['TaskListTemplate']['items'] as $i => $item)
                {
                    $newItem = array('TaskListTemplateItem' => array(
                        'task_list_template_id' => $id,
                        'task_item_id' => $item,
                        'sort_order' => $i
                    ));
                    $this->TaskListTemplateItem->create();
                    $this->TaskListTemplateItem->save($newItem);
                }
                $this->Session->setFlash('Your New Task List Template has been updated!','flash_success');
            }
            else
            {
                $this->Session->setFlash('An error occurred when saving, please try again','flash_error');
            }
            
            $this->redirect('/admin/taskListTemplates');
            
        }
        
        $data = $this->TaskListTemplate->find('first', array('conditions' => array('TaskListTemplate.id' => $id),
            'recursive' => 2));
        $this->data = $data;
        $this->set('data', $data);
        $this->set('items', $this->TaskItem->find('all'));
        $this->set('actions', $this->TaskListAction->find('all', array('order' => 'TaskListAction.id DESC',
            'fields' => array('TaskListAction.id, TaskListAction.name'))));
        
    }
    function admin_create() {
       
        if(!empty($this->request->data))
        {
            $this->request->data['TaskListTemplate']['items'] = json_decode($this->request->data['TaskListTemplate']['items']);
           
            $data = $this->request->data;
            $this->TaskListTemplate->create();
            if($this->TaskListTemplate->save($data))
            {
                $id = $this->TaskListTemplate->id;
                foreach($data['TaskListTemplate']['items'] as $i => $item)
                {
                    $newItem = array('TaskListTemplateItem' => array(
                        'task_list_template_id' => $id,
                        'task_item_id' => $item,
                        'sort_order' => $i
                    ));
                    $this->TaskListTemplateItem->create();
                    $this->TaskListTemplateItem->save($newItem);
                }
                $this->Session->setFlash('Your New Task List Template has been created!','flash_success');
            }
            else
            {
                $this->Session->setFlash('An error occurred when saving, please try again','flash_error');
            }
            
        }
        
        $this->set('items', $this->TaskItem->find('all'));
        $this->set('actions', $this->TaskListAction->find('all', array('order' => 'TaskListAction.id DESC',
            'fields' => array('TaskListAction.id, TaskListAction.name'))));
        
    }
    function admin_ajaxRemoveItem($id)
    {
        $this->layout='ajax';
        $this->TaskItem->delete($id);
        exit('ok');
    }
    function admin_addNewItem()
    {
       // pr($this->request->data); exit();
        $this->layout = 'ajax';
        if(!empty($this->request->data))
        {
            $this->TaskItem->create();
            if($this->TaskItem->save($this->request->data))
                exit($this->TaskItem->id);
            else
                exit('error');
        }
        else
        {
            exit('error');
        }
    }
}
