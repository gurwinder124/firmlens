<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blogs;
use Validator;
use Exception;

class BlogsController extends Controller
{
    public function  addBlogs(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title'     => 'required',
                'description'    => 'required',
                'author_name' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['code' => '302', 'error' => $validator->errors()]);
            }
            $blog = new Blogs;
            $blog->title = $request->title;
            $blog->description = $request->description;
            $blog->author_name = $request->author_name;
            $blog->save();
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $blog]);
        } catch (Exception $e){
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
    //protected route
    public function updateBlogs(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id'     => 'required',
             
            ]);
            if ($validator->fails()) {
                return response()->json(['code' => '302', 'error' => $validator->errors()]);
            }
            $data = Blogs::where('id', $request->id)->first(); /* Check id exist in table */
            if (!$data) {
                return response()->json(['code' => '400', 'error' => "Invalid data"]);
            }
            if ($request->title) {
                $data->title = $request->title;
            }
            if ($request->description) {
                $data->description = $request->description;
            }
            if ($request->author_name) {
                $data->description = $request->author_name;
            }
            $data->save();
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
    public function deleteBlogs(Request $request)
    {
        try {
           
                $validator = Validator::make($request->all(), [
                    'id'     => 'required',
                 
                ]);
                if ($validator->fails()) {
                    return response()->json(['code' => '302', 'error' => $validator->errors()]);
                }
            $data = Blogs::findorfail($request->id)->delete();
           
            return response()->json(['status' => 'Success', 'code' => 200, 'user' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
    public function blogsList()
    {
        try {
            $data = Blogs::all();
            if(is_null($data) ){
                return response()->json(['status' => 'error', 'code' => '404', 'meassage' => 'no data']);

            }
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
}
