<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Social;
use Illuminate\Support\Facades\Validator;
use Exception;

class SocialController extends Controller
{
    public function  createSocialMedia(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'     => 'required',
                'logo_path'    => 'required',
                'link'    => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['code' => '302', 'error' => $validator->errors()]);
            }
            $user = auth('admin-api')->user();
            $social = new Social;
            $social->name = $request->name;
            $social->logo_path = $request->logo_path;
            $social->link = $request->link;
            $social->save();
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $social]);
        } catch (Exception $e){
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
    public function updateSocialMedia(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id'     => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['code' => '302', 'error' => $validator->errors()]);
            }
            $data = Social::where('id', $request->id)->first(); /* Check id exist in table */
            if (!$data) {
                return response()->json(['code' => '400', 'error' => "Invalid data"]);
            }
            if ($request->name){
                $data->name = $request->name;
            }
            if ($request->logo_path){
                $data->logo_path = $request->logo_path;
            }
            if ($request->link){
                $data->link = $request->link;
            }
            $data->save();
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
    public function deleteSocialMedia(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id'     => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['code' => '302', 'error' => $validator->errors()]);
            }
            $data = Social::where('id', $request->id)->firstorfail()->delete();
            return response()->json(['status' => 'Success', 'code' => 200, 'user' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
    public function socialMediaList(Request $request)
    {
        try {
            $data = Social::all();
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
   
}
