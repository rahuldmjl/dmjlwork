<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\photography;
use App\productListModel;
use App\Helpers\PhotoshopHelper;
use App\photography_product;
use DB;
use Auth;
use App\category;
use App\color;
use App\shootModel;
class PhotoshopController extends Controller
{
    public $product;
    public $photography;
    public  $category;
    public $color;
    public $product_list;
    public $userid;
    public $count;
    public $total;
    public $done;
    public $punding;
    public function __construct()
    {
        $this->userid="1";
        $this->total=PhotoshopHelper::get_count_product("photography_product","0",$this->userid)->count();
        $this->done=PhotoshopHelper::get_count_product1("photographies","3",$this->userid)->count();
        $this->pending=PhotoshopHelper::get_count_product1("photographies","4",$this->userid)->count();
        $this->product=PhotoshopHelper::get_product_list($this->userid);
        $this->category=category::all();
        $this->color=color::all();
        $this->product_list=PhotoshopHelper::get_photoshop_product_list("photographies",$this->userid);
    }
    public function index()
    {

    }
    /*
    Photography pending get data from this function
    */
    public function get_pending_list()
    {
    
         $category_name=$this->category;
         $color_name=$this->color;
         $list=$this->product->limit(10)->get();
          $totalproduct=$this->total;
           $done_product_count=$this->done;
           $rework_product_count=$this->pending;
          return view('Photoshop/Photography/photography_pending',compact('list','totalproduct','category_name','color_name','done_product_count','remaning','rework_product_count'));
  
    }
    /*

pending photography pending ajax List
    */

    public function Pending_Ajax_list(Request $request){
         $data = array();
        $params = $request->post();
        $start = (!empty($params['start']) ? $params['start'] : 0);
        $length = (!empty($params['length']) ? $params['length'] : 10);
        $stalen = $start / $length;
        $curpage = $stalen;
        $maindata =$this->product;
        $where = '';
        $offset = '';
        $limit = '';
        $order = $params['order'][0]['column'];
        $order_direc = strtoupper($params['order'][0]['dir']);
        if ($order == "1") {
			$order_by = 'sku';
		} elseif ($order == "2") {
			$order_by = 'color';
        }
        elseif ($order == "3") {
			$order_by = 'category_id';
		} 
         else {
			$order_by = 'id';
		}

         if(!empty($params['skusearch']))
        {
            $maindata->where('sku','LIKE', '%' . $params['skusearch']. '%');
        }
       if(!empty($params['category'])){
        $maindata->where('category_id',$params['category']);
       }
       if(!empty($params['color'])){
        $maindata->where('color',$params['color']);
       }
       if(!empty($params['status'])){
        $maindata->where('status',$params['status']);
       }
        $datacount =$maindata->get()->count();
        $datacoll = $maindata;
             $data["recordsTotal"] = $datacount;
	  	    $data["recordsFiltered"] = $datacount;
            $data['deferLoading'] = $datacount;
            $datacollection = $datacoll->take($length)->offset($start)->orderBy($order_by, $order_direc)->get();
  
            $i=1;
             if(count($datacollection)>0){
                foreach($datacollection as $key=>$p){
                    $srno = $key + 1 + $start;
                    $c=photography_product::get_category_by_id($p->category_id);
                    $action='<select name="status" id="status" onchange="statuschange(this.value)" class="form-control" style="height:20px;width:150px;float: left;">
            <option value="2/'.$p->id.'/'.$p->category_id.'">Pending</option>
            <option value="1/'.$p->id.'/'.$p->category_id.'">In processing</option>
            <option value="3/'.$p->id.'/'.$p->category_id.'">Done</option>
        </select>';
                    $data['data'][] = array($srno,$p->sku, $p->color,$c, $action);
                }
               
            }else{
                $data['data'][] = array('', '', '', '','','');
          
            }
         echo json_encode($data);
        exit;
    }
     /*
    Photography done get data from this function
    */
    public function get_done_list()
    {
       $category_name=$this->category;
       $color_name=$this->color;
       $donelist=$this->product_list->where(['pro.status'=>3])->limit(10)->get();
       $totalproduct=$this->total;
       $done_product_count=$this->done;
       $rework_product_count=$this->pending;
      return view('Photoshop/Photography/photography_done',compact('donelist','category_name','color_name','totalproduct','done_product_count','rework_product_count'));
    }

    /*
    Fetch Data from photography department ajax done list
    */

