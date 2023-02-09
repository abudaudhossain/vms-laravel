<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use  Validator;
use App\Models\Meeting;

class MeetingController extends Controller
{
    //

    public function meeting_request(Request $request){
       // code
        $input = $request->all();

        $rules = [
            'name' => 'required',
            'meetingWith' => 'required',
            'organization' => 'required',
           
        ];
        $customMessage = [
            '$name.required' =>'name is required',
        ];
        $validator = Validator::make($input, $rules, $customMessage);
        if($validator->fails()) {
        return response()->json($validator->errors(), 422);
        }

        // dd();
        $now_time = new \DateTime("NOW");

        $new_meeting = new Meeting();
        $new_meeting -> name= $input['name'];
        $new_meeting ->purpose = $input['purpose'];
        $new_meeting -> meetingWith= $input['meetingWith'];
        $new_meeting -> date = $now_time;
        $new_meeting -> status = 2;
        $new_meeting -> type = 0;
        $new_meeting -> organization_id= $input['organization'];
        $new_meeting -> createdBy= auth()->user()['id'];
        $new_meeting -> phone= auth()->user()['phone'];
        $new_meeting->save();

        return response()->json([
           'type'=>'Success',
           'message'=>'Create New Meeting Successfully',
           'employee' => $new_meeting
           ,
        ], 200); 
        
    }
}
