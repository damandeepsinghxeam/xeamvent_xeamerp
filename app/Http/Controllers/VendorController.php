<?php

namespace App\Http\Controllers;

use App\Vendor;
use App\VendorApprovals;
use Illuminate\Http\Request;

use App\Country;
use App\State;
use App\City;
use App\Employee;
use App\EmployeeProfile;
use App\Http\Controllers\Controller;
use App\Mail\GeneralMail;
use App\StockItem;
use App\VendorCategory;
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
    
     // get data for create vendor form

    public function create()
    {
        if (Auth::guest()) {
            return redirect('/');
        }

        $data['countries'] = Country::where(['isactive'=>1])->get();
        $data['states'] = State::where(['isactive'=>1])->get();    
        $data['cities']         = City::where(['isactive' => 1])->orderBy('name')->get();
        $data['vendor_categories']         = VendorCategory::where(['isactive' => 1])->orderBy('name')->select('id', 'name')->get();
        $data['stock_items']         = StockItem::where(['isactive' => 1])->orderBy('name')->select('id', 'name')->get();
       
        return view('vendor.create_vendor')->with(['data' => $data]);
    }

        /*
        Ajax request to get category wise vendor items
    */
        function categoryWiseServices(Request $request)
            {
                $category_Ids = $request->categoryIds;
                $stockitems = StockItem::where(['isactive'=>1])
                    ->whereIn('category_id',$category_Ids)
                    ->select('id','name')
                    ->get();
                return $stockitems;
            }//end of function

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // for save vendor form
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
            'category_id'                   => 'required', 
            'description_of_company'        => 'required',      
            'items_for_service'             => 'required',     
        ]);

        if ($validator->fails()) {
            return redirect("vendor/create")
                ->withErrors($validator, 'basic')
                ->withInput($request->all());
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
            'category_id'                    => $request->category_id,
            'items_for_service'              => implode(',', $request->items_for_service)
        
        ];

        $user = User::where(['id' => Auth::id()])->with('employee')->first();

        $saved_vendor = $user->vendor()->create($data);
         
        $userId = User::permission('vendor-approval')->first();
        $supervisorUserId = $userId->id;
    
       // $userId = User::where('employee_code', '01')->first()->id;
        $vendor_approval = [
            'user_id'       => $request->user_id,
            'vendor_id'     => $saved_vendor->id,
            'supervisor_id' => $supervisorUserId,
            'vendor_status' => '0', //inprogress
        ];

        $notification_data = [
            'sender_id' => $request->user_id,
            'receiver_id' => $supervisorUserId,
            'label' => 'Vendor For Approval',
            'read_status' => '0'
        ]; 
        $notification_data['message'] = "New Vendor is added and is Pending for Approval";        
        $saved_vendor_approval = $user->vendorapprovals()->create($vendor_approval);

        $saved_vendor->notifications()->create($notification_data);

        return redirect()->back()->with('success', "Vendor created successfully.");
    }

    // Display list for approved vendors
    public function listApprovedVendors(){       

        $vendor_app =  DB::table('vendors as vend')
                    ->join('vendor_approvals as vap','vend.id','=','vap.vendor_id')
                    ->where('vap.vendor_status','1')
                    ->select('vend.*','vap.vendor_status','vap.supervisor_id')
                    ->get();
    
            if($vendor_app AND !$vendor_app->isEmpty()){   
                foreach($vendor_app as $vendorData){         
                    $vendor_approval_data_array['id'] = $vendorData->id;
                    $vendor_approval_data_array['name_of_firm'] = $vendorData->name_of_firm;
                    $vendor_approval_data_array['type_of_firm'] = $vendorData->type_of_firm;
                    $vendor_approval_data_array['type_of_firm_others'] = $vendorData->type_of_firm_others;
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
        
            return view('vendor.list_approved_vendors')->with(['vendors'=>$data, 'approval'=>'1']);
    
    }

    // List Of Vendors for approval
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
                $vendor_approval_data_array['type_of_firm_others'] = $vendorData->type_of_firm_others;
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

    // For Save the edit Vendor data
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
            'category_id'                   => 'required',       
            'items_for_service'             => 'required',     
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'basic')
                ->withInput($request->all());
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
            'category_id'                    => $request->category_id,
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
                                'category_id'                    => $request->category_id,
                                'items_for_service'              => implode(',', $request->items_for_service)
                                    ]);
    
        // return redirect()->back()->with('success', "Vendor updated successfully.");
        return redirect("vendor/approval-vendors")->with('success', "Vendor updated successfully.");
    }

   // For Vendor Actions

    function vendorAction(Request $request, $action, $vendor_id = null)
    {
        if(!empty($vendor_id)){
            $vendor = Vendor::find($vendor_id);
        }
        // dd($vendor);

        $user = Auth::user();

        if($action == 'edit'){

            $data['countries'] = Country::where(['isactive'=>1])->get();
            $data['states'] = State::where(['isactive'=>1])->get();    
            $data['cities']  = City::where(['isactive' => 1])->orderBy('name')->get();
            $data['vendor_categories'] = VendorCategory::where(['isactive' => 1])->orderBy('name')->select('id', 'name')->get();
            $data['stockitems'] = StockItem::where(['isactive' => 1])->orderBy('name')->select('id', 'name')->get();

            $vendor = Vendor::where(['id'=>$vendor_id])->first();

            $data['action'] = $action;
            $data['vendor'] = $vendor;
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
            $data['category_id'] = $vendor->category_id;
            $data['items_for_service'] = $vendor->items_for_service;

            // echo"<pre>";
            // print_r(($data));exit;

            return view('vendor.edit_vendor')->with(['data'=>$data, 'link'=>'edit']);

        }elseif($action == 'approve'){

            $vendor = Vendor::where(['id'=>$vendor_id])->first();

            $vendor_id = $vendor->id;

            $saved_vendor = vendorapprovals::where('vendor_id', $vendor_id)
                                ->update(['vendor_status' => '1']);
            
            
            return redirect("vendor/approved-vendors")->with('success', "Vendor has been approved."); 

        }elseif($action == 'reject'){

            $vendor = Vendor::where(['id'=>$vendor_id])->first();

            $vendor_id = $vendor->id;

            $saved_vendor = vendorapprovals::where('vendor_id', $vendor_id)
                                ->update(['vendor_status' => '2']);
            
            return redirect("vendor/approval-vendors"); 

        }elseif($action == 'show_vendor_detail'){
            
            $data['vendor_approval'] = Vendor::where(['id'=>$vendor_id])
                                                ->first(); 
            
            $cat_id = $data['vendor_approval']->category_id;
            $category_name = DB::table('vendor_categories')->where('id', $cat_id)->value('name');

            $items = [];
            if(!empty($data['vendor_approval']->items_for_service)) {

                
                $item_id = explode (",", $data['vendor_approval']->items_for_service);
                $items = StockItem::whereIn('id', $item_id)->pluck('name')->toArray();
            }                     
            return view('vendor.show_vendor_detail')->with(['vendor_data'=>$data,'items'=>$items,'category_name'=>$category_name]);
        }
    }//end of function    

}
