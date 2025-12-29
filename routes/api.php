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

// 1. Route Login & Logout 
Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/logout', [AuthApiController::class, 'logout']);

// 2. Public Data
Route::prefix('reports')->group(function () {
    Route::get('buildings', [ReportApiController::class, 'getBuildings']);
    Route::get('buildings/{building_id}/rooms', [ReportApiController::class, 'getRooms']);
    Route::get('facilities', [ReportApiController::class, 'getFacilities']);
});

Route::middleware(['api', 'supabase.auth'])->prefix('reports')->group(function () {
    // GET /api/reports (List laporan)
    Route::get('/', [ReportApiController::class, 'index']);

    // POST /api/reports (Buat laporan)
    Route::post('/', [ReportApiController::class, 'store']);

    // Detail, Update, Delete
    Route::get('/{id}', [ReportApiController::class, 'show']);
    Route::post('/{id}', [ReportApiController::class, 'update']); // Pake POST buat update file
    Route::delete('/{id}', [ReportApiController::class, 'destroy']);
    Route::get('/{id}/edit', [ReportApiController::class, 'editData']);
});
