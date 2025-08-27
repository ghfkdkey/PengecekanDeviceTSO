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
            // Get counts from database
            $totalDevices = Device::count();
            $totalFloors = Floor::count();
            $totalRooms = Room::count();
            $totalBuildings = Building::count();
            $totalRegionals = Regional::count();
            $totalAreas = Area::count();
            
            // Get device check sessions (latest per device) - same logic as device-check-results
            $deviceSessions = DeviceCheckResult::selectRaw('
                device_id,
                checked_at,
                SUM(CASE WHEN status = "passed" THEN 1 ELSE 0 END) as passed_count,
                SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed_count,
                SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_count
            ')
            ->groupBy('device_id', 'checked_at')
            ->orderBy('checked_at', 'desc')
            ->get();
            
            // Calculate stats per device session (same logic as device-check-results)
            $passedDevices = 0;
            $failedDevices = 0;
            $pendingDevices = 0;
            
            foreach ($deviceSessions as $session) {
                $failed = $session->failed_count || 0;
                $pending = $session->pending_count || 0;
                
                if ($failed > 0) {
                    $failedDevices += 1;
                } elseif ($pending > 0) {
                    $pendingDevices += 1;
                } else {
                    $passedDevices += 1;
                }
            }
            
            return response()->json([
                'total_devices' => $totalDevices,
                'total_floors' => $totalFloors,
                'total_rooms' => $totalRooms,
                'total_buildings' => $totalBuildings,
                'total_regionals' => $totalRegionals,
                'total_areas' => $totalAreas,
                'pending_devices' => $pendingDevices,
                'passed_devices' => $passedDevices,
                'failed_devices' => $failedDevices,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load dashboard statistics',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function activities(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);
            
            // Get recent device check activities dengan timezone yang benar
            $checkActivities = DeviceCheckResult::with(['device.room.floor', 'user'])
                ->where('checked_at', '>=', now()->subDays(7))
                ->orderBy('checked_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($result) {
                    // Pastikan timezone sesuai dengan aplikasi (Asia/Jakarta)
                    $checkedAt = Carbon::parse($result->checked_at)->setTimezone(config('app.timezone', 'Asia/Jakarta'));
                    
                    return [
                        'type' => 'device_check',
                        'description' => "{$result->user->full_name} mengecek {$result->device->device_name} di {$result->device->room->room_name}",
                        'user_name' => $result->user->full_name,
                        'created_at' => $checkedAt->toISOString(), // Format ISO untuk JavaScript
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