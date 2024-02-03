<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ClientsController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\PayrollController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\PayablesController;

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
Route::post('/timesheet_upload', [PayrollController::class, 'timesheet_upload']);
Route::post('/fetch_payroll', [PayrollController::class, 'fetch_payroll'])->name('api/fetch_payroll');
Route::post('/login_auth', [LoginController::class, 'login'])->name('api/login_auth');
Route::post('/fetch_employees', [EmployeeController::class, 'fetch_employees'])->name('fetch_employees');
