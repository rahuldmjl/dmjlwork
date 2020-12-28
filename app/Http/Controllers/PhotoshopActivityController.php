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

    public function __construct(){
     $this->category=PhotoshopHelper::getCategoryList();
     $this->color=PhotoshopHelper::getcolorList();
     $this->user=userphotography::all();
     $this->defaultload=PhotoshopHelper::activity_load();
     
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
            $datacollection = $datacoll->take($length)->offset($start)->get();
  
            $i=1;
             if(count($datacollection)>0){
                foreach($datacollection as $key=>$p){
                    $srno = $key + 1 + $start;
                    $data['data'][] = array($srno,$p->sku,$p->color,$p->name,0,0,$p->action_name,$p->action_date_time);
                }
               
            }else{
                $data['data'][] = array('', '', '', '','','','','');
          
            }
         echo json_encode($data);
        exit;
    }
}
