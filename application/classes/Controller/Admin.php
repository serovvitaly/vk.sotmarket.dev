<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin extends Controller_Layout {

    public $template = '_layout/admin';
    
    const PRODUCTS_DISPALY = 20;
    
    public function action_index()
    {        
        //
    }
    
    
    public function action_prod_list()
    {
        $current_page = $this->request->param('id', 1);
        if ($current_page < 1) $current_page = 1;
        
        $this->data['current_page'] = $current_page;
        
        
        $products = ORM::factory('Product');
        
        $pages_count = ceil( $products->count_all() / self::PRODUCTS_DISPALY );
        if ($current_page > $pages_count) $current_page = $pages_count;
        
        $this->data['pages_count'] = $pages_count;
        
        $offset = ($current_page - 1) * self::PRODUCTS_DISPALY;
        
        $this->data['products'] = $products->offset($offset)->limit( self::PRODUCTS_DISPALY )->find_all()->as_array();
    }

} // End
