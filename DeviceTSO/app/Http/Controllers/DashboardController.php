<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Floor;
use App\Models\Room;
use App\Models\DeviceCheckResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats()
    {
        try {
            // Get counts from database
            $totalDevices = Device::count();
            $totalFloors = Floor::count();
            $totalRooms = Room::count();
            
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
            
            // Get recent device check activities
            $checkActivities = DeviceCheckResult::with(['device.room.floor', 'user'])
                ->orderBy('checked_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($result) {
                    return [
                        'type' => 'device_check',
                        'description' => "{$result->user->full_name} mengecek {$result->device->device_name} di {$result->device->room->room_name}",
                        'user_name' => $result->user->full_name,
                        'created_at' => $result->checked_at,
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
                    // Ambil nama user yang benar-benar menambahkan device
                    $userName = $device->creator ? $device->creator->full_name : 'Unknown User';
                    
                    return [
                        'type' => 'device_added',
                        'description' => "Device {$device->device_name} ditambahkan di {$device->room->room_name}",
                        'user_name' => $userName,
                        'created_at' => $device->created_at
                    ];
                });
            
            // Get recent floor additions with REAL user info
            $floorActivities = Floor::with('creator')
                ->where('created_at', '>=', now()->subDays(7))
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($floor) {
                    // Ambil nama user yang benar-benar menambahkan floor
                    $userName = $floor->creator ? $floor->creator->full_name : 'Unknown User';
                    
                    return [
                        'type' => 'floor_added',
                        'description' => "Lantai {$floor->floor_name} ditambahkan",
                        'user_name' => $userName,
                        'created_at' => $floor->created_at
                    ];
                });
            
            // Get recent room additions with REAL user info
            $roomActivities = Room::with(['floor', 'creator'])
                ->where('created_at', '>=', now()->subDays(7))
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($room) {
                    // Ambil nama user yang benar-benar menambahkan room
                    $userName = $room->creator ? $room->creator->full_name : 'Unknown User';
                    
                    return [
                        'type' => 'room_added',
                        'description' => "Ruangan {$room->room_name} ditambahkan di {$room->floor->floor_name}",
                        'user_name' => $userName,
                        'created_at' => $room->created_at
                    ];
                });
            
            // Combine all activities and sort by created_at
            $allActivities = $checkActivities
                ->concat($deviceActivities)
                ->concat($floorActivities)
                ->concat($roomActivities)
                ->sortByDesc('created_at')
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