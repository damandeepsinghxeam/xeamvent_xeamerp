
<table class="table table-striped table-responsive table-bordered"  id="tablexportData">
    <thead class="table-heading-style">
    <tr style="">
        <th>S No.</th>
        <th>Employee Code</th>
        <th>Employee Name</th>
        <th>Father's Name</th>
        <th>Designation</th>
        <th>Department</th>
        <th>Location</th>
        <th>Project</th>
        @foreach($projectEarningHeads as $projectEarningHead)
            <th>{{ ucwords($projectEarningHead->name) }}</th>
        @endforeach
        <th>Total Earning</th>
        <th>PF</th>
        <th>ESI</th>
        <th>PT</th>
        <th>TDS</th>
        @foreach($projectDeductionHeads as $projectDeductionHead)
            <th>{{ ucwords($projectDeductionHead->name) }}</th>
        @endforeach
        <th>Total Deduction</th>
        <th>Net Amount</th>
    </tr>
    </thead>
    <tbody class="kra_tbody">
    <?php $serialNumber = 1; ?>

        @foreach($salarySheets as $salarySheet)
            <tr>
                <td>{{ $serialNumber }}</td>
                <td>{{ \App\Employee::where('user_id',$salarySheet->user->id)->first()->employee_id }}</td>
                <td>{{ \App\Employee::where('user_id',$salarySheet->user->id)->first()->fullname }}</td>
                <td>{{ \App\Employee::where('user_id',$salarySheet->user->id)->first()->father_name }}</td>
                <td>{{ $salarySheet->designation }}</td>
                <td>{{ $salarySheet->department }}</td>
                <td>Mohali</td>
                <td>{{ $salarySheet->project }}</td>
                @foreach(\Illuminate\Support\Facades\DB::table('salary_sheet_breakdowns')->where('salary_sheet_id',$salarySheet->id)->where('salary_head_type', 'earning')->get() as $salaryBreakdown)
                    <td>{{ $salaryBreakdown->value }}</td>
                @endforeach
                <td>{{ $salarySheet->total_earning }}</td>
                <td>{{ $salarySheet->pf }}</td>
                <td>{{ $salarySheet->esi }}</td>
                <td>{{ $salarySheet->pt }}</td>
                <td>00</td>
                @foreach(\Illuminate\Support\Facades\DB::table('salary_sheet_breakdowns')->where('salary_sheet_id',$salarySheet->id)->where('salary_head_type', 'deduction')->get() as $salaryBreakdown)
                    <td>{{ $salaryBreakdown->value }}</td>
                @endforeach
                <td>{{ $salarySheet->total_deduction }}</td>
                <td>{{ $salarySheet->salary }}</td>
            </tr>

            <?php $serialNumber++ ?>
        @endforeach
    </tbody>
</table>


