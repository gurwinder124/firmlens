<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;
use Hash;

class UserController extends Controller
{
    public function updateSubUser(Request $request)
    { // dd('test');
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'email'    => 'email',

        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '302', 'error' => $validator->errors()]);
        }
        try {
            $user = User::findorfail($request->id);
            if ($request->first_name) {
                $user->first_name = $request->first_name;
            }
            if ($request->last_name) {
                $user->last_name = $request->last_name;
            }
            if ($request->email) {
                $user->email = $request->email;
            }
            if ($request->designation) {
                $user->designation = $request->designation;
            }
            if ($request->official_email) {
                $user->official_email = $request->official_email;
            }
            $user->save();

            return response()->json(['status' => 'Success', 'code' => 200, 'msg' => 'user record updated ', 'user' => $user]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }

    public function deleteSubUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '302', 'error' => $validator->errors()]);
        }
        try {
            User::where('id', $request->id)
                ->update([
                    'is_active' => User::NOT_ACTIVE
                ]);
            return response()->json(['status' => 'Success', 'code' => 200, 'msg' => 'user deleted updated ']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
     public function userDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '302', 'error' => $validator->errors()]);
        }
        try {
            $data=User::findorfail($request->id);
               
            return response()->json(['status' => 'Success', 'code' => 200, 'data' =>$data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
}
