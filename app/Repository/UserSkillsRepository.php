<?php

namespace App\Repository;

use App\User;
use App\UserSkill;

class UserSkillsRepository
{       
    private $skill;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user, UserSkill $skill)
    {
        $this->user = $user;
        $this->skill = $skill;
    }
                
    public function find($id)
    {        
        return $this->skill->find($id);
    }
    
    public function findByUser($userOrID)
    {
        $user = $this->_getModelObj($userOrID, $this->user);
        
        if(!$user)
            return null;
                
        return $this->skill->where('user_id', $user->id)->get();
    }
        
    public function getSkillsList()
    {                
        return $this->skill->all()->groupBy('cat')
                ->map(function ($skills, $key) {                    
                    return$skills->unique('name')->pluck('name', 'name');
                })
                ->toArray();        
    }    
    
    public function create($data)
    {   
        
        $skill = $this->skill->create($data);
        
        return $skill; 
    }
    
    public function update($skillOrID, $data)
    {
        $skill = $this->_getModelObj($skillOrID);
        
        if(!$skill)
            return null;
        
        $skill->update($data);
        
        return $skill;
    }
    
    public function allUpdate($userOrID, $data)
    {
        $user = $this->_getModelObj($userOrID, $this->user);
        
        if(!$user)
            return null;
                
        $removeIDs = array_diff( $this->findByUser($userOrID)->pluck('id')->toArray(), $data['id']);
        $this->skill->whereIn('id', $removeIDs)->delete();
        
        $dataConvert = $this->_convertData($data);
        foreach($dataConvert as $rowUpdate){
            $rowUpdate['user_id'] = $user->id;
            $rowUpdate['used'] = date('Y-m-d H:i:s', strtotime($rowUpdate['used']));
            $this->skill->updateOrCreate(['id' => $rowUpdate['id']], $rowUpdate);
        }
        
        return $this->findByUser($userOrID)->groupBy('cat');
    }
    
    private function _getModelObj($IdOrModel, $isModel = null)
    {
        $model = $isModel ?? $this->skill;
        
        if ($IdOrModel instanceof $model){
            $modelObj = $IdOrModel;
        }else{
            $modelObj = $model->find($IdOrModel);                
        }
        
        return $modelObj;
    }
    
    private function _convertData($data)
    {
        $diff = array_diff( array_keys($data), getColumnNames($this->skill->getTable()) );
        foreach($diff as $keyRem)
            unset($data[$keyRem]);
     
        $rowKeys = array_keys($data);
        $rows = array_keys($data[array_key_first($data)]);                
        $convertData = [];
        foreach($rows as $row){
            $rowData = [];
            foreach ($rowKeys as $rowKey){
                $rowData[$rowKey] = $data[$rowKey][$row];
            }
            $convertData[] = $rowData;
        }
        
        return $convertData;
    }
             
}
