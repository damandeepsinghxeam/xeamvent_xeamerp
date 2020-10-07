<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Department;
use App\Esi;
use App\Exports\SalarySheetExport;
use App\Pf;
use App\Project;
use App\PtRate;
use App\SalaryHead;
use App\SalarySheetBreakdown;
use App\SalaryStructure;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use App\SalarySheet;
use App\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Imports\SalarySheetImport;
use Excel;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SalarySheetController extends Controller
{
    public function index(){
        $years = range( date("Y") , 2000 );
//        $salarySheets = SalarySheet::all();

        $projects = Project::where('isactive', 1)->get();
        $departments = Department::where('isactive', 1)->get();
        return view('salary_sheet.index', compact('years', 'projects', 'departments'));
    }

    public function create(){
        $employeeCodes = Employee::where('isactive',1)->where('cover_amount','!=','')->get()->pluck('employee_id');
        $years = range( date("Y") , 2000 );
        $months = array();
        for ($i = 0; $i < 12; $i++) {
            $timestamp = mktime(0, 0, 0, date('n') - $i, 1);
            $months[date('n', $timestamp)] = [date('m', $timestamp), date('F', $timestamp)];
        }

        $projects = Project::where('isactive', 1)->get();
        $departments = Department::where('isactive', 1)->get();
        return view('salary_sheet.create', compact('employeeCodes', 'years', 'months', 'projects', 'departments'));
    }

    public function store(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $project = Project::where('id', $request->project_id)->first();
        $projectEmployees = $project->users->pluck('id')->toArray();

        if ($request->department_id == 'all') {
            $employeeUserIds = $projectEmployees;
        } else {
            $department = Department::where('id', $request->department_id)->first()->name;
            $departmentEmployees = DB::table('employee_profiles')->where('department_id', $request->department_id)->get()->pluck('user_id')->toArray();
            $employeeUserIds = array_intersect($projectEmployees, $departmentEmployees);
        }

//        foreach($employeeUserIds as $employeeUserId) {
        $user = User::where('id', 33)->first();

        $employeeDepartmentId = DB::table('employee_profiles')->where('user_id', $user->id)->first()->department_id;
        $department = Department::where('id', $employeeDepartmentId)->first()->name;

        $designation = $user->designation->pluck('name');

        $employeeEarningSalaryStructure = DB::table('employee_salary_structures')->where('user_id', $user->id)->where('calculation_type', 'earning')->get();
        $employeeEarning = DB::table('employee_salary_structures')->where('user_id', $user->id)->where('calculation_type', 'earning')->sum('value');

        $employeeDeductionSalaryStructure = DB::table('employee_salary_structures')->where('user_id', $user->id)->where('calculation_type', 'deduction')->get();
        $employeeDeductions = DB::table('employee_salary_structures')->where('user_id', $user->id)->where('calculation_type', 'deduction')->sum('value');

        if($employeeEarning <= 15000){
            $pfSalaryHeads = SalaryStructure::where('project_id', $project->id)->where('salary_cycle_id', $employeeEarningSalaryStructure[0]->salary_cycle_id)->where('pf_applicable', 1)->get()->pluck('salary_head_id')->toArray();
            $pfSalaryHeadsValue = DB::table('employee_salary_structures')->where('user_id', $user->id)->whereIn('salary_head_id', $pfSalaryHeads)->sum('value');
            $activePfPercent = Pf::where('is_active', 1)->first()->total_pf;
            $pf = ($pfSalaryHeadsValue / 100) * $activePfPercent;
        }else{
            $pf = 0;
        }

        $activeEsi = Esi::where('is_active', 1)->first();
        $totalEsiPercent = $activeEsi->employee_percent;
        if ($employeeEarning < $activeEsi->cutoff) {
            $esi = ($employeeEarning / 100) * $totalEsiPercent;
        } else {
            $esi = 0;
        }


        $employeeState = DB::table('employee_profiles')->where('user_id', $user->id)->first()->state_id;
        $ptRate = PtRate::where('state_id', $employeeState)->first();
        if(isset($ptRate)){
            $ptRateRanges = DB::table('pt_rate_salary_ranges')->where('pt_rate_id', $ptRate->id)->where('min_salary', '>=', $employeeEarning)->where('max_salary', '<=', $employeeEarning)->get();
            if(count($ptRateRanges) >= 1) {
                $pt = $ptRateRanges->pt_rate;
            }else{
                $pt = 0;
            }
        }else{
            $pt = 0;
        }

        $totalDeduction = $employeeDeductions + $pf+$esi+$pt;
        $employeeSalary = $employeeEarning - $totalDeduction;

        $employee = Employee::where('user_id', $user->id)->first();
        $monthlySalary = $employeeSalary;
        $totalsalaryMonthDays = cal_days_in_month(CAL_GREGORIAN,$month,$year);
        $salaryPerDay = ($monthlySalary / $totalsalaryMonthDays);
        $employeePresentDays = DB::table('attendance_results')->where('user_id', $employee->user_id)->whereYear('on_date', $year)->whereMonth('on_date', $month)->first()->total_present_days;

        $salarySheet = SalarySheet::create([
            'year' => $year,
            'month' => $month,
            'user_id' => $user->id,
            'project' => $project->name,
            'designation' => $designation[0],
            'department' => $department,
            'total_month_days' => $totalsalaryMonthDays,
            'paid_days' => $employeePresentDays,
            'unpaid_days' => $totalsalaryMonthDays - $employeePresentDays,
            'salary' => $salaryPerDay * $employeePresentDays,
            'gross_salary' => $monthlySalary,
            'esi' => $esi,
            'pf' => $pf,
            'pt' => $pt,
            'total_earning' => $employeeEarning,
            'total_deduction' => $totalDeduction
        ]);

        foreach ($employeeEarningSalaryStructure as $earningHeads){
            $salaryHeadName = SalaryHead::where('id', $earningHeads->salary_head_id)->first()->name;
            DB::table('salary_sheet_breakdowns')->insert([
                'salary_sheet_id' => $salarySheet->id,
                'salary_head_id' => $earningHeads->salary_head_id,
                'salary_head_name' => $salaryHeadName,
                'salary_head_type' => $earningHeads->calculation_type,
                'value' =>$earningHeads->value
            ]);
        }

        foreach ($employeeDeductionSalaryStructure as $deductionHeads){
            $salaryHeadName = SalaryHead::where('id', $deductionHeads->salary_head_id)->first()->name;
            DB::table('salary_sheet_breakdowns')->insert([
                'salary_sheet_id' => $salarySheet->id,
                'salary_head_id' => $deductionHeads->salary_head_id,
                'salary_head_name' => $salaryHeadName,
                'salary_head_type' => $deductionHeads->calculation_type,
                'value' =>$deductionHeads->value
            ]);
        }
//        }
        return redirect()->route('payroll.salary.sheet.index')->with('success', 'Salary Sheet created successfully!');

    }

    public function edit(SalarySheet $salarySheet){
        return view('salary_sheet.edit', compact('salarySheet'));
    }

    public function update(Request $request, SalarySheet $salarySheet){

        $salarySession = explode('-', $request->salary_of);
        $year = $salarySession[0];
        $month = $salarySession[1];

        $ctc = Employee::where('employee_id', $request->employee_code)->first()->cover_amount;
        $ctc = str_replace(',', '', $ctc);
        $monthlySalary = ($ctc / 12);
        $salaryPerDay = ($monthlySalary / $request->total_month_days);

        SalarySheet::where('id', $salarySheet->id)->update([
            'date' => $request->salary_of,
            'total_month_days' => $request->total_month_days,
            'year' => $year,
            'month' => $month,
            'paid_days' => $request->paid_days,
            'unpaid_days' => $request->unpaid_days,
            'salary' => $salaryPerDay * $request->paid_days,
            'gross_salary' => $monthlySalary
        ]);

        return redirect()->route('payroll.salary.sheet.index')->with('success','Salary Sheet updated successfully!');
    }

    public function destroy($salarySheet){
        $salarySheet = SalarySheet::findorfail($salarySheet);
        $salarySheet->delete();
        return redirect()->route('payroll.salary.sheet.index')->with('success','Salary Sheet deleted successfully!');
    }

    public function filter(Request $request){
//        if($request->year != '' AND $request->month == '') {
//            $salarySheets = SalarySheet::where('year', $request->year)->get();
//        }
//        elseif($request->year == '' AND $request->month != '') {
//            $salarySheets = SalarySheet::where('month', $request->month)->get();
//        }
//        elseif($request->year != '' AND $request->month != '') {
//            $salarySheets = SalarySheet::where('year', $request->year)->where('month', $request->month)->get();
//        }
        if($request->department != '') {
            if($request->paid_not_paid == 'generate') {
                $salarySheets = SalarySheet::where('year', $request->year)->where('month', $request->month)->where('project', $request->project)->where('department', $request->department)->where('status', 'generate')->get();
            }
            elseif($request->paid_not_paid == 'not_paid'){
                $salarySheets = SalarySheet::where('year', $request->year)->where('month', $request->month)->where('project', $request->project)->where('department', $request->department)->where('status', 'on_hold')->get();
            }else{
                $salarySheets = SalarySheet::where('year', $request->year)->where('month', $request->month)->where('project', $request->project)->where('department', $request->department)->get();
            }
        }else{
            if($request->paid_not_paid == 'generate') {
                $salarySheets = SalarySheet::where('year', $request->year)->where('month', $request->month)->where('project', $request->project)->where('status', 'generate')->get();
            }
            elseif($request->paid_not_paid == 'on_hold'){
                $salarySheets = SalarySheet::where('year', $request->year)->where('month', $request->month)->where('project', $request->project)->where('status', 'on_hold')->get();
            }else {
                $salarySheets = SalarySheet::where('year', $request->year)->where('month', $request->month)->where('project', $request->project)->get();
            }
        }

        if($salarySheets != '') {
            $output = "";
            $serialNumber = 1;
            $redirect_url = '?id='.'&year='.'&month=';

            foreach ($salarySheets as $key => $salarySheet) {

                $output .= '<tr>' .
                    $this->salarySheetStatus($salarySheet, $serialNumber).
                    '<td>' . $salarySheet->project . '</td>' .
                    '<td>' . $salarySheet->department . '</td>' .
                    '<td>' . $salarySheet->total_month_days . '</td>' .
                    '<td>' . $salarySheet->paid_days . '</td>' .
                    '<td>' . $salarySheet->total_earning . '</td>' .
                    '<td>' . $salarySheet->total_deduction . '</td>' .
                    '<td>' . $salarySheet->salary . '</td>' .
                    '<td>' . $salarySheet->gross_salary . '</td>' .
                    '<td>' .
                    '<span class="btn bg-purple" data-toggle="modal" data-target="' . '#salaryBreakdown' . $salarySheet->id . '">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </span>'
                    . '</td>' .
                    '<td>'.
                        '<a title="View Attendance & Verify" target="_blank" href="' . url('attendances/view', $redirect_url) . '">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                        </a>'.
                    '</td>'.
                    ' <td>' .
                    '<div class="modal" id="' . 'salaryBreakdown' . $salarySheet->id . '">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">' .
                    Employee::where("user_id", $salarySheet->user->pluck("id"))->first()->fullname
                    . 'Salary Breakdown</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                      <table class="table table-striped table-bordered"  id="tablexportData">
                                                            <thead class="table-heading-style">
                                                            <tr>'.
                    $this->breakdownHeadNames($salarySheet).'
                                                                <th>PF</th>
                                                                <th>ESI</th>
                                                                <th>Total Earnings</th>
                                                                <th>Total Deductions</th>
                                                                <th>Net Amount</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>'.
                    $this->breakdownHeadValues($salarySheet).'
                                                                <td>'. $salarySheet->pf .'</td>
                                                                <td>'. $salarySheet->esi .'</td>
                                                                <td>'.
                    SalarySheetBreakdown::where('salary_sheet_id',$salarySheet->id)->where('salary_head_type','earning')->sum('value')
                    .'</td>
                                                                <td>'.
                    SalarySheetBreakdown::where('salary_sheet_id',$salarySheet->id)->where('salary_head_type','deduction')->sum('value')
                    .'</td>
                                                                <td>' . $salarySheet->salary . '</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </td>' .
                    '</tr>';
                $serialNumber++;
            }

            return Response::json(['table_data' => $output, 'data' => $salarySheets]);
        }
    }

    public function breakdownHeadNames($salarySheet){
        $breakdownHeadNames =  '';
        foreach($salarySheet->salaryBreakdowns->pluck("salary_head_name") as $salaryHead) {
            $breakdownHeadNames .= '<th>' . $salaryHead . '</th>';
        }
        return $breakdownHeadNames;
    }

    public function breakdownHeadValues($salarySheet){
        $breakdownHeadNames =  '';
        foreach($salarySheet->salaryBreakdowns as $salaryBreakdown) {
            $breakdownHeadNames .= '<td>' . $salaryBreakdown->value . '</td>';
        }
        return $breakdownHeadNames;
    }

    public function salarySheetStatus($salarySheet, $serialNumber){
        if($salarySheet->status == 'generate') {
            return '<td>'.'<input type="checkbox" name="salary_sheet_id[]" value="'.$salarySheet->id.'" checked disabled>'.'</td>'.
                '<td>' . $serialNumber . '</td>' .
                '<td style="background: green; color:white">' . Employee::where('user_id', $salarySheet->user->id)->first()->fullname . '</td>';
        }elseif($salarySheet->status == 'on_hold'){
            return '<td>'.'<input type="checkbox" name="salary_sheet_id[]" value="'.$salarySheet->id.'">'.'</td>'.
                '<td>' . $serialNumber . '</td>' .
                '<td style="background: red; color:white">' . Employee::where('user_id', $salarySheet->user->id)->first()->fullname . '</td>';
        }else{
            return '<td>'.'<input type="checkbox" name="salary_sheet_id[]" value="'.$salarySheet->id.'">'.'</td>'.
                '<td>' . $serialNumber . '</td>' .
                '<td>' . Employee::where('user_id', $salarySheet->user->id)->first()->fullname . '</td>';
        }
    }

    public function export(Request $request){
        if($request->department != '') {
            if($request->paid_not_paid == 'generate') {
                $salarySheets = SalarySheet::where('year', $request->year)->where('month', $request->month)->where('project', $request->project)->where('department', $request->department)->where('status', 'generate')->get();
            }
            elseif($request->paid_not_paid == 'not_paid'){
                $salarySheets = SalarySheet::where('year', $request->year)->where('month', $request->month)->where('project', $request->project)->where('department', $request->department)->where('status', 'on_hold')->get();
            }else{
                $salarySheets = SalarySheet::where('year', $request->year)->where('month', $request->month)->where('project', $request->project)->where('department', $request->department)->get();
            }
        }else{
            if($request->paid_not_paid == 'generate') {
                $salarySheets = SalarySheet::where('year', $request->year)->where('month', $request->month)->where('project', $request->project)->where('status', 'generate')->get();
            }
            elseif($request->paid_not_paid == 'on_hold'){
                $salarySheets = SalarySheet::where('year', $request->year)->where('month', $request->month)->where('project', $request->project)->where('status', 'on_hold')->get();
            }else {
                $salarySheets = SalarySheet::where('year', $request->year)->where('month', $request->month)->where('project', $request->project)->get();
            }
        }

        $project = Project::where('name',$request->project)->first();
        $projectEarningHeads = DB::table('salary_structures')->join('salary_heads', 'salary_structures.salary_head_id', '=', 'salary_heads.id')->where('project_id', $project->id)->where('calculation_type', 'earning')->select('name')->get();
        $projectDeductionHeads = DB::table('salary_structures')->join('salary_heads', 'salary_structures.salary_head_id', '=', 'salary_heads.id')->where('project_id', $project->id)->where('calculation_type', 'deduction')->select('name')->get();

        return Excel::download(new SalarySheetExport($salarySheets, $projectEarningHeads, $projectDeductionHeads), 'salary-sheet.xlsx');
    }

    public function pay(Request $request){
        foreach ($request->salary_sheet_id as $salarySheetId){
            SalarySheet::where('id', $salarySheetId)->update([
                'status' => $request->status
            ]);
        }
        return back()->with('success', "Salary Sheet Status Updated Successfully");
    }


    public function extraIncome(Request $request){
       return $request->year;
        return back()->with('success', "Salary Sheet Status Updated Successfully");
    }


    public function upload(Request $request){
        if($request->hasFile('file')){

            $data = Excel::toArray(new SalarySheetImport(), $request->file('file'));

            foreach ($data[0] as $key => $row) {
                if($key > 0) {

                    $user = User::where('employee_code', $row[0])->first();
                    if($user != '') {
                        $employee = Employee::where('user_id', $user->id)->first();
                        if ($employee->isactive == 1) {
                            $ctc = str_replace(',', '', $employee->cover_amount);
                            $monthlySalary = ($ctc / 12);
                            $salaryPerDay = ($monthlySalary / 30);

                            $time=strtotime($request->salary_of);
                            $month=date("m",$time);
                            $year=date("Y",$time);

                            $employeePresentDays = DB::table('attendance_results')->where('user_id', $employee->user_id)->whereYear('on_date', $year)->whereMonth('on_date', $month)->get()->count();
                            $totalCurrentMonthDays = date('t');

                            $arr[] = [
                                'employee_code' => $row[0],
                                'date' => $request->salary_of,
                                'total_month_days' => $totalCurrentMonthDays,
                                'year' => $year,
                                'month' => $month,
                                //'paid_days' => $request->paid_days,
                                'paid_days' => $employeePresentDays,
                                'unpaid_days' => $totalCurrentMonthDays - $employeePresentDays,
                                'salary' => $salaryPerDay * $employeePresentDays,
                                'gross_salary' => $monthlySalary
                            ];
                        } else {
                            $notActiveEmployees[] = $employee->employee_id;
                        }
                    }else{
                        $wrongEmployeeCode[] =  $row[0];
                    }
                }
            }
            if(!empty($arr)){
                \DB::table('salary_sheets')->insert($arr);
                if(isset($notActiveEmployees) AND $notActiveEmployees != '') {
                    return redirect()->route('payroll.salary.sheet.index')->with('success', 'Salary Sheet created successfully!')->withErrors([$notActiveEmployees, 'These are not active employees']);
                }elseif(isset($wrongEmployeeCode) AND $wrongEmployeeCode != ''){
                    return redirect()->route('payroll.salary.sheet.index')->with('success', 'Salary Sheet created successfully!')->withErrors([$wrongEmployeeCode, 'Incorrect Employee Ids']);
                }else{
                    return redirect()->route('payroll.salary.sheet.index')->with('success', 'Salary Sheet created successfully!');
                }
            }else{
                return redirect()->route('payroll.salary.sheet.index');
            }
        }

    }
}
