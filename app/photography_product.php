<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class photography_product extends Model
{
    public static function get_product_list()
    {
          return photography_product::where('status','=',0)->take(10)->get();
    }
    public function category()
    {
        return $this->hasOne('App\category','entity_id','category_id');
    }
 
    public static function get_photography_product_count(){

        return photography_product::all();
    }
   
   
}
