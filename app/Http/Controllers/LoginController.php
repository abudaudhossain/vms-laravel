<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\Visitor;
use App\Models\OtpInfo;
use  Validator;

use DateTime;
use DateInterval;
   

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

    public function getOTP(Request $request){
        $input = $request->all();
     
        $rules = [
            'phone' => 'required',   
        ];

        $customMessage = [
            'phone.required' =>'Phone is required'
        ];

        $validator = Validator::make($input, $rules, $customMessage);
        
        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $otp_info = OtpInfo::where('phone', $input['phone'])->first();

        $minutes_to_add = 2;
        $time = new \DateTime('NOW');
        $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
        $otp = $this->OTP();
        //   $time = $time->format("d-m-y h:i:s");

        if($otp_info){
            // $now_time = new \DateTime("NOW");
            $date = date('Y-m-d h:i:s');
           if($date < $otp_info['OTPExpireAt']){
            return response()->json([
                'type'=>"Error",
                'message'=>"Recently sended otp . Please wait some time for resend otp",
                 'data'=>[],
                ], 401);
           }else{
           
            // dd($otp);

            OtpInfo::where('phone', $input['phone'])->update(['otp' => $otp, 'otp_validation'=>false,'OTPExpireAt'=>$time]);

            return response()->json([
                'type'=>"Success",
                'message'=>"Send OTP Successfully",
                 'data'=>[
                    'phone'=> $input['phone']
                 ],
                ], 201);
            
           }
           
        }else{
            $new_otp = new OtpInfo();
            $new_otp ->phone = $input['phone'];
            $new_otp ->otp = $otp;
            $new_otp ->otp_validation = false;
            $new_otp ->OTPExpireAt = $time;
           
            $new_otp->save();
        
        return response()->json([
            'type'=>"Success",
            'message'=>"Send OTP Successfully",
             'data'=>[
                'phone'=> $new_otp['phone']
             ],
            ], 201);
        }
    }

    public function OTPValidation(Request $request)
    {   
        $input = $request->all();
     
        $rules = [
            'phone' => 'required|exists:otp_infos',
            'otp' => 'required',
        ];
        
        $customMessage = [
            'phone.required' =>'Phone is required',
            'phone.exists'=> 'Cannot found Phone number',
            'otp.required' => 'OTP is required'
        ];


        $validator = Validator::make($input, $rules, $customMessage);
        
        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
      
        // if(auth()->attempt(array('phone' => $input['phone'], 'otp' => $input['otp'])))
        // {
            $otp_info = OtpInfo::where('phone', $input['phone'])->first();

           
                // $now_time = new \DateTime("NOW");
            $date = date('Y-m-d h:i:s');
            if($date > $otp_info['OTPExpireAt']){
                return response()->json([
                    'type'=>"Error",
                    'message'=>"Your OTP is Expired",
                     'data'=>[],
                    ], 401);
            }
            
            if($otp_info['otp'] ==  $input['otp']){

                // opt validation update
                OtpInfo::where('phone', $input['phone'])->update(['otp_validation' =>true]);
                // check visitor exits or not
                $visitor = Visitor::where('phone', $input['phone'])->first();
                if($visitor){                  
           
                    // update visitor access token
                    $access_token = $visitor->createToken($input['phone'])->accessToken;    
                    Visitor::where('phone', $input['phone'])->update(['access_token' => $access_token]);

                     return response()->json([
                        'type'=>"Success",
                        'message'=>"Create New Visitor Successfully",
                        'data'=>[
                            'access_token' => $access_token
                        ],
                    ], 200);

                }else{
                    //  create new visitor and add access token
                    $new_visitor = new Visitor();                  
                    $new_visitor ->phone = $input['phone'];
                    $new_visitor ->logAt = $date;
                    $new_visitor ->save();
                    $access_token = $new_visitor->createToken($input['phone'])->accessToken;    
                    Visitor::where('phone', $input['phone'])->update(['access_token' => $access_token]);

                     return response()->json([
                        'type'=>"Success",
                        'message'=>"Create New Visitor Successfully",
                        'data'=>[
                            'access_token' => $access_token
                        ],
                    ], 200);

                }
            }

            // dd($otpInfo['otp'],  $input['otp'], $access_token);  
            //     $access_token = $otpInfo->createToken($input['phone'])->accessToken;    
            //     OtpInfo::where('phone', $input['phone'])->update(['access_token' => $access_token]);
            
            //     return response()->json([
            //         'type'=>"Success",
            //         'message'=>"User Login Successfully",
            //         'data'=>[
            //             'access_token' => $access_token
            //         ],
            //     ], 200);
            // }

            // dd($otpInfo['otp'],  $input['otp']);
            

        // }else{
        //      return response()->json(['message'=>"password or email invalided"]);
        // }

        return "otp wrong auth";
           
    }


    protected function OTP(){
        return  random_int(10000, 99999);
    }
}
