<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AdminRegisterController extends Controller
{
    //
    public function register_admin()
    {
        return view('pages.register_admin');
    }
    public function create_admin(Request $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return view('pages.register_admin');
    }
}
