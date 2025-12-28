<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;

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

// Public routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
});

Route::middleware('web')->post('/login', [AuthController::class, 'login'])->name('login.post');

// Protected routes - require authentication
Route::middleware(['web', 'supabase.auth'])->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        
        // Multi-step report creation - PERBAIKAN DI SINI
        Route::get('/create/step1', [ReportController::class, 'createStep1'])->name('create-step1');
        Route::post('/create/step2', [ReportController::class, 'createStep2'])->name('create-step2');
        Route::post('/create/step3', [ReportController::class, 'createStep3'])->name('create-step3');
        
        // PERUBAHAN: Route step4 menerima GET dan POST
        Route::match(['get', 'post'], '/create/step4', [ReportController::class, 'createStep4'])->name('create-step4');
        
        Route::post('/store', [ReportController::class, 'store'])->name('store');
        
        // Single report operations
        Route::get('/{id}', [ReportController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ReportController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ReportController::class, 'update'])->name('update');
        Route::delete('/{id}', [ReportController::class, 'destroy'])->name('destroy');
    });
});