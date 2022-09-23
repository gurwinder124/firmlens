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
            $user = auth('admin-api')->user();
            //dd( $user->email);
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
    public function sendacceptmail($id,$status,$subject){
        $user = User::where('company_id',  $id)->first();
        $data=[
            'to'=>$user->email,
            'name'=>$user->first_name,
            'data'=>'Thanks',
            'status'=>$status,
            'subject' => $subject,
        ];
     dispatch(new CompanyStatusEmail($data))->afterResponse();
    }
    public function comapnyCountList()
    {
        try {
            $user = auth('admin-api')->user();
            //dd($user);
            $data = [];
            $data['totalcomapny_list']= Company::count();
            $req = Company::REQUEST_STATUS_APPROVED;
            $data['totalapproved'] = Company::where('request_status', '=', $req)->count();
            $req = Company::REQUEST_STATUS_PENDING;
            $data['totalpending'] = Company::where('request_status', '=', $req)->count();
            $req = Company::REQUEST_STATUS_REJECT;
            $data['totalrejected'] = Company::where('request_status', '=', $req)->count();
            
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
    public function companyList(Request $request)
    {
        try {
            $user = auth('admin-api')->user();
            $status = $request->status;
            $data=[];
            $data['company_list'] = Company::select("*")->when($status, function ($query, $status) {
                return $query->where('request_status', $status);
            })->orderBy('created_at', 'DESC')->get();
           $data['company_count']=  $data['company_list']->count();
           
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
    public function companyDetails($id)
    {
        try {
            $data = Company::where('id', $id)->get();

            if(!$data){
                return response()->json(['status' => 'error', 'code' => 404, 'message' => "Data Not Found"]);
            }
           
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
    function getCompStatus()
    {
        return response()->json(['status' => 200, 'data' => Company::getCompStatus()]);
    }
}
