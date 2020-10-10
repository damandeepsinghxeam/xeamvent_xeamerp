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

         
        $userId = User::permission('vendor-approval')->pluck('id');
        $supervisorUserId = $userId[0];
    
       // $userId = User::where('employee_code', '01')->first()->id;
        $vendor_approval = [
            'user_id'       => $request->user_id,
            'vendor_id'        => $saved_vendor->id,
            'supervisor_id' => $supervisorUserId,
            'vendor_status'    => '0', //inprogress
        ];

        //dd($vendor_approval);
        
        $saved_vendor = $user->vendorapprovals()->create($vendor_approval);

        return redirect()->back()->with('success', "Vendor created successfully.");
    }


        // code for vendor listing of approval
        
        function listApprovalVendors(){   

           $user = Auth::user();
    
           $canapprove = auth()->user()->can('vendor-approval'); 
    
        //    $projectsdraft_sent = Vendor::where(['status'=>'1'])
        //                     ->orderBy('created_at','DESC')
        //                     ->get(); 

            $vendor_app =  DB::table('vendors as vend')
                           ->join('vendor_approvals as vap','vend.id','=','vap.vendor_id')
                           ->where('vap.vendor_status','0')
                           ->select('vend.*','vap.vendor_status','vap.supervisor_id')
                           ->get();

            // dd($vendor_app);
    
            if($vendor_app AND !$vendor_app->isEmpty() AND $canapprove==1){   
                foreach($vendor_app as $vendorData){         
                    $vendor_approval_data_array['id'] = $vendorData->id;
                    $vendor_approval_data_array['name_of_firm'] = $vendorData->name_of_firm;
                    $vendor_approval_data_array['type_of_firm'] = $vendorData->type_of_firm;
                    $vendor_approval_data_array['status_of_company'] = $vendorData->status_of_company;
                    $vendor_approval_data_array['email'] = $vendorData->email;
                    $vendor_approval_data_array['vendor_status'] = $vendorData->vendor_status;
                    $vendor_approval_data_array['supervisor_id'] = $vendorData->supervisor_id;
                    $data[] = $vendor_approval_data_array;
                    
                }
            }else{
                $data=[];
            }   
            // dd($data);
         
            return view('vendor.list_vendors')->with(['vendors'=>$data, 'approval'=>'1']);
    
        }


        public function editVendor(Request $request)
        {   
    
          $data = $request->all();
          $vendor_id = $request->id;
    
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
    
             //$saved_vendor = $user->vendor()->create($data);     
    
             $saved_vendor = Vendor::where('id', $vendor_id)
                                ->update([
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
                                        ]);
        
            // return redirect()->back()->with('success', "Vendor updated successfully.");
            return redirect("vendor/approval-vendors")->with('success', "Vendor updated successfully.");
        }


    function vendorAction(Request $request, $action, $vendor_id = null)
    {
 
        if(!empty($vendor_id)){
            $vendor = Vendor::find($vendor_id);
        }
        // dd($vendor);

        $user = Auth::user();

        if($action == 'add') {
            
        
        }elseif($action == 'edit'){

            $data['countries'] = Country::where(['isactive'=>1])->get();
            $data['states'] = State::where(['isactive'=>1])->get();    
            $data['cities']         = City::where(['isactive' => 1])->orderBy('name')->get();
            $data['vendoritems']         = Vendoritem::where(['isactive' => 1])->orderBy('name')->select('id', 'name')->get();

            $vendor = Vendor::where(['id'=>$vendor_id])->first();

            //  dd($vendor);
            
            // if($vendor){                
            //     $data['project_draft_values'] = unserialize($vendor->project_approval);
            // }else{
            //     $data['project_draft_values'] = "";
            // }
            $data['action'] = $action;

            // $state_val = [];
            // $location_val = [];
            // foreach($data['states'] as $state){
                
            //     array_push($state_val,$state->id);
            // }
            // $data['state_val'] = $state_val;
            
            // foreach($data['locations'] as $location){
                
            //     array_push($location_val,$location->id);
            // }
            // $data['location_val'] = $location_val;
            $data['id'] = $vendor->id;
            $data['name_of_firm'] = $vendor->name_of_firm;
            $data['type_of_firm'] = $vendor->type_of_firm;
            $data['type_of_firm_others'] = $vendor->type_of_firm_others;         
            $data['status_of_company'] = $vendor->status_of_company;
            $data['type_of_service_provide'] = $vendor->type_of_service_provide;
            $data['manpower_provided'] = $vendor->manpower_provided;
            $data['address'] = $vendor->address;
            $data['country_id'] = $vendor->country_id;
            $data['state_id'] = $vendor->state_id;
            $data['city_id'] = $vendor->city_id;
            $data['pin'] = $vendor->pin;
            $data['std_code_with_phn_no'] = $vendor->std_code_with_phn_no;
            $data['email'] = $vendor->email;
            $data['website'] = $vendor->website;
            $data['mobile'] = $vendor->mobile;
            $data['name_of_contact_person'] = $vendor->name_of_contact_person;
            $data['designation_of_contact_person'] = $vendor->designation_of_contact_person;
            $data['description_of_company'] = $vendor->description_of_company;
            $data['items_for_service'] = $vendor->items_for_service;

            // echo"<pre>";
            // print_r(($data));exit;

            return view('vendor.edit_vendor')->with(['data'=>$data, 'link'=>'edit']);

        }elseif($action == 'approve'){

            $vendor = Vendor::where(['id'=>$vendor_id])->first();

            $vendor_id = $vendor->id;

            $saved_vendor = vendorapprovals::where('vendor_id', $vendor_id)
                                ->update(['vendor_status' => '1']);

            
            //$project->Approval->create(['approver_id'=>$user->id]);
            
            // $project_data = [                   
            //                     'status' => '2'                             
            //                 ];
            // $status_update = ProjectDraft::updateOrCreate(['id' => $project_id, 'status' => '1'], $project_data);
            // $project_drafts = ProjectDraft::where(['id'=>$project_id,'status'=>'2'])->first();
            
            
            return redirect("vendor/approval-vendors")->with('success', "Vendor has been approved."); 

        }elseif($action == 'reject'){
            

        
            $project_drafts = ProjectDraft::where(['id'=>$project_id,'status'=>'1'])->first();

            $project_draft_creator = $project_drafts->creator_id;

            $project_approval_values = unserialize($project_drafts->project_approval);
            

            if(isset($project_approval_values['send_back_reason'])  AND is_array($project_approval_values['send_back_reason'])){

                array_push($project_approval_values['send_back_reason'], $request->reason);

            }else{

                $project_approval_values['send_back_reason'] = array($request->reason);
            }  
            
            //$project->Approval->create(['approver_id'=>$user->id]);
            
            $project_data = [   
                                'project_approval' => serialize($project_approval_values),          
                                'status' => '0',
                                'sent_back' => 1                            
                            ];
            ProjectDraft::updateOrCreate(['id' => $project_id, 'status' => '1'], $project_data);


            ///////////////////notify/////////////////////////

            $notification_data = [
                             'sender_id' => $user->id,
                             'receiver_id' => $project_draft_creator,
                             'label' => 'Project sent back',
                             'read_status' => '0'
                         ]; 
            $notification_data['message'] = $project_approval_values['projectName']." has not been approved. It is sent back"; 
            
            pushNotification($notification_data['receiver_id'], $notification_data['label'], $notification_data['message']);

            $mail_data = array();
            $project_creator = Employee::where(['user_id'=>$project_draft_creator])
                            ->with('user')->first();
            $mail_data['to_email'] = $project_creator->user->email;
            $mail_data['subject'] = "Project not Approved";
            $mail_data['message'] = $project_approval_values['projectName']." has not been approved. It is sent back. Please correct it."; 
            $mail_data['fullname'] = $project_creator->fullname;
            //$this->sendGeneralMail($mail_data);

            return redirect("mastertables/approval-projects"); 

        }elseif($action == 'activate'){

            $project->isactive = 1;
            
            $project->save();

            //return redirect("mastertables/approval-projects");      

            return redirect()->back();

        }elseif($action == 'show'){
            
            $data['project_approval'] = Project::where(['id'=>$project_id, 'approval_status'=>'1'])
                                                ->first(); 
            $dep_id = $data['project_approval']->project_owner_dep_id;
            $state_id = $projects['project_approval']->project_location_state;
            $city_id = $projects['project_approval']->project_location_city;

            $data['project_approval']->state = State::where(['id'=>$state_id])->select('id','name')->get();
            $data['project_approval']->city = Location::where(['id'=>$city_id])->get();
            $data['project_approval']->department = Department::where(['id'=>$dep_id])->select('id','name')->first();
             
                                                
            $data['bg_data']= BgApproval::where(['project_id'=>$project_id])
                                                ->first(); 
            $data['it_data']= ItRequirement::where(['project_id'=>$project_id])                                               
                                                ->first(); 
            $data['ins_data']= insuranceApproval::where(['project_id'=>$project_id])
                                                ->first(); 
            $data['files']= DB::table('document_project')->where('project_id', $project_id)->get();                                   

            //return $data['it_data'];

            return view('mastertables.show_project_detail')->with(['projects'=>$data]);

        }elseif($action == 'show_projectDraft'){
            
            $data['locations'] = Location::where(['isactive'=>1])->get();
            
            $canapprove = auth()->user()->can('approve-project');

            if($canapprove==1){

                 $project_drafts = ProjectDraft::where(['id'=>$project_id, 'status'=>'1'])->first();

            }else{

                 $project_drafts = ProjectDraft::where(['id'=>$project_id, 'status'=>'0'])->first();

            }
            
            
            
            if($project_drafts){                
                $data['project_approval'] = unserialize($project_drafts->project_approval);

                $dep_id = $data['project_approval']['project_owner_department'];
                $data['project_approval']['departments'] = Department::where(['id'=>$dep_id])->select('id','name')->first();

                $project_location_state = $data['project_approval']['location_state'];
                $project_location_city = $data['project_approval']['location_city'];
                $data['project_approval']['states'] = State::whereIn('id', $project_location_state)->select('id','name')->get();
                $data['project_approval']['cities'] = Location::whereIn('id', $project_location_city)->select('id','name')->get();

                $data['bg_data'] = unserialize($project_drafts->bg_data);
                $data['it_data'] = unserialize($project_drafts->it_req_data);
                $data['ins_data'] = unserialize($project_drafts->insurance_data);


               /* $owner_name = $data['project_draft_values']['project_owner_name'];
                $data['project_draft_values'] = Emloyee::where(['isactive'=>1])->get();
                */
            }else{
                $data['project_draft_values'] = "";
            }            
//return $data;
            return view('mastertables.show_project_draft_detail')->with(['projects'=>$data]);

        }elseif($action == 'deactivate'){
            $project->isactive = 0;
            $project->save();

            return redirect()->back();
            
        }
    }//end of function    



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
