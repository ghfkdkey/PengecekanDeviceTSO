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

    // Device Check Routes
    Route::get('/device-check', [DeviceCheckResultController::class, 'deviceCheckPage'])->name('device-check.page');
    Route::post('/device-check', [DeviceCheckResultController::class, 'store'])->name('device-check.store');

    // Device Check Results Routes  
    Route::get('/device-check-results', [DeviceCheckResultController::class, 'index'])->name('device-check-results.index');

    // API Routes for Device Check
    Route::get('/api/rooms/{floorId}', [DeviceCheckResultController::class, 'getRoomsByFloor']);
    Route::get('/api/devices/{roomId}', [DeviceCheckResultController::class, 'getDevicesByRoom']);
    Route::get('/api/checklist/{deviceType}', [DeviceCheckResultController::class, 'getChecklistByDeviceType']);
    Route::post('/api/device-check-results/multiple', [DeviceCheckResultController::class, 'storeMultipleResults']);

    Route::get('/api/test', function() {
        return response()->json(['message' => 'API routes are working', 'timestamp' => now()]);
    });

    Route::get('/api/device-check-sessions', function() {
        try {
            \Log::info('API route /api/device-check-sessions was hit');
            $controller = new \App\Http\Controllers\DeviceCheckResultController();
            return $controller->listLatestPerDevice();
        } catch (\Exception $e) {
            \Log::error('Error in /api/device-check-sessions: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });

    Route::get('/api/device-check-session-detail', [DeviceCheckResultController::class, 'sessionDetail']);
    Route::get('/api/device-check-results', [DeviceCheckResultController::class, 'apiIndex']);
    Route::put('/api/device-check-results/{id}', [DeviceCheckResultController::class, 'update']);
    Route::delete('/api/device-check-results/{id}', [DeviceCheckResultController::class, 'destroy']);

    Route::get('/api/devices', function() {
        return \App\Models\Device::with('room.floor')->get();
    });
    Route::get('/api/checklist-items', function() {
        return \App\Models\ChecklistItem::all();
    });
    Route::get('/api/users', function() {
        return \App\Models\User::all();
    });


    // Device Check Results Routes  
    Route::get('/device-check-results', [DeviceCheckResultController::class, 'index'])->name('device-check-results.index');

    // API Routes for Device Check
    Route::get('/api/rooms/{floorId}', [DeviceCheckResultController::class, 'getRoomsByFloor']);
    Route::get('/api/devices/{roomId}', [DeviceCheckResultController::class, 'getDevicesByRoom']);
    Route::get('/api/checklist/{deviceType}', [DeviceCheckResultController::class, 'getChecklistByDeviceType']);
    Route::post('/api/device-check-results/multiple', [DeviceCheckResultController::class, 'storeMultipleResults']);

    // User Management API Routes
    Route::prefix('api')->group(function () {
        Route::get('/users', [UserController::class, 'apiIndex']);
        Route::get('/users/{id}', [UserController::class, 'apiShow']);
        Route::post('/users', [UserController::class, 'apiStore']);
        Route::put('/users/{id}', [UserController::class, 'apiUpdate']);
        Route::delete('/users/{id}', [UserController::class, 'apiDestroy']);
        
        // Regional dropdown data for user form
        Route::get('/regionals', [RegionalController::class, 'apiIndex']);

        Route::get('/dashboard/stats', [App\Http\Controllers\DashboardController::class, 'stats']);
        Route::get('/dashboard/activities', [App\Http\Controllers\DashboardController::class, 'activities']);
    });
});