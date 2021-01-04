<?php 
use App\photography_product;
use App\Helpers\PhotoshopHelper;

?>
@extends('layout.photo_navi')


@section('title', 'Photography Pending')

@section('distinct_head')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link href="<?=URL::to('/');?>/cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">

@endsection
<style>
    table, td, th {
      
       width: 300px;
    }
 </style>
@section('body_class', 'header-light sidebar-dark sidebar-expandheader-light sidebar-dark sidebar-expand')

@section('content')
<main class="main-wrapper clearfix">
  <!-- Page Title Area -->
  <div class="row page-title clearfix">
   Activity Logs
      <!-- /.page-title-right -->
  </div>
  <!-- /.page-title -->
  <!-- =================================== -->
  <!-- Different data widgets ============ -->
  <!-- =================================== -->
  <div class="col-md-12 widget-holder loader-area" style="display: none;">
    <div class="widget-bg text-center">
      <div class="loader"></div>
    </div>
  </div>
  <div class="widget-list">
  <div class="row">
			<div class="col-md-12 widget-holder content-area">
				<div class="widget-bg">
					<div class="widget-heading clearfix">
						<h5 class="border-b-light-1 pb-1 mb-2 mt-0 w-100"> Activity list</h5>
					  
					</div>
					<div class="widget-body clearfix dataTable-length-top-0">
						<form class="mr-b-30" method="post" action="javascript:void(0);">
							{{ csrf_field() }}
							<div class="row">
                           
								<div class="col-md-4">
                               	<div class="form-group">
										<select class="form-control" name="userfilter" id="userfilter">
											<option value="">Select User</option>
                                            @foreach($userlist as $user)
											<option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>	
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<select class="form-control" name="categoryFilter" id="categoryFilter">
											<option value="">Select Category</option>
                                            @foreach($categorylist as $cat)
											<option value="{{$cat->entity_id}}">{{$cat->name}}</option>
                                            @endforeach
											
										</select>	
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<select class="form-control" name="colorFilter" id="colorFilter">
											<option value="">Select Color</option>
                                            @foreach($colorlist as $color)
											<option value="{{$color->name}}">{{$color->name}}</option>
                                            @endforeach
										</select>	
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<select class="form-control" name="departmentFilter" id="departmentFilter">
                                        <option value="">Select Department</option>
										<option value="Photography">Photography</option>
											<option value="psd">PSD</option>
											<option value="Placement">Placement</option>
                      <option value="Editing">Editing</option>
											<option value="JPEG">Jpeg</option>
                      <option value="REGULAR">Regular</option>
                      <option value="MODEL">Model</option>
                      <option value="INSTAGRAM">Instagram</option>
										</select>	
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<select class="form-control" name="statusFilter" id="statusFilter">
											<option value="">Select Status</option>
											<option value="3">Done</option>
											<option value="2">Pending</option>
											<option value="4">Rework</option>
											<option value="1">In Process</option>
										
										</select>	
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group">
										<input class="form-control" name="sku" id="sku" style="height: 43px;" placeholder="Sku Search" type="text">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<input class="btn btn-primary" style="height: 43px;" id="searchfilter"   type="submit" value="Apply">
                     <button class="btn btn-default" namwe="reset" id="reset" type="button">Reset</button>
                 	</div>
								</div>
							</div>
						</form>
				</div>
        </div>
        </div>
		<div class="widget-list">
      	<div class="row">
  			<div class="col-md-12 widget-holder content-area">
  				<div class="widget-bg">
  					<div class="widget-heading clearfix">
  						<h5 class="border-b-light-1 pb-1 mb-2 mt-0 w-100">Activity List</h5>
						
  					</div>
  					<div class="widget-body clearfix dataTable-length-top-0">
  					  <table class="table table-striped table-center word-break mt-0"   id="activitydatatable">
  							<thead>
  								<tr class="bg-primary">
                    <th>Sku</th>
									 <th>Color</th>
									<th>Category</th>
  								<th>User</th>
									<th>Status</th>
									<th>Department</th>
                  <th>Date</th>
  								</tr>
  							</thead>
  						<tbody>
                       <?php 
  
                       ?>
                          @foreach($record as $data)
                        <?php 
                       $username=PhotoshopHelper::getuserbyname($data->userid);
            
                        ?>
				          <tr>
                   
                            <td>{{$data->sku}}</td>
                            <td>{{$data->color}}</td>
                            <td>{{$data->name}}</td>
                            <td><?= $username[0]->uname;?></td>
                            <td>
                            @if($data->status=="3")
                            done
                            @endif
                            @if($data->status=="2")
                            pending
                            @endif
                            @if($data->status=="4")
                            Rework
                            @endif
                           </td>
						     	         <td>{{$data->action_name}}</td>
                            <td>{{$data->action_date_time}}</td>
                           
	                </tr>
                    @endforeach
				
                        </tbody>
							  <tfoot>
								<tr class="bg-primary">
                    
  								  <th>Sku</th>
									  <th>Color</th>
								  	<th>Category</th>
  									<th>User</th>
									  <th>Status</th>
									  <th>Department</th>
                    <th>Date</th>
								</tr>
							</tfoot>
	  					</table>
  					</div>
  				</div>

