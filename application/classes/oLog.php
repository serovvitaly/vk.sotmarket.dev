<?php defined('SYSPATH') or die('No direct script access.');

class oLog {
    
    public static function add($data, $type = 'UNDEFINED', $notice = NULL)
    {      
        if (is_array($data) OR is_object($data)) {
            $data = json_encode($data);
        }
        
        $log = ORM::factory('Log');
      
        $log->data   = $data;
        
        $log->type   = strtoupper($type);
        
        $log->notice = $notice;
        
        $log->save();
    }

} // End
