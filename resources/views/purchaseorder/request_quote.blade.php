@extends('admins.layouts.app')
@section('content')

<link rel="stylesheet" href="{{asset('public/admin_assets/plugins/timepicker/bootstrap-timepicker.min.css')}}">
<script src="{{asset('public/admin_assets/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script src="{{asset('public/admin_assets/plugins/validations/jquery.validate.js')}}"></script>
<script src="{{asset('public/admin_assets/plugins/validations/additional-methods.js')}}"></script>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>Request Vendor For Product Quotation</h1>
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
          <div class="box box-primary success">
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
	        <form id="productRequisitionForm" action="{{ url('purchaseorder/save-request-quote') }}" method="POST" enctype="multipart/form-data">
	        {{ csrf_field() }}
	           <div class="box-body jrf-form-body">
	              <div class="row">
	                 <div class="col-md-6">
	                    <div class="form-group">


						<div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="name_of_firm" class="apply-leave-label">Select Vendor<sup class="ast">*</sup></label>
	                          </div>
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
	                             <select class="form-control select2 input-sm basic-detail-input-style" name="name_of_firm[]" multiple="multiple" style="width: 100%;" id="name_of_firm" data-placeholder="Select Vendors">
	                                @if(!empty($vendorDetail))
                                    @foreach($vendorDetail as $vendorId => $vandorName)  
                                    <option value="{{$vendorId}}">{{$vandorName}}</option>
                                    @endforeach
	                                @endif
	                             </select>
	                          </div>
	                       </div>
	                    </div>
					           	@php $user_id = Auth::id(); @endphp
	                        <input type="hidden" name="user_id" value="{{@$user_id}}">
	                    </div>

	                 </div>

	                 <div class="col-md-6">

					 <div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="product_request_title" class="apply-leave-label">Product Request Title<sup class="ast">*</sup></label>
	                          </div>
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
	                             <input type="text" class="form-control input-sm basic-detail-input-style" name="product_request_title" id="product_request_title" placeholder="Enter Product Request Title">
	                          </div>
	                       </div>
	                </div>

                    <div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="product_request_description" class="apply-leave-label">Product Request Description<sup class="ast">*</sup></label>
	                          </div>
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
	                             <textarea rows="4" cols="50" class="form-control input-sm basic-detail-input-style" id="product_request_description" name="product_request_description" placeholder="Enter Product Request Description"></textarea>
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

<script>
    $("#productRequisitionForm").validate({
      rules: {
        "product_request_title" : {
			required : true
        },

      "product_request_description" : {
			required : true
        },  

		"name_of_firm[]" : {
          required: true
        }
      },
      errorPlacement: function(error, element) {
	    if (element.hasClass('select2')) {
	      error.insertAfter(element.next('span.select2'));
	    } else {
	      error.insertAfter(element);
	    }
	  },
      messages: {
          "product_request_title" : {
            required: 'Enter Product Request Title'
          },

          "product_request_description" : {
            required: 'Enter Product Request Description'
          },

		  "name_of_firm[]" : {
            required: 'Select Vendor'
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

   /* $("div.alert-dismissible").fadeOut(3000);
    $("#shift_timing_to").timepicker({
      showInputs: false
    });  */


</script>
@endsection