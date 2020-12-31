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
use App\shootModel;
class PhotoshopHelper
{



public static function get_product_validation($id){
    $data=DB::table('photography_products')
        ->select('id','status')
        ->where('id','=',$id)
        ->first();
    return $data->status;
}


public static function getCategory_name_by_id($id){
    $status=DB::table('categories')
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
    $product=DB::table('photography_products as p')->where(['status'=>0])->groupBy(['sku','color']);
}else{
    $product=DB::table('photography_products as p')->where(['userid'=>$uid,'status'=>0])->groupBy(['sku','color']);
  
}
  
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
        $count=DB::table($ta)->where(["status"=>$status,'userid'=>$userid])->get();
    }
    return $count;
}
//Get Count For All Department and All Status
public static function getCountAllDepartment($model,$userid,$status){
    $totalrecord=DB::table($model .' as m')
                ->join('photography_products as p','m.product_id','p.id')
                ->groupBy(['sku','color'])
                ->where(['m.created_by'=>$userid,'m.status'=>$status])
                ->get()->count();
             return $totalrecord;   

}
//Get Placement Data from the join 
public static function update_user_assign($uid,$pid,$loginid){
    
    DB::table('photography_products')->where(["id"=>$pid])->update(["userid"=>$uid,"work_assign_by"=>$loginid]);

  
}
public static function get_photoshop_product_list($table,$userid){
    $product=DB::table($table.' as pro')
    ->join('photography_products as p','p.id','pro.product_id')
    ->join('categories as c','c.entity_id','pro.category_id')
    ->groupBy(['p.sku','p.color'])
    ->where('pro.created_by','=',$userid)
    ->orderBy('pro.id','DESC');
    return $product;
}
//psd Pending List for user assign
public static function get_photoshop_product_list_user($table,$userid){
    $product=DB::table($table.' as pro')
    ->join('photography_products as p','p.id','pro.product_id')
    ->join('categories as c','c.entity_id','pro.category_id')
    ->groupBy(['p.sku','p.color'])
    ->where('pro.work_assign_user','=',$userid)
    ->orderBy('pro.id','DESC');
    return $product;
}



/*
Photography Actity Code below

*/
public static function getUserList(){
    $user=DB::table('userphotographies')->get();
    return $user;
}
//Default load Data in Photography Activity function
public static function getDefaultLoadIn_photography_activity(){
    $data=DB::table('photographies as p')
        ->join('photography_products as pro','p.product_id','pro.id')
        ->join('categories as c','c.entity_id','p.category_id')
        ->join('userphotographies as u','p.created_by','u.id')
        ->join('photoshop_caches as cs','pro.id','cs.product_id')
        ->select('pro.sku','pro.id','c.name','pro.color','u.name as username','p.status','cs.action_name','p.created_at')
        ->where('p.work_assign_user','=',0)
        ->groupBy(['pro.sku','pro.color']);
        return $data;
    
}

public static function activity_load(){
    $data=DB::table('photoshop_caches as cache')
        ->join('photography_products as pro','cache.product_id','pro.id')
        ->join('categories as c','c.entity_id','pro.category_id')
        ->select('pro.sku','pro.color','pro.userid','c.name','cache.status','cache.action_name','cache.action_date_time');
      
    return $data;
}

public static function getWorkAssign_List($department){
    if($department==""){
        $department="photographies";
        
    }else{
        $department=$department;
    }
      $data=DB::table('photography_products as pro')
        ->join($department .' as p','p.product_id','pro.id')
        ->join('categories as c','p.category_id','c.entity_id')
        ->select('pro.sku','pro.color','pro.userid','c.name as categoryname','p.status','p.product_id as pid')
        ->where('p.next_department_status',0)
        ->groupBy(['pro.sku','pro.color']);
        
       
     return $data;
}

public static function updateuserassign($table,$pid,$uid,$loginuser){
    $status=DB::table($table)->where(["product_id"=>$pid])->update(["work_assign_user"=>$uid,'work_assign_by'=>$loginuser]);
    if($status){
        return true;
    }else{
        return false;
    }
}

public static function getCategoryname($id){
   
    $data=DB::table('categories')
        ->select('name as catname')
        ->where('entity_id',$id)
        ->get();

      return $data;  
}
public static function getuserbyname($id){
   
    $data=DB::table('userphotographies')
        ->select('name as uname')
         ->where('id',$id)
        ->get();

      return $data;  
}

public static function get_shoot_data(){

    $data=DB::table('photography_products as p')
          ->join('categories as c','p.category_id','c.entity_id')
          ->select('p.id','p.sku','p.category_id','c.name','p.color')
          ->where('p.status','=',1);

    return $data;
 }

public static function get_shoot_data_from_shoot_table($model){
    $data=DB::table('shoot_models as s')
        ->join('photography_products as p','p.id','s.product_id')
        ->join('categories as c','s.category_id','c.entity_id')
        ->where(['shootModule'=>$model]);
    return $data;      
}
public static function updateshoot($attrbute,$status,$productid){
   $shoot=photography_product::where('id','=',$productid)->first();
   $shoot->$attrbute=$status;
   $shoot->save();
   return true;
}
public static function getShootData($pid,$model){
    $status=shootModel::where(["product_id"=>$pid,'shootModule'=>$model])->get();
    return $status->count();
}
public static function updateshoottable($pid,$status){
    $shoot=shootModel::where('product_id','=',$pid)->first();
    $shoot->status=$status;
    $shoot->save();
    return true;
}
}   
?>