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
            <h1>Edit DMS Document</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Edit Dms Document</li>
            </ol>
        </section>
        <!-- Content Header Ends here -->

        <!-- Main content Starts here -->
        <section class="content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box box-primary">

                        <!-- Form Starts here -->
                        <form id="document_form_3" method="post" action="{{ route('dms.document.update', $dmsDocument->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- Box Body Starts here -->
                            <div class="box-body jrf-form-body">

                                <div class="form-group">
                                    <label for="document_name">Document Name<span class="ast">*</span></label>
                                    <input type="text" name="document_name" class="@error('document_name') is-invalid @enderror form-control input-sm basic-detail-input-style" id="" value="{{ $dmsDocument->name }}" placeholder="Enter Document Name here">
                                    @error('document_name')
                                    <div class="alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="document_category">Category<span class="ast">*</span></label>

                                    <select name="document_category" class="form-control input-sm basic-detail-input-style" id="">
                                        <option value=""  disabled>Please Select Document Category</option>
                                        @foreach($dmsCategories as $dmscategory)
                                            <option value="{{ $dmscategory->id }}" {{$dmscategory->id == $dmsDocument->dms_category_id  ? 'selected' : ''}}>{{ $dmscategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="document_keywords">Document Keywords<span class="ast">*</span></label>
                                    <select name="document_keywords[]" class="form-control input-sm basic-detail-input-style select2" data-placeholder="Please Select Document Keywords" id="" multiple="multiple" required >
                                        <option value="" disabled>Please Select Document Keywords</option>
                                        @foreach($dmsKeywords as $dmsKeyword)
                                            <option value="{{ $dmsKeyword->id }}"  {{ isset($dmsDocument) && in_array($dmsKeyword->id, $dmsDocument->keywords()->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $dmsKeyword->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="departments">Department<span class="ast">*</span></label>
                                    <select name="departments[]" id="departmentIds" class="form-control input-sm basic-detail-input-style select2" data-placeholder="Select Dapartment/ Dapartments"  multiple="multiple" required>
                                        <option value="" disabled>Please Select Document Departments</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ isset($dmsDocument) && in_array($department->id, $dmsDocument->departments()->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="employees">Employees<span class="ast">*</span></label>
                                    <select name="employees[]"  class="departmentIds form-control input-sm basic-detail-input-style select2" data-placeholder="Select Employee/ Employees" id="employees" multiple="multiple" required>
                                        <option value="" disabled>Please Select Document Employees</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}"  {{ isset($dmsDocument) && in_array($employee->id, $dmsDocument->employees()->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $employee->fullname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="Document">Document<span class="ast">*</span></label>
                                    <input type="file" name="document_files[]" id="" multiple>
                                </div>

                                <div class="form-group">
                                    <label for="">Uploaded Documents</label>
                                    <br/>
                                    @foreach(json_decode($dmsDocument->document) as $document)
                                        <a href="{{ asset("public/uploads/document/". $document) }}" target="_blank">View
                                            {{$document}}</a>
                                       <a href="{{ URL('dms-documents/'.$dmsDocument->id.'/'.$document.'/remove') }}" onclick="return confirm('Are you sure you want to delete this document?');"><i class="fa fa-trash btn-danger btn"></i></a>
                                        <br/>
                                    @endforeach
                                </div>
                            </div>
                            <!-- Box Body Ends here -->
                            <!-- Box Footer Starts here -->
                            <div class="box-footer text-center">
                                <input type="submit" class="btn btn-primary submit-btn-style" id="submit2" value="Update" name="submit">
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
            console.log(department_ids);
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
                    $("#employees").append('<option>--Select Nation--</option>');
                    if(employees)
                    {
                        var formoption = "";
                        $.each(employees, function(v) {
                            var val = employees[v]
                            formoption += "<option value='" + val['id'] + "'  >" + val['fullname'] + "</option>";
                        });
                        $('#employees').html(formoption);
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        });

    </script>
    <!-- Custom Script Ends here -->
@endsection

