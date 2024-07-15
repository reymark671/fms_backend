<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coordinator;
use App\Models\Client;
use App\Models\CoordinatorAssignment;
class ServiceCoordinatorController extends Controller
{
    //

    public function fetch_all_service_coordinator_accounts()
    {
        
        // $coordinators = Coordinator::get();
        $coordinators = Coordinator::with(['assignments.client:id,first_name,last_name,email'])->get();
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
    public function fetch_available_clients(Request $request)
    {
        if($request->input('coordinator_id'))
        {
            $coordinator_id = $request->input('coordinator_id');
            $assignedClients = CoordinatorAssignment::where('coordinator_id', $coordinator_id)->pluck('client_id');
            $availableClients = Client::whereNotIn('id', $assignedClients)
            ->select('id', 'first_name', 'last_name','email') 
            ->get();
            return  response()->json($availableClients);
        }
        return  response()->json(['message' => 'error']);
    }
    public function clients_assignment(Request $request)
    {
        $request->validate([
            'coordinator_id' => 'required|exists:coordinators,id',
            'selected_clients' => 'required|array',
            'selected_clients.*' => 'exists:clients,id',
        ]);

        $coordinator_id = $request->coordinator_id;
        $selected_clients = $request->selected_clients;
        foreach ($selected_clients as $client_id) {
            CoordinatorAssignment::create([
                'coordinator_id' => $coordinator_id,
                'client_id' => $client_id,
            ]);
        }

        return response()->json(['message' => 'Clients successfully assigned'], 200);

    }
    public function fetch_coordinators()
    {
        $coordinators = Coordinator::select('id', 'first_name', 'last_name','email') ->get();
        return response()->json($coordinators);
    }
}
