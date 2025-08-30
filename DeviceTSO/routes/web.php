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
    
    // Dashboard
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    Route::resource('areas', AreaController::class);
    
    // Update regional routes to explicitly define the delete method
    Route::delete('/regionals/{id}', [RegionalController::class, 'destroy'])->name('regionals.destroy');
    Route::resource('regionals', RegionalController::class)->except(['destroy']);
    
    Route::resource('buildings', BuildingController::class);
    
    // User Management Routes
    Route::resource('users', UserController::class);
    
    // Floor Management
    Route::resource('floors', FloorController::class);
    
    // Room Management
    Route::resource('rooms', RoomController::class);
    
    // Device Management
    Route::get('/devices/export-excel', [DeviceController::class, 'exportExcel'])->name('devices.export-excel');
    Route::resource('devices', DeviceController::class);
    
    // Device Image Upload - Specific route for image upload
    Route::post('/devices/upload-image', [DeviceController::class, 'uploadImage'])->name('devices.upload-image');
    Route::get('/devices/image/{id}', [DeviceController::class, 'getImage'])->name('devices.get-image');    

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
        Route::get('/users', [UserController::class, 'apiIndex'])->name('users.api-index');
        Route::post('/users', [UserController::class, 'apiStore'])->name('users.api-store');
        Route::get('/users/{id}', [UserController::class, 'apiShow'])->name('users.api-show');
        Route::put('/users/{id}', [UserController::class, 'apiUpdate'])->name('users.api-update');
        Route::delete('/users/{id}', [UserController::class, 'apiDestroy'])->name('users.api-destroy');

        // Dashboard API Routes
        Route::get('/dashboard/stats', [App\Http\Controllers\DashboardController::class, 'stats'])->name('dashboard.stats');
        Route::get('/dashboard/activities', [App\Http\Controllers\DashboardController::class, 'activities'])->name('dashboard.activities');

        Route::get('/floors', [FloorController::class, 'apiIndex'])->name('floors.api-index');
        Route::get('/rooms', [RoomController::class, 'apiIndex'])->name('rooms.api-index');
        
        // Checklist Items API
        Route::get('/checklist-items/{id}', [ChecklistItemController::class, 'apiShow']);
        Route::put('/checklist-items/{id}', [ChecklistItemController::class, 'apiUpdate']); // perbaiki path
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
    Route::delete('/checklist-items/{id}', [ChecklistItemController::class, 'destroy'])->name('checklist-items.destroy');
});

// Checklist Items manual CRUD routes
Route::get('/checklist-items', [ChecklistItemController::class, 'index'])->name('checklist-items.index');
Route::get('/checklist-items/create', [ChecklistItemController::class, 'create'])->name('checklist-items.create');
Route::post('/checklist-items', [ChecklistItemController::class, 'store'])->name('checklist-items.store');
Route::get('/checklist-items/{id}', [ChecklistItemController::class, 'show'])->name('checklist-items.show');
Route::get('/checklist-items/{id}/edit', [ChecklistItemController::class, 'edit'])->name('checklist-items.edit');
Route::put('/checklist-items/{id}', [ChecklistItemController::class, 'update'])->name('checklist-items.update');
Route::delete('/checklist-items/{id}', [ChecklistItemController::class, 'destroy'])->name('checklist-items.destroy');