<?php

namespace App\Http\Controllers;

use App\Models\Stat;
use Illuminate\Http\Request;

class StatController extends Controller
{
    public function index()
    {
        $stats = Stat::orderBy('display_order', 'asc')->get();
        return view('stats.index', compact('stats'));
    }

    public function create()
    {
        return view('stats.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'value' => 'required|max:100',
            'label' => 'required|max:255',
            'display_order' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        Stat::create([
            'value' => $validated['value'],
            'label' => $validated['label'],
            'display_order' => $validated['display_order'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('stats.index')->with('success', 'Stat created successfully!');
    }

    public function edit(Stat $stat)
    {
        return view('stats.edit', compact('stat'));
    }

    public function update(Request $request, Stat $stat)
    {
        $validated = $request->validate([
            'value' => 'required|max:100',
            'label' => 'required|max:255',
            'display_order' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $stat->update([
            'value' => $validated['value'],
            'label' => $validated['label'],
            'display_order' => $validated['display_order'],
            'is_active' => $validated['is_active'] ?? $stat->is_active,
        ]);

        return redirect()->route('stats.index')->with('success', 'Stat updated successfully!');
    }

    public function destroy(Stat $stat)
    {
        $stat->delete();

        return redirect()->route('stats.index')->with('success', 'Stat deleted successfully!');
    }
}
