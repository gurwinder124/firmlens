<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use App\Jobs\CompanyStatusEmail;
use App\Models\User;
use Exception;

class AdminController extends Controller
{

    public function companyPendingList()
    {
        try {
            $user = auth('admin-api')->user();
            $req =  Company::REQUEST_STATUS_PENDING;
            $companylist = Company::where('request_status', '=', $req)->get();
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $companylist]);
        } catch (\Exception $e) {
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
            $user = auth('admin')->user();
            $id = $req->id;
            $getstatus = $req->status;
         
            $companystatus = Company::where('id',  $id)->update([
                'request_status' => $getstatus
            ]);
            $subject="Regarding Registration declined";
            if ($getstatus == 2) {
                $user = User::where('company_id',  $id)->update([
                    'is_active' => 1
                ]);
                $subject="Regarding Registration Accepted";
            }
           
            $this->sendacceptmail($id, $companystatus, $subject);
            return response()->json(['status' => 'Success', 'code' => 200, 'msg' => 'Company status updated ']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
    public function sendacceptmail($id,$status, $subject){
        $user = User::where('company_id',  $id)->first();
        $data=[
            'to'=>$user->email,
            'name'=>$user->name,
            'data'=>'Thanks',
            'status'=>$status,
            'subject' => $subject,
        ];
     dispatch(new CompanyStatusEmail($data))->afterResponse();

    }
    public function comapnyApprovedList()
    {
        try {
            $user = auth('admin-api')->user();
            $req =  Company::REQUEST_STATUS_APPROVED;
            $companylist = Company::where('request_status', '=', $req)->get();
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $companylist]);
        } catch (Exception $e) {
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
            })->orderBy('created_at', 'DESC')->paginate(10);
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $companylist]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }

    function getCompStatus()
    {
        return response()->json(['status' => 200, 'data' => Company::getCompStatus()]);
    }
}
