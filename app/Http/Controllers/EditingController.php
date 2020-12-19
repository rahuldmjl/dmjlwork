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
    public function __construct()
    {
       
        $user=Auth::user();
        $this->editing_pending_list=PhotoshopHelper::get_placement_product_detail();
        $this->editing_product=PhotoshopHelper::get_editing_product_list();
        $this->category=category::all();
        $this->color=color::all();
    }
    
    public function get_pending_list_editing()
    {
        $categorylist=$this->category;
        $colorlist=$this->color;
        $pending_list=$this->editing_pending_list->where(['placements.status'=>3,'next_department_status'=>0])->get();  
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
      $maindata = Placement::query();
      $maindata->join('photography_products','placements.product_id','photography_products.id');
      $maindata->join('categories','placements.category_id','categories.entity_id');
      $maindata->where(['placements.next_department_status'=>0]);
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
        $done_list=$this->editing_product->where(['editing_models.status'=>3])->get();
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
          $maindata->where('photography_products.sku','LIKE', '%' . $params['skusearch']. '%');
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
    public function get_rework_list_editing()
    {
        $categorylist=$this->category;
        $colorlist=$this->color;
        $editing_rework_list=$this->editing_product->where(['editing_models.status'=>4])->get();
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
      $maindata = EditingModel::query();
      $maindata->join('photography_products','editing_models.product_id','photography_products.id');
      $maindata->join('categories','editing_models.category_id','categories.entity_id');
     $maindata->where(['editing_models.next_department_status'=>0,'editing_models.status'=>4]);
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

    public function get_pending_submit_editing(Request $request)
    {
        $url= $request->url();
      $urllink= explode('Photoshop/',$url);
      $link= $urllink[1];
      $editing=new EditingModel();
      if($request->input('status') !="1")
      {
          

          $editing->product_id=$request->input('product_id');

          $editing->category_id=$request->input('category_id');
          $editing->status=$request->input('status');
          $editing->current_status='1';
          $editing->next_department_status='0';
       
         //Cache table data Insert
         if($request->input('status')=='3')
         {
          $editing->save();
          $cache=array(
            'product_id'=>$request->input('product_id'),
            'action_name'=>$link,
            'status'=>$request->input('status'),
            'action_by'=>"user",
            'action_date_time'=>date('Y-m-d H:i:s')
  
          );
           PhotoshopHelper::store_cache_table_data($cache);
           EditingModel::getUpdatestatusdone($request->input('product_id'));
         }
        
      }
        return redirect()->back()->with('success', 'Editing status Change Successfull');
     
   
     
      //  return redirect()->back()->with($message);
    }

    public function submit_done_list_editng(Request $request)
    {
        $url= $request->url();
        $urllink= explode('Photoshop/',$url);
        $link= $urllink[1];
     if($request->input('status')=='0')
     {
      
        $message=array(
            'success'=>'Editing Select Status',
            'class'=>'alert alert-danger'
        );
        
     }
     else{


        $cache=array(
            'product_id'=>$request->input('product_id'),
            'action_name'=>$link,
            'status'=>$request->input('status'),
            'action_by'=>"user",
            'action_date_time'=>date('Y-m-d H:i:s')
  
        );
       PhotoshopHelper::store_cache_table_data($cache);
       EditingModel::update_editing_status($request->get('product_id'),$request->input('status'));
     
      
       
        $message=array(
            'success'=>'Editing Rework Successfull',
            'class'=>'alert alert-success'
        );
       if($request->input('status')=='4')
       {
        EditingModel::getUpdatestatusrework($request->input('product_id'));
        EditingModel::delete_from_jpeg_List($request->input('product_id'));
       }
       
      
     }
    return redirect()->back()->with($message);   
    }

}
