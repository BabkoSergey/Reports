<?php

function transKeys($data) {
    
    return $data;
}

function transValues($data, $pluck = false, $upperCase = false) {
        
    $result = [];
    
    foreach($data as $key=>$val){
        if($pluck){
            $result[$val] = __($upperCase ? ucfirst($val) : $val);
        }else{
            $result[$key] = __($upperCase ? ucfirst($val) : $val);
        }        
    }
    
    return $result;
}

function transValuesOnlyKeyd($data) {
        
    $result = [];
    
    foreach($data as $key=>$val)        
            $result[$val] = $val;
    
    return $result;
}

