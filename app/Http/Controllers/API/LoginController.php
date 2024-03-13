<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\send_otp;
use App\Mail\OTPSender;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
            Mail::to($client->email)->send(new OTPSender($otp, $body, $subject));
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

    public function otp_verification_emp(Request $request)
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
        $client = Employee::where('id', $client_id)
        ->where('OTP', $otp)
        ->where('token', $token)
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
    public function reset_password_employee(Request $request)
    {
        $employee_email =$request->input('email');
        $pw = Str::random(8);
        $hashedPassword = Hash::make($pw);
      
        $employee = Employee::where('email', $employee_email)->first();
        if($employee)
        {
            $employee->update([
                'pw' => $pw,
                'password' => $hashedPassword,
            ]);

        }
        else 
        {
            return response()->json(['errors' =>"email not found", 'status' =>403], 200);
        }
        $url = env('EMPLOYEE_URL');
        Mail::to($employee->email)->send(new ResetPassword($pw, $url));
        return response()->json(['success' =>"your password has been sent to your email", 'status' =>200], 200);
    } 
    public function reset_password_client(Request $request)
    {
        $employee_email =$request->input('email');
        $pw = Str::random(8);
        $hashedPassword = Hash::make($pw);
        $employee = Client::where('email', $employee_email)->first();
        if($employee)
        {
            $employee->update([
                'pw' => $pw,
                'password' => $hashedPassword,
            ]);

        }
        else 
        {
            return response()->json(['errors' =>"email not found", 'status' =>403], 200);
        }
        $url = env('CLIENT_URL');
        Mail::to($employee->email)->send(new ResetPassword($pw,$url));
        return response()->json(['success' =>"your password has been sent to your email", 'status' =>200], 200);
    }   
    public function change_password_client(Request $request)
    {
        $tokenDetails = explode("$",$request->input('token'));
        $newPassword = $request->input('password');
        if(strlen(trim($newPassword))<=7)
        {
            return response()->json(['error' =>"your password is not valid", 'status' =>403], 200);
        }
        $hashedPassword = Hash::make($newPassword);
        $token = $tokenDetails[0];
        $client_id =  $tokenDetails[1];
        $client = Client::where('id', $client_id)->first();
        if($client)
        {
            $client->update([
                'password' => $hashedPassword,
            ]);
        }
        return response()->json(['success' =>"your password has been updated", 'status' =>200], 200);

    }  
    public function change_password_employee(Request $request)
    {
        $tokenDetails = explode("$",$request->input('token'));
        $newPassword = $request->input('password');
        if(strlen(trim($newPassword))<=7)
        {
            return response()->json(['error' =>"your password is not valid", 'status' =>403], 200);
        }
        $hashedPassword = Hash::make($newPassword);
        $token = $tokenDetails[0];
        $employee_id =  $tokenDetails[1];
        $employee = Employee::where('id', $employee_id)->first();
        if($employee)
        {
            $employee->update([
                'password' => $hashedPassword,
            ]);
        }
        return response()->json(['success' =>"your password has been updated", 'status' =>200], 200);

    }
    public function test_image_s3(Request $request)
    {
        try {
            $image = $request->file('file');
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $path = 'test_image/' . $filename;
            $uploadedPath = Storage::disk('s3')->put($path, file_get_contents($image), 'public');
            $publicUrl = Storage::disk('s3')->url($path);
    
            return $publicUrl;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
