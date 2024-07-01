<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceCodeCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class ServiceCategoryController extends Controller
{
    //
    public function index(Request $request)
    {

        $service_categories = ServiceCodeCategory::all();
        return view('pages.service_category',['service_categories' => $service_categories]);
    }

    public function create(Request $request)
    {
        if ($request->has('category_name')) {
            $validator = Validator::make($request->all(), [
                'category_name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('service_code_categories', 'category_description')->whereNull('deleted_at')
                ]
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first(), 'status' => 422], 422);
            }
            $service_category = ServiceCodeCategory::create([
                'category_description' => $request->input('category_name'),
            ]);
        
            return response()->json(['message' => 'Category was successfully added', 'status' => 200], 201);
        }
        return response()->json(['message' => 'Category name is required', 'status' => 400], 400);
    }
    public function destroy($id)
    {
        try {
            $category = ServiceCodeCategory::findOrFail($id);
            $category->delete();

            return response()->json([
                'message' => 'Category successfully deleted.',
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete category.',
                'status' => 500
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_description' => 'required|string|max:255|unique:service_code_categories,category_description,' . $id,
        ]);

        try {
            $category = ServiceCodeCategory::findOrFail($id);
            $category->category_description = $request->category_description;
            $category->save();

            return response()->json([
                'message' => 'Category successfully updated.',
                'category' => $category,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update category.',
                'status' => 500
            ], 500);
        }
    }
    public function getCategories()
    {
        $categories = ServiceCodeCategory::all();
        return response()->json(['categories' => $categories]);
    }
}
