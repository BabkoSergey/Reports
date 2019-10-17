<?php

namespace App\Repository;

use App\Task;
use App\Project;
use App\Estimate;

class TasksRepository
{       
    private $task;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }
    
    public function all($type = null , $resourse = false)
    {
        return !$type ? $this->task->all() : ($resourse !== false ? $this->task->where(['type' => $type, 'resourse' => $resourse])->get() : $this->task->where(['type' => $type])->get());
    }
        
    public function find($id)
    {
        $task = $this->task->find($id);
                    
        return $task;
    }
        
    public function create($data)
    {        
        if(!$this->_checkResouce($data))
            return false;
        
        $task = $this->task->create($data);
        
        return $task; 
    }
    
    public function update($taskOrID, $data)
    {
        $task = $this->_getModelObj($taskOrID);
        
        if(!$task)
            return null;
        
        $task->update($data);
        
        return $task;
    }
    
    public function delete($taskOrID)
    {
        $task = $this->_getModelObj($taskOrID);
        
        if(!$task)
            return false;
        
        $task->delete();
        
        return true;
    }
    
    private function _getModelObj($IdOrModel, $isModel = null)
    {
        $model = $isModel ?? $this->task;
        
        if ($IdOrModel instanceof $model){
            $modelObj = $IdOrModel;
        }else{
            $modelObj = $model->find($IdOrModel);                
        }
        
        return $modelObj;
    }
               
    public function getTypes() 
    {         
        return getEnumValues($this->task->getTable(), 'type');
    }
    
    private function _checkResouce($data)
    {
        switch ($data['type']){
            case 'project':
                return Project::find($data['resourse']);
            case 'estimate':
                return Estimate::find($data['resourse']);
            default :
                return true;
        }
    }
}
