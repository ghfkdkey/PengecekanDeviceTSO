<?php

namespace App\Http\Controllers;

use App\Models\Regional;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegionalController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        
        // Get areas based on user role
        if ($user->isAdmin()) {
            // Admin can see all areas and their regionals
            $areas = Area::all();
            $regionals = Regional::with(['area', 'creator'])->get();
        } else if ($user->isGA()) {
            // PIC GA can only see their assigned regional's area
            $areas = Area::whereHas('regionals', function($query) use ($user) {
                $query->where('regional_id', $user->regional_id);
            })->get();
            $regionals = Regional::with(['area', 'creator'])
                ->where('regional_id', $user->regional_id)
                ->get();
        } else {
            // PIC Operational can only see their assigned regional's area
            $areas = Area::whereHas('regionals', function($query) use ($user) {
                $query->where('regional_id', $user->regional_id);
            })->get();
            $regionals = Regional::with(['area', 'creator'])
                ->where('regional_id', $user->regional_id)
                ->get();
        }

        return view('regionals.index', compact('regionals', 'areas'));
    }

    /**
     * Store a newly created regional
     */
    public function store(Request $request)
    {
        if (!$this->checkPermission('ManageArea')) {
            return $this->unauthorized($request);
        }

        try {
            $validated = $request->validate([
                'regional_name' => 'required|string|max:100|unique:regionals,regional_name',
                'area_id' => 'required|exists:areas,area_id',
            ], [
                'regional_name.required' => 'Nama regional harus diisi',
                'regional_name.string' => 'Nama regional harus berupa teks',
                'regional_name.max' => 'Nama regional maksimal 100 karakter',
                'regional_name.unique' => 'Nama regional sudah digunakan',
                'area_id.required' => 'Area harus dipilih',
                'area_id.exists' => 'Area yang dipilih tidak valid',
            ]);
            
            $validated['user_id'] = auth()->id(); 
            $regional = Regional::create($validated);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Regional berhasil ditambahkan',
                    'data' => $regional->load(['area', 'buildings'])
                ]);
            }
            
            return redirect()->route('regionals.index')
                            ->with('success', 'Regional berhasil ditambahkan');
                            
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            Log::error('Error in RegionalController@store: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan regional: ' . $e->getMessage() // Pesan lebih detail untuk debug
                ], 500);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat menyimpan regional');
        }
    }

    /**
     * Display the specified regional
     */
    public function show($id)
    {
        try {
            $regional = Regional::with(['area', 'buildings.devices'])
                              ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $regional
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Regional tidak ditemukan'
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('Error in RegionalController@show: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data regional'
            ], 500);
        }
    }

    /**
     * Update the specified regional
     */
    public function update(Request $request, $id)
    {
        if (!$this->checkPermission('manage_regional')) {
            return $this->unauthorized();
        }

        try {
            $regional = Regional::findOrFail($id);
            
            $validated = $request->validate([
                'regional_name' => 'required|string|max:100|unique:regionals,regional_name,' . $id . ',regional_id',
                'area_id' => 'required|exists:areas,area_id',
            ], [
                'regional_name.required' => 'Nama regional harus diisi',
                'regional_name.string' => 'Nama regional harus berupa teks',
                'regional_name.max' => 'Nama regional maksimal 100 karakter',
                'regional_name.unique' => 'Nama regional sudah digunakan',
                'area_id.required' => 'Area harus dipilih',
                'area_id.exists' => 'Area yang dipilih tidak valid',
            ]);
            
            $regional->update($validated);
            $regional->load(['area', 'buildings']);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Regional berhasil diperbarui',
                    'data' => $regional
                ]);
            }
            
            return redirect()->route('regionals.index')
                           ->with('success', 'Regional berhasil diperbarui');
                           
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Regional tidak ditemukan'
                ], 404);
            }
            
            return back()->with('error', 'Regional tidak ditemukan');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            Log::error('Error in RegionalController@update: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui regional'
                ], 500);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat memperbarui regional');
        }
    }

    /**
     * Remove the specified regional
     */
    public function destroy(Request $request, $id)
    {
        if (!$this->checkPermission('manage_regional')) {
            return $this->unauthorized();
        }

        try {
            $regional = Regional::findOrFail($id);
            
            // Check if regional has buildings
            if ($regional->buildings()->count() > 0) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Regional tidak dapat dihapus karena masih memiliki building terkait'
                    ], 400);
                }
                
                return back()->with('error', 'Regional tidak dapat dihapus karena masih memiliki building terkait');
            }
            
            $regionalName = $regional->regional_name;
            $regional->delete();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Regional '{$regionalName}' berhasil dihapus"
                ]);
            }
            
            return redirect()->route('regionals.index')
                           ->with('success', "Regional '{$regionalName}' berhasil dihapus");
                           
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Regional tidak ditemukan'
                ], 404);
            }
            
            return back()->with('error', 'Regional tidak ditemukan');
            
        } catch (\Exception $e) {
            Log::error('Error in RegionalController@destroy: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus regional'
                ], 500);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat menghapus regional');
        }
    }

    /**
     * Get regionals by area (for API/AJAX)
     */
    public function getByArea(Request $request, $areaId)
    {
        try {
            $regionals = Regional::where('area_id', $areaId)
                               ->orderBy('regional_name')
                               ->get(['regional_id', 'regional_name']);
            
            return response()->json([
                'success' => true,
                'data' => $regionals
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in RegionalController@getByArea: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data regional'
            ], 500);
        }
    }

    /**
     * Get regional statistics
     */
    public function getStatistics()
    {
        try {
            $statistics = [
                'total_regionals' => Regional::count(),
                'regionals_by_area' => Regional::selectRaw('areas.area_name, COUNT(*) as total')
                    ->join('areas', 'regionals.area_id', '=', 'areas.area_id')
                    ->groupBy('areas.area_id', 'areas.area_name')
                    ->orderBy('total', 'desc')
                    ->get(),
                'total_buildings' => Regional::withCount('buildings')->get()->sum('buildings_count'),
                'regionals_with_buildings' => Regional::has('buildings')->count(),
                'regionals_without_buildings' => Regional::doesntHave('buildings')->count(),
            ];
            
            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in RegionalController@getStatistics: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat statistik'
            ], 500);
        }
    }

    public function apiIndex()
    {
        try {
            $user = auth()->user();
            
            if ($user->isAdmin()) {
                // Admin can see all regionals
                $regionals = Regional::select('regional_id', 'regional_name')
                                ->orderBy('regional_name')
                                ->get();
            } else if ($user->isGA()) {
                // PIC GA can only see their assigned regional
                $regionals = Regional::select('regional_id', 'regional_name')
                                ->where('regional_id', $user->regional_id)
                                ->orderBy('regional_name')
                                ->get();
            } else {
                // PIC Operational can only see their assigned regional
                $regionals = Regional::select('regional_id', 'regional_name')
                                ->where('regional_id', $user->regional_id)
                                ->orderBy('regional_name')
                                ->get();
            }

            return response()->json($regionals);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load regionals: ' . $e->getMessage()
            ], 500);
        }
    }
}