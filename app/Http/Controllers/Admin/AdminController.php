<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use Exception;

class AdminController extends Controller
{
   
    public function companyPendingList(){
        //dd("test");
        try{
            $user = auth('admin-api')->user();
            //dd($user);
            $req =  Company::REQUEST_STATUS_PENDING;
            //dd($req);
            $companylist =Company::where('request_status','=', $req)->get();
            //dd($companylist->toarray());
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $companylist]);
        }
        catch( \Exeception $e){
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);

        }
        
    }
    public function updateCompanyStatus(Request $req){
        
        try{
            $user = auth('admin-api')->user();
            $id=$req->id;
            $getstatus=$req->status;
           // dd($req);
            if($getstatus == "approved")
            {
            $status = Company::REQUEST_STATUS_APPROVED;
            }else{
                $status = Company::REQUEST_STATUS_REJECT;
            }
            $companystatus =Company::where('id',  $id)->update([
                'request_status'=> $status
            ]);
            //dd($companystatus);
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $companystatus]);
        }
        catch(Exeception $e){
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);

        }
    }
    public function comapnyApprovedList(){
        //dd("test");
        try{
            $user = auth('admin-api')->user();
            $req =  Company::REQUEST_STATUS_APPROVED;
            //dd($req);
            $companylist =Company::where('request_status','=', $req)->get();
            //dd($companylist->toarray());
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $companylist]);
        }
        catch(Exeception $e){
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);

        }
    }
}

