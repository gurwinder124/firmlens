<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendNewRegisterEmail;
use App\Jobs\SendWelcomeEmail;
use App\Models\Admin;
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
                $user= Auth::user();
                if ($user->is_active == USER::IS_ACTIVE) {
                    $success['user']  = $user;
                    $success['token'] = $user->createToken('accessToken')->accessToken;
                    return sendResponse($success, 'You are successfully logged in.');
                }
                else{
                    return response()->json(['status' => 'error', 'code' => '401', 'msg' => 'You  are not approved by admin']);
                }
               
            }else {
                return sendError('Unauthorised', ['error' => 'invalid credentials'], 401);
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
                'first_name' => 'required',
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
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->designation = $request->designation;
            $user->password = Hash::make($request->password);
            $user->company_id = $company->id;
            $user->is_root_user = 1;
            $user->is_active = User::NOT_ACTIVE;
            $user->parent_id = 0;
            $user->save();
            $admin=Admin::select('name','email')->where('id','=',1)->first();
            $email=$admin->email;
            $name=$admin->name;
            $data = [
                'to' =>  $email,
                'name' => $name,
                'company_name' =>$request->company_name,
                'data' => "Thanks ",
                'subject' => "Regarding Register new User"
            ];
            $welcomedata=[
                'to'=>$request->email,
                'name'=>$request->first_name,
                'data' => "Thanks ",
                'subject' => "Regarding Welcome"
            ];
            dispatch(new SendNewRegisterEmail($data))->afterResponse();
            dispatch(new SendWelcomeEmail($welcomedata))->afterResponse();
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $company, 'user' => $user]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }



    //protected route
    public function createSubUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
            'designation' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => '302', 'error' => $validator->errors()]);
        }
        $loginuser = auth('api')->user();
        //dd($loginuser);
        try {
            $user =  new User;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->designation = $request->designation;
            $user->password = Hash::make($request->password);
            $user->company_id = $loginuser->company_id;
            $user->is_root_user = 0;
            $user->is_active = User::IS_ACTIVE;
            $user->parent_id = $loginuser->id;
            $user->save();

            return response()->json(['status' => 'Success', 'code' => 200, 'user' => $user]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
    public function userLogout(Request $request)
    {
        try {
            if (Auth::guard('api')->user()) {
                $user = Auth::user('api')->token();
                $user->revoke();
                return response()->json(['status' => 'success', 'code' => '200', 'msg' => 'Logout successfully']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '401', 'msg' => $e->getmessage()]);
        }
    }
}
