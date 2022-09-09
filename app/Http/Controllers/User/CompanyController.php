<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Exception;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use Validator;
use Hash;


class CompanyController extends Controller
{
 
    public  function employeeListById(Request $request){
        try{
            $companylist=User::select('*')->where('company_id',$request->company_id)->where('is_root_user','=',0)->paginate(10);
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
