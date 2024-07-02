<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resources;
use App\Models\ClientFileUpload;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
class ResourcesController extends Controller
{
    //

    public function fetch_resources(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);
        $resources = Resources::all();
        return response()->json(['message' => 'fetch successful', 'data' => $resources], 201);

    }
    public function store_file(Request $request)
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
            $fileUrl = Storage::disk($s3Disk)->putFileAs('uploads/client_files', $file, $fileName, 'public');
            $fileUrls[] = Storage::disk($s3Disk)->url($fileUrl);
        }
    
        $concatenatedFileUrls = implode('|', $fileUrls);
    
        $payables = ClientFileUpload::create([
            'client_id' => $client_id,
            'report_file' => $concatenatedFileUrls,
            'description' =>$request->input('description'),
        ]);
    
        return response()->json(['message' => 'file uploaded successfully', 'status' => 200], 201);
    }
    public function fetch_store_file(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed'], 400);
        }

        $token_details = explode("$", $request->input('token'));
        $client_id = $token_details[1];
        
        $file_uploads = ClientFileUpload::where('client_id', $client_id)->get();
        $file_uploads_result = [];

        foreach ($file_uploads as $file_uploads) {
            $file_uploads_result[] = [
                'id'            => $file_uploads->id,
                'files'         => $file_uploads->report_file,
                'upload_date'   => $file_uploads->created_at,
                'description'   => $file_uploads->description,
            ];
        }

        return response()->json(['data' => $file_uploads_result], 200);
    }
}
