<?php

namespace App\Repository;

use App\User;
use App\UserInfo;

class UserInfosRepository
{       
    private $info;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user, UserInfo $info)
    {
        $this->user = $user;
        $this->info = $info;
    }
                
    public function find($id)
    {        
        return $this->info->find($id);
    }
    
    public function findByUser($userOrID)
    {
        $user = $this->_getModelObj($userOrID, $this->user);
        
        if(!$user)
            return null;
        
        $info = $this->info->where('user_id', $user->id)->first();
        
        return $info ? $info : $this->create(['user_id' => $user->id]);
    }
        
    public function create($data)
    {   
        
        $info = $this->info->create($data);
        
        return $info; 
    }
    
    public function update($infoOrID, $data)
    {
        $info = $this->_getModelObj($infoOrID);
        
        if(!$info)
            return null;
        
        $info->update($data);
        
        return $info;
    }

    public function getEnums($colum) 
    {         
        return getEnumValues($this->info->getTable(), $colum);
    }
    
    private function _getModelObj($IdOrModel, $isModel = null)
    {
        $model = $isModel ?? $this->info;
        
        if ($IdOrModel instanceof $model){
            $modelObj = $IdOrModel;
        }else{
            $modelObj = $model->find($IdOrModel);                
        }
        
        return $modelObj;
    }
             
}
