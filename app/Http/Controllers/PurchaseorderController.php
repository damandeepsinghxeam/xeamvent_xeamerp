<?php

namespace App\Http\Controllers;

use App\Purchaseorder;
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
    public function create_product_request()
    {
        if (Auth::guest()) {
            return redirect('/');
        }

        $data['productitems']  = Productitem::where(['isactive' => 1])->orderBy('name')->select('id', 'name')->get();
        return view('purchaseorder.create_product_request')->with(['data' => $data]);
    }
}
