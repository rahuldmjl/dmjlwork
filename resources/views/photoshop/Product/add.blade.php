
@extends('layout.photo_navi')


@section('title', 'Add Product')

@section('distinct_head')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link href="<?=URL::to('/');?>/cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">

@endsection

@section('body_class', 'header-light sidebar-dark sidebar-expandheader-light sidebar-dark sidebar-expand')

@section('content')
<main class="main-wrapper clearfix">
  <!-- Page Title Area -->
  <div class="row page-title clearfix">
    {{ Breadcrumbs::render('product_add') }}
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
                                <h5 class="box-title">Upload Product</h5>
                                <div class="tabs">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item active"><a class="nav-link" href="#home-tab" data-toggle="tab" aria-expanded="true">Home</a>
                                        </li>
                                        <li class="nav-item "><a class="nav-link" href="#profile-tab" data-toggle="tab" aria-expanded="true">Single Product</a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link" href="#messages-tab" data-toggle="tab" aria-expanded="true">BulkUpload</a>
                                        </li>
                                       
                                    </ul>
                                    <!-- /.nav-tabs -->
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="home-tab">
                                        <div class="row">
                    <!-- Counter: Sales -->
                    <div class="col-md-3 col-sm-6 widget-holder widget-full-height">
                        <div class="widget-bg bg-primary text-inverse">
                            <div class="widget-body">
                                <div class="widget-counter">
                                    <h6>Total Sales <small class="text-inverse">Last week</small></h6>
                                    <h3 class="h1">&dollar;<span class="counter">741</span></h3><i class="material-icons list-icon">add_shopping_cart</i>
                                </div>
                                <!-- /.widget-counter -->
                            </div>
                            <!-- /.widget-body -->
                        </div>
                        <!-- /.widget-bg -->
                    </div>
                    <!-- /.widget-holder -->
                    <!-- Counter: Subscriptions -->
                    <div class="col-md-3 col-sm-6 widget-holder widget-full-height">
                        <div class="widget-bg bg-color-scheme text-inverse">
                            <div class="widget-body clearfix">
                                <div class="widget-counter">
                                    <h6>New Subscriptions <small class="text-inverse">Last month</small></h6>
                                    <h3 class="h1"><span class="counter">346</span></h3><i class="material-icons list-icon">event_available</i>
                                </div>
                                <!-- /.widget-counter -->
                            </div>
                            <!-- /.widget-body -->
                        </div>
                        <!-- /.widget-bg -->
                    </div>
                    <!-- /.widget-holder -->
                    <!-- Counter: Users -->
                    <div class="col-md-3 col-sm-6 widget-holder widget-full-height">
                        <div class="widget-bg">
                            <div class="widget-body clearfix">
                                <div class="widget-counter">
                                    <h6>New Users <small>Last 7 days</small></h6>
                                    <h3 class="h1"><span class="counter">625</span></h3><i class="material-icons list-icon">public</i>
                                </div>
                                <!-- /.widget-counter -->
                            </div>
                            <!-- /.widget-body -->
                        </div>
                        <!-- /.widget-bg -->
                    </div>
                    <!-- /.widget-holder -->
                    <!-- Counter: Pageviews -->
                    <div class="col-md-3 col-sm-6 widget-holder widget-full-height">
                        <div class="widget-bg">
                            <div class="widget-body clearfix">
                                <div class="widget-counter">
                                    <h6>Total PageViews <small>Last 24 Hours</small></h6>
                                    <h3 class="h1"><span class="counter">2748</span></h3><i class="material-icons list-icon">show_chart</i>
                                </div>
                                <!-- /.widget-counter -->
                            </div>
                            <!-- /.widget-body -->
                        </div>
                        <!-- /.widget-bg -->
                    </div>
                    <!-- /.widget-holder -->
                </div>   
                   </div>
                      <div class="tab-pane " id="profile-tab">
                      <div class="col-md-6 widget-holder">
                        <div class="widget-bg">
                            <div class="widget-body clearfix">
                                <h5 class="box-title mr-b-0">Create Photography Product</h5>
                                <form class="mr-t-30" action="" method="POST">
                                @csrf
                                    <div class="form-group row">
                                        <label for="sample3UserName" class="text-sm-right col-sm-3 col-form-label">SKU Number</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                               
                                                <input type="text" class="form-control" name="sku" id="sample3UserName" placeholder="SKU Number">
                                            </div>
                                            <!-- /.input-group -->
                                        </div>
                                        <!-- /.col-sm-9 -->
                                    </div>
                                    <!-- /.form-group -->
                                    <div class="form-group row">
                                        <label for="sample3Password" class="text-sm-right col-sm-3 col-form-label">Color</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                            <select class="form-control" name="color" data-placeholder="Choose" data-toggle="select">
                                           <option>Select Color</option>
                                           @foreach($color as $col)
                                           <option value="{{$col->name}}">{{$col->name}}</option>
                                           @endforeach
                                        </select>  
                                        </div>
                                        </div>
                                        <!-- /.col-sm-9 -->
                                    </div>
                                    <div class="form-group row">
                                        <label for="sample3Password" class="text-sm-right col-sm-3 col-form-label">Category</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                            <select class="form-control" name="category" data-placeholder="Choose" data-toggle="select">
                                           <option>Select Category</option>
                                           @foreach($category as $cat)
                                      <option value="{{$cat->id}}">{{$cat->name}}</option>
                                           @endforeach
                                        </select>
                                            </div>
                                            <!-- /.input-group -->
                                        </div>
                                        <!-- /.col-sm-9 -->
                                    </div>
                                    <div class="form-actions">
                                        <div class="form-group row">
                                            <div class="col-sm-9 ml-auto btn-list">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                <button type="button" class="btn btn-default">Cancel</button>
                                            </div>
                                            <!-- /.col-sm-12 -->
                                        </div>
                                        <!-- /.form-group -->
                                    </div>
                                    <!-- /.form-actions -->
                                </form>
                            </div>
                            <!-- /.widget-body -->
                        </div>
                        <!-- /.widget-bg -->
                    </div>
                    <!-- /.widget-holder -->
              
                       
                       
                      </div>
                                        <div class="tab-pane" id="messages-tab">
                                            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis,
                                                ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.</p>
                                            <p>Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt.Cras dapibus.
                                                Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim.</p>
                                        </div>
                                       
                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- /.tabs -->
                            </div>
                            <!-- /.widget-body -->
                        </div>
                        <!-- /.widget-bg -->
                 
            
      
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


<script src="<?=URL::to('/');?>/js/jquery.validate.min.js"></script>
<script src="<?=URL::to('/');?>/js/additional-methods.min.js"></script>

@endsection