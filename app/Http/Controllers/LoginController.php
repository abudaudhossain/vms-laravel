<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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
            if (auth()->user()->type == 'admin') {
                return response()->json(['message'=>"This is Admin"]);
            }else if (auth()->user()->type == 'manager') {
                return response()->json(['message'=>"This is manager"]);
            }else{
                return response()->json(['message'=>"user"]);
            }
            

        }else{
             return response()->json(['message'=>"password or email invalided"]);
        }
           
    }
}
