<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ClientsController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\PayrollController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\PayablesController;
use App\Http\Controllers\API\VendorController;
use App\Http\Controllers\API\CoordinatorController;
use App\Http\Controllers\API\ResourcesController;
use App\Http\Controllers\API\HiredEmployeesController;
use App\Http\Controllers\EmailSender;

/*
|--------------------------------------------------------------------------
| API Routes 
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/create_client', [ClientsController::class, 'register_client']);
Route::post('/add_payable', [PayablesController::class, 'upload_payable']);
Route::post('/fetch_payable', [PayablesController::class, 'fetch_payables']);
Route::post('/timesheet_upload', [PayrollController::class, 'timesheet_upload']);
Route::post('/fetch_payroll', [PayrollController::class, 'fetch_payroll'])->name('api/fetch_payroll');
Route::post('/send_email_cli', [EmailSender::class, 'send_email_cli'])->name('send_email_cli');
Route::post('/fetch_payroll_api', [PayrollController::class, 'fetch_payroll_api'])->name('fetch_payroll_api');

#login_client
Route::post('/login_auth', [LoginController::class, 'login'])->name('api/login_auth');
Route::post('/otp_verification', [LoginController::class, 'otp_verification'])->name('otp_verification');
Route::post('/reset_password_client', [LoginController::class, 'reset_password_client'])->name('reset_password_client');
Route::post('/change_password_client', [LoginController::class, 'change_password_client'])->name('change_password_client');

#employee 
Route::post('/add_employee', [EmployeeController::class, 'add_employee']);
Route::post('/fetch_employees', [EmployeeController::class, 'fetch_employees'])->name('fetch_employees');
Route::post('/timesheets_client', [EmployeeController::class, 'timesheets_client'])->name('timesheets_client');
Route::post('/decline_timesheet', [EmployeeController::class, 'decline_timesheet'])->name('decline_timesheet');
Route::post('/sign_in', [EmployeeController::class, 'sign_in'])->name('sign_in');
Route::post('/timesheet_entry', [EmployeeController::class, 'timesheet_entry'])->name('timesheet_entry');
Route::post('/timesheets', [EmployeeController::class, 'timesheets'])->name('timesheets');
Route::get('/client_fetch', [EmployeeController::class, 'client_fetch'])->name('client_fetch');
Route::post('/time_in', [EmployeeController::class, 'time_in'])->name('time_in');
Route::post('/time_out', [EmployeeController::class, 'time_out'])->name('time_out');
Route::get('/check_time_in', [EmployeeController::class, 'check_time_in'])->name('check_time_in');

#Hired Employees
Route::post('/fetch_available_employees', [HiredEmployeesController::class, 'fetch_available_employees'])->name('fetch_available_employees');
Route::post('/hired_employee', [HiredEmployeesController::class, 'hired_employee'])->name('hired_employee');
Route::post('/fetch_client_employee', [HiredEmployeesController::class, 'fetch_client_employee'])->name('fetch_client_employee');
Route::post('/terminate_employee', [HiredEmployeesController::class, 'terminate_employee'])->name('terminate_employee');

// employees login API 
Route::post('/send_email_emp', [EmailSender::class, 'send_email_emp'])->name('send_email_emp');
Route::post('/otp_verification_emp', [LoginController::class, 'otp_verification_emp'])->name('otp_verification_emp');
Route::post('/reset_password_employee', [LoginController::class, 'reset_password_employee'])->name('reset_password_employee');
Route::post('/change_password_employee', [LoginController::class, 'change_password_employee'])->name('change_password_employee');
Route::post('/test_image_s3', [LoginController::class, 'test_image_s3'])->name('test_image_s3');

#vendors controller
Route::post('/create_vendor_account', [VendorController::class, 'create_vendor_account'])->name('create_vendor_account');
Route::post('/vendor_sign_in', [VendorController::class, 'vendor_sign_in'])->name('vendor_sign_in');
Route::post('/vendor_verify_otp', [VendorController::class, 'vendor_verify_otp'])->name('vendor_verify_otp');
Route::post('/reset_password_vendor', [VendorController::class, 'reset_password_vendor'])->name('reset_password_vendor');
Route::post('/upload_invoice', [VendorController::class, 'upload_invoice'])->name('upload_invoice');
Route::post('/fetch_invoices_vendor', [VendorController::class, 'fetch_invoices_vendor'])->name('fetch_invoices_vendor');
Route::post('/change_password_vendor', [VendorController::class, 'change_password_vendor'])->name('change_password_vendor');


#coordinators API
Route::post('/create_coordinator_account', [CoordinatorController::class, 'create_coordinator_account'])->name('create_coordinator_account');
Route::post('/coordinator_sign_in', [CoordinatorController::class, 'coordinator_sign_in'])->name('coordinator_sign_in');
Route::post('/coordinator_verify_otp', [CoordinatorController::class, 'coordinator_verify_otp'])->name('coordinator_verify_otp');
Route::post('/fetch_reports', [CoordinatorController::class, 'fetch_reports'])->name('fetch_reports');

#resources
Route::get('/fetch_resources', [ResourcesController::class, 'fetch_resources'])->name('fetch_resources');
Route::post('/upload_files', [ResourcesController::class, 'store_file'])->name('upload_files');
Route::post('/fetch_file_uploads', [ResourcesController::class, 'fetch_store_file'])->name('fetch_file_uploads');


