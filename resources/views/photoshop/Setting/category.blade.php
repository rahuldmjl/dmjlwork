
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
    {{ Breadcrumbs::render('photgraphy_setting_category') }}
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
				  <a href="javascript:void(0);" style="float:right" data-toggle="modal" data-target="#category-modal" class="btn btn-primary"><i class="material-icons list-icon fs-24">playlist_add</i> Add Category</a>
  					<div class="widget-heading clearfix">
  						<h5 style="float:left" class="border-b-light-1 pb-1 mb-2 mt-0 w-100">Category List</h5>
						  </div>
					  @if(Session::has('success'))
					  <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('success') }}</p>
					  @endif
  					<div class="widget-body clearfix dataTable-length-top-0">
  						
	                    <table class="table table-striped table-center word-break mt-0"   data-toggle="datatables" >
  							<thead>
  								<tr class="bg-primary">
  									<th>id</th>
									  <th>Category Id</th>
                                      <th>Category Name</th>
                                      
  									<th>Action</th>
  								
  								</tr>
  							</thead>
  							<tbody>
								
							  @foreach($category_list as $key=>$cate)
				
					<tr>
						<td>{{$key+1}}</td>
						<td>{{$cate->entity_id}} </td>
						<td>{{$cate->name}}</td>
						<td>
						<a href="javascript:void(0)" class="color-content table-action-style " ><i class="material-icons md-18" title="View Transaction">remove_red_eye</i></a>
						&nbsp;
						<a href="{{ route('photography.setting.category.edit',['id'=>$cate->id]) }}" class="color-content table-action-style " ><i class="material-icons md-18" title="View Transaction">mode_edit</i></a>
						&nbsp;
						<a href="javascript:void(0)" class="color-content table-action-style " ><i class="material-icons md-18" title="View Transaction">delete</i></a>
						&nbsp;
					
						</td>
					
					</tr>
					@endforeach

							  </tbody>
							  <tfoot>
								<tr class="bg-primary">
								<th>id</th>
									  <th>Category Id</th>
                                      <th>Category Name</th>
                                      
  									<th>Action</th>
								</tr>
							</tfoot>
	  					</table>
  					</div>
  				</div>

  <!-- /.widget-list -->

<!-- /.main-wrappper -->
<div id="category-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <div class="modal-body">
                                               <form id="addctegory" action="javascript:void(0);" method="post">
                                                   
                                            <div class="form-group">
                                                        <label for="username">Entity id</label>
                                                        <input class="form-control" type="text" name="entityid" id="entityid"  placeholder="Entity id">
                                                       </div>
                                             <div class="form-group">
                                                        <label for="username">Category Name</label>
                                                        <input class="form-control" type="text" name="cname" id="username"  placeholder="Category Name">
                                                       </div>
                                                   <button class="btn btn-primary" id="savecategory" type="submit">Submit</button>
                                          
                                            </div>
										      
                                                </form>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
								</main>
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
<script>
 
</script>
@endsection