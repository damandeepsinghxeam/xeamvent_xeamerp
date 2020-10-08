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
        <h1>Vendor Registration</h1>
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
	        <form id="vendorRequisitionForm" action="{{ url('vendor/save-vendor') }}" method="POST" enctype="multipart/form-data">
	        {{ csrf_field() }}
	           <div class="box-body jrf-form-body">
	              <div class="row">
	                 <div class="col-md-6">
	                    <div class="form-group">

						<div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="jrf_industry_type" class="apply-leave-label">Name of Firm / Company<sup class="ast">*</sup></label>
	                          </div>
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
	                             <input type="text" class="form-control input-sm basic-detail-input-style" name="name_of_firm" id="name_of_firm" placeholder="Enter Name Of Firm">
	                          </div>
	                       </div>
	                    </div>
	                    <!-- for Project JRF -->
	                      	<div class="form-group">
	                           	<div class="row">
	                              	<div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                                	<label for="type_of_firm" class="apply-leave-label">Type Of Firm<sup class="ast">*</sup></label>
	                              	</div>
	                              	<div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
										<select name="type_of_firm" class="form-control input-sm basic-detail-input-style" id="type_of_firm">
											<option value="">Please select Firm Type</option>
											<option value="Public Limited Co">Public Limited Co</option> 
											<option value="Partenership Co">Partenership Co</option> 
											<option value="Proprietorship">Proprietorship</option>
											<option value="Govt Sector">Govt Sector</option>  
											<option value="Others">Others</option>
										</select>
	                              </div>
	                           </div>
	                      	</div>

	                      <div class="others_firm" style="display: none;">
	                        <div class="form-group">
	                           <div class="row">
	                              <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                                 <label for="type_of_firm_others" class="apply-leave-label">Please Specify Others<sup class="ast">*</sup></label>
	                              </div>
	                              <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
	                                  <input type="text" name="type_of_firm_others" class="form-control input-sm basic-detail-input-style" id="type_of_firm_others" placeholder="Please Specify Others"> 
	                              </div>
	                           </div>
	                        </div>
	                    </div>

						<div class="form-group">
	                           	<div class="row">
	                              	<div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                                	<label for="status_of_company" class="apply-leave-label">Status Of Company<sup class="ast">*</sup></label>
	                              	</div>
	                              	<div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
										<select name="status_of_company" class="form-control input-sm basic-detail-input-style" id="status_of_company">
											<option value="">Please select Status Of Company</option>
											<option value="Manufacturer">Manufacturer</option> 
											<option value="Authorized Dealer">Authorized Dealer</option> 
											<option value="Stokist">Stokist</option>
											<option value="Trader">Trader</option>
											<option value="Service Provider">Service Provider</option>  
										</select>
	                              </div>
	                           </div>
	                      	</div>

						<div class="service" style="display: none;">
	                        <div class="form-group">
	                           <div class="row">
							   <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                                	<label for="type_of_service_provide" class="apply-leave-label">Type Of Service Provide<sup class="ast">*</sup></label>
	                              	</div>
	                              	<div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
										<select name="type_of_service_provide" class="form-control input-sm basic-detail-input-style" id="type_of_service_provide">
											<option value="">Please select Type Of Service Provide</option>
											<option value="Mechanical Contractor">Mechanical Contractor</option> 
											<option value="Electrical Contractor">Electrical Contractor</option> 
											<option value="Instrumentation Contractor">Instrumentation Contractor</option>
											<option value="Civil Contractor">Civil Contractor</option>
											<option value="Computer Systems">Computer Systems</option>
											<option value="Consultancy Services">Consultancy Services</option>  
											<option value="Repair & Maintenance Services">Repair & Maintenance Services</option>
											<option value="Printing Services">Printing Services</option>  
											<option value="Transportation Services">Transportation Services</option> 
											<option value="Shipping Services">Shipping Services</option> 
											<option value="E-Tender Services">E-Tender Services</option>  
											<option value="Placement Services">Placement Services</option> 
											<option value="Insurance Services">Insurance Services</option>
											<option value="Health Services">Health Services</option> 
											<option value="Courier Services">Courier Services</option>  
											<option value="Surveyor">Surveyor</option>
											<option value="General Services">General Services</option>
											<option value="Financial Services">Financial Services</option> 
											<option value="Security Services">Security Services</option> 
											<option value="Auditing Services">Auditing Services</option> 
											<option value="Manpower Supply">Manpower Supply</option>      
										</select>
	                              </div>
	                           </div>
	                        </div>

							<div class="form-group">
	                           <div class="row">
							   <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                                	<label for="manpower_provided" class="apply-leave-label">Manpower Provided<sup class="ast">*</sup></label>
	                              	</div>
	                              	<div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
										<select name="manpower_provided" class="form-control input-sm basic-detail-input-style" id="manpower_provided">
											<option value="">Please Specify Manpower Provided</option>
											<option value="Yes">Yes</option> 
											<option value="No">No</option> 
										</select>
	                              </div>
	                           </div>
	                        </div>
	                    </div> 

						<div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="address" class="apply-leave-label">Address<sup class="ast">*</sup></label>
	                          </div>
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
	                             <textarea rows="4" cols="50" class="form-control input-sm basic-detail-input-style" id="address" name="address" placeholder="Address"></textarea>
	                          </div>
	                       </div>
	                    </div>

						<div class="form-group">
	                           <div class="row">
							   <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                                	<label for="country_id" class="apply-leave-label">Country<sup class="ast">*</sup></label>
	                              	</div>
	                              	<div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
									    <select class="form-control input-sm basic-detail-input-style country_id" name="country_id" id ='country_id'>
											@if(!$data['countries']->isEmpty())
												@foreach($data['countries'] as $country)
													<option value="{{$country->id}}" @if(@$country->phone_code == '91'){{'selected'}}@endif>(+{{@$country->phone_code}}) {{@$country->iso3}}
													</option>
												@endforeach
											@endif
                                        </select>
	                              </div>
	                           </div>
	                        </div> 

							<div class="form-group">
	                           <div class="row">
							   <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                                	<label for="state_id" class="apply-leave-label">State<sup class="ast">*</sup></label>
	                              	</div>
	                              	<div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
									  <select class="form-control state_id input-sm basic-detail-input-style" name="state_id" id="state_id">
										@if(!$data['states']->isEmpty())
											@foreach($data['states'] as $state)
												<option value="{{$state->id}}" @if($state->name == "Punjab"){{"selected"}}@endif>{{$state->name}}</option>
											@endforeach
										@endif
                                      </select>
	                              </div>
	                           </div>
	                        </div> 

							<div class="form-group">
	                           <div class="row">
							   <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                                	<label for="type" class="apply-leave-label">City<sup class="ast">*</sup></label>
	                              	</div>
	                              	<div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
									  <select class="form-control city_id input-sm basic-detail-input-style" name="city_id" id="city_id"></select>
	                              </div>
	                           </div>
	                        </div> 

							<div class="form-group">
	                           <div class="row">
							   <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
							   <label for="pin" class="apply-leave-label">Pincode<sup class="ast">*</sup></label>
	                              	</div>
	                              	<div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
									  <input autocomplete="off" type="text" class="form-control input-sm basic-detail-input-style" name="pin" id="pin" placeholder="Please Enter Numeric  Value In Pin Code.">
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
	                    <div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="std_code_with_phn_no" class="apply-leave-label">STD Code with Phone No.<sup class="ast">*</sup></label>
	                          </div>
	                          
							  <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
							  <input autocomplete="off" type="text" class="form-control input-sm basic-detail-input-style" name="std_code_with_phn_no" id="std_code_with_phn_no" placeholder="Please Enter STD Code with Phone No.">	
                                </div>
								             
	                       </div>
	                    </div>
	                       <div class="row">
	                       </div>
	                    </div>

	                    <div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="email" class="apply-leave-label">Email<sup class="ast">*</sup></label>
	                          </div>
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
							  <input autocomplete="off" type="text" class="form-control input-sm basic-detail-input-style" name="email" id="email" placeholder="Please Enter Valid Company Email Id.">
	                          </div>
	                       </div>
	                    </div>
	                    <div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="website" class="apply-leave-label">Website</label>
	                          </div>
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
							  <input autocomplete="off" type="text" class="form-control input-sm basic-detail-input-style" name="website" id="website" placeholder="Please enter full website url with http or https.">
	                          </div>
	                       </div>
	                    </div>

						<div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="mobile" class="apply-leave-label">Mobile<sup class="ast">*</sup></label>
	                          </div>
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
							  <input autocomplete="off" type="text" class="form-control input-sm basic-detail-input-style" name="mobile" id="mobile" maxlength="10" placeholder="Please enter mobile">
	                          </div>
	                       </div>
	                    </div>


	                    <div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="name_of_contact_person" class="apply-leave-label">Name of Contact Person<sup class="ast">*</sup></label>
	                          </div>
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
	                             <input type="text" class="form-control input-sm basic-detail-input-style" name="name_of_contact_person" id="name_of_contact_person" placeholder="Enter Name Of Contact Person">
	                          </div>
	                       </div>
	                    </div>

						<div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="designation_of_contact_person" class="apply-leave-label">Designation Of Contact Person<sup class="ast">*</sup></label>
	                          </div>
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
	                             <input type="text" class="form-control input-sm basic-detail-input-style" name="designation_of_contact_person" id="designation_of_contact_person" placeholder="Enter Name Of Contact Person">
	                          </div>
	                       </div>
	                    </div>



						<div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="description_of_company" class="apply-leave-label">Brief Description of Business of your Company<sup class="ast">*</sup></label>
	                          </div>
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
	                             <textarea rows="4" cols="50" class="form-control input-sm basic-detail-input-style" id="description_of_company" name="description_of_company" placeholder="Brief Description of Business of your Company"></textarea>
	                          </div>
	                       </div>
	                    </div>

						<div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="items_for_service" class="apply-leave-label">Items for Service<sup class="ast">*</sup></label>
	                          </div>
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
	                             <select class="form-control select2 input-sm basic-detail-input-style" name="items_for_service[]" multiple="multiple" style="width: 100%;" id="items_for_service" data-placeholder="Items For Service ">
	                                @if(!$data['vendoritems']->isEmpty())
	                                @foreach($data['vendoritems'] as $Vendoritem)  
	                                <option value="{{$Vendoritem->id}}">{{$Vendoritem->name}}</option>
	                                @endforeach
	                                @endif  
	                             </select>
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
    $("#vendorRequisitionForm").validate({
      rules: {
        "name_of_firm" : {
			required : true
        },
        
         "type_of_firm" : {
          required: true
        },

        "type_of_firm_others" :{
          required: true
        },

        "status_of_company" : {
          required: true
        },
		
        "type_of_service_provide" : {
          required: true
        },
        
        "manpower_provided" : {
          required: true
        },
        "address" : {
          required: true
        },
      
        "country_id" : {
          required: true
        },
        "state_id" : {
          required: true
        },
        "city_id" : {
          required: true
        },
        "pin" : {
          required: true,
		  digits : true
        },
        "std_code_with_phn_no" : {
          required: true,
		  digits : true
        },
        
        "email" : {
          required: true,
		  email : true
        },

		"website" : {
			url : true
        },

        "mobile" : {
			required : true,
            digits : true,
            exactlengthdigits : 10
        },
      
        "name_of_contact_person" : {
			required : true
        },

        "designation_of_contact_person" : {
			required : true
        },
        "description_of_company" : {
          required: true
        },
		"items_for_service[]" : {
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
          "name_of_firm" : {
            required: 'Select Name Of Firm'
          },
          "type_of_firm" : {
            required: 'Select Type Of Firm'
          },
          
          "type_of_firm_others" : {
            required: 'Please Specify Others'
          },
          "status_of_company" : {
            required: 'Select Status Of Company'
          },
          "type_of_service_provide" : {
            required: 'Specify Types Of Service Provide'
          },
         
          "manpower_provided" : {
            required: 'Specify Manpower Provided'
          },
          "address" : {
            required: 'Enter Address'
          },
          
          "country_id" : {
            required: 'Select Country'
          },
          "state_id" : {
            required: 'Select State'
          },
          "city_id" : {
            required: 'Select City'
          },
          "pin" : {
            required: 'Enter Pincode'
          },
          "std_code_with_phn_no" : {
            required: 'Enter Std Code with Phone No'
          },
         
          "email" : {
            required: 'Please Enter Email'
          },

		  "website" : {
           url : 'Please enter full website url with http:// or https://.'
           },
        
          "mobile" : {
            required: 'Enter Mobile'
          },

          "name_of_contact_person" : {
            required: 'Enter Name Of Contact Person'

          },
          "designation_of_contact_person" : {
            required: 'Enter Designation Of Contact Person'
          },
          "description_of_company" : {
            required: 'Enter Description Of Company'
          },
		  "items_for_service[]" : {
            required: 'Select Items For Service'
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


    $("#type_of_firm").click(function () {
       var type = $(this).val();
       if(type == 'Others'){
        $(".others_firm").show();
       } else {
        $(".others_firm").hide();
       }
    });

	$("#status_of_company").click(function () {
       var type = $(this).val();
       if(type == 'Service Provider'){
        $(".service").show();
       } else {
        $(".service").hide();
       }
    });



		$('#state_id').on('change', function(){

			var stateId = $(this).val();

			var stateIds = [];

			stateIds.push(stateId);

			$('#city_id').empty();

			var displayString = "";

			$.ajax({

				type: 'POST',

				url: "{{ url('employees/states-wise-cities') }} ",

				data: {stateIds: stateIds},

				success: function(result){

					if(result.length != 0){

						result.forEach(function(city){

							displayString += '<option value="'+city.id+'">'+city.name+'</option>';

						});

					}else{

						displayString += '<option value="" selected disabled>None</option>';

					}

					$('#city_id').append(displayString);
				}

			});

     }).change();



		$('#preStateId').on('change', function(){

				var stateId = $(this).val();

				var stateIds = [];

				stateIds.push(stateId);

				$('#preCityId').empty();

				var displayString = "";

				$.ajax({

					type: 'POST',

					url: "{{ url('employees/states-wise-cities') }} ",

					data: {stateIds: stateIds},

					success: function(result){

						if(result.length != 0){

							result.forEach(function(city){

								displayString += '<option value="'+city.id+'">'+city.name+'</option>';

							});

						}else{

							displayString += '<option value="" selected disabled>None</option>';

						}

						$('#preCityId').append(displayString);

					}

				});

		}).change();

</script>
@endsection