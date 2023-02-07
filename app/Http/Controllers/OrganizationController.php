<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use  Validator;
use Auth;

class OrganizationController extends Controller
{
    public function create_organization(Request $request){
        $input = $request->all();
        $admin_input = $input['administrator'];
        $organization_input = $input['organization'];
    
     
        $rules_admin_input = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'password' => 'required'
        ];
        

        $customMessage_admin_input = [
            'name.required' =>'Name is required',
            'phone.required' =>'Email is required',
            'email.required' =>'Email is required',
            'email.email'=> 'Email must be a valid email',
            'email.unique'=> 'Email must be a unique email',          
            'password.required' => 'Password is required'
        ];
        $validator = Validator::make($admin_input, $rules_admin_input, $customMessage_admin_input);
        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $rules_organization_input =[
            'name' => 'required|unique:organizations',
            'type' => 'required',
            'founder' => 'required',
        ];
        $customMessage_organization_input=[
            'name.required' =>'Name is required',
        ];

        

        $validator1 = Validator::make($organization_input,$rules_organization_input,$customMessage_organization_input);

        if($validator1->fails()) {
            return response()->json($validator1->errors(), 422);
        }

           

            $organization = new Organization();

            $organization-> name= $organization_input['name'];
            $organization-> type= $organization_input['type'];
            $organization-> founder= $organization_input['founder'];
           
     
            $organization->save();

            $user = new User();
            $user ->name = $admin_input['name'];
            $user ->email = $admin_input['email'];
            $user ->password =Hash::make($admin_input['password']);
            $user ->type = 1;
            $user ->phone = $admin_input['phone'];
            $user-> organization_id= $organization['id'];
            $user->save();

            return response()->json([
                'type'=>"Success",
                'message'=>"Create New User Successfully",
                 'data'=>[
                    'user'=>$user, 
                    'organization'=>$organization
                 ],
                ], 201);
    }

    public function get_organization(Request $request){

        $organization = Organization::get();

        return response()->json([
            'type'=>"Success",
            'message'=>"Get All Organization Successfully",
             'data'=>[
                'organization'=>$organization
             ],
            ], 201);
    }
}
