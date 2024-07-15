<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\ClientStatusUpdated;
use Illuminate\Support\Facades\Mail;

class ClientsController extends Controller
{
    public function view_all(Request $request)
    {
        $statusFilter = $request->input('status', 'all');
        $clients = $this->getClientsByStatus($statusFilter);

        return view('pages.clients', ['clients' => $clients, 'selectedStatus' => $statusFilter]);
    }
    public function fetch_clients(Request $status)
    {
        $statusFilter = $status->input('status', 'all');
        $clients = $this->getClientsByStatus($statusFilter);
        return $clients;
    }

    public function delete_client(Request $request)
    {
        $clientId = $request->input('id');
        $client = Client::find($clientId);

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }
        $client->delete();

        return response()->json(['message' => 'Client soft-deleted successfully']);
    }
    public function approve_client(Request $request)
    {
        $client = Client::find($request->input('id'));
        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }
        $client->status = 1;
        $client->save();
        $clientEmail = $client->email;
        Mail::to($clientEmail)->send(new ClientStatusUpdated($client));
        return response()->json(['message' => 'Client status updated successfully']);
    }

    private function getClientsByStatus($statusFilter)
    {
        if ($statusFilter === 'active') {
            return Client::where('status', '>', 0)->get();
        } elseif ($statusFilter === 'pending') {
            return Client::where('status', 0)->get();
        } elseif ($statusFilter === 'declined') {
            return Client::where('status', '<', 0)->get();
        }
        return Client::all();
    }
    public function fetch_clients_data()
    {
        $client = Client::select('id', 'first_name', 'last_name','email')->get();
        return response()->json($client);
    }
    
}


