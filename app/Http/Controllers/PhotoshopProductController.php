<?php

namespace App\Http\Controllers;
use App\photography_product;
use App\category;
use App\color;
use Illuminate\Http\Request;
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
    $datacount = $maindata->count();
		$datacoll = $maindata;
        $data["recordsTotal"] = $datacount;
		$data["recordsFiltered"] = $datacount;
		$data['deferLoading'] = $datacount;
        
        $datacollection = $datacoll->take($length)->offset($start)->get();
        
         if(count($datacollection)>0){
            foreach($datacollection as $key=>$p){
                $action='<a class="color-content table-action-style btn-delete-customer " data-href="'.route('delete.product',['id'=>$p->id]) .'" style="cursor:pointer;"><i class="material-icons md-18">delete</i></a>&nbsp;&nbsp;';
                $category="0";
                $data['data'][] = array($p->sku, $p->color,$category, $action);
            }
           
        }else{
            $data['data'][] = array('', '', '', '');
      
        }
     echo json_encode($data);
    exit;
 
}


    public function add_of_product()
    {
        $category=category::all();
        $color=color::all();
       
        return view('Photoshop/Product/add',compact('category','color'));
    }

    public function list_of_product_filter(Request $request)
    {
        $category=$request->input('category');
        $color=$request->input('color');
        $status=$request->input('status');
        $sku=$request->input('sku');
        $filter=array(
            'category'=>$category,
            'color'=>$color,
            'status'=>$status,
            'sku'=>$sku
        );
        
        
      
         if($color !=="null")
        {
            $list=$this->list_prpduct->where('color',$color);
           
        }
        else if($status !=="null")
        {
            $list=$this->list_prpduct->where('status',$status);
         
        }
        else if($sku !=="null"){
            $list=$this->list_prpduct->where('sku',$sku);

        }else{
            $list=$this->list_prpduct;
        }
        $category=category::all();
        $color = photography_product::select('color')->distinct()->get();
     
        return view('Photoshop/Product/list',compact('list','category','color'));
 
    }

    public function deleteproduct($id){
        $data=photography_product::find($id);
        $data->delete();
        return redirect()->back();
  
    }


    public function add_action_product(Request $request){
       $sku=$request->input('sku');
       $category_id=$request->input('category');
       $color=$request->input('color');
       echo $color;
    }
   
}
