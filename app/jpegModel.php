<?php

namespace App;
use App\EditingModel;
use DB;

use Illuminate\Database\Eloquent\Model;

class jpegModel extends Model
{

 
 public static function update_Jpeg_status($productid,$status)
 {
  $data=array('status'=>$status);
  return jpegModel::where('product_id','=',$productid)->update($data);
 }
  public static function getUpdatestatusdone($productid)
  {
      $data=array('next_department_status'=>'1');
      return EditingModel::where('product_id','=',$productid)->update($data);
  }
  
}
