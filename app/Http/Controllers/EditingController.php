<?php

namespace App\Http\Controllers;
use App\Helpers\PhotoshopHelper;
use Illuminate\Http\Request;
use App\EditingModel;
use App\psd;
use Auth;
use App\category;
use App\color;
use App\Placement;
class EditingController extends Controller
{
    //get all Pending list of Editing Department
    public $product;
    public $psd;
    public $editing_pending_list;
    public $editing_product;
   public $category;
   public $color;
   public $userid;
    public function __construct()
    {
       $this->userid=1;
        $user=Auth::user();
        $this->editing_pending_list=PhotoshopHelper::get_photoshop_product_list_user("placements",$this->userid);
        $this->editing_product=PhotoshopHelper::get_photoshop_product_list('editing_models',$this->userid);;
        $this->category=category::all();
        $this->color=color::all();
    }
    
    public function get_pending_list_editing()
    {
        $categorylist=$this->category;
        $colorlist=$this->color;
        $pending_list= $this->editing_pending_list->where(['pro.status'=>3,'pro.next_department_status'=>0])->get(); 
        return view('Photoshop/Editing/editing_pending',compact('pending_list','categorylist','colorlist'));
    }

    //Get Ajax Pending List

    public function get_pending_Ajax_list(Request $request){
      $data = array();
      $params = $request->post();
      $start = (!empty($params['start']) ? $params['start'] : 0);
      $length = (!empty($params['length']) ? $params['length'] : 10);
      $stalen = $start / $length;
      $curpage = $stalen;
      $maindata = $this->editing_pending_list;
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
      $datacoll = $maindata->where(['pro.status'=>3,'pro.next_department_status'=>0]);
          $data["recordsTotal"] = $datacount;
      $data["recordsFiltered"] = $datacount;
      $data['deferLoading'] = $datacount;
           $datacollection = $datacoll->take($length)->offset($start)->get();
            if(count($datacollection)>0){
              foreach($datacollection as $key=>$p){
                  $srno = $key + 1 + $start;
                  $action='<select name="status" onchange="pendinttodone(this.value)" class="form-control" style="height:20px;width:150px;float: left;">
              <option value="2/'.$p->id.'/'.$p->category_id.'">Pending</option>
              <option value="1/'.$p->id.'/'.$p->category_id.'">In processing</option>
              <option value="3/'.$p->id.'/'.$p->category_id.'">Done</option>
          </select>';
               
                  $data['data'][] = array($srno,$p->sku, $p->color,$p->name,"pending", $action);
              }
             
          }else{
              $data['data'][] = array('', '', '', '','','');
        
          }
       echo json_encode($data);
      exit;
    }
   //get all done list of Editing Department

    public function get_done_list_editng()
    {
       
      
        $categorylist=$this->category;
        $colorlist=$this->color;
        $done_list=$this->editing_product->where(['pro.status'=>3])->get();
         return view('Photoshop/Editing/editing_done',compact('done_list','categorylist','colorlist'));

    }

    //Get Editing Done Ajax List
    public function get_done_AjaxList(Request $request){
      $data = array();
      $params = $request->post();
      $start = (!empty($params['start']) ? $params['start'] : 0);
      $length = (!empty($params['length']) ? $params['length'] : 10);
      $stalen = $start / $length;
      $curpage = $stalen;
      $maindata = $this->editing_product;
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
      $datacoll = $maindata;
          $data["recordsTotal"] = $datacount;
      $data["recordsFiltered"] = $datacount;
      $data['deferLoading'] = $datacount;
          $csrf=csrf_field();
        
          $datacollection = $datacoll->take($length)->offset($start)->get();
          
           if(count($datacollection)>0){
              foreach($datacollection as $key=>$p){
                  $srno = $key + 1 + $start;
                  $action='<select name="status" onchange="donetorework(this.value)" class="form-control" style="height:20px;width:150px;float: left;">
              <option value="0/'.$p->product_id.'/'.$p->category_id.'">select status</option>
              <option value="4/'.$p->product_id.'/'.$p->category_id.'">Rework</option>
            </select> ';
               
                  $data['data'][] = array($srno,$p->sku, $p->color,$p->name,"Done", $action);
              }
             
          }else{
              $data['data'][] = array('', '', '', '','','');
        
          }
       echo json_encode($data);
      exit;
    }
    public function get_rework_list_editing()
    {
        $categorylist=$this->category;
        $colorlist=$this->color;
        $editing_rework_list=$this->editing_product->where(['pro.status'=>4])->get();
       return view('Photoshop/Editing/editing_rework',compact('editing_rework_list','categorylist','colorlist'));
    }

