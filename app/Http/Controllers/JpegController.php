<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\PhotoshopHelper;
use App\jpegModel;
use App\category;
use App\color;
use App\EditingModel;
use Auth; 
class JpegController extends Controller
{
  
   public $product;
   public $jpeg;
   public $jpeg_pending_list;
   public $category;
   public $color;
  
   public function __construct()
   {
      $this->jpeg_pending_list=PhotoshopHelper::get_editing_product_list();
        $this->category=category::all();
      $this->color=color::all();
      $this->product=PhotoshopHelper::get_jpeg_product_list();
    
   }
   /*
   Get Jpeg Pending List with out Ajax Load
   */
   public function get_pending_list_jpeg()
   {
     $list=$this->jpeg_pending_list->where(['editing_models.status'=>3,'editing_models.next_department_status'=>0])->get();
      $categorylist=$this->category;
      $colorlist=$this->color;
     return view('Photoshop/JPEG/jpeg_pending',compact('list','categorylist','colorlist'));
   }
 /*
   Get Jpeg Ajax Pending List with Load
   */
public function get_pending_Ajax_list(Request $request){
   $data = array();
   $params = $request->post();
   $start = (!empty($params['start']) ? $params['start'] : 0);
   $length = (!empty($params['length']) ? $params['length'] : 10);
   $stalen = $start / $length;
   $curpage = $stalen;
   $maindata = EditingModel::query();
   $maindata->join('photography_products','editing_models.product_id','photography_products.id');
   $maindata->join('categories','editing_models.category_id','categories.entity_id');
  $maindata->where(['editing_models.next_department_status'=>0,'editing_models.status'=>3]);
   $where = '';
   $offset = '';
   $limit = '';
   $order = $params['order'][0]['column'];
   $order_direc = strtoupper($params['order'][0]['dir']);
   if(!empty($params['skusearch'])){
       $maindata->where('photography_products.sku',$params['skusearch']);
   }
  if(!empty($params['category'])){
   $maindata->where('photography_products.category_id',$params['category']);
  }
  if(!empty($params['color'])){
   $maindata->where('photography_products.color',$params['color']);
  }
  
    $datacount = $maindata->count();
   $datacoll = $maindata;
       $data["recordsTotal"] = $datacount;
   $data["recordsFiltered"] = $datacount;
   $data['deferLoading'] = $datacount;
       $csrf=csrf_field();
     
       $datacollection = $datacoll->take($length)->offset($start)->get();
       
        if(count($datacollection)>0){
           foreach($datacollection as $key=>$p){
               $srno = $key + 1 + $start;
               $action='
                   <form action="" method="POST">
                   '.$csrf.'
     <input type="hidden" value="'.$p->id.'" name="product_id"/>
     <input type="hidden" value="'.$p->category_id.'" name="category_id"/>
           <select name="status" class="form-control" style="height:20px;width:150px;float: left;">
           <option value="2">Pending</option>
           <option value="1">In processing</option>
           <option value="3">Done</option>
       </select>
       <button type="submit" style="height: 30px;
   width: 30px;"  class="btn btn-primary btn-circle"><i class="material-icons list-icon">check</i></button>
   
     </form>
                   ';
            
               $data['data'][] = array($srno,$p->sku, $p->color,$p->name, $action);
           }
          
       }else{
           $data['data'][] = array('', '', '', '','');
     
       }
    echo json_encode($data);
   exit;
}
//get all done list of JPEG Department

