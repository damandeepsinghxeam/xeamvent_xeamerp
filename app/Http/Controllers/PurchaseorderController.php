<?php

namespace App\Http\Controllers;

use App\Purchaseorder;
use App\RequestedProductItems;
use Illuminate\Http\Request;

use App\Country;
use App\State;
use App\City;
use App\Employee;
use App\EmployeeProfile;
use App\Http\Controllers\Controller;
use App\Mail\GeneralMail;
use App\Productitem;
use App\User;
use Auth;
use DateTime;
use DB;
use Illuminate\Support\Facades\Mail;
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
    
           
        
           // $userId = User::where('employee_code', '01')->first()->id;
            // $vendor_approval = [
            //     'user_id'       => $request->user_id,
            //     'vendor_id'        => $saved_vendor->id,
            //     'supervisor_id' => $supervisorUserId,
            //     'vendor_status'    => '0', //inprogress
            // ];
    
            //dd($vendor_approval);
            
            // $saved_vendor = $user->vendorapprovals()->create($vendor_approval);
    
            return redirect()->back()->with('success', "Product Request created successfully.");
        }
}
