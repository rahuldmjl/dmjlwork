
@extends('layout.photo_navi')

<?php
use App\Helpers\PhotoshopHelper;
?>
@section('title', 'Product Status')

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
    product Status
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
                        @if (session()->has('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
@endif
                            <div class="widget-body clearfix">
                                <h5 class="box-title">Product Assign List</h5>
                                <div class="tabs">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item active "><a class="nav-link" href="#home-tab" data-toggle="tab" aria-expanded="true">Pending</a>
                                        </li>
                                        <li class="nav-item "><a class="nav-link" href="#inprocess-tab" data-toggle="tab" aria-expanded="true">In-progress</a>
                                        </li>
                                        <li class="nav-item "><a class="nav-link" href="#complete-tab" data-toggle="tab" aria-expanded="true">Completed</a>
                                        </li>
                                        <li class="nav-item "><a class="nav-link" href="#rework-tab" data-toggle="tab" aria-expanded="true">Rework</a>
                                        </li>
                                    </ul>
                                    <!-- /.nav-tabs -->
                                    <div class="tab-content">
                 
                            <div class="tab-pane active"  id="home-tab">
                            <div class="widget-body clearfix dataTable-length-top-0">
  						
                          <table class="table table-striped table-center word-break mt-0 checkbox checkbox-primary"   id="photographytable" >
                                        <thead>
                              <tr class="bg-primary">
                              <th>Sr No</th>
                               <th>Sku</th>
                               <th>Color</th>
                               <th>Category</th>
                               <th>User</th>
                               <th>Action</th>
                                            
                               </tr>
                                        </thead>
                                        <tbody>
                                   <?php 
                                   $i=1;?> 
                                @foreach($data as $pending)  
                                
                              <tr>
                                     
                            <td><?=$i++;?></td>
                             <td>c</td>
                             <td>0</td>
                              <td>0</td>
                             <td>0</td>
                              <td>0</td>
          
                                </tr>
                                @endforeach
               </tbody>
                           <tfoot>
                                          <tr class="bg-primary">
                                           
                                             <th>Sr No</th>
                                              <th>Sku</th>
                                              <th>Color</th>
                                              <th>Category</th>
                                            <th>User</th>
                                             <th>Action</th>
                                          
                                          </tr>
                                      </tfoot>
                                    </table>
                        </div>
                                        
                             </div>
                            <div class="tab-pane " id="inprocess-tab">
                            <div class="widget-body clearfix dataTable-length-top-0">
  						
                          <table class="table table-striped table-center word-break mt-0 checkbox checkbox-primary"   id="photographytable" >
                                        <thead>
                                            <tr class="bg-primary">
                              <th>Sr No</th>
                               <th>Sku</th>
                               <th>Color</th>
                               <th>Category</th>
                              <th>User</th>
                              <th>Action</th>
                                            
                               </tr>
                                        </thead>
                                        <tbody>
                                  
                              <tr>
                                     
                            <td>0</td>
                             <td>0</td>
                             <td>0</td>
                              <td>0</td>
                             <td>0</td>
                              <td>0</td>
          
                                </tr>
               </tbody>
                           <tfoot>
                                          <tr class="bg-primary">
                                           
                                             <th>Sr No</th>
                                              <th>Sku</th>
                                              <th>Color</th>
                                              <th>Category</th>
                                            <th>User</th>
                                             <th>Action</th>
                                          
                                          </tr>
                                      </tfoot>
                                    </table>
                        </div>
                                        
                                    
                              </div>
                              <div class="tab-pane " id="complete-tab">
                              <div class="widget-body clearfix dataTable-length-top-0">
  						
                          <table class="table table-striped table-center word-break mt-0 checkbox checkbox-primary"   id="photographytable" >
                                        <thead>
                                            <tr class="bg-primary">
                              <th>Sr No</th>
                               <th>Sku</th>
                               <th>Color</th>
                               <th>Category</th>
                              <th>User</th>
                              <th>Action</th>
                                            
                               </tr>
                                        </thead>
                                        <tbody>
                                  
                              <tr>
                                     
                            <td>0</td>
                             <td>0</td>
                             <td>0</td>
                              <td>0</td>
                             <td>0</td>
                              <td>0</td>
          
                                </tr>
               </tbody>
                           <tfoot>
                                          <tr class="bg-primary">
                                           
                                             <th>Sr No</th>
                                              <th>Sku</th>
                                              <th>Color</th>
                                              <th>Category</th>
                                            <th>User</th>
                                             <th>Action</th>
                                          
                                          </tr>
                                      </tfoot>
                                    </table>
                        </div>
                                        
                                          
                              </div>
                              <div class="tab-pane " id="rework-tab">
                                            <h1>
                                            <div class="widget-body clearfix dataTable-length-top-0">
  						
                          <table class="table table-striped table-center word-break mt-0 checkbox checkbox-primary"   id="photographytable" >
                                        <thead>
                                            <tr class="bg-primary">
                              <th>Sr No</th>
                               <th>Sku</th>
                               <th>Color</th>
                               <th>Category</th>
                              <th>User</th>
                              <th>Action</th>
                                            
                               </tr>
                                        </thead>
                                        <tbody>
                                  
                              <tr>
                                     
                            <td>0</td>
                             <td>0</td>
                             <td>0</td>
                              <td>0</td>
                             <td>0</td>
                              <td>0</td>
          
                                </tr>
               </tbody>
                           <tfoot>
                                          <tr class="bg-primary">
                                           
                                             <th>Sr No</th>
                                              <th>Sku</th>
                                              <th>Color</th>
                                              <th>Category</th>
                                            <th>User</th>
                                             <th>Action</th>
                                          
                                          </tr>
                                      </tfoot>
                                    </table>
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