    //Rework AJax List
    public function get_rework_ajaxList(Request $request){
      $data = array();
      $params = $request->post();
      $start = (!empty($params['start']) ? $params['start'] : 0);
      $length = (!empty($params['length']) ? $params['length'] : 10);
      $stalen = $start / $length;
      $curpage = $stalen;
      $maindata =$this->editing_product;
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
      $datacoll = $maindata;
          $data["recordsTotal"] = $datacount;
      $data["recordsFiltered"] = $datacount;
      $data['deferLoading'] = $datacount;
          $csrf=csrf_field();
        
          $datacollection = $datacoll->take($length)->offset($start)->get();
          
           if(count($datacollection)>0){
              foreach($datacollection as $key=>$p){
                  $srno = $key + 1 + $start;
                  $action=' <select name="status" onchange="reworktodone(this.value)" class="form-control" style="height:20px;width:150px;float: left;">
              <option value="0/'.$p->product_id.'/'.$p->category_id.'">select status</option>
              <option value="3/'.$p->product_id.'/'.$p->category_id.'">Done</option>
                           </select>
          ';
               
                  $data['data'][] = array($srno,$p->sku, $p->color,$p->name,"Rework", $action);
              }
             
          }else{
              $data['data'][] = array('', '', '', '','','');
        
          }
       echo json_encode($data);
      exit;
    }

    public function get_pending_submit_editing(Request $request)
    {
        $url= $request->url();
      $urllink= explode('Photoshop/',$url);
      $link= $urllink[1];
      $dep=explode("/",$link);
      $editing=new EditingModel();
      if($request->input('status') !="1")
      {
          

          $editing->product_id=$request->input('product_id');

          $editing->category_id=$request->input('category_id');
          $editing->status=$request->input('status');
          $editing->current_status='1';
          $editing->next_department_status='0';
         
          $editing->created_by=$this->userid;
          $editing->work_assign_by="0";
          $editing->work_assign_user="0";
         //Cache table data Insert
         if($request->input('status')=='3')
         {
          $editing->save();
          $cache=array(
            'product_id'=>$request->input('product_id'),
            'action_name'=>$dep[0],
            'status'=>$request->input('status'),
            'action_by'=>$this->userid,
            'action_date_time'=>date('Y-m-d H:i:s')
  
          );
           PhotoshopHelper::store_cache_table_data($cache);
           EditingModel::getUpdatestatusdone($request->input('product_id'));
           $message="Editing status Change Successfull";
         }
         return response()->json(['success'=>$message]);
   
      }
        
     
      //  return redirect()->back()->with($message);
    }

    public function submit_done_list_editng(Request $request)
    {
        $url= $request->url();
        $urllink= explode('Photoshop/',$url);
        $link= $urllink[1];
        $dep=explode("/",$link);
     if($request->input('status')=='0')
     {
      
        $message="Editing Select Status";
           
     }
     else{


        $cache=array(
            'product_id'=>$request->input('product_id'),
            'action_name'=>$dep[0],
            'status'=>$request->input('status'),
            'action_by'=>$this->userid,
            'action_date_time'=>date('Y-m-d H:i:s')
  
        );
       PhotoshopHelper::store_cache_table_data($cache);
       EditingModel::update_editing_status($request->get('product_id'),$request->input('status'));
       $message="Editing Rework Successfull";
       if($request->input('status')=='4')
       {
        EditingModel::getUpdatestatusrework($request->input('product_id'));
        EditingModel::delete_from_jpeg_List($request->input('product_id'));
       }
       $message="Editing Rework Successfull";
      
     }
    return redirect()->back()->with($message);   
    }

}
