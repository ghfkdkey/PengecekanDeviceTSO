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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $checklistItem = ChecklistItem::findOrFail($id);
        $checklistItem->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Checklist item deleted successfully.']);
        }

        return redirect()->route('checklist-items.index')
            ->with('success', 'Checklist item deleted successfully.');
    }
}