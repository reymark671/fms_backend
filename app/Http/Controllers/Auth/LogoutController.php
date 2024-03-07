<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\OTPSender;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
class LogoutController extends Controller
{
    //
    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }

    public function login_verify(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $user = User::where('email', $email)->first();
    
        if ($user && Hash::check($password, $user->password)) {
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $user->update([
                'otp' => $otp,
            ]);
                $subject ="OTP LOGIN CODE";
                $body ="Here is your OTP CODE. <br><br><b>$otp</b><br><br> Please copy this in the OTP Login Portal to make your login successful.";
                Mail::to($user->email)->send(new OTPSender($otp, $body, $subject));
                return response()->json(['success' => true, 'message' => 'OTP Sent']);
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }
    }
    public function otp_verify(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $otp_code = $request->input('otp');
        $user = User::where('email', $email)->first();
        if ($user && Hash::check($password, $user->password) && $user->otp == $otp_code) {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                return response()->json(['success' => true, 'message' => 'Login successful']);
            } else {
                return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
            }
    } else {
        return response()->json(['success' => false, 'message' => 'Invalid otp'], 401);
    }

    }
}
