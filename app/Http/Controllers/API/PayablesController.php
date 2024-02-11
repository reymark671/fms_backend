<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Payable;
use Illuminate\Support\Facades\File;
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
        $filePaths = [];
    
        foreach ($request->file('files') as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/payables'), $fileName);
            $filePaths[] = 'uploads/payables/' . $fileName;
        }
    
        $concatenatedFileDir = implode('|', $filePaths);
    
        $payables = Payable::create([
            'client_id' => $client_id,
            'file_dir' => $concatenatedFileDir,
            'description' =>$request->input('description'),
        ]);
    
        return response()->json(['message' => 'payables created successfully', 'status' => 200], 201);

    }
    public function fetch_payables(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);
        $token_details = explode("$", $request->input('token'));
        $client_id = $token_details[1];
        $payables = Payable::where('client_id', $client_id)->get();
        foreach($payables as $payable)
        {
            $fileDirs = explode('|', $payable->file_dir);
            $payable_file = [];
            foreach($fileDirs as $files)
            {
                $fullPath = public_path($files);
                if (file_exists($fullPath)) {
                    $fileContent = File::get($fullPath);
                    $base64FileContent = base64_encode($fileContent);
                    $payable_file[] = [
                        'file_name' => pathinfo($files, PATHINFO_BASENAME),
                        'file_content' => $base64FileContent,
                    ];
                } else {
                    $payable_file[] =[];
                }
            }
            $receipt_arr =[];
            if($payable->response_file)
            {
                $receipt_file = explode('|', $payable->response_file);
                foreach($receipt_file as $files)
                {
                    if (file_exists($fullPath)) {
                        $fileContent = File::get($fullPath);
                        $base64FileContent = base64_encode($fileContent);
                        $receipt_arr[] = [
                            'file_name' => pathinfo($files, PATHINFO_BASENAME),
                            'file_content' => $base64FileContent,
                        ];
                    } else {
                        $receipt_arr[] =[];
                    }
                }
            }
            $payables_result[] = [
                'id'            => $payable->id,
                'files'         => $payable_file,
                'upload_date'   => $payable->created_at,
                'description'   => $payable->description,
                'receipt'       => $receipt_arr
            ];
        }
        return response()->json(['data' => $payables_result]);
    }
}
