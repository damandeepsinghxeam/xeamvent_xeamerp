<?php

namespace App\Http\Controllers;

use App\Purchaseorder;
use App\RequestedProductItems;
use App\Vendor;
use App\VendorApprovals;
use Illuminate\Http\Request;

use App\Country;
use App\State;
use App\City;
use App\Employee;
use App\EmployeeProfile;
use App\Http\Controllers\Controller;
use Mail;
use App\Mail\GeneralMail;
use App\Productitem;
use App\Vendoritem;
use App\User;
use Auth;
use DateTime;
use DB;
//use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Validator;
use View;
use App\Helper;

class PurchaseorderController extends Controller
{ 
    // Request Product Item Form

    public function create_product_request()
    {
        if (Auth::guest()) {
            return redirect('/');
        }

        $data['productitems']  = Productitem::where(['isactive' => 1])->orderBy('name')->select('id', 'name')->get();
        return view('purchaseorder.create_product_request')->with(['data' => $data]);
    }


        // for save Product Form 

        public function saveProductRequest(Request $request)
        {   
    
          $data = $request->all();
    
            if (Auth::guest()) {
                return redirect('/');
            }
    
            $validator = Validator::make($request->all(), [
                'product_name'                  => 'required',
                'no_of_items_requested'         => 'required',
                'product_description'           => 'required',
            ]);
    
            if ($validator->fails()) {
                return redirect("purchaseorder/product_request")
                    ->withErrors($validator, 'basic')
                    ->withInput($request->all());
            }

           $user      = User::where(['id' => Auth::id()])->with('employee')->first();

           // $userId = User::where('employee_code', '01')->first()->id;
  
           $userId = User::permission('product-request-approval')->pluck('id');

           $supervisorUserId = $userId[0];
    
            $data = [
                'user_id'                               => $request->user_id,
                'product_name'                          => $request->product_name,
                'others_product_name'                   => $request->others_product_name,
                'no_of_items_requested'                 => $request->no_of_items_requested,
                'product_description'                   => $request->product_description,
                'supervisor_id'                         => $supervisorUserId,
                'product_requested_status'                         => '0', //inprogress
            
            ];

            $saved_vendor = $user->RequestedProductItems()->create($data);   
    
            return redirect()->back()->with('success', "Product Request created successfully.");
        }

    // List Of Product Items for approval
       function listProductRequests(){   

        $user = Auth::user();

        $canapprove = auth()->user()->can('product-request-approval'); 

    //    $projectsdraft_sent = Vendor::where(['status'=>'1'])
    //                     ->orderBy('created_at','DESC')
    //                     ->get(); 

        $product_request_app =  DB::table('requested_product_items')
                        ->where('product_requested_status','0')
                        ->get();

        // dd($product_request_app);

        if($product_request_app AND !$product_request_app->isEmpty() AND $canapprove==1){   
            foreach($product_request_app as $productRequestData){         
                $product_request_data_array['id'] = $productRequestData->id;
                $product_request_data_array['product_name'] = $productRequestData->product_name;
                $product_request_data_array['others_product_name'] = $productRequestData->others_product_name;
                $product_request_data_array['no_of_items_requested'] = $productRequestData->no_of_items_requested;
                $product_request_data_array['product_description'] = $productRequestData->product_description;
                $product_request_data_array['product_requested_status'] = $productRequestData->product_requested_status;
                $product_request_data_array['supervisor_id'] = $productRequestData->supervisor_id;
                $data[] = $product_request_data_array;
                
            }
        }else{
            $data=[];
        }   
        // dd($data);
        
        return view('purchaseorder.list_product_requests')->with(['requested_product_items'=>$data, 'approval'=>'1']);

    }
    
       // For Product Request Actions

    function productRequestAction(Request $request, $action, $product_request_id = null)
       {
           if(!empty($product_request_id)){
               $requested_Product_items = RequestedProductItems::find($product_request_id);
           }
            // dd($requested_Product_items);
   
           $user = Auth::user();
   
        if($action == 'approve'){
   
               $requested_Product_items = RequestedProductItems::where(['id'=>$product_request_id])->first();
   
               $product_request_id = $requested_Product_items->id;
   
               $saved_requested_Product_items = RequestedProductItems::where('id', $product_request_id)
                                   ->update(['product_requested_status' => '1']);
               
               
               return redirect("purchaseorder/approval-product-requests")->with('success', "Item has been approved."); 
   
           }elseif($action == 'reject'){
   
               $requested_Product_items = RequestedProductItems::where(['id'=>$product_request_id])->first();
   
               $product_request_id = $requested_Product_items->id;
   
               $saved_requested_Product_items = RequestedProductItems::where('id', $product_request_id)
                                   ->update(['product_requested_status' => '2']);
               
               return redirect("purchaseorder/approval-product-requests"); 
   
           }
       }//end of function  

