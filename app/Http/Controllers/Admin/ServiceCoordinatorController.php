<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coordinator;
class ServiceCoordinatorController extends Controller
{
    //

    public function fetch_all_service_coordinator_accounts()
    {
        
        $coordinators = Coordinator::get();
        return view('pages.coordinators',['coordinators' => $coordinators]);
    }

    public function change_coordinator_status(Request $request)
    {
        $coordinator_id = $request->input('id');
        $coordinator = Coordinator::find($coordinator_id);
       
        if (!$coordinator) {
            return response()->json(['message' => 'resource not found'], 404);
        }
        $coordinator->is_active = $request->input('status');
        $coordinator->save();

        return response()->json(['message' => 'resource soft-deleted successfully']);
    }
}
