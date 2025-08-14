<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\RoomController;
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
    
    // Dashboard
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    
    // User Management Routes
    Route::resource('users', UserController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
    
    // Floor Management
    Route::resource('floors', FloorController::class);
    
    // Room Management
    Route::resource('rooms', RoomController::class);
    
    // Device Management
    Route::resource('devices', DeviceController::class);
    
    // Checklist Items Management
    Route::resource('checklist-items', ChecklistItemController::class);

    Route::get('/device-check-results', [DeviceCheckResultController::class, 'webIndex'])->name('device-check-results.index');
    
    // Device Checking Routes
    Route::prefix('check')->name('check.')->group(function () {
        Route::get('/', [ChecklistItemController::class, 'index'])->name('index');
        Route::get('/floor/{floor}', [ChecklistItemController::class, 'showFloorRooms'])->name('floor');
        Route::get('/room/{room}', [ChecklistItemController::class, 'showRoomDevices'])->name('room');
        Route::get('/device/{device}', [ChecklistItemController::class, 'showDeviceChecklist'])->name('device');
        Route::post('/device/{device}', [ChecklistItemController::class, 'storeCheckResult'])->name('store');
    });
    
    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ChecklistItemController::class, 'reports'])->name('index');
        Route::get('/device/{device}', [ChecklistItemController::class, 'deviceReport'])->name('device');
        Route::get('/export', [ChecklistItemController::class, 'exportReport'])->name('export');
    });
    
    // API Routes untuk AJAX
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/rooms/{floor}', [RoomController::class, 'getByFloor'])->name('rooms.by-floor');
        Route::get('/devices/{room}', [DeviceController::class, 'getByRoom'])->name('devices.by-room');
        Route::get('/checklist/{deviceType}', [ChecklistItemController::class, 'getByDeviceType'])->name('checklist.by-type');
    });
    
    // // Admin only routes
    // Route::middleware('admin')->group(function () {
    //     Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    //     Route::post('/users', [UserController::class, 'store'])->name('users.store');
    //     Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    //     Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    //     Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    // });
});

// Custom middleware untuk admin
Route::middleware(['auth'])->group(function () {
    // Routes yang bisa diakses semua authenticated users
});