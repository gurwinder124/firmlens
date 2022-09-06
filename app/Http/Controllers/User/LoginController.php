<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;
use Hash;

class LoginController extends Controller
{


    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email',
                'password' => 'required'
            ]);

            if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user             = Auth::user();
                $success['name']  = $user->name;
                $success['token'] = $user->createToken('accessToken')->accessToken;

                return sendResponse($success, 'You are successfully logged in.');
            } else {
                return sendError('Unauthorised', ['error' => 'Unauthorised'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'code' => '401', 'msg' => 'You  are not authorised']);
        }
    }
    public function registerCompany(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'company_name' => 'required',
                'company_type' => 'required',
                'domain_name' => 'required',
                'email' => 'required| email',
                'password' => 'required',
                'designation' => 'required',
                'name' => 'required',
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
            $user->email = $request->email;
            $user->designation = $request->designation;
            $user->password = Hash::make($request->password);
            $user->company_id = $company->id;
            $user->is_root_user = 1;
            $user->is_active = User::NOT_ACTIVE;
            $user->parent_id = 0;
            $user->save();

            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $company, 'user' => $user]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }



    //protected route
    public function createSubUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
            'designation' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => '302', 'error' => $validator->errors()]);
        }
        $loginuser = auth('api')->user();
        try {
            $user =  new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->designation = $request->designation;
            $user->password = Hash::make($request->password);
            $user->company_id = $loginuser->id;
            $user->is_root_user = 0;
            $user->is_active = User::IS_ACTIVE;
            $user->parent_id = 1;
            $user->save();
            return response()->json(['status' => 'Success', 'code' => 200, 'user' => $user]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
}
