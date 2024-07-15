<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Http\Requests\Employees\UpdateEmployeesRequest;
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
    public function fetch_employees_data()
    {
        $employees = Employee::select('id', 'first_name', 'last_name','email')->get();
        return response()->json($employees );
    }
    public function update_employee(UpdateEmployeesRequest $data)
    {
        $employee = Employee::find($data->input('id'));
        if (!$employee) {
            return response()->json(['error' => 'Client not found'], 404);
        }
        $fieldsToUpdate = [
            'service_code', 'first_name', 'last_name', 'SP_number', 
            'phone_number', 'email', 'Username', 'password', 'Status'
        ];
    
        foreach ($fieldsToUpdate as $field) {
            if ($data->has($field)) {
                $employee->$field = $data->input($field);
            }
        }
    
        $employee->save();
        return response()->json(['message' => 'Updated successfully']);
    }
}
