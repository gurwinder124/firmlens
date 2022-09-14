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
 
    public  function employeeListById(Request $request){
        try{
            $companylist=User::select('*')->where('company_id',$request->company_id)->where('is_root_user','=',0)->where('is_active','=',1)->get();
            //dd($companylist);
            if(!$companylist){
                return response()->json(['status'=>'error','code'=>'404','message'=>'users not found']);
            }
            return response()->json(['status'=>'success','code'=>'200','data'=>$companylist]);
        }
        catch(Exception $e){
            return response()->json(['status'=>'error','code'=>'500','message'=>$e->getmessage()]);
        }
    }
    public  function  companyListByUser(){
        try{
            $loginuser = auth('api')->user();
            //dd( $loginuser);
            $companylist=Company::select('*')->where('id','!=',$loginuser->company_id)->get();
            //dd($companylist);
            if(!$companylist){
                return response()->json(['status'=>'error','code'=>'404','message'=>'users not found']);
            }
            return response()->json(['status'=>'success','code'=>'200','data'=>$companylist]);
        }
        catch(Exception $e){
            return response()->json(['status'=>'error','code'=>'500','message'=>$e->getmessage()]);

        }
    }
}
