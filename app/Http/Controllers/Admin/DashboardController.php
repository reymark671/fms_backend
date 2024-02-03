<?php

namespace App\Http\Controllers\Admin;
use App\Models\Client;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $clients = Client::all();
        $client_status = [
            -1 => 'Declined',
            0  => 'Pending',
            1  => 'Active',
        ];
        $clientCounts = $clients->groupBy('status')->map(function ($group, $status) use ($client_status) {
            return [
                'label' => $client_status[$status],
                'count' => $group->count(),
            ];
        });
        return view('pages.dashboard', ['clientCounts' => $clientCounts]);
    }
}
