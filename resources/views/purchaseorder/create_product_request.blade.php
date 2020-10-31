@extends('admins.layouts.app')
@section('content')


<link rel="stylesheet" href="{{asset('public/admin_assets/bower_components/summernote/summernote.min.css')}}">
<link rel="stylesheet" href="{{asset('public/admin_assets/dist/css/travel_module.css')}}">
<style>

  #purchase_order_table {
    counter-reset: serial-number;  /* Set the serial number counter to 0 */
  }
	#purchase_order_table td:first-child:before {
		counter-increment: serial-number;  /* Increment the serial number counter */
		content: counter(serial-number);  /* Display the counter */
  }
</style>

<!-- Content Wrapper Starts here -->
<div class="content-wrapper">
  <!-- Content Header Starts here -->
  <section class="content-header">
    <h1><i class="fa fa-file"></i> Purchase Order Form</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Purchase Order</li>
    </ol>
  </section>
  <!-- Content Header Ends here -->

  <!-- Main content Starts here -->
  <section class="content">
    <div class="row">
      <div class="col-sm-12">
        <div class="box main_box p-sm">
          <!-- Form Starts here -->
          <form id="purchase_order_form" action="{{ url('purchaseorder/save-product-request') }}" method="POST" enctype="multipart/form-data">            
          {{ csrf_field() }}
            <!-- Box Body Starts here -->
            <div class="box-body form-sidechange">              
              <!-- Table Starts here -->
              <table id="purchase_order_table" class="table table-striped table-responsive table-bordered">
                <thead class="table-heading-style table_1">
                  <tr>
                    <th>S No.</th>
                    <th style="width: 300px;">Product / Item Category</th>
                    <th style="width: 300px;">Product / Items</th>
                    <th style="width: 200px;">Quantity</th>
                    <th>Add / Remove</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td></td>
                    <td>
                      <select name="category[]" class="form-control input-md basic-detail-input-style input_1 category" id="category">
                          <option value="" selected disabled>Select Category</option>
                          <!-- <option value="">Please select Product Item</option> -->
                            @if(!$data['vendor_categories']->isEmpty())
                              @foreach($data['vendor_categories'] as $vendor_category)
                                <option value="{{$vendor_category->id}}">{{$vendor_category->name}}</option>
                              @endforeach
                            @endif  
                        </select>
                    </td>
                    <td>
                    <!-- <select name="items" class="form-control input-md basic-detail-input-style input_1 select2" id=""> -->
                      <select name="items[]" class="form-control input-md basic-detail-input-style input_1 item" id="items">
                        <option value="">--Select--</option>
                      </select>
                    </td>
                    <td>
                      <input type="text" name="quantity[]" class="form-control input-md basic-detail-input-style input_1 quantity" id="" placeholder="For Ex. 10">
                    </td>
                    <td>
                      <a href="javascript:void(0)" id="add_Item">
                        <i class="fa fa-plus a_r_style a_r_style_green"></i>
                      </a>
                      <a href='javascript:void(0);' class='remove'><span class='fa fa-minus a_r_style a_r_style_red'></span></a>
                     </td>
                  </tr>
                </tbody>
                <tfoot class="table-heading-style table_1">
                  <tr>
                    <th>S No.</th>
                    <th style="width: 300px;">Product / Item Category</th>
                    <th style="width: 300px;">Product / Items</th>
                    <th style="width: 200px;">Quantity</th>
                    <th>Add / Remove</th>
                  </tr>
                </tfoot>
              </table>
              <!-- Table Ends here -->              
              <div class="row m-t-md">
                <div class="col-md-12">
                  <p>Who will coordinate with the vendor:</p>
                  <hr class="m-t-xs">
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
                        <label for="" class="apply-leave-label label_1">Co-ordinator's Departments<span style="color:red">*</span></label>
                      </div>
                      <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
                        <select name="coordinate_departments[]" class="form-control input-md basic-detail-input-style input_1 select2" id="departments" multiple="multiple" data-placeholder="Select Department">
                          <!-- <option value="">Please select Product Item</option> -->
                            @if(!$data['departments']->isEmpty())
                              @foreach($data['departments'] as $Department)  
                                <option value="{{$Department->id}}">{{$Department->name}}</option>
                              @endforeach
                            @endif
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
                        <label for="" class="apply-leave-label label_1">Co-ordinator<span style="color:red">*</span></label>
                      </div>
                      <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
                        <select name="coordinate_employees[]" class="form-control input-md basic-detail-input-style input_1 select2" id="coordinate_employees" multiple="multiple" data-placeholder="Select Employees">
                         
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6 col-md-offset-3">
                  <div class="vendor_list_box">
                     <div class="vendor_list_heading">
                       <h2>Vendors<span style="color:red">*</span></h2>
                     </div>
                     <div class="vendor_list_content p-sm m-t-md">
                       <!-- <div class="vendor_list_item">
                          <label class="t-check-container">
                            <input type="checkbox" class="selectSingleCheckbox">
                            <span class="task-checkmark"></span>&nbsp;&nbsp; Vendor Name 1
                          </label>
                        </div>
                        <div class="vendor_list_item">
                          <label class="t-check-container">
                            <input type="checkbox" class="selectSingleCheckbox">
                            <span class="task-checkmark"></span>&nbsp;&nbsp; Vendor Name 2
                          </label>
                        </div>
                        <div class="vendor_list_item">
                          <label class="t-check-container">
                            <input type="checkbox" class="selectSingleCheckbox">
                            <span class="task-checkmark"></span>&nbsp;&nbsp; Vendor Name 3
                          </label>
                        </div> -->
                     </div>
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-2 leave-label-box label-470">
                  <label for="" class="apply-leave-label label_1">Requirement<span style="color:red">*</span></label>
                </div>
                <div class="col-md-10">
                  <textarea id="po_summernote" name="requirement"></textarea>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6">
                  <div class="row">
                      <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
                        <label for="required_by" class="apply-leave-label label_1">Required By<span style="color:red">*</span></label>
                      </div>
                      <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
                        <div class="input-group">
                          <input type="text" name="required_by" class="form-control input-md basic-detail-input-style input_1 datepicker" id="" placeholder="01/01/2020">
                          <div class="input-group-addon time-icon">
                            <i class="fa fa-calendar"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
              
            </div>
            <!-- Box Body Ends here -->
            
            <!-- Box Footer Starts here -->
            <div class="box-footer text-center">
              <input type="submit" class="btn btn-primary submit-btn-style" id="submit2" value="Send for Approval" name="submit">
            </div>
            <!-- Box Footer Ends here -->
          </form>
          <!-- Form Ends here -->
        </div>
      </div>
    </div>
  </section>
  <!-- Main content Ends Here-->
