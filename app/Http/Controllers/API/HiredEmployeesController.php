<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HiredEmployees;
use App\Models\Employee;
class HiredEmployeesController extends Controller
{
    //
    public function fetch_available_employees(Request $request)
    {
        $token = $request->input('token');
       
        $verification_Details = explode("$", $token);
        $client_id = $verification_Details[1];
        $token = $verification_Details[0];
        $registeredEmployeeIds = HiredEmployees::where('client_id', $client_id)->pluck('employee_id');
        $availableEmployees = Employee::whereNotIn('id', $registeredEmployeeIds)
            ->select('id', 'first_name', 'last_name', 'SP_number','email','phone_number') 
            ->get();
        return response()->json(['data' => $availableEmployees, 'status' => 200], 200);
    }

    public function hired_employee(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'position' => 'required|string|max:255',
            'hired_date' => 'required|date',
            'token' => 'required|string|max:255',
        ]);
        $token = $request->input('token');
        $verification_Details = explode("$", $token);
        $client_id = $verification_Details[1];
        $token = $verification_Details[0];
        $hiredEmployee = HiredEmployees::create([
            'client_id' => $client_id, 
            'employee_id' => $request->employee_id,
            'position' => $request->position,
            'hired_date' => $request->hired_date,
            'separation_date' => $request->separation_date,
        ]);
        return response()->json(['message' => 'Employee hired successfully', 'data' => $hiredEmployee, 'status' => 200], 200);

    }
    public function fetch_client_employee(Request $request)
    {
        $token = $request->input('token');
       
        $verification_Details = explode("$", $token);
        $client_id = $verification_Details[1];
        $token = $verification_Details[0];
        $registeredEmployees = HiredEmployees::with('employee')
            ->where('client_id', $client_id)
            ->get();
        return response()->json(['message' => 'data_fetched', 'data' => $registeredEmployees, 'status' => 200], 200);
    }
    public function terminate_employee(Request $request)
    {
        $token = $request->input('token');
        $employee_id = $request->input('employee_id');
        
        $verification_Details = explode("$", $token);
        $client_id = $verification_Details[1];
        $token = $verification_Details[0];
        $id=$request->input('id');
        $hiredEmployee = HiredEmployees::find($id);
        if ($hiredEmployee) {
            $hiredEmployee->separation_date = now();
            $hiredEmployee->save();

            return response()->json(['message' => 'Employee terminated successfully.', 'status' => 200], 200);
        } else {
            return response()->json(['message' => 'Employee not found.', 'status' => 404], 404);
        }
    }
}
