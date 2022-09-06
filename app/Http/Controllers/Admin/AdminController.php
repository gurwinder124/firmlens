<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
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
            $user = auth('admin-api')->user();
            $id = $req->id;
            $getstatus = $req->status;
            // dd($req);
            if ($getstatus == "approved") {
                $status = Company::REQUEST_STATUS_APPROVED;
                $user_status = User::IS_ACTIVE;
            } else {
                $status = Company::REQUEST_STATUS_REJECT;
                $user_status =User::NOT_ACTIVE;
            }
            $companystatus = Company::where('id',  $id)->update([
                'request_status' => $status
            ]);
            // $userstatus= User::
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $companystatus]);
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
            $companylist = Company::select("*")->when($status, function($query, $status){
                return $query->where('request_status', $status);
            })->get();
            //  dd($companylist);
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $companylist]);
        } catch (Exeception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }

    function getCompStatus(){
        return response()->json(['status' => 200,'data'=>Company::getCompStatus()]);
    }
}
