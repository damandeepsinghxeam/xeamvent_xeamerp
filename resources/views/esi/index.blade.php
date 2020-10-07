@extends('admins.layouts.app')
@section('content')
    <link rel="stylesheet" href="{{asset('public/admin_assets/plugins/dataTables/jquery.dataTables.min.css')}}">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>ESI LISTING</h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('employees/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="box">
                    <div class="box-header">
                        @include('admins.validation_errors')

                        @can('create', \App\Esi::class)
                            <div class="box-footer text-right">
                                <a href="{{ route('payroll.esi.create') }}">
                                    <button class="btn btn-primary submit-btn-style" id="submit2" value="Add New">Add New</button>
                                </a>
                            </div>
                        @endcan
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="listHolidays" class="table table-bordered table-striped">
                            <thead class="table-heading-style">
                            <tr>
                                <th>S.No.</th>
                                <th>Effective From</th>
                                <th>Employee(%)</th>
                                <th>Employer(%)</th>
                                <th>CutOff</th>
                                <th>Is Active</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $counter = 1; @endphp
                            @foreach($esis as $key =>$value)
                                <tr>
                                    <td>{{ @$counter++ }}</td>
                                    <td>{{ date('d-M-Y', strtotime(@$value->effective_esi_dt)) }}</td>
                                    <td>{{ @$value->employee_percent }}</td>
                                    <td>{{ @$value->employer_percent }}</td>
                                    <td>{{ @$value->cutoff }}</td>
                                    <td>
                                        @can('update', $value)
                                        <form id="makeActive" action="{{ route('payroll.esi.make.active') }}" method="POST" >
                                               @csrf
                                            <input type="hidden" name="esi_id" value="{{ @$value->id }}">
                                            <select name="is_active" class="is_active" onchange="this.form.submit()">
                                                <option value="1" {{$value->is_active == 1  ? 'selected' : ''}}>Active</option>
                                                <option value="0" {{$value->is_active == 0  ? 'selected' : ''}}>In-active</option>
                                            </select>
                                        </form>
                                        @endcan
                                    </td>
                                    <td>
                                        <a class="btn btn-primary" target="_blank" href='{{route("payroll.esi.edit",$value->id)}}'>Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="table-heading-style">
                            <tr>
                                <th>S.No.</th>
                                <th>Effective From</th>
                                <th>Employee(%)</th>
                                <th>Employer(%)</th>
                                <th>CutOff</th>
                                <th>Is Active</th>
                                <th>Actions</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.row -->
            <!-- Main row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <script src="{{asset('public/admin_assets/plugins/dataTables/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#listHolidays').DataTable({
                scrollX: true,
                responsive: true
            });
            setTimeout(function() {
                $('.message').fadeOut('fast');
            }, 1000);
        });
    </script>
@endsection
