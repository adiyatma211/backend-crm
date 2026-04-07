<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('display_order', 'asc')->get();
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:2|max:255|unique:clients',
            'initial' => 'required|min:2|max:10',
            'logo_url' => 'nullable|max:500',
            'display_order' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        Client::create([
            'name' => $validated['name'],
            'initial' => $validated['initial'],
            'logo_url' => $validated['logo_url'],
            'display_order' => $validated['display_order'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('clients.index')->with('success', 'Client created successfully!');
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|min:2|max:255|unique:clients,name,' . $client->id,
            'initial' => 'required|min:2|max:10',
            'logo_url' => 'nullable|max:500',
            'display_order' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $client->update([
            'name' => $validated['name'],
            'initial' => $validated['initial'],
            'logo_url' => $validated['logo_url'],
            'display_order' => $validated['display_order'],
            'is_active' => $validated['is_active'] ?? $client->is_active,
        ]);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully!');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully!');
    }
}
