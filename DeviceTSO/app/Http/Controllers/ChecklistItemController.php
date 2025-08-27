<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ChecklistItem;
use Illuminate\Http\Request;

class ChecklistItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // For API requests
        if (request()->expectsJson()) {
            return ChecklistItem::with('checkResults')->get();
        }

        // For web requests
        $checklistItems = ChecklistItem::with('checkResults')->get();
        return view('checklist-items.index', compact('checklistItems'));
    }

    /**
     * API: Get all checklist items (for AJAX)
     */
    public function apiIndex()
    {
        try {
            $checklistItems = ChecklistItem::orderBy('device_type')->orderBy('question')->get();
            return response()->json($checklistItems);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load checklist items',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('checklist-items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate device type
        $request->validate([
            'device_type' => 'required|string|max:50',
            'custom_device_type' => 'nullable|string|max:50',
            'questions' => 'required|array|min:1',
            'questions.*' => 'required|string|max:500'
        ]);

        // Determine device type (custom takes precedence)
        $deviceType = $request->custom_device_type ?: $request->device_type;

        // Create multiple checklist items
        $createdItems = [];
        foreach ($request->questions as $question) {
            if (trim($question)) { // Only create if question is not empty
                $checklistItem = ChecklistItem::create([
                    'device_type' => $deviceType,
                    'question' => trim($question)
                ]);
                $createdItems[] = $checklistItem;
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => count($createdItems) . ' checklist items created successfully.',
                'items' => $createdItems
            ], 201);
        }

        $message = count($createdItems) > 1 
            ? count($createdItems) . ' checklist items created successfully.'
            : 'Checklist item created successfully.';

        return redirect()->route('checklist-items.index')
            ->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $checklistItem = ChecklistItem::with('checkResults')->findOrFail($id);

        if (request()->expectsJson()) {
            return response()->json($checklistItem);
        }

        return view('checklist-items.show', compact('checklistItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $checklistItem = ChecklistItem::findOrFail($id);
        return view('checklist-items.edit', compact('checklistItem'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'device_type' => 'required|string|max:50',
            'custom_device_type' => 'nullable|string|max:50',
            'question' => 'required|string|max:500'
        ]);

        // Determine device type (custom takes precedence)
        $deviceType = $request->custom_device_type ?: $request->device_type;

        $checklistItem = ChecklistItem::findOrFail($id);
        $checklistItem->update([
            'device_type' => $deviceType,
            'question' => $validated['question']
        ]);

        if ($request->expectsJson()) {
            return response()->json($checklistItem);
        }

        return redirect()->route('checklist-items.index')
            ->with('success', 'Checklist item updated successfully.');
    }

    /**
     * Delete the specified checklist item (only if no check results exist)
     */
    public function delete($id)
    {
        try {
            $item = ChecklistItem::findOrFail($id);
            
            // Check if item has any check results
            $resultCount = $item->checkResults()->count();
            
            if ($resultCount > 0) {
                if (request()->ajax() || request()->expectsJson()) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Tidak dapat menghapus checklist item yang sudah memiliki hasil pengecekan'
                    ], 403);
                }
                
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus checklist item yang sudah memiliki hasil pengecekan');
            }
            
            // Delete the item (no check results exist)
            $item->delete();

            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Checklist item berhasil dihapus'
                ]);
            }

            return redirect()->route('checklist-items.index')
                ->with('success', 'Checklist item berhasil dihapus');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Gagal menghapus checklist item: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Gagal menghapus checklist item: ' . $e->getMessage());
        }
    }

    /**
     * API: Get a specific checklist item
     */
    public function apiShow($id)
    {
        $item = ChecklistItem::with('checkResults')->findOrFail($id);
        return response()->json([
            'checklist_id' => $id,
            'device_type' => $item->device_type,
            'question' => $item->question,
            'check_results_count' => $item->checkResults->count()
        ]);
    }

    /**
     * API: Update a specific checklist item
     */
    public function apiUpdate(Request $request, $id)
    {
        $request->validate([
            'device_type' => 'required|string|max:50',
            'question' => 'required|string|max:500'
        ]);

        $item = ChecklistItem::findOrFail($id);
        $item->update($request->only(['device_type', 'question']));

        return response()->json($item);
    }
}