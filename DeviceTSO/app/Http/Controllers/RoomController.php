<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Floor;
use App\Models\Building;
use App\Traits\RegionalFilter;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    use RegionalFilter;

    public function index(Request $request)
    {
        if (!$this->checkPermission('ManageBuilding')) {
            return $this->unauthorized($request);
        }

        // Dapatkan parameter filter yang sudah disesuaikan dengan hak akses user
        $filteredParams = $this->getFilteredRequestParams($request);
        
        // Validasi akses regional/area jika ada yang dikirim via request
        if (!$this->validateRegionalAccess($request->get('regional'), $request->get('area'))) {
            return $this->unauthorized($request);
        }

        // Query rooms dengan filter regional melalui floor.building
        $query = Room::with('floor.building.regional.area');
        
        // Apply regional filter untuk rooms melalui relasi floor.building
        $query->whereHas('floor.building', function ($q) {
            $this->applyRegionalFilter($q, 'regional_id');
        });

        // Apply additional filters (hanya jika user admin)
        if (auth()->user()->isAdmin()) {
            if ($request->filled('floor')) {
                $query->where('floor_id', $request->floor);
            } elseif ($request->filled('building')) {
                $query->whereHas('floor', function ($q) use ($request) {
                    $q->where('building_id', $request->building);
                });
            } elseif ($request->filled('regional')) {
                $query->whereHas('floor.building', function ($q) use ($request) {
                    $q->where('regional_id', $request->regional);
                });
            } elseif ($request->filled('area')) {
                $query->whereHas('floor.building.regional', function ($q) use ($request) {
                    $q->where('area_id', $request->area);
                });
            }
        }

        $rooms = $query->orderBy('room_name')->get();

        // Query floors dengan filter regional
        $floorsQuery = Floor::with('building.regional');
        $floorsQuery->whereHas('building', function ($q) {
            $this->applyRegionalFilter($q, 'regional_id');
        });
        
        // Apply additional floor filters (hanya jika user admin)
        if (auth()->user()->isAdmin()) {
            if ($request->filled('building')) {
                $floorsQuery->where('building_id', $request->building);
            } elseif ($request->filled('regional')) {
                $floorsQuery->whereHas('building', function ($q) use ($request) {
                    $q->where('regional_id', $request->regional);
                });
            } elseif ($request->filled('area')) {
                $floorsQuery->whereHas('building.regional', function ($q) use ($request) {
                    $q->where('area_id', $request->area);
                });
            }
        }
        
        $floors = $floorsQuery->orderBy('floor_name')->get();

        // Query buildings dengan filter regional
        $buildingsQuery = Building::query();
        $buildingsQuery = $this->applyRegionalFilter($buildingsQuery, 'regional_id');
        
        // Apply additional building filters (hanya jika user admin)
        if (auth()->user()->isAdmin()) {
            if ($request->filled('regional')) {
                $buildingsQuery->where('regional_id', $request->regional);
            } elseif ($request->filled('area')) {
                $buildingsQuery->whereHas('regional', function ($q) use ($request) {
                    $q->where('area_id', $request->area);
                });
            }
        }
        
        $buildings = $buildingsQuery->orderBy('building_name')->get();

        // Get areas dan regionals yang bisa diakses user
        $areas = $this->getAccessibleAreas();
        $regionals = $this->getAccessibleRegionals($filteredParams['area']);
        
        // Get filter restrictions untuk view
        $filterRestrictions = $this->getFilterRestrictions();

        return view('rooms.index', compact(
            'rooms', 
            'floors',
            'buildings',
            'areas', 
            'regionals', 
            'filterRestrictions'
        ));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'floor_id' => 'required|exists:floors,floor_id',
                'room_name' => 'required|string|max:100'
            ]);
            
            // TAMBAHKAN user_id dari user yang sedang login
            $validated['user_id'] = auth()->id();

            $room = Room::create($validated);
            
            // Load relasi floor untuk response yang lengkap
            $room->load('floor');

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ruangan berhasil ditambahkan.',
                    'room' => [
                        'room_id' => $room->room_id,
                        'room_name' => $room->room_name,
                        'floor_id' => $room->floor_id,
                        'user_id' => $room->user_id,
                        'created_at' => $room->created_at,
                        'updated_at' => $room->updated_at,
                        'floor' => [
                            'floor_id' => $room->floor->floor_id,
                            'floor_name' => $room->floor->floor_name
                        ]
                    ]
                ]);
            }

            return redirect()->route('rooms.index')
                             ->with('success', 'Ruangan berhasil ditambahkan.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                             ->withInput();
        }
    }

    public function show($id)
    {
        $room = Room::with('floor')->findOrFail($id);
        return view('rooms.show', compact('room'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'floor_id' => 'required|exists:floors,floor_id',
                'room_name' => 'required|string|max:100'
            ]);
            
            $room = Room::findOrFail($id);
            $room->update($validated);
            
            // Load relasi floor untuk response yang lengkap
            $room->load('floor');

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ruangan berhasil diperbarui.',
                    'room' => [
                        'room_id' => $room->room_id,
                        'room_name' => $room->room_name,
                        'floor_id' => $room->floor_id,
                        'user_id' => $room->user_id,
                        'created_at' => $room->created_at,
                        'updated_at' => $room->updated_at,
                        'floor' => [
                            'floor_id' => $room->floor->floor_id,
                            'floor_name' => $room->floor->floor_name
                        ]
                    ]
                ]);
            }

            return redirect()->route('rooms.index')
                             ->with('success', 'Ruangan berhasil diperbarui.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                             ->withInput();
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $room = Room::findOrFail($id);
            $roomName = $room->room_name; // Simpan nama room sebelum dihapus
            
            $room->delete();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Ruangan '{$roomName}' berhasil dihapus.",
                    'deleted_id' => $id
                ]);
            }
        
            return redirect()->route('rooms.index')
                             ->with('success', "Ruangan '{$roomName}' berhasil dihapus.");
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    protected function checkPermission($permission)
    {
        $user = auth()->user();
        if (!$user) return false;

        $methodName = 'can' . ucfirst($permission);
        
        if (method_exists($user, $methodName)) {
            return $user->$methodName();
        }

        return false;
    }

    /**
     * Return unauthorized response
     */
    protected function unauthorized($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengakses ini'
            ], 403);
        }

        return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut');
    }
}