<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
class LoginController extends Controller
{
    //
    public function login(Request $request)
    {
       
        $credentials = $request->only('email', 'password');
        $client = Client::where('email', $credentials['email'])->first();
        if (!$client) {
            return response()->json(['message' => 'Invalid email or password', 'status' => 401]);
        }

        if (Hash::check($credentials['password'], $client->password)) {
            if($client->status<=0)
            {
                return response()->json([
                    'message' => 'Status Failed',
                    'data'    => 'account not yet active',
                    'status'  => 406,
                ]);

            }
            $token_random_str = Str::random(60);
            $token = $token_random_str."$".$client->id;
            $client->update(['api_token' => $token_random_str]);
            return response()->json([
                'message' => 'Login successful',
                'data'    => $client,
                'token'   => $token,
                'status'  => 200,
            ]);
        } else {
            return response()->json(['message' => 'Invalid email or password', 'status' => 401]);
        }
    }
}