    public function get_done_ajax__list(Request $request){
         $data=array();
        $params = $request->post();
        $params = $request->post();
		$start = (!empty($params['start']) ? $params['start'] : 0);
		$length = (!empty($params['length']) ? $params['length'] : 10);
		$stalen = $start / $length;
		$curpage = $stalen;
        $maindata =$this->product_list;
        $where = '';
        $offset = '';
        $limit = '';
        $order = $params['order'][0]['column'];
        $order_direc = strtoupper($params['order'][0]['dir']);
        if ($order == "1") {
            $order_by = 'p.sku';
           
		} elseif ($order == "2") {
            $order_by = 'p.color';
          
        }
        elseif ($order == "3") {
			$order_by = 'p.category_id';
		} 
         else {
			$order_by = 'p.sku';
		}
       if(!empty($params['skusearch'])){
            $maindata->where('p.sku','LIKE', '%' . $params['skusearch']. '%');
        }
       if(!empty($params['category'])){
        $maindata->where('p.category_id',$params['category']);
       }
       if(!empty($params['color'])){
        $maindata->where('p.color',$params['color']);
       }
            $totaldonecount1=$maindata->get()->count();
            $datacoll = $maindata;
            $data["recordsTotal"] =$totaldonecount1;
            $data["recordsFiltered"] =$totaldonecount1;
            $data['deferLoading'] = $totaldonecount1;
            $donecollection = $datacoll->take($length)->where(['pro.status'=>3])->offset($start)->orderBy($order_by, $order_direc)->get();
  
       if(count($donecollection)>0){
        foreach($donecollection as $key => $product)
        {  $srno = $key + 1 + $start;
           $action='<select name="status" id="status" onchange="donetorework(this.value)" class="form-control" style="height:20px;">
                     <option value="0">select status</option>
                     <option value="4/'.$product->product_id.'/'.$product->category_id.'">Rework</option>
                </select>'  ;
            $data['data'][] = array($srno,$product->sku, $product->color, $product->name, 'Done', $action);
        }

       
      }else{
        $data['data'][] = array('','','', '', '', '', '');
      }
          
      
         echo json_encode($data);exit;
    }

    /*
    Photography Rework Ajax List
    
    */
    public function get_rework_ajax_list(Request $request){
        $data=array();
        $params = $request->post();
        $params = $request->post();
		$start = (!empty($params['start']) ? $params['start'] : 0);
		$length = (!empty($params['length']) ? $params['length'] : 10);
		$stalen = $start / $length;
		$curpage = $stalen;
        $maindata = $this->product_list;
        $where = '';
        $offset = '';
        $limit = '';
        $order = $params['order'][0]['column'];
        $order_direc = strtoupper($params['order'][0]['dir']);
        if ($order == "1") {
			$order_by = 'p.sku';
		} elseif ($order == "2") {
			$order_by = 'p.color';
        }
        elseif ($order == "3") {
			$order_by = 'p.category_id';
		} 
         else {
			$order_by = 'p.sku';
		}
     if(!empty($params['skusearch'])){
            $maindata->where('p.sku','LIKE', '%' . $params['skusearch']. '%');
        }
       if(!empty($params['category'])){
        $maindata->where('p.category_id',$params['category']);
       }
       if(!empty($params['color'])){
            $maindata->where('p.color',$params['color']);
       }
            $datacoll = $maindata->where(['pro.status'=>4]);
            $data["recordsTotal"] =$datacoll->get()->count();
            $data["recordsFiltered"] = $datacoll->get()->count();
            $data['deferLoading'] = $datacoll->get()->count();
            $donecollection = $datacoll->take($length)->offset($start)->orderBy($order_by, $order_direc)->get();
       
      if(count($donecollection)>0){
        foreach($donecollection as $key => $product)
        {  $srno = $key + 1 + $start;
             $action='
                 <select name="status"id="status" onchange="reworktodone(this.value)" class="form-control" style="height:20px;">
                     <option value="0/'.$product->product_id.'/'.$product->category_id.'">select status</option>
                     <option value="3/'.$product->product_id.'/'.$product->category_id.'">Done</option>
                </select>';
           
            $data['data'][] = array($srno,$product->sku, $product->color, $product->name, 'Rework', $action);
        }

       
      }else{
        $data['data'][] = array('','','', '', '', '', '');
      }
          
      
         echo json_encode($data);exit;
    }
     /*
    Photography Rework get data from this function
    */
    public function get_rework_list()
    {
     
        $category_name=$this->category;
        $color_name=$this->color;
        $totalproduct=$this->total;
        $done_product_count=$this->done;
        $rework_product_count=$this->pending;
       $reworklist=$this->product_list->where(['pro.status'=>4])->get();
      return view('Photoshop/Photography/photography_rework',compact('reworklist','category_name','color_name','totalproduct','rework_product_count','doneproduct','done_product_count'));
    }

