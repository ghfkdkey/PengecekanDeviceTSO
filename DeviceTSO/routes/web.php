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
    Route::resource('users', UserController::class);
    
    // Floor Management
    Route::resource('floors', FloorController::class);
    
    // Room Management
    Route::resource('rooms', RoomController::class);
    
    // Device Management - Main routes
    Route::resource('devices', DeviceController::class);
    
    // Device Image Upload - Specific route for image upload
    Route::post('/devices/upload-image', [DeviceController::class, 'uploadImage'])->name('devices.upload-image');
    Route::get('/devices/image/{id}', [DeviceController::class, 'getImage'])->name('devices.get-image');
    
    // Checklist Items Management
    Route::resource('checklist-items', ChecklistItemController::class);

    // Device Check Results - Web views
    Route::get('/device-check-results', [DeviceCheckResultController::class, 'webIndex'])->name('device-check-results.index');
    Route::get('/device-check', [DeviceCheckResultController::class, 'deviceCheckPage'])->name('device-check.page');
    
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
    
    // API Routes untuk AJAX dan Device Check
    Route::prefix('api')->name('api.')->group(function () {
        // Room and Device selection APIs
        Route::get('/rooms/{floor}', [DeviceCheckResultController::class, 'getRoomsByFloor'])->name('rooms.by-floor');
        Route::get('/devices/{room}', [DeviceCheckResultController::class, 'getDevicesByRoom'])->name('devices.by-room');
        Route::get('/checklist/{deviceType}', [DeviceCheckResultController::class, 'getChecklistByDeviceType'])->name('checklist.by-type');
    
        // Device Check Results API
        Route::post('/device-check-results/multiple', [DeviceCheckResultController::class, 'storeMultipleResults'])->name('device-check-results.multiple');
        Route::get('/device-check-results', [DeviceCheckResultController::class, 'apiIndex'])->name('device-check-results.api-index');
        Route::get('/device-check-results/{id}', [DeviceCheckResultController::class, 'show'])->name('device-check-results.show');
        Route::post('/device-check-results', [DeviceCheckResultController::class, 'store'])->name('device-check-results.store');
        Route::put('/device-check-results/{id}', [DeviceCheckResultController::class, 'update'])->name('device-check-results.update');
        Route::delete('/device-check-results/{id}', [DeviceCheckResultController::class, 'destroy'])->name('device-check-results.destroy');
    
        // Aggregated sessions
        Route::get('/device-check-sessions', [DeviceCheckResultController::class, 'listLatestPerDevice'])->name('device-check-results.sessions-latest');
        Route::get('/device-check-session-detail', [DeviceCheckResultController::class, 'sessionDetail'])->name('device-check-results.session-detail');
    
        // Supporting APIs for dropdowns and management
        Route::get('/devices', [DeviceController::class, 'apiIndex'])->name('devices.api-index');
        Route::get('/checklist-items', [ChecklistItemController::class, 'apiIndex'])->name('checklist-items.api-index');
        
        // ✅ FIX: Tambahkan semua CRUD untuk Users API
        Route::get('/users', [UserController::class, 'index'])->name('users.api-index');
        Route::post('/users', [UserController::class, 'store'])->name('users.api-store');
        Route::get('/users/{id}', [UserController::class, 'show'])->name('users.api-show');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.api-update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.api-destroy');
    
        Route::get('/floors', [FloorController::class, 'apiIndex'])->name('floors.api-index');
        Route::get('/rooms', [RoomController::class, 'apiIndex'])->name('rooms.api-index');
    });

});

// Routes that need to be OUTSIDE the api prefix but still protected
Route::middleware('auth')->group(function () {
    // Device CRUD operations - these should be at root level, not under /api
    Route::post('/devices', [DeviceController::class, 'store'])->name('devices.store');
    Route::put('/devices/{device}', [DeviceController::class, 'update'])->name('devices.update');
    Route::delete('/devices/{device}', [DeviceController::class, 'destroy'])->name('devices.destroy');
    
    // Floor CRUD operations
    Route::post('/floors', [FloorController::class, 'store'])->name('floors.store');
    Route::put('/floors/{floor}', [FloorController::class, 'update'])->name('floors.update');
    Route::delete('/floors/{floor}', [FloorController::class, 'destroy'])->name('floors.destroy');
    
    // Room CRUD operations
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');
    
    // Checklist Items CRUD operations
    Route::post('/checklist-items', [ChecklistItemController::class, 'store'])->name('checklist-items.store');
    Route::put('/checklist-items/{checklistItem}', [ChecklistItemController::class, 'update'])->name('checklist-items.update');
    Route::delete('/checklist-items/{checklistItem}', [ChecklistItemController::class, 'destroy'])->name('checklist-items.destroy');
});

// Admin only routes (uncomment and modify as needed)
/*
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});
*/