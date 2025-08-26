<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Area;
use App\Models\Regional;
use Illuminate\Http\Request;

class BuildingController extends Controller {

    public function index(Request $request)
    {
        $query = Building::query();

        if ($request->filled('regional')) {
            $query->where('regional_id', $request->regional);
        } elseif ($request->filled('area')) {
            $query->whereHas('regional', function ($q) use ($request) {
                $q->where('area_id', $request->area);
            });
        }

        $buildings = $query->with('regional.area')
                        ->withCount('floors')  // Add this line
                        ->get();

        $areas = Area::orderBy('area_name')->get();
        $regionals = $request->filled('area') 
            ? Regional::where('area_id', $request->area)->orderBy('regional_name')->get()
            : Regional::orderBy('regional_name')->get();

        return view('buildings.index', compact('buildings', 'areas', 'regionals'));
    }

    public function store(Request $request)
    {
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

            // Tambahkan user_id dari user yang sedang login secara otomatis
            $validated['user_id'] = auth()->id();

            $building = Building::create($validated);

            // Kirim respons JSON yang konsisten
            return response()->json([
                'success' => true,
                'message' => 'Gedung berhasil ditambahkan!',
                'data' => $building
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangani error validasi
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // Tangani error server lainnya
            Log::error('Error creating building: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan di server saat menyimpan gedung.'
            ], 500);
        }
    }

    public function show($id) {
        return Building::with('regional', 'floors')->findOrFail($id);
    }

    public function update(Request $request, $id) {
        $building = Building::findOrFail($id);
        $building->update($request->only('building_name', 'building_code'));
        return $building;
    }

    public function destroy($id) {
        Building::destroy($id);
        return response()->json(['message' => 'Building deleted']);
    }
}