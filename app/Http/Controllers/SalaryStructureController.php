<?php

namespace App\Http\Controllers;

use App\Project;
use App\SalaryCycle;
use App\SalaryHead;
use Illuminate\Http\Request;
use App\SalaryStructure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class SalaryStructureController extends Controller
{
    public function index(){
        $salaryStructures = SalaryStructure::all();
        return view('salary_structure.index', compact('salaryStructures'));
    }

    public function create(){
        $earningSalaryHeads = SalaryHead::where('type','earning')->get();
        $deductionSalaryHeads = SalaryHead::where('type','deduction')->get();
        $projects = Project::where(['isactive'=>1,'approval_status'=>'1'])->get();
        return view('salary_structure.create', compact('earningSalaryHeads', 'deductionSalaryHeads', 'projects'));
    }

    public function store(Request $request){
        $earningHeads = $request['earning_heads'];
        $earningHeadPfApplicables = $request['earning_head_pf_applicables'];

        $deductionHeads = $request['deduction_heads'];
        $deductionHeadPfApplicables = $request['deduction_head_pf_applicables'];

        if($earningHeads != '') {
            for ($i = 0; $i < count($earningHeads); $i++) {
                if (isset($earningHeadPfApplicables[$i]) != '') {
                    $pfApplicable = $earningHeadPfApplicables[$i];
                } else {
                    $pfApplicable = 0;
                }
                if (SalaryStructure::where('project_id', $request['project_id'])->where('salary_cycle_id', $request['salary_cycle'])->where('salary_head_id', $earningHeads[$i])->doesntExist()){
                    SalaryStructure::create([
                        'project_id' => $request['project_id'],
                        'salary_cycle_id' => $request['salary_cycle'],
                        'salary_head_id' => $earningHeads[$i],
                        'calculation_type' => 'earning',
                        'pf_applicable' => $pfApplicable
                    ]);
                }
            }
        }

        if($deductionHeads != '') {
            for ($i = 0; $i < count($deductionHeads); $i++) {
                if (isset($deductionHeadPfApplicables[$i]) != '') {
                    $pfApplicable = $deductionHeadPfApplicables[$i];
                } else {
                    $pfApplicable = 0;
                }
                SalaryStructure::create([
                    'project_id' => $request['project_id'],
                    'salary_cycle_id' => $request['salary_cycle'],
                    'salary_head_id' => $deductionHeads[$i],
                    'calculation_type' => 'deduction',
                    'pf_applicable' => $pfApplicable
                ]);
            }
        }

//        $heads = array("project_id" => $request->project_id,"salary_cycle_id" => $request->salary_cycle,"earning_heads" =>$request->earning_heads, "earning_head_pf_applicables" => $request->earning_head_pf_applicables, "deduction_heads" =>$request->deduction_heads, "deduction_head_pf_applicables" => $request->deduction_head_pf_applicables);
//        DB::table('project_drafts')->insert([
//            "salary_structure" => serialize($heads),
//        ]);
        return redirect()->route('payroll.salary.structure.index')->with('success','Salary Structure created successfully!');
    }

    public function save(Request $request){
        $salaryStructure = unserialize(DB::table('project_drafts')->pluck('salary_structure')->last());
        $earningHeads = $salaryStructure['earning_heads'];
        $earningHeadPfApplicables = $salaryStructure['earning_head_pf_applicables'];

        $deductionHeads = $salaryStructure['deduction_heads'];
        $deductionHeadPfApplicables = $salaryStructure['deduction_head_pf_applicables'];

        for($i=0; $i<count($earningHeads); $i++){
            if(isset($earningHeadPfApplicables[$i]) != ''){
                $pfApplicable = $earningHeadPfApplicables[$i];
            }else{
                $pfApplicable=0;
            }
            SalaryStructure::create([
                'project_id' => $salaryStructure['project_id'],
                'salary_cycle_id' => $salaryStructure['salary_cycle_id'],
                'salary_head_id' => $earningHeads[$i],
                'calculation_type' => 'earning',
                'pf_applicable' => $pfApplicable
            ]);
        }

        for($i=0; $i<count($deductionHeads); $i++){
            if( isset($deductionHeadPfApplicables[$i]) != ''){
                $pfApplicable = $deductionHeadPfApplicables[$i];
            }else{
                $pfApplicable=0;
            }
            SalaryStructure::create([
                'project_id' => $salaryStructure['project_id'],
                'salary_cycle_id' => $salaryStructure['salary_cycle_id'],
                'salary_head_id' => $deductionHeads[$i],
                'calculation_type' => 'deduction',
                'pf_applicable' => $pfApplicable
            ]);
        }
    }

    public function show(){
        return view('salary_structure.show');
    }

    public function edit(SalaryStructure $salaryStructure){

        return view('salary_structure.edit', compact('salaryStructure'));
    }

    public function update(Request $request, SalaryStructure $salaryStructure){
        $request->validate([
            'salary_structure' => 'required|max:50|unique:salary_structures,name,'.$salaryStructure->id.',id,deleted_at,NULL',
        ]);
        SalaryStructure::where('id',$salaryStructure->id)->update([
            'name' => $request->salary_structure,
            'type' => $request->type
        ]);

        return redirect()->route('payroll.salary.head.index')->with('success','Salary Structure updated successfully!');
    }

    public function destroy(SalaryStructure $salaryStructure){
        $salaryStructure->delete();
        return redirect()->route('payroll.salary.structure.index')->with('success','Salary Structure deleted successfully!');;
    }

    public function projectSalaryCycle(Request $request){
        $salaryHeads = SalaryCycle::where('project_id',$request->project_id)->get();
        return Response::json(['success'=>true,'data'=>$salaryHeads]);
    }

    public function salaryHeads(Request $request){
        $allEarningHeads = \App\SalaryStructure::where('project_id', $request->project_id)->where('salary_cycle_id', $request->salary_cycle_id)->where('calculation_type', 'earning')->get()->pluck('salary_head_id');

        if(count($allEarningHeads) > 0) {
            foreach ($allEarningHeads as $earningHead) {
                $earningHeads[] = SalaryHead::where('id', $earningHead)->first();
            }
        }else{
            $earningHeads = '';
        }

        $allDeductionHeads = \App\SalaryStructure::where('project_id', $request->project_id)->where('salary_cycle_id', $request->salary_cycle_id)->where('calculation_type', 'deduction')->get()->pluck('salary_head_id');

        if(count($allDeductionHeads) > 0) {
            foreach ($allDeductionHeads as $deductionHead) {
                $deductionHeads[] = SalaryHead::where('id', $deductionHead)->first();
            }
        }else{
            $deductionHeads ='';
        }

        $data = array('earning_heads'=>$earningHeads,'deduction_heads' => $deductionHeads);
        return Response::json(['success'=>true, 'data' => $data]);
    }

}
