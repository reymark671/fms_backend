<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\EmployeeSendMail;
use App\Mail\ClientSendMail;
use App\Models\Employee;
use App\Models\MailSender;
use App\Models\Client;
use App\Models\Vendor;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
class EmailSender extends Controller
{
    //
    public function send_email_emp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject'    => 'required|string',
            'message'    => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $token = $request->bearerToken();
        $verification_Details = explode("$", $token);
        $employee_id = $verification_Details[1];
        $token = $verification_Details[0];
        $employee = Employee::with('client')->find($employee_id);
        if (!$employee) {
            return response()->json(['error' => 'Record not found'], 404);
        }
        $employee_name = $employee->first_name." ".$employee->last_name;
        $employee_email = $employee->email;
        $client_email = $employee->client->email;
        $employee = MailSender::create([
            'subject'   => $request->input('subject'),
            'message'   => $request->input('message'),
            'recipient' => $client_email,
            'sender'    => $employee_email,
        ]);
     
       try
       {
        $send_mail = new EmployeeSendMail($client_email, $request->input('subject'), $request->input('message'),$employee_name);
        Mail::to($client_email)->send($send_mail);
       }
       catch(Exception $e)
       {
        return response()->json(['message' => $e->getMessage()]);
       }
        return response()->json(['message' => 'Message has been sent', 'status'=> 200], 200);

    }
    public function send_email_cli(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject'    => 'required|string',
            'message'    => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $token = $request->bearerToken();
        $verification_Details = explode("$", $token);
        $client_id = $verification_Details[1];
        $token = $verification_Details[0];
        $client = Client::find($client_id);
        $recipient_email = env('MAIL_SUPPORT');
        $client_mail = MailSender::create([
            'subject'   => $request->input('subject'),
            'message'   => $request->input('message'),
            'recipient' => $recipient_email,
            'sender'    => $client->email,
        ]);
        if(!$client_mail)
        {
            return response()->json(['errors' =>"internal data error"], 422);
        }
        try
       {
        $send_mail = new ClientSendMail($recipient_email, $request->input('subject'), $request->input('message'),$client->first_name." ".$client->last_name);
        Mail::to($recipient_email)->send($send_mail);
       }
       catch(Exception $e)
       {
        return response()->json(['message' => $e->getMessage()]);
       }
        return response()->json(['message' => 'Message has been sent', 'status'=> 200], 200);
        
    }
    public function send_email_vend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject'    => 'required|string',
            'message'    => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $token = $request->bearerToken();
        $verification_Details = explode("$", $token);
        $vendor_id = $verification_Details[1];
        $token = $verification_Details[0];
        $vendor = Vendor::find($vendor_id);
        if (!$vendor) {
            return response()->json(['error' => 'Record not found'], 404);
        }
        $vendor_name = $vendor->first_name." ".$vendor->last_name;
        $vendor_email = $vendor->email;
        $recipient_email = env('MAIL_SUPPORT');
        $vendor = MailSender::create([
            'subject'   => $request->input('subject'),
            'message'   => $request->input('message'),
            'recipient' => $recipient_email,
            'sender'    => $vendor_email,
        ]);
     
       try
       {
        $send_mail = new EmployeeSendMail($recipient_email, $request->input('subject'), $request->input('message'),$vendor_name);
        Mail::to($recipient_email)->send($send_mail);
       }
       catch(Exception $e)
       {
        return response()->json(['message' => $e->getMessage()]);
       }
        return response()->json(['message' => 'Message has been sent', 'status'=> 200], 200);

    }
}
