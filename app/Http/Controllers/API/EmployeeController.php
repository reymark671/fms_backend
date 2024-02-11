<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    //
    public function add_employee(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
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

        $filePaths = [];

        foreach ($data->file('files_upload') as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/employees'), $fileName);
            $filePaths[] = 'uploads/employees/' . $fileName;
        }

        $concatenatedFileDir = implode('|', $filePaths);
        $sp_number = "SPN_" . date("ymd") . $client_id;
        $pw = Str::random(8);
        $hashedPassword = Hash::make($pw);
        $employee = Employee::create([
            'first_name' => $data->input('first_name'),
            'last_name' => $data->input('last_name'),
            'client_id' => $client_id,
            'file_dir' => $concatenatedFileDir,
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

        return response()->json(['message' => 'employee created successfully', 'status' => 201, 'data' => $employee], 201);
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

}
