<?php defined('SYSPATH') or die('No direct script access.');

class Model_Product extends ORM {
    
    
    public function price()
    {
        return number_format($this->price, 2);
    }
    
    
} // End