</div>
<!-- Content Wrapper Ends here -->


<!-- Script Source Files Starts here -->
<script src="{{asset('public/admin_assets/plugins/validations/jquery.validate.js')}}"></script>
<script src="{{asset('public/admin_assets/plugins/validations/additional-methods.js')}}"></script>
<script src="{{asset('public/admin_assets/bower_components/summernote/summernote.min.js')}}"></script>
<!-- Script Source Files Ends here -->

<!-- Custom Script Starts here -->
<script>
  $(document).ready(function() {

    
    $.validator.prototype.checkForm = function() {
      //overriden in a specific page
      this.prepareForm();
      for (var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++) {
        if (this.findByName(elements[i].name).length !== undefined && this.findByName(elements[i].name).length > 1) {
          for (var cnt = 0; cnt < this.findByName(elements[i].name).length; cnt++) {
            this.check(this.findByName(elements[i].name)[cnt]);
          }
        } else {
          this.check(elements[i]);
        }
      }
      return this.valid();
    };

    //Validation Starts here
    $("#purchase_order_form").validate({
      errorPlacement: function(error, element) {
        if (element.hasClass('select2')) {
            error.insertAfter(element.next('span.select2'));
        } else {
            error.insertAfter(element);
        }
      },
      rules: {
          "category[]" : {
              required: true
          },
          "items[]" : {
              required: true
          },
          "quantity[]" :{
              required:true,
              digits : true
          },
          "coordinate_departments[]" :{
              required:true
          },
          "coordinate_employees[]" :{
              required:true
          },
          "requirement": {
              required: true
          },
          "required_by" : {
              required: true
          }
      },
      messages: {
          "category[]" : {
              required: "Select Category"
          },
          "items[]" : {
              required: "Select Item"
          },
          "quantity[]":{
              required: "Enter Quantity"
          },
          "coordinate_departments[]" :{
              required: "Select Department"
          },
          "coordinate_employees[]" :{
              required: "Select Employees"
          },
          "requirement": {
            required: "Select Requirement"
          },
          "required_by" : {
              required: "Select Date"
          }
      }
    });
    //Validation Ends here

    /*Insurance Expiry Functionality Starts here*/
    $("#insurance_expiry").hide();
    $('.insurance').click(function () {
      var insuranceValue = $(this).val();
       if(insuranceValue == '1'){
        $("#insurance_expiry").fadeIn();
       } else {
        $("#insurance_expiry").fadeOut();
        $("#insurance_expiry input").val('');
       }
    });
    /*Insurance Expiry Functionality ends here*/
    
    /*Summernote functionality starts here*/
    $('#po_summernote').summernote({
        placeholder: 'For Ex. 2 Laptop with chargers and 1 year warranty',
        tabsize: 2,
        height: 120,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });
    /*Summernote functionality ends here*/
    
    /*Add More Items functionality starts here*/
    // $("#add_Item").on('click', function(){
    //   $('#purchase_order_table tbody').append('<tr><td></td><td><select name="category" class="form-control input-md basic-detail-input-style input_1" id="category"><option value="" selected disabled>Select Category</option><option value="Item 1">Item 1</option><option value="Item 2">Item 2</option><option value="Item 3">Item 3</option><option value="Item 4">Item 4</option></select></td><td><select name="items" class="form-control input-md basic-detail-input-style input_1" id="items"><option value="" selected disabled>Select Item</option><option value="Item 1">Item 1</option><option value="Item 2">Item 2</option><option value="Item 3">Item 3</option><option value="Item 4">Item 4</option></select></td><td><input type="text" name="quantity" class="form-control input-md basic-detail-input-style input_1" id="" placeholder="For Ex. 10"></td><td><a href="javascript:void(0)" id="" class="remove_Item"><i class="fa fa-minus a_r_style a_r_style_red"></i></a></td></tr>');
      
    //   $('.remove_Item').on('click', function(){
    //     $(this).parents('tr').remove();
    //   });
      
    // });
    /*Add More Items functionality ends here*/

    $(document).on('click', '#add_Item', function() {
      var data = $("#purchase_order_table tr:eq(1)").clone(true).appendTo("#purchase_order_table");
      data.find("select").val('');
      data.find('select[name="items[]"]').html('<option value="">--Select--</option>');
      data.find("input").val('');
    });

    $(document).on('click', '.remove', function() {
      var trIndex = $(this).closest("tr").index();
        if(trIndex>0) {
        $(this).closest("tr").remove();
      } else {		
      }
    });
 
 // get category wise items
    function getVendors(){
      let categoriesIds = []
      $('.category').each(function(){
        categoriesIds.push($(this).val())
      })
      //console.log(categoriesIds)
      var chk = '';
      $.ajax({
        type: 'POST',
        url: "{{ url('purchaseorder/get-vendors-by-category') }} ",
        data: JSON.stringify(categoriesIds),
        contentType: "application/json",
        success: function(result){

          if(result.length != 0){
            //console.log(result)
            var _inc = 1;
            result.forEach(function(vendor) {
              chk += '<div class="vendor_list_item">'+
                        '<label class="t-check-container" for="vendor_name['+_inc+']">'+
                          '<input type="checkbox" name="vender_name[]" id="vendor_name['+_inc+']" class="selectSingleCheckbox vender_name" value="' + vendor.id + '">'+
                          '<span class="task-checkmark m-r-sm"></span>' + vendor.name_of_firm +
                        '</label>'+
                      '</div>';
              _inc++;
            });
            $('.vendor_list_content').html(chk);            
          }
        }
      });
    }

    $(document).on('change', '.category', function(){
      var category = $(this);

      var categoryId = $(this).val();
      var categoryIds = [];
      categoryIds.push(categoryId);
      // $(category).parents('tr').find('select[name="items"]').empty();
      // $('#items').empty();
      var displayString = '<option value="">--Select--</option>';

      $.ajax({
        type: 'POST',
        url: "{{ url('vendor/category-wise-services') }} ",
        data: {categoryIds: categoryIds},
        success: function(result){
          //console.log(result);

          if(result.length != 0){
            result.forEach(function(item){
              displayString += '<option value="'+item.id+'">'+item.name+'</option>';
            });
            getVendors();
          }

          $(category).parents('tr').find('select[name="items[]"]').html(displayString);
          //$('#items').append(displayString);
        }
      });
    }).change();

 // get department wise employees

      $('#departments').on('change', function(){
                var departmentIds = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '{{ URL('purchaseorder/deptt-wise-employees') }}',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        departmentIds: departmentIds
                    },
                    success: function (data) {
                      //console.log(data);
                        var employees = data.data;
                        // $('#coordinate_employees').empty();
                        if(employees)
                        {
                            var formoption = "";
                            $.each(employees, function(v) {
                                var val = employees[v]
                                formoption += "<option value='" + val['id'] + "'>" + val['fullname'] + "</option>";
                            });
                            $('#coordinate_employees').html(formoption);
                        }
                    },
                    error: function (xhr) {
                        //console.log(xhr.responseText);
                    }
                });
            });
    

    $("div.alert-dismissible").fadeOut(25000);
  });

  //jQuery.noConflict();
$(function () {
  //Initialize Select2 Elements
  $('.select2').select2()
  //Date picker
  $('.datepicker').datepicker({
    autoclose: true,
    orientation: "bottom",
    format: 'dd/mm/yyyy'
  });
  //Timepicker
  if($('.timepicker').length) {
    $('.timepicker').timepicker({
      showInputs: false
    });
  }    
  $("select").on("select2:close", function (e) {
      $(this).valid(); 
  });
});
</script>
<!-- Custom Script Ends here -->
@endsection