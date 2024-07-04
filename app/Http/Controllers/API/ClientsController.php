<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Requests\Clients;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
class ClientsController extends Controller
{
    //
    public function view_all()
    {
    
    }
    public function register_client(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'ss_number' => 'required|string|unique:clients',
                'address' => 'required|string',
                'city' => 'required|string',
                'state' => 'required|string',
                'zip_code' => 'required|string',
                'contact_number' => 'required|string',
                'email' => 'required|email|unique:clients',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Hash the password before storing it
            $hashedPassword = Hash::make($validator->validated()['password']);
            $validatedData = $validator->validated();
            $validatedData['password'] = $hashedPassword;

            $client = Client::create($validatedData);

            return response()->json(['message' => 'Client created successfully', 'status' => 200]);
        } catch (QueryException $e) {
            return response()->json(['errors' => "Error insert data.", 'status' => 500]);
        } 
    }
}
