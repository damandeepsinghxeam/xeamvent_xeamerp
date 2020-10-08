<?php

namespace App\Http\Controllers;

use App\Vendor;
use App\VendorApprovals;
use Illuminate\Http\Request;

use App\Project;
use App\AdRequisitionForm;
use App\ArfApproval;
use App\Country;
use App\State;
use App\City;
use App\Department;
use App\Designation;
use App\Employee;
use App\EmployeeProfile;
use App\Http\Controllers\Controller;
use App\Jrf;
use App\JrfApprovals;
use App\MgmtDtApprovals;
use App\JrfCity;
use App\JrfHierarchy;
use App\JrfInterviewerDetail;
use App\JrfLevelOneScreening;
use App\JrfLevelTwoScreening;
use App\FinalAppointmentApproval;
use App\JrfLevelOneScreeningLanguage;
use App\JrfLevelOneScreeningQualification;
use App\JrfLevelOneScreeningSkill;
use App\JrfQualification;
use App\JrfRecruitmentTasks;
use App\JrfSkill;
use App\Language;
use App\LeaveAuthority;
use App\Mail\GeneralMail;
use App\Mail\JrfGeneralMail;
use App\Qualification;
use App\Skill;
use App\Vendoritem;
use App\Closour;
use App\BenifitPerks;
use App\JobPosts;
use App\CandidateApprovals;
use App\ManagementApprovals;
use App\UserManager;
use App\User;
use Auth;
use DateTime;
use DB;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Validator;
use View;
use App\Helper;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::guest()) {
            return redirect('/');
        }

        $data['countries'] = Country::where(['isactive'=>1])->get();
        $data['states'] = State::where(['isactive'=>1])->get();    
        $data['cities']         = City::where(['isactive' => 1])->orderBy('name')->get();
        $data['vendoritems']         = Vendoritem::where(['isactive' => 1])->orderBy('name')->select('id', 'name')->get();
       
        return view('vendor.create_vendor')->with(['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveVendor(Request $request)
    {   

      $data = $request->all();

        if (Auth::guest()) {
            return redirect('/');
        }

        $validator = Validator::make($request->all(), [
            'name_of_firm'                  => 'required',
            'type_of_firm'                  => 'required',
            'status_of_company'             => 'required',
            'address'                       => 'required',
            'country_id'                    => 'required',
            'state_id'                      => 'required',
            'city_id'                       => 'required',
            'pin'                           => 'required',
            'std_code_with_phn_no'          => 'required',
            'email'                         => 'required',
            'mobile'                        => ['required','min:10','max:10'],
            'name_of_contact_person'        => 'required',
            'designation_of_contact_person' => 'required',
            'description_of_company'        => 'required',      
            'items_for_service'             => 'required',     
        ]);

        if ($validator->fails()) {
            return redirect("vendor/create")
                ->withErrors($validator, 'basic')
                ->withInput();
        }

        $data = [
            'name_of_firm'                   => $request->name_of_firm,
            'type_of_firm'                   => $request->type_of_firm,
            'type_of_firm_others'            => $request->type_of_firm_others,
            'status_of_company'              => $request->status_of_company,
            'type_of_service_provide'        => $request->type_of_service_provide,
            'manpower_provided'              => $request->manpower_provided,
            'address'                        => $request->address,
            'country_id'                     => $request->country_id,
            'state_id'                       => $request->state_id,
            'city_id'                        => $request->city_id,
            'pin'                            => $request->pin,
            'std_code_with_phn_no'           => $request->std_code_with_phn_no,
            'email'                          => $request->email,
            'website'                        => $request->website,
            'mobile'                         => $request->mobile,
            'name_of_contact_person'         => $request->name_of_contact_person,
            'designation_of_contact_person'  => $request->designation_of_contact_person,
            'description_of_company'         => $request->description_of_company,
            'items_for_service'              => implode(',', $request->items_for_service)
        
        ];

        $user      = User::where(['id' => Auth::id()])->with('employee')->first();

         $saved_vendor = $user->vendor()->create($data);     

        // $userId = User::permission('vendor-approval')->pluck('id');
         $userId = User::where('employee_code', '01')->first()->id;
        $vendor_approval = [
            'user_id'       => $request->user_id,
            'vendor_id'        => $saved_vendor->id,
            'supervisor_id' => $userId,
            // 'priority'      => '2', 
            'vendor_status'    => '0', //inprogress
        ];

        //dd($vendor_approval);
        
        $saved_vendor = $user->vendorapprovals()->create($vendor_approval);

        return redirect()->back()->with('success', "Vendor created successfully.");
    }

    // List view of Jrf

    // public function listVendor(Request $request)
    // {

    //     if (Auth::guest()) {
    //         return redirect('/');
    //     }
        
    //     $condition = array();
    //     $user = User::where(['id' => Auth::id()])->first();

    //     $condition[] = array('vendor.user_id', '=', $user->id);

    //     if(!empty($request->project_id)){
    //         $condition[] = array('vendor.vendor_id', '=', $request->vendor_id);
    //     }

    //     // if(!empty($request->designation_id)){
    //     //     $condition[] = array('jrf.designation_id', '=', $request->designation_id);
    //     // }

    //     $jrfs = DB::table('vendors as vendor')
    //         ->join('designations as des', 'vendor.designation_id', 'des.id')
    //         ->join('departments as dep','vendor.department_id','=','dep.id')
    //         ->join('roles as r', 'vendor.role_id', 'r.id')
    //         ->join('projects as prj','vendor.project_id','prj.id')
    //         ->where($condition)            
    //         ->select('vendor.*', 'des.name as designation', 'r.name as role','prj.name','dep.name as department')
    //         ->orderBy('vendor.id', 'DESC')
    //         ->get();


    //     $detail['recruitment_detail'] = DB::table('jrf_recruitment_tasks as jrt')
    //                                     ->join('vendors as vendor','jrt.jrf_id','=','vendor.id')
    //                                     ->where('jrt.user_id',$user->id)    
    //                                     ->select('jrt.last_date','jrt.jrf_id')    
    //                                     ->get();

    //     $projects = Project::where(['isactive'=>1])->get();
    //     $designations = Designation::where(['isactive'=>1])->select('id','name')->get();

    //     if (!$vendors->isEmpty()) {
    //         foreach ($vendors as $key => $value) {


    //             $vendor_approval_status = DB::table('vendor_approvals as va')
    //                 ->leftjoin('jrf_recruitment_tasks as jrt','va.vendor_id','=','jrt.jrf_id')    
    //                 ->where(['va.vendor_id' => $value->id])->get();
                
    //             $can_cancel_vendor = 0;
    //             if (count($vendor_approval_status) == 1 && $vendor_approval_status[0]->jrf_status == 0) {
    //                 $can_cancel_vendor = 1;
    //             }

    //             $value->vendor_approval_status = $vendor_approval_status;
    //             $value->can_cancel_vendor   = $can_cancel_vendor;

    //             if ($value->final_status == '0') {
    //                 $check_rejected = DB::table('jrf_approvals as va')
    //                     ->where(['va.vendor_id' => $value->id, 'va.jrf_status' => '2'])
    //                     ->first();
    //                 if (!empty($check_rejected)) {
    //                     $value->secondary_final_status = 'Rejected';
    //                 } else {
    //                     $value->secondary_final_status = 'In-Progress';
    //                 }
    //             } else {
    //                     $value->secondary_final_status = 'Closed';
    //             }

    //         }
    //     }
    //     return view('vendor.list_vendor')->with(['vendors' => $vendors,'designations'=>$designations,'projects'=>$projects,'req'=>$request]);

    // }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $vendor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vendor $vendor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        //
    }
}
