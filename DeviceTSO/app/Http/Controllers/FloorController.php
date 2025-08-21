<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    /**
     * Display a listing of the floors.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $floors = Floor::orderBy('floor_name')->get();
            return response()->json($floors);
        }

        $floors = Floor::orderBy('floor_name')->get();
        return view('floors.index', compact('floors'));
    }

    /**
     * Store a newly created floor.
     */
    public function store(Request $request)
    {
        $request->validate([
            'floor_name' => 'required|string|max:50|unique:floors,floor_name'
        ]);

        $floor = Floor::create([
            'floor_name' => $request->floor_name,
            'user_id' => auth()->id()
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Lantai berhasil ditambahkan!',
                'floor' => $floor
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
}