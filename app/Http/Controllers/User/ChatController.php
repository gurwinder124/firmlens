<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use Illuminate\Support\Facades\Validator;
use Exception;

class ChatController extends Controller
{
    public function  Chating(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'to_id'     => 'required',
                'message'    => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['code' => '302', 'error' => $validator->errors()]);
            }
            $loginuser = auth('api')->user();
            $chat = new Chat;
            $chat->from_id = $loginuser->id;
            $chat->to_id = $request->to_id;
            $chat->message = $request->message;
            $chat->save();
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $chat]);
        } catch (Exception $e){
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
    public function  showChating(Request $request)
    {
        try {
            $loginuser = auth('api')->user();
            $chating = Chat::select('from_id','to_id','message')->where('from_id','=',$loginuser->id)->orwhere('to_id','=',$loginuser->id)->orderBy('created_at', 'DESC')->get();
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $chating]);
        } catch (Exception $e){
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
   
}
