<?php defined('SYSPATH') or die('No direct script access.');

class Model_Log extends ORM {

    public function add($data, $type = 'UNDEFINED', $notice = NULL)
    {      
        if (is_array($data) OR is_object($data)) {
            $data = json_encode($data);
        }
      
        $this->data   = $data;
        
        $this->type   = strtoupper($type);
        
        $this->notice = $notice;
        
        $this->save();
    }

} // End
