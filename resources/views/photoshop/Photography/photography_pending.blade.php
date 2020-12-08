
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
    {{ Breadcrumbs::render('photography.pending') }}
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
                    <!-- Default Tabs -->
                    <div class="col-md-12 widget-holder">
                        <div class="widget-bg">
                            <div class="widget-body clearfix">
                                <h5 class="box-title">Total Photography Filter</h5>
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
                                    <h6>Total Product <small class="text-inverse"></small></h6>
                                    <h3 class="h1"><span class="counter">{{$totalproduct}}</span></h3><i class="material-icons list-icon">add_shopping_cart</i>
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
                                    <h6>Done  <small class="text-inverse"></small></h6>
                                    <h3 class="h1"><span class="counter">0</span></h3><i class="material-icons list-icon">add_shopping_cart</i>
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
                                    <h6>Pending <small></small></h6>
                                    <h3 class="h1"><span class="counter">0</span></h3><i class="material-icons list-icon">add_shopping_cart</i>
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
						<h5 class="border-b-light-1 pb-1 mb-2 mt-0 w-100">Photography Filter</h5>
					  
					</div>
					<div class="widget-body clearfix dataTable-length-top-0">
						<form class="mr-b-30" method="post" action="javascript:void(0);">
							{{ csrf_field() }}
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<select class="form-control" name="categoryFilter" id="categoryFilter">
											<option value="">Select Category</option>
											
										</select>	
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<select class="form-control" name="colorFilter" id="colorFilter">
											<option value="">Select Color</option>
											
										</select>	
									</div>
								</div>
								
								<div class="col-md-3">
									<div class="form-group">
										<select class="form-control" name="statusFilter" id="statusFilter">
											<option value="">Select Status</option>
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
                     <button class="btn btn-default" namwe="reset" id="reset" type="button">Reset</button>
                    
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
  					  <table class="table table-striped table-center word-break mt-0"   data-toggle="datatables" >
  							<thead>
  								<tr class="bg-primary">
  									<th>Sku</th>
									  <th>Color</th>
									  <th>Category</th>
  									<th>Action</th>
  								
  								</tr>
  							</thead>
  							<tbody>
							
						
						
 @foreach ($pendinglist as $item)
<tr>
		<td>{{$item->sku}}</td>
	
	<td>{{$item->color}} Gold</td>
	<td>
	0
			
	</td>
		<td>
			<form action="" method="POST">
			<input type="hidden" value="{{$item->id}}" name="product_id"/>
			<input type="hidden" value="{{$item->categoryid}}" name="category_id"/>
				@csrf
				<select name="status" class="form-control" style="height:20px;width:150px;float: left;">
					<option value="2">Pending</option>
					<option value="1">In processing</option>
					<option value="3">Done</option>
				</select>
				<input type="submit" style="height:20px;" class="btn btn-primary" value="Submit"/>
		
			</form>
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

@endsection