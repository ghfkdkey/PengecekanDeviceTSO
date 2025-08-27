<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Room;
use App\Models\Floor;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DeviceController extends Controller
{
    /**
     * Display a listing of the devices
     */
    public function index(Request $request)
    {
        // Mulai query builder dengan eager loading relasi yang dibutuhkan
        $query = Device::with(['room.floor.building']);

        // Terapkan filter berdasarkan input dari request
        if ($request->filled('room')) {
            $query->where('room_id', $request->room);
        }

        // Filter berdasarkan floor
        if ($request->filled('floor')) {
            $query->whereHas('room', function ($q) use ($request) {
                $q->where('floor_id', $request->floor);
            });
        }

        // Filter berdasarkan building
        if ($request->filled('building')) {
            $query->whereHas('room.floor', function ($q) use ($request) {
                $q->where('building_id', $request->building);
            });
        }

        if ($request->filled('type')) {
            $query->where('device_type', $request->type);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('device_name', 'like', "%{$searchTerm}%")
                ->orWhere('serial_number', 'like', "%{$searchTerm}%");
            });
        }

        // Eksekusi query untuk mendapatkan device yang sudah difilter
        $devices = $query->get();
        
        // Data untuk dropdown filter tidak perlu difilter
        $rooms = Room::with(['floor.building'])->orderBy('room_name')->get();
        $floors = Floor::with('building')->orderBy('floor_name')->get();
        $buildings = Building::orderBy('building_name')->get();
        
        $deviceTypes = Device::whereNotNull('device_type')
            ->distinct()
            ->pluck('device_type')
            ->filter()
            ->sort();
        
        // Kembalikan view dengan data yang sudah difilter dan data untuk filter
        return view('devices.index', compact('devices', 'rooms', 'floors', 'buildings', 'deviceTypes'));
    }

    /**
     * API: Return plain devices list for dropdowns (no wrappers)
     */
    public function apiIndex()
    {
        $devices = Device::with(['room.floor'])
            ->select('device_id', 'room_id', 'device_name', 'device_type', 'serial_number')
            ->orderBy('device_name')
            ->get();
        return response()->json($devices);
    }

    /**
     * Store a newly created device
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'room_id' => 'required|exists:rooms,room_id',
                'device_name' => 'required|string|max:100',
                'device_type' => 'required|string|max:50',
                'serial_number' => 'nullable|string|max:100|unique:devices,serial_number',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                'category' => 'required|string|max:50',
                'notes' => 'nullable|string',
                'merk' => 'required|string|max:100',
                'tahun_po' => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
                'no_po' => 'nullable|string|max:100',
                'no_bast' => 'nullable|string|max:100',
                'tahun_bast' => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
                'condition' => 'required|string|in:baik,rusak',
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('devices', 'public');
                $validated['image_path'] = $path;
            }

            $validated['user_id'] = auth()->id();

            Device::create($validated);

            // Berikan respons JSON untuk request AJAX
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Device berhasil ditambahkan!'
                ]);
            }

            // Redirect untuk request non-AJAX
            return redirect()->route('devices.index')->with('success', 'Device berhasil ditambahkan');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Jika validasi gagal, kirim error sebagai JSON
            return response()->json([
                'success' => false, 
                'message' => 'Data tidak valid', 
                'errors' => $e->errors()
            ], 422);
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
        try {
            $device = Device::findOrFail($id);

            $validated = $request->validate([
                'room_id' => 'required|exists:rooms,room_id',
                'device_name' => 'required|string|max:100',
                'device_type' => 'required|string|max:50',
                // Pastikan validasi unique mengabaikan device saat ini
                'serial_number' => 'nullable|string|max:100|unique:devices,serial_number,' . $id . ',device_id',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                'category' => 'required|string|max:50',
                'notes' => 'nullable|string',
                'merk' => 'required|string|max:100',
                'tahun_po' => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
                'no_po' => 'nullable|string|max:100',
                'no_bast' => 'nullable|string|max:100',
                'tahun_bast' => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
                'condition' => 'required|string|in:baik,rusak',
            ]);
            
            // ... (logika update image Anda sudah benar) ...

            $device->update($validated);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Device berhasil diperbarui!'
                ]);
            }

            return redirect()->route('devices.index')->with('success', 'Device berhasil diperbarui');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Data tidak valid', 
                'errors' => $e->errors()
            ], 422);
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

            // Delete device image if exists
            if ($device->image_path) {
                Storage::disk('public')->delete($device->image_path);
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
            $goodConditionDevices = Device::where('condition', 'baik')->count();
            $badConditionDevices = Device::where('condition', 'rusak')->count();
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

            $devicesByCondition = Device::selectRaw('condition, COUNT(*) as count')
                ->groupBy('condition')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_devices' => $totalDevices,
                    'good_condition_devices' => $goodConditionDevices,
                    'bad_condition_devices' => $badConditionDevices,
                    'rooms_with_devices' => $roomsWithDevices,
                    'device_types' => $deviceTypes,
                    'devices_by_type' => $devicesByType,
                    'devices_by_room' => $devicesByRoom,
                    'devices_by_condition' => $devicesByCondition
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

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Filter by merk
        if ($request->has('merk') && $request->merk) {
            $query->where('merk', 'like', '%' . $request->merk . '%');
        }

        // Filter by condition
        if ($request->has('condition') && $request->condition) {
            $query->where('condition', $request->condition);
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

    public function exportExcel(Request $request)
    {
        try {
            // SOLUSI: Lakukan Eager Loading untuk semua relasi bertingkat
            $devices = Device::with([
                'room.floor.building.regional.area'
            ])->get();

            if ($devices->isEmpty()) {
                // Sebaiknya redirect dengan pesan error jika tidak ada data
                return back()->with('error', 'Tidak ada data device untuk diekspor.');
            }

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Data Devices');

            // Set Headers
            $headers = [
                'Kode Gedung', 'Area', 'Regional', 'Nama Gedung', 'Lantai', 'Ruangan',
                'Kategori', 'Tipe Device', 'Merk', 'Nama Device', 'Serial Number',
                'Catatan', 'No PO', 'No BAST', 'Tahun BAST', 'Kondisi'
            ];
            $sheet->fromArray($headers, NULL, 'A1');

            // Style Headers
            $sheet->getStyle('A1:P1')->getFont()->setBold(true);
            $sheet->getStyle('A1:P1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('E5E7EB');

            // Add Data
            $row = 2;
            foreach ($devices as $device) {
                // Gunakan null coalescing operator (??) untuk keamanan jika relasi kosong
                $sheet->setCellValue('A' . $row, $device->room->floor->building->building_code ?? 'N/A');
                $sheet->setCellValue('B' . $row, $device->room->floor->building->regional->area->area_name ?? 'N/A');
                $sheet->setCellValue('C' . $row, $device->room->floor->building->regional->regional_name ?? 'N/A');
                $sheet->setCellValue('D' . $row, $device->room->floor->building->building_name ?? 'N/A');
                $sheet->setCellValue('E' . $row, $device->room->floor->floor_name ?? 'N/A');
                $sheet->setCellValue('F' . $row, $device->room->room_name ?? 'N/A');
                $sheet->setCellValue('G' . $row, $device->category ?? 'N/A');
                $sheet->setCellValue('H' . $row, $device->device_type ?? 'N/A');
                $sheet->setCellValue('I' . $row, $device->merk ?? 'N/A');
                $sheet->setCellValue('J' . $row, $device->device_name ?? 'N/A');
                $sheet->setCellValue('K' . $row, $device->serial_number ?? 'N/A');
                $sheet->setCellValue('L' . $row, $device->notes ?? 'N/A');
                $sheet->setCellValue('M' . $row, $device->no_po ?? 'N/A');
                $sheet->setCellValue('N' . $row, $device->no_bast ?? 'N/A');
                $sheet->setCellValue('O' . $row, $device->tahun_bast ?? 'N/A');
                $sheet->setCellValue('P' . $row, $device->condition ? ucfirst($device->condition) : 'N/A');
                $row++;
            }

            // Auto-size columns
            foreach (range('A', 'P') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            $filename = 'devices_' . date('Y-m-d_H-i-s') . '.xlsx';
            $writer = new Xlsx($spreadsheet);
            
            return response()->streamDownload(function() use ($writer) {
                $writer->save('php://output');
            }, $filename);

        } catch (\Exception $e) {
            \Log::error('Excel export error: ' . $e->getMessage() . ' in file ' . $e->getFile() . ' on line ' . $e->getLine());
            // Redirect dengan pesan error agar user tahu
            return back()->with('error', 'Gagal mengekspor data: Terjadi kesalahan internal.');
        }
    }

    /**
     * Upload device image
     */
    public function uploadImage(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|exists:devices,device_id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240'
        ], [
            'device_id.required' => 'Device ID harus diisi.',
            'device_id.exists' => 'Device tidak ditemukan.',
            'image.required' => 'Gambar harus diupload.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'image.max' => 'Ukuran gambar maksimal 10MB.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }
    
        try {
            $device = Device::findOrFail($request->device_id);
            
            // Delete old image if exists
            if ($device->image_path) {
                Storage::disk('public')->delete($device->image_path);
            }
            
            // Store new image
            $imagePath = $request->file('image')->store('device-images', 'public');
            
            // Update device
            $device->update(['image_path' => $imagePath]);
            
            // Reload device untuk mendapatkan data terbaru
            $device->refresh();
            
            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil diupload!',
                'data' => [
                    'image_path' => $imagePath,
                    'image_url' => asset('storage/' . $imagePath),
                    'device' => $device
                ]
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Device tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload gambar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete device image
     */
    public function deleteImage($id)
    {
        try {
            $device = Device::findOrFail($id);
            
            if ($device->image_path) {
                // Delete image file from storage
                Storage::disk('public')->delete($device->image_path);
                
                // Remove image path from database
                $device->update(['image_path' => null]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Gambar berhasil dihapus.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Device tidak memiliki gambar.'
                ], 404);
            }
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Device tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus gambar: ' . $e->getMessage()
            ], 500);
        }
    }
}