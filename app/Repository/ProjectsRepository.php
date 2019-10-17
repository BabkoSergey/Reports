<?php

namespace App\Repository;

use App\Project;

use App\Repository\TasksRepository;

class ProjectsRepository
{       
    private $project;
    
    private $tasks;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Project $project, TasksRepository $tasks)
    {
        $this->project = $project;
        $this->tasks = $tasks;
    }
    
    public function all($disabled = null)
    {
        return $disabled != null ? $this->project->where('status', $disabled)->get() : $this->project->all();
    }
        
    public function find($id)
    {
        $project = $this->project->find($id);
                    
        return $project;
    }
        
    public function create($data)
    {        
        
        $project = $this->project->create($data);
        
        return $project; 
    }
    
    public function update($projectOrID, $data)
    {
        $project = $this->_getModelObj($projectOrID);
        
        if(!$project)
            return null;
        
        $project->update($data);
        
        return $project;
    }
    
    public function delete($projectOrID)
    {
        $project = $this->_getModelObj($projectOrID);
        
        if(!$project)
            return false;
        
        $tasks = $this->tasks->all('project', $project);
        foreach($tasks as $task){
            $this->tasks->delete($task);
        }
        
        $project->delete();
        
        return true;
    }
    
    private function _getModelObj($IdOrModel, $isModel = null)
    {
        $model = $isModel ?? $this->project;
        
        if ($IdOrModel instanceof $model){
            $modelObj = $IdOrModel;
        }else{
            $modelObj = $model->find($IdOrModel);                
        }
        
        return $modelObj;
    }
         
}
