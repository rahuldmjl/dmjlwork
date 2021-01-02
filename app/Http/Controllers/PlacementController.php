<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\psd;
use Auth;
use DB;
use App\category;
use App\color;
use App\Placement;
use App\Helpers\PhotoshopHelper;
class PlacementController extends Controller
{
   
    public $psd;
    public $user;
    public $placement;
    public $category;
    public $color;
    public $product;
    public $userid;
    public function __construct()
    {
        $this->userid="1";
         $this->category=category::all();
         $this->color=color::all();
         $user=Auth::user();
        $this->placement=Placement::all();
        $this->psd=PhotoshopHelper::get_photoshop_product_list('psds',$this->userid);
        $this->placement=PhotoshopHelper::get_photoshop_product_list('placements',$this->userid);
     
       
    }
   //get Placement Pending List
    public function get_placement_pending_list(){
        $cateorylist=$this->category;
        $colorlist=$this->color;
        $list=$this->psd->where(['pro.status'=>3,'pro.next_department_status'=>0])->orderBy('pro.id','DESC')->get();
       return View('Photoshop/Placement/placement_pending',compact('list','cateorylist','colorlist'));

    }


    public function pending_Ajax_list(Request $request){
    $data = array();
    $params = $request->post();
    $start = (!empty($params['start']) ? $params['start'] : 0);
    $length = (!empty($params['length']) ? $params['length'] : 10);
    $stalen = $start / $length;
    $curpage = $stalen;
    $maindata =$this->psd;
    $where = '';
    $offset = '';
    $limit = '';
    $order = $params['order'][0]['column'];
    $order_direc = strtoupper($params['order'][0]['dir']);
   if(!empty($params['skusearch']))
    {
        $maindata->where('p.sku','LIKE', '%' . $params['skusearch']. '%');
    }
   if(!empty($params['category']))
   {
    $maindata->where('p.category_id',$params['category']);
   }
   if(!empty($params['color']))
   {
    $maindata->where('p.color',$params['color']);
   }
   
        $datacount = $maindata->where(['pro.status'=>3,'pro.next_department_status'=>0])->get()->count();
		$datacoll = $maindata->where(['pro.status'=>3,'pro.next_department_status'=>0]);
        $data["recordsTotal"] = $datacount;
		$data["recordsFiltered"] = $datacount;
		$data['deferLoading'] = $datacount;
        $datacollection = $datacoll->take($length)->offset($start)->get();
        
         if(count($datacollection)>0){
            foreach($datacollection as $key=>$p){
                $srno = $key + 1 + $start;
                $action='<select name="status" onchange="placementdone(this.value)" class="form-control" style="height:20px;width:150px;float: left;">
            <option value="2/'.$p->product_id.'/'.$p->category_id.'">Pending</option>
            <option value="1/'.$p->product_id.'/'.$p->category_id.'">In processing</option>
            <option value="3/'.$p->product_id.'/'.$p->category_id.'">Done</option>
        </select>
				';
             
                $data['data'][] = array($srno,$p->sku, $p->color,$p->name, $action);
            }
           
        }else{
            $data['data'][] = array('', '', '', '','','');
      
        }
     echo json_encode($data);
    exit;
    }
     /*
    Get Done  List
    */

    public function get_placement_done_list()
    {
        $cateorylist=$this->category;
        $colorlist=$this->color;
        $done_list=$this->placement->where(['pro.status'=>3])->get();
        return View('Photoshop/Placement/placement_done',compact('done_list','cateorylist','colorlist'));
    }
    /*
    Get Done Ajax List
    */

