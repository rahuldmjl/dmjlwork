
@extends('layout.photo_navi')


@section('title', 'Shoot Module')

@section('distinct_head')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link href="<?=URL::to('/');?>/cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">

@endsection

@section('body_class', 'header-light sidebar-dark sidebar-expandheader-light sidebar-dark sidebar-expand')

@section('content')
<main class="main-wrapper clearfix">
  <!-- Page Title Area -->
  <div class="row page-title clearfix">
  Photography Shoot
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
                    <!-- Counter: Sales -->
                    <div class="col-md-4 col-sm-6 widget-holder widget-full-height">
                        <div class="widget-bg bg-primary text-inverse">
                            <div class="widget-body">
                                <div class="widget-counter">
                                    <h6>Pending in {{$title}}<small class="text-inverse"></small></h6>
                                    <h3 class="h1"><span class="counter">{{$pending->count()}}</span></h3><i class="material-icons list-icon">add_shopping_cart</i>
                                </div>
                            </div>
                         </div>
                     </div>
                  
                    <div class="col-md-4 col-sm-6 widget-holder widget-full-height">
                        <div class="widget-bg bg-color-scheme text-inverse">
                            <div class="widget-body clearfix">
                                <div class="widget-counter">
                                    <h6>Done in {{$title}} <small class="text-inverse"></small></h6>
                                    <h3 class="h1"><span class="counter">{{$done->count()}}</span></h3><i class="material-icons list-icon">add_shopping_cart</i>
                                </div>
                             </div>
                         </div>
                     </div>
                    
                    <div class="col-md-4 col-sm-6 widget-holder widget-full-height">
                        <div class="widget-bg">
                            <div class="widget-body clearfix">
                                <div class="widget-counter">
                                    <h6>Rework in {{$title}}<small></small></h6>
                                    <h3 class="h1"><span class="counter">{{$rework->count()}}</span></h3><i class="material-icons list-icon">add_shopping_cart</i>
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
                <div class="row">
                    <!-- Default Tabs -->
                    <div class="col-md-12 widget-holder">
                        <div class="widget-bg">
                        @if (session()->has('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
@endif
                            <div class="widget-body clearfix">
                                <h5 class="box-title">Total {{$title}} </h5>
                                <div class="tabs">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item active "><a class="nav-link" href="#home-tab" data-toggle="tab" aria-expanded="true">Pending</a>
                                        </li>
                                        <li class="nav-item "><a class="nav-link" href="#profile-tab" data-toggle="tab" aria-expanded="true">Done</a>
                                        </li>
                                        <li class="nav-item "><a class="nav-link" href="#shoot-tab" data-toggle="tab" aria-expanded="true">Rework</a>
                                        </li>
                                    </ul>
                                    <!-- /.nav-tabs -->
                                  <div class="tab-content">
                                      <div class="tab-pane active" id="home-tab">
										<div class="row">
                                    
  			<div class="col-md-12 widget-holder content-area">
  				<div class="widget-bg">
  					<div class="widget-heading clearfix">
  						<h5 class="border-b-light-1 pb-1 mb-2 mt-0 w-100">{{$title}} Pending List </h5>
                        
					  </div>
					
  					<div class="widget-body clearfix dataTable-length-top-0">
  						
	              <table class="table table-striped table-center word-break mt-0"   id="{{$title}}datatable">
  							<thead>
  								<tr class="bg-primary">
								   <th>Sku</th>
									<th>Color</th>
                                    <th>Category</th>
                                    <th>Action</th>
  								 </tr>
  							</thead>
  							<tbody>
                            @foreach($pending as $pending)
							 <tr>
						      <td>{{$pending->sku}}</td>
							   <td>{{$pending->color}}</td>
							   <td>{{$pending->name}} </td>
						        <td>
                               	<select class="form-control" style="height:20px;width:220px;" onchange="action(this.value)">
                                        <option value="">Select Status</option>
                                        <option value="done/{{$pending->id}}/{{$pending->category_id}}/3">done</option>
                                        <option value="pending/{{$pending->id}}/{{$pending->category_id}}/2">pending</option>
                                    </select>
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
                               <div class="tab-pane " id="profile-tab">
                                <div class="row">
                                <div class="col-md-12 widget-holder content-area">
  			
                                <div class="widget-bg">
  					<div class="widget-heading clearfix">
  						<h5 class="border-b-light-1 pb-1 mb-2 mt-0 w-100">{{$title}} Done List </h5>
                        
					  </div>
					
  					<div class="widget-body clearfix dataTable-length-top-0">
  						
                                <table class="table table-striped table-center word-break mt-0"   id="{{$title}}datatabledone">
  							<thead>
  								<tr class="bg-primary">
								 	<th>Sku</th>
									<th>Color</th>
                                   <th>Category</th>
                                   <th>Action</th>
  								
  								</tr>
  							</thead>
  							<tbody>
             
                              @foreach($done as $done)
						
                              <tr>
						      <td>{{$done->sku}}</td>
							   <td>{{$done->color}}</td>
							   <td>{{$done->name}} </td>
						      
							   <td>
                               <select class="form-control" style="height:20px;width:220px;" onchange="action(this.value)">
                                        <option value="">Select Status</option>
                                        <option value="rework/{{$done->product_id}}/{{$done->category_id}}/4">Rework</option>
                                    </select>
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
                                <div class="tab-pane " id="shoot-tab">
                                <div class="row">
                                <div class="col-md-12 widget-holder content-area">
  			
              <div class="widget-bg">
    <div class="widget-heading clearfix">
        <h5 class="border-b-light-1 pb-1 mb-2 mt-0 w-100">{{$title}} Rework List </h5>
      
    </div>
  
    <div class="widget-body clearfix dataTable-length-top-0">
        
              <table class="table table-striped table-center word-break mt-0"   id="{{$title}}datatablerework">
            <thead>
                <tr class="bg-primary">
                    <th>Sku</th>
                  <th>Color</th>
                   <th>Category</th>
                   <th>Action</th>
                
                </tr>
            </thead>
            <tbody>
            @foreach($rework as $r)
           <tr>
             <td>{{$r->sku}}</td>
             <td>{{$r->color}}</td>
             <td>{{$r->name}}</td>
          
             <td>
                     <select name="status" id="status" onchange="action(this.value)" class="form-control" style="height:20px;width:220px;">
                         <option value="">select status</option>
                         <option value="done/{{$r->product_id}}/{{$r->category_id}}/3">Done</option>
                     </select>
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
                                  </div>

        
                               </div>
		
  <!-- /.widget-list -->
</main>
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
    $(document).ready(function () {
        var tablename="{{$title}}datatable";
        $('#'+tablename).DataTable();
        $('#'+tablename+"done").DataTable();
        $('#'+tablename+"rework").DataTable();

    });
function action(data){
    var status=data.split("/");
    var model="{{$title}}";
    var table="shoot_"+model.toLowerCase()+"s";
    var url = $(this).data('href');
   var t=model.toLowerCase();
    
    $.ajax({
       
          url: "<?=URL::current();?>",
          type:"POST",
          data:{
            "_token": "{{ csrf_token() }}",
            category_id:status[2],
            pid:status[1],
            status:status[0],
            statusmode:status[3],
            attribute:table,
            model:model
          
         },
          success:function(response){
              console.log(response);
            swal({
                    title: response.type,
                    text: response.success,
                    type: "success",
                    buttonClass: 'btn btn-primary'
                   
                  });
                  window.location.href = t;
          },
         });
}
</script>
@endsection
