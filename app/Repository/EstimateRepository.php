<?php

namespace App\Repository;

use App\Estimate;

use App\Repository\TasksRepository;

class EstimateRepository
{       
    private $estimate;
    
    private $tasks;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Estimate $estimate, TasksRepository $tasks)
    {
        $this->estimate = $estimate;
        $this->tasks = $tasks;
    }
    
    public function all($disabled = null)
    {
        return $disabled !== null ? $this->estimate->where('status', $disabled)->get() : $this->estimate->all();
    }
    
    public function get($params = [])
    {
        return $this->estimate->where($params)->get();
    }
    
    public function find($id)
    {
        $estimate = $this->estimate->find($id);
                    
        return $estimate;
    }
        
    public function create($data)
    {        
        
        $estimate = $this->estimate->create($data);
        
        return $estimate; 
    }
    
    public function update($estimateOrID, $data)
    {
        $estimate = $this->_getModelObj($estimateOrID);
        
        if(!$estimate)
            return null;
        
        $estimate->update($data);
        
        return $estimate;
    }
    
    public function delete($estimateOrID)
    {
        $estimate = $this->_getModelObj($estimateOrID);
        
        if(!$estimate)
            return false;
        
        $tasks = $this->tasks->all('estimate', $estimate);
        foreach($tasks as $task){
            $this->tasks->delete($task);
        }
        
        $estimate->delete();
        
        return true;
    }
    
    public function getTypes() 
    {         
                
        return getEnumValues($this->estimate->getTable(), 'view');
    }
    
    private function _getModelObj($IdOrModel, $isModel = null)
    {
        $model = $isModel ?? $this->estimate;
        
        if ($IdOrModel instanceof $model){
            $modelObj = $IdOrModel;
        }else{
            $modelObj = $model->find($IdOrModel);                
        }
        
        return $modelObj;
    }
         
}
