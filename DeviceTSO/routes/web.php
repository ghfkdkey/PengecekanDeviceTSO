<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\RegionalController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\ChecklistItemController;
use App\Http\Controllers\DeviceCheckResultController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Protected Routes (require authentication)
Route::middleware('auth')->group(function () {
    // Dashboard - semua role bisa akses
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    // Area Management
    Route::resource('areas', AreaController::class);

    // Regional Management 
    Route::resource('regionals', RegionalController::class);

    // Building Management
    Route::resource('buildings', BuildingController::class);

    // Floor Management
    Route::resource('floors', FloorController::class);

    // Room Management
    Route::resource('rooms', RoomController::class);

    // Device Management
    Route::resource('devices', DeviceController::class);

    // Checklist Management
    Route::resource('checklist-items', ChecklistItemController::class);

    // User Management
    Route::resource('users', UserController::class);

    // Device Check
    Route::get('/device-check', [DeviceCheckResultController::class, 'index'])->name('device-check.page');
    Route::post('/device-check', [DeviceCheckResultController::class, 'store'])->name('device-check.store');

    // User Management API Routes
    Route::prefix('api')->group(function () {
        Route::get('/users', [UserController::class, 'apiIndex']);
        Route::get('/users/{id}', [UserController::class, 'apiShow']);
        Route::post('/users', [UserController::class, 'apiStore']);
        Route::put('/users/{id}', [UserController::class, 'apiUpdate']);
        Route::delete('/users/{id}', [UserController::class, 'apiDestroy']);
        
        // Regional dropdown data for user form
        Route::get('/regionals', [RegionalController::class, 'apiIndex']);
    });
});