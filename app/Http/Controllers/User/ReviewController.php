<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Validator;
use Exception;


class ReviewController extends Controller
{
    public function  addReview(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id'     => 'required',
                'rating'    => 'required|between:1,5',
                'description'    => 'required',
               
            ]);
            if ($validator->fails()) {
                return response()->json(['code' => '302', 'error' => $validator->errors()]);
            }
            $review = new Review;
            $review->user_id = $request->user_id;
            $review->rating = $request->rating;
            $review->description = $request->description;
            $review->save();
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $review]);
        } catch (Exception $e){
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
    //protected route
    public function updateReview(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id'     => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['code' => '302', 'error' => $validator->errors()]);
            }
            $data = Review::where('id', $request->id)->first(); /* Check id exist in table */
           
            if ($request->rating) {
                $data->rating = $request->rating;
            }
            if ($request->description) {
                $data->description = $request->description;
            }
          
            $data->save();
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
    
    public function ReviewList(Request $request)
    {
        try {
            $data = Review::all();
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
}