</main>
<input type="hidden" id="activitylistAjax" value="<?=URL::to('Photoshop/Activity/activity_ajax');?>">

<style type="text/css">
.form-control[readonly] {background-color: #fff;}
</style>
@endsection

@section('distinct_footer_script')
<script src="<?=URL::to('/');?>/cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="<?=URL::to('/');?>/cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/buttons/1.6.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.print.min.js"></script>
<script src="<?=URL::to('/');?>/js/jquery.validate.min.js"></script>
<script src="<?=URL::to('/');?>/js/additional-methods.min.js"></script>
<script> 
	var buttonCommon = {
        exportOptions: {
            format: {
                body: function ( data, row, column, node ) {                    
                    if (column === 3) {
                      data = data.replace(/(&nbsp;|<([^>]+)>)/ig, "");
                    }
                    return data;
                }
            }
        }
    };
$(document).ready(function() {
    var title="name";
 var table= $('#activitydatatable').DataTable({
    "dom": "<'row mb-2 align-items-center'<'col-auto dataTable-length-tb-0'l><'col'B>><'row'<'col-md-12' <'user-roles-main' t>>><'row'<'col-md-3'i><'col-md-6 ml-auto'p>>",
  "lengthMenu": [[10, 50, 100, 200,500], [10, 50, 100, 200,500]],
  "buttons": [
    $.extend( true, {}, buttonCommon, {
      extend: 'csv',
      footer: false,
      title: title,
      className: "btn btn-primary btn-sm px-3",
      exportOptions: {
          columns: [0,1,2,3,4,5,6,7],
          orthogonal: 'export'
      }
    }),
    $.extend( true, {}, buttonCommon, {
      extend: 'excel',
      footer: false,
      title: title,
      className: "btn btn-primary btn-sm px-3",
      exportOptions: {
          columns: [0,1,2,3,4,5,6,7],
          orthogonal: 'export'
      }
    })
  ],
  
  "language": {
    "search": "",
    "infoEmpty": "No matched records found",
    "zeroRecords": "No matched records found",
    "emptyTable": "No data available in table",
    /*"sProcessing": "<div class='spinner-border' style='width: 3rem; height: 3rem;'' role='status'><span class='sr-only'>Loading...</span></div>"*/
  },
  "order": [[ 0, "desc" ]],
  "deferLoading": <?=$totalrecordcount;?>,
  "processing": true,
  "serverSide": true,
  "searching": false,
  "serverMethod": "post",
   
  "ajax":{
    "url": $("#activitylistAjax").val(),
    "data": function(data, callback){
      showLoader();
      data._token = "{{ csrf_token() }}";
      var skusearch = $('#sku').val();
      if(skusearch != ''){
        data.skusearch = skusearch;
    
      }
      var departmentFilter = $('#departmentFilter').children("option:selected").val();
     if(departmentFilter != ''){
       data.departmentFilter=departmentFilter;
     }
     var categoryFilter=$('#categoryFilter').children("option:selected").val();
     if(categoryFilter !=''){
         data.categoryFilter=categoryFilter;
     }
     var colorFilter=$('#colorFilter').children("option:selected").val();
     if(colorFilter !=''){
         data.colorFilter=colorFilter;
     }
     
     var statusFilter=$('#statusFilter').children("option:selected").val();
     if(statusFilter !=''){
         data.statusFilter=statusFilter;
     }
     var userfilter=$('#userfilter').children("option:selected").val();
     if(userfilter !=''){
         data.userfilter=userfilter;
     }
    },
    complete: function(response){
      hideLoader();
    }
  },
});
    $('#searchfilter').click(function(){
        table.draw();
  });
  $('#reset').click(function(){
    $('#sku').val('');
    $('#departmentFilter option[value=""]').attr('selected','selected');
    $('#departmentFilter').on('change', function() {
      if(this.value == ''){
        $('#departmentFilter option[value=""]').attr('selected','selected');
      }else{
        $('#departmentFilter option[value=""]').removeAttr('selected','selected');
      }
	});
    $('#categoryFilter option[value=""]').attr('selected','selected');
    $('#categoryFilter').on('change', function() {
      if(this.value == ''){
        $('#categoryFilter option[value=""]').attr('selected','selected');
      }else{
        $('#categoryFilter option[value=""]').removeAttr('selected','selected');
      }
	});
    $('#colorFilter option[value=""]').attr('selected','selected');
    $('#colorFilter').on('change', function() {
      if(this.value == ''){
        $('#colorFilter option[value=""]').attr('selected','selected');
      }else{
        $('#colorFilter option[value=""]').removeAttr('selected','selected');
      }
	});
    $('#statusFilter option[value=""]').attr('selected','selected');
    $('#statusFilter').on('change', function() {
      if(this.value == ''){
        $('#statusFilter option[value=""]').attr('selected','selected');
      }else{
        $('#statusFilter option[value=""]').removeAttr('selected','selected');
      }
	});
    $('#userfilter option[value=""]').attr('selected','selected');
    $('#userfilter').on('change', function() {
      if(this.value == ''){
        $('#userfilter option[value=""]').attr('selected','selected');
      }else{
        $('#userfilter option[value=""]').removeAttr('selected','selected');
      }
	});
    table.draw();
  });
});

</script>
@endsection
