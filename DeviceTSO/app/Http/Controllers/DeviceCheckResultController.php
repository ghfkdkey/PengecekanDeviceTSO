<?php

namespace App\Http\Controllers;

use App\Models\DeviceCheckResult;
use Illuminate\Http\Request;

class DeviceCheckResultController extends Controller
{
    public function index()
    {
        return DeviceCheckResult::with(['device', 'checklistItem', 'user'])->orderBy('checked_at', 'desc')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,device_id',
            'checklist_id' => 'required|exists:checklist_items,checklist_id',
            'user_id' => 'required|exists:users,user_id',
            'status' => 'required|string|max:10',
            'notes' => 'nullable|string',
            'checked_at' => 'nullable|date',
        ]);

        return DeviceCheckResult::create($request->all());
    }

    public function show($id)
    {
        return DeviceCheckResult::with(['device', 'checklistItem', 'user'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $result = DeviceCheckResult::findOrFail($id);
        $result->update($request->all());
        return $result;
    }

    public function destroy($id)
    {
        return DeviceCheckResult::destroy($id);
    }

    public function webIndex()
    {
        return view('device-check-results.index');
    }
}