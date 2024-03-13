<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Timesheet;
use Illuminate\Http\Request;
use App\Mail\send_otp;
use App\Mail\NewEmployee;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    //
    public function add_employee(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string',
            'phone_number' => 'required|string',
            'files_upload.*' => 'required|file|mimes:jpeg,png,pdf,jpg,xlsx|max:20048',
        ]);
        $token = $data->bearerToken();
        $verification_Details = explode("$", $token);
        $client_id = $verification_Details[1];
        $token = $verification_Details[0];
        $client = Client::find($client_id);
        if (!$client) {
            return response()->json(['message' => 'invalid token', 'status' => 400]);
        }
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // $filePaths = [];

        // foreach ($data->file('files_upload') as $file) {
        //     $fileName = time() . '_' . $file->getClientOriginalName();
        //     $file->move(public_path('uploads/employees'), $fileName);
        //     $filePaths[] = 'uploads/employees/' . $fileName;
        // }

        // $concatenatedFileDir = implode('|', $filePaths);
        $s3Disk = 's3';
        $fileUrls = [];
        foreach ($data->file('files_upload') as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $fileUrl = Storage::disk($s3Disk)->putFileAs('uploads/employees', $file, $fileName, 'public');
            $fileUrls[] = Storage::disk($s3Disk)->url($fileUrl);
        }
    
        $concatenatedFileUrls = implode('|', $fileUrls);
        $sp_number = "SPN_" . date("ymd") . $client_id;
        $pw = Str::random(8);
        $hashedPassword = Hash::make($pw);
        $employee = Employee::create([
            'first_name' => $data->input('first_name'),
            'last_name' => $data->input('last_name'),
            'email' => $data->input('email'),
            'phone_number' => $data->input('phone_number'),
            'client_id' => $client_id,
            'file_dir' => $concatenatedFileUrls,
            'SP_number' => $sp_number,
            'Username' => $sp_number,
            'password' => $hashedPassword,
            'pw' => $pw,
        ]);
        $employeeId = $employee->id;
        $sp_number_with_id = $sp_number . $employeeId;
        $employee->update([
            'SP_number' => $sp_number_with_id,
            'Username' => $sp_number_with_id,
        ]);
        $url = env('CLIENT_URL');
        Mail::to($employee->email)->send(new NewEmployee($sp_number_with_id,$url, $employee->pw));
        return response()->json(['message' => 'employee created successfully, employees will receive their log in credentials', 'status' => 201, 'data' => $employee], 201);
    }

    public function fetch_employees_old(Request $request)
    {
        $credentials = $request->only('token');
        $data = explode("$", $credentials['token']);
        $token = $data[0];
        $client_id = $data[1];
        $employees = Employee::where('client_id', $client_id)->get();

        $employeeData = [];

        foreach ($employees as $employee) {
            $fileDirs = explode('|', $employee->file_dir);

            $employeeFiles = [];

            foreach ($fileDirs as $fileDir) {
                $fullPath = public_path($fileDir);
                if (file_exists($fullPath)) {
                    $fileContent = File::get($fullPath);
                    $base64FileContent = base64_encode($fileContent);
                    $employeeFiles[] = [
                        'file_name' => pathinfo($fileDir, PATHINFO_BASENAME),
                        'file_content' => $base64FileContent,
                    ];
                } else {
                    $employeeFiles[] = [];
                }
            }
            $employeeData[] = [
                'id' => $employee->id,
                'first_name'    => $employee->first_name,
                'last_name'     => $employee->last_name,
                'client_id'     => $employee->client_id,
                'SP_number'     => $employee->SP_number,
                'status'        => $employee->Status,
                'pw'            => $employee->pw,
                'created_at'    => $employee->created_at,
                'updated_at'    => $employee->updated_at,
                'files'         => $employeeFiles,
            ];
        }
        return response()->json($employeeData);
    }
    public function fetch_employees(Request $request)
    {
        $credentials = $request->only('token');
        $data = explode("$", $credentials['token']);
        $token = $data[0];
        $client_id = $data[1];
        $employees = Employee::where('client_id', $client_id)->get();
    
        $employeeData = [];
    
        foreach ($employees as $employee) {
            $fileUrls = explode('|', $employee->file_dir); 
    
            $employeeData[] = [
                'id' => $employee->id,
                'first_name' => $employee->first_name,
                'last_name' => $employee->last_name,
                'client_id' => $employee->client_id,
                'SP_number' => $employee->SP_number,
                'status' => $employee->Status,
                'pw' => $employee->pw,
                'created_at' => $employee->created_at,
                'updated_at' => $employee->updated_at,
                'files' => $fileUrls, 
            ];
        }
    
        return response()->json($employeeData);
    }

    

    public function sign_in(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $employees = Employee::where('email', $credentials['email'])
        ->orWhere('Username', $credentials['email'])
        ->first();
        if (!$employees) {
            return response()->json(['message' => 'Invalid email or password', 'status' => 401]);
        }
        if (Hash::check($credentials['password'], $employees->password)) {
           
            $token_random_str = Str::random(60);
            $token = $token_random_str."$".$employees->id;
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $subject ="OTP LOGIN CODE";
            $body ="Here is your OTP CODE. <br><br><b>$otp</b><br><br> Please copy this in the OTP Login Portal to make your login successful.";
            Mail::to($employees->email)->send(new send_otp($otp, $body, $subject));
            $employees->update([
                'token' => $token_random_str,
                'OTP'     => $otp,
            ]);
            return response()->json([
                'message' => 'Verification',
                'token'   => $token,
                'status'  => 200,
            ]);
        } else {
            return response()->json(['message' => 'Invalid email or password', 'status' => 401]);
        }
    }

    public function timesheet_entry(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'start_date'    => 'required|date',
            'start_time'    => 'required|date_format:H:i',
            'end_date'      => 'required|date',
            'end_time'      => 'required|date_format:H:i',
            'total_hours'   => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $token = $request->bearerToken();
        $verification_Details = explode("$", $token);
        $employee_id = $verification_Details[1];
        $token = $verification_Details[0];
        $employee_data = Employee::find($employee_id);
        if(!$employee_data)
        {
            return response()->json(['message' => 'Unregistered', 'status' => 401]);
        }

        //get the month para sa compute ng total month
        // $month_from = date("Y-m-01", strtotime($request->input('start_date')));
        // $month_to = date("Y-m-31", strtotime($request->input('start_date')));
        
        // $current_time_allocated = Timesheet::where('employee_id', $employee_id)
        //     ->whereBetween('start_date', [$month_from, $month_to])
        //     ->whereBetween('end_date', [$month_from, $month_to])
        //     ->get();
        // $total_hours_sum = $current_time_allocated->sum('total_hours');
        // $total_month_hrs = 50 - ($total_hours_sum + $request->input('total_hours'));
        // $available_hours = 50 - $total_hours_sum ;
        // if($total_hours_sum>50  )
        // {
        //     return  response()->json(['message' => "you exceeded the maximum number of hours for this month", 'status' => 401]);
        // }
        // if($total_month_hrs<=0)
        // {
        //     return  response()->json([
        //         'message' => "you will exceeded the maximum number of hours for this duration", 
        //         'available_hours'=>$available_hours,
        //         'status' => 401]);
        // }
        $week_start = date('Y-m-d', strtotime('monday this week', strtotime($request->input('start_date'))));
        $week_end = date('Y-m-d', strtotime('sunday this week', strtotime($request->input('start_date'))));

        $current_time_allocated = Timesheet::where('employee_id', $employee_id)
            ->whereBetween('start_date', [$week_start, $week_end])
            ->whereBetween('end_date', [$week_start, $week_end])
            ->get();

        $total_hours_sum = $current_time_allocated->sum('total_hours');
        $total_week_hrs = 50 - ($total_hours_sum + $request->input('total_hours'));
        $available_hours = 50 - $total_hours_sum;

        if ($total_hours_sum > 50) {
            return response()->json(['message' => "You exceeded the maximum number of hours for this week ", 'status' => 401]);
        }

        if ($total_week_hrs <= 0) {
            return response()->json([
                'message' => "You will exceed the maximum number of hours for this week ",
                'available_hours' => $available_hours,
                'status' => 401
            ]);
        }
       
        $timesheet = Timesheet::create([
            'start_date'    => $request->input('start_date'),
            'start_time'    => $request->input('start_time'),
            'end_date'      => $request->input('end_date'),
            'end_time'      => $request->input('end_time'),
            'specification' => $request->input('specification'),
            'total_hours'   => $request->input('total_hours'),
            'employee_id'   => $employee_data->id,
            'client_id'     => $employee_data->client_id,
        ]);

        return response()->json(['message' => 'timesheet was added successfully', 'status' => 201, 'data' => $timesheet], 201);

    }

    public function timesheets(Request $request)
    {
        $token = $request->bearerToken();
        $verification_Details = explode("$", $token);
        $employee_id = $verification_Details[1];
        $token = $verification_Details[0];
        $timesheets = Timesheet::where('employee_id', $employee_id)->get();
        return response()->json(['message' => 'query successful', 'status' => 201, 'data' => $timesheets], 201);
        
    }

    public function timesheets_client(Request $request)
    {
        
        $token = $request->bearerToken();
        $verification_Details = explode("$", $token);
        $client_id = $verification_Details[1];
        $token = $verification_Details[0];
        $timesheets = Timesheet::where('client_id', $client_id)->with('employee')->get();
       
        return response()->json(['message' => 'query successful', 'status' => 201, 'data' => $timesheets], 201);
        
    }
    public function decline_timesheet(Request $request)
    {
        $token = $request->bearerToken();
        $verification_Details = explode("$", $token);
        $client_id = $verification_Details[1];
        $token = $verification_Details[0];

        $timesheet = Timesheet::where('client_id', $client_id)
            ->where('id', $request->input('time_sheet_id'))
            ->first();  

        if ($timesheet) {
            $timesheet->update(['status' => $request->input('status')]); 
            return response()->json(['message' => 'query successful', 'status' => 201, 'data' => $timesheet], 201);
        } else {
            return response()->json(['message' => 'timesheet not found', 'status' => 404], 404);
        }
    }

}
