<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
class PayablesController extends Controller
{
    //
    public function fetch_payables_all(Request $request)
    {
        
        $payable_data = Payable::with('client')->with('employee')->get();
        return view('pages.payables_1',['payables' => $payable_data]);
    }
    public function upload_payable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files_upload.*' => 'required|file|mimes:jpeg,png,pdf,jpg|max:20048',
            'client_id' => 'required|exists:clients,id',
            'employee_id' => 'required|exists:employees,id',
        ]);
        
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $filePaths = [];
    
        foreach ($request->file('files_upload') as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/payables'), $fileName);
            $filePaths[] = 'uploads/payables/' . $fileName;
        }
    
        $concatenatedFileDir = implode('|', $filePaths);
    
        $payables = Payable::create([
            'employee_id' => $request->input('employee_id'),
            'client_id' => $request->input('client_id'),
            'file_dir' => $concatenatedFileDir,
        ]);
    
        return response()->json(['message' => 'payables created successfully', 'data' => $payables], 201);

    }
    public function fetch_payables(Request $request)
    {
        $client_id = $request->input('client_id');
        $payables = Payable::where('payables.client_id', $client_id)
                    ->get();
        return response()->json(['data' => $payables]);
    }
    public function update_payables(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'upload_file.*' => 'required|file|mimes:jpeg,png,pdf,jpg|max:20048',
            'payable_id' => 'required|exists:payables,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        // $filePaths = [];
    
        // foreach ($request->file('upload_file') as $file) {
        //     $fileName = time() . '_' . $file->getClientOriginalName();
        //     $file->move(public_path('uploads/payables/admin/'), $fileName);
        //     $filePaths[] = 'uploads/payables/admin/' . $fileName;
        // }
        $payables = Payable::find($request->input('payable_id'));
        if (!$payables) {
            return response()->json(['error' => 'payables not found'], 404);
        }
        // $concatenatedFileDir = implode('|', $filePaths);
        $s3Disk = 's3';
        $fileUrls = [];
        foreach ($request->file('upload_file') as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $fileUrl = Storage::disk($s3Disk)->putFileAs('uploads/employees/response', $file, $fileName, 'public');
            $fileUrls[] = Storage::disk($s3Disk)->url($fileUrl);
        }
    
        $concatenatedFileUrls = implode('|', $fileUrls);
        $payables->response_file = $concatenatedFileUrls;
        $payables->save();
        return response()->json(['data' => 'file was saved']);
    }
}
