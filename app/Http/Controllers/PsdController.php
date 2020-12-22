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
      $this->photography=photography::getphotographyProduct();
      $this->psd=PhotoshopHelper::get_psd_product_list();
      $user=Auth::user();
      $this->product_list=PhotoshopHelper::get_psd_pending_product_list($this->userid);
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
      $psdpending= $this->product_list->where(['photographies.status'=>'3','photographies.next_department_status'=>'0'])->get();
   
      return view('Photoshop/PSD/psd_pending',compact('psdpending','categorylist','colorlist'));
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
        $maindata = PhotoshopHelper::get_photography_product_list();
        $where = '';
        $offset = '';
        $limit = '';
        $order = $params['order'][0]['column'];
        $order_direc = strtoupper($params['order'][0]['dir']);
               
     if(!empty($params['skusearch'])){
            $maindata->where('photography_products.sku','LIKE', '%' . $params['skusearch']. '%');
        }
       if(!empty($params['category'])){
        $maindata->where('photographies.category_id',$params['category']);
       }
       if(!empty($params['color'])){
        $maindata->where('photography_products.color',$params['color']);
       }
           $datacoll = $maindata->where(['photographies.status'=>3,'photographies.next_department_status'=>0])->orderBy('photographies.id','DESC');
            $data["recordsTotal"] =$datacoll->count();
            $data["recordsFiltered"] = $datacoll->count();
            $data['deferLoading'] = $datacoll->count();
            $csrf=csrf_field();
          
              
        $donecollection = $datacoll->take($length)->offset($start)->get();
       
      if(count($donecollection)>0){
        foreach($donecollection as $key => $product)
        {  $srno = $key + 1 + $start;
            $csrf=csrf_field();
        
            $token=$request->session()->token();
             $action='<form action="" method="post" style="margin-right: 110px;">
             '.$csrf.'
            <input type="hidden" value="'.$product->product_id.'" name="product_id" id="product_id"/>
             <input type="hidden" value="'.$product->category_id.'" name="category_id" />
                 <select name="status" id="status" class="form-control" style="height:20px;width:120px;float: left;">
                 <option value="2">Pending</option>
                 <option value="1">In processing</option>
                 <option value="3">Done</option>
                </select>
                <button type="submit" style="height: 30px;
                width: 30px;" class="btn btn-primary btn-circle"><i class="material-icons list-icon">check</i></button>
                    
             </form>';
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
      $psd_done_list=$this->psd->where(['psds.status'=>3])->orderBy('psds.id','DESC')->get();
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
          $maindata->where('photography_products.sku','LIKE', '%' . $params['skusearch']. '%');
      }
     if(!empty($params['category'])){
      $maindata->where('photography_products.category_id',$params['category']);
     }
     if(!empty($params['color'])){
      $maindata->where('photography_products.color',$params['color']);
     }
     
          $datacoll = $maindata->where(['psds.status'=>3])->orderBy('psds.id','DESC');
          $data["recordsTotal"] =$datacoll->count();
          $data["recordsFiltered"] = $datacoll->count();
          $data['deferLoading'] = $datacoll->count();
          $csrf=csrf_field();
        
            
      $donecollection = $datacoll->take($length)->offset($start)->get();
     
    if(count($donecollection)>0){
      foreach($donecollection as $key => $product)
      {  $srno = $key + 1 + $start;
          $csrf=csrf_field();
      
          $token=$request->session()->token();
           $action='<form action="" method="post" style="margin-right: 110px;">
           '.$csrf.'
          <input type="hidden" value="'.$product->product_id.'" name="product_id" id="product_id"/>
           <input type="hidden" value="'.$product->category_id.'" name="category_id" />
               <select name="status" id="status" class="form-control" style="height:20px;width:120px;float: left;">
             	<option value="0">select status</option>
											<option value="4">Rework</option>
									  </select>
              <button type="submit" style="height: 30px;
              width: 30px;" class="btn btn-primary btn-circle"><i class="material-icons list-icon">check</i></button>
                  
           </form>';
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
       $psd_rework=$this->psd->where(['psds.status'=>4])->get();
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
          $maindata->where('photography_products.sku','LIKE', '%' . $params['skusearch']. '%');
      }
     if(!empty($params['category'])){
      $maindata->where('photography_products.category_id',$params['category']);
     }
     if(!empty($params['color'])){
      $maindata->where('photography_products.color',$params['color']);
     }
     
          $datacoll = $maindata->where(['psds.status'=>4])->orderBy('psds.id','DESC');
          $data["recordsTotal"] =$datacoll->count();
          $data["recordsFiltered"] = $datacoll->count();
          $data['deferLoading'] = $datacoll->count();
          $csrf=csrf_field();
        
            
      $donecollection = $datacoll->take($length)->offset($start)->get();
     
    if(count($donecollection)>0){
      foreach($donecollection as $key => $product)
      {  $srno = $key + 1 + $start;
          $csrf=csrf_field();
      
          $token=$request->session()->token();
           $action='<form action="" method="post" style="margin-right: 110px;">
           '.$csrf.'
          <input type="hidden" value="'.$product->product_id.'" name="product_id" id="product_id"/>
           <input type="hidden" value="'.$product->category_id.'" name="category_id" />
               <select name="status" id="status" class="form-control" style="height:20px;width:120px;float: left;">
           	        	<option value="1">In Process</option>
											<option value="3">Done</option>
													  </select>
              <button type="submit" style="height: 30px;
              width: 30px;" class="btn btn-primary btn-circle"><i class="material-icons list-icon">check</i></button>
                  
           </form>';
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
            'action_by'=>"user",
            'action_date_time'=>date('Y-m-d H:i:s')
  
  
          );
           PhotoshopHelper::store_cache_table_data($cache);
           photography::getUpdatenextdepartmentdone($request->input('product_id'));
         }
        
      }
        return redirect()->back()->with('success', 'Psd status Change Successfull');
     
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
          'action_by'=>"user",
          'action_date_time'=>date('Y-m-d H:i:s')

      );
      PhotoshopHelper::store_cache_table_data($cache);
      psd::update_psd_status($request->get('product_id'),$request->input('status'));
       }
    if($request->input('status')=='4')
    {
       psd::delete_from_below_department($request->get('product_id'));
       psd::getUpdatestatus_psd($request->input('product_id'));
    }
       return redirect()->back()->with('success', 'Psd status Change Successfull');
    }

}
