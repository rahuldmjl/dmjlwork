<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\category;
use App\color;
class PhotoshopSetting extends Controller
{
    public $category;
    public $color;
    
    public function __construct(){
        $this->category=category::all();
        $this->color=color::all();
    }
//Category Setting List
    public function category(){
        $category_list=$this->category;
       
        return view('Photoshop/Setting/category',compact('category_list'));
    }

    //Color Setting List

    public function color()
    {
        $color_list=$this->color;
        return view('Photoshop/Setting/color',compact('color_list'));
    }

    //Delete from categroy List 

    public function category_delete(){
       echo "Delete "; 
    }

    //Edit Category
    public function editCategory($id){
        $category_detail=category::find($id);
        return view('Photoshop/Setting/Add_category',compact('category_detail'));
    }

}
