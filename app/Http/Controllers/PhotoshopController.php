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
    public function __construct()
    {
        $this->product=photography_product::groupBy('sku')->groupBy('color')->get();
        $this->photography=photography::getphotographyProduct();
        $this->category=category::all();
        $this->color=color::all();

       
    }
    public function index()
    {

        DB::setTablePrefix('');
        $totoalproduct=DB::table('Catalog_product_flat_1 as e')->count();
        $totalphotographydone=DB::table('Catalog_product_flat_1 as e')
                    ->where('dml_only','=',0)
                    ->count();
        $totalphotographypending=DB::table('Catalog_product_flat_1 as e')
                    ->where('dml_only','=',1)
                    ->count();
        DB::setTablePrefix('dml_');
        return view('Photoshop/Photography/index',compact('totoalproduct','totalphotographydone','totalphotographypending'));
    }

    /*
    Photography pending get data from this function
    */
    public function get_pending_list()
    {
       $pendinglist=array();
     
        $pending=photography_product::groupBy('sku')->groupBy('color')->get();
        
       $pendinglist=collect($this->product)->where('status','=',0)->take(10);
       $totalproduct= count($pending);  
      $category_name=$this->category;
      $color_name=$this->color;
    return view('Photoshop/Photography/photography_pending',compact('pendinglist','totalproduct','category_name','color_name'));
 
  
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
            $datacollection = $datacoll->take($length)->offset($start)->groupBy('sku')->groupBy('color')->get();
       
            $data["recordsTotal"] = $datacount;
            $data["recordsFiltered"] = $datacount;
            $data['deferLoading'] = $datacount;
           
             if(count($datacollection)>0){
                foreach($datacollection as $key=>$p){
                    $action='<a class="color-content table-action-style btn-delete-customer " data-href="'.route('delete.product',['id'=>$p->id]) .'" style="cursor:pointer;"><i class="material-icons md-18">delete</i></a>&nbsp;&nbsp;';
                    $srno = $key + 1 + $start;
                    $c=photography_product::get_category_by_id($p->category_id);
                    $action='
                    <form action="" method="POST">
                    
			<input type="hidden" value="{{$p->id}}" name="product_id"/>
			<input type="hidden" value="{{$p->category_id}}" name="category_id"/>
            <select name="status" class="form-control" style="height:20px;width:150px;float: left;">
            <option value="2">Pending</option>
            <option value="1">In processing</option>
            <option value="3">Done</option>
        </select>
				<button type="submit" style="height: 30px;
    width: 30px;"  class="btn btn-primary btn-circle"><i class="material-icons list-icon">remove_red_eye</i></button>
		
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
     $donelist=collect($this->photography)->where('status','=',3);
 

  return view('Photoshop/Photography/photography_done',compact('donelist'));
    }
     /*
    Photography Rework get data from this function
    */
    public function get_rework_list()
    {
     
       
       $reworklist=collect($this->photography)->where('status','=',4);
      return view('Photoshop/Photography/photography_rework',compact('reworklist'));
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
        $photoshop->save();
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
    PhotoshopHelper::store_cache_table_data($cache);
    photography_product::update_product($request->input('product_id'));
  
 return  redirect('Photoshop/Photography/pending')->with('message','Photoshop Status Change Successfull');
    }
/*
done list submit for particular product change the photography status
done to rework 
*/
    public function submit_done_list(Request $request)
    {
        
        $user=Auth::user();
        if($request->input('status') !='0')
        {
            //cache table data insert 
            $cache=array(
                'product_id'=>$request->input('product_id'),
                'url'=>PhotoshopHelper::getDepartment($request->url()),
                'status'=>$request->input('status'),
                'action_by'=>$user->id
    
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
