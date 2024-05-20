<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resources;
use Illuminate\Support\Facades\Validator;
class ResourcesController extends Controller
{
    //

    public function fetch_resources(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);
        $resources = Resources::all();
        return response()->json(['message' => 'fetch successful', 'data' => $resources], 201);

    }
}
