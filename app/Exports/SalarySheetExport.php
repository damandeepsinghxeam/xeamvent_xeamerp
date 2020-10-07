<?php

namespace App\Exports;

use App\Project;
use App\SalarySheet;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class SalarySheetExport implements FromView
{
//    protected $year;
//    protected $month;
//    protected $project;
//    protected $department;
//    protected $paidNotPaid;

    protected $salarySheets;
    protected $projectEarningHeads;
    protected $projectDeductionHeads;

    public function __construct($salarySheets, $projectEarningHeads, $projectDeductionHeads)
    {
        $this->salarySheets = $salarySheets;
        $this->projectEarningHeads = $projectEarningHeads;
        $this->projectDeductionHeads = $projectDeductionHeads;

    }
    /**
    * @return \Illuminate\Support\Collection
    */
//    public function collection()
//    {
//        if($this->department != '') {
//            if($this->paidNotPaid == 'generate') {
//                $salarySheets = SalarySheet::where('year', $this->year)->where('month', $this->month)->where('project', $this->project)->where('department', $this->department)->where('status', 'generate')->get();
//            }
//            elseif($this->paidNotPaid == 'not_paid'){
//                $salarySheets = SalarySheet::where('year', $this->year)->where('month', $this->month)->where('project', $this->project)->where('department', $this->department)->where('status', 'on_hold')->get();
//            }else{
//                $salarySheets = SalarySheet::where('year', $this->year)->where('month', $this->month)->where('project', $this->project)->where('department', $this->department)->get();
//            }
//        }else{
//            if($this->paidNotPaid == 'generate') {
//                $salarySheets = SalarySheet::where('year', $this->year)->where('month', $this->month)->where('project', $this->project)->where('status', 'generate')->get();
//            }
//            elseif($this->paidNotPaid == 'on_hold'){
//                $salarySheets = SalarySheet::where('year', $this->year)->where('month', $this->month)->where('project', $this->project)->where('status', 'on_hold')->get();
//            }else {
//                $salarySheets = SalarySheet::where('year', $this->year)->where('month', $this->month)->where('project', $this->project)->get();
//            }
//        }
//        return $salarySheets;
//    }

//    public function headings(): array
//    {
//        return [
//            'Employee Name',
//            'Employee Code',
//            'Fathers Name',
//            'Designation',
//            'Department',
//        ];
//    }

    public function view(): View
    {
        $salarySheets = $this->salarySheets;
        $projectEarningHeads = $this->projectEarningHeads;
        $projectDeductionHeads = $this->projectDeductionHeads;
        return view('salary_sheet.export', compact('salarySheets', 'projectEarningHeads', 'projectDeductionHeads'));
    }
}
