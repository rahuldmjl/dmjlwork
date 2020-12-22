<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\userphotography;
use App\Helpers\PhotoshopHelper;
class PhotoshopActivityController extends Controller
{
    public $user;
   
    public function __construct(){
        $this->user=PhotoshopHelper::getUserList();
    }
//Photography Activiy function 
    public function photography(){
        
        $user_detail=$this->user;
        return view('Photoshop/Activity/photography',compact('user_detail'));
   
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
