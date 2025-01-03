<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Client;
use App\Models\VendorBankAccount;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\TcpdfFpdi;
use TCPDF;
use mikehaertl\pdftk\Pdf;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Storage;
use App\Models\VendorEnrolledAccount;

class VendorAccountController extends Controller
{
    
    protected $token;
    protected $vendor_id;
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
        $this->vendor_id = $verification_Details[1];
    }
    public function fetchVendorAccount()
    {
        if(!$this->vendor_id)
        {
            return response()->json(['message' => 'Invalid Vendor Account'], 401);
        }
        $vendor = Vendor::where('id', $this->vendor_id)->first();
        if($vendor)
        {
            $vendor->makeHidden(['password']);
            return response()->json(['data' => $vendor], 200);
        }
        else
        {
            return response()->json(['message' => 'Invalid Vendor Account'], 401);
        }
    }


    public function fetchAccountStatus()
    {
        if(!$this->vendor_id)
        {
            return response()->json(['message' => 'Invalid Vendor Account'], 401);
        }
        $vendor = Vendor::where('id', $this->vendor_id)
        ->with('vendorBankAccounts')  
        ->first();
        $vendor->makeHidden(['password', 'otp']);
        $vendorData =[];
        $vendorData['signature'] = null;
        if(!$vendor)
        {
            return response()->json(['message' => 'Invalid Vendor Account'], 401);
        }
        $vendorData = [
            'signature' => $vendor->signature ? 1 : 0,
            'payment' => $vendor->vendorBankAccounts->isEmpty() ? 0 : 1,
        ];
        return response()->json(['data' => $vendorData], 200);
    }
    public function fetchAccountSignature()
    {
        if(!$this->vendor_id)
        {
            return response()->json(['message' => 'Invalid Vendor Account'], 401);
        }
        $vendor = Vendor::where('id', $this->vendor_id)->first();
        if($vendor)
        {
            $signature = $vendor->signature;
            if (filter_var($signature, FILTER_VALIDATE_URL)) {
                return response()->json([
                    'data' => [
                        'type' => 'drawn',
                        'signature_url' => $signature,
                    ]
                ], 200);
            }
            $signature_text = json_decode($signature);
            return response()->json(['data' => $signature_text], 200);
        }
        else
        {
            return response()->json(['message' => 'Invalid Vendor Account'], 401);
        }
    }
    public function UpdateAccountSignatureText(Request $request)
    {
        if(!$this->vendor_id)
        {
            return response()->json(['message' => 'Invalid Vendor Account'], 401);
        }
        $vendor = Vendor::where('id', $this->vendor_id)->first();
        if($vendor)
        {
            $vendor->signature = $request->all();
            $vendor->signature_date = date('Y-m-d');
            $vendor->save();
            return response()->json(['message' => 'signature updated successfully'], 200);
        }
        else
        {
            return response()->json(['message' => 'Invalid Vendor Account'], 401);
        }
    }
    public function UpdateAccountSignatureDraw(Request $request)
    {
        if(!$this->vendor_id)
        {
            return response()->json(['message' => 'Invalid Vendor Account'], 401);
        }
        $vendor = Vendor::where('id', $this->vendor_id)->first();
        if($vendor)
        {
            $request->validate([
                'signature' => 'required|file|mimes:jpeg,png,jpg,gif',
                'style' => 'required|string',
                'type' => 'required|string',
            ]);
            $s3Disk = 's3';
            $fileName = $this->vendor_id. '_' . $request->file('signature')->getClientOriginalName();
            $fileUrl = Storage::disk($s3Disk)->putFileAs('uploads/vendor/signature',  $request->file('signature'), $fileName, 'public');
            $vendor->signature = Storage::disk($s3Disk)->url($fileUrl);
            $vendor->save();
            return response()->json(['message' => 'signature updated successfully'], 200);
        }
        else
        {
            return response()->json(['message' => 'Invalid Vendor Account'], 401);
        }
    }
    public function UpdateBankAccount(Request $request)
    {
        $validatedData = $request->validate([
            'account_type' => 'required|in:1,2',
            'bank_name' => 'required|string|max:100',
            'routing_number' => 'required|digits_between:9,12',
            'account_number' => 'required|digits_between:9,12',
            'paystub_copy' => 'boolean',
        ]);
        $bankAccount = VendorBankAccount::where('vendor_id', $this->vendor_id)->first();

        if ($bankAccount) {
            $bankAccount->update($validatedData);

            return response()->json([
                'message' => 'Bank account details updated successfully.',
                'data' => $bankAccount,
            ]);
        } else {
            $validatedData['vendor_id'] =$this->vendor_id;

            $newBankAccount = VendorBankAccount::create($validatedData);

            return response()->json([
                'message' => 'Bank account details saved successfully.',
                'data' => $newBankAccount,
            ]);
        }
    }
    public function fetchBankAccount()
    {
        if(!$this->vendor_id)
        {
            return response()->json(['message' => 'Invalid Vendor Account'], 401);
        }
        $vendorBankAccount = VendorBankAccount::where('vendor_id', $this->vendor_id)
        ->first();
        if($vendorBankAccount)
        {
            return response()->json(['data' => $vendorBankAccount], 200);
        }
        else
        {
            return response()->json(['message' => 'No Account', 'data'=> null], 200);
        }
    }
    public function UpdateVendorAccount(Request $request)
    {
        if(!$this->vendor_id)
        {
            return response()->json(['message' => 'Invalid Vendor Account'], 401);
        }
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'company_name' => 'required|string|max:255',
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'tin' => 'nullable|string|max:20',
        ]);
        $vendor = Vendor::where('id', $this->vendor_id)->first();
        if($vendor)
        {
            $vendor->update($validated);
            return response()->json(['message' =>  $vendor], 200);
        }
        else
        {
            return response()->json(['message' => 'Invalid Vendor Account'], 401);
        }
    }

    public function searchClient(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);
        $vendorId = $this->vendor_id;
        $vendor = Vendor::where('id', $vendorId)->first();
        if($vendor)
        {
            $clients = Client::whereRaw('LOWER(first_name) LIKE ?', ['%' . strtolower($validated['first_name']) . '%'])
            ->whereRaw('LOWER(last_name) LIKE ?', ['%' . strtolower($validated['last_name']) . '%'])
            ->whereNotIn('id', function ($query) use ($vendorId) {
                $query->select('client_id')
                    ->from('vendor_enrolled_accounts')
                    ->where('vendor_id', $vendorId);
            })
            ->get();
            return response()->json(['clients' => $clients], 200);    
        }
        else
        {
            return response()->json(['message' => 'Invalid Vendor Account'], 401);
        }
    }

    public function clientEnrollment(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
        ]);
        $vendor = Vendor::find($this->vendor_id);
        if (!$vendor) {
            return response()->json(['message' => 'Invalid Vendor Account'], 401);
        }
        $enrollment = VendorEnrolledAccount::create([
            'vendor_id' => $this->vendor_id,
            'client_id' => $validated['client_id'],
            'enrolled_at' => now(),
            'status' => 'active', 
        ]);
        return response()->json(['message' => 'Client enrolled successfully!', 'enrollment' => $enrollment], 201);
        
    }
    public function getEnrolledAccounts(Request $request)
    {
        $vendorId = $this->vendor_id;
        $enrolledAccounts = VendorEnrolledAccount::with('client')
            ->where('vendor_id', $vendorId)
            ->get()
            ->map(function ($account) {
                return [
                    'id' => $account->client->id,
                    'first_name' => $account->client->first_name,
                    'last_name' => $account->client->last_name,
                ];
            });

        return response()->json(['accounts' => $enrolledAccounts]);
    }
    public function fetchClientEnrolledAccounts(Request $request)
    {
        $vendorId = $this->vendor_id;
        $enrolledAccounts = VendorEnrolledAccount::with('client')
            ->where('vendor_id', $vendorId)
            ->with('client')
            ->get()
            ->map(function ($account) {
                return [
                    'id' => $account->client->id,
                    'first_name' => $account->client->first_name,
                    'last_name' => $account->client->last_name,
                ];
            });
        return response()->json(['accounts' => $enrolledAccounts], 200);
    }

    public function fetchW9Form()
    {
        // $this->vendor_id = 5;
        if (!$this->vendor_id) {
            return response()->json(['message' => 'Invalid Vendor Account'], 401);
        }

        $vendor = Vendor::find($this->vendor_id);
        if (!$vendor ) {
            return response()->json(['message' => 'Invalid Vendor Account'], 401);
        }
        if (!$vendor->signature) {
            return response()->json(['message' => 'Signature not found'], 401);
        }
      
        $pdf = new Fpdi();
        
        $pageCount = $pdf->setSourceFile(public_path('w9fileform.pdf'));
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $template = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($template);
        
            
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($template);
        
            
            if ($pageNo === 1) {
                
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetXY(25, 44);
                $pdf->Write(0, $vendor->company_name);
                $pdf->SetXY(25, 83);
                $pdf->Write(0, "X");
                $pdf->SetXY(25, 103);
                $pdf->Write(0, $vendor->address_1 . ' ' . $vendor->address_2);
                $pdf->SetXY(25, 112);
                $pdf->Write(0,  $vendor->city . '       ' . $vendor->state . '          ' . $vendor->zip);
                $tinParts = str_split($vendor->tin);
                $pdf->SetFont('Helvetica', '', 18);
                $pdf->SetXY(147, 153);
                $pdf->Write(0,  $tinParts[0].' '.$tinParts[1]);
                $pdf->SetXY(162, 153);
                $pdf->Write(0,  $tinParts[2].' '.$tinParts[3].' '.$tinParts[4].' '.$tinParts[5].' '.$tinParts[6].' '.$tinParts[7].' '.$tinParts[8]);
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->SetXY(143, 209);
                $pdf->Write(0,  $vendor->signature_date ?? "");
                
                
                $signature = json_decode($vendor->signature);
                if (filter_var($vendor->signature, FILTER_VALIDATE_URL)) {
                    $pdf->Image($vendor->signature, 40, 193, 30, 30);
                } else {
                    $commonFonts = [
                        "Alex Brush, cursive" => "AlexBrush-Regular.ttf",
                        "Mr Dafoe, cursive" => "MrDafoe-Regular.ttf",
                        "MrDeHaviland, cursive" => "MrDeHaviland-Regular.ttf",
                        "PWSimpleScript, cursive" => "pwsimplescript.ttf"
                    ];
                    
                    // self::fontGenerate($commonFonts[$signature->style->fontFamily]);
                    
                    $fontFamily = pathinfo($commonFonts[$signature->style->fontFamily], PATHINFO_FILENAME);
                    
                    
                    $pdf->AddFont($fontFamily, '', $fontFamily.'.php');
                    $fontWeight = isset($signature->style->fontWeight) ? $signature->style->fontWeight : '';
                    $color = isset($signature->style->color) ? $signature->style->color : '#000000';
                    $pdf->SetFont($fontFamily, '', 18);
                    $pdf->SetTextColor(hexdec(substr($color, 1, 2)), hexdec(substr($color, 3, 2)), hexdec(substr($color, 5, 2)));
                    $pdf->SetXY(45, 208);
                    $pdf->Write(0, $signature->signature); 
                }
            }
        }

        return response($pdf->Output('S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="filled_w9_form.pdf"'
        ]);
    }

    public function fontGenerate($fontFileTTF)
    {
        $rawFontPath = public_path('fonts/'.$fontFileTTF);  
        $filenameWithoutExtension = pathinfo($rawFontPath, PATHINFO_FILENAME);  
        $publicFontPath = public_path('fonts/fpdf');  

        if (file_exists($rawFontPath)) {
            
            if (!file_exists($publicFontPath . '/' . $filenameWithoutExtension . '.php')) {
                
                
                if (!is_dir($publicFontPath)) {
                    mkdir($publicFontPath, 0777, true);  
                }
                require_once base_path('vendor/fpdf/fpdf/src/Fpdf/makefont/makefont.php');
                
                MakeFont($rawFontPath, 'cp1252');  
            }
        }
    }
}
