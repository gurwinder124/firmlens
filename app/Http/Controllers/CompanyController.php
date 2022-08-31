<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Hash;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function registerCompany(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'company_name' => 'required',
                'company_type' => 'required',
                'domain_name' =>'required',
                'email'=>'required| email',
                'password'=>'required',
                'designation'=>'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['code' => '302', 'error' => $validator->errors()]);
            }
            $company = new Company;
            $company->company_name = $request->company_name;
            $company->company_type = $request->company_type;
            $company->domain_name = $request->domain_name;
            $company->request_status = Company::REQUEST_STATUS_PENDING;
            $company->save();


            $user = new User;
            $user->name = $request->name;
            $user->email=$request->email;
            $user->designation=$request->designation;
            $user->password = Hash::make($request->password);
            $user->company_id = $company->id;
            $user->is_root_user = 1;
            $user->parent_id = 0;
            $user->save();

            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $company,'user'=>$user]);
        }
        catch(Exception $e){
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }

  
    
}
