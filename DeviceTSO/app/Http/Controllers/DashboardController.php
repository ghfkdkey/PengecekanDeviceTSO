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
            $totalDevices   = Device::count();
            $totalFloors    = Floor::count();
            $totalRooms     = Room::count();
            $totalBuildings = Building::count();
            $totalRegionals = Regional::count();
            $totalAreas     = Area::count();

            $totals = DB::table(DB::raw("
                (
                    SELECT
                        dcr.device_id,
                        SUM(CASE WHEN dcr.status = 'failed'  THEN 1 ELSE 0 END) AS failed_count,
                        SUM(CASE WHEN dcr.status = 'pending' THEN 1 ELSE 0 END) AS pending_count,
                        SUM(CASE WHEN dcr.status = 'passed'  THEN 1 ELSE 0 END) AS passed_count
                    FROM device_check_results dcr
                    JOIN (
                        SELECT device_id, MAX(checked_at) AS latest_checked_at
                        FROM device_check_results
                        GROUP BY device_id
                    ) latest
                        ON latest.device_id = dcr.device_id
                    AND latest.latest_checked_at = dcr.checked_at
                    GROUP BY dcr.device_id
                ) x
            "))
            ->selectRaw("
                SUM(CASE WHEN x.failed_count  > 0 THEN 1 ELSE 0 END)                                      AS failed_devices,
                SUM(CASE WHEN x.failed_count  = 0 AND x.pending_count > 0 THEN 1 ELSE 0 END)              AS pending_devices,
                SUM(CASE WHEN x.failed_count  = 0 AND x.pending_count = 0 THEN 1 ELSE 0 END)              AS passed_devices
            ")
            ->first();

            $failedDevices  = (int) ($totals->failed_devices  ?? 0);
            $pendingDevices = (int) ($totals->pending_devices ?? 0);
            $passedDevices  = (int) ($totals->passed_devices  ?? 0);

            return response()->json([
                'total_devices'   => $totalDevices,
                'total_floors'    => $totalFloors,
                'total_rooms'     => $totalRooms,
                'total_buildings' => $totalBuildings,
                'total_regionals' => $totalRegionals,
                'total_areas'     => $totalAreas,
                'pending_devices' => $pendingDevices,
                'passed_devices'  => $passedDevices,
                'failed_devices'  => $failedDevices,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Failed to load dashboard statistics',
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