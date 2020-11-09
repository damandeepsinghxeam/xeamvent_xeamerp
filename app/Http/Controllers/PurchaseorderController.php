<?php

namespace App\Http\Controllers;

use App\PurchaseOrders;
use App\PurchaseOrderStockItems;
use App\PurchaseOrderCoordinators;
use App\Vendor;
use App\VendorApprovals;
use Illuminate\Http\Request;
use App\Department;
use App\Country;
use App\State;
use App\City;
use App\Employee;
use App\RequestedProductItems;
use App\EmployeeProfile;
use App\Http\Controllers\Controller;
use Mail;
use App\Mail\GeneralMail;
use App\Productitem;
use App\VendorCategory;
use App\StockItem;
use App\User;
use Response;
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
        //$data['productitems']  = Productitem::where(['isactive' => 1])->orderBy('name')->select('id', 'name')->get();
        $data['vendor_categories']         = VendorCategory::where(['isactive' => 1])->orderBy('name')->select('id', 'name')->get();
        $data['stock_items']         = Stockitem::where(['isactive' => 1])->orderBy('name')->select('id', 'name')->get();
        $data['departments'] = Department::where(['isactive'=>1])->orderBy('name')->select('id', 'name')->get();
        return view('purchaseorder.create_product_request')->with(['data' => $data]);
    }

    /*
        Ajax request to get deptt wise employees
    */
    function depttWiseEmployees(Request $request)
    {

        if($request->departmentIds != '') {
            $data = DB::table('employees as e')
                ->join('employee_profiles as ep', 'e.user_id', '=', 'ep.user_id')
                ->whereIn('ep.department_id', $request->departmentIds)
                ->where(['e.approval_status' => '1', 'e.isactive' => 1, 'ep.isactive' => 1])
                ->select('e.id', 'e.fullname')
                ->get();            
        }     
        return Response::json(['success'=>true,'data'=>$data]);
    }//end of function

    // get vendors by categories
    
    function getVendorsByCategory(Request $request)
    {
        $ids = $request->all();
        //return $idString = implode(',', $ids);
        // return VendorCategory::whereIn('id', $ids)
        //             ->with('vendors')
        //             ->get();
        // return Vendor::whereIn('category_id', $ids)
        // ->get();

        $Vendor =  DB::table('vendors as vend')
                    ->join('vendor_approvals as vap','vend.id','=','vap.vendor_id')
                    ->where('vap.vendor_status','1')
                    ->whereIn('vend.category_id', $ids)
                    ->select('vend.id','vend.name_of_firm','vap.vendor_status')
                    ->get();
        return $Vendor;
                    
    }//end of function

        // for save Product Form 

        public function saveProductRequest(Request $request)
        {   
    
          $data = $request->all();
         // dd($data);
    
            if (Auth::guest()) {
                return redirect('/');
            }
    
            $validator = Validator::make($request->all(), [
                'category'                  => 'required',
                'items'                     => 'required',
                'quantity'                  => 'required',
                'approx_price'              => 'required',
                'total_price'               => 'required',
                'coordinate_employees'      => 'required',
                'purpose'                   => 'required',
                'required_by'               => 'required'    
            ]);
    
            if ($validator->fails()) {
                return redirect("purchaseorder/product_request")
                    ->withErrors($validator, 'basic')
                    ->withInput($request->all());
            }

           $user      = User::where(['id' => Auth::id()])->with('employee')->first();
           $userId = User::permission('product-request-approval')->pluck('id');
           $supervisorUserId = $userId[0];
    
            $data_purchase_order = [
                'purpose'                   => strip_tags($request->purpose),
                'required_by'               => date($request->required_by),
                'created_by'                => $request->user_id,
                'supervisor_id'             => $supervisorUserId,
                'order_status'              => '0' //inprogress
            ];

            $saved_purchase_order = PurchaseOrders::create($data_purchase_order); 

            $count_category = count($request->category);
            $purchase_order_stock_item_data = [];
            for ($i = 0; $i < $count_category; $i++){
                $purchase_order_stock_item_data[] = [
                    'purchase_order_id'     => $saved_purchase_order->id, 
                    'stock_item_id'         => $request->items[$i],
                    'quantity'              => $request->quantity[$i],
                    'approx_price'          => $request->approx_price[$i],
                    'total_price'          => $request->total_price[$i]
                ];
            }         
            PurchaseOrderStockItems::insert($purchase_order_stock_item_data);

            $count_emp = count($request->coordinate_employees);
            $data_purchase_order_coordinator = [];
            for ($i = 0; $i < $count_emp; $i++){
                $data_purchase_order_coordinator[] = [
                'purchase_order_id'      => $saved_purchase_order->id,
                'coordinator_user_id'    => $request->coordinate_employees[$i]
                ];
            }
            PurchaseOrderCoordinators::insert($data_purchase_order_coordinator);

            $notification_data = [
                'sender_id'        => $request->user_id,
                'receiver_id'      => $supervisorUserId,
                'label'            => 'Item Request For Approval',
                'read_status'      => '0'
            ]; 
            $notification_data['message'] = "New Item Request is added and is Pending for Approval";  
      
            $saved_purchase_order->notifications()->create($notification_data);
    
            return redirect()->back()->with('success', "Product Request created successfully.");
        }

    // List Of Product Items for approval
       function listProductRequests(){   

        $user = Auth::user();

        $canapprove = auth()->user()->can('product-request-approval'); 

        $product_request_app =  DB::table('purchase_orders as po')
                        //->join('purchase_order_stock_items as prsi','po.id','=','prsi.purchase_order_id')
                        //->join('stock_items as si','prsi.stock_item_id','=','si.id')
                        ->join('employees as emp', 'po.created_by', '=', 'emp.user_id')
                        //->select('po.*','prsi.quantity','si.name','emp.fullname')
                        ->select('po.*','emp.fullname')
                        ->where('po.order_status','0')
                        ->get();

        if($product_request_app AND !$product_request_app->isEmpty() AND $canapprove==1){   
            foreach($product_request_app as $productRequestData){         
                $product_request_data_array['id'] = $productRequestData->id;
                //$product_request_data_array['name'] = $productRequestData->name;
                //$product_request_data_array['quantity'] = $productRequestData->quantity;
                $product_request_data_array['purpose'] = $productRequestData->purpose;
                $product_request_data_array['fullname'] = $productRequestData->fullname;
                $product_request_data_array['order_status'] = $productRequestData->order_status;
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
               $requested_Product_items = PurchaseOrders::find($product_request_id);
           }
            //  dd($requested_Product_items);
   
           $user = Auth::user();
   
        if($action == 'approve'){
   
               $requested_Product_items = PurchaseOrders::where(['id'=>$product_request_id])->first();
   
               $product_request_id = $requested_Product_items->id;
   
               $saved_requested_Product_items = PurchaseOrders::where('id', $product_request_id)
                                   ->update(['order_status' => '1']);
               
               
               return redirect("purchaseorder/approval-product-requests")->with('success', "Item has been approved."); 
   
           }elseif($action == 'reject'){
   
               $requested_Product_items = PurchaseOrders::where(['id'=>$product_request_id])->first();
   
               $product_request_id = $requested_Product_items->id;
   
               $saved_requested_Product_items = PurchaseOrders::where('id', $product_request_id)
                                   ->update(['product_requested_status' => '2']);
               
               return redirect("purchaseorder/approval-product-requests"); 
   
           }
           elseif($action == 'show_purchase_order_detail'){

            $purchase_order_approval =  DB::table('purchase_orders as po')
            ->join('purchase_order_stock_items as prsi','po.id','=','prsi.purchase_order_id')
            ->join('stock_items as si','prsi.stock_item_id','=','si.id')
            ->join('employees as emp', 'po.created_by', '=', 'emp.user_id')
            ->select('po.*','prsi.quantity','prsi.approx_price','prsi.total_price','si.name','emp.fullname')
            ->where('po.id', $product_request_id)
            ->get();

            $coordinate_employees =  DB::table('employees as e')
            ->join('purchase_order_coordinators as co','e.user_id','=','co.coordinator_user_id')
            ->select('e.fullname as coordinate_employee_name')
            ->where('co.purchase_order_id', $product_request_id)
            ->get();
            
            // return $data['purchase_order_approval'];
            // $data['vendor_approval'] = Vendor::where(['id'=>$vendor_id])
            //                                     ->first(); 
            
            // $cat_id = $data['vendor_approval']->category_id;
            // $category_name = DB::table('vendor_categories')->where('id', $cat_id)->value('name');

            // $items = [];
            // if(!empty($data['vendor_approval']->items_for_service)) {   
            //     $item_id = explode (",", $data['vendor_approval']->items_for_service);
            //     $items = StockItem::whereIn('id', $item_id)->pluck('name')->toArray();
            // }                     
            return view('purchaseorder/show_purchase_order_detail')->with(['purchase_order_data' => $purchase_order_approval, 'coordinate_employees' => $coordinate_employees]);
        }
       }//end of function  

        // List Of Product Request Items Status
        function ProductRequestsStatus(){   

            $user = Auth::user();
            $user_id = $user->id;
    
            $product_request_app =  DB::table('purchase_orders as po')
                        ->join('purchase_order_stock_items as prsi','po.id','=','prsi.purchase_order_id')
                        ->join('stock_items as si','prsi.stock_item_id','=','si.id')
                        ->select('po.*','prsi.quantity','si.name')
                        ->get();
    
            if($product_request_app AND !$product_request_app->isEmpty()){   
                foreach($product_request_app as $productRequestData){         
                    $product_request_data_array['id'] = $productRequestData->id;
                    $product_request_data_array['purpose'] = $productRequestData->purpose;
                    $product_request_data_array['name'] = $productRequestData->name;
                    $product_request_data_array['quantity'] = $productRequestData->quantity;
                    $product_request_data_array['order_status'] = $productRequestData->order_status;
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
