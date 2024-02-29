<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\EmployeeSendMail;
use App\Mail\ClientSendMail;
use App\Models\Employee;
use App\Models\MailSender;
use App\Models\Client;
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
        $send_mail = new EmployeeSendMail("poknaitz@gmail.com", $request->input('subject'), $request->input('message'),$employee_name);
        Mail::to('poknaitz@gmail.com')->send($send_mail);
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
        $client_mail = MailSender::create([
            'subject'   => $request->input('subject'),
            'message'   => $request->input('message'),
            'recipient' => 'poknaitz@gmail.com',
            'sender'    => $client->email,
        ]);
        if(!$client_mail)
        {
            return response()->json(['errors' =>"internal data error"], 422);
        }
        try
       {
        $send_mail = new ClientSendMail("poknaitz@gmail.com", $request->input('subject'), $request->input('message'),$client->first_name." ".$client->last_name);
        Mail::to('poknaitz@gmail.com')->send($send_mail);
       }
       catch(Exception $e)
       {
        return response()->json(['message' => $e->getMessage()]);
       }
        return response()->json(['message' => 'Message has been sent', 'status'=> 200], 200);
        
    }
}
