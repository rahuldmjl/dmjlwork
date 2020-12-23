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
class PhotoshopController extends Controller
{
    public $product;
    public $photography;
    public  $category;
    public $color;
    public $product_list;
    public $userid;
    public function __construct()
    {
        $this->userid="1";
        $this->product=PhotoshopHelper::get_product_list($this->userid)->where('status',0)->groupBy(['sku','color']);
        $this->category=category::all();
        $this->color=color::all();
        $this->product_list=PhotoshopHelper::get_photography_product_list($this->userid);
        
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
         $done_product_count=PhotoshopHelper::get_count_product("photography_product","1",$this->userid);
         $totalproduct=PhotoshopHelper::get_count_product("photography_product","0",$this->userid);
        $remaning=$totalproduct-$done_product_count;
        return view('Photoshop/Photography/photography_pending',compact('list','totalproduct','category_name','color_name','done_product_count','remaning'));
  
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
        $datacount = count($maindata->where('status',0)->get());
        $datacoll = $maindata;
        $datacollection = $datacoll->where('status',0)->take($length)->offset($start)->get();
            $csrf=csrf_field();
            $data["recordsTotal"] = $datacount;
	  	    $data["recordsFiltered"] = $datacount;
            $data['deferLoading'] = $datacount;
            $token=$request->session()->token();
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
       $donelist=$this->product_list->where(['photographies.status'=>3])->get();
       $totalproduct= PhotoshopHelper::get_count_product("photography_product","1",$this->userid);;
       $doneproduct=$donelist->count();
     
  return view('Photoshop/Photography/photography_done',compact('donelist','category_name','color_name','totalproduct','doneproduct'));
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
               
     if(!empty($params['skusearch'])){
            $maindata->where('photography_products.sku','LIKE', '%' . $params['skusearch']. '%');
        }
       if(!empty($params['category'])){
        $maindata->where('photography_products.category_id',$params['category']);
       }
       if(!empty($params['color'])){
        $maindata->where('photography_products.color',$params['color']);
       }
       
            $datacoll = $maindata->where(['photographies.status'=>3])->orderBy('photographies.id','DESC');
            $data["recordsTotal"] =$datacoll->count();
            $data["recordsFiltered"] = $datacoll->count();
            $data['deferLoading'] = $datacoll->count();
            $csrf=csrf_field();
          
              
        $donecollection = $datacoll->take($length)->offset($start)->get();
       
      if(count($donecollection)>0){
        foreach($donecollection as $key => $product)
        {  $srno = $key + 1 + $start;
           $action='<select name="status" id="status" onchange="donetorework(this.value)" class="form-control" style="height:20px;width:120px;float: left;">
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
               
     if(!empty($params['skusearch'])){
            $maindata->where('photography_products.sku','LIKE', '%' . $params['skusearch']. '%');
        }
       if(!empty($params['category'])){
        $maindata->where('photography_products.category_id',$params['category']);
       }
       if(!empty($params['color'])){
        $maindata->where('photography_products.color',$params['color']);
       }
         $datacoll = $maindata->where(['photographies.status'=>4])->orderBy('photographies.id','DESC');
            $data["recordsTotal"] =$datacoll->count();
            $data["recordsFiltered"] = $datacoll->count();
            $data['deferLoading'] = $datacoll->count();
            $donecollection = $datacoll->take($length)->offset($start)->get();
       
      if(count($donecollection)>0){
        foreach($donecollection as $key => $product)
        {  $srno = $key + 1 + $start;
             $action='
                 <select name="status"id="status" onchange="reworktodone(this.value)" class="form-control" style="height:20px;width:120px;float: left;">
                     <option value="0/'.$product->product_id.'/'.$product->category_id.'">select status</option>
                     <option value="3/'.$product->product_id.'/'.$product->category_id.'">Done</option>
                </select>';
           
            $data['data'][] = array($srno,$product->sku, $product->color, $product->name, 'Done', $action);
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
    
       $reworklist=$this->product_list->where(['photographies.status'=>4])->get();
  
    // $reworklist=collect($this->photography)->where('status','=',4);
      return view('Photoshop/Photography/photography_rework',compact('reworklist','category_name','color_name'));
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
          $message="Photography Status  Change Successfull";
        }else{
            $message="Please Select The Staus";  
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
            $message="Photography Status Change Successfull";
       
            if($request->input('status')=='4')
         {
             photography::delete_from_below_department($request->input('product_id'));
             photography::getUpdatestatusdone($request->input('product_id'));
             $message="Photography product Done to Rework";
         }
         
        }
        
        return response()->json(['success'=>$message]);
        
        
    }

  
}