    public function done_ajax_list(Request $request){
        $data = array();
        $params = $request->post();
        $start = (!empty($params['start']) ? $params['start'] : 0);
        $length = (!empty($params['length']) ? $params['length'] : 10);
        $stalen = $start / $length;
        $curpage = $stalen;
        $maindata =$this->placement;
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
            $datacoll = $maindata->where(['pro.status'=>3])->orderBy('pro.id','DESC');
            $data["recordsTotal"] = $datacount;
            $data["recordsFiltered"] = $datacount;
            $data['deferLoading'] = $datacount;
           
          
            $datacollection = $datacoll->take($length)->offset($start)->get();
            
             if(count($datacollection)>0){
                foreach($datacollection as $key=>$p){
                    $srno = $key + 1 + $start;
                    $action='<select name="status" class="form-control" onchange="donetorework(this.value)" style="height:20px;width:150px;float: left;">
                              <option value="0/'.$p->product_id.'/'.$p->category_id.'">select status</option>
                              <option value="4/'.$p->product_id.'/'.$p->category_id.'">Rework</option>
                             </select>';
                 
                    $data['data'][] = array($srno,$p->sku, $p->color,$p->name, $action);
                }
               
            }else{
                $data['data'][] = array('', '', '', '','','');
          
            }
         echo json_encode($data);
        exit;
    }

    public function get_placement_rework_list()
    {
       
       $rework_list=$this->placement->where(['pro.status'=>4])->get();;
       $cateorylist=$this->category;
        $colorlist=$this->color;
        return View('Photoshop/Placement/placement_rework',compact('rework_list','cateorylist','colorlist'));
    }


    /*
Get Rework Ajax List
    */
    public function rework_ajax_list(Request $request){
        $data = array();
        $params = $request->post();
        $start = (!empty($params['start']) ? $params['start'] : 0);
        $length = (!empty($params['length']) ? $params['length'] : 10);
        $stalen = $start / $length;
        $curpage = $stalen;
        $maindata =$this->placement;
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
            $datacoll = $maindata->where(['pro.status'=>4])->orderBy('pro.id','DESC');
            $data["recordsTotal"] = $datacount;
            $data["recordsFiltered"] = $datacount;
            $data['deferLoading'] = $datacount;
            $csrf=csrf_field();
          
            $datacollection = $datacoll->take($length)->offset($start)->get();
            
             if(count($datacollection)>0){
                foreach($datacollection as $key=>$p){
                    $srno = $key + 1 + $start;
                    $action='<select name="status" onchange="reworktodone(this.value)" class="form-control" style="height:20px;width:150px;float: left;">
                <option value="">Select Status</option>
                <option value="1/'.$p->id.'/'.$p->category_id.'">Done</option>
         </select>';
                 
                    $data['data'][] = array($srno,$p->sku, $p->color,$p->name, $action);
                }
               
            }else{
                $data['data'][] = array('', '', '', '','','');
          
            }
         echo json_encode($data);
        exit;
    }
    public function get_pending_list_data_submit(Request $request)
    {
        $user=Auth::user();
        $placement_data=new Placement();
        if($request->input('status') !="1")
        {
           $placement_data->product_id=$request->input('product_id');
           $placement_data->category_id=$request->input('category_id');
            $placement_data->status=$request->input('status');
            $placement_data->current_status=1;
            $placement_data->next_department_status=0;
            $placement_data->created_by=$this->userid;
            $placement_data->work_assign_by=0;
            $placement_data->work_assign_user=0;
            $url= $request->url();
             $urllink= explode('Photoshop/',$url);
             $link= $urllink[1];
             $dep=explode("/",$link);
           //Cache table data Insert
           if($request->input('status')=='3')
           {
            $placement_data->save();
            $cache=array(
                'product_id'=>$request->input('product_id'),
                'action_name'=> $dep[0],
                'status'=>$request->input('status'),
                'action_by'=>$this->userid,
                'action_date_time'=>date('Y-m-d H:i:s')
    
    
            );
             PhotoshopHelper::store_cache_table_data($cache);
             placement::getUpdatestatusdone($request->input('product_id'));
             $message=config('constants.message.status');;
           }
           return response()->json(['success'=>$message]); 
           
        }
        
    }

    public function submit_done_list(Request $request)
    {
        $user=Auth::user();
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
           placement::update_placement_status($request->input('product_id'),$request->input('status'));
   
           if($request->input('status')=='4')
    {
         placement::delete_from_editing($request->input('product_id'));
   
         placement::delete_from_jpeg($request->input('product_id'));
          placement::getUpdatestatus_JPEG($request->input('product_id'));
    
      
    }
    $message=config('constants.message.status');;
         
        }
        return response()->json(['success'=>$message]);     
}
}
