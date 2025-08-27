<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!$this->checkPermission('ManageArea')) {
            return $this->unauthorized(request());
        }

        $areas = Area::with(['user', 'regionals'])->latest()->get();
        return view('areas.index', compact('areas'));
    }

    public function store(Request $request)
    {
        if (!$this->checkPermission('ManageArea')) {
            return $this->unauthorized($request);
        }
        $validator = Validator::make($request->all(), [
            'area_name' => 'required|string|max:100|unique:areas,area_name',
        ], [
            'area_name.required' => 'Nama area harus diisi',
            'area_name.unique' => 'Nama area sudah digunakan',
            'area_name.max' => 'Nama area maksimal 100 karakter',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $area = Area::create([
                'area_name' => $request->area_name,
                'user_id' => auth()->id()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Area berhasil ditambahkan!',
                    'area' => $area
                ]);
            }
            
            return redirect()->route('areas.index')->with('success', 'Area berhasil ditambahkan!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan area: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Gagal menambahkan area: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Area $area)
    {
        $area->load(['user', 'regionals.user']);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $area
            ]);
        }
        
        return view('areas.show', compact('area'));
    }

    public function update(Request $request, Area $area)
    {
        $validator = Validator::make($request->all(), [
            'area_name' => 'required|string|max:100|unique:areas,area_name,' . $area->area_id . ',area_id',
        ], [
            'area_name.required' => 'Nama area harus diisi',
            'area_name.unique' => 'Nama area sudah digunakan',
            'area_name.max' => 'Nama area maksimal 100 karakter',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $area->update($validator->validated());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Area berhasil diperbarui!'
                ]);
            }
            
            return redirect()->route('areas.index')->with('success', 'Area berhasil diperbarui!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui area: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Gagal memperbarui area: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Area $area)
    {
        try {
            // Check if area has regionals
            if ($area->regionals()->count() > 0) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Area tidak dapat dihapus karena masih memiliki regional!'
                    ], 422);
                }
                return redirect()->back()->with('error', 'Area tidak dapat dihapus karena masih memiliki regional!');
            }

            $area->delete();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Area berhasil dihapus!'
                ]);
            }
            
            return redirect()->route('areas.index')->with('success', 'Area berhasil dihapus!');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus area: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Gagal menghapus area: ' . $e->getMessage());
        }
    }

    // API methods (untuk keperluan API jika diperlukan)
    public function apiIndex()
    {
        return Area::with('regionals')->get();
    }

    public function apiShow($id)
    {
        return Area::with('regionals')->findOrFail($id);
    }

    // Method khusus untuk mendapatkan data area untuk modal
    public function getArea(Area $area)
    {
        return response()->json([
            'success' => true,
            'data' => $area->load(['user', 'regionals'])
        ]);
    }

    // Method untuk mendapatkan semua users untuk dropdown
    public function getUsers()
    {
        $users = User::select('id', 'username', 'full_name', 'role')->get();
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }
}