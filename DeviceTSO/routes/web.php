<?php

use Illuminate\Support\Facades\Mail;
use App\Mail\DeviceStatusChangedMail;
use App\Models\DeviceCheckResult;
use App\Models\User;
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

    Route::prefix('api')->group(function () {
        Route::get('/rooms/{floorId}', [DeviceCheckResultController::class, 'getRoomsByFloor']);
        Route::get('/devices/{roomId}', [DeviceCheckResultController::class, 'getDevicesByRoom']);
        Route::get('/checklist/{deviceType}', [DeviceCheckResultController::class, 'getChecklistByDeviceType']);
        Route::post('/device-check-results/multiple', [DeviceCheckResultController::class, 'storeMultipleResults']);
        
        Route::get('/device-check-sessions', [DeviceCheckResultController::class, 'listLatestPerDevice']);
        Route::get('/device-check-session-detail', [DeviceCheckResultController::class, 'sessionDetail']);
        Route::get('/device-check-history/{deviceId}/{checklistId}', [DeviceCheckResultController::class, 'getCheckHistory']);
        Route::put('/device-check-results/{id}/update-status', [DeviceCheckResultController::class, 'updateIndividualResult']);
        Route::get('/device-check-results', [DeviceCheckResultController::class, 'apiIndex']);
        Route::post('/device-check-results', [DeviceCheckResultController::class, 'store']);
        Route::put('/device-check-results/{id}', [DeviceCheckResultController::class, 'update']);
        Route::delete('/device-check-results/{id}', [DeviceCheckResultController::class, 'destroy']);

        Route::get('/devices', function() {
            return \App\Models\Device::with('room.floor')->get();
        });
        Route::get('/checklist-items', function() {
            return \App\Models\ChecklistItem::all();
        });

        // User Management API Routes
        Route::get('/users', [UserController::class, 'apiIndex']);
        Route::get('/users/{id}', [UserController::class, 'apiShow']);
        Route::post('/users', [UserController::class, 'apiStore']);
        Route::put('/users/{id}', [UserController::class, 'apiUpdate']);
        Route::delete('/users/{id}', [UserController::class, 'apiDestroy']);
        
        // Regional dropdown data for user form
        Route::get('/regionals', [RegionalController::class, 'apiIndex']);

        // Dashboard API
        Route::get('/dashboard/stats', [App\Http\Controllers\DashboardController::class, 'stats']);
        Route::get('/dashboard/activities', [App\Http\Controllers\DashboardController::class, 'activities']);
        
        // Test route
        Route::get('/test', function() {
            return response()->json(['message' => 'API routes are working', 'timestamp' => now()]);
        });
    });

    // Route::get('/send-test-email', function () {
    //     $sampleResult = DeviceCheckResult::with(['device.room.floor.building', 'checklistItem', 'user'])
    //                     ->whereIn('status', ['failed', 'maintenance'])
    //                     ->whereHas('device.room.floor.building')
    //                     ->whereHas('checklistItem')
    //                     ->whereHas('user')
    //                     ->first();

    //     $samplePic = User::where('role', 'like', '%PIC%')->whereNotNull('email')->first();

    //     if (!$sampleResult || !$samplePic) {
    //         return "GAGAL: Tidak dapat menemukan data contoh yang LENGKAP. Pastikan ada data 'failed'/'maintenance' dan user 'PIC' dengan alamat email di database Anda.";
    //     }

    //     try {
    //         Mail::to($samplePic->email)
    //             ->send(new DeviceStatusChangedMail($sampleResult, $samplePic));
            
    //         return "BERHASIL! Email tes telah dikirim ke " . $samplePic->email . ". Silakan cek inbox Mailtrap Anda.";

    //     } catch (Exception $e) {
    //         return "GAGAL MENGIRIM EMAIL: " . $e->getMessage();
    //     }
    // });
});