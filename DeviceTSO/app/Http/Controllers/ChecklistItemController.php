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
        $validated = $request->validate([
            'device_type' => 'required|string|max:50',
            'question' => 'required|string|max:255'
        ]);

        $checklistItem = ChecklistItem::create($validated);

        if ($request->expectsJson()) {
            return response()->json($checklistItem, 201);
        }

        return redirect()->route('checklist-items.index')
            ->with('success', 'Checklist item created successfully.');
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
            'question' => 'required|string|max:255'
        ]);

        $checklistItem = ChecklistItem::findOrFail($id);
        $checklistItem->update($validated);

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