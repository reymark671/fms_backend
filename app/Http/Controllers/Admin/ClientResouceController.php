<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientFileUpload;

class ClientResouceController extends Controller
{
    //

    public function index()
    {
        $client_files = ClientFileUpload::with('client')->get();
        return view('pages.client_resources', compact('client_files'));
    }
}
