<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\send_otp;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class LoginController extends Controller
{
    //
    public function login(Request $request)
    {
       
        $credentials = $request->only('email', 'password');
        $client = Client::where('email', $credentials['email'])->first();
        if (!$client) {
            return response()->json(['message' => 'Invalid email or password', 'status' => 401]);
        }

        if (Hash::check($credentials['password'], $client->password)) {
            if($client->status<=0)
            {
                return response()->json([
                    'message' => 'Status Failed',
                    'data'    => 'account not yet active',
                    'status'  => 406,
                ]);

            }
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $token_random_str = Str::random(60);
            $token = $token_random_str."$".$client->id;
            $client->update([
                'api_token' => $token_random_str,
                'otp' => $otp,
            ]);
            $subject ="OTP LOGIN CODE";
            $body ="Here is your OTP CODE. <br><br><b>$otp</b><br><br> Please copy this in the OTP Login Portal to make your login successful.";
            Mail::to($client->email)->send(new send_otp($otp, $body, $subject));
            return response()->json([
                'message' => 'Verification',
                'token'   => $token,
                'status'  => 200,
            ]);
           
        } else {
            return response()->json(['message' => 'Invalid email or password', 'status' => 401]);
        }
    }
    public function otp_verification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|string',
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $tokenDetails = explode("$",$request->input('token'));
        $token = $tokenDetails[0];
        $client_id =  $tokenDetails[1];
        $otp = $request->input('otp');
        $client = Client::where('id', $client_id)
        ->where('otp', $otp)
        ->where('api_token', $token)
        ->first();
        if($client)
        {
            return response()->json(['success' => $token."$".$client_id, 'status' =>200], 200);
        }
        else 
        {
            return response()->json(['errors' =>"invalid token", 'status' =>403], 200);
        }
       


    }
}
