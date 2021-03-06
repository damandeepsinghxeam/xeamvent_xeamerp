@extends('admins.layouts.app')
@section('content')

<link rel="stylesheet" href="{{asset('public/admin_assets/plugins/timepicker/bootstrap-timepicker.min.css')}}">

<link rel="stylesheet" href="{{asset('public/admin_assets/dist/css/travel_module.css')}}">

<script src="{{asset('public/admin_assets/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script src="{{asset('public/admin_assets/plugins/validations/jquery.validate.js')}}"></script>
<script src="{{asset('public/admin_assets/plugins/validations/additional-methods.js')}}"></script>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1><i class="fa fa-edit"></i> Edit Vendor</h1>
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
	        <form id="vendorRequisitionForm" action="{{ url('vendor/edit-vendor') }}" method="POST" enctype="multipart/form-data">
	        {{ csrf_field() }}
	           <div class="box-body jrf-form-body">
	              <div class="row">
	                 <div class="col-md-6">
	                    <div class="form-group">

						          <div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="jrf_industry_type" class="apply-leave-label label_1">Name of Firm / Company<sup class="ast">*</sup></label>
	                          </div>
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
	                             <input type="text" class="form-control input-md basic-detail-input-style input_1" name="name_of_firm" id="name_of_firm" value="{{ $data['vendor']->name_of_firm }}" placeholder="Enter Name Of Firm">
	                          </div>
	                       </div>
	                    </div>
	                    <!-- for Project JRF -->
	                      	<div class="form-group">
	                           	<div class="row">
	                              	<div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                                	<label for="type_of_firm" class="apply-leave-label label_1">Type Of Firm<sup class="ast">*</sup></label>
	                              	</div>
	                              	<div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
                                  <select name="type_of_firm" class="form-control input-md basic-detail-input-style input_1" id="type_of_firm">
                                    <option value="">Please select Firm Type</option>
                                    <option value="Public Limited Co" {{ $data['vendor']->type_of_firm == "Public Limited Co" ? 'selected' : '' }}>Public Limited Co</option> 
                                    <option value="Partenership Co" {{ $data['vendor']->type_of_firm == "Partenership Co" ? 'selected' : '' }}>Partenership Co</option> 
                                    <option value="Proprietorship" {{ $data['vendor']->type_of_firm == "Proprietorship" ? 'selected' : '' }}>Proprietorship</option>
                                    <option value="Govt Sector" {{ $data['vendor']->type_of_firm == "Govt Sector" ? 'selected' : '' }}>Govt Sector</option>  
                                    <option value="Others" {{ $data['vendor']->type_of_firm == "Others" ? 'selected' : '' }}>Others</option>
                                  </select>
	                              </div>
	                           </div>
	                      	</div>

	                      <div class="others_firm" style="display: none;">
	                        <div class="form-group">
	                           <div class="row">
	                              <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                                 <label for="type_of_firm_others" class="apply-leave-label label_1">Please Specify Others<sup class="ast">*</sup></label>
	                              </div>
	                              <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
	                                  <input type="text" name="type_of_firm_others" class="form-control input-md basic-detail-input-style input_1" id="type_of_firm_others" value="{{ $data['type_of_firm_others'] }}" placeholder="Please Specify Others"> 
	                              </div>
	                           </div>
	                        </div>
	                    </div>

						          <div class="form-group">
	                           	<div class="row">
	                              	<div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                                	<label for="status_of_company" class="apply-leave-label label_1">Status Of Company<sup class="ast">*</sup></label>
	                              	</div>
	                              	<div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
                                  <select name="status_of_company" class="form-control input-md basic-detail-input-style input_1" id="status_of_company">
                                    <option value="">Please select Status Of Company</option>
                                    <option value="Manufacturer" {{ $data['status_of_company'] == "Manufacturer" ? 'selected="selected"' : '' }}>Manufacturer</option> 
                                    <option value="Authorized Dealer" {{ $data['status_of_company'] == "Authorized Dealer" ? 'selected="selected"' : '' }}>Authorized Dealer</option> 
                                    <option value="Stokist" {{ $data['status_of_company'] == "Stokist" ? 'selected="selected"' : '' }}>Stokist</option>
                                    <option value="Trader" {{ $data['status_of_company'] == "Trader" ? 'selected="selected"' : '' }}>Trader</option>
                                    <option value="Service Provider" {{ $data['status_of_company'] == "Service Provider" ? 'selected="selected"' : '' }}>Service Provider</option>  
                                  </select>
	                              </div>
	                           </div>
	                      	</div>

						            <div class="service" style="display: none;">
	                        <div class="form-group">
	                           <div class="row">
							                   <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                                	<label for="type_of_service_provide" class="apply-leave-label label_1">Type Of Service Provide<sup class="ast">*</sup></label>
	                              	</div>
	                              	<div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
                                <select name="type_of_service_provide" class="form-control input-md basic-detail-input-style input_1" id="type_of_service_provide">
                                  <option value="">Please select Type Of Service Provide</option>
                                  <option value="Mechanical Contractor" {{ $data['type_of_service_provide'] == "Mechanical Contractor" ? 'selected="selected"' : '' }}>Mechanical Contractor</option> 
                                  <option value="Electrical Contractor" {{ $data['type_of_service_provide'] == "Electrical Contractor" ? 'selected="selected"' : '' }}>Electrical Contractor</option> 
                                  <option value="Instrumentation Contractor" {{ $data['type_of_service_provide'] == "Instrumentation Contractor" ? 'selected="selected"' : '' }}>Instrumentation Contractor</option>
                                  <option value="Civil Contractor" {{ $data['type_of_service_provide'] == "Civil Contractor" ? 'selected="selected"' : '' }}>Civil Contractor</option>
                                  <option value="Computer Systems" {{ $data['type_of_service_provide'] == "Computer Systems" ? 'selected="selected"' : '' }}>Computer Systems</option>
                                  <option value="Consultancy Services" {{ $data['type_of_service_provide'] == "Consultancy Services" ? 'selected="selected"' : '' }}>Consultancy Services</option>  
                                  <option value="Repair & Maintenance Services" {{ $data['type_of_service_provide'] == "Repair & Maintenance Services" ? 'selected="selected"' : '' }}>Repair & Maintenance Services</option>
                                  <option value="Printing Services" {{ $data['type_of_service_provide'] == "Printing Services" ? 'selected="selected"' : '' }}>Printing Services</option>  
                                  <option value="Transportation Services" {{ $data['type_of_service_provide'] == "Transportation Services" ? 'selected="selected"' : '' }}>Transportation Services</option> 
                                  <option value="Shipping Services" {{ $data['type_of_service_provide'] == "Shipping Services" ? 'selected="selected"' : '' }}>Shipping Services</option> 
                                  <option value="E-Tender Services" {{ $data['type_of_service_provide'] == "E-Tender Services" ? 'selected="selected"' : '' }}>E-Tender Services</option>  
                                  <option value="Placement Services" {{ $data['type_of_service_provide'] == "Placement Services" ? 'selected="selected"' : '' }}>Placement Services</option> 
                                  <option value="Insurance Services" {{ $data['type_of_service_provide'] == "Insurance Services" ? 'selected="selected"' : '' }}>Insurance Services</option>
                                  <option value="Health Services" {{ $data['type_of_service_provide'] == "Health Services" ? 'selected="selected"' : '' }}>Health Services</option> 
                                  <option value="Courier Services" {{ $data['type_of_service_provide'] == "Courier Services" ? 'selected="selected"' : '' }}>Courier Services</option>  
                                  <option value="Surveyor" {{ $data['type_of_service_provide'] == "Surveyor" ? 'selected="selected"' : '' }}>Surveyor</option>
                                  <option value="General Services" {{ $data['type_of_service_provide'] == "General Services" ? 'selected="selected"' : '' }}>General Services</option>
                                  <option value="Financial Services" {{ $data['type_of_service_provide'] == "Financial Services" ? 'selected="selected"' : '' }}>Financial Services</option> 
                                  <option value="Security Services" {{ $data['type_of_service_provide'] == "Security Services" ? 'selected="selected"' : '' }}>Security Services</option> 
                                  <option value="Auditing Services" {{ $data['type_of_service_provide'] == "Auditing Services" ? 'selected="selected"' : '' }}>Auditing Services</option> 
                                  <option value="Manpower Supply" {{ $data['type_of_service_provide'] == "Manpower Supply" ? 'selected="selected"' : '' }}>Manpower Supply</option>      
                                </select>
	                              </div>
	                           </div>
	                        </div>

							<div class="form-group">
	                           <div class="row">
							   <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                                	<label for="manpower_provided" class="apply-leave-label label_1">Manpower Provided<sup class="ast">*</sup></label>
	                              	</div>
	                              	<div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
										<select name="manpower_provided" class="form-control input-md basic-detail-input-style input_1" id="manpower_provided">
											<option value="">Please Specify Manpower Provided</option>
											<option value="Yes" {{ $data['manpower_provided'] == "Yes" ? 'selected="selected"' : '' }}>Yes</option> 
											<option value="No" {{ $data['manpower_provided'] == "No" ? 'selected="selected"' : '' }}>No</option> 
										</select>
	                              </div>
	                           </div>
	                        </div>
	                    </div> 

						<div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="address" class="apply-leave-label label_1">Address<sup class="ast">*</sup></label>
	                          </div>
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
	                             <textarea rows="4" cols="50" class="form-control input-md basic-detail-input-style input_1" id="address" name="address" placeholder="Address">{{ $data['address'] }}</textarea>
	                          </div>
	                       </div>
	                    </div>

						<div class="form-group">
	                           <div class="row">
							   <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                                	<label for="country_id" class="apply-leave-label label_1">Country<sup class="ast">*</sup></label>
	                              	</div>
	                              	<div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
									    <select class="form-control input-md basic-detail-input-style input_1 country_id" name="country_id" id ='country_id'>
											@if(!$data['countries']->isEmpty())
												@foreach($data['countries'] as $country)
													<option value="{{$country->id}}" @if(@$country->id == $data['vendor']->country_id){{'selected'}}@endif>(+{{@$country->phone_code}}) {{@$country->iso3}}
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
	                                	<label for="state_id" class="apply-leave-label label_1">State<sup class="ast">*</sup></label>
	                              	</div>
	                              	<div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
									  <select class="form-control state_id input-md basic-detail-input-style input_1" name="state_id" id="state_id">
										@if(!$data['states']->isEmpty())
											@foreach($data['states'] as $state)
												<option value="{{$state->id}}" @if($state->id == $data['vendor']->state_id){{"selected"}}@endif>{{$state->name}}</option>
											@endforeach
										@endif
                                      </select>
	                              </div>
	                           </div>
	                        </div> 

							<div class="form-group">
	                           <div class="row">
							   <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                                	<label for="type" class="apply-leave-label label_1">City<sup class="ast">*</sup></label>
	                              	</div>
	                              	<div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
									  <select class="form-control city_id input-md basic-detail-input-style input_1" name="city_id" id="city_id">
									  </select>
	                              </div>
	                           </div>
	                        </div> 

							<div class="form-group">
	                           <div class="row">
							   <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
							   <label for="pin" class="apply-leave-label label_1">Pincode<sup class="ast">*</sup></label>
	                              	</div>
	                              	<div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
									  <input autocomplete="off" type="text" class="form-control input-md basic-detail-input-style input_1" name="pin" id="pin" value="{{ $data['pin'] }}" placeholder="Please Enter Numeric  Value In Pin Code.">
	                              </div>
	                           </div>
	                        </div>
					
	                      @php $user_id = Auth::id(); @endphp
	                        <input type="hidden" name="user_id" value="{{@$user_id}}">
							            <input type="hidden" name="id" value="{{ $data['id'] }}">

	                    </div>

	                 </div>

	                 <div class="col-md-6">
	                    <div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="std_code_with_phn_no" class="apply-leave-label label_1">STD Code with Phone No.<sup class="ast">*</sup></label>
	                          </div>
	                          
                            <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
                              <input autocomplete="off" type="text" class="form-control input-md basic-detail-input-style input_1" name="std_code_with_phn_no" id="std_code_with_phn_no" value="{{ $data['std_code_with_phn_no'] }}" placeholder="Please Enter STD Code with Phone No.">	
                            </div>
								             
	                       </div>
	                    </div>

	                    <div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="email" class="apply-leave-label label_1">Email<sup class="ast">*</sup></label>
	                          </div>
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
							  <input autocomplete="off" type="text" class="form-control input-md basic-detail-input-style input_1" name="email" id="email" value="{{ $data['email'] }}" placeholder="Please Enter Valid Company Email Id.">
	                          </div>
	                       </div>
	                    </div>
	                    <div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="website" class="apply-leave-label label_1">Website</label>
	                          </div>
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
							  <input autocomplete="off" type="text" class="form-control input-md basic-detail-input-style input_1" name="website" id="website" value="{{ $data['website'] }}" placeholder="Please enter full website url with http or https.">
	                          </div>
	                       </div>
	                    </div>

						<div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="mobile" class="apply-leave-label label_1">Mobile<sup class="ast">*</sup></label>
	                          </div>
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
							  <input autocomplete="off" type="text" class="form-control input-md basic-detail-input-style input_1" name="mobile" id="mobile" value="{{ $data['mobile'] }}" maxlength="10" placeholder="Please enter mobile">
	                          </div>
	                       </div>
	                    </div>


	                    <div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="name_of_contact_person" class="apply-leave-label label_1">Name of Contact Person<sup class="ast">*</sup></label>
	                          </div>
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
	                             <input type="text" class="form-control input-md basic-detail-input-style input_1" name="name_of_contact_person" id="name_of_contact_person" value="{{ $data['name_of_contact_person'] }}" placeholder="Enter Name Of Contact Person">
	                          </div>
	                       </div>
	                    </div>

						<div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="designation_of_contact_person" class="apply-leave-label label_1">Designation Of Contact Person<sup class="ast">*</sup></label>
	                          </div>
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
	                             <input type="text" class="form-control input-md basic-detail-input-style input_1" name="designation_of_contact_person" id="designation_of_contact_person" value="{{ $data['designation_of_contact_person'] }}" placeholder="Enter Designation Of Contact Person">
	                          </div>
	                       </div>
	                    </div>

						<div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="description_of_company" class="apply-leave-label label_1">Brief Description of Business of your Company<sup class="ast">*</sup></label>
	                          </div>
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
	                             <textarea rows="4" cols="50" class="form-control input-md basic-detail-input-style input_1" id="description_of_company" name="description_of_company"  placeholder="Brief Description of Business of your Company">{{ $data['description_of_company'] }}</textarea>
	                          </div>
	                       </div>
	                    </div>

						<div class="form-group">
	                           <div class="row">
							   <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                                	<label for="type" class="apply-leave-label label_1">Item Category<sup class="ast">*</sup></label>
	                              	</div>
	                              	<div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
									  <select class="form-control category_id input-md basic-detail-input-style input_1" name="category_id" id="category_id">
										@if(!$data['vendor_categories']->isEmpty())
											@foreach($data['vendor_categories'] as $category)
												<option value="{{$category->id}}" @if($category->id == $data['vendor']->category_id){{"selected"}}@endif>{{$category->name}}</option>
											@endforeach
										@endif
                                      </select>
	                              </div>
	                           </div>
	                        </div> 

						<div class="form-group">
	                       <div class="row">
	                          <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
	                             <label for="items_for_service" class="apply-leave-label label_1">Items for Service<sup class="ast">*</sup></label>
	                          </div>
							  	@php
									$item_id = explode (",", $data['vendor']->items_for_service);
								@endphp
	                          <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
	                             <select class="form-control select2 input-md basic-detail-input-style input_1" name="items_for_service[]" multiple="multiple" style="width: 100%;" id="items_for_service" data-placeholder="Items For Service ">
	                                @if(!$data['stockitems']->isEmpty())
										@foreach($data['stockitems'] as $Stockitem)
											@php
												$selectedItem = null;
												/* if(!empty($item_id) && in_array($Stockitem->id, $item_id)) {
													$selectedItem = 'selected';
												} */
											@endphp
											<option value="{{$Stockitem->id}}" {{$selectedItem}}>{{$Stockitem->name}}</option>
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
$(document).ready(function () {

	
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
            digits : true
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
    	});
	*/

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

	$('#state_id').on('change', function() {

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
					var city_id = '{{$data['vendor']->city_id}}';
					result.forEach(function(city){
						var selectedCity = (city_id == city.id)? 'selected' : null;
						displayString += '<option value="'+city.id+'" '+selectedCity+'>'+city.name+'</option>';
					});
				}else{
					displayString += '<option value="" selected disabled>None</option>';
				}
				$('#city_id').append(displayString);
			}

		});

	}).change();


	$('#category_id').on('change', function() {

		var categoryId = $(this).val();
		var categoryIds = [];
		categoryIds.push(categoryId);
		$('#items_for_service').empty();
		var displayString = "";

		var items_for_service = '{{$data['vendor']->items_for_service}}'.split(',');

		$.ajax({
			type: 'POST',
			url: "{{ url('vendor/category-wise-services') }} ",
			data: {categoryIds: categoryIds},
			success: function(result) {
				if(result.length != 0) {
					result.forEach(function(item) {
						var selectedItem = null;
						if($.inArray(item.id.toString(), items_for_service)!='-1') {
							selectedItem = 'selected';
						}
						displayString += '<option value="'+item.id+'" '+selectedItem+'>'+item.name+'</option>';
					});
				}else{
					displayString += '<option value="" selected disabled>None</option>';
				}
				$('#items_for_service').append(displayString);
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
});
</script>
@endsection