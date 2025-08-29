<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\Building;
use Illuminate\Http\Request;
use App\Traits\RegionalFilter;

class FloorController extends Controller
{
    use RegionalFilter;
    /**
     * Display a listing of the floors.
     */
    public function index(Request $request)
    {
        if (!$this->checkPermission('ManageBuilding')) { 
            return $this->unauthorized($request);
        }

        // Handle AJAX request
        if ($request->ajax()) {
            $query = Floor::with('building.regional');
            
            // Apply regional filter untuk floors melalui relasi building
            $query = $this->applyRegionalFilterWithRelation($query, 'building', 'regional_id');
            
            $floors = $query->orderBy('floor_name')->get();
            return response()->json($floors);
        }

        // Dapatkan parameter filter yang sudah disesuaikan dengan hak akses user
        $filteredParams = $this->getFilteredRequestParams($request);
        
        // Validasi akses regional/area jika ada yang dikirim via request
        if (!$this->validateRegionalAccess($request->get('regional'), $request->get('area'))) {
            return $this->unauthorized($request);
        }

        // Query floors dengan filter regional
        $query = Floor::with('building.regional.area');
        
        // Apply regional filter untuk floors melalui relasi building
        $query = $this->applyRegionalFilterWithRelation($query, 'building', 'regional_id');

        // Apply additional filters (hanya jika user admin)
        if (auth()->user()->isAdmin()) {
            if ($request->filled('building')) {
                $query->where('building_id', $request->building);
            } elseif ($request->filled('regional')) {
                $query->whereHas('building', function ($q) use ($request) {
                    $q->where('regional_id', $request->regional);
                });
            } elseif ($request->filled('area')) {
                $query->whereHas('building.regional', function ($q) use ($request) {
                    $q->where('area_id', $request->area);
                });
            }
        }

        $floors = $query->orderBy('floor_name')->get();

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

        return view('floors.index', compact(
            'floors', 
            'buildings', 
            'areas', 
            'regionals', 
            'filterRestrictions'
        ));
    }

    /**
     * Store a newly created floor.
     */
    public function store(Request $request)
    {
        $request->validate([
            'floor_name' => 'required|string|max:50|unique:floors,floor_name',
            'building_id' => 'required|exists:buildings,building_id'
        ], [
            'building_id.required' => 'Gedung harus dipilih',
            'building_id.exists' => 'Gedung tidak valid'
        ]);

        $floor = Floor::create([
            'floor_name' => $request->floor_name,
            'building_id' => $request->building_id,
            'user_id' => auth()->id()
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Lantai berhasil ditambahkan!',
                'floor' => $floor->load('building')
            ]);
        }

        return redirect()->route('floors.index')->with('success', 'Lantai berhasil ditambahkan!');
    }

    /**
     * Display the specified floor.
     */
    public function show($id, Request $request)
    {
        $floor = Floor::with('rooms')->findOrFail($id);
        
        if ($request->ajax()) {
            return response()->json($floor);
        }

        return view('floors.show', compact('floor'));
    }

    /**
     * Update the specified floor.
     */
    public function update(Request $request, $id)
    {
        $floor = Floor::findOrFail($id);

        $request->validate([
            'floor_name' => 'required|string|max:50|unique:floors,floor_name,' . $id . ',floor_id'
        ]);

        $floor->update([
            'floor_name' => $request->floor_name
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Lantai berhasil diperbarui!',
                'floor' => $floor
            ]);
        }

        return redirect()->route('floors.index')->with('success', 'Lantai berhasil diperbarui!');
    }

    /**
     * Remove the specified floor.
     */
    public function destroy($id, Request $request)
    {
        $floor = Floor::findOrFail($id);
        
        // Check if floor has associated rooms
        if ($floor->rooms()->exists()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus lantai yang masih memiliki ruangan!'
                ], 400);
            }
            
            return redirect()->route('floors.index')->with('error', 'Tidak dapat menghapus lantai yang masih memiliki ruangan!');
        }

        $floor->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Lantai berhasil dihapus!'
            ]);
        }

        return redirect()->route('floors.index')->with('success', 'Lantai berhasil dihapus!');
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