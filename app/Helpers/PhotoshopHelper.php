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


}   
?>