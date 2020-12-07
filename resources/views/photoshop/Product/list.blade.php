
@extends('layout.photo_navi')


@section('title', 'Product List')

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
  <div class="row page-title clearfix">
    {{ Breadcrumbs::render('product_list') }}
 </div>
  <div class="col-md-12 widget-holder loader-area" style="display: none;">
    <div class="widget-bg text-center">
      <div class="loader"></div>
    </div>
  </div>
  <div class="widget-list">
                <div class="row">
                    <!-- Default Tabs -->
                    <div class="col-md-12 widget-holder">
                        <div class="widget-bg">
                            <div class="widget-body clearfix">
                                <h5 class="box-title">Total Product Filter</h5>
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
                                    <h6>Total Product <small class="text-inverse">All</small></h6>
                                    <h3 class="h1"><span class="counter">{{$total}}</span></h3><i class="material-icons list-icon">add_shopping_cart</i>
                                </div>
                                <!-- /.widget-counter -->
                            </div>
                            <!-- /.widget-body -->
                        </div>
                        <!-- /.widget-bg -->
                    </div>
                    <!-- /.widget-holder -->
                    <!-- Counter: Subscriptions -->
                    <div class="col-md-4 col-sm-6 widget-holder widget-full-height">
                        <div class="widget-bg bg-color-scheme text-inverse">
                            <div class="widget-body clearfix">
                                <div class="widget-counter">
                                    <h6>Pending Product <small class="text-inverse"></small></h6>
                                    <h3 class="h1"><span class="counter">{{$pending}}</span></h3><i class="material-icons list-icon">add_shopping_cart</i>
                                </div>
                                <!-- /.widget-counter -->
                            </div>
                            <!-- /.widget-body -->
                        </div>
                        <!-- /.widget-bg -->
                    </div>
                    <!-- /.widget-holder -->
                    <!-- Counter: Users -->
                    <div class="col-md-4 col-sm-6 widget-holder widget-full-height">
                        <div class="widget-bg">
                            <div class="widget-body clearfix">
                                <div class="widget-counter">
                                    <h6>In Process<small></small></h6>
                                    <h3 class="h1"><span class="counter">{{$done}}</span></h3><i class="material-icons list-icon">add_shopping_cart</i>
                                </div>
                                <!-- /.widget-counter -->
                            </div>
                            <!-- /.widget-body -->
                        </div>
                        <!-- /.widget-bg -->
                    </div>
                    <!-- /.widget-holder -->
                    <!-- Counter: Pageviews -->
                    
                    <!-- /.widget-holder -->
                </div>
         </div>
                  <div class="tab-pane active" id="profile-tab">
						<div class="row">
			<div class="col-md-12 widget-holder content-area">
				<div class="widget-bg">
					<div class="widget-heading clearfix">
						<h5 class="border-b-light-1 pb-1 mb-2 mt-0 w-100">Filter</h5>
					  
					</div>
					<div class="widget-body clearfix dataTable-length-top-0">
						<form class="mr-b-30" method="post" action="javascript:void(0);">
							{{ csrf_field() }}
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<select class="form-control" name="category">
											<option value="null">Select Category</option>
											@foreach($category as $cat){
												<option value={{$cat->entity_id}}>{{$cat->value}}</option>
											
											@endforeach
										</select>	
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<select class="form-control" name="color" id="colorFilter">
											<option value="null">Select Color</option>
											@foreach($color->unique('color') as $user){
												<option>{{$user->color}}</option>
											
											@endforeach
										</select>	
									</div>
								</div>
								
								<div class="col-md-3">
									<div class="form-group">
										<select class="form-control" name="status" id="statusFilter">
											<option value="null">Select Status</option>
											<option value="0">Pending</option>
											<option value="1">Done</option>
										</select>	
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<input class="form-control" name="sku" id="sku" style="height: 43px;" placeholder="Sku Search" type="text">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<input class="btn btn-primary" style="    height: 43px;" id="searchfilter"   type="submit" value="Apply">
                                        <button class="btn btn-default" id="searchreset" type="button">Reset</button>
                    
                                	</div>
								</div>
							</div>
						</form>


								</div>
							</div>
						</div>
					</div>
							</div>
												
                                    </div>
                                 </div>
                            </div>
                         </div>
                      </div>
                   </div>
               </div>
           
  	<div class="widget-list">

		
      	<div class="row">
  			<div class="col-md-12 widget-holder content-area">
  				<div class="widget-bg">
  					<div class="widget-heading clearfix">
  						<h5 class="border-b-light-1 pb-1 mb-2 mt-0 w-100">Product List</h5>
						
  					</div>
  					<div class="widget-body clearfix dataTable-length-top-0">
  						
	                    <table class="table table-striped table-center word-break mt-0"   id="photographytable" >
  							<thead>
  								<tr class="bg-primary">
  									<th>Sku</th>
									  <th>Color</th>
									  <th>Category</th>
									 <th>Action</th>
  								
  								</tr>
  							</thead>
  							<tbody>
                     @foreach($list as $item)

	                <tr>
		                <td>{{$item->sku}}</td>
	                    <td>{{$item->color}}</td>
						<td>0</td>
						
                      <td>
						<?php 
						if($item->status=='0')
						{
							?>
							<a href="" class="color-content table-action-style"><i class="material-icons sm-18">delete</i></a>
							&nbsp;	&nbsp;&nbsp;
							<a href="javascript:void(0)"  class="color-content table-action-style"><i class="material-icons sm-18">remove_red_eye</i></a>
							
							<?php 
						}
						else {
							?>
							
							<a href="delete/{{$item->id}}"   class="color-content table-action-style"><i class="material-icons sm-18">delete</i></a>
							
							<a href="view/{{$item->id}}" class="color-content table-action-style"><i class="material-icons sm-18">remove_red_eye</i></a>
							<?php 
						}
						?>
						</td>

    </tr>
   
	@endforeach
	  </tbody>
							  <tfoot>
								<tr class="bg-primary">
									<th>Sku</th>
									<th>Color</th>
									<th>Category</th>
								
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
<input type="hidden" id="photographyproductAjax" value="<?=URL::to('Photoshop/Product/listAjax');?>">
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
<script type="text/javascript">
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
	var table = $('#photographytable').DataTable({
		"dom": "<'row mb-2 align-items-center'<'col-auto dataTable-length-tb-0'l><'col'B>><'row'<'col-md-12' <'user-roles-main' t>>><'row'<'col-md-3'i><'col-md-6 ml-auto'p>>",
  "lengthMenu": [[10, 50, 100, 200,500], [10, 50, 100, 200,500]],
  "buttons": [
    $.extend( true, {}, buttonCommon, {
      extend: 'csv',
      footer: false,
      title: 'Photography-Product-List',
      className: "btn btn-primary btn-sm px-3",
      exportOptions: {
          columns: [0,1,2],
          orthogonal: 'export'
      }
    }),
    $.extend( true, {}, buttonCommon, {
      extend: 'excel',
      footer: false,
      title: 'Photography-Product-List',
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
  "deferLoading": <?=$pending?>,
  "processing": true,
  "serverSide": true,
  "searching": false,
  "serverMethod": "post",
  "ajax":{
    "url": $("#photographyproductAjax").val(),
    "data": function(data, callback){
        console.log("rr");
      showLoader();
      data._token = "{{ csrf_token() }}";
      var skusearch = $('#sku').val();
  
      if(skusearch != ''){
        data.skusearch = skusearch;
    
      }

      var colorFilter=$('#colorFilter').val();
      if(colorFilter != ''){
          data.colorFilter=colorFilter;
      }

      var statusFilter=$('statusFilter').val();
      if(statusFilter != ''){
          data.statusFilter=statusFilter;
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
	</script>
@endsection