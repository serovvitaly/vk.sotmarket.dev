<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Layout extends Controller_Template {

    public $template = '_layout/default';
    
    public $data = array();
    
    
    
    public function __set($name, $value)
    {
        if (!isset( $this->$name )) {
            $this->template->$name = $value;           
        }
    }
    
    
    //
    
    
    public function after()
    {
        if (!isset($this->template->content)) {
            
            $view_name = strtolower($this->request->controller() . '/' . $this->request->action());
            
            $this->template->content = View::factory($view_name, $this->data);
        }
        
        
        parent::after();
    }

} // End
