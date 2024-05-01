<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\VendorsInvoice;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
    public function vendor_invoice()
    {
        $vendors_invoice = VendorsInvoice::with('vendor')->get();
        return view('pages.invoice_vendor',['vendors_invoice' => $vendors_invoice]);
    }
    public function update_vendor_invoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'upload_file.*' => 'required|file|mimes:jpeg,png,pdf,jpg|max:20048',
            'invoice_id' => 'required|exists:vendors_invoices,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $payables = VendorsInvoice::find($request->input('invoice_id'));
        if (!$payables) {
            return response()->json(['error' => 'invoice not found'], 404);
        }
        $s3Disk = 's3';
        $fileUrls = [];
        foreach ($request->file('upload_file') as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $fileUrl = Storage::disk($s3Disk)->putFileAs('uploads/invoice/response', $file, $fileName, 'public');
            $fileUrls[] = Storage::disk($s3Disk)->url($fileUrl);
        }
    
        $concatenatedFileUrls = implode('|', $fileUrls);
        $payables->reciept_file = $concatenatedFileUrls;
        $payables->is_complete = "1";
        $payables->save();
        return response()->json(['data' => 'file was saved']);
    }
    public function delete_vendor_invoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:vendors_invoices,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $invoice = VendorsInvoice::find($request->input('id'));
        $invoice->delete();
        return response()->json(['data' => 'Deleted']);

    }
}
