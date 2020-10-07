@extends('admins.layouts.app')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.20/css/jquery.dataTables.min.css">

    <style>
        #document_form_3_table tr th,
        #document_form_3_table tr td { vertical-align: middle;}

        .list-padding { padding: 10px;}
    </style>
@endsection

@section('content')

    <!-- Content Wrapper Starts here -->
    <div class="content-wrapper">

        <!-- Content Header Starts here -->
        <section class="content-header">
            <h1>All Documents</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dms Documents</li>
            </ol>
        </section>
        <!-- Content Header Ends here -->

        <!-- Main content Starts here -->
        <section class="content">
            <div class="row">
                <div class="col-sm-12">
                    @include('admins.validation_errors')

                    <form id="document_form_3" method="post" action="{{ route('dms.document.filter') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="document_department">Document Department<span class="ast">*</span></label>
                                    <select name="document_department" class="filter form-control input-sm basic-detail-input-style" id="document_department">
                                        <option value="" selected>Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="document_category">Document Category<span class="ast">*</span></label>
                                    <select name="document_category" class="filter form-control input-sm basic-detail-input-style" id="document_category">
                                        <option value="" selected>Please Select Document Category</option>
                                        @foreach($dmsCategories as $dmsCategory)
                                            <option value="{{ $dmsCategory->id }}">{{ $dmsCategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="document_keyword">Document Keyword<span class="ast">*</span></label>
                                    <select name="document_keyword" class="filter form-control input-sm basic-detail-input-style" id="document_keyword">
                                        <option value="" selected>Select Document Keyword</option>
                                        @foreach($dmsKeywords as $dmsKeyword)
                                            <option value="{{ $dmsKeyword->id }}">{{ $dmsKeyword->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                        <div class="box-footer text-right">
                            <a href="{{ route('dms.document.create') }}">
                                <button class="btn btn-primary submit-btn-style" id="submit2" value="Add New">Add New</button>
                            </a>
                        </div>
                        
                    <div class="box box-primary list-padding">

                        <!-- Table Starts here -->
                        <table id="document_form_4_table" class="table table-striped table-responsive table-bordered text-center">
                            <thead class="table-heading-style">
                            <tr>
                                <th>S No.</th>
                                <th>Document Name</th>
                                <th>Departments</th>
                                <th>Employees</th>
                                <th>Category</th>
                                <th>Keywords</th>
                                <th>Edit</th>
                                <th>Delete</th>
                                <th>Download</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dmsDocuments as $document)
                                <tr>
                                    <td>{{ $document['id'] }}</td>
                                    <td>{{ $document['name'] }}</td>
                                    <td>
                                        @foreach($document['departments'] as $department)
                                            <span>{{ $department->name }},</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($document['employees'] as $employee)
                                            <span>{{ $employee->first_name }} {{ $employee->last_name }},</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $document['category']->name }}</td>
                                    <td>
                                        @foreach($document['keywords'] as $keyword)
                                            <span>{{ $keyword->name }},</span>
                                        @endforeach
                                    </td>
                                    <td>
                                            <a href="{{ route('dms.document.edit', $document['id']) }}">
                                                <button class="btn bg-purple">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </a>
                                            
                                    </td>
                                    <td>
                                            <form method="post" action="{{ route('dms.document.destroy', $document['id']) }}" onclick="return confirm('Are you sure you want to delete this Document?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                    </td>
                                    <td>
                                        @if($document['document'] != '')
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-download"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    @foreach(json_decode($document['document']) as $document)
                                                        <a href="{{ route('dms.document.download', $document) }}"><i class="fa fa-download btn"></i>{{$document}} </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="table-heading-style">
                            <tr>
                                <th>S No.</th>
                                <th>Document Name</th>
                                <th>Category</th>
                                <th>Keywords</th>
                                <th>Edit</th>
                                <th>Delete</th>
                                <th>Download</th>
                            </tr>
                            </tfoot>
                        </table>
                        <!-- Table Ends here -->

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.20/js/jquery.dataTables.min.js"></script>
    <!-- Script Source Files Ends here -->

    <!-- Custom Script Starts here -->
    <script>
        //DataTable Starts here
        $("#document_form_4_table").DataTable({
            "scrollX" : true,
            responsive: true
        });
        //DataTable Ends here

        $('.filter').change(function() {
            var document_department = $("#document_department :selected").val();
            console.log(document_department);
            var document_category = $("#document_category :selected").val();
            console.log(document_category);
            var document_keyword = $("#document_keyword :selected").val();
            console.log(document_keyword);
            $.ajax({
                type: 'POST',
                url: '{{ URL('dms-documents/department/filter') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    document_department: document_department,
                    document_category: document_category,
                    document_keyword: document_keyword
                },
                success: function (data) {
                    console.log('success');
                    console.log(data);
                    $('tbody').html(data);
                },
                error: function (xhr) {
                    console.log('error');
                    console.log(xhr.responseText);
                }
            });
        });
    </script>
    <!-- Custom Script Ends here -->
@endsection

