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
        return DeviceCheckResult::with([
            'device.room.floor',
            'checklistItem',
            'user'
        ])->orderBy('checked_at', 'desc')->get();
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
        $query = DeviceCheckResult::with(['device.room.floor', 'checklistItem', 'user']);
        $result = $query->findOrFail($id);

        if (request()->expectsJson()) {
            return $result;
        }

        return view('device-check-results.show', compact('result'));
    }

    public function update(Request $request, $id)
    {
        $result = DeviceCheckResult::findOrFail($id);
        $result->update($request->all());
        return $result;
    }

    public function destroy($id)
    {
        return DeviceCheckResult::destroy($id);
    }

    public function webIndex()
    {
        return view('device-check-results.index');
    }

    // New method for the improved device check page
    public function deviceCheckPage()
    {
        $floors = Floor::with(['rooms.devices'])->get();
        $checklistItems = ChecklistItem::all();
        
        return view('device-check-results.device-check', compact('floors', 'checklistItems'));
    }

    // API method to get rooms by floor
    public function getRoomsByFloor($floorId)
    {
        try {
            $rooms = Room::where('floor_id', $floorId)->get();
            return response()->json($rooms);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // API method to get devices by room
    public function getDevicesByRoom($roomId)
    {
        try {
            $devices = Device::where('room_id', $roomId)
                            ->select('device_id', 'device_name', 'device_type', 'serial_number')
                            ->get();
            
            \Log::info('Devices for room ' . $roomId . ': ' . $devices->count() . ' devices found');
            \Log::info('Device types: ' . $devices->pluck('device_type')->unique()->implode(', '));
            
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

    // API: list aggregated check sessions (one row per device per checked_at)
    public function listSessions()
    {
        $sessions = DB::table('device_check_results as dcr')
            ->join('devices as d', 'd.device_id', '=', 'dcr.device_id')
            ->join('rooms as r', 'r.room_id', '=', 'd.room_id')
            ->join('floors as f', 'f.floor_id', '=', 'r.floor_id')
            ->join('users as u', 'u.id', '=', 'dcr.user_id')
            ->select(
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

    // API: session detail (all checklist results for a device at checked_at; if not provided, use latest)
    public function sessionDetail(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,device_id',
            'checked_at' => 'nullable|date',
        ]);

        $checkedAt = $request->checked_at;
        if (!$checkedAt) {
            $checkedAt = DeviceCheckResult::where('device_id', $request->device_id)
                ->max('checked_at');
        }

        if (!$checkedAt) {
            return response()->json([]);
        }

        $checkedAtFormatted = Carbon::parse($checkedAt)->format('Y-m-d H:i:s');

        $results = DeviceCheckResult::with(['device.room.floor', 'checklistItem', 'user'])
            ->where('device_id', $request->device_id)
            ->where('checked_at', $checkedAtFormatted)
            ->orderBy('checklist_id')
            ->get();

        return response()->json($results);
    }

    // API: latest session per device (one row per device)
    public function listLatestPerDevice()
    {
        // Subquery to get latest checked_at per device
        $latestSub = DB::table('device_check_results')
            ->select('device_id', DB::raw('MAX(checked_at) as latest_checked_at'))
            ->groupBy('device_id');

        // Join with results to aggregate counts for the latest session
        $sessions = DB::table('device_check_results as dcr')
            ->joinSub($latestSub, 'latest', function ($join) {
                $join->on('latest.device_id', '=', 'dcr.device_id')
                     ->on('latest.latest_checked_at', '=', 'dcr.checked_at');
            })
            ->join('devices as d', 'd.device_id', '=', 'dcr.device_id')
            ->join('rooms as r', 'r.room_id', '=', 'd.room_id')
            ->join('floors as f', 'f.floor_id', '=', 'r.floor_id')
            ->join('users as u', 'u.id', '=', 'dcr.user_id')
            ->select(
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