<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ClientsController;
use App\Http\Controllers\Admin\EmployeesController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\PayablesController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\AdminRegisterController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Redirect to the dashboard if the user is logged in
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('home');
    });
    
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/register_admin', [AdminRegisterController::class, 'register_admin'])->name('register_admin');
    Route::post('/create_admin', [AdminRegisterController::class, 'create_admin'])->name('create_admin');
    Route::get('/clients', [ClientsController::class, 'view_all'])->name('clients');
    Route::get('/fetch_clients', [ClientsController::class, 'fetch_clients'])->name('fetch_clients');
    Route::post('/delete_client', [ClientsController::class, 'delete_client'])->name('delete_client');
    Route::post('/approve_client', [ClientsController::class, 'approve_client'])->name('approve_client');
    Route::get('/employees', [EmployeesController::class, 'view_all'])->name('employees');
    Route::post('/fetch_employees', [EmployeesController::class, 'fetch_employees'])->name('fetch_employees');
    Route::get('/payables', [PayablesController::class, 'fetch_payables_all'])->name('payables');
    Route::get('/fetch_payables', [PayablesController::class, 'fetch_payables'])->name('fetch_payables');
    Route::post('/update_payables', [PayablesController::class, 'update_payables'])->name('update_payables');
    Route::get('/payroll', [PayrollController::class, 'fetch_payroll_all'])->name('payroll');
    Route::post('/update_payroll', [PayrollController::class, 'update_payroll'])->name('update_payroll');
    Route::post('/create_payroll', [PayrollController::class, 'create_payroll'])->name('create_payroll');

});
Route::post('/logout_session', [LogoutController::class, 'logout'])->name('logout_session');
// Redirect to the login page if the user is not logged in
Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('home');
    });
    
    Auth::routes();
});


