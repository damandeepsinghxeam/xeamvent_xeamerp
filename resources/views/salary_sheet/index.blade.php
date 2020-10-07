@extends('admins.layouts.app')

{{--@section('style')--}}
{{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.20/css/jquery.dataTables.min.css">--}}

{{--    <style>--}}
{{--        .heading2_form {--}}
{{--            font-size: 20px;--}}
{{--            text-decoration: underline;--}}
{{--            text-align: center;--}}
{{--        }--}}
{{--        .basic-detail-label {--}}
{{--            padding-right: 0px;--}}
{{--            padding-top: 4px;--}}
{{--        }--}}
{{--        .ast{--}}
{{--            color:red;--}}
{{--        }--}}
{{--    </style>--}}
{{--@endsection--}}

{{--@section('content')--}}
{{--    <!-- Content Wrapper. Contains page content -->--}}
{{--    <div class="content-wrapper">--}}
{{--        <!-- Content Header (Page header) -->--}}
{{--        <section class="content-header">--}}
{{--            <h1>Salary Sheets</h1>--}}
{{--            <ol class="breadcrumb">--}}
{{--                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>--}}
{{--            </ol>--}}
{{--        </section>--}}

{{--        <!-- Main content -->--}}
{{--        <section class="content">--}}
{{--            <!-- Small boxes (Stat box) -->--}}
{{--            <div class="row">--}}
{{--                <div class="col-sm-12">--}}
{{--                    <div class="box box-primary">--}}
{{--                        <!-- form start -->--}}
{{--                        @include('admins.validation_errors')--}}
{{--                        <br/>--}}
{{--                        <form action="{{ route('payroll.salary.sheet.export') }}" method="post">--}}
{{--                            <form method="post" action="{{ route('payroll.salary.sheet.extra.income') }}">--}}

{{--                                <div class="row">--}}
{{--                                    <div class="col-md-2"></div>--}}
{{--                                    <div class="col-md-4">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="year">Year<span class="ast"> *</span></label>--}}
{{--                                            <select name="year" class="filter form-control input-sm basic-detail-input-style" id="year">--}}
{{--                                                <option value="" selected>Select Year</option>--}}
{{--                                                @foreach($years as $year)--}}
{{--                                                    <option value="{{ $year }}">{{ $year }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="col-md-4">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="month">Month<span class="ast"> *</span></label>--}}
{{--                                            <select name="month" class="filter form-control input-sm basic-detail-input-style" id="month">--}}
{{--                                                <option value="" selected disabled>Please select Month</option>--}}
{{--                                                <option value="01">January</option>--}}
{{--                                                <option value="02">February</option>--}}
{{--                                                <option value="03">March</option>--}}
{{--                                                <option value="04">April</option>--}}
{{--                                                <option value="05">May</option>--}}
{{--                                                <option value="06">June</option>--}}
{{--                                                <option value="07">July</option>--}}
{{--                                                <option value="08">August</option>--}}
{{--                                                <option value="09">September</option>--}}
{{--                                                <option value="10">October</option>--}}
{{--                                                <option value="11">November</option>--}}
{{--                                                <option value="12">December</option>--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-2"></div>--}}
{{--                                </div>--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-md-3 col-md-offset-1">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="project">Project<span class="ast"> *</span></label>--}}
{{--                                            <select name="project" id="project" class="filter form-control input-sm basic-detail-input-style" id="year">--}}
{{--                                                <option value="" selected>Select Project</option>--}}
{{--                                                @foreach($projects as $project)--}}
{{--                                                    <option value="{{ $project->name }}">{{ $project->name }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="col-md-3">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="department">Department</label>--}}
{{--                                            <select name="department" id="department" class="filter form-control input-sm basic-detail-input-style" id="month">--}}
{{--                                                <option value="" selected>Select Department</option>--}}
{{--                                                @foreach($departments as $department)--}}
{{--                                                    <option value="{{ $department->name }}">{{ $department->name }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-3">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="paid_not_paid">Generate/On Hold Salary</label>--}}
{{--                                            <select name="paid_not_paid" id="paid_not_paid" class="filter form-control input-sm basic-detail-input-style" id="month">--}}
{{--                                                <option value="" selected>Select Salary Status</option>--}}
{{--                                                <option value="generate">Generate</option>--}}
{{--                                                <option value="on_hold">Salary Status</option>--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <button type="submit"  class="btn btn-success">Add Arrear</button>--}}
{{--                            </form>--}}

{{--                            <button type="submit"  class="btn btn-success">Export Salary Sheet Data To Excel File</button>--}}
{{--                        </form>--}}

{{--                        <div class="box-body jrf-form-body">--}}
{{--                            <h2 class="heading2_form">Salary Sheets List:</h2>--}}
{{--                            <h3 class="heading2_form"><span id="salary_sheet_month"></span> <span id="salary_sheet_year"></span></h3>--}}

{{--                            <div class="box-footer text-right">--}}
{{--                                <a href="{{ route('payroll.salary.sheet.create') }}">--}}
{{--                                    <button class="btn btn-primary submit-btn-style" id="submit2" value="Add New">Add New</button>--}}
{{--                                </a>--}}
{{--                            </div>--}}
{{--                            <form method="post" action="{{ route('payroll.salary.sheet.pay') }}">--}}
{{--                                <button type="submit" style="background: green; color:white; padding: 2px" name="status" value="generate" >Generate Salary Sheet</button>--}}
{{--                                <button type="submit" style="background: red; color:white; padding: 2px" name="status" value="on_hold">Hold Salary Sheet</button>--}}

{{--                                <!--KRA Table Starts here-->--}}
{{--                                <table class="table table-striped table-responsive table-bordered"  id="tablexportData">--}}
{{--                                    <thead class="table-heading-style">--}}
{{--                                    <tr>--}}
{{--                                        <th>P/Up</th>--}}
{{--                                        <th>S No.</th>--}}
{{--                                        <th>Employee</th>--}}
{{--                                        <th>Project</th>--}}
{{--                                        <th>Department</th>--}}
{{--                                        <th>Month Days</th>--}}
{{--                                        <th>Paid Days</th>--}}
{{--                                        <th>Total Earning</th>--}}
{{--                                        <th>Total Deduction</th>--}}
{{--                                        <th>Net Amount</th>--}}
{{--                                        <th>Expected Amount</th>--}}
{{--                                        <th>Salary Breakdown</th>--}}
{{--                                        <th>Attendance</th>--}}
{{--                                        --}}{{-- <th>Edit</th>--}}
{{--                                        --}}{{--                                    <th>Delete</th>--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody class="kra_tbody">--}}
{{--                                    </tbody>--}}
{{--                                    <tfoot class="table-heading-style">--}}
{{--                                    <th>P/Up</th>--}}
{{--                                    <th>S No.</th>--}}
{{--                                    <th>Employee</th>--}}
{{--                                    <th>Project</th>--}}
{{--                                    <th>Department</th>--}}
{{--                                    <th>Month Days</th>--}}
{{--                                    <th>Paid Days</th>--}}
{{--                                    <th>Total Earning</th>--}}
{{--                                    <th>Total Deduction</th>--}}
{{--                                    <th>Net Amount</th>--}}
{{--                                    <th>Expected Amount</th>--}}
{{--                                    <th>Salary Breakdown</th>--}}
{{--                                    <th>Attendance</th>--}}
{{--                                    --}}{{--                                <th>Edit</th>--}}
{{--                                    --}}{{--                                <th>Delete</th>--}}
{{--                                    </tfoot>--}}
{{--                                </table>--}}
{{--                                <!--KRA Table Ends here-->--}}
{{--                            </form>--}}
{{--                        </div>--}}
{{--                        --}}{{--                        <input type="button" id="btnExport" value="Export" onclick="Export()" />--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--                <!-- /.box -->--}}
{{--            </div>--}}
{{--            <!-- /.row -->--}}
{{--        </section>--}}
{{--        <!-- /.content -->--}}
{{--    </div>--}}
{{--    <!-- /.content-wrapper -->--}}

{{--@endsection--}}

{{--@section('script')--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.js"></script>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.20/js/jquery.dataTables.min.js"></script>--}}

{{--    <script src="https://rawgit.com/unconditional/jquery-table2excel/master/src/jquery.table2excel.js"></script>--}}
{{--    <script type="text/javascript">--}}
{{--        function Export() {--}}
{{--            $("#tablexportData").table2excel({--}}
{{--                filename: "SalarySheet.xls"--}}
{{--            });--}}
{{--        }--}}
{{--    </script>--}}

{{--    <script>--}}
{{--        setTimeout(function() {--}}
{{--            $('.error').fadeOut('fast');--}}
{{--        }, 1000);--}}

{{--        setTimeout(function() {--}}
{{--            $('.success').fadeOut('fast');--}}
{{--        }, 1000);--}}

{{--        $('.filter').change(function() {--}}
{{--            var year = $("#year :selected").val();--}}

{{--            var month = $("#month :selected").val();--}}

{{--            var project = $("#project :selected").val();--}}

{{--            var department = $("#department :selected").val();--}}

{{--            var paid_not_paid = $("#paid_not_paid :selected").val();--}}

{{--            $.ajax({--}}
{{--                type: 'POST',--}}
{{--                url: '{{ URL('payroll/salary-sheets/filter') }}',--}}
{{--                data: {--}}
{{--                    "_token": "{{ csrf_token() }}",--}}
{{--                    year: year,--}}
{{--                    month: month,--}}
{{--                    project: project,--}}
{{--                    department: department,--}}
{{--                    paid_not_paid: paid_not_paid--}}
{{--                },--}}
{{--                success: function (data) {--}}
{{--                    console.log('success');--}}
{{--                    var salarySheet = data.data;--}}
{{--                    $("#salarySheetData").val(salarySheet);--}}
{{--                    $('tbody').html(data.table_data);--}}
{{--                    document.getElementById("salary_sheet_year").innerHTML = year--}}
{{--                    document.getElementById("salary_sheet_month").innerHTML = moment(month, 'MM').format('MMMM')+','--}}
{{--                    console.log(year);--}}
{{--                    console.log(moment(month, 'MM').format('MMMM'));--}}
{{--                },--}}
{{--                error: function (xhr) {--}}
{{--                    console.log('error');--}}
{{--                    console.log(xhr.responseText);--}}
{{--                }--}}
{{--            });--}}
{{--        });--}}

{{--        $('#export').change(function() {--}}
{{--            var year = $("#year :selected").val();--}}
{{--            console.log(year);--}}
{{--            var month = $("#month :selected").val();--}}
{{--            console.log(month);--}}

{{--            var project = $("#project :selected").val();--}}
{{--            console.log(project);--}}

{{--            var department = $("#department :selected").val();--}}
{{--            console.log(department);--}}

{{--            $.ajax({--}}
{{--                type: 'POST',--}}
{{--                url: '{{ URL('payroll/salary-sheets/export') }}',--}}
{{--                data: {--}}
{{--                    "_token": "{{ csrf_token() }}",--}}
{{--                    year: year,--}}
{{--                    month: month,--}}
{{--                    project: project,--}}
{{--                    department: department--}}
{{--                },--}}
{{--                success: function (data) {--}}
{{--                    console.log('success');--}}
{{--                    var salarySheet = data.data;--}}
{{--                    console.log(salarySheet);--}}
{{--                    $("#salarySheetData").val(salarySheet);--}}
{{--                    console.log(salarySheet);--}}
{{--                },--}}
{{--                error: function (xhr) {--}}
{{--                    console.log('error');--}}
{{--                    console.log(xhr.responseText);--}}
{{--                }--}}
{{--            });--}}
{{--        });--}}

{{--        console.log(salarySheet);--}}
{{--    </script>--}}

{{--@endsection--}}




@section('content')

<link rel="stylesheet" href="plugins/dataTables/jquery.dataTables.min.css">

<style>
    .heading2_form { font-size: 20px; text-decoration: underline; }
    .basic-detail-label { padding-right: 0px; padding-top: 4px; }
    table tr th, table tr td {vertical-align: middle !important; }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Salary Sheets</h1>
        <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <!-- form start -->
                    <form id="salary_sheets" method="POST">
                        <div class="box-body jrf-form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row field-changes-below">
                                        <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
                                            <label for="year" class="basic-detail-label">Year<span style="color: red">*</span></label>
                                        </div>
                                        <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
                                            <select name="year" id="year" class="form-control input-sm basic-detail-input-style">
                                                <option value="" selected disabled>Select Year</option>
                                                <option value="Year 1">Year 1</option>
                                                <option value="Year 2">Year 2</option>
                                                <option value="Year 3">Year 3</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row field-changes-below">
                                        <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
                                            <label for="month" class="basic-detail-label">Month<span style="color: red">*</span></label>
                                        </div>
                                        <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
                                            <select name="month" id="month" class="form-control input-sm basic-detail-input-style">
                                                <option value="" selected disabled>Select Month</option>
                                                <option value="Month 1">Month 1</option>
                                                <option value="Month 2">Month 2</option>
                                                <option value="Month 3">Month 3</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row field-changes-below">
                                        <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
                                            <label for="project" class="basic-detail-label">Project<span style="color: red">*</span></label>
                                        </div>
                                        <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
                                            <select name="project" id="project" class="form-control input-sm basic-detail-input-style">
                                                <option value="" selected disabled>Select Project</option>
                                                <option value="Project 1">Project 1</option>
                                                <option value="Project 2">Project 2</option>
                                                <option value="Project 3">Project 3</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row field-changes-below">
                                        <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
                                            <label for="department" class="basic-detail-label">Department<span style="color: red">*</span></label>
                                        </div>
                                        <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
                                            <select name="department" id="department" class="form-control input-sm basic-detail-input-style">
                                                <option value="" selected disabled>Select Department</option>
                                                <option value="Department 1">Department 1</option>
                                                <option value="Department 2">Department 2</option>
                                                <option value="Department 3">Department 3</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row field-changes-below">
                                        <div class="col-md-4 col-sm-4 col-xs-4 leave-label-box label-470">
                                            <label for="paid_or_not_paid" class="basic-detail-label">Paid/Not Paid Salary<span style="color: red">*</span></label>
                                        </div>
                                        <div class="col-md-8 col-sm-8 col-xs-8 leave-input-box input-470">
                                            <select name="paid_or_not_paid" id="paid_or_not_paid" class="form-control input-sm basic-detail-input-style">
                                                <option value="" selected disabled>Select Salary Status</option>
                                                <option value="Paid">Paid</option>
                                                <option value="Not Paid">Not Paid</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="box-footer create-footer text-center">
                                <input type="submit" class="btn btn-primary" id="submit3" value="Submit" name="submit">
                                <button type="button" class="btn btn-success">Export Salary sheet data to excel file</button>
                            </div>
                            <br>
                            <h2 class="heading2_form text-center">Salary Sheet List:</h2>
                            <div class="space-15">
                                <button type="button" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Add New</button>
                                <button type="button" class="btn btn-xs btn-success">Generate Salary sheet</button>
                                <button type="button" class="btn btn-xs btn-danger">Hold Salary sheet</button>
                            </div>

                            <!--Salary Sheet Table Starts here-->
                            <div class="table-box">
                                <table class="table table-striped table-responsive table-bordered text-center" id="salary_sheet_table">
                                    <thead class="table-heading-style">
                                    <tr>
                                        <th>P/ Up</th>
                                        <th>S No.</th>
                                        <th>Employee</th>
                                        <th>Project</th>
                                        <th>Department</th>
                                        <th>Month Days</th>
                                        <th>Paid Days</th>
                                        <th>Total earning</th>
                                        <th>Total deduction</th>
                                        <th>Net Amount</th>
                                        <th>Expected Amount</th>
                                        <th>Salary Breakdown</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <label class="t-check-container">
                                                <input type="checkbox" class="selectSingleCheckbox">
                                                <span class="task-checkmark"></span>
                                            </label>
                                        </td>
                                        <td>1</td>
                                        <td>Employee Name here</td>
                                        <td>XEAM HO</td>
                                        <td>BD Corporate</td>
                                        <td>31</td>
                                        <td>31</td>
                                        <td>83323.00</td>
                                        <td>0.00</td>
                                        <td>83323.00</td>
                                        <td>83323.00</td>
                                        <td>
                                            <button type= "button" class="btn btn-xs bg-purple view-salary-sheet" data-toggle="modal" data-target="#salary_sheet_modal">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="t-check-container">
                                                <input type="checkbox" class="selectSingleCheckbox">
                                                <span class="task-checkmark"></span>
                                            </label>
                                        </td>
                                        <td>1</td>
                                        <td>Employee Name here</td>
                                        <td>XEAM HO</td>
                                        <td>BD Corporate</td>
                                        <td>31</td>
                                        <td>31</td>
                                        <td>83323.00</td>
                                        <td>0.00</td>
                                        <td>83323.00</td>
                                        <td>83323.00</td>
                                        <td>
                                            <button type= "button" class="btn btn-xs bg-purple view-salary-sheet" data-toggle="modal" data-target="#salary_sheet_modal">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="t-check-container">
                                                <input type="checkbox" class="selectSingleCheckbox">
                                                <span class="task-checkmark"></span>
                                            </label>
                                        </td>
                                        <td>1</td>
                                        <td>Employee Name here</td>
                                        <td>XEAM HO</td>
                                        <td>BD Corporate</td>
                                        <td>31</td>
                                        <td>31</td>
                                        <td>83323.00</td>
                                        <td>0.00</td>
                                        <td>83323.00</td>
                                        <td>83323.00</td>
                                        <td>
                                            <button type= "button" class="btn btn-xs bg-purple view-salary-sheet" data-toggle="modal" data-target="#salary_sheet_modal">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="t-check-container">
                                                <input type="checkbox" class="selectSingleCheckbox">
                                                <span class="task-checkmark"></span>
                                            </label>
                                        </td>
                                        <td>1</td>
                                        <td>Employee Name here</td>
                                        <td>XEAM HO</td>
                                        <td>BD Corporate</td>
                                        <td>31</td>
                                        <td>31</td>
                                        <td>83323.00</td>
                                        <td>0.00</td>
                                        <td>83323.00</td>
                                        <td>83323.00</td>
                                        <td>
                                            <button type= "button" class="btn btn-xs bg-purple view-salary-sheet" data-toggle="modal" data-target="#salary_sheet_modal">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot class="table-heading-style">
                                    <tr>
                                        <th>P/ Up</th>
                                        <th>S No.</th>
                                        <th>Employee</th>
                                        <th>Project</th>
                                        <th>Department</th>
                                        <th>Month Days</th>
                                        <th>Paid Days</th>
                                        <th>Total earning</th>
                                        <th>Total deduction</th>
                                        <th>Net Amount</th>
                                        <th>Expected Amount</th>
                                        <th>Salary Breakdown</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!--Salary Sheet Table Ends here-->
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.row -->


        <!--Modal Starts here-->
        <div class="modal fade" id="salary_sheet_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Super Admin Salary Breakdown</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Heads</th>
                                        <th>Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>HRA</td>
                                        <td>4000.00</td>
                                    </tr>
                                    <tr>
                                        <td>TA</td>
                                        <td>0.00</td>
                                    </tr>
                                    <tr>
                                        <td>Basic</td>
                                        <td>15100.00</td>
                                    </tr>
                                    <tr>
                                        <td>Conveyance</td>
                                        <td>1600.00</td>
                                    </tr>
                                    <tr>
                                        <td>SPL Allow.</td>
                                        <td>1300.00</td>
                                    </tr>
                                    <tr>
                                        <td>Bonus</td>
                                        <td>0.00</td>
                                    </tr>
                                    <tr>
                                        <td>Other Allow.</td>
                                        <td>0.00</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Heads</th>
                                        <th>Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Other DED</td>
                                        <td>0.00</td>
                                    </tr>
                                    <tr>
                                        <td>MISC DED</td>
                                        <td>0.00</td>
                                    </tr>
                                    <tr>
                                        <td>PF</td>
                                        <td>2292.00</td>
                                    </tr>
                                    <tr>
                                        <td>ESI</td>
                                        <td>0.00</td>
                                    </tr>
                                    <tr>
                                        <td>Total Earnings</td>
                                        <td>22000</td>
                                    </tr>
                                    <tr>
                                        <td>Total Deductions</td>
                                        <td>0</td>
                                    </tr>
                                    <tr>
                                        <td>Net Amount</td>
                                        <td>656.93</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!--Modal Ends here-->


    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script src="plugins/dataTables/jquery.dataTables.min.js"></script>
<script src="validations/jquery.validate.js"></script>
<script src="validations/additional-methods.js"></script>

<script>

    $('#salary_sheet_table').DataTable({
        responsive: true
    });

    $("#salary_sheets").validate({
        rules: {
            "name" : {
                required: true
            },
            "from" : {
                required: true
            },
            "to" : {
                required: true
            }
        },
        messages: {
            "name" : {
                required: 'Please enter name'
            },
            "from" : {
                required: 'Please select any value'
            },
            "to" : {
                required: 'Please select any value'
            }
        }
    });
</script>

@endSection








