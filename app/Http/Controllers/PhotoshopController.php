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
    public $category;
    public $color;
    public $product_list;
    public function __construct()
    {
        $this->product=photography_product::groupBy(['sku','color'])->where('status',0)->get();
        $this->photography=photography::getphotographyProduct();
        $this->category=category::all();
        $this->color=color::all();
        $this->product_list=PhotoshopHelper::get_photography_product_list();
       
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
        $list=photography_product::all()->take(50)->where('status', 0);
        $done_product_count=photography_product::where('status', 1)->count();
        $totalproduct=$this->product->count();  
        $remaning=count(photography_product::all());
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
        $maindata = photography_product::query();
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
        $datacount = $maindata->count();
        
         $datacoll = $maindata->groupBy(['sku','color'])->where('status','0');
        $datacollection = $datacoll->take($length)->offset($start)->get();
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
                    $action='
                    <form action="" method="POST">
                    '.$csrf.'
			<input type="hidden" value="'.$p->id.'" name="product_id"/>
			<input type="hidden" value="'.$p->category_id.'" name="category_id"/>
            <select name="status" onchange="statuschange(this.value)" class="form-control" style="height:20px;width:150px;float: left;">
            <option value="2">Pending</option>
            <option value="1">In processing</option>
            <option value="3">Done</option>
        </select>
				<button type="submit" style="height: 30px;
    width: 30px;"  class="btn btn-primary btn-circle"><i class="material-icons list-icon">check</i></button>
		
			</form>
                    ';
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
  
      $totalproduct= $this->product->count();
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
        $maindata = photography::query();
        $maindata->join('photography_products','photographies.product_id','photography_products.id');
        $maindata->join('categories','photographies.category_id','categories.entity_id');
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
            $csrf=csrf_field();
           $p=$product->getProduct;
           $ca=$product->category;
           $check='<div class="checkbox checkbox-primary" style="width: 100px;">
           <label>
           <input type="checkbox" class="chkProduct" value="'.$p->id.'" name="chkProduct" id="chkProduct"> <span class="label-text"></span>
           </label>
       </div>';
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
            $data['data'][] = array($srno,$p->sku, $p->color, $ca->name, 'Done', $action);
        }

       
      }else{
        $data['data'][] = array('','','', '', '', '', '');
      }
          
      
         echo json_encode($data);exit;
    }

    /*
    Photography Rework Ajax List
    
    */
    public function get_ajax_list(Request $request){
        $data=array();
        $params = $request->post();
        $params = $request->post();
		$start = (!empty($params['start']) ? $params['start'] : 0);
		$length = (!empty($params['length']) ? $params['length'] : 10);
		$stalen = $start / $length;
		$curpage = $stalen;
        $maindata = photography::query();
        $maindata->join('photography_products','photographies.product_id','photography_products.id');
        $maindata->join('categories','photographies.category_id','categories.entity_id');
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
            $csrf=csrf_field();
          
              
        $donecollection = $datacoll->take($length)->offset($start)->get();
       
      if(count($donecollection)>0){
        foreach($donecollection as $key => $product)
        {  $srno = $key + 1 + $start;
            $csrf=csrf_field();
           $p=$product->getProduct;
           $ca=$product->category;
           $check='<div class="checkbox checkbox-primary" style="width: 100px;">
           <label>
           <input type="checkbox" class="chkProduct" value="'.$p->id.'" name="chkProduct" id="chkProduct"> <span class="label-text"></span>
           </label>
       </div>';
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
            $data['data'][] = array($srno,$p->sku, $p->color, $ca->name, 'Done', $action);
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
        $photoshop->created_by="user";
        $url= $request->url();
        $urllink= explode('Photoshop/',$url);
        $link= $urllink[1];
        $cache=array(
            'product_id'=>$request->input('product_id'),
            'action_name'=>$link,
            'status'=>$request->input('status'),
            'action_by'=>"user",
            "action_date_time"=>date('Y-m-d H:i:s')

        );
        if($request->input('status') !="2"){
            $photoshop->save();
            PhotoshopHelper::store_cache_table_data($cache);
          photography_product::update_product($request->input('product_id'));
          $message="Photoshop Done Change Successfull";
        }else{
            $message="Please Select The Staus";  
        }
        
  
 return  redirect('Photoshop/Photography/pending')->with('message',$message);
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
      
        if($request->input('status') !='0')
        {
            //cache table data insert 
            $cache=array(
                'product_id'=>$request->input('product_id'),
                'action_name'=>$link,
                'status'=>$request->input('status'),
                'action_by'=>"user",
                'action_date_time'=>date('Y-m-d H:i:s')
    
            );
            PhotoshopHelper::store_cache_table_data($cache);
            photography::update_photography_status($request->get('product_id'),$request->input('status'));
         if($request->input('status')=='4')
         {
             photography::delete_from_below_department($request->input('product_id'));
             photography::getUpdatestatusdone($request->input('product_id'));
             
         }
         
            return redirect()->back()->with('success', 'Photography status Change Successfull');
        }
        else{
            return redirect()->back()->with('success', 'Select the photography status');
        }
  
        
        
    }

  
}
