<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
class VendorController extends Controller
{
    //

    public function fetch_all_vendors()
    {
        $vendors = Vendor::get();
        return view('pages.vendors',['vendors' => $vendors]);
    }

    public function change_vendor_status(Request $request)
    {
        $vendor_id = $request->input('id');
        $vendor = Vendor::find($vendor_id);
       
        if (!$vendor) {
            return response()->json(['message' => 'resource not found'], 404);
        }
        $vendor->is_active = $request->input('status');
        $vendor->save();

        return response()->json(['message' => 'resource soft-deleted successfully']);
    }
}
