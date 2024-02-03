<?php

namespace App\Http\Controllers;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class PayrollController extends Controller
{
    //
    public function fetch_payroll_all()
    {
        return view('pages.payroll');
    }
    public function update_payroll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payroll_id' => 'required|exists:payroll,id',
            'payroll_file.*' => 'required|file|mimes:jpeg,png,pdf,jpg|max:20048',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $filePaths = [];
    
        foreach ($request->file('payroll_file') as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/payroll/payroll_sheet/'), $fileName);
            $filePaths[] = 'uploads/payroll/payroll_sheet/' . $fileName;
        }
    
        $concatenatedFileDir = implode('|', $filePaths);
        $payroll = Payroll::find($request->input('payroll_id'));
        if ($payroll) {
            $payroll->update([
                'payroll_file' => $concatenatedFileDir,
            ]);
        } else {
            return response()->json(['errors' => "payroll not found"], 422);
        }
        return response()->json(['data' => $payroll]);

    }
}
