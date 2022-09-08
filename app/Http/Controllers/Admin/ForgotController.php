<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\ForgotPassword;
use App\Jobs\ForgotPasswordEmail;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Str;
use DB;

class ForgotController extends Controller
{
    public function forget_password(Request $request)
    {
        try {
            $user = Admin::where('email', $request->email)->get();
            if (count($user) > 0) {
                $token = Str::random(40);

                $url = env('FRONTEND_URL').'/reset_password?token='.$token;

                $name = $user[0]['name'];
                
                $data = [
                    'to' =>$request->email,
                    'url' => $url, 
                    'name' => $name, 
                    'data'=>"Thanks",
                    'subject' => "Regarding change Password"
                ];
                $datetime = Carbon::now()->format('Y-m-d H:i:s');
                ForgotPassword::updateOrCreate(
                    ['email' => $request->email],
                    [
                        'email' => $request->email,
                        'token' => $token,
                        'created_at' => $datetime
                    ]
                );
                dispatch(new ForgotPasswordEmail($data));



                return response()->json(['status' => 'success', 'code' => '200', 'msg' => 'Plaese check your mail to  reset your password']);
            } 
                return response()->json(['status' => 'error', 'code' => '400', 'data' => 'user not found']);
            
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'message' => $e->getmessage()]);
        }
    }
    public function reset_password(Request $request)
    {
        try {
            $reset = DB::table('forgot_passwords')->select('*')->where('token', $request->token)->first();
            $startTime= Carbon::parse($reset->created_at);
            $finishtime= Carbon::parse(Carbon::now()->format('Y-m-d H:i:s'));
            $durationtime=$startTime->diffInMinutes($finishtime);
           if( $durationtime >60){
            return response()->json(['status' => 'error', 'code' => '404', 'msg' => 'Token expire, Please try again']);
           }
            if (isset($request->token) && $reset != "") {
                $user = Admin::where('email', $reset->email)->first();
                return response()->json(['status' => 'success', 'code' => '200', 'msg' => $user]);
            } else {
                return response()->json(['status' => 'error', 'code' => '404', 'message' => "token not found"]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'message' => $e->getmessage()]);
        }
    }
    public function updateNewPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string',
                'cm_password'=>'required|same:password',
            ]);
            if ($validator->fails()) {
                return response()->json(['code' => '302', 'error' => $validator->errors()]);
            }
            $user = Admin::find($request->id);
          
            $user->password = Hash::make($request->password);
            $user->save();
             ForgotPassword::where('email', $user->email)->delete();
            return response()->json(['status' => 'success', 'code' => '200', 'msg' => "password updated"]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'message' => $e->getmessage()]);
        }
    }
}
