<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Coordinator\CreateCoordinatorRequest;
use App\Http\Requests\Coordinator\SignInRequest;
use App\Http\Requests\Coordinator\VerfiyOtpCodeRequest;
use App\Models\Coordinator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Mail\ResetPassword;
use App\Mail\send_otp;
use Illuminate\Support\Facades\Validator;
use App\Mail\OTPSender;
use App\Models\Reports;
use App\Models\Payable;
use Illuminate\Support\Collection;
use App\Models\Payroll;
use App\Models\ClientFileUpload;
use App\Models\CoordinatorAssignment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class CoordinatorController extends Controller
{
    //

    public function create_coordinator_account(CreateCoordinatorRequest $request)
    {
        try{
            $hashedPassword = Hash::make($request->validated()['password']);
            $validatedData = $request->validated();
            $validatedData['password'] = $hashedPassword;
            $client = Coordinator::create($validatedData);
            return response()->json(['message' => 'account was created successfully', 'status' => 200]);
        }
        catch (QueryException $e) {
            return response()->json(['errors' => "Error insert data.", 'status' => 500]);
        } 
    }
    public function coordinator_sign_in(SignInRequest $request)
    {
        $credentials = $request->validated();
        $coordinator = Coordinator::where('email', $credentials['email'])->first();
        if (!$coordinator) {
            return response()->json(['message' => 'Invalid email or password', 'status' => 401]);
        }
        if (Hash::check($credentials['password'], $coordinator->password)) {
            if($coordinator->is_active<=0)
            {
                return response()->json([
                    'message' => 'Status Failed',
                    'data'    => 'account not yet active',
                    'status'  => 406,
                ]);

            }
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $token_random_str = Str::random(60);
            $token = $token_random_str."$".$coordinator->id;
            $coordinator->update([
                'otp' => $otp,
            ]);
            $subject ="OTP LOGIN CODE";
            $body ="Here is your OTP CODE. <br><br><b>$otp</b><br><br> Please copy this in the OTP Login Portal to make your login successful.";
            Mail::to($coordinator->email)->send(new OTPSender($otp, $body, $subject));
            return response()->json([
                'message' => 'Verification',
                'token'   => $token,
                'status'  => 200,
            ]);
           
        } else {
            return response()->json(['message' => 'Invalid email or password', 'status' => $coordinator]);
        }
    }

    public function coordinator_verify_otp(VerfiyOtpCodeRequest $request)
    {
        $otp_details = $request->validated();
        $coordinator_details =explode('$', $otp_details['token']);
        $coordinator_id = $coordinator_details[1];
        $coordinator = Coordinator::where('id', $coordinator_id)
        ->where('OTP', $otp_details['otp'])
        ->first();
        if($coordinator)
        {
            return response()->json(['success' => $coordinator_details[0]."$".$coordinator_id,'data'=>$coordinator, 'status' =>200], 200);
        }
        else 
        {
            return response()->json(['errors' =>"invalid token", 'status' =>403], 200);
        }

    }

    public function fetch_reports(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed'], 400);
        }

        $token_details = explode("$", $request->input('token'));
        $coordinator_id = $token_details[1];
        
        $coordinator = Coordinator::find($coordinator_id);
        if(!$coordinator)
        {
            return response()->json(['error' => 'Validation failed'], 400);
        }
        $fms_reports        = Reports::get();
        $assignedClients    = CoordinatorAssignment::where('coordinator_id', $coordinator_id)->pluck('client_id');
        $payables_report    = Payable::whereIn('id', $assignedClients)
                                 ->whereNotNull('response_file')
                                 ->with(['client' => function($query) {
                                    $query->select('id', 'first_name', 'last_name');
                                }])
                                 ->get();
        $payroll_report     = Payroll::whereIn('id', $assignedClients)
                                ->with(['client' => function($query) {
                                    $query->select('id', 'first_name', 'last_name');
                                }])
                                ->get();
        $client_file        = ClientFileUpload::whereIn('id', $assignedClients)
                                ->with(['client' => function($query) {
                                    $query->select('id', 'first_name', 'last_name');
                                }])->get();
        $mergedReports      = new Collection();

        foreach ($fms_reports as $fms_report) {
            $fms_report->report_type_data = "fms report";
            $mergedReports->push($fms_report);
        }

        foreach ($payables_report as $payable) {
            $payable->report_type_data = "payables report";
            $payable->report_file = $payable->response_file;
            $mergedReports->push($payable);
        }

        foreach ($payroll_report as $payroll) {
            $payroll->report_type_data = "payroll report";
            $payable->report_file = $payable->payroll_file;
            $mergedReports->push($payroll);
        }

        foreach ($client_file as $file) {
            $file->report_type_data = "client file";
            $mergedReports->push($file);
        }
        return response()->json(['data' => $mergedReports], 200);
    }
    public function reset_password_coordinator(Request $request)
    {
        $vendor_email =$request->input('email');
        $pw = Str::random(8);
        $hashedPassword = Hash::make($pw);
      
        $vendor = Coordinator::where('email', $vendor_email)->first();
        if($vendor)
        {
            $vendor->update([
                'password' => $hashedPassword,
            ]);

        }
        else 
        {
            return response()->json(['errors' =>"email not found", 'status' =>403], 200);
        }
        $url = env('VENDOR_URL');
        Mail::to($vendor->email)->send(new ResetPassword($pw, $url));
        return response()->json(['success' =>"your password has been sent to your email", 'status' =>200], 200);
    } 
    public function change_password_coordinator(Request $request)
    {
        $tokenDetails = explode("$",$request->input('token'));
        $newPassword = $request->input('password');
        if(strlen(trim($newPassword))<=7)
        {
            return response()->json(['error' =>"your password is not valid", 'status' =>403], 200);
        }
        $hashedPassword = Hash::make($newPassword);
        $token = $tokenDetails[0];
        $vendor_id =  $tokenDetails[1];
        $vendor = Coordinator::where('id', $vendor_id)->first();
        if($vendor)
        {
            $vendor->update([
                'password' => $hashedPassword,
            ]);
        }
        return response()->json(['success' =>"your password has been updated", 'status' =>200], 200);
    }
    
}
