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
        
        $this->user=PhotoshopHelper::getUserList();
        $this->category=category::all();
        $this->color=color::all();
       $this->defaultload=PhotoshopHelper::getDefaultLoadIn_photography_activity();
    }
//Photography Activiy function 
    public function photography(){
        
        $user_detail=$this->user;
        $category_list=$this->category;
        $color_list=$this->color;
        $dataList=$this->defaultload->get();
        return view('Photoshop/Activity/photography',compact('user_detail','category_list','color_list','dataList'));
   
    }

    public function ajax_load_photoshop(Request $request){
        $data=array();
        $params = $request->post();
        $params = $request->post();
		$start = (!empty($params['start']) ? $params['start'] : 0);
		$length = (!empty($params['length']) ? $params['length'] : 10);
		$stalen = $start / $length;
        $curpage = $stalen;
        $maindata =$this->defaultload;
        if(!empty($params['departmentFilter'])){
            $maindata->where('cs.action_name', $params['departmentFilter']);
        }
        if(!empty($params['statusFilter'])){
            $maindata->where('p.status', $params['statusFilter']);
        }
        if(!empty($params['colorFilter'])){
            $maindata->where('pro.color', $params['colorFilter']);
        }
        if(!empty($params['categoryFilter'])){
            $maindata->where('c.name', $params['categoryFilter']);
        }
        if(!empty($params['userfilter'])){
            $maindata->where('u.name', $params['userfilter']);
        }
        $datacoll = $maindata;
        $data["recordsTotal"] =$this->defaultload->get()->count();
        $data["recordsFiltered"] = $this->defaultload->get()->count();
        $data['deferLoading'] =$this->defaultload->get()->count();
       $donecollection = $datacoll->take($length)->offset($start)->get();
       if(count($donecollection)>0){
        foreach($donecollection as $key => $product)
        {  
            $srno = $key + 1 + $start;
            if($product->status=="3"){
                $status="Done";
            }
            if($product->status=="4"){
                $status="Rework";
            }
            $data['data'][] = array($product->sku,$product->color,$product->name, $product->username, $status, $product->action_name,$product->created_at);
   
        }
    }else{
        $data['data'][] = array('','','', '', '', '', '');
    }
   
    
 
  echo json_encode($data);exit;
    }
    public function psd(){
        echo "psd";
    }
    public function placement(){
        echo "placement";
    }
    public function jpeg(){
        echo "jpeg";
    }
}
