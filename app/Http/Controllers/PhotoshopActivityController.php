<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\userphotography;
use App\Helpers\PhotoshopHelper;
use App\category;
use App\color;
use DB;
class PhotoshopActivityController extends Controller
{
    public $user;
    public $category;
    public $color;
    public $defaultload;
    public $userid;

    public function __construct(){
     $this->category=PhotoshopHelper::getCategoryList();
     $this->color=PhotoshopHelper::getcolorList();
     $this->user=userphotography::all();
     $this->defaultload=PhotoshopHelper::activity_load();
     $this->userid=2;
     
    }
 public function Activityload(Request $request){
        $params = $request->post();
        $start = (!empty($params['start']) ? $params['start'] : 0);
        $length = (!empty($params['length']) ? $params['length'] : 10);
        $categorylist=$this->category;
        $colorlist=$this->color;
        $userlist=$this->user;
        $record=$this->defaultload->take($length)->offset($start)->get();
        $totalrecordcount=$this->defaultload->count();
        
      return view('Photoshop/Activity/index',compact('categorylist','colorlist','userlist','record','totalrecordcount'));
  
    }

    public function Activityload_ajax(Request $request){
        $data = array();
        $params = $request->post();
        $start = (!empty($params['start']) ? $params['start'] : 0);
        $length = (!empty($params['length']) ? $params['length'] : 10);
        $stalen = $start / $length;
        $curpage = $stalen;
        $maindata =$this->defaultload;
        $order = $params['order'][0]['column'];
        $order_direc = strtoupper($params['order'][0]['dir']);
      
        if ($order == "1") {
			$order_by = 'sku';
		} elseif ($order == "2") {
			$order_by = 'color';
        }
        elseif ($order == "3") {
			$order_by = 'category_id';
		} elseif ($order == "4") {
			$order_by = 'action_by';
        } elseif ($order == "5") {
			$order_by = 'status';
        }
         elseif ($order == "7") {
			$order_by = 'action_date_time';
		}
         else {
			$order_by = 'sku';
		}
        if(!empty($params['skusearch'])){
            $maindata->where('pro.sku','LIKE', '%' . $params['skusearch']. '%');
      
        }
        if(!empty($params['departmentFilter'])){
            $maindata->where('cache.action_name',$params['departmentFilter']);
        }
        if(!empty($params['categoryFilter'])){
            $maindata->where('pro.category_id',$params['categoryFilter']);
       
        }
        if(!empty($params['colorFilter'])){
            $maindata->where('pro.color',$params['colorFilter']);
       
        }
        if(!empty($params['statusFilter'])){
            $maindata->where('cache.status',$params['statusFilter']);
      
        }
        if(!empty($params['userfilter'])){
            $maindata->where('cache.action_by',$params['userfilter']);
        
        }
            $datacount =$maindata->get()->count();
            $datacoll = $maindata;
            $data["recordsTotal"] = $datacount;
	  	    $data["recordsFiltered"] = $datacount;
            $data['deferLoading'] = $datacount;
            $datacollection = $datacoll->take($length)->offset($start)->orderBy($order_by, $order_direc)->get();
  
            if(count($datacollection)>0){
                foreach($datacollection as $key=>$p){
                    $username=PhotoshopHelper::getuserbyname($p->userid);
                    if($p->status=="3"){
                        $status="done";
                    }
                    else if($p->status=="2"){
                        $status="pending";
                    }else if($p->status=="4"){
                        $status="Rework";
                    }else{

                    }
                     $data['data'][] = array($p->sku,$p->color,$p->name,$username->uname,$status,$p->action_name,$p->action_date_time);
                }
               
            }else{
                $data['data'][] = array('', '', '', '','','','','');
          
            }
         echo json_encode($data);
        exit;
    }

    //Department Admin Code
   public function admin(){
       $data=PhotoshopHelper::getuserbyname($this->userid);
       $start = (!empty($params['start']) ? $params['start'] : 0);
       $length = (!empty($params['length']) ? $params['length'] : 10);
     if($data->type=="admin"){
           $department=$data->type;
           $pending=PhotoshopHelper::get_product_list(0)->take($length)->offset($start)->get();
          return view('Photoshop/admin/index',compact('department','pending'));
 
       }
   }
}
