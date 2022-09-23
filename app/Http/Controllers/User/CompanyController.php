<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;


class CompanyController extends Controller
{

    public  function employeeListById(Request $request)
    {
        try {
            $companylist = User::select('*')->where('company_id', $request->company_id)->where('is_root_user', '=', 0)->where('is_active', '=', 1)->get();
            //dd($companylist);
            if (!$companylist) {
                return response()->json(['status' => 'error', 'code' => '404', 'message' => 'users not found']);
            }
            return response()->json(['status' => 'success', 'code' => '200', 'data' => $companylist]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'message' => $e->getmessage()]);
        }
    }
    public  function  companyListByUser()
    {
        try {
            $loginuser = auth('api')->user();
            //dd( $loginuser);
            $companylist = Company::select('*')->where('id', '!=', $loginuser->company_id)->get();
            //dd($companylist);
            if (!$companylist) {
                return response()->json(['status' => 'error', 'code' => '404', 'message' => 'users not found']);
            }
            return response()->json(['status' => 'success', 'code' => '200', 'data' => $companylist]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'message' => $e->getmessage()]);
        }
    }

    public function userStats()
    {
        try {
            $loginuser = auth('api')->user();
            // dd( $loginuser);
            $data['emp_count'] = User::where('parent_id', '=', $loginuser->id)
                ->where('company_id', '=', $loginuser->company_id)
                ->count();
            $data['emp_review'] = User::join('reviews', 'reviews.user_id', '=', 'users.id')
                ->where('users.parent_id', '=', $loginuser->id)
                ->where('users.company_id', '=', $loginuser->company_id)
                ->count();
            if (!$data) {
                return response()->json(['status' => 'error', 'code' => '404', 'message' => 'Data not found']);
            }
            return response()->json(['status' => 'success', 'code' => '200', 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'message' => $e->getmessage()]);
        }
    }

    public function companyUpdate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'company_name' => 'required',
                'company_type' => 'required',
                'domain_name' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['code' => '302', 'error' => $validator->errors()]);
            }

            $loginuser = auth('api')->user();
            if ($request->id != $loginuser->company_id || $loginuser->is_root_user == 0) {
                return response()->json(['status' => 'Error', 'code' => 401, 'message' => 'unautherize']);
            }

            $company = Company::find($request->id);
            if (!$company) {
                return response()->json(['status' => 'Error', 'code' => 404, 'message' => 'Data not Found']);
            }
            $company->company_name = $request->company_name;
            $company->company_type = $request->company_type;
            $company->domain_name = $request->domain_name;

            $company_logo = [];
            if ($request->file('company_logo')) {
                $path = $request->file('company_logo')->store('company', 'public');
                $name = $request->file('company_logo')->getClientOriginalName();
                $company_logo = array("imagename" => $name, "path" => $path);
                $company->company_logo = $company_logo;
            }

            $company->save();

            return response()->json(['status' => 'Success', 'code' => 200, 'data' => "Updated Successfully"]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
}
