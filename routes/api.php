<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReportApiController;
use App\Http\Controllers\Api\AuthApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 1. Route Login & Logout (PENTING: Jangan lupa middleware session di Kernel.php yang saya bilang sebelumnya)
Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/logout', [AuthApiController::class, 'logout']);

// 2. Public Data
Route::prefix('reports')->group(function () {
    Route::get('buildings', [ReportApiController::class, 'getBuildings']);
    Route::get('buildings/{building_id}/rooms', [ReportApiController::class, 'getRooms']);
    Route::get('facilities', [ReportApiController::class, 'getFacilities']);
});

// 3. Protected Routes (CRUD Laporan) <--- INI YANG KAMU LEWATKAN
Route::middleware('api')->prefix('reports')->group(function () {
    // GET /api/reports (List laporan)
    Route::get('/', [ReportApiController::class, 'index']); 
    
    // POST /api/reports (Buat laporan) <--- INI SOLUSI EROR 404 KAMU
    Route::post('/', [ReportApiController::class, 'store']); 
    
    // Detail, Update, Delete
    Route::get('/{id}', [ReportApiController::class, 'show']);
    Route::post('/{id}', [ReportApiController::class, 'update']); // Pakai POST untuk update file
    Route::delete('/{id}', [ReportApiController::class, 'destroy']);
});