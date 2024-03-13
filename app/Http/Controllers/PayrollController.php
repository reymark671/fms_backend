<?php

namespace App\Http\Controllers;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
class PayrollController extends Controller
{
    //
    public function fetch_payroll_all()
    {
        $payroll_data = Payroll::with('client')->with('employee')->get();
        return view('pages.payroll_1',['payroll' => $payroll_data]);
    }
    public function update_payroll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payroll_id' => 'required|exists:payroll,id',
            'payroll_file.*' => 'required|file|mimes:jpeg,png,pdf,jpg|max:20048',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        // $filePaths = [];
    
        // foreach ($request->file('payroll_file') as $file) {
        //     $fileName = time() . '_' . $file->getClientOriginalName();
        //     $file->move(public_path('uploads/payroll/payroll_sheet/'), $fileName);
        //     $filePaths[] = 'uploads/payroll/payroll_sheet/' . $fileName;
        // }
    
        // $concatenatedFileDir = implode('|', $filePaths);
        $s3Disk = 's3';
        $fileUrls = [];
        foreach ($request->file('payroll_file') as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $fileUrl = Storage::disk($s3Disk)->putFileAs('uploads/payroll', $file, $fileName, 'public');
            $fileUrls[] = Storage::disk($s3Disk)->url($fileUrl);
        }
    
        $concatenatedFileUrls = implode('|', $fileUrls);
        $payroll = Payroll::find($request->input('payroll_id'));
        if ($payroll) {
            $payroll->update([
                'payroll_file' => $concatenatedFileUrls,
            ]);
        } else {
            return response()->json(['errors' => "payroll not found"], 422);
        }
        return response()->json(['data' => $payroll]);

    }
    public function create_payroll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient' => 'required|exists:clients,id',
            'provider' => 'required|exists:employees,id',
            'from_date' => 'required',
            'to_date' => 'required',
            'payroll.*' => 'required|file|mimes:jpeg,png,pdf,jpg,xlsx|max:20048',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        // $filePaths = [];
        // foreach ($request->file('payroll') as $file) {
        //     $fileName = time() . '_' . $file->getClientOriginalName();
        //     $file->move(public_path('uploads/payroll/payroll_sheet/'), $fileName);
        //     $filePaths[] = 'uploads/payroll/payroll_sheet/' . $fileName;
        // }
        // $concatenatedFileDir = implode('|', $filePaths);
        $s3Disk = 's3';
        $fileUrls = [];
        foreach ($request->file('payroll') as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $fileUrl = Storage::disk($s3Disk)->putFileAs('uploads/payroll', $file, $fileName, 'public');
            $fileUrls[] = Storage::disk($s3Disk)->url($fileUrl);
        }
    
        $concatenatedFileUrls = implode('|', $fileUrls);
        $payroll = Payroll::create([
            'client_id' => $request->input('recipient'),
            'employee_id' => $request->input('provider'),
            'payroll_start' => $request->input('from_date'),
            'payroll_end' => $request->input('to_date'),
            'payroll_file' => $concatenatedFileUrls,
        ]);
    
        return response()->json(['message' => 'employee created successfully', 'status' => 201, 'data' => $payroll], 201);
        
    }
}
