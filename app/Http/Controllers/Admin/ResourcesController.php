<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resources;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
class ResourcesController extends Controller
{
    //

    public function resources(Request $request)
    {
        $resources = Resources::all();
        return view('pages.resources',['resources' => $resources]);
    }
    public function add_resources(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'upload_file.*' => 'required|file|mimes:jpeg,png,pdf,jpg|max:20048',
            'title' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $s3Disk = 's3';
        $fileUrls = [];
        foreach ($request->file('upload_file') as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $fileUrl = Storage::disk($s3Disk)->putFileAs('uploads/resources', $file, $fileName, 'public');
            $fileUrls[] = Storage::disk($s3Disk)->url($fileUrl);
        }
        $concatenatedFileUrls = implode('|', $fileUrls);
        $resources = Resources::create([
            'url' => $concatenatedFileUrls,
            'resource_name' =>$request->input('title'),
            'description' =>$request->input('description') ?? '',
        ]);
        return response()->json(['message' => 'resources created successfully', 'status' => 200], 201);
    

    }
    public function delete_resources(Request $request)
    {
        $resourceId = $request->input('id');
        $resource = Resources::find($resourceId);
       
        if (!$resource) {
            return response()->json(['message' => 'resource not found'], 404);
        }
        $resource->delete();

        return response()->json(['message' => 'resource soft-deleted successfully']);
    }
}
