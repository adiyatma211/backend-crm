<?php

namespace App\Http\Controllers\Api;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Testimonial::where('is_active', true)
                ->whereNull('deleted_at')
                ->orderBy('display_order', 'asc')
                ->orderBy('rating', 'desc');
            
            if ($request->has('rating')) {
                $query->where('rating', $request->rating);
            }
            
            $testimonials = $query->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Testimonials retrieved successfully',
                'data' => $testimonials,
                'count' => count($testimonials)
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving testimonials',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
