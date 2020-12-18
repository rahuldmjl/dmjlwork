<?php
namespace App\Helpers;
use Illuminate\Support\Facades\DB;
use App;
use App\productListModel;
use Auth;
use Config;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use App\photography_product;

class PhotoshopHelper
{


//Get the Status From SELECT * from photosraphy_status
public static function getStatus($id)
{
    DB::setTablePrefix('');
    $status=DB::table('photosraphy_status')
            ->where('entity_id','=',$id)
            ->get();
            DB::setTablePrefix('dml_');
   return $status;
}
//Get department from url 
public static function getDepartment($url)
{
    $url=explode('Photoshop/',$url);
    $depart=explode('/',$url[1]);
    return $depart[0];
}

public static function get_product_validation($id){
    $data=DB::table('photography_products')
        ->select('id','status')
        ->where('id','=',$id)
        ->first();
    return $data->status;
}

public static function get_status($name)
{
    $status=DB::table('photoshop_status_types')
            ->where('status_name','=',$name)
            ->get();
            return $status;
}

public static function getCategory_name_by_id($id){
    $status=DB::table('categories')
    ->select('name')
    ->where('entity_id','=',$id)
    ->get();
    return $status;
}

public static function store_cache_table_data($cache)
{
  PhotoshopHelper::addintoCasheTable($cache);
 }
public static function addintoCasheTable($data)
{
    $data=DB::table('photoshop_caches')
                ->insert($data);

}
public static function get_product_list(){
    
   $product=DB::table('photography_products as p')
          ->join('categories as c','c.entity_id','p.category_id')
          ->select('p.id','p.sku','p.color','c.name','c.entity_id','c.name');
     return $product;
}
//Get Placement Data from the join 

public static function get_placement_product_detail(){

   $product=DB::table('placements')
    ->join('photography_products','photography_products.id','placements.product_id')
    ->join('categories','categories.entity_id','placements.category_id');
    return $product;
}
//Get PhotoGraphy Product List Using Joing

public static function get_photography_product_list(){
    $product=DB::table('photographies')
    ->join('photography_products','photography_products.id','photographies.product_id')
    ->join('categories','categories.entity_id','photographies.category_id');
    
    
    return $product;
}
//Get PhotoGraphy Product List Using Joing

public static function get_psd_product_list(){
    $product=DB::table('psds')
    ->join('photography_products','photography_products.id','psds.product_id')
    ->join('categories','categories.entity_id','psds.category_id');
    
    
    return $product;
}
//Get Editing Product List
public static function get_editing_product_list(){
    $product=DB::table('editing_models')
    ->join('photography_products','photography_products.id','editing_models.product_id')
    ->join('categories','categories.entity_id','editing_models.category_id');
     return $product;
}
//Get Editing Product List
public static function get_jpeg_product_list(){
    $product=DB::table('jpeg_models')
    ->join('photography_products','photography_products.id','jpeg_models.product_id')
    ->join('categories','categories.entity_id','jpeg_models.category_id');
     return $product;
}
}   
?>