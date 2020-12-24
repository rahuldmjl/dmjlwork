<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\PhotoshopHelper;
use App\photography;
use App\psd;
use App\productListModel;
use App\photography_product;
use DB;
use Auth;
use App\category;
use App\color;
class PsdController extends Controller
{
  
 
  public $photography;
  public $psd;
  public $user;
  public $product_list;
  public $userid;
  public function __construct()
  {
      $this->userid="1";
      $user=Auth::user();
      $this->product_list=PhotoshopHelper::get_photoshop_product_list_user("photographies",$this->userid);
      $this->psd=PhotoshopHelper::get_photoshop_product_list('psds',$this->userid);
      $this->color=color::all();
      $this->category=category::all();
  }
    public function index()
    {
        return view('Photoshop/PSD/index');
    }
    /*
    Get Pending List 
    this list come from photography done option
    */
    public function get_psd_pending_list()
    {
        $categorylist=$this->category;
         $colorlist=$this->color;
         $totalproduct= PhotoshopHelper::getCountAllDepartment("photographies",$this->userid,3);
        $doneproduct= PhotoshopHelper::getCountAllDepartment("psds",$this->userid,3);
        $pending=$totalproduct-$doneproduct;
        $psdpending= $this->product_list->where(['pro.status'=>'3','pro.next_department_status'=>0])->limit(10)->get();
   
        return view('Photoshop/PSD/psd_pending',compact('psdpending','categorylist','colorlist','totalproduct','doneproduct','pending'));
    }
    /*
    Get Pending Ajax List 
    */

public function get_Ajax_pendingList(Request $request){
  
        $data=array();
        $params = $request->post();
        $params = $request->post();
		$start = (!empty($params['start']) ? $params['start'] : 0);
		$length = (!empty($params['length']) ? $params['length'] : 10);
		$stalen = $start / $length;
		$curpage = $stalen;
        $maindata =$this->product_list->where(['pro.status'=>'3','pro.next_department_status'=>0]);
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
           $datacoll = $maindata->get()->count();
            $data["recordsTotal"] =$datacoll;
            $data["recordsFiltered"] = $datacoll;
            $data['deferLoading'] = $datacoll;
         $donecollection = $maindata->take($length)->offset($start)->get();
       if(count($donecollection)>0){
        foreach($donecollection as $key => $product)
        {  $srno = $key + 1 + $start;
            $action='<select name="status" id="status" onchange="psdpendingtodone(this.value)" class="form-control" style="height:20px;width:120px;float: left;">
                 <option value="2/'.$product->product_id.'/'.$product->category_id.'">Pending</option>
                 <option value="1'.$product->product_id.'/'.$product->category_id.'">In processing</option>
                 <option value="3'.$product->product_id.'/'.$product->category_id.'">Done</option>
             ';
            $data['data'][] = array($srno,$product->sku, $product->color, $product->name, $action);
        }

       
      }else{
        $data['data'][] = array('','','', '', '', '');
      }
          
      
         echo json_encode($data);exit;
}

    /*
    Get done List 
    this list come from psd  done option
    */
    public function get_psd_done_list()
    {
      $categorylist=$this->category;
      $colorlist=$this->color;
      $psd_done_list=$this->psd->where(['pro.status'=>3])->orderBy('pro.id','DESC')->get();
      return view('Photoshop/PSD/psd_done',compact('psd_done_list','categorylist','colorlist'));
    }

    /*
    get Done Ajax List
    */

