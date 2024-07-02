<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ClientsController;
use App\Http\Controllers\Admin\EmployeesController;
use App\Http\Controllers\Admin\TimesheetsController;
use App\Http\Controllers\Admin\ResourcesController;
use App\Http\Controllers\Admin\ServiceCoordinatorController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceCodeController;
use App\Http\Controllers\Admin\ClientSpendingPlanController;
use App\Http\Controllers\Admin\ClientResouceController;
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
    #admin
    Route::get('/register_admin', [AdminRegisterController::class, 'register_admin'])->name('register_admin');
    Route::post('/create_admin', [AdminRegisterController::class, 'create_admin'])->name('create_admin');

    #configuration
    #service category
    Route::get('/service_category', [ServiceCategoryController::class, 'index'])->name('service_category');
    Route::post('/create_category', [ServiceCategoryController::class, 'create'])->name('create_category');
    Route::get('/service-categories', [ServiceCategoryController::class, 'getCategories']);
    Route::delete('/service-categories/{id}', [ServiceCategoryController::class, 'destroy'])->name('service-categories.destroy');
    Route::put('/service-categories/{id}', [ServiceCategoryController::class, 'update'])->name('service-categories.update');
    #service_code
    Route::get('/service_code', [ServiceCodeController::class, 'index'])->name('service_code');
    Route::resource('service-codes', ServiceCodeController::class);
    Route::get('service-codes/{id}/edit', [ServiceCodeController::class, 'edit']);

    #spending plan
    Route::resource('client-spending-plan', ClientSpendingPlanController::class);
    Route::get('client-spending-plan/{id}/download', [ClientSpendingPlanController::class, 'download'])->name('client-spending-plan.download');



    #Clients
    Route::get('/clients', [ClientsController::class, 'view_all'])->name('clients');
    Route::get('/fetch_clients', [ClientsController::class, 'fetch_clients'])->name('fetch_clients');
    Route::post('/delete_client', [ClientsController::class, 'delete_client'])->name('delete_client');
    Route::post('/approve_client', [ClientsController::class, 'approve_client'])->name('approve_client');

    #Employees
    Route::get('/employees', [EmployeesController::class, 'view_all'])->name('employees');
    Route::post('/fetch_employees', [EmployeesController::class, 'fetch_employees'])->name('fetch_employees');
    Route::post('/update_employee', [EmployeesController::class, 'update_employee'])->name('update_employee');

    #Payables
    Route::get('/payables', [PayablesController::class, 'fetch_payables_all'])->name('payables');
    Route::get('/fetch_payables', [PayablesController::class, 'fetch_payables'])->name('fetch_payables');
    Route::post('/update_payables', [PayablesController::class, 'update_payables'])->name('update_payables');

    #Payroll
    Route::get('/payroll', [PayrollController::class, 'fetch_payroll_all'])->name('payroll');
    Route::post('/update_payroll', [PayrollController::class, 'update_payroll'])->name('update_payroll');
    Route::post('/create_payroll', [PayrollController::class, 'create_payroll'])->name('create_payroll');
    
    #timesheet
    Route::get('/timesheets', [TimesheetsController::class, 'timesheets'])->name('timesheets');

    #resources
    Route::get('/resources', [ResourcesController::class, 'resources'])->name('resources');
    Route::post('/add_resources', [ResourcesController::class, 'add_resources'])->name('add_resources');
    Route::post('/delete_resources', [ResourcesController::class, 'delete_resources'])->name('delete_resources');

    #client files
    Route::get('/client_resources', [ClientResouceController::class, 'index'])->name('client_resources');

    #vendor
    Route::get('/vendors', [VendorController::class, 'fetch_all_vendors'])->name('vendors');
    Route::post('/change_vendor_status', [VendorController::class, 'change_vendor_status'])->name('change_vendor_status');
    Route::get('/vendor_invoice', [VendorController::class, 'vendor_invoice'])->name('vendor_invoice');
    Route::post('/update_vendor_invoice', [VendorController::class, 'update_vendor_invoice'])->name('update_vendor_invoice');
    Route::post('/delete_vendor_invoice', [VendorController::class, 'delete_vendor_invoice'])->name('delete_vendor_invoice');

    #Coordinator
    Route::get('/service_coordinator_accounts', [ServiceCoordinatorController::class, 'fetch_all_service_coordinator_accounts'])->name('service_coordinator_accounts');
    Route::post('/change_coordinator_status', [ServiceCoordinatorController::class, 'change_coordinator_status'])->name('change_coordinator_status');

    #reports
    Route::get('/fetch_all_reports', [ReportsController::class, 'fetch_all_reports'])->name('fetch_all_reports');
    Route::post('/upload_report', [ReportsController::class, 'upload_report'])->name('upload_report');
    Route::post('/delete_report', [ReportsController::class, 'delete_report'])->name('delete_report');

});
Route::post('/logout_session', [LogoutController::class, 'logout'])->name('logout_session');
Route::post('/login_verify', [LogoutController::class, 'login_verify'])->name('login_verify');
Route::post('/otp_verify', [LogoutController::class, 'otp_verify'])->name('otp_verify');
// Redirect to the login page if the user is not logged in
Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('home');
    });
    
    Auth::routes();
});


