<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\category;
class photography_product extends Model
{
    public static function get_product_list()
    {
          return photography_product::where('status','=',0) ->orderBy('id', 'DESC')->take(10)->get();
    }
    public function category()
    {
        return $this->hasOne('App\category','entity_id','category_id');
    }
 
    public static function get_photography_product_count(){

        return photography_product::all();
    }
    public static function productInsert($data){
    
        return photography_product::insert($data);
       }
       /*
        find category by category id 
       */
  public static function  get_category_by_id($id){
    $name=category::where('entity_id',$id)->first();
    return $name->name;
  }

 public static function update_product($id){
  $data=array('status'=>"1");
  return photography_product::where('id','=',$id)->update($data);
 }

}
