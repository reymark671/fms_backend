<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reports;
use App\Models\Client;
use Illuminate\Support\Facades\Validator;
class ReportsController extends Controller
{
    //
    public function fetch_reports_client(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed'], 400);
        }
        

        $token_details = explode("$", $request->input('token'));
        $client_id = $token_details[1];
        $client = Client::find($client_id);
        if(!$client)
        {
            return response()->json(['error' => 'Validation failed'], 400);
        }
        $fms_reports        = Reports::get();
        $fms_reports = Reports::where('report_destination_type', 3)
            ->whereRaw("FIND_IN_SET($client_id, report_destination_account_id)")
            ->get();
        return response()->json(['data' => $fms_reports], 200);
    }
}
