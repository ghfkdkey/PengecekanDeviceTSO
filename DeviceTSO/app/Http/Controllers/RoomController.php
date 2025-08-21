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
}