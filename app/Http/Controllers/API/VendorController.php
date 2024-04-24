<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\CreateVendorRequest;
use App\Http\Requests\Vendor\SignInRequest;
use App\Http\Requests\Vendor\VerfiyOtpCodeRequest;
use App\Http\Requests\Vendor\UploadInvoiceRequest;
use App\Models\Vendor;
use App\Models\VendorsInvoice;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Mail\ResetPassword;
use App\Mail\send_otp;
use Illuminate\Support\Facades\Validator;
use App\Mail\OTPSender;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
class VendorController extends Controller
{

    public function create_vendor_account(CreateVendorRequest $request)
    {
        try{
            $hashedPassword = Hash::make($request->validated()['password']);
            $validatedData = $request->validated();
            $validatedData['password'] = $hashedPassword;
            $client = Vendor::create($validatedData);
            return response()->json(['message' => 'account was created successfully', 'status' => 200]);
        }
        catch (QueryException $e) {
            return response()->json(['errors' => "Error insert data.", 'status' => 500]);
        } 

    }
    public function vendor_sign_in(SignInRequest $request)
    {
        $credentials = $request->validated();
        $vendor = Vendor::where('email', $credentials['email'])->first();
        if (!$vendor) {
            return response()->json(['message' => 'Invalid email or password', 'status' => 401]);
        }
        if (Hash::check($credentials['password'], $vendor->password)) {
            if($vendor->is_active<=0)
            {
                return response()->json([
                    'message' => 'Status Failed',
                    'data'    => 'account not yet active',
                    'status'  => 406,
                ]);

            }
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $token_random_str = Str::random(60);
            $token = $token_random_str."$".$vendor->id;
            $vendor->update([
                'otp' => $otp,
            ]);
            $subject ="OTP LOGIN CODE";
            $body ="Here is your OTP CODE. <br><br><b>$otp</b><br><br> Please copy this in the OTP Login Portal to make your login successful.";
            Mail::to($vendor->email)->send(new OTPSender($otp, $body, $subject));
            return response()->json([
                'message' => 'Verification',
                'token'   => $token,
                'status'  => 200,
            ]);
           
        } else {
            return response()->json(['message' => 'Invalid email or password', 'status' => 401]);
        }
    }

    public function vendor_verify_otp(VerfiyOtpCodeRequest $request)
    {
        $otp_details = $request->validated();
        $vendor_details =explode('$', $otp_details['token']);
        $vendor_id = $vendor_details[1];
        $vendor = Vendor::where('id', $vendor_id)
        ->where('OTP', $otp_details['otp'])
        ->first();
        if($vendor)
        {
            return response()->json(['success' => $vendor_details[0]."$".$vendor_id,'data'=>$vendor, 'status' =>200], 200);
        }
        else 
        {
            return response()->json(['errors' =>"invalid token", 'status' =>403], 200);
        }


    }
    public function reset_password_vendor(Request $request)
    {
        $vendor_email =$request->input('email');
        $pw = Str::random(8);
        $hashedPassword = Hash::make($pw);
      
        $vendor = Vendor::where('email', $vendor_email)->first();
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

    public function upload_invoice(UploadInvoiceRequest $request)
    {
        try{
            $s3Disk = 's3';
            $fileName = time() . '_' . $request->file('invoice_file')->getClientOriginalName();
            $fileUrl = Storage::disk($s3Disk)->putFileAs('uploads/vendors_invoices',  $request->file('invoice_file'), $fileName, 'public');
            $token = $request->bearerToken();
            $verification_Details = explode("$", $token);
            $vendor_id = $verification_Details[1];
            $validatedData = $request->validated();
            $validatedData['vendor_id'] = $vendor_id;
            $validatedData['invoice_file'] = Storage::disk($s3Disk)->url($fileUrl);
            $vendor = VendorsInvoice::create($validatedData);
            return response()->json(['message' => 'invoice was created successfully', 'status' => 200]);
        }
        catch (QueryException $e) {
            return response()->json(['errors' => "Error insert data.", 'status' => 500]);
        }
    }
    public function fetch_invoices_vendor(Request $request)
    {
        $token = $request->bearerToken();
        $verification_Details = explode("$", $token);
        $vendor_id = $verification_Details[1];
        $invoices = VendorsInvoice::where('vendor_id', $vendor_id)->get();
        if($invoices)
        {
            return response()->json(['data' => $invoices], 200);
        }
        else
            return response()->json(['error' => "no data found"], 401);

    }
}
