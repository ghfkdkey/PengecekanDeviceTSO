<?php

namespace App\Http\Controllers;

use App\Models\DeviceCheckResult;
use App\Models\Floor;
use App\Models\Room;
use App\Models\Device;
use App\Models\ChecklistItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DeviceCheckResultController extends Controller
{
    public function index()
    {
        // For web, render the page
        return view('device-check-results.index');
    }

    // API: list check results with relations for history table
    public function apiIndex()
    {
        $user = auth()->user();
        
        $query = DeviceCheckResult::with([
            'device.room.floor.building.regional',
            'checklistItem',
            'user'
        ]);

        // Filter berdasarkan regional user jika bukan admin
        if (!$user->isAdmin()) {
            $query->whereHas('device.room.floor.building.regional', function($q) use ($user) {
                $q->where('regional_id', $user->regional_id);
            });
        }

        return $query->orderBy('checked_at', 'desc')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,device_id',
            'checklist_id' => 'required|exists:checklist_items,checklist_id',
            'user_id' => 'required|exists:users,user_id',
            'status' => 'required|string|max:10',
            'notes' => 'nullable|string',
            'checked_at' => 'nullable|date',
        ]);

        return DeviceCheckResult::create($request->all());
    }

    public function show($id)
    {
        $user = auth()->user();
        
        $query = DeviceCheckResult::with(['device.room.floor.building.regional', 'checklistItem', 'user']);
        
        // Filter berdasarkan regional user jika bukan admin
        if (!$user->isAdmin()) {
            $query->whereHas('device.room.floor.building.regional', function($q) use ($user) {
                $q->where('regional_id', $user->regional_id);
            });
        }
        
        $result = $query->findOrFail($id);

        if (request()->expectsJson()) {
            return $result;
        }

        return view('device-check-results.show', compact('result'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $result = DeviceCheckResult::findOrFail($id);
        
        // Check if user can access this result based on regional
        if (!$user->isAdmin()) {
            $device = $result->device()->with('room.floor.building.regional')->first();
            if ($device->room->floor->building->regional->regional_id !== $user->regional_id) {
                abort(403, 'Unauthorized access to this device check result');
            }
        }
        
        $result->update($request->all());
        return $result;
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $result = DeviceCheckResult::findOrFail($id);
        
        // Check if user can access this result based on regional
        if (!$user->isAdmin()) {
            $device = $result->device()->with('room.floor.building.regional')->first();
            if ($device->room->floor->building->regional->regional_id !== $user->regional_id) {
                abort(403, 'Unauthorized access to this device check result');
            }
        }
        
        return DeviceCheckResult::destroy($id);
    }

    public function webIndex()
    {
        return view('device-check-results.index');
    }

    // PERBAIKAN: Filter floors berdasarkan regional user
    public function deviceCheckPage()
    {
        $user = auth()->user();
        
        $floorsQuery = Floor::with(['rooms.devices', 'building.regional']);
        
        // Filter berdasarkan regional user jika bukan admin
        if (!$user->isAdmin()) {
            $floorsQuery->whereHas('building.regional', function($query) use ($user) {
                $query->where('regional_id', $user->regional_id);
            });
        }
        
        $floors = $floorsQuery->get();
        $checklistItems = ChecklistItem::all();
        
        // Log untuk debugging
        \Log::info('Device check page access', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'user_regional_id' => $user->regional_id,
            'is_admin' => $user->isAdmin(),
            'floors_count' => $floors->count(),
            'floors_regional_ids' => $floors->pluck('building.regional.regional_id')->unique()->values()->toArray()
        ]);
        
        return view('device-check-results.device-check', compact('floors', 'checklistItems'));
    }

    // PERBAIKAN: API method to get rooms by floor - dengan filter regional
    public function getRoomsByFloor($floorId)
    {
        try {
            $user = auth()->user();
            
            // Cek apakah floor ini boleh diakses user
            if (!$user->isAdmin()) {
                $floor = Floor::with('building.regional')->findOrFail($floorId);
                if ($floor->building->regional->regional_id !== $user->regional_id) {
                    return response()->json(['error' => 'Unauthorized access to this floor'], 403);
                }
            }
            
            $rooms = Room::where('floor_id', $floorId)->get();
            
            \Log::info('Get rooms by floor', [
                'user_id' => $user->id,
                'user_regional_id' => $user->regional_id,
                'floor_id' => $floorId,
                'rooms_count' => $rooms->count()
            ]);
            
            return response()->json($rooms);
        } catch (\Exception $e) {
            \Log::error('Error getting rooms for floor ' . $floorId . ': ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // PERBAIKAN: API method to get devices by room - dengan filter regional
    public function getDevicesByRoom($roomId)
    {
        try {
            $user = auth()->user();
            
            // Cek apakah room ini boleh diakses user
            if (!$user->isAdmin()) {
                $room = Room::with('floor.building.regional')->findOrFail($roomId);
                if ($room->floor->building->regional->regional_id !== $user->regional_id) {
                    return response()->json(['error' => 'Unauthorized access to this room'], 403);
                }
            }
            
            $devices = Device::where('room_id', $roomId)
                            ->select('device_id', 'device_name', 'device_type', 'serial_number')
                            ->get();
            
            \Log::info('Devices for room ' . $roomId . ': ' . $devices->count() . ' devices found');
            \Log::info('Device types: ' . $devices->pluck('device_type')->unique()->implode(', '));
            \Log::info('User access info', [
                'user_id' => $user->id,
                'user_regional_id' => $user->regional_id,
                'room_id' => $roomId
            ]);
            
            return response()->json($devices);
        } catch (\Exception $e) {
            \Log::error('Error getting devices for room ' . $roomId . ': ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // API method to get checklist items by device type
    public function getChecklistByDeviceType($deviceType)
    {
        $checklistItems = ChecklistItem::where('device_type', $deviceType)
                                      ->orWhere('device_type', 'general')
                                      ->get();
        
        return response()->json($checklistItems);
    }

    // Store multiple check results
    public function storeMultipleResults(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,device_id',
            'checklist_results' => 'required|array',
            'checklist_results.*.checklist_id' => 'required|exists:checklist_items,checklist_id',
            'checklist_results.*.status' => 'required|in:passed,failed,pending',
            'checklist_results.*.notes' => 'nullable|string',
        ]);

        $user = auth()->user();
        
        // PERBAIKAN: Cek apakah device ini boleh diakses user
        if (!$user->isAdmin()) {
            $device = Device::with('room.floor.building.regional')->findOrFail($request->device_id);
            if ($device->room->floor->building->regional->regional_id !== $user->regional_id) {
                return response()->json(['error' => 'Unauthorized access to this device'], 403);
            }
        }

        $results = [];
        $userId = auth()->id();
        $checkedAt = now();

        foreach ($request->checklist_results as $result) {
            $checkResult = DeviceCheckResult::create([
                'device_id' => $request->device_id,
                'checklist_id' => $result['checklist_id'],
                'user_id' => $userId,
                'status' => $result['status'],
                'notes' => $result['notes'] ?? null,
                'checked_at' => $checkedAt,
            ]);

            $results[] = $checkResult->load(['device', 'checklistItem', 'user']);
        }

        return response()->json([
            'message' => 'Check results saved successfully',
            'results' => $results
        ]);
    }

    // PERBAIKAN: API: list aggregated check sessions - dengan filter regional
    public function listSessions()
    {
        $user = auth()->user();
        
        $query = DB::table('device_check_results as dcr')
            ->join('devices as d', 'd.device_id', '=', 'dcr.device_id')
            ->join('rooms as r', 'r.room_id', '=', 'd.room_id')
            ->join('floors as f', 'f.floor_id', '=', 'r.floor_id')
            ->join('buildings as b', 'b.building_id', '=', 'f.building_id')
            ->join('regionals as reg', 'reg.regional_id', '=', 'b.regional_id')
            ->join('users as u', 'u.id', '=', 'dcr.user_id');

        // Filter berdasarkan regional user jika bukan admin
        if (!$user->isAdmin()) {
            $query->where('reg.regional_id', $user->regional_id);
        }

        $sessions = $query->select(
                'dcr.device_id',
                'dcr.user_id',
                'dcr.checked_at',
                'd.device_name',
                'd.device_type',
                'd.serial_number',
                'r.room_name',
                'f.floor_name',
                'u.full_name as checked_by',
                DB::raw("SUM(CASE WHEN dcr.status = 'passed' THEN 1 ELSE 0 END) as passed_count"),
                DB::raw("SUM(CASE WHEN dcr.status = 'failed' THEN 1 ELSE 0 END) as failed_count"),
                DB::raw("SUM(CASE WHEN dcr.status = 'pending' THEN 1 ELSE 0 END) as pending_count"),
                DB::raw('COUNT(*) as total_items')
            )
            ->groupBy(
                'dcr.device_id',
                'dcr.user_id',
                'dcr.checked_at',
                'd.device_name',
                'd.device_type',
                'd.serial_number',
                'r.room_name',
                'f.floor_name',
                'u.full_name'
            )
            ->orderBy('dcr.checked_at', 'desc')
            ->get();

        return response()->json($sessions);
    }

    // PERBAIKAN: API: session detail - dengan filter regional
    public function sessionDetail(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,device_id',
            'checked_at' => 'nullable|date',
        ]);

        $user = auth()->user();
        
        // Cek apakah device ini boleh diakses user
        if (!$user->isAdmin()) {
            $device = Device::with('room.floor.building.regional')->findOrFail($request->device_id);
            if ($device->room->floor->building->regional->regional_id !== $user->regional_id) {
                return response()->json(['error' => 'Unauthorized access to this device'], 403);
            }
        }

        $checkedAt = $request->checked_at;
        if (!$checkedAt) {
            $checkedAt = DeviceCheckResult::where('device_id', $request->device_id)
                ->max('checked_at');
        }

        if (!$checkedAt) {
            return response()->json([]);
        }

        $checkedAtFormatted = Carbon::parse($checkedAt)->format('Y-m-d H:i:s');

        $results = DeviceCheckResult::with(['device.room.floor.building.regional', 'checklistItem', 'user'])
            ->where('device_id', $request->device_id)
            ->where('checked_at', $checkedAtFormatted)
            ->orderBy('checklist_id')
            ->get();

        return response()->json($results);
    }

    // PERBAIKAN: API: latest session per device - dengan filter regional
    public function listLatestPerDevice()
    {
        $user = auth()->user();
        
        // Subquery to get latest checked_at per device
        $latestSub = DB::table('device_check_results')
            ->select('device_id', DB::raw('MAX(checked_at) as latest_checked_at'))
            ->groupBy('device_id');

        // Join with results to aggregate counts for the latest session
        $query = DB::table('device_check_results as dcr')
            ->joinSub($latestSub, 'latest', function ($join) {
                $join->on('latest.device_id', '=', 'dcr.device_id')
                     ->on('latest.latest_checked_at', '=', 'dcr.checked_at');
            })
            ->join('devices as d', 'd.device_id', '=', 'dcr.device_id')
            ->join('rooms as r', 'r.room_id', '=', 'd.room_id')
            ->join('floors as f', 'f.floor_id', '=', 'r.floor_id')
            ->join('buildings as b', 'b.building_id', '=', 'f.building_id')
            ->join('regionals as reg', 'reg.regional_id', '=', 'b.regional_id')
            ->join('users as u', 'u.id', '=', 'dcr.user_id');

        // Filter berdasarkan regional user jika bukan admin
        if (!$user->isAdmin()) {
            $query->where('reg.regional_id', $user->regional_id);
        }

        $sessions = $query->select(
                'dcr.device_id',
                DB::raw('latest.latest_checked_at as checked_at'),
                'd.device_name',
                'd.device_type',
                'd.serial_number',
                'r.room_name',
                'f.floor_name',
                DB::raw('MAX(u.full_name) as checked_by'),
                DB::raw("SUM(CASE WHEN dcr.status = 'passed' THEN 1 ELSE 0 END) as passed_count"),
                DB::raw("SUM(CASE WHEN dcr.status = 'failed' THEN 1 ELSE 0 END) as failed_count"),
                DB::raw("SUM(CASE WHEN dcr.status = 'pending' THEN 1 ELSE 0 END) as pending_count"),
                DB::raw('COUNT(*) as total_items')
            )
            ->groupBy(
                'dcr.device_id',
                'latest.latest_checked_at',
                'd.device_name',
                'd.device_type',
                'd.serial_number',
                'r.room_name',
                'f.floor_name'
            )
            ->orderBy('d.device_name')
            ->get();

        return response()->json($sessions);
    }
}