<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

use Exception;

class AdminController extends Controller
{

    public function companyPendingList()
    {
        //dd("test");
        try {
            $user = auth('admin-api')->user();
            //dd($user);
            $req =  Company::REQUEST_STATUS_PENDING;
            //dd($req);
            $companylist = Company::where('request_status', '=', $req)->get();
            //dd($companylist->toarray());
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $companylist]);
        } catch (\Exeception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
    public function updateCompanyStatus(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'id' => 'required',
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['code' => '302', 'error' => $validator->errors()]);
            }
            $user = auth('admin-api')->user();
            $id = $req->id;
            $getstatus = $req->status;
            if ($getstatus == 2) {
                $user = User::where('company_id',  $id)->update([
                    'is_active' => 1

                ]);
                //dd($user);
            }
            $companystatus = Company::where('id',  $id)->update([
                'request_status' => $getstatus
            ]);
            return response()->json(['status' => 'Success', 'code' => 200, 'msg' => 'Company status updated ']);
        } catch (Exeception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
    public function comapnyApprovedList()
    {
        //dd("test");
        try {
            $user = auth('admin-api')->user();
            $req =  Company::REQUEST_STATUS_APPROVED;
            //dd($req);
            $companylist = Company::where('request_status', '=', $req)->get();
            //dd($companylist->toarray());
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $companylist]);
        } catch (Exeception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
    public function companyList(Request $request)
    {
        try {
            $user = auth('admin-api')->user();
            $status = $request->status;
            $companylist = Company::select("*")->when($status, function ($query, $status) {
                return $query->where('request_status', $status);
            })->get();
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $companylist]);
        } catch (Exeception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }

    function getCompStatus()
    {
        return response()->json(['status' => 200, 'data' => Company::getCompStatus()]);
    }
}
