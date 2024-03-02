<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payroll;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PayrollController extends Controller
{
    //
    public function timesheet_upload(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'client_id' => 'required|exists:clients,id',
            'payroll_start' => 'required|date',
            'payroll_end' => 'required|date|after_or_equal:payroll_start',
            'time_sheet_file.*' => 'required|file|mimes:jpeg,png,pdf,jpg|max:20048',
        ], [
            'payroll_end.after_or_equal' => 'The end date must be equal to or later than the start date.',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $filePaths = [];
    
        foreach ($data->file('time_sheet_file') as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/payroll/timesheet/'), $fileName);
            $filePaths[] = 'uploads/payroll/timesheet/' . $fileName;
        }
    
        $concatenatedFileDir = implode('|', $filePaths);
    
        $payroll = Payroll::create([
            'client_id' => $data->input('client_id'),
            'payroll_start' => $data->input('payroll_start'),
            'payroll_end' => $data->input('payroll_end'),
            'time_sheet_file' => $concatenatedFileDir,
        ]);
    
        return response()->json(['message' => 'payroll created successfully', 'data' => $payroll], 201);
    }

    public function fetch_payroll(Request $request)
    {
        $client_id = $request->input('client_id');
        $payroll = Payroll::where('client_id', $client_id)->get();
        return response()->json(['data' => $payroll]);
    }
    public function fetch_payroll_api(Request $request)
{
    $client_id = $request->input('client_id');
    $payroll = Payroll::where('client_id', $client_id)->with('employee')->get();

    foreach ($payroll as &$record) {
        $file_path = public_path($record->payroll_file);
        $file_content = base64_encode(File::get($file_path));
        $record->payroll_file_content = $file_content;
    }

    return response()->json(['data' => $payroll]);
}
}
