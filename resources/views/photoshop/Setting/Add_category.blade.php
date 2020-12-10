
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
  <div class="col-md-12 widget-holder loader-area" style="display:none;">
    <div class="widget-bg text-center">
      <div class="loader"></div>
    </div>
  </div>
  	<div class="widget-list">
      	<div class="row">
        <div class="col-md-12 widget-holder content-area">
  				<div class="widget-bg">
       
        @if(!empty($category_detail)>0)
        <div class="col-md-4 mr-b-30">
                                        <div class="card card-outline-primary">
                                            <div class="card-header">
                                                <h5 class="card-title mt-0 mb-3"></h5>
                                                <h6 class="card-subtitle">Sub title goes here with small font</h6>
                                            </div>
                                            <div class="card-body">
                                            <form id="addctegory" method="post" action="javascript:void(0)">

                                            <div class="form-group">
                                                        <label for="username">Entity id</label>
                                                        <input class="form-control" type="text" name="entityid" id="entityid"  placeholder="Entity id">
                                                       </div>
                                             <div class="form-group">
                                                        <label for="username">Category Name</label>
                                                        <input class="form-control" type="text" name="cname" id="username"  placeholder="Category Name">
                                                       </div>
                                                  
                                               
                                            </div>
                                           
                                            <div class="text-center mr-b-30">
                                                        <button class="btn btn-primary" id="savecategory" type="submit">Submit</button>
                                                    </div>
                                        </div>
                                    </div>
                                    </form>
                                         
        @else
        <h2>Add</h2>
        @endif
          
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