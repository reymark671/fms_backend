<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\VendorsInvoice;
use Illuminate\Http\Request;

class ClientVendorController extends Controller
{
    //
    protected $token;
    protected $client_id;
    public function __construct(Request $request)
    {
        $this->token = $request->bearerToken();
        $this->verifyToken($request);
    }
    public function verifyToken($request)
    {
        if (!$this->token) {
            return response()->json(['message' => 'Invalid request'], 401);
        }

        $verification_Details = explode("$", $this->token);
        $this->client_id = $verification_Details[1];
    }
    public function clientFetchVendorsInvoice(Request $request)
    {
        if($this->client_id){
            $invoices = VendorsInvoice::where('client_id', $this->client_id)->get();
            return response()->json(['data' => $invoices]);
        }
        else
        {
            return response()->json(['message' => 'Invalid Client Account'], 401);
        }
    }
    public function declineInvoice(Request $request)
    {
        if($this->client_id){
            $validated = $request->validate([
                'invoice_id' => 'required|exists:vendors_invoices,id',
            ]);
            $invoice = VendorsInvoice::find($validated['invoice_id']);
            if ($invoice) {
                $invoice->update([
                    'is_client_approved' => '-1',
                ]);
                return response()->json(['message' => 'Invoice Declined Successfully!'], 200);
            } else {
                return response()->json(['message' => 'Invoice not found'], 404);
            }
        }
        else
        {
            return response()->json(['message' => 'Invalid Client Account'], 401);
        }
    }

    public function approveInvoice(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:vendors_invoices,id',
        ]);
        $invoice = VendorsInvoice::find($validated['invoice_id']);
        if ($invoice) {
            $invoice->update([
                'is_client_approved' => '1',
            ]);
            return response()->json(['message' => 'Invoice Accepted Successfully!'], 200);
        } else {
            return response()->json(['message' => 'Invoice not found'], 404);
        }
    }

}
