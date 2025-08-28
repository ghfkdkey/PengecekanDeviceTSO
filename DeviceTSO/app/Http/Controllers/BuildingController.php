<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Area;
use App\Models\Regional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BuildingController extends Controller 
{
    public function __construct()
    {
        $this->middleware('auth');
        // Hapus debug dari constructor, pindah ke method yang membutuhkan
    }

    public function index(Request $request)
    {
        // Debug untuk troubleshooting - hapus setelah masalah teratasi
        // dd(auth()->user()->debugPermissions());
        
        // Ganti 'ManageArea' dengan 'ManageBuilding' sesuai debug output
        if (!$this->checkPermission('ManageBuilding')) {
            return $this->unauthorized($request);
        }

        $query = Building::query();

        if ($request->filled('regional')) {
            $query->where('regional_id', $request->regional);
        } elseif ($request->filled('area')) {
            $query->whereHas('regional', function ($q) use ($request) {
                $q->where('area_id', $request->area);
            });
        }

        $buildings = $query->with('regional.area')
                        ->withCount('floors')
                        ->get();

        $areas = Area::orderBy('area_name')->get();
        $regionals = $request->filled('area') 
            ? Regional::where('area_id', $request->area)->orderBy('regional_name')->get()
            : Regional::orderBy('regional_name')->get();

        return view('buildings.index', compact('buildings', 'areas', 'regionals'));
    }

    public function store(Request $request)
    {
        // Ganti 'ManageArea' dengan 'ManageBuilding'
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
        return Building::with('regional', 'floors')->findOrFail($id);
    }

    public function update(Request $request, $id) 
    {
        if (!$this->checkPermission('ManageBuilding')) {
            return $this->unauthorized($request);
        }
        
        $building = Building::findOrFail($id);
        $building->update($request->only('building_name', 'building_code'));
        return $building;
    }

    public function destroy($id) 
    {
        if (!$this->checkPermission('ManageBuilding')) {
            return $this->unauthorized(request());
        }
        
        Building::destroy($id);
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