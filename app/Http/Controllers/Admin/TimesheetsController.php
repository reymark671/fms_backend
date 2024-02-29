<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timesheet;

class TimesheetsController extends Controller
{
    //
    public function timesheets(Request $request)
    {
        $timesheets = Timesheet::with('employee')->with('client')->get();
        return view('pages.timesheets',['timesheets' => $timesheets]);

    }
}
