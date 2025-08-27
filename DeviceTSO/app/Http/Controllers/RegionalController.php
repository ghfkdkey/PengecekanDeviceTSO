<?php

namespace App\Http\Controllers;

use App\Models\Regional;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegionalController extends Controller
{
    /**
     * Display a listing of regionals
     */
    public function index(Request $request)
    {
        try {
            // Get all areas for dropdown
            $areas = Area::orderBy('area_name')->get();
            
            // Build query for regionals
            $query = Regional::with(['area', 'buildings']);
            
            // Filter by area if specified
            if ($request->has('area') && !empty($request->area)) {
                $query->where('area_id', $request->area);
            }
            
            // Get regionals with relationships
            $regionals = $query->orderBy('regional_name')->get();
            
            return view('regionals.index', compact('regionals', 'areas'));
            
        } catch (\Exception $e) {
            Log::error('Error in RegionalController@index: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data regional');
        }
    }

    /**
     * Store a newly created regional
     */
    public function store(Request $request)
    {
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
}