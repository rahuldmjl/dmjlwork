<?php

namespace App\Http\Controllers;
use App\photography_product;
use App\category;
use App\color;
use Illuminate\Http\Request;
use App\Helpers\PhotoshopHelper;
use App\userphotography;
use DB;
class PhotoshopProductController extends Controller
{
    public $list_prpduct;
    public $total_product;
    public $category;
    public $color;
    public $user_assign;
    public $userid;
    public function __construct()
    {
       $this->list_prpduct=collect(photography_product::get_product_list());
       $this->total_product=collect(photography_product::get_photography_product_count());
       $this->category=PhotoshopHelper::getCategoryList();
       $this->color=PhotoshopHelper::get_product_list("0");
       $this->user_assign=userphotography::all();
       $this->userid="1";
    }
    public function list_of_product()
    {
       $total=count($this->total_product);
       $pending=count($this->total_product->where('userid','=',0));
       $done=count($this->total_product->where('status','=',1));
       $list=$this->list_prpduct;
       $category=category::all();
       $user_assign= $this->user_assign;
       $color = photography_product::select('color')->distinct()->get();
       return view('Photoshop/Product/list',compact('list','category','color','total','done','pending','user_assign'));
        
       
    }
   
public function Photography_product_ajax(Request $request){
    $data = array();
    $params = $request->post();
    $start = (!empty($params['start']) ? $params['start'] : 0);
    $length = (!empty($params['length']) ? $params['length'] : 10);
    $stalen = $start / $length;
    $curpage = $stalen;
    $maindata =photography_product::where('status',0)->groupby(['sku','color']);
    $where = '';
    $offset = '';
    $limit = '';
    $order = $params['order'][0]['column'];
    $order_direc = strtoupper($params['order'][0]['dir']);
    if(!empty($params['skusearch'])){
        $maindata->where('sku', 'LIKE', '%' . $params['skusearch']. '%');
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
   if(!empty($params['userFilter'])){
     $maindata->where('userid',$params['userFilter']);
   }
   $datacoll = $maindata->where(['status'=>0,'userid'=>0]);
    $datacount = $maindata->get()->count();
	  $data["recordsTotal"] = $datacount;
		$data["recordsFiltered"] = $datacount;
		$data['deferLoading'] = $datacount;
        
        $datacollection = $datacoll->take($length)->offset($start)->get();
        
         if(count($datacollection)>0){
            foreach($datacollection as $key=>$p){
              $id=$p->id;
             $check='<label><input class="form-check-input chkProduct" data-id="'.$id.'" value="'.$id.'" type="checkbox" name="chkProduct[]" id="chkProduct{{$p->id}}"><span class="label-text"></label>';
               $user=PhotoshopHelper::getUserAssign($p->userid);
                $action='<a class="color-content table-action-style btn-delete-customer " data-href="'.route('delete.product',['id'=>$p->id]) .'" style="cursor:pointer;"><i class="material-icons md-18">delete</i></a>&nbsp;&nbsp;';
                $srno = $key + 1 + $start;
             // $cat=photography_product::get_category_by_id($p->category_id);
               $cat=photography_product::get_category_by_id($p->category_id);
             
                $data['data'][] = array($check,$srno,$p->sku, $p->color,$cat,$user, $action);
            }
           
        }else{
            $data['data'][] = array('','', '', '', '','','','');
      
        }
     echo json_encode($data);
    exit;
 
}
  public function add_of_product()
    {
        $category=category::all();
        $color=color::all();
      $totalproduct=count($this->total_product);
      $data=collect($this->total_product)->where('status',1);
      $pendingediting=collect($this->total_product)->where('status',0);
       $done_editing=count($data);
       $pending_editing=count($pendingediting);
     return view('Photoshop/Product/add',compact('category','color','totalproduct','done_editing','pending_editing'));
    }

    public function deleteproduct($id){
        $data=photography_product::find($id);
        $data->delete();
        return redirect()->back();
  
    }


    public function add_action_product(Request $request){
      
      $photography_product=array();
      $photography_product[]=array(
        'sku'=>$request->input('sku'),
        'category_id'=>$request->input('category'),
        'color'=>$request->input('color'),
        'regular_shoot_status'=>"0",
        'model_shoot_status'=>"0",
       'instagram_shoot_status'=>"0",
       'status'=>"0",
       'created_at'=>date("Y-m-d H:i:s"),
       'updated_at'=>date("Y-m-d H:i:s"),
       'created_by'=>$this->userid,
       'deleted_at'=>$this->userid,
       "userid"=>"0",
       "work_assign_by"=>$this->userid
      );
      $status=photography_product::productInsert($photography_product);
      if($status){
        return  redirect('Photoshop/Product/add')->with('message','Product Add Change Successfull');
      }else{
        return  redirect('Photoshop/Product/add')->with('message','Product Add Change Successfull');
      }
 
    }
/*
Assign Product using multiple checkbox
*/

public function assign_product(Request $request){
  $uid=$request->userid;
  $length=count($request->pid);
  $pid=$request->pid;
  $data=array();

foreach ($pid as $value) {
 $data[]=array($value);
 $PhotoshopHelper=PhotoshopHelper::update_user_assign($uid,$value,$this->userid);
}
 return response()->json(['success'=>config('constants.message.user_assign')]);
 
  

}

public function workassign(){
   $category=category::all();
  $color=color::all();
  $user=$this->user_assign;
  $data=PhotoshopHelper::getWorkAssign_List("")->get();

return view('Photoshop/Product/workassign',compact('data','category','color','user'));
  
}
public function ajax_workassign(Request $request){
  $data=array();
  $params = $request->post();
  $params = $request->post();
  $start = (!empty($params['start']) ? $params['start'] : 0);
  $length = (!empty($params['length']) ? $params['length'] : 10);
  $stalen = $start / $length;
  $curpage = $stalen;
 
  if(!empty($params['departmentfilter'])){
    $maindata = PhotoshopHelper::getWorkAssign_List($params['departmentfilter']);
  }
  
  if(!empty($params['categoryFilter'])){
   $maindata->where('p.category_id',$params['categoryFilter']);
  }
  
  if(!empty($params['colorFilter'])){
    $maindata->where('pro.color',$params['colorFilter']);
   }
   $maindata = PhotoshopHelper::getWorkAssign_List("");
 
  $datacoll = $maindata;
  $data["recordsTotal"] =$datacoll->get()->count();
  $data["recordsFiltered"] = $datacoll->get()->count();
  $data['deferLoading'] = $datacoll->get()->count();
  $donecollection = $datacoll->take($length)->offset($start)->get();
  if(count($donecollection)>0){
    foreach($donecollection as $key => $product)
    { 
      $check='<input class="form-check-input chkProduct" data-id="'.$product->pid.'" value="'.$product->pid.'" type="checkbox" name="chkProduct[]" id="chkProduct'.$product->pid.'"><span class="label-text"></label>';
       $data['data'][] = array( $check,$product->sku, $product->color, $product->categoryname,$product->created_by);
    }

   
  }else{
    $data['data'][] = array('','','','', '', '', '', '');
  }
  echo json_encode($data);exit;
}

public function user_assign(Request $request){
  $uid=$request->userid;
  $length=count($request->pid);
  $table=$request->tablename;
  $pid=$request->pid;
  $data=array();

foreach ($pid as $value) {
 $data[]=array($value);
 if($value !="on"){
  PhotoshopHelper::updateuserassign($table,$value,$uid,$this->userid);
 }

}
  $message=config('constants.message.product_assign');
return response()->json(['success'=>$message]);
}
    /*
Testing For data fetch 

    */
 
 public function test(){
     $query=   DB::table('photography_products')
     ->select('photography_products.sku','photography_products.category_id','photography_products.color')
     ->join('photographies','photographies.product_id','=','photography_products.id')
     ->where(['photographies.status'=>3])
     ->get();
     var_dump(count($query));
     foreach($query as $q){
         echo $q->sku;
         echo "<br>";
     }
 }
}
