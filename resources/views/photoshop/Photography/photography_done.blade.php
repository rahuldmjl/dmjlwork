@extends('layout.photo_navi')


@section('title', 'Photography Done')

@section('distinct_head')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link href="<?=URL::to('/');?>/cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
<style>
    table, td, th {
      
       width: 300px;
    }
 </style>
@endsection

@section('body_class', 'header-light sidebar-dark sidebar-expandheader-light sidebar-dark sidebar-expand')

@section('content')
<main class="main-wrapper clearfix">
  <!-- Page Title Area -->
  <div class="row page-title clearfix">
    {{ Breadcrumbs::render('photography.done') }}
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
		  <div class="col-md-12 widget-holder">
                        <div class="widget-bg">
                        @if (session()->has('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
@endif
                            <div class="widget-body clearfix">
                                <h5 class="box-title">Total  Photography Done List</h5>
                                <div class="tabs">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item "><a class="nav-link" href="#home-tab" data-toggle="tab" aria-expanded="true">Home</a>
                                        </li>
                                        <li class="nav-item active"><a class="nav-link" href="#profile-tab" data-toggle="tab" aria-expanded="true">Filter</a>
                                        </li>
                                      
                                    </ul>
                                    <!-- /.nav-tabs -->
                                    <div class="tab-content">
                                        <div class="tab-pane " id="home-tab">
                                        <div class="row">
                    <!-- Counter: Sales -->
                    <div class="col-md-4 col-sm-6 widget-holder widget-full-height">
                        <div class="widget-bg bg-primary text-inverse">
                            <div class="widget-body">
                                <div class="widget-counter">
                                    <h6>Photography Pending <small class="text-inverse"></small></h6>
                                    <h3 class="h1"><span class="counter">{{$totalproduct}}</span></h3><i class="material-icons list-icon">add_shopping_cart</i>
                                </div>
                            </div>
                         </div>
                     </div>
                  
                    <div class="col-md-4 col-sm-6 widget-holder widget-full-height">
                        <div class="widget-bg bg-color-scheme text-inverse">
                            <div class="widget-body clearfix">
                                <div class="widget-counter">
                                    <h6>Photography Done   <small class="text-inverse"></small></h6>
                                    <h3 class="h1"><span class="counter">{{$done_product_count}}</span></h3><i class="material-icons list-icon">add_shopping_cart</i>
                                </div>
                             </div>
                         </div>
                     </div>
                    
                    <div class="col-md-4 col-sm-6 widget-holder widget-full-height">
                        <div class="widget-bg">
                            <div class="widget-body clearfix">
                                <div class="widget-counter">
                                    <h6>Photography<small></small></h6>
                                    <h3 class="h1"><span class="counter">{{$rework_product_count}}</span></h3><i class="material-icons list-icon">add_shopping_cart</i>
                                </div>
                                <!-- /.widget-counter -->
                            </div>
                            <!-- /.widget-body -->
                        </div>
                        <!-- /.widget-bg -->
                    </div>
                  </div>
         </div>
                  <div class="tab-pane active" id="profile-tab">
						<div class="row">
			<div class="col-md-12 widget-holder content-area">
				<div class="widget-bg">
					<div class="widget-heading clearfix">
						<h5 class="border-b-light-1 pb-1 mb-2 mt-0 w-100">Photography Filter</h5>
					  
					</div>
					<div class="widget-body clearfix dataTable-length-top-0">
						<form class="mr-b-30" method="post" action="javascript:void(0);">
							{{ csrf_field() }}
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<select class="form-control" name="categoryFilter" id="categoryFilter">
											<option value="">Select Category</option>
											@foreach($category_name as $cat)
											<option value="{{$cat->entity_id}}">{{$cat->name}}</option>
											@endforeach
										</select>	
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<select class="form-control" name="colorFilter" id="colorFilter">
											<option value="">Select Color</option>
											@foreach($color_name as $color)
											<option value="{{$color->name}}">{{$color->name}}</option>
											@endforeach
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
  			<div class="col-md-12 widget-holder content-area">
  				<div class="widget-bg">
  					<div class="widget-heading clearfix">
  						<h5 class="border-b-light-1 pb-1 mb-2 mt-0 w-100">Product List</h5>
						
					  </div>
					  @if(Session::has('success'))
					  <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success') }}</p>
					  @endif
  					<div class="widget-body clearfix dataTable-length-top-0">
  						
	                    <table class="table table-striped table-center word-break mt-0"  id="photography_rework">
  							<thead>
  								<tr class="bg-primary">
								  <th data-orderable="false">Sr No</th>
  									<th>Sku</th>
									  <th>Color</th>
                     <th>Category</th>
                     <th data-orderable="false">Status</th>
  									<th data-orderable="false">Action</th>
  								
  								</tr>
  							</thead>
  							<tbody>
							  <?php 
				$i=1;
							  ?>
		 @foreach ($donelist as $item)
		 <?php 
								
								
									?>
	
	<tr>
		<td><?php echo $i++;?></td>
		<td>{{$item->sku}}</td>
		<td>{{$item->color}}
		</td>
    <td>{{$item->name}}</td>
    <td>Done</td>
		<td>
					<select name="status" id="status" onchange="donetorework(this.value)" class="form-control" style="height:20px;width:150px;float: left;">
					<option value="0/{{$item->product_id}}/{{$item->category_id}}">select status</option>
					<option value="4/{{$item->product_id}}/{{$item->category_id}}">Rework</option>
				</select>
				</form>
		</td>
	 </tr>
	@endforeach
							  </tbody>
							  <tfoot>
								<tr class="bg-primary">
								  <th>Sr No</th>
  							 	<th>Sku</th>
									<th>Color</th>
                  <th>Category</th>
                  <th>Status</th>
									<th>Action</th>
								
								</tr>
							</tfoot>
	  					</table>
  					</div>
  				</div>
  			</div>
  		</div>
    </div>
  <!-- /.widget-list -->
</main>
<!-- /.main-wrappper -->
<input type="hidden" id="photography_done_listAjax" value="<?=URL::to('Photoshop/Photography/done_ajax_list');?>">
<style type="text/css">
.form-control[readonly] {background-color: #fff;}
</style>
@endsection

@section('distinct_footer_script')
<script src="<?=URL::to('/');?>/cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.print.min.js"></script>
<script src="<?=URL::to('/');?>/js/jquery.validate.min.js"></script>
<script src="<?=URL::to('/');?>/js/additional-methods.min.js"></script>
<script type="text/javascript">
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
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
	var table = $('#photography_rework').DataTable({
		"dom": "<'row mb-2 align-items-center'<'col-auto dataTable-length-tb-0'l><'col'B>><'row'<'col-md-12' <'user-roles-main' t>>><'row'<'col-md-3'i><'col-md-6 ml-auto'p>>",
  "lengthMenu": [[10, 50, 100, 200,500], [10, 50, 100, 200,500]],
  "buttons": [
    $.extend( true, {}, buttonCommon, {
      extend: 'csv',
      footer: false,
      title: 'Photography-pending-List',
      className: "btn btn-primary btn-sm px-3",
      exportOptions: {
          columns: [0,1,2,3],
          orthogonal: 'export'
      }
    }),
    $.extend( true, {}, buttonCommon, {
      extend: 'excel',
      footer: false,
      title: 'Photography-pending-List',
      className: "btn btn-primary btn-sm px-3",
      exportOptions: {
          columns: [0,1,2],
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
  "deferLoading": <?=$done_product_count?>,
  "processing": true,
  "serverSide": true,
  "searching": false,
  "serverMethod": "post",
  "ajax":{
    "url": $("#photography_done_listAjax").val(),
    "data": function(data, callback){
   
      showLoader();
      data._token = "{{ csrf_token() }}";
      console.log(data);
      var skusearch = $('#sku').val();
      if(skusearch != ''){
        data.skusearch = skusearch;
    
      }
      var category = $('#categoryFilter').children("option:selected").val();
     if(category != ''){
       data.category=category;
     }
     var color = $('#colorFilter').children("option:selected").val();
     if(color != ''){
       data.color=color;
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
	$('#categoryFilter option[value=""]').attr('selected','selected');
	$('#colorFilter option[value=""]').attr('selected','selected');
	$('#statusFilter option[value=""]').attr('selected','selected');
	$('#categoryFilter').on('change', function() {
      if(this.value == ''){
        $('#categoryFilter option[value=""]').attr('selected','selected');
      }else{
        $('#categoryFilter option[value=""]').removeAttr('selected','selected');
      }
	});
	$('#colorFilter').on('change', function() {
      if(this.value == ''){
        $('#colorFilter option[value=""]').attr('selected','selected');
      }else{
        $('#colorFilter option[value=""]').removeAttr('selected','selected');
      }
	});
	$('#statusFilter').on('change', function() {
      if(this.value == ''){
        $('#statusFilter option[value=""]').attr('selected','selected');
      }else{
        $('#statusFilter option[value=""]').removeAttr('selected','selected');
      }
    });
	table.draw();
  });
	function donetorework(str){
   var data=str.split('/');
   var status1=data[0];
   var product_id=data[1];
   var category_id=data[2];
    if(status1 !=0){
      swal({
		         title: 'Are you sure?',
		         type: 'info',
			    	 text:'To change the Status of the product',
		         showCancelButton: true,
		         confirmButtonText: 'Confirm',
		         confirmButtonClass: 'btn-confirm-all-productexcel btn btn-info'
		        }).then(function(data){
                if(data.value){
                  $.ajax({
          url: "<?=URL::to('Photoshop/Photography/done');?>",
          type:"POST",
          data:{
            "_token": "{{ csrf_token() }}",
            status:status1,
            product_id:product_id,
            category_id:category_id
         },
          success:function(response){
            swal({
                    title: 'success',
                    text: '<?php echo Config::get('constants.photoshop_message.Photography_change'); ?>',
                    type: 'success'
                   
                   
                  });
                  window.location.href = "";
          },
         });
                }
            });
  
   }else{
    swal({
            title: 'Oops!',
            text: '<?php echo Config::get('constants.photoshop_message.select'); ?>',
            type: 'error',
            showCancelButton: true,
            showConfirmButton: false,
            confirmButtonClass: 'btn btn-danger',
            cancelButtonText: 'Ok'
        });
   }
  }
</script>
@endsection