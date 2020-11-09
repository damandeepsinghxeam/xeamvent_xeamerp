@extends('admins.layouts.app')

@section('content')

<style>
/*.viewTilTable tr td:first-child { font-weight: 600; }*/
#projectHandover table tr th,
#projectHandover table tr td:first-child {
  font-family: 'Lato-bold';
}
  
</style>

<!-- Content Wrapper Starts here -->
<div class="content-wrapper">

  <!-- Content Header Starts here -->
  <section class="content-header">
    <h1><i class="fa fa-eye"></i> View Vendor Detail</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>
  <!-- Content Header Ends here -->
  
  <!-- Main content Starts here -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box main_box p-sm">
     @php  
      @$name_of_firm = $vendor_data['vendor_approval']->name_of_firm;
      @$type_of_firm = $vendor_data['vendor_approval']->type_of_firm;
      @$type_of_firm_others = $vendor_data['vendor_approval']->type_of_firm_others;
      @$status_of_company = $vendor_data['vendor_approval']->status_of_company;
      @$type_of_service_provide = $vendor_data['vendor_approval']->type_of_service_provide;
      @$manpower_provided = $vendor_data['vendor_approval']->manpower_provided;
      @$address = $vendor_data['vendor_approval']->address;
      @$country_id = $vendor_data['vendor_approval']->country_id;
      @$state_id = $vendor_data['vendor_approval']->state_id;
      @$city_id = $vendor_data['vendor_approval']->city_id;
      @$pin = $vendor_data['vendor_approval']->pin;
      @$std_code_with_phn_no = $vendor_data['vendor_approval']->std_code_with_phn_no;
      @$email = $vendor_data['vendor_approval']->email;
      @$website = $vendor_data['vendor_approval']->website;
      @$mobile = $vendor_data['vendor_approval']->mobile;
      @$name_of_contact_person = $vendor_data['vendor_approval']->name_of_contact_person;
      @$designation_of_contact_person = $vendor_data['vendor_approval']->designation_of_contact_person;
      @$description_of_company = $vendor_data['vendor_approval']->description_of_company;
      @$items_for_service = $vendor_data['vendor_approval']->items_for_service;

    @endphp 

			<div class="box-body form-sidechange">
				<!-- Tabs Content Starts here -->
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs edit-nav-styling">
					  <li class="active"><a href="#projectHandover" class="f_b" data-toggle="tab">Vendor Detail</a></li>
					</ul>
					<div class="tab-content">
						
					<!-- Project Handover Tab Starts here -->
					<div class="active tab-pane" id="projectHandover">
						<!-- Row starts here -->
						<div class="row">
							<div class="col-md-6">
								<table class="table table-bordered table-striped viewTilTable">
									<thead class="table-heading-style table_1">
										<tr>
                      <th style="width: 40%;">Field</th>
                      <th style="width: 60%;">Value</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Name of Firm / Company</td>
											<td>{{$name_of_firm}}</td>
										</tr>
										<tr>
											<td>Type Of Firm</td>
											<td>{{$type_of_firm}}</td>
										</tr>
										<tr>
											<td>Type Of Firm Others</td>
											<td>@if($type_of_firm_others){{$type_of_firm_others}} @else - @endif</td>
										</tr>
										
										<tr>
											<td>Status Of Company</td>
											<td>
											{{$status_of_company}}
											</td>
										</tr>
										<tr>
											<td>Type Of Service Provide</td>
											<td>@if($type_of_service_provide){{$type_of_service_provide}} @else - @endif</td>
										</tr>
										<tr>
											<td>Manpower Provided</td>
											<td>@if($manpower_provided){{$manpower_provided}} @else - @endif</td>
										</tr>
										<tr>
											<td>Address</td>
											<td>{{$address}}</td>
										</tr>
										<tr>
											<td>Country</td>
											<td>{{@$vendor_data['vendor_approval']->country->name}}</td>
										</tr>
										<tr>
											<td>State</td>
											<td>{{@$vendor_data['vendor_approval']->state->name}}
											</td>
										</tr>
										<tr>
											<td>City</td>
											<td>{{@$vendor_data['vendor_approval']->city->name}}</td>
										</tr>
										<tr>
											<td>Pincode</td>
											<td>{{$pin}}</td>
										</tr>
										<tr>
											<td>STD Code with Phone No</td>
											<td>{{$std_code_with_phn_no}}</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="col-md-6">
							  <table class="table table-bordered table-striped viewTilTable">
									<thead class="table-heading-style table_1">
										<tr>
                      <th style="width: 40%;">Field</th>
                      <th style="width: 60%;">Value</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Email</td>
											<td>{{$email}}</td>
										</tr>
										<tr>
											<td>Website</td>
											<td>@if($manpower_provided){{$website}} @else - @endif</td>
										</tr>
										<tr>
											<td>Mobile</td>
											<td>{{$mobile}}</td>
										</tr>
										<tr>
											<td>Name of Contact Person</td>
											<td>{{$name_of_contact_person}}</td>
										</tr>
										<tr>
											<td>Designation Of Contact Person</td>
											<td>{{$designation_of_contact_person}}</td>
										</tr>
										<tr>
											<td>Brief Description of Business of your Company</td>
											<td>{{$description_of_company}}</td>
										</tr>
										<tr>
											<td>Item Category</td>
											<td>{{$category_name}}</td>
										</tr>
										<tr>
											<td>Items for Service</td>
											@if(count($items) > 0)
												<td>
												@foreach($items as $item)
												  <p>{{$item}}</p>
												@endforeach
												</td>
											@else
												<td>-</td>
											@endif
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<!-- Row Ends here -->


					</div>
					<!-- Project Handover Tab Ends here -->


					</div>
					<!-- /.tab-content -->
				</div>
				<!-- Tabs Content ends here -->
			</div>
        </div>
      </div>
    </div>
  </section>
  <!-- Main content Ends Here-->

</div>
<!-- Content Wrapper Ends here -->
@endsection
  