    public function get_ajax_done_list(Request $request){
      $data=array();
      $params = $request->post();
      $params = $request->post();
  $start = (!empty($params['start']) ? $params['start'] : 0);
  $length = (!empty($params['length']) ? $params['length'] : 10);
  $stalen = $start / $length;
  $curpage = $stalen;
      $maindata=$this->psd;
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
     
          $datacoll = $maindata->where(['pro.status'=>3])->get()->count();
          $data["recordsTotal"] =$datacoll;
          $data["recordsFiltered"] = $datacoll;
          $data['deferLoading'] = $datacoll;
          $csrf=csrf_field();
        
            
      $donecollection = $maindata->take($length)->offset($start)->orderBy('pro.id','DESC')->get();
     
    if(count($donecollection)>0){
      foreach($donecollection as $key => $product)
      {  $srno = $key + 1 + $start;
           $action='  <select name="status" onchange="donetorework(this.value)" class="form-control" style="height:20px;width:120px;float: left;">
             	<option value="0/'.$product->product_id.'/'.$product->category_id.'">select status</option>
											<option value="4/'.$product->product_id.'/'.$product->category_id.'">Rework</option>
									  </select> ';
            
          $data['data'][] = array($srno,$product->sku, $product->color, $product->name, $action);
      }

     
    }else{
      $data['data'][] = array('','','', '', '', '');
    }
        
    
       echo json_encode($data);exit;
    }
      /*
    Get rework List 
    this list come from psd  rework option
    */
    public function get_psd_rework_list()
    {    $categorylist=$this->category;
      $colorlist=$this->color;
       $psd_rework=$this->psd->where(['pro.status'=>4])->get();
        return view('Photoshop/PSD/psd_rework',compact('psd_rework','categorylist','colorlist'));
    }

    /*
    Get PSd rework Ajax List 
    */
    public function get_psd_rework_ajaxList(Request $request){
      $data=array();
      $params = $request->post();
      $params = $request->post();
  $start = (!empty($params['start']) ? $params['start'] : 0);
  $length = (!empty($params['length']) ? $params['length'] : 10);
  $stalen = $start / $length;
  $curpage = $stalen;
      $maindata=$this->psd;
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
     
          $datacoll = $maindata->where(['pro.status'=>4])->orderBy('pro.id','DESC');
          $data["recordsTotal"] =$datacoll->count();
          $data["recordsFiltered"] = $datacoll->count();
          $data['deferLoading'] = $datacoll->count();
          $csrf=csrf_field();
        
            
      $donecollection = $datacoll->take($length)->offset($start)->get();
     
    if(count($donecollection)>0){
      foreach($donecollection as $key => $product)
      {  $srno = $key + 1 + $start;
            $action='<select name="status"  onchange="reworktodone(this.value)" class="form-control" style="height:20px;width:120px;float: left;">
           	        	<option value="1/'.$product->product_id.'/'.$product->category_id.'">In Process</option>
											<option value="3/'.$product->product_id.'/'.$product->category_id.'">Done</option>
											</select>';
          $data['data'][] = array($srno,$product->sku, $product->color, $product->name, $action);
      }

     
    }else{
      $data['data'][] = array('','','', '', '', '');
    }
        
    
       echo json_encode($data);exit;
    }
    /* Get All Data from ppending From psd Department
    Submit Pending Data into post method
    */

    public function get_data_from_psd_pending_list(Request $request)
    {
       $photoshop=new psd();
      if($request->input('status') !="1")
      {
          

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
         //Cache table data Insert
         if($request->input('status')=='3')
         {
          $photoshop->save();
          $cache=array(
            'product_id'=>$request->input('product_id'),
            'action_name'=>$link,
            'status'=>$request->input('status'),
            'action_by'=> $this->userid,
            'action_date_time'=>date('Y-m-d H:i:s')
  
  
          );
           PhotoshopHelper::store_cache_table_data($cache);
           photography::getUpdatenextdepartmentdone($request->input('product_id'));
        $message="Psd Status Change Successfull";
          }
        
      }
      return response()->json(['success'=>$message]);
       
    }

    public function submit_done_list(Request $request)
    {
      $url= $request->url();
      $urllink= explode('Photoshop/',$url);
      $link= $urllink[1];
        $psd=psd::find($request->input('id'));
       if($request->input('status') !='0')
       {
        $cache=array(
          'product_id'=>$request->input('product_id'),
          'action_name'=>$link,
          'status'=>$request->input('status'),
          'action_by'=> $this->userid,
          'action_date_time'=>date('Y-m-d H:i:s')

      );
      PhotoshopHelper::store_cache_table_data($cache);
      psd::update_psd_status($request->get('product_id'),$request->input('status'));
      $message='Psd status Change Successfull';
    }
    if($request->input('status')=='4')
    {
       psd::delete_from_below_department($request->get('product_id'));
       psd::getUpdatestatus_psd($request->input('product_id'));
       $message='Psd status Change Successfull';
    }
      
      return response()->json(['success'=>$message]);
    }
 
}
