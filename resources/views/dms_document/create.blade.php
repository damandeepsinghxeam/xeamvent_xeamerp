@extends('admins.layouts.app')

@section('style')
    <style>
        #document_form_3_table tr th,
        #document_form_3_table tr td { vertical-align: middle;}
    </style>
@endsection

@section('content')

    <!-- Content Wrapper Starts here -->
    <div class="content-wrapper">

        <!-- Content Header Starts here -->
        <section class="content-header">
            <h1>Add DMS Document</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Add New Dms Document</li>
            </ol>
        </section>
        <!-- Content Header Ends here -->

        <!-- Main content Starts here -->
        <section class="content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box box-primary">

                        <!-- Form Starts here -->
                        <form id="document_form_3" method="post" action="{{ route('dms.document.store') }}" enctype="multipart/form-data">
                        @csrf
                        <!-- Box Body Starts here -->
                            <div class="box-body jrf-form-body">

                                <div class="form-group">
                                    <label for="document_name">Document Name<span class="ast">*</span></label>
                                    <input type="text" name="document_name" class="@error('document_name') is-invalid @enderror form-control input-sm basic-detail-input-style" id="" placeholder="Enter Document Name here">
                                    @error('document_name')
                                    <div class="alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="document_category">Document Category<span class="ast">*</span></label>
                                    <select name="document_category" class="form-control input-sm basic-detail-input-style" id="">
                                        <option value="" selected disabled>Please Select Document Category</option>
                                        @foreach($dmsCategories as $dmscategory)
                                            <option value="{{ $dmscategory->id }}">{{ $dmscategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="document_keywords">Document Keywords<span class="ast">*</span></label>
                                    <select name="document_keywords[]" class="form-control input-sm basic-detail-input-style select2" data-placeholder="Please Select Document Keywords" id="" multiple="multiple" required>
                                        <option value="" disabled>Please Select Document Keywords</option>
                                        @foreach($dmsKeywords as $dmsKeyword)
                                            <option value="{{ $dmsKeyword->id }}">{{ $dmsKeyword->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="department">Department<span class="ast">*</span></label>
                                    <select name="departments[]" id="departmentIds" class="form-control input-sm basic-detail-input-style select2" data-placeholder="Select Dapartment/ Dapartments"  multiple="multiple" required>
                                        <option value=""  disabled>Please Select Document Departments</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="employees">Employees<span class="ast">*</span></label>
                                    <select name="employees[]"  class="departmentIds form-control input-sm basic-detail-input-style select2" data-placeholder="Select Employee/ Employees" id="employees" multiple="multiple" required>
                                        <option value=""  disabled>Please Select Document Employees</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="Document">Document<span class="ast">*</span></label>
                                    <input type="file" name="document_files[]" id="" multiple>
                                </div>
                            </div>
                            <!-- Box Body Ends here -->
                            <!-- Box Footer Starts here -->
                            <div class="box-footer text-center">
                                <input type="submit" class="btn btn-primary submit-btn-style" id="submit2" value="Save" name="submit">
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
@endsection

@section('script')

    <!-- Script Source Files Starts here -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.js"></script>
    <!-- Script Source Files Ends here -->

    <!-- Custom Script Starts here -->
    <script>
        $(document).ready(function(){
            //Validation Starts here
            $("#document_form_3").validate({
                rules: {
                    "document_name" : {
                        required: true
                    },
                    "document_keywords" : {
                        required: true
                    },
                    "document_category" : {
                        required: true
                    },
                    "document_file" : {
                        required: true
                    },
                    "department" : {
                        required: true
                    },
                    "employees" : {
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
                    "document_name" : {
                        required: "Please enter document name"
                    },
                    "document_keywords" : {
                        required: "Please select document keyword/keywords"
                    },
                    "document_category" : {
                        required: "Please select document category"
                    },
                    "document_file" : {
                        required: "Choose file"
                    },
                    "department" : {
                        required: "Select Department/ Departments"
                    },
                    "employees" : {
                        required: "Select Employee / Employees"
                    }
                }
            });
            //Validation Ends here

            $('#departmentIds').change(function() {
                var department_ids = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '{{ URL('dms-documents/department/employee') }}',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        department_ids: department_ids
                    },
                    success: function (data) {
                        var employees = data.data;
                        // $('#employees').empty();
                        if(employees)
                        {
                            var formoption = "";
                            $.each(employees, function(v) {
                                var val = employees[v]
                                formoption += "<option value='" + val['id'] + "'>" + val['fullname'] + "</option>";
                            });
                            $('#employees').html(formoption);
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
    <!-- Custom Script Ends here -->
@endsection

