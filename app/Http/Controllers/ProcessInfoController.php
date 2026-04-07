<?php

namespace App\Http\Controllers;

use App\Models\ProcessInfo;
use Illuminate\Http\Request;

class ProcessInfoController extends Controller
{
    public function index()
    {
        $processInfos = ProcessInfo::orderBy('display_order', 'asc')->get();
        return view('process-info.index', compact('processInfos'));
    }

    public function create()
    {
        return view('process-info.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|max:255',
            'value' => 'required|numeric',
            'unit' => 'required|max:50',
            'display_order' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        ProcessInfo::create([
            'label' => $validated['label'],
            'value' => $validated['value'],
            'unit' => $validated['unit'],
            'display_order' => $validated['display_order'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('process-info.index')->with('success', 'Process info created successfully!');
    }

    public function edit(ProcessInfo $processInfo)
    {
        return view('process-info.edit', compact('processInfo'));
    }

    public function update(Request $request, ProcessInfo $processInfo)
    {
        $validated = $request->validate([
            'label' => 'required|max:255',
            'value' => 'required|numeric',
            'unit' => 'required|max:50',
            'display_order' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $processInfo->update([
            'label' => $validated['label'],
            'value' => $validated['value'],
            'unit' => $validated['unit'],
            'display_order' => $validated['display_order'],
            'is_active' => $validated['is_active'] ?? $processInfo->is_active,
        ]);

        return redirect()->route('process-info.index')->with('success', 'Process info updated successfully!');
    }

    public function destroy(ProcessInfo $processInfo)
    {
        $processInfo->delete();

        return redirect()->route('process-info.index')->with('success', 'Process info deleted successfully!');
    }
}
