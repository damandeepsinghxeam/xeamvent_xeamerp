@extends('admins.layouts.app')
@section('content')


<link rel="stylesheet" href="{{asset('public/admin_assets/dist/css/travel_module.css')}}">


<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>Product Requisition</h1>
        <ol class="breadcrumb">
          <li><a href="{{url('/employees/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
          <!-- <li><a href="{{url('/jrf/list-jrf')}}">JRF List</a></li>  -->
        </ol>
      </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-sm-12">
          <div class="box main_box p-sm success">
             @if ($errors->basic->any())
              <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <ul>
                    @foreach ($errors->basic->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
              </div>
              @endif

              <div class="alert-dismissible">
                @if(session()->has('success'))
                  <div class="alert {{(session()->get('error')) ? 'alert-danger' : 'alert-success'}}">
                    {{ session()->get('success') }}
                  </div>
                @endif
              </div>

              @if(session()->has('jrfError'))
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  {{ session()->get('jrfError') }}
                </div>
              @endif

	        <!-- form start -->
	        <form id="productRequisitionForm" action="{{ url('purchaseorder/save-product-request') }}" method="POST" enctype="multipart/form-data">
	        {{ csrf_field() }}
	           <div class="box-body form-sidechange">
	              <div class="row">
	                 <div class="col-md-6">
	                    <div class="form-group">

					
	                      <!-- for Project JRF -->
	                      	<div class="form-group">
	                           	<div class="row">
	                              	<div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                                	<label for="product_name" class="apply-leave-label label_1">Product Item<sup class="ast">*</sup></label>
	                              	</div>
	                              	<div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
                                    <select class="form-control select2 input-md basic-detail-input-style input_1" name="product_name" style="width: 100%;" id="product_name" data-placeholder="Select Product Item ">
                                    <option value="">Please select Product Item</option>
                                    @if(!$data['productitems']->isEmpty())
                                    @foreach($data['productitems'] as $Productitem)  
                                    <option value="{{$Productitem->name}}">{{$Productitem->name}}</option>
                                    @endforeach
                                    @endif  
                                    <option value="Others">Others</option>
                                    </select>
	                              </div>
	                           </div>
	                      	</div>

	                      <div class="others_product_name" style="display: none;">
	                        <div class="form-group">
	                           <div class="row">
	                              <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                                 <label for="others_product_name" class="apply-leave-label label_1">Please Specify Others<sup class="ast">*</sup></label>
	                              </div>
	                              <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
	                                  <input type="text" name="others_product_name" class="form-control input-md basic-detail-input-style input_1" id="others_product_name" placeholder="Please Specify Others"> 
	                              </div>
	                           </div>
	                        </div>
	                    </div>
					
	                      @php $user_id = Auth::id(); @endphp
	                        <input type="hidden" name="user_id" value="{{@$user_id}}">
	                        <input type="hidden" name="department_id" value="{{@$data['user_dept']->department_id}}">

	                    </div>

	                 </div>

	                 <div class="col-md-6">

						          <div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
							                  <label for="no_of_items_requested" class="apply-leave-label label_1">No. Of Product Items<sup class="ast">*</sup></label>
	                          </div>
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
							                <input autocomplete="off" type="text" class="form-control input-md basic-detail-input-style input_1" name="no_of_items_requested" id="no_of_items_requested" placeholder="Enter No. of Requested Items">
	                          </div>
	                       </div>
	                    </div>

						          <div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
							                <label for="product_description" class="apply-leave-label label_1">Enter Description<sup class="ast">*</sup></label>
	                          </div>
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
                                <textarea rows="4" cols="50" class="form-control input-md basic-detail-input-style input_1" id="product_description" name="product_description" placeholder="Brief Description"></textarea>
	                          </div>
	                       </div>
	                    </div>

	                  </div>
	                </div>
	              <div class="text-center">
	                 <input type="submit" class="btn btn-primary submit-btn-style" id="submit"  value="Submit" name="submit">
	              </div>
	           </div>
	          </form>
	        <!-- form end -->
	        </div>
	      <!-- /.box-body -->
	    </div>
     </div>
  </section>
  </div>
<!-- /.row -->


<!--Script Files starts here-->

<!--<link rel="stylesheet" href="{{asset('public/admin_assets/plugins/timepicker/bootstrap-timepicker.min.css')}}">-->
<!--<script src="{{asset('public/admin_assets/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>-->
<script src="{{asset('public/admin_assets/plugins/validations/jquery.validate.js')}}"></script>
<script src="{{asset('public/admin_assets/plugins/validations/additional-methods.js')}}"></script>

<!--Script Files ends here-->


<script>
    $("#productRequisitionForm").validate({
      rules: {
        "product_name" : {
			required : true
        },
		"others_product_name" : {
          required: true
        },
        "no_of_items_requested" : {
          required: true,
		     digits : true 
        },		
		"product_description" : {
          required: true
        },
	
      },
      errorPlacement: function(error, element) {
	    if (element.hasClass('select2')) {
	      error.insertAfter(element.next('span.select2'));
	    } else {
	      error.insertAfter(element);
	    }
	  },
      messages: {
          "product_name" : {
            required: 'Select Product Item'
          },
          "others_product_name" : {
            required: 'Select Specify Others'
          },
          "no_of_items_requested" : {
            required: 'Please Specify Number Of Requested Items'
          },
		     "product_description" : {
            required: 'Please Specify Description'
          }
        }
    });

    $.validator.addMethod("greaterThan",function (value, element, param) {
      var $min = $(param);
      if (this.settings.onfocusout) {
        $min.off(".validate-greaterThan").on("blur.validate-greaterThan", function () {
          $(element).valid();
        });
      }
      return parseInt(value) >= parseInt($min.val());
    }, "Max must be greater than min");

    // No space Method

    jQuery.validator.addMethod("noSpace", function(value, element) { 
      return value == '' || value.trim().length != 0;  
    }, "No space please and don't leave it empty");

    $('.only_numeric').bind('keyup paste', function(){
        this.value = this.value.replace(/[^0-9-]/g, '');
    });

    $(document).on('change', "#product_name", function () {
       var type = $(this).val();
       if(type == 'Others'){
        $(".others_product_name").show();
       } else {
        $(".others_product_name").hide();
       }
    });

</script>
@endsection