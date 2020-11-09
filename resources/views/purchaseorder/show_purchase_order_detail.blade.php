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
    <h1><i class="fa fa-eye"></i> View Purchase Order Detail</h1>
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
	  
     
    @endphp 

			<div class="box-body form-sidechange">
				<!-- Tabs Content Starts here -->
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs edit-nav-styling">
					  <li class="active"><a href="#projectHandover" class="f_b" data-toggle="tab">Purchase Order Detail</a></li>
					</ul>
					<div class="tab-content">
						
					<!-- Project Handover Tab Starts here -->
					<div class="active tab-pane" id="projectHandover">
						<!-- Row starts here -->
						<div class="row">
							<div class="col-md-6 col-md-offset-3">
								<table class="table table-bordered table-striped viewTilTable">
									<thead class="table-heading-style table_1">
										<tr>
											<th style="width: 40%;">Field</th>
											<th style="width: 60%;">Value</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Purpose</td>
											<td>
												@if(count($purchase_order_data) > 0)
													@foreach($purchase_order_data as $purpose)
														@if($loop->iteration == 1)
															<p>{{$purpose->purpose}}</p>
														@endif
													@endforeach
												@else
													--
												@endif
											</td>
                                            <tr>
											<td>Required By</td>
											<td>
												@if(count($purchase_order_data) > 0)
													@foreach($purchase_order_data as $purpose)
														@if($loop->iteration == 1)
															<p>{{$purpose->required_by}}</p>
														@endif
													@endforeach
												@else
													--
												@endif
											</td>
											</tr>

                                            <tr>
											<td>Requested By</td>
											<td>
												@if(count($purchase_order_data) > 0)
													@foreach($purchase_order_data as $purpose)
														@if($loop->iteration == 1)
															<p>{{$purpose->fullname}}</p>
														@endif
													@endforeach
												@else
													--
												@endif
											</td>
											</tr>
										</tr>
										
									</tbody>
								</table>
							</div>
						</div>
						<!-- Row Ends here -->

						<div class="row">
							<div class="col-md-12">
								<table class="table table-bordered table-striped">
									<thead class="table-heading-style table_1">
										<tr>
											<th>Item Name</th>
											<th>Quantity</th>
											<th>Approx. Price (Per Item)</th>
											<th>Total Price</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												@if(count($purchase_order_data) > 0)
														@foreach($purchase_order_data as $product_name)
															@if($loop->iteration == 1)
																<p>{{$product_name->name}}</p>
															@endif
														@endforeach
													@else
														--
													@endif
											</td>
											<td>
												@if(count($purchase_order_data) > 0)
														@foreach($purchase_order_data as $product_name)
															@if($loop->iteration == 1)
																<p>{{$product_name->quantity}}</p>
															@endif
														@endforeach
													@else
														--
													@endif
											</td>
											<td>
												@if(count($purchase_order_data) > 0)
														@foreach($purchase_order_data as $product_name)
															@if($loop->iteration == 1)
																<p>{{$product_name->approx_price}}</p>
															@endif
														@endforeach
													@else
														--
													@endif
											</td>
											<td>
												@if(count($purchase_order_data) > 0)
														@foreach($purchase_order_data as $product_name)
															@if($loop->iteration == 1)
																<p>{{$product_name->total_price}}</p>
															@endif
														@endforeach
													@else
														--
													@endif
											</td>
										
										</tr>
										<tr>
										</tr>
									</tbody>
									<tfoot class="table-heading-style table_1">
										<tr>
											<th>Product Name</th>
											<th>Quantity</th>
											<th>Approx. Price (Per Item)</th>
											<th>Total Price</th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>


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
  
