<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    public $table = 'categories';
    public function product()
    {
        return $this->belongsTo('App\photography_product');
    }

    public static function get_category_entity_id($id)
    {
        return category::where('entity_id','=',$id)->first();
        
    }


}