        // List Of Product Items for approval
        function ProductRequestsStatus(){   

            $user = Auth::user();
            $user_id = $user->id;
    
        //    $projectsdraft_sent = Vendor::where(['status'=>'1'])
        //                     ->orderBy('created_at','DESC')
        //                     ->get(); 
    
            $product_request_app =  DB::table('requested_product_items')
                            ->where('user_id', $user_id)
                            ->get();

            // dd($product_request_app);
    
            if($product_request_app AND !$product_request_app->isEmpty()){   
                foreach($product_request_app as $productRequestData){         
                    $product_request_data_array['id'] = $productRequestData->id;
                    $product_request_data_array['product_name'] = $productRequestData->product_name;
                    $product_request_data_array['others_product_name'] = $productRequestData->others_product_name;
                    $product_request_data_array['no_of_items_requested'] = $productRequestData->no_of_items_requested;
                    $product_request_data_array['product_description'] = $productRequestData->product_description;
                    $product_request_data_array['product_requested_status'] = $productRequestData->product_requested_status;
                    $product_request_data_array['supervisor_id'] = $productRequestData->supervisor_id;
                    $data[] = $product_request_data_array;
                    
                }
            }else{
                $data=[];
            }   
            // dd($data);
            
            return view('purchaseorder.list_product_requests_status')->with(['requested_product_items'=>$data, 'approval'=>'1']);
    
        }

      // Request a Vendor For Product Quotation Form  
        public function request_quote()
        {
            if (Auth::guest()) {
                return redirect('/');
            }

            $vendorDetail =  DB::table('vendors as vend')
            ->join('vendor_approvals as vap','vend.id','=','vap.vendor_id')
            ->where('vap.vendor_status','1')
            ->pluck('vend.name_of_firm', 'vend.id')
            ->toArray();

            $data['vendorDetail'] = $vendorDetail;
            return view('purchaseorder.request_quote', $data);
        }

        

        public function saveRequestQuote(Request $request)
        {
            if (Auth::guest()) {
                return redirect('/');
            }
            $user = Auth::user();
            if($request->isMethod('post')) {
            
                try {
                    \DB::beginTransaction();

                    $vendorIds = $vendorId = $request->name_of_firm;

                    $vendors = Vendor::whereIn('id', [$vendorIds])->get();
                    if(!empty($vendors) && count($vendors) > 0) {
                        foreach($vendors as $key => $vendor) {
                            // /***************Send Mail Code**************************/
                            $mail_data['from_email'] = $user->email;
                            $mail_data['to_email'] = $vendor->email;
                            $mail_data['subject'] = $request->product_request_title;
                            $mail_data['message'] = $request->product_request_description;
                            if(!$this->sendGeneralMail($mail_data)) {
                                throw new \Exception("Error occurs please try again.", 151);
                            }
                        }
                    } else {
                        throw new \Exception("Vendor Data not found.", 151);
                    }
                    \DB::commit();

                    $message = 'Email Sent Successfully.';
                    return redirect()->back()->withSuccess($message);
                } catch (\Exception $e) {
                    \DB::rollBack();
                   // dd($e->getMessage());
                    $message = 'Error code 500: internal server error.';
                    if($e->getCode() == 151) {
                        $message = $e->getMessage();
                    }
                    // $e->getMessage()
                    return redirect()->back()->withError($message)->withInput($request->all());
                }
            } else {
                $message = 'Error code 400: Invalid Request.';
                return redirect()->back()->withError($message)->withInput($request->all());
            }
        }

        function sendGeneralMail($mail_data)
        {   //mail_data Keys => to_email, subject, fullname, message
                if(!empty($mail_data['to_email'])){
                    Mail::to($mail_data['to_email'])->send(new GeneralMail($mail_data));
                }
                return true;
        }//end of function
        
}
