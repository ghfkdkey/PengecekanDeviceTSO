<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{
    /**
     * Display a listing of the devices
     */
    public function index(Request $request)
    {
        // Get all devices with room relationship
        $devices = Device::with(['room.floor'])->get();
        
        // Get all rooms for filter dropdown
        $rooms = Room::with('floor')->orderBy('room_name')->get();
        
        // Get unique device types for filter
        $deviceTypes = Device::whereNotNull('device_type')
            ->distinct()
            ->pluck('device_type')
            ->filter()
            ->sort();
        
        // If this is an AJAX request, return JSON data
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $devices,
                'rooms' => $rooms,
                'device_types' => $deviceTypes
            ]);
        }
        
        // Return view for regular page load
        return view('devices.index', compact('devices', 'rooms', 'deviceTypes'));
    }

    /**
     * Store a newly created device
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,room_id',
            'device_name' => 'required|string|min:3|max:100',
            'device_type' => 'nullable|string|max:50',
            'serial_number' => 'nullable|string|max:100',
        ], [
            'room_id.required' => 'Ruangan harus dipilih.',
            'room_id.exists' => 'Ruangan yang dipilih tidak valid.',
            'device_name.required' => 'Nama device harus diisi.',
            'device_name.min' => 'Nama device minimal 3 karakter.',
            'device_name.max' => 'Nama device maksimal 100 karakter.',
            'device_type.max' => 'Tipe device maksimal 50 karakter.',
            'serial_number.max' => 'Serial number maksimal 100 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        try {
            // Check if device name already exists in the same room
            $existingDevice = Device::where('room_id', $request->room_id)
                ->where('device_name', $request->device_name)
                ->first();

            if ($existingDevice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Device dengan nama tersebut sudah ada di ruangan ini.'
                ], 409);
            }

            $device = Device::create($request->only([
                'room_id', 
                'device_name', 
                'device_type', 
                'serial_number'
            ]));

            $device->load(['room.floor']);

            return response()->json([
                'success' => true,
                'message' => 'Device berhasil ditambahkan.',
                'data' => $device
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan device. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Display the specified device
     */
    public function show($id)
    {
        try {
            $device = Device::with(['room.floor'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $device
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Device tidak ditemukan.'
            ], 404);
        }
    }

    /**
     * Update the specified device
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,room_id',
            'device_name' => 'required|string|min:3|max:100',
            'device_type' => 'nullable|string|max:50',
            'serial_number' => 'nullable|string|max:100',
        ], [
            'room_id.required' => 'Ruangan harus dipilih.',
            'room_id.exists' => 'Ruangan yang dipilih tidak valid.',
            'device_name.required' => 'Nama device harus diisi.',
            'device_name.min' => 'Nama device minimal 3 karakter.',
            'device_name.max' => 'Nama device maksimal 100 karakter.',
            'device_type.max' => 'Tipe device maksimal 50 karakter.',
            'serial_number.max' => 'Serial number maksimal 100 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        try {
            $device = Device::findOrFail($id);

            // Check if device name already exists in the same room (excluding current device)
            $existingDevice = Device::where('room_id', $request->room_id)
                ->where('device_name', $request->device_name)
                ->where('device_id', '!=', $id)
                ->first();

            if ($existingDevice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Device dengan nama tersebut sudah ada di ruangan ini.'
                ], 409);
            }

            $device->update($request->only([
                'room_id', 
                'device_name', 
                'device_type', 
                'serial_number'
            ]));

            $device->load(['room.floor']);

            return response()->json([
                'success' => true,
                'message' => 'Device berhasil diperbarui.',
                'data' => $device
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Device tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui device. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Remove the specified device
     */
    public function destroy($id)
    {
        try {
            $device = Device::findOrFail($id);
            
            // Check if device has any check results
            $hasCheckResults = $device->checkResults()->exists();
            
            if ($hasCheckResults) {
                return response()->json([
                    'success' => false,
                    'message' => 'Device tidak dapat dihapus karena memiliki riwayat pemeriksaan. Harap hapus riwayat pemeriksaan terlebih dahulu.'
                ], 409);
            }

            $deviceName = $device->device_name;
            $device->delete();

            return response()->json([
                'success' => true,
                'message' => "Device '{$deviceName}' berhasil dihapus."
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Device tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus device. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Get devices by room
     */
    public function getByRoom($roomId)
    {
        try {
            $devices = Device::with(['room.floor'])
                ->where('room_id', $roomId)
                ->orderBy('device_name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $devices
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data device.'
            ], 500);
        }
    }

    /**
     * Get device statistics
     */
    public function getStatistics()
    {
        try {
            $totalDevices = Device::count();
            $activeDevices = Device::where('status', 'active')->count();
            $roomsWithDevices = Device::distinct('room_id')->count('room_id');
            $deviceTypes = Device::whereNotNull('device_type')->distinct()->count('device_type');

            $devicesByType = Device::selectRaw('device_type, COUNT(*) as count')
                ->whereNotNull('device_type')
                ->groupBy('device_type')
                ->orderBy('count', 'desc')
                ->get();

            $devicesByRoom = Device::with('room')
                ->selectRaw('room_id, COUNT(*) as count')
                ->groupBy('room_id')
                ->orderBy('count', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_devices' => $totalDevices,
                    'active_devices' => $activeDevices,
                    'rooms_with_devices' => $roomsWithDevices,
                    'device_types' => $deviceTypes,
                    'devices_by_type' => $devicesByType,
                    'devices_by_room' => $devicesByRoom
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik device.'
            ], 500);
        }
    }

    /**
     * Bulk update device status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_ids' => 'required|array',
            'device_ids.*' => 'exists:devices,device_id',
            'status' => 'required|in:active,inactive,maintenance'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updatedCount = Device::whereIn('device_id', $request->device_ids)
                ->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => "{$updatedCount} device berhasil diperbarui statusnya.",
                'updated_count' => $updatedCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status device.'
            ], 500);
        }
    }

    /**
     * Search devices
     */
    public function search(Request $request)
    {
        $query = Device::with(['room.floor']);

        // Search by device name
        if ($request->has('name') && $request->name) {
            $query->where('device_name', 'like', '%' . $request->name . '%');
        }

        // Filter by room
        if ($request->has('room_id') && $request->room_id) {
            $query->where('room_id', $request->room_id);
        }

        // Filter by device type
        if ($request->has('device_type') && $request->device_type) {
            $query->where('device_type', $request->device_type);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        try {
            $devices = $query->orderBy('device_name')->get();

            return response()->json([
                'success' => true,
                'data' => $devices,
                'count' => $devices->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan pencarian device.'
            ], 500);
        }
    }

    /**
     * Export devices to CSV
     */
    public function export(Request $request)
    {
        try {
            $devices = Device::with(['room.floor'])->get();

            $filename = 'devices_' . date('Y-m-d_H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function () use ($devices) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fwrite($file, "\xEF\xBB\xBF");
                
                // CSV headers
                fputcsv($file, [
                    'ID Device',
                    'Nama Device',
                    'Tipe Device',
                    'Serial Number',
                    'Ruangan',
                    'Lantai',
                    'Status',
                    'Dibuat Pada',
                    'Diperbarui Pada'
                ]);

                // CSV data
                foreach ($devices as $device) {
                    fputcsv($file, [
                        $device->device_id,
                        $device->device_name,
                        $device->device_type ?? '',
                        $device->serial_number ?? '',
                        $device->room->room_name ?? '',
                        $device->room->floor->floor_name ?? '',
                        $device->status ?? 'unknown',
                        $device->created_at->format('Y-m-d H:i:s'),
                        $device->updated_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengekspor data device.'
            ], 500);
        }
    }
}