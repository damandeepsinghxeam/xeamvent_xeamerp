<?php

namespace App\Http\Controllers;

use App\Vendor;
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
            'mobile'                        => 'required',
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

        Vendor::create($data);
        return redirect()->back()->with('success', "Vendor created successfully.");
    }

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
