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
    public function __construct()
    {
       //Unique Category List
       $this->category=category::all();
       //Unique Color List 
       $this->color=color::all();
       
        $user=Auth::user();
        $this->placement=Placement::all();
        $this->psd=PhotoshopHelper::get_psd_product_list();

        //Placement table data fetch

        $this->product=PhotoshopHelper::get_placement_product_detail();
       
    }
   //get Placement Pending List
    public function get_placement_pending_list(){
       
           $list=$this->psd->where('psds.status',3)
            ->where('psds.next_department_status',0)
            ->limit(10)
            ->orderBy('psds.id','DESC')
            ->get();
        $cateorylist=$this->category;
        $colorlist=$this->color;
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
           

    if(!empty($params['skusearch'])){
        $maindata->where('photography_products.sku','LIKE', '%' . $params['skusearch']. '%');
    }
   if(!empty($params['category'])){
    $maindata->where('photography_products.category_id',$params['category']);
   }
   if(!empty($params['color'])){
    $maindata->where('photography_products.color',$params['color']);
   }
   
        $datacount = $maindata->count();
		$datacoll = $maindata->where(['psds.next_department_status'=>0,'psds.status'=>3])->orderBy('psds.id','DESC');
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
       $this->product->where(['placements.status'=>3]);
       $done_list=$this->product->get();
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
        $maindata = Placement::query();
        $maindata->join('photography_products','placements.product_id','photography_products.id');
        $maindata->join('categories','placements.category_id','categories.entity_id');
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
       
            $datacount = $maindata->count();
            $datacoll = $maindata->where(['placements.status'=>3])->orderBy('placements.id','DESC');
            $data["recordsTotal"] = $datacount;
            $data["recordsFiltered"] = $datacount;
            $data['deferLoading'] = $datacount;
            $csrf=csrf_field();
          
            $datacollection = $datacoll->take($length)->offset($start)->get();
            
             if(count($datacollection)>0){
                foreach($datacollection as $p){
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
       
        $this->product->where(['placements.status'=>4]);
       $rework_list=$this->product->get();
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
        $maindata = Placement::query();
        $maindata->join('photography_products','placements.product_id','photography_products.id');
        $maindata->join('categories','placements.category_id','categories.entity_id');
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
       
            $datacount = $maindata->count();
            $datacoll = $maindata->where(['placements.status'=>4])->orderBy('placements.id','DESC');
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
                <option value="">Select Status</option>
                <option value="0">Pending</option>
                <option value="1">Done</option>
         </select>
                    <button type="submit" style="height: 30px;
        width: 30px;"  class="btn btn-primary btn-circle"><i class="material-icons list-icon">check</i></button>
            
                </form>
                        ';
                 
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
            $placement_data->current_status='1';
            $placement_data->next_department_status='0';
            $url= $request->url();
            $urllink= explode('Photoshop/',$url);
            $link= $urllink[1];
           //Cache table data Insert
           if($request->input('status')=='3')
           {
            $placement_data->save();
            $cache=array(
                'product_id'=>$request->input('product_id'),
                'action_name'=>$link,
                'status'=>$request->input('status'),
                'action_by'=>"user",
                'action_date_time'=>date('Y-m-d H:i:s')
    
    
            );
             PhotoshopHelper::store_cache_table_data($cache);
             placement::getUpdatestatusdone($request->input('product_id'));
           }
          
        }
        return redirect()->back()->with('success', 'Psd status Change Successfull');
     
    }

    public function submit_done_list(Request $request)
    {
        $user=Auth::user();
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
           placement::update_placement_status($request->input('product_id'),$request->input('status'));
    if($request->input('status')=='4')
    {
         placement::delete_from_editing($request->input('product_id'));
   
         placement::delete_from_jpeg($request->input('product_id'));
          placement::getUpdatestatus_JPEG($request->input('product_id'));
    
      
    }
    
        }
return redirect()->back()->with('success', 'Psd status Change Successfull');
    
}
}
