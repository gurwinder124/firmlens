<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\Validator;
use Exception;


class QuestionController extends Controller
{
    public function  addQuestion(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'question'     => 'required',
                'description'    => 'required',
               
            ]);
            if ($validator->fails()) {
                return response()->json(['code' => '302', 'error' => $validator->errors()]);
            }
            $blog = new Question;
            $blog->question = $request->question;
            $blog->description = $request->description;
            $blog->save();
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $blog]);
        } catch (Exception $e){
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
    //protected route
    public function updateQuestions(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id'     => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['code' => '302', 'error' => $validator->errors()]);
            }
            $data = Question::where('id', $request->id)->first(); /* Check id exist in table */
            if (!$data) {
                return response()->json(['code' => '400', 'error' => "Invalid data"]);
            }
            if ($request->question) {
                $data->question = $request->question;
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
    public function deleteQuestion(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id'     => 'required',
             
            ]);
            if ($validator->fails()) {
                return response()->json(['code' => '302', 'error' => $validator->errors()]);
            }
            $data = Question::where('id', $request->id)->firstorfail()->delete();
            return response()->json(['status' => 'Success', 'code' => 200, 'user' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
    public function questionList(Request $request)
    {
        try {
            $data = Question::all();
            return response()->json(['status' => 'Success', 'code' => 200, 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'code' => '500', 'meassage' => $e->getmessage()]);
        }
    }
}
