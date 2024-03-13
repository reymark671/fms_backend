<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Payable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
class PayablesController extends Controller
{
    //
    public function upload_payable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files.*' => 'required|file|mimes:jpeg,png,pdf,jpg,xlsx|max:20048',
            'token' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $token_details = explode("$", $request->input('token'));
        $client_id = $token_details[1];
        $s3Disk = 's3';
        $fileUrls = [];
        foreach ($request->file('files') as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $fileUrl = Storage::disk($s3Disk)->putFileAs('uploads/payables', $file, $fileName, 'public');
            $fileUrls[] = Storage::disk($s3Disk)->url($fileUrl);
        }
    
        $concatenatedFileUrls = implode('|', $fileUrls);
    
        $payables = Payable::create([
            'client_id' => $client_id,
            'file_dir' => $concatenatedFileUrls,
            'description' =>$request->input('description'),
        ]);
    
        return response()->json(['message' => 'payables created successfully', 'status' => 200], 201);

    }
    public function fetch_payables(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => 'Validation failed'], 400);
            }

            $token_details = explode("$", $request->input('token'));
            $client_id = $token_details[1];
            
            $payables = Payable::where('client_id', $client_id)->get();
            $payables_result = [];

            foreach ($payables as $payable) {
                $fileDirs = $payable->file_dir ? explode('|', $payable->file_dir) : [];
                $receipt_file = $payable->response_file ? explode('|', $payable->response_file) : [];

                $payables_result[] = [
                    'id'            => $payable->id,
                    'files'         => $fileDirs,
                    'upload_date'   => $payable->created_at,
                    'description'   => $payable->description,
                    'receipt'       => $receipt_file,
                ];
            }

            return response()->json(['data' => $payables_result], 200);
        }
}
