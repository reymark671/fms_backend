<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ServiceCodeController extends Controller
{
    //
    public function index(Request $request)
    {

        $serviceCodes = ServiceCode::with('category')->get();
        return view('pages.service_code', ['service_codes' => $serviceCodes]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:service_codes,code',
            'service_code_description' => 'required',
            'service_code_category_id' => 'required|exists:service_code_categories,id',
        ]);

        $serviceCode = ServiceCode::create($request->all());

        return response()->json([
            'message' => 'Service code created successfully',
            'service_code' => $serviceCode
        ], 201);
    }
    public function destroy($id)
    {
        $service_code = ServiceCode::findOrFail($id);
        $service_code->delete();
        return response()->json(['message' => 'Service code deleted successfully']);
    }
    public function edit($id)
    {
        $service_code = ServiceCode::findOrFail($id);
        return response()->json(['service_code' => $service_code]);
    }
    public function update(Request $request, $id)
    {
        $service_code = ServiceCode::findOrFail($id);
        $service_code->update($request->all());
        return response()->json(['message' => 'Service code updated successfully']);
    }
}
