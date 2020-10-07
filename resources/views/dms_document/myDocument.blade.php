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
            <h1>My Documents</h1>
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

                    <div class="box box-primary list-padding">

                        <!-- Table Starts here -->
                        <table id="document_form_4_table" class="table table-striped table-responsive table-bordered text-center">
                            <thead class="table-heading-style">
                            <tr>
                                <th>S No.</th>
                                <th>Document Name</th>
                                <th>Category</th>
                                <th>Keywords</th>
                                <th>Download</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dmsDocuments as $document)
                                <tr>
                                    <td>{{ $document['id'] }}</td>
                                    <td>{{ $document['name'] }}</td>
                                    <td>{{ $document['category']->name }}</td>
                                    <td>
                                        @foreach($document['keywords'] as $keyword)
                                            <span>{{ $keyword->name }},</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($document['document'] != '')
                                            <form method="post" action="{{ route('dms.document.download', $document['id']) }}">
                                                @csrf
                                                <button class="btn btn-secondary">
                                                    <i class="fa fa-download"></i>
                                                </button>
                                            </form>
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
    </script>
    <!-- Custom Script Ends here -->
@endsection

