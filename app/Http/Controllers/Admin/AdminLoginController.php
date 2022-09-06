<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Models\Admin;
use Auth;

class AdminLoginController extends Controller
{
    /**
     * User login API method
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    { //dd($request);
        try{
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email',
                'password' => 'required'
            ]);

            if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

            $credentials = $request->only('email', 'password');
            //    dd($credentials);
            if(\Auth::guard('admin')->attempt($credentials)) {
                $user= Auth::guard('admin')->user();
                // dd($user);
                $success['name']  = $user->name;
                $success['token'] = $user->createToken('accessToken',['admin'])->accessToken;

                return response()->json(['status'=>'success','code'=>'200','data'=>$success]);
            }
        }
        catch(\Exception $e){
          return response()->json(['status'=>'error','code'=>'401', 'msg'=>$e->getmessage()]);
        }
    }
   
    

    /**
     * User registration API method
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
            return response()->json(['status'=>'success','code'=>'200','data'=>$success]);

        } catch (Exception $e) {
            return response()->json(['status'=>'error','code'=>'401', 'msg'=>$e->getmessage()]);

        }

       
    }
}
