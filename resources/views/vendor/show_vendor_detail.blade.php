@extends('admins.layouts.app')

@section('content')

<style>
.viewTilTable tr td:first-child { font-weight: 600; }
</style>

<!-- Content Wrapper Starts here -->
<div class="content-wrapper">

  <!-- Content Header Starts here -->
  <section class="content-header">
    <h1>Vendor Detail</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>
  <!-- Content Header Ends here -->
  
  <!-- Main content Starts here -->
  <section class="content">
    <div class="row">
      <div class="col-sm-12">
        <div class="box box-primary">
     @php  
		@$name_of_firm = $vendor_data['vendor_approval']->name_of_firm;
		@$type_of_firm = $vendor_data['vendor_approval']->type_of_firm;
		@$status_of_company = $vendor_data['vendor_approval']->status_of_company;
				
		@endphp 

			<div class="box-body">
				<!-- Tabs Content Starts here -->
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs edit-nav-styling">
					  <li class="active"><a href="#projectHandover" data-toggle="tab">Vendor Detail</a></li>
					</ul>
					<div class="tab-content">
						
					<!-- Project Handover Tab Starts here -->
					<div class="active tab-pane" id="projectHandover">
						<!-- Row starts here -->
						<div class="row">
							<div class="col-md-6">
								<table class="table table-bordered table-striped viewTilTable">
									<thead class="table-heading-style">
										<tr>

										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Name of Firm / Company</td>
											<td></td>
										</tr>
										<tr>
											<td>Type Of Firm</td>
											<td></td>
										</tr>
										<tr>
											<td>Status Of Company</td>
											<td>
													
											</td>
										</tr>
										<tr>
											<td>Address</td>
											<td></td>
										</tr>
										<tr>
											<td>Country</td>
											<td></td>
										</tr>
										<tr>
											<td>State</td>
											<td>
											</td>
										</tr>
										<tr>
											<td>City</td>
											<td></td>
										</tr>
										<tr>
											<td>Pincode</td>
											<td></td>
										</tr>
										<tr>
											<td>STD Code with Phone No</td>
											<td></td>
										</tr>
										<tr>
											<td>Email</td>
											<td></td>
										</tr>
										<tr>
											<td>Website</td>
											<td></td>
										</tr>
										<tr>
											<td>Mobile</td>
											<td></td>
										</tr>
										<tr>
											<td>Name of Contact Person</td>
											<td></td>
										</tr>
										<tr>
											<td>Designation Of Contact Person</td>
											<td></td>
										</tr>
										<tr>
											<td>Brief Description of Business of your Company</td>
											<td></td>
										</tr>
										<tr>
											<td>Items for Service</td>
											<td></td>
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
  
