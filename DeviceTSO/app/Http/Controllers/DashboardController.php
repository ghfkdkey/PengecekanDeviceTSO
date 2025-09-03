<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Floor;
use App\Models\Room;
use App\Models\Building;
use App\Models\Regional;
use App\Models\Area;
use App\Models\DeviceCheckResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function stats()
    {
        try {
            $user = auth()->user(); // Dapatkan user yang sedang login
            
            // Cek apakah pengguna adalah admin, PIC GA atau PIC Operasional
            $isAdmin = $user->isAdmin();
            $isGA = $user->isGA();
            $isOperational = $user->isOperational();

            // Hitung total perangkat yang dapat diakses oleh user
            $totalDevicesQuery = Device::query();
            
            if (!$isAdmin) {
                // Jika bukan admin, filter perangkat berdasarkan regional_id
                $totalDevicesQuery->whereHas('room.floor.building.regional', function($query) use ($user) {
                    $query->where('regional_id', $user->regional_id);
                });
            }
            
            $totalDevices = $totalDevicesQuery->count();

            // Hitung perangkat berdasarkan status (failed, pending, passed, maintenance)
            $statusCounts = DB::table('device_check_results as dcr')
                ->selectRaw("
                    COUNT(DISTINCT CASE WHEN dcr.status = 'failed' THEN dcr.device_id END) AS failed_devices,
                    COUNT(DISTINCT CASE WHEN dcr.status = 'pending' THEN dcr.device_id END) AS pending_devices,
                    COUNT(DISTINCT CASE WHEN dcr.status = 'passed' THEN dcr.device_id END) AS passed_devices,
                    COUNT(DISTINCT CASE WHEN dcr.status = 'maintenance' THEN dcr.device_id END) AS maintenance_devices
                ")
                ->join('devices as d', 'd.device_id', '=', 'dcr.device_id')
                ->join('rooms as r', 'r.room_id', '=', 'd.room_id')
                ->join('floors as f', 'f.floor_id', '=', 'r.floor_id')
                ->join('buildings as b', 'b.building_id', '=', 'f.building_id')
                ->join('regionals as rg', 'rg.regional_id', '=', 'b.regional_id')
                ->when(!$isAdmin, function($query) use ($user) {
                    // Untuk non-admin, filter berdasarkan regional_id
                    $query->where('rg.regional_id', $user->regional_id);
                })
                ->groupBy('dcr.device_id')
                ->first();

            // Hitung perangkat yang belum memiliki hasil pengecekan
            $devicesWithResults = DeviceCheckResult::distinct('device_id')
                ->whereIn('device_id', function ($query) use ($user) {
                    $query->select('d.device_id')
                        ->from('devices as d')
                        ->join('rooms as r', 'r.room_id', '=', 'd.room_id')
                        ->join('floors as f', 'f.floor_id', '=', 'r.floor_id')
                        ->join('buildings as b', 'b.building_id', '=', 'f.building_id')
                        ->join('regionals as rg', 'rg.regional_id', '=', 'b.regional_id')
                        ->where('rg.regional_id', $user->regional_id);
                })
                ->count('device_id');

            // Hitung perangkat yang tidak memiliki hasil pengecekan (belum ada status)
            $devicesWithoutResults = $totalDevices - $devicesWithResults;

            // Perhitungan untuk Admin (hanya perangkat yang belum diperiksa yang dihitung sebagai pending)
            if ($isAdmin) {
                $pendingDevices = $devicesWithoutResults;  // Admin hanya melihat perangkat yang belum diperiksa
            } else {
                // Untuk PIC GA atau PIC Operasional, hitung perangkat yang belum diperiksa sebagai pending
                $pendingDevices = (int) ($statusCounts->pending_devices ?? 0) + $devicesWithoutResults;
            }

            // Kembalikan hasil statistik
            return response()->json([
                'total_devices' => $totalDevices,
                'pending_devices' => $pendingDevices,
                'failed_devices' => (int) ($statusCounts->failed_devices ?? 0),
                'passed_devices' => (int) ($statusCounts->passed_devices ?? 0),
                'maintenance_devices' => (int) ($statusCounts->maintenance_devices ?? 0),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load dashboard statistics',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function activities(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);
            $checkActivities = DeviceCheckResult::with(['device.room.floor', 'user'])
                ->where('checked_at', '>=', now()->subDays(7))
                ->orderBy('checked_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($result) {
                    $checkedAt = Carbon::parse($result->checked_at)->setTimezone(config('app.timezone', 'Asia/Jakarta'));
                    
                    return [
                        'type' => 'device_check',
                        'description' => "{$result->user->full_name} mengecek {$result->device->device_name} di {$result->device->room->room_name}",
                        'user_name' => $result->user->full_name,
                        'created_at' => $checkedAt->toISOString(),
                        'status' => $result->status
                    ];
                });
            
            // Get recent device additions with REAL user info
            $deviceActivities = Device::with(['room.floor', 'creator'])
                ->where('created_at', '>=', now()->subDays(7))
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($device) {
                    $userName = $device->creator ? $device->creator->full_name : 'Unknown User';
                    $createdAt = Carbon::parse($device->created_at)->setTimezone(config('app.timezone', 'Asia/Jakarta'));
                    
                    return [
                        'type' => 'device_added',
                        'description' => "Device {$device->device_name} ditambahkan di {$device->room->room_name}",
                        'user_name' => $userName,
                        'created_at' => $createdAt->toISOString()
                    ];
                });
            
            // Get recent floor additions with REAL user info
            $floorActivities = Floor::with(['building', 'creator'])
                ->where('created_at', '>=', now()->subDays(7))
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($floor) {
                    $userName = $floor->creator ? $floor->creator->full_name : 'Unknown User';
                    $createdAt = Carbon::parse($floor->created_at)->setTimezone(config('app.timezone', 'Asia/Jakarta'));
                    $buildingName = $floor->building ? $floor->building->building_name : 'Unknown Building';
                    
                    return [
                        'type' => 'floor_added',
                        'description' => "Lantai {$floor->floor_name} ditambahkan di gedung {$buildingName}",
                        'user_name' => $userName,
                        'created_at' => $createdAt->toISOString()
                    ];
                });
            
            // Get recent room additions with REAL user info
            $roomActivities = Room::with(['floor.building', 'creator'])
                ->where('created_at', '>=', now()->subDays(7))
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($room) {
                    $userName = $room->creator ? $room->creator->full_name : 'Unknown User';
                    $createdAt = Carbon::parse($room->created_at)->setTimezone(config('app.timezone', 'Asia/Jakarta'));
                    $floorName = $room->floor ? $room->floor->floor_name : 'Unknown Floor';
                    
                    return [
                        'type' => 'room_added',
                        'description' => "Ruangan {$room->room_name} ditambahkan di {$floorName}",
                        'user_name' => $userName,
                        'created_at' => $createdAt->toISOString()
                    ];
                });
            
            // Get recent building additions with REAL user info
            $buildingActivities = Building::with(['regional.area', 'creator'])
                ->where('created_at', '>=', now()->subDays(7))
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($building) {
                    $userName = $building->creator ? $building->creator->full_name : 'Unknown User';
                    $createdAt = Carbon::parse($building->created_at)->setTimezone(config('app.timezone', 'Asia/Jakarta'));
                    $regionalName = $building->regional ? $building->regional->regional_name : 'Unknown Regional';
                    
                    return [
                        'type' => 'building_added',
                        'description' => "Gedung {$building->building_name} ditambahkan di regional {$regionalName}",
                        'user_name' => $userName,
                        'created_at' => $createdAt->toISOString()
                    ];
                });
            
            // Get recent regional additions with REAL user info
            $regionalActivities = Regional::with(['area', 'creator'])
                ->where('created_at', '>=', now()->subDays(7))
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($regional) {
                    $userName = $regional->creator ? $regional->creator->full_name : 'Unknown User';
                    $createdAt = Carbon::parse($regional->created_at)->setTimezone(config('app.timezone', 'Asia/Jakarta'));
                    $areaName = $regional->area ? $regional->area->area_name : 'Unknown Area';
                    
                    return [
                        'type' => 'regional_added',
                        'description' => "Regional {$regional->regional_name} ditambahkan di area {$areaName}",
                        'user_name' => $userName,
                        'created_at' => $createdAt->toISOString()
                    ];
                });
            
            // Get recent area additions with REAL user info
            $areaActivities = Area::with('creator')
                ->where('created_at', '>=', now()->subDays(7))
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($area) {
                    $userName = $area->creator ? $area->creator->full_name : 'Unknown User';
                    $createdAt = Carbon::parse($area->created_at)->setTimezone(config('app.timezone', 'Asia/Jakarta'));
                    
                    return [
                        'type' => 'area_added',
                        'description' => "Area {$area->area_name} berhasil ditambahkan",
                        'user_name' => $userName,
                        'created_at' => $createdAt->toISOString()
                    ];
                });
            
            // Combine all activities and sort by created_at
            $allActivities = $checkActivities
                ->concat($deviceActivities)
                ->concat($floorActivities)
                ->concat($roomActivities)
                ->concat($buildingActivities)
                ->concat($regionalActivities)
                ->concat($areaActivities)
                ->sortByDesc(function ($activity) {
                    return Carbon::parse($activity['created_at']);
                })
                ->take($limit)
                ->values();
            
            return response()->json($allActivities);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load dashboard activities',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}