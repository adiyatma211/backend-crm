<?php

namespace App\Http\Controllers\Api;

use App\Models\ProcessInfo;
use Illuminate\Routing\Controller;

class ProcessInfoController extends Controller
{
    public function index()
    {
        try {
            $processInfos = ProcessInfo::where('is_active', true)
                ->whereNull('deleted_at')
                ->orderBy('display_order', 'asc')
                ->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Process info retrieved successfully',
                'data' => $processInfos,
                'count' => count($processInfos)
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving process info',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
