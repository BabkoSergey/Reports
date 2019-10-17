<?php

namespace App\Repository;

use App\User;
use App\UserPosition;

class UserPositionsRepository
{       
    private $info;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user, UserPosition $position)
    {
        $this->user = $user;
        $this->position = $position;
    }
                
    public function find($id)
    {        
        return $this->position->find($id);
    }
    
    public function findAllByUser($userOrID)
    {
        $user = $this->_getModelObj($userOrID, $this->user);
        
        if(!$user)
            return null;
        
        return $this->position->where('user_id', $user->id)->get();
    }
    
    public function findLastByUser($userOrID)
    {
        $user = $this->_getModelObj($userOrID, $this->user);
        
        if(!$user)
            return null;
        
        $positions = $this->findAllByUser($user)->sortBy('from');
        
        return $positions->where('from', '<=', date('Y-m-d H:i:s', strtotime(date('Y-m-d', time()))))->last() ?? $positions->first();
    }
    
    public function getPositionIDBefore($positionOrID)
    {
        $position = $this->_getModelObj($positionOrID);
        
        if(!$position)
            return null;
        
        $positionBefore = $this->findAllByUser($position->user_id)->sortBy('from')->where('from', '>', $position->from)->first();
        
        return $positionBefore->id ?? null;
    }
        
    public function getUser($id)
    {
        $position = $this->find($id);
        if(!$position)
            return null;
        
        $user = $this->_getModelObj($position->user_id, $this->user);
        
        if(!$user)
            return null;
        
        return $user;
    }
        
    public function create($data)
    {   
        $error = $this->_checkAvalibleDates($data['user_id'], $data['from']);
        
        if($error->error)
            return $error;
                        
        $newPosition = $this->position->create($data);
        
        if($newPosition)
            $this->_resetPositionStatus($data['user_id'], $data['from'], $newPosition);
        
        return $newPosition; 
    }
    
    public function update($positionOrID, $data)
    {        
        $position = $this->_getModelObj($positionOrID);
        
        if(!$position)
            return null;
        
        $error = $this->_checkAvalibleDates($position->user_id, $data['from']);
        
        if($error->error)
            return $error;
        
        $position->update($data);
        
        return $position;
    }
    
    public function delete($positionOrID)
    {
        $position = $this->_getModelObj($positionOrID);
        
        if(!$position)
            return null;
        
        $lastPosition = $this->findLastByUser($position->user_id);
        if(!$position->to && $lastPosition->id == $position->id)
            $this->_layOffUser($position->user_id);
                     
        $position->delete($position->id);
        
        return true;
    }
    
    public function layOnUser($userOrID)
    {
        $user = $this->_getModelObj($userOrID, $this->user);
        
        if(!$user)
            return null;
        
        $position = $this->findLastByUser($user);        
        if($position && $position->to && $position->to == date('Y-m-d H:i:s', strtotime(date('Y-m-d', time()))) ){
            $position->to = null;
            $position->save();
            
            $user->pos_status = true;
            $user->status = true;
            $user->save();
        }            
        
        return $position;
    }
    
    public function layOffUser($userOrID)
    {
        $user = $this->_getModelObj($userOrID, $this->user);
        
        if(!$user)
            return null;
                
        return $this->_layOffUser($user);
    }
    
        
    private function _checkAvalibleDates($userID, $date)
    {
        $error = new \stdClass();        
        $error->error = null;
        
        $from = date('Y-m-d H:i:s', strtotime($date));        
        
        if( $from < date('Y-m-d H:i:s', strtotime(date('Y-m-d', time()))) ){
            $error->error = $date . ' - '. __('cannot be less than the current date');
            return $error;
        }
        
        $user = $this->_getModelObj($userID, $this->user);
        if( !$user ){
            $error->error = __('User is not found!');
            return $error;
        }
        
        $positions = $this->findAllByUser($user);
        
        if($positions && $positions->where('from', $from)->first()){
            $error->error = __('The value for this date is already set');
            return $error;            
        }
        
        return $error;
    }
    
    private function _resetPositionStatus($userOrID, $date, $newPosition = null)
    {
        $error = new \stdClass();        
        $error->error = null;
        
        $user = $this->_getModelObj($userOrID, $this->user);
        if( !$user ){
            $error->error = __('User is not found!');
            return $error;
        }
        
        $from = date('Y-m-d H:i:s', strtotime($date));    
        $now = date('Y-m-d H:i:s', strtotime(date('Y-m-d', time())));
        
        if( $from == $now ){            
            $this->_layOffUser($user, false);
        }
        
        if( $newPosition && $from == $now ){            
            $newPosition->to = null;
            $newPosition->save();
            
            $user->pos_status = true;
            $user->status = true;
            $user->save();
        }
        
        return $error;
    }
    
    private function _layOffUser($userOrID, $now = true)
    {
        $user = $this->_getModelObj($userOrID, $this->user);
        
        $dateNow = date('Y-m-d H:i:s', strtotime(date('Y-m-d', time())));
        $datePreNow = date('Y-m-d H:i:s', strtotime("-1 day", strtotime($dateNow)));
        
        $position = $this->findAllByUser($user)->sortBy('from')->where('from', $now ? '<=' : '<', $dateNow)->last(); 
        
        if(!$position)
            return;
        
        if(!$position->to){                
            $position->to = $now ? $dateNow : $datePreNow;            
            $position->save();
        
            $user->pos_status = false;
            $user->status = false;
            $user->save();
        }else if(!$now && $position->to == $dateNow){
            $position->to = $datePreNow;    
            $position->save();
        }
        
        return $position;
    }


    private function _getModelObj($IdOrModel, $isModel = null)
    {
        $model = $isModel ?? $this->position;
        
        if ($IdOrModel instanceof $model){
            $modelObj = $IdOrModel;
        }else{
            $modelObj = $model->find($IdOrModel);                
        }
        
        return $modelObj;
    }
}
