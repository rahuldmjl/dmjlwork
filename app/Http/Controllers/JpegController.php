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
  public $userid;
   public function __construct()
   {
       $this->userid=1;
      $this->jpeg_pending_list=PhotoshopHelper::get_photoshop_product_list_user("editing_models",$this->userid);
        $this->category=category::all();
      $this->color=color::all();
      $this->product=PhotoshopHelper::get_photoshop_product_list('jpeg_models',$this->userid);
    
   }
   /*
   Get Jpeg Pending List with out Ajax Load
   */
   public function get_pending_list_jpeg()
   {
    $total=$this->jpeg_pending_list->get()->count();
     $list=$this->jpeg_pending_list->where(['pro.status'=>3,'pro.next_department_status'=>0])->limit(10)->get();
      $categorylist=$this->category;
      $colorlist=$this->color;
     return view('Photoshop/JPEG/jpeg_pending',compact('total','list','categorylist','colorlist'));
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
   $maindata =$this->jpeg_pending_list;
      $where = '';
   $offset = '';
   $limit = '';
   $order = $params['order'][0]['column'];
   $order_direc = strtoupper($params['order'][0]['dir']);
   if(!empty($params['skusearch'])){
       $maindata->where('p.sku','LIKE', '%' . $params['skusearch']. '%');
   }
  if(!empty($params['category'])){
   $maindata->where('p.category_id',$params['category']);
  }
  if(!empty($params['color'])){
   $maindata->where('p.color',$params['color']);
  }
  
    $datacount = $maindata->get()->count();
   $datacoll = $maindata->where(['pro.status'=>3,'pro.next_department_status'=>0])->orderBy('pro.id','DESC');
     $data["recordsTotal"] = $datacount;
    $data["recordsFiltered"] = $datacount;
    $data['deferLoading'] = $datacount;
       $datacollection = $datacoll->take($length)->offset($start)->get();
       
        if(count($datacollection)>0){
           foreach($datacollection as $key=>$p){
               $srno = $key + 1 + $start;
               $action='<select name="status" onchange="pendingtodone(this.value)" class="form-control" style="height:20px;width:150px;float: left;">
           <option value="2/'.$p->id.'/'.$p->category_id.'">Pending</option>
           <option value="1/'.$p->id.'/'.$p->category_id.'">In processing</option>
           <option value="3">Done</option>
       </select>
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
         $done_list=$this->product->where(['pro.status'=>3])->get();
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
   $maindata = $this->product;
   $where = '';
   $offset = '';
   $limit = '';
   $order = $params['order'][0]['column'];
   $order_direc = strtoupper($params['order'][0]['dir']);
   if(!empty($params['skusearch'])){
       $maindata->where('p.sku','LIKE', '%' . $params['skusearch']. '%');
   }
  if(!empty($params['category'])){
   $maindata->where('p.category_id',$params['category']);
  }
  if(!empty($params['color'])){
   $maindata->where('p.color',$params['color']);
  }
  
    $datacount = $maindata->count();
   $datacoll = $maindata->where(['pro.status'=>3]);
       $data["recordsTotal"] = $datacount;
   $data["recordsFiltered"] = $datacount;
   $data['deferLoading'] = $datacount;
       $csrf=csrf_field();
     
       $datacollection = $datacoll->take($length)->offset($start)->get();
       
        if(count($datacollection)>0){
           foreach($datacollection as $key=>$p){
               $srno = $key + 1 + $start;
               $action='<select name="status" onchange="donetorework(this.value)" class="form-control" style="height:20px;width:150px;float: left;">
           <option value="0/'.$p->id.'/'.$p->category_id.'">select status</option>
           <option value="4/'.$p->id.'/'.$p->category_id.'">Rework</option>
            
           </select>';
            
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
      $rework_list=$this->product->where(['pro.status'=>4])->get();
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
   $maindata = $this->product;
   $where = '';
   $offset = '';
   $limit = '';
   $order = $params['order'][0]['column'];
   $order_direc = strtoupper($params['order'][0]['dir']);
   if(!empty($params['skusearch'])){
       $maindata->where('p.sku','LIKE', '%' . $params['skusearch']. '%');
   }
  if(!empty($params['category'])){
   $maindata->where('p.category_id',$params['category']);
  }
  if(!empty($params['color'])){
   $maindata->where('p.color',$params['color']);
  }
  
    $datacount = $maindata->count();
   $datacoll = $maindata->where(['pro.status'=>4]);
       $data["recordsTotal"] = $datacount;
   $data["recordsFiltered"] = $datacount;
   $data['deferLoading'] = $datacount;
       $csrf=csrf_field();
     
       $datacollection = $datacoll->take($length)->offset($start)->get();
       
        if(count($datacollection)>0){
           foreach($datacollection as $key=>$p){
               $srno = $key + 1 + $start;
               $action=' <select name="status" onchange="reworktodone(this.value)" class="form-control" style="height:20px;width:150px;float: left;">
           <option value="1/'.$p->id.'/'.$p->category_id.'">In Process</option>
           <option value="3/'.$p->id.'/'.$p->category_id.'">Done</option>
                            </select>';
            
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
      $dep=explode("/",$link);
      $user=Auth::user();
      $jpeg=new jpegModel();
      if($request->input('status') !="1")
      {
         $jpeg->product_id=$request->input('product_id');
         $jpeg->category_id=$request->input('category_id');
          $jpeg->status=$request->input('status');
          $jpeg->current_status='1';
          $jpeg->next_department_status='0';
         
          $jpeg->created_by=$this->userid;
          $jpeg->work_assign_by="0";
          $jpeg->work_assign_user="0";
         //Cache table data Insert
         if($request->input('status')=='3')
         {
          $jpeg->save();
          $cache=array(
            'product_id'=>$request->input('product_id'),
            'action_name'=>$dep[0],
            'status'=>$request->input('status'),
            'action_by'=>$this->userid,
            'action_date_time'=>date('Y-m-d H:i:s')
                      );
           PhotoshopHelper::store_cache_table_data($cache);
           jpegModel::getUpdatestatusdone($request->input('product_id'));
         }
         $message="Jpeg status Change Successfull";
        
      }
        return response()->json(['success'=>$message]);
   
   }

   public function submit_done_list_jpeg(Request $request)
   {
      $url= $request->url();
      $urllink= explode('Photoshop/',$url);
      $link= $urllink[1];
      $dep=explode("/",$link);
      if($request->input('status') !='0')
      {
     
         $cache=array(
            'product_id'=>$request->input('product_id'),
            'action_name'=>$dep[0],
            'status'=>$request->input('status'),
            'action_by'=>$this->userid,
            'action_date_time'=>date('Y-m-d H:i:s')
  
        );
        PhotoshopHelper::store_cache_table_data($cache);
        jpegModel::update_Jpeg_status($request->get('product_id'),$request->input('status'));
     $message=config('constants.message.status');;
 
      }
      else{
        $message=config('constants.message.status');;
 
      }
      return redirect()->back()->with($message);
   }
}
