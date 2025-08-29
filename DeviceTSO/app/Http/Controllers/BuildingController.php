<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Area;
use App\Models\Regional;
use App\Traits\RegionalFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BuildingController extends Controller 
{
    use RegionalFilter;
    
    public function __construct()
    {
        $this->middleware('auth');
    }

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

        $query = Building::query();

        // Apply regional filter berdasarkan role user
        $query = $this->applyRegionalFilter($query, 'regional_id');

        // Apply additional filters (hanya jika user admin)
        if (auth()->user()->isAdmin()) {
            if ($request->filled('regional')) {
                $query->where('regional_id', $request->regional);
            } elseif ($request->filled('area')) {
                $query->whereHas('regional', function ($q) use ($request) {
                    $q->where('area_id', $request->area);
                });
            }
        }

        $buildings = $query->with('regional.area')
                        ->withCount('floors')
                        ->get();

        // Get areas dan regionals yang bisa diakses user
        $areas = $this->getAccessibleAreas();
        $regionals = $this->getAccessibleRegionals($filteredParams['area']);
        
        // Get filter restrictions untuk view
        $filterRestrictions = $this->getFilterRestrictions();

        return view('buildings.index', compact(
            'buildings', 
            'areas', 
            'regionals', 
            'filterRestrictions'
        ));
    }

    public function store(Request $request)
    {
        if (!$this->checkPermission('ManageBuilding')) {
            return $this->unauthorized($request);
        }

        try {
            $validated = $request->validate([
                'building_code' => 'required|string|max:50|unique:buildings,building_code',
                'building_name' => 'required|string|max:100',
                'regional_id' => 'required|exists:regionals,regional_id',
            ], [
                'building_code.required' => 'Kode gedung harus diisi.',
                'building_code.unique' => 'Kode gedung ini sudah digunakan.',
                'building_name.required' => 'Nama gedung harus diisi.',
                'regional_id.required' => 'Regional harus dipilih.',
            ]);

            // Validasi akses regional untuk PIC GA dan PIC Operational
            if (!$this->validateRegionalAccess($validated['regional_id'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke regional tersebut.'
                ], 403);
            }

            $validated['user_id'] = auth()->id();
            $building = Building::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Gedung berhasil ditambahkan!',
                'data' => $building
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error creating building: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan di server saat menyimpan gedung.'
            ], 500);
        }
    }

    public function show($id) 
    {
        if (!$this->checkPermission('ManageBuilding')) {
            return $this->unauthorized(request());
        }
        
        $query = Building::with('regional', 'floors');
        $query = $this->applyRegionalFilter($query, 'regional_id');
        
        return $query->findOrFail($id);
    }

    public function update(Request $request, $id) 
    {
        if (!$this->checkPermission('ManageBuilding')) {
            return $this->unauthorized($request);
        }
        
        $query = Building::query();
        $query = $this->applyRegionalFilter($query, 'regional_id');
        
        $building = $query->findOrFail($id);
        
        // Validasi regional_id jika ada dalam request
        if ($request->has('regional_id')) {
            if (!$this->validateRegionalAccess($request->regional_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke regional tersebut.'
                ], 403);
            }
        }
        
        $building->update($request->only('building_name', 'building_code', 'regional_id'));
        return $building;
    }

    public function destroy($id) 
    {
        if (!$this->checkPermission('ManageBuilding')) {
            return $this->unauthorized(request());
        }
        
        $query = Building::query();
        $query = $this->applyRegionalFilter($query, 'regional_id');
        
        $building = $query->findOrFail($id);
        $building->delete();
        
        return response()->json(['message' => 'Building deleted']);
    }

    /**
     * Check if current user has permission
     */
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