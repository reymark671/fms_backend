<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reports;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Reports\CreateReportRequest;
class ReportsController extends Controller
{
    //

    public function fetch_all_reports()
    {
        $reports = Reports::all()->map(function ($report) {
            $report->load('users'); // Eager load the 'users' relationship if necessary
            $report->destination_account_full_names = $report->getDestinationAccountFullNamesAttribute();
            return $report;
        });
        return view('pages.reports', ['reports' => $reports]);
    }
    public function upload_report(CreateReportRequest $request)
    {
        try{
            $s3Disk = 's3';
            foreach ($request->file('report_file') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $fileUrl = Storage::disk($s3Disk)->putFileAs('uploads/reports', $file, $fileName, 'public');
                $fileUrls[] = Storage::disk($s3Disk)->url($fileUrl);
            }
            $concatenatedFileUrls = implode('|', $fileUrls);
            $validatedData = $request->validated();
            $validatedData['report_file'] = $concatenatedFileUrls;
            $validatedData['report_destination_type'] = $request->input('report_destination');
            $validatedData['report_destination_account_id'] = implode(',', $request->input('destination_account'));
            $vendor = Reports::create($validatedData);
            return response()->json(['message' => 'report was created successfully', 'status' => 200]);
        }
        catch (QueryException $e) {
            return response()->json(['errors' => "Error insert data.", 'status' => 500]);
        }
    }

    public function delete_report(Request $request)
    {
        $reportsId = $request->input('id');
        $reports = Reports::find($reportsId);
       
        if (!$reports) {
            return response()->json(['message' => 'reports not found'], 404);
        }
        $reports->delete();

        return response()->json(['message' => 'reports soft-deleted successfully']);
    }
}
