<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use  Validator;
use App\Models\User;

class UserController extends Controller
{
    
    public function create_employee(Request $request){
        $input = $request->all();

        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'password' => 'required',
        ];
        $customMessage = [
            '$name.required' =>'name is required',
        ];
        $validator = Validator::make($input, $rules, $customMessage);
        if($validator->fails()) {
        return response()->json($validator->errors(), 422);
        }   

      
            $new_user = new User();
            $new_user ->name = $input['name'];
            $new_user ->email = $input['email'];
            $new_user ->phone = $input['phone'];
            $new_user ->password = Hash::make($input['password']);
            $new_user ->type = 2;
            $new_user -> organization_id= auth()->user()['organization_id'];
            $new_user->save();

        return response()->json([
            'type'=>"Success",
            'message'=>"Create New Employee Successfully",
            'employee' => $new_user,
        ], 200);
    }

    public function get_all_employees(){
       // code
       $users = User::where('organization_id', auth()->user()['organization_id'])->get();
       
       return response()->json([
        'type'=>"Success",
        'message'=>"Get All Employees Successfully",
        'employee' => $users,
        ], 200); 
    }

    public function get_employee_by_id($id){

       $user = User::where('organization_id', auth()->user()['organization_id'])->where('id', $id)->get();

       return response()->json([
        'type'=>"Success",
        'message'=>"Get All Employees Successfully",
        'employee' => $user,
        ], 200); 


    }
}
