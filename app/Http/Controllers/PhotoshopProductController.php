<?php

namespace App\Http\Controllers;
use App\photography_product;
use App\category;
use App\color;
use Illuminate\Http\Request;
use App\Helper\PhotoshopHelper;
use DB;
class PhotoshopProductController extends Controller
{
    public $list_prpduct;
    public $total_product;
    public $category;
    public function __construct()
    {
       $this->list_prpduct=collect(photography_product::get_product_list());
       $this->total_product=collect(photography_product::get_photography_product_count());
       
       
    }
    public function list_of_product()
    {
       $total=count($this->total_product);
       $pending=count($this->total_product->where('status','=',0));
       $done=count($this->total_product->where('status','=',1));
       $list=$this->list_prpduct;
       $category=category::all();
       $color = photography_product::select('color')->distinct()->get();
      return view('Photoshop/Product/list',compact('list','category','color','total','done','pending'));
        
       
    }
   
public function Photography_product_ajax(Request $request){
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
   
   if(!empty($params['skusearch'])){
        $maindata->where('sku',$params['skusearch']);
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
   $datacoll = $maindata->where('status',0);
      
    $datacount = $maindata->count();
	  $data["recordsTotal"] = $datacount;
		$data["recordsFiltered"] = $datacount;
		$data['deferLoading'] = $datacount;
        
        $datacollection = $datacoll->take($length)->offset($start)->get();
        
         if(count($datacollection)>0){
            foreach($datacollection as $key=>$p){
                $action='<a class="color-content table-action-style btn-delete-customer " data-href="'.route('delete.product',['id'=>$p->id]) .'" style="cursor:pointer;"><i class="material-icons md-18">delete</i></a>&nbsp;&nbsp;';
                $srno = $key + 1 + $start;
             // $cat=photography_product::get_category_by_id($p->category_id);
               $cat=photography_product::get_category_by_id($p->category_id);
             
                $data['data'][] = array($srno,$p->sku, $p->color,$cat, $action);
            }
           
        }else{
            $data['data'][] = array('', '', '', '','','');
      
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
       'created_by'=>"test",
       'deleted_at'=>"test"
      );
      $status=photography_product::productInsert($photography_product);
      if($status){
        return  redirect('Photoshop/Product/add')->with('message','Product Add Change Successfull');
      }else{
        return  redirect('Photoshop/Product/add')->with('message','Product Add Change Successfull');
      }
 
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
