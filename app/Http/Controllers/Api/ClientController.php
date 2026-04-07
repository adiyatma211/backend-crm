<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use Illuminate\Routing\Controller;

class ClientController extends Controller
{
    public function index()
    {
        try {
            $clients = Client::where('is_active', true)
                ->whereNull('deleted_at')
                ->orderBy('display_order', 'asc')
                ->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Clients retrieved successfully',
                'data' => $clients,
                'count' => count($clients)
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving clients',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
