<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
class EmployeesController extends Controller
{
    //
    public function view_all()
    {
        
        $employees = Employee::with('client')->get();
        return view('pages.employees',['employees' => $employees]);
    }
    public function fetch_employees(Request $data)
    {
        $client_id = $data->input('client_id');
        $employees = Employee::where('client_id', $client_id)->get();
        return response()->json(['data' => $employees]);
    }
}
