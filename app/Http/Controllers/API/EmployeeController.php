<?php

namespace App\Http\Controllers\API;
use App\Models\Employee;
use App\Models\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
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
        $verification_Details = explode("$",$token);
        $client_id = $verification_Details[1];
        $token = $verification_Details[0];
        $client = Client::find($client_id);
        if(!$client)
        {
            return response()->json(['message' => 'invalid token', 'status' =>400]);
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
    
        $employee = Employee::create([
            'first_name' => $data->input('first_name'),
            'last_name' => $data->input('last_name'),
            'client_id' => $client_id,
            'file_dir' => $concatenatedFileDir,
        ]);
    
        return response()->json(['message' => 'employee created successfully','status'=> 201, 'data' => $employee], 201);
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
                    $employeeFiles[] =[];
                }
            }
            $employeeData[] = [
                'id' => $employee->id,
                'first_name' => $employee->first_name,
                'last_name' => $employee->last_name,
                'client_id' => $employee->client_id,
                'created_at' => $employee->created_at,
                'updated_at' => $employee->updated_at,
                'files' => $employeeFiles,
            ];
        }
        return response()->json($employeeData);
    }
    

}
