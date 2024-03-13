<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ClientsController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\PayrollController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\PayablesController;
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
Route::post('/add_employee', [EmployeeController::class, 'add_employee']);
Route::post('/add_payable', [PayablesController::class, 'upload_payable']);
Route::post('/fetch_payable', [PayablesController::class, 'fetch_payables']);
Route::post('/timesheet_upload', [PayrollController::class, 'timesheet_upload']);
Route::post('/fetch_payroll', [PayrollController::class, 'fetch_payroll'])->name('api/fetch_payroll');
Route::post('/login_auth', [LoginController::class, 'login'])->name('api/login_auth');
Route::post('/fetch_employees', [EmployeeController::class, 'fetch_employees'])->name('fetch_employees');
Route::post('/timesheets_client', [EmployeeController::class, 'timesheets_client'])->name('timesheets_client');
Route::post('/decline_timesheet', [EmployeeController::class, 'decline_timesheet'])->name('decline_timesheet');
Route::post('/send_email_cli', [EmailSender::class, 'send_email_cli'])->name('send_email_cli');
Route::post('/otp_verification', [LoginController::class, 'otp_verification'])->name('otp_verification');
Route::post('/fetch_payroll_api', [PayrollController::class, 'fetch_payroll_api'])->name('fetch_payroll_api');
Route::post('/reset_password_client', [LoginController::class, 'reset_password_client'])->name('reset_password_client');
Route::post('/change_password_client', [LoginController::class, 'change_password_client'])->name('change_password_client');

// employees API 
Route::post('/sign_in', [EmployeeController::class, 'sign_in'])->name('sign_in');
Route::post('/timesheet_entry', [EmployeeController::class, 'timesheet_entry'])->name('timesheet_entry');
Route::post('/timesheets', [EmployeeController::class, 'timesheets'])->name('timesheets');
Route::post('/send_email_emp', [EmailSender::class, 'send_email_emp'])->name('send_email_emp');
Route::post('/otp_verification_emp', [LoginController::class, 'otp_verification_emp'])->name('otp_verification_emp');
Route::post('/reset_password_employee', [LoginController::class, 'reset_password_employee'])->name('reset_password_employee');
Route::post('/change_password_employee', [LoginController::class, 'change_password_employee'])->name('change_password_employee');
Route::post('/test_image_s3', [LoginController::class, 'test_image_s3'])->name('test_image_s3');


