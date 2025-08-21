<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Floor;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = \App\Models\Room::with('floor')->get();
        $floors = \App\Models\Floor::orderBy('floor_name')->get();
        
        return view('rooms.index', compact('rooms', 'floors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'floor_id' => 'required|exists:floors,floor_id',
            'room_name' => 'required|string|max:100'
        ]);
        
        // TAMBAHKAN user_id dari user yang sedang login
        $validated['user_id'] = auth()->id();

        $room = Room::create($validated); // Gunakan $validated bukan $request->all()

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Room berhasil ditambahkan.',
                'room' => $room
            ]);
        }

        return redirect()->route('rooms.index')
                         ->with('success', 'Room berhasil ditambahkan.');
    }

    public function show($id)
    {
        $room = Room::with('floor')->findOrFail($id);
        return view('rooms.show', compact('room'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'floor_id' => 'required|exists:floors,floor_id',
            'room_name' => 'required|string|max:100'
        ]);
        
        $room = Room::findOrFail($id);
        $room->update($validated); // Gunakan $validated bukan $request->all()

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Room berhasil diperbarui.',
                'room' => $room
            ]);
        }

        return redirect()->route('rooms.index')
                         ->with('success', 'Room berhasil diperbarui.');
    }

    public function destroy(Request $request, $id) // Tambahkan parameter Request $request
    {
        Room::destroy($id);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Room berhasil dihapus.'
            ]);
        }
    
        return redirect()->route('rooms.index')
                         ->with('success', 'Room berhasil dihapus.');
    }
}