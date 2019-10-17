<?php

namespace App\Repository;

use App\DevReport;

class DevReportsRepository
{       
    private $report;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(DevReport $report)
    {
        $this->report = $report;
    }
    
    public function all($where = [])
    {        
        return empty($where) ? $this->report->all() : $this->report->where($where)->get();
    }
        
    public function find($id)
    {
        $report = $this->report->find($id);
                    
        return $report;
    }
        
    public function create($data)
    {   
        $report = $this->report->create($data);
        
        return $report; 
    }
    
    public function update($reportOrID, $data)
    {
        $report = $this->_getModelObj($reportOrID);
        
        if(!$report)
            return null;
        
        $report->update($data);
        
        return $report;
    }
    
    public function delete($reportOrID)
    {
        $report = $this->_getModelObj($reportOrID);
        
        if(!$report)
            return false;
                
        $report->delete();
        
        return true;
    }
    
    public function getFullTime($params = [])
    {
        $sumTime = function($reports) {
            $res = 0;
            foreach ($reports as $reportsRow) {
                $res += strtotime($reportsRow->time);
            }
            return date('H:i', $res);
        };

        return $sumTime($this->all($params));
    }
    
    private function _getModelObj($IdOrModel, $isModel = null)
    {
        $model = $isModel ?? $this->report;
        
        if ($IdOrModel instanceof $model){
            $modelObj = $IdOrModel;
        }else{
            $modelObj = $model->find($IdOrModel);                
        }
        
        return $modelObj;
    }
             
}
