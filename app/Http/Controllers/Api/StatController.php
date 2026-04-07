<?php

namespace App\Http\Controllers\Api;

use App\Models\Stat;
use Illuminate\Routing\Controller;

class StatController extends Controller
{
    public function index()
    {
        try {
            $stats = Stat::where('is_active', true)
                ->whereNull('deleted_at')
                ->orderBy('display_order', 'asc')
                ->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Stats retrieved successfully',
                'data' => $stats,
                'count' => count($stats)
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving stats',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
