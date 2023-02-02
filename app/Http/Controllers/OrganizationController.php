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
        
        
        $obj_merged = array_merge((array) $admin_input, (array) $organization_input);
     
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'password' => 'required',
            'company_name' => 'required',
            'type' => 'required',
            'founder' => 'required',
        ];
        

        $customMessage = [
            'name.required' =>'Name is required',
            'phone.required' =>'Email is required',
            'email.required' =>'Email is required',
            'email.email'=> 'Email must be a valid email',
            'email.unique'=> 'Email must be a unique email',
            'password.required' => 'Password is required'
        ];
        $validator = Validator::make($obj_merged, $rules, $customMessage);
        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $user = new User();
            $user ->name = $admin_input['name'];
            $user ->email = $admin_input['email'];
            $user ->password =Hash::make($admin_input['password']);
            $user ->type = 1;
            $user ->phone = $admin_input['phone'];
            $user->save();

            $organization = new Organization();

            $organization-> name= $organization_input['company_name'];
            $organization-> type= $organization_input['type'];
            $organization-> founder= $organization_input['founder'];
            $organization-> user_id= $user['id'];
     
            $organization->save();

            return response()->json([
                'type'=>"Success",
                'message'=>"Create New User Successfully",
                 'data'=>[
                    'user'=>$user, 
                    'organization'=>$organization
                 ],
                ], 201);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json($th, 422);
        }

       



        return response()->json(['message'=>"create organization"]);
    }
}
