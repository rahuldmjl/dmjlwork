
@extends('layout.photo_navi')


@section('title', 'Work Assignment')

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
    Work Assignment
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
                                <h5 class="box-title">Work Assignment</h5>
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
                                    <h3 class="h1"><span class="counter">0</span></h3><i class="material-icons list-icon">add_shopping_cart</i>
                                </div>
                            </div>
                         </div>
                     </div>
                  
                    <div class="col-md-4 col-sm-6 widget-holder widget-full-height">
                        <div class="widget-bg bg-color-scheme text-inverse">
                            <div class="widget-body clearfix">
                                <div class="widget-counter">
                                    <h6>Done  <small class="text-inverse"></small></h6>
                                    <h3 class="h1"><span class="counter">0</span></h3><i class="material-icons list-icon">add_shopping_cart</i>
                                </div>
                             </div>
                         </div>
                     </div>
                    
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
                            <div class="col-md-4">
									<div class="form-group">
										<select class="form-control" name="departmentfilter" id="departmentfilter">
											<option value="">Select Department</option>
											<option value="photographies">Photography</option>
                                            <option value="psds">Psd</option>
									
                                        </select>	
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<select class="form-control" name="categoryFilter" id="categoryFilter">
											<option value="">Select Category</option>
                                            @foreach($category as $cat)
                                            <option  value="{{$cat->entity_id}}">{{$cat->name}}</option>
                                            @endforeach
										</select>	
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<select class="form-control" name="colorFilter" id="colorFilter">
											<option value="">Select Color</option>
                                            @foreach($color as $col)
                                            <option  value="{{$col->name}}">{{$col->name}}</option>
                                            @endforeach
										</select>	
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<input class="btn btn-primary" style="height: 43px;" id="searchfilter"   type="submit" value="Apply">
                                        <button class="btn btn-default" name="reset" id="reset" type="button">Reset</button>
                    
                                	</div>
								</div>
							</div>
						</form>
				</div>
        </div>
        </div>
      	<div class="row">
  			<div class="col-md-12 widget-holder content-area">
  				<div class="widget-bg">
  					<div class="widget-heading clearfix">
  						<h5 class="border-b-light-1 pb-1 mb-2 mt-0 w-100">Product List</h5>
						<div class="col-md-4" style="float: right;">
                        <select class="form-control" id="userassign" name="userassign">
                            <option value="">Select User</option>
                            @foreach($user as $u)
                            <option value="{{$u->id}}">{{$u->name}}</option>
                            @endforeach
                        </select>
                        <input type="submit" class="btn btn-primary" name="assignuser" id="assignuser" value="Assign"/>
  				
                        </div>
                   	</div>
  					<div class="widget-body clearfix dataTable-length-top-0">
  						
	                    <table class="table table-striped table-center word-break mt-0"  id="workassigndatatable">
  							<thead>
  								<tr class="bg-primary">
                                  <th  data-orderable="false" class="checkboxth"><label><input class="form-check-input" type="checkbox" name="chkAllProduct" id="chkAllProduct"><span class="label-text"></span></label></th>
               
								  	<th>Sr No</th>
  									<th>Sku</th>
									<th>Color</th>
									<th>Category</th>
								</tr>
  							</thead>
  						<tbody>
                          <?php $i=1;?>
                        
                          @foreach($data as $d)
		                      <tr>
                                     <td><label><input class="form-check-input chkProduct" data-id="{{$d->pid}}" value="{{$d->pid}}" type="checkbox" name="chkProduct[]" id="chkProduct{{$d->pid}}"><span class="label-text"></label></td>
                                    <td><?=$i++;?></td>
                                    <td>{{$d->sku}}</td>
                                    <td>{{$d->color}}</td>
                                    <td>{{$d->categoryname}}</td>
                             </tr>
						@endforeach
			             </tbody>
							<tfoot>
								<tr class="bg-primary">
                                <th></th>
               
								    <th>Sr No</th>
									<th>Sku</th>
									<th>Color</th>
									<th>Category</th>
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
<input type="hidden" id="workassignlistAjax" value="<?=URL::to('Photoshop/Product/workassignlist');?>">

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
	var table = $('#workassigndatatable').DataTable({
        "dom": "<'row mb-2 align-items-center'<'col-auto dataTable-length-tb-0'l><'col'B>><'row'<'col-md-12' <'user-roles-main' t>>><'row'<'col-md-3'i><'col-md-6 ml-auto'p>>",
  "lengthMenu": [[10, 50, 100, 200,500], [10, 50, 100, 200,500]],
  "buttons": [
    $.extend( true, {}, buttonCommon, {
      extend: 'csv',
      footer: false,
      title: 'work-assign-List',
      className: "btn btn-primary btn-sm px-3",
      exportOptions: {
          columns: [0,1,2,3],
          orthogonal: 'export'
      }
    }),
    $.extend( true, {}, buttonCommon, {
      extend: 'excel',
      footer: false,
      title: 'work-assign-List',
      className: "btn btn-primary btn-sm px-3",
      exportOptions: {
          columns: [0,1,2,3],
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
  "deferLoading": <?php echo $data->count()?>,
  "processing": true,
  "serverSide": true,
  "searching": false,
  "serverMethod": "post",
  "ajax":{
    "url": $("#workassignlistAjax").val(),
    "data": function(data, callback){
      console.log(data);
      showLoader();
      data._token = "{{ csrf_token() }}";
      var departmentfilter = $('#departmentfilter').children("option:selected").val();
     if(departmentfilter != ''){
       data.departmentfilter=departmentfilter;
        }
        var categoryFilter = $('#categoryFilter').children("option:selected").val();
     if(categoryFilter != ''){
       data.categoryFilter=categoryFilter;
        }
        
       var colorFilter = $('#colorFilter').children("option:selected").val();
     if(colorFilter != ''){
       data.colorFilter=colorFilter;
        }
      },
    complete: function(response){
      hideLoader();
    }
  }
    });
    $('#searchfilter').click(function(){
    table.draw();
  });
  $('#reset').click(function(){
    $('#departmentfilter option[value=""]').attr('selected','selected');
	$('#categoryFilter option[value=""]').attr('selected','selected');
	$('#colorFilter option[value=""]').attr('selected','selected');
    $('#departmentfilter').on('change', function() {
      if(this.value == ''){
        $('#departmentfilter option[value=""]').attr('selected','selected');
      }else{
        $('#departmentfilter option[value=""]').removeAttr('selected','selected');
      }
	});
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
    table.draw();
  });
  $("#chkAllProduct").click(function(){
    $('.chkProduct').prop('checked', this.checked);
});
$('#assignuser').click(function(){
   var user=$('#userassign').val();
  if(user ==''){
                swal({
                    title: 'error',
                    text: 'Select user From Product Assign',
                    type: 'error',
                    buttonClass: 'btn btn-primary'
                   
                  });
  }else{
    var myCheckboxes = new Array();
        $("input:checked").each(function() {
           myCheckboxes.push($(this).val());
        });
        var table=$('#departmentfilter').val();
        var tablename;
        if(table ==''){
            tablename="photographies";
        }else{
            tablename=table;
        }
        var len=myCheckboxes.length;
        if(len !=0){
            $.ajax({
          url: "<?=URL::to('Photoshop/Product/userassign');?>",
          type:"POST",
          data:{
            "_token": "{{ csrf_token() }}",
            userid:user,
            pid:myCheckboxes,
            tablename:tablename
         },
          success:function(response){
            swal({
                    title: 'success',
                    text: response.success,
                    type: 'success',
                    buttonClass: 'btn btn-primary'
                   
                  });
                  window.location.href = "";
          },
         });
        }else{
            swal({
                    title: 'error',
                    text: "Check Product",
                    type: 'error',
                    buttonClass: 'btn btn-primary'
                   
                  }); 
        }
  }
});
</script>
@endsection