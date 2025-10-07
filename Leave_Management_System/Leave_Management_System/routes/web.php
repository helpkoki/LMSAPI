<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SoftwareController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\LicensesController;
use App\Http\Controllers\ViewerDashboardController;
use App\Http\Controllers\ViewerLicenseController;
use App\Http\Controllers\Viewer\ViewerController;

use App\Http\Controllers\SignupController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\EmployeeController;

use App\Http\Controllers\Admin\LeaveRequestController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\ReportsController;


// Public welcome route
Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function() {
    Route::get('/policies', function () {
        return view('policies');
    })->name('policies');
});

Route::get('/signup', [SignupController::class, 'showSignupForm'])->name('signup');
Route::post('/signup', [SignupController::class, 'signup'])->name('signup.post');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');


Route::get('/policies', [PolicyController::class, 'show'])->name('policies');
    Route::get('/policies/download', [PolicyController::class, 'downloadPdf'])->name('policies.download');

Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout');



// calendar main page


// show leaves on clicked day
Route::get('admin/leaves/{year}/{month}/{day}', function($year, $month, $day) {
    $date = \Carbon\Carbon::create($year, $month, $day)->format('Y-m-d');
    $leaves = \App\Models\LeaveRequest::whereDate('start_date', '<=', $date)
        ->whereDate('end_date', '>=', $date)
        ->with('user')->get();
    return view('admin.leaves.byday', compact('leaves', 'date'));
})->name('admin.leaves.byday');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
});
Route::get('/dashboard', [LeaveController::class, 'dashboard'])
    ->middleware('auth')->name('dashboard');

// Leave application page
Route::get('/apply', [LeaveController::class, 'applyForm'])
    ->middleware('auth')->name('apply');

// Handle leave application form submit
Route::post('/apply-for-leave', [LeaveController::class, 'submit'])
    ->middleware('auth')->name('leave.submit');

// Example stubs for additional routes visible in sidebar:
Route::get('/reports', function() { return view('reports'); })->name('reports');
Route::get('/policies', function() { return view('policies'); })->name('policies');
Route::get('/settings', function() { return view('settings'); })->name('settings');

Route::get('/leave/my-requests', [LeaveController::class, 'myRequests'])->name('leave.myRequests');
Route::patch('/leave/withdraw/{id}', [LeaveController::class, 'withdraw'])->name('leave.withdraw');

Route::get('/leave/my-requests', [LeaveController::class, 'myRequests'])->name('leave.myRequests');

Route::get('/settings', [AuthController::class, 'edit'])->name('settings.edit')->middleware('auth');
Route::post('/settings', [AuthController::class, 'update'])->name('settings.update')->middleware('auth');

Route::get('/leave/status/{status}', [LeaveController::class, 'statusLeaves'])->name('leave.status');

Route::middleware(['web', 'auth']) // add whatever middleware you want
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('employees', [EmployeeController::class, 'index'])->name('employees');
        // other admin routes here
    });



Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Route::get('/requests', [LeaveRequestController::class, 'index'])->name('requests');
   Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
   Route::resource('admin/employees', EmployeeController::class)->only(['index', 'destroy']);
   

 Route::get('employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings');
     Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
     
   
    Route::get('calendar/events', [CalendarController::class, 'events'])->name('calendar.events');

 // route in web.php


    // Add other admin routes here as needed.
});
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login.post');
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
    });
});


