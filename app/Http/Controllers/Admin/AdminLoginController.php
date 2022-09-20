<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Models\Admin;
use App\Models\Designation;




class AdminLoginController extends Controller
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
            //    dd($credentials);
            if (\Auth::guard('admin')->attempt($credentials)) {
                $user = Auth::guard('admin')->user();
                // dd($user);
                $success['name']  = $user->name;
                $success['token'] = $user->createToken('accessToken', ['admin'])->accessToken;

                return response()->json(['status' => 'success', 'code' => '200', 'data' => $success]);
            } else {
                return response()->json(['status' => 'error', 'code' => '404', 'msg' => ' Invalid credential']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'code' => '401', 'msg' => $e->getmessage()]);
        }
    }




    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

        try {
            $user = Admin::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' =>  Hash::make($request->password),
            ]);
            $success['name']  = $user->name;
            $success['token'] = $user->createToken('accessToken', ['admin'])->accessToken;
            return response()->json(['status' => 'success', 'code' => '200', 'data' => $success]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '401', 'msg' => $e->getmessage()]);
        }
    }
    public function designationAdd(Request $request)
    {
        try {
            $user = auth('admin-api')->user();
            $validator = Validator::make($request->all(), [
                'name'    => 'required',
                'designation_slug' => 'required'
            ]);

            if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

            $designation = new Designation;
            $designation->name = $request->name;
            $designation->designation_slug = $request->designation_slug;
            $designation->save();

            return response()->json(['status' => 'success', 'code' => '200', 'data' => $designation]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '401', 'msg' => $e->getmessage()]);
        }
    }
    public function designationList()
    {
        try {
            $user = auth('admin-api')->user();
            $designationlist = Designation::select('id', 'name', 'designation_slug')->get();
            return response()->json(['status' => 'success', 'code' => '200', 'data' => $designationlist]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '401', 'msg' => $e->getmessage()]);
        }
    }
    public function adminLogout(Request $request)
    {
        try {
            if (Auth::guard('admin')->user()) // this means that the admin was logged in.
            {
                $user = Auth::user('admin-api')->token();
                $user->revoke();
            return response()->json(['status' => 'success', 'code' => '200', 'msg' =>'Logout successfully']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '401', 'msg' => $e->getmessage()]);
        }
    }
}
