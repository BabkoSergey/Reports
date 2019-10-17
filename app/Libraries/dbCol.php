<?php
    
    function getEnumValues($table, $column)
    {
        $type = DB::select(DB::raw("SHOW COLUMNS FROM $table WHERE Field = '{$column}'"))[0]->Type ?? '';
        
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        
        $enum = array();
        
        foreach (explode(',', $matches[1]) as $value) {
            $v = trim($value, "'");
            $enum = array_add($enum, $v, $v);
        }

        return $enum;
    }
    
    function getColumnNames($table)
    {
        try
        {
            $names = DB::connection()->getSchemaBuilder()->getColumnListing($table);
        }
        catch (Exception $e)
        {
            $names = [];
        }
        
        return $names;
    }
    
    function getLength($table, $column)
    {             
        try
        {
            $length = DB::connection()->getDoctrineColumn($table, $column)->getLength() ?? null;
        }
        catch (Exception $e)
        {
            $length = null;
        }
        
        return $length;
    }