    /*
    photography pending submit button action
    get all detail from photography pending list 

    */

    public function pending_list_submit(Request $request)
    {
        $user=Auth::user();
       
        $photoshop=new photography();
        $photoshop->product_id=$request->input('product_id');
        $photoshop->category_id=$request->input('category_id');
        $photoshop->status=$request->input('status');
        $photoshop->current_status='1';
        $photoshop->next_department_status='0';
        
        $photoshop->created_by=$this->userid;
        $photoshop->work_assign_by="0";
        $photoshop->work_assign_user="0";
        $url= $request->url();
        $urllink= explode('Photoshop/',$url);
        $link= $urllink[1];
        $dep=explode("/",$link);
        $cache=array(
            'product_id'=>$request->input('product_id'),
            'action_name'=>$dep[0],
            'status'=>$request->input('status'),
            'action_by'=>$this->userid,
            "action_date_time"=>date('Y-m-d H:i:s')

        );
        if($request->input('status') !="2"){
            $photoshop->save();
            PhotoshopHelper::store_cache_table_data($cache);
          photography_product::update_product($request->input('product_id'));
          $message=config('constants.message.psd_done');
        }else{
            $message=config('constants.message.error');;  
        }
        
        return response()->json(['success'=>$message]);
 

 
    }
/*
done list submit for particular product change the photography status
done to rework 
*/
    public function submit_done_list(Request $request)
    {
        
      
        $url= $request->url();
      
        $urllink= explode('Photoshop/',$url);
        $link= $urllink[1];
        $dep=explode("/",$link);
        if($request->input('status') !='0')
        {
            //cache table data insert 
            $cache=array(
                'product_id'=>$request->input('product_id'),
                'action_name'=>$dep[0],
                'status'=>$request->input('status'),
                'action_by'=>$this->userid,
                'action_date_time'=>date('Y-m-d H:i:s')
    
            );
            PhotoshopHelper::store_cache_table_data($cache);
            photography::update_photography_status($request->get('product_id'),$request->input('status'));
            $message=config('constants.message.status');
       
            if($request->input('status')=='4')
         {
             photography::delete_from_below_department($request->input('product_id'));
             photography::getUpdatestatusdone($request->input('product_id'));
             $message=config('constants.message.status');
         }
         
        }
        
        return response()->json(['success'=>$message]);
        
        
    }
//Shoot in Photography Department

public function shoot(Request $request){
  $model_name=$request->model."_shoot_status";
 
$title=strtoupper($request->model);
$data=PhotoshopHelper::get_shoot_data();
$shoot_data=PhotoshopHelper::get_shoot_data_from_shoot_table($title);
$done=$shoot_data->where('s.status','=',3)->get();
$rework=PhotoshopHelper::get_shoot_data_from_shoot_table($title)->where('s.status','=',4)->get();
$pending=$data->where($model_name,'=',0)->get();
return view('Photoshop/Photography/shoot/index',compact('title','done','pending','rework'));
  
}
  public function shoot_action(Request $request){
     // $data=array(['model'=>$request->model,'status'=>$request->status]);
     $user=Auth::user();
       
     $shoot=new shootModel();
     $shoot->product_id=$request->pid;
     $shoot->category_id=$request->category_id;
     $shoot->status=$request->statusmode;
    $shoot->shootModule=$request->model;
    $shoot->created_by=$this->userid;
    $attribute=$request->attribute;
    $model=$request->model;
    $ss=$model."_shoot_status";
    $cache=array(
      'product_id'=>$request->pid,
      'action_name'=>$request->model,
      'status'=>$request->statusmode,
      'action_by'=>$this->userid,
      'action_date_time'=>date('Y-m-d H:i:s')

  );
 PhotoshopHelper::store_cache_table_data($cache);
 
      if($request->status=="rework"){
        $update=PhotoshopHelper:: updateshoottable($request->pid,$request->statusmode,$model);
        $message=$model."  ".config('constants.message.done');;
        $type="sucess";
      }
      if($request->status=="done"){
        $check=PhotoshopHelper::getShootData($request->pid,$model);
        if($check){
          $h=PhotoshopHelper:: updateshoottable($request->pid,$request->statusmode,$model);
         $message=$model."  ".config('constants.message.done');
         $type="sucess";
        }else{
          $shoot->save();
          $sta=PhotoshopHelper::updateshoot($ss,"1",$request->pid);
          $message=$model."  ".config('constants.message.done');
          $type="sucess";
        }
         }
       return response()->json(['success'=>$message,"type"=>$request->status]);
   
     
  }
}