   public function get_done_list_jpeg()
   {
         $done_list=$this->product->where(['jpeg_models.status'=>3])->get();
         $categorylist=$this->category;
         $colorlist=$this->color;
       return view('Photoshop/JPEG/jpeg_done',compact('done_list','categorylist','colorlist'));

   }
   /*
   Done Ajax List of Jpeg Department
   */
  public function get_done_ajax_list(Request $request){
   $data = array();
   $params = $request->post();
   $start = (!empty($params['start']) ? $params['start'] : 0);
   $length = (!empty($params['length']) ? $params['length'] : 10);
   $stalen = $start / $length;
   $curpage = $stalen;
   $maindata = jpegModel::query();
   $maindata->join('photography_products','jpeg_models.product_id','photography_products.id');
   $maindata->join('categories','jpeg_models.category_id','categories.entity_id');
  $maindata->where(['jpeg_models.next_department_status'=>0,'jpeg_models.status'=>3]);
   $where = '';
   $offset = '';
   $limit = '';
   $order = $params['order'][0]['column'];
   $order_direc = strtoupper($params['order'][0]['dir']);
   if(!empty($params['skusearch'])){
       $maindata->where('photography_products.sku',$params['skusearch']);
   }
  if(!empty($params['category'])){
   $maindata->where('photography_products.category_id',$params['category']);
  }
  if(!empty($params['color'])){
   $maindata->where('photography_products.color',$params['color']);
  }
  
    $datacount = $maindata->count();
   $datacoll = $maindata;
       $data["recordsTotal"] = $datacount;
   $data["recordsFiltered"] = $datacount;
   $data['deferLoading'] = $datacount;
       $csrf=csrf_field();
     
       $datacollection = $datacoll->take($length)->offset($start)->get();
       
        if(count($datacollection)>0){
           foreach($datacollection as $key=>$p){
               $srno = $key + 1 + $start;
               $action='
                   <form action="" method="POST">
                   '.$csrf.'
     <input type="hidden" value="'.$p->id.'" name="product_id"/>
     <input type="hidden" value="'.$p->category_id.'" name="category_id"/>
           <select name="status" class="form-control" style="height:20px;width:150px;float: left;">
           <option value="0">select status</option>
           <option value="4">Rework</option>
     
       </select>
       <button type="submit" style="height: 30px;
   width: 30px;"  class="btn btn-primary btn-circle"><i class="material-icons list-icon">check</i></button>
   
     </form>
                   ';
            
               $data['data'][] = array($srno,$p->sku, $p->color,$p->name,"Done", $action);
           }
          
       }else{
           $data['data'][] = array('', '', '', '','','');
     
       }
    echo json_encode($data);
   exit;
  }
   //get all Rework list of JPEG Department
   public function get_rework_list_jpeg()
   { 
      $rework_list=$this->product->where(['jpeg_models.status'=>4])->get();
      $categorylist=$this->category;
      $colorlist=$this->color;
      return view('Photoshop/JPEG/jpeg_rework',compact('rework_list','categorylist','colorlist'));
   }
/*
Ajax Rework List 
*/
public function get_ajax_Rework_list(Request $request){
   $data = array();
   $params = $request->post();
   $start = (!empty($params['start']) ? $params['start'] : 0);
   $length = (!empty($params['length']) ? $params['length'] : 10);
   $stalen = $start / $length;
   $curpage = $stalen;
   $maindata = jpegModel::query();
   $maindata->join('photography_products','jpeg_models.product_id','photography_products.id');
   $maindata->join('categories','jpeg_models.category_id','categories.entity_id');
  $maindata->where(['jpeg_models.next_department_status'=>0,'jpeg_models.status'=>4]);
   $where = '';
   $offset = '';
   $limit = '';
   $order = $params['order'][0]['column'];
   $order_direc = strtoupper($params['order'][0]['dir']);
   if(!empty($params['skusearch'])){
       $maindata->where('photography_products.sku',$params['skusearch']);
   }
  if(!empty($params['category'])){
   $maindata->where('photography_products.category_id',$params['category']);
  }
  if(!empty($params['color'])){
   $maindata->where('photography_products.color',$params['color']);
  }
  
    $datacount = $maindata->count();
   $datacoll = $maindata;
       $data["recordsTotal"] = $datacount;
   $data["recordsFiltered"] = $datacount;
   $data['deferLoading'] = $datacount;
       $csrf=csrf_field();
     
       $datacollection = $datacoll->take($length)->offset($start)->get();
       
        if(count($datacollection)>0){
           foreach($datacollection as $key=>$p){
               $srno = $key + 1 + $start;
               $action='
                   <form action="" method="POST">
                   '.$csrf.'
     <input type="hidden" value="'.$p->id.'" name="product_id"/>
     <input type="hidden" value="'.$p->category_id.'" name="category_id"/>
           <select name="status" class="form-control" style="height:20px;width:150px;float: left;">
           <option value="1">In Process</option>
           <option value="3">Done</option>
     
       </select>
       <button type="submit" style="height: 30px;
   width: 30px;"  class="btn btn-primary btn-circle"><i class="material-icons list-icon">check</i></button>
   
     </form>
                   ';
            
               $data['data'][] = array($srno,$p->sku, $p->color,$p->name,"Rework", $action);
           }
          
       }else{
           $data['data'][] = array('', '', '', '','','');
     
       }
    echo json_encode($data);
   exit;
}
   public function submit_pending_list_jpeg(Request $request)
   {
      $url= $request->url();
      $urllink= explode('Photoshop/',$url);
      $link= $urllink[1];
      $user=Auth::user();
      $jpeg=new jpegModel();
      if($request->input('status') !="1")
      {
         $jpeg->product_id=$request->input('product_id');
         $jpeg->category_id=$request->input('category_id');
          $jpeg->status=$request->input('status');
          $jpeg->current_status='1';
          $jpeg->next_department_status='0';
       
         //Cache table data Insert
         if($request->input('status')=='3')
         {
          $jpeg->save();
          $cache=array(
            'product_id'=>$request->input('product_id'),
            'action_name'=>$link,
            'status'=>$request->input('status'),
            'action_by'=>"user",
            'action_date_time'=>date('Y-m-d H:i:s')
                      );
           PhotoshopHelper::store_cache_table_data($cache);
           jpegModel::getUpdatestatusdone($request->input('product_id'));
         }
        
      }
      return redirect()->back()->with('success', 'Jpeg status Change Successfull');
      
   }

   public function submit_done_list_jpeg(Request $request)
   {
      $url= $request->url();
      $urllink= explode('Photoshop/',$url);
      $link= $urllink[1];
      if($request->input('status') !='0')
      {
     
         $cache=array(
            'product_id'=>$request->input('product_id'),
            'action_name'=>$link,
            'status'=>$request->input('status'),
            'action_by'=>"user",
            'action_date_time'=>date('Y-m-d H:i:s')
  
        );
        PhotoshopHelper::store_cache_table_data($cache);
        jpegModel::update_Jpeg_status($request->get('product_id'),$request->input('status'));
     $message=array(
      'success'=>'Jpeg Done Change Successfull',
      'class'=>'alert alert-success',
  );
      }
      else{
         $message=array(
            'success'=>'Jpeg Select Status',
            'class'=>'alert alert-danger',
        );
      }
      return redirect()->back()->with($message);
   }
}
