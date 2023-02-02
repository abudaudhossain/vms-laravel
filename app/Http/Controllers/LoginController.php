<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use  Validator;
   

class LoginController extends Controller
{
    //

    public function login(Request $request)
    {   
        $input = $request->all();
     
        $rules = [
            'email' => 'required|email|exists:users',
            'password' => 'required',
        ];

        $customMessage = [
            'email.required' =>'Email is required',
            'email.email'=> 'Email must be a valid email',
            'email.exists'=> 'Cannot found Email',
            'password.required' => 'Password is required'
        ];


        $validator = Validator::make($input, $rules, $customMessage);
        
        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
      
        if(auth()->attempt(array('email' => $input['email'], 'password' => $input['password'])))
        {
            $user = User::where('email', $input['email'])->first();
            $access_token = $user->createToken($input['email'])->accessToken;
          
            User::where('email', $input['email'])->update(['access_token' => $access_token]);
        
            
            return response()->json([
                'type'=>"Success",
                'message'=>"User Login Successfully",
                 'data'=>[
                    'access_token' => $access_token
                 ],
                ], 200);

        }else{
             return response()->json(['message'=>"password or email invalided"]);
        }
           
    }
}
