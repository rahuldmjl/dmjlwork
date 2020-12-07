<?php

namespace App\Http\Controllers;
use App\photography_product;
use App\category;
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
    $where = '';
    $offset = '';
    $limit = '';
    $order = $params['order'][0]['column'];
    $order_direc = strtoupper($params['order'][0]['dir']);
    if ($order == "1") {
        $order_by = 'sku';
    } else{
        $order_by = 'color';
    } 
    if(!empty($params['colorFilter'])){
        $product=photography_product::where('color',$params['colorFilter'])->get();
    }
   
    
    $data["draw"] = $params['draw'];
		$data["page"] = $curpage;
		//$data["query"] = $maindata->toSql();
		$data["recordsTotal"] = count($product);
		$data["recordsFiltered"] = count($product);
        $data['deferLoading'] = count($product);
        if(count($product)>0){
            foreach($product as $p){
                $action='CCXC ';
                $data['data'][] = array($p->sku, $p->color, $p->category_id, $action);
            }
           
        }else{
            $data['data'][] = array('', '', '', '');
      
        }
     echo json_encode($data);
    exit;
 
}


    public function add_of_product()
    {
        return view('Photoshop/Product/add');
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
   
}
