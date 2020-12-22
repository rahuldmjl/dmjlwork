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
use App\category;
use App\color;
use App\userphotography;
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

public static  function getCategoryList(){
    $cat=category::all();
     return $cat;
   
}
public static function getcolorList(){
    $color=DB::table('colors')->get();
     return $color;
   
}
public static function get_product_list($uid){
if($uid=="0"){
    $product=DB::table('photography_products as p');
  
}else{
    $product=DB::table('photography_products as p')->where('userid','=',$uid);
  
}
   $product=DB::table('photography_products as p')->where('userid','=',$uid);
   return $product;
}
public static function getUserAssign($id){
    $check=userphotography::find($id);
    if($check){
        return  $check->name;
    }
        else{
            return  "pending";
        }
   
}

public static function get_count_product($model,$status,$userid){
    $ta=$model."s";
    if($status=="a"){
        $count=DB::table($ta)->where('userid','=',$userid)->groupBy(['sku','color'])->get();
    }else{
        $count=DB::table($ta)->where("status",$status)->where('userid','=',$userid)->groupBy(['sku','color'])->get();
    }
    return count($count);
}
//Get Placement Data from the join 
public static function update_user_assign($uid,$pid){
    
    DB::table('photography_products')->where(["id"=>$pid])->update(["userid"=>$uid]);

  
}
public static function get_placement_product_detail(){

   $product=DB::table('placements')
    ->join('photography_products','photography_products.id','placements.product_id')
    ->join('categories','categories.entity_id','placements.category_id');
    return $product;
}
//Get PhotoGraphy Product List Using Joing

public static function get_photography_product_list($userid){
    $product=DB::table('photographies')
    ->join('photography_products as p','p.id','photographies.product_id')
    ->join('categories as c','c.entity_id','photographies.category_id')
    ->where('p.userid','=',$userid);
    return $product;
}
//psd Pending List for user assign

public static function get_psd_pending_product_list($userid){
    $product=DB::table('photographies')
    ->join('photography_products as p','p.id','photographies.product_id')
    ->join('categories as c','c.entity_id','photographies.category_id')
    ->where('photographies.work_assign','=',$userid);
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


/*
Photography Actity Code below

*/
public static function getUserList(){
    $user=DB::table('userphotographies')->get();
    return $user;
}
}   
?>