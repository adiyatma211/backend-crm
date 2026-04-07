<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        try {
            $settings = Settings::first();

            if ($settings) {
                $settings->logo_url_full = $settings->logo_url ? asset($settings->logo_url) : null;
                $settings->logo_light_url_full = $settings->logo_light_url ? asset($settings->logo_light_url) : null;
                $settings->logo_dark_url_full = $settings->logo_dark_url ? asset($settings->logo_dark_url) : null;
            }

            return response()->json([
                'success' => true,
                'message' => 'Settings retrieved successfully',
                'data' => $settings
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving settings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getLogo(Request $request)
    {
        try {
            $settings = Settings::first();

            $darkMode = $request->has('dark_mode') ? $request->dark_mode : false;

            return response()->json([
                'success' => true,
                'message' => 'Logo URL retrieved successfully',
                'data' => [
                    'logo_url' => $settings->logo_url ? asset($settings->logo_url) : null,
                    'logo_light_url' => $settings->logo_light_url ? asset($settings->logo_light_url) : null,
                    'logo_dark_url' => $settings->logo_dark_url ? asset($settings->logo_dark_url) : null,
                    'logo_url_full' => $settings->getLogoUrl($darkMode) ? asset($settings->getLogoUrl($darkMode)) : null,
                    'logo_alt_text' => $settings->logo_alt_text,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving logo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $settings = Settings::first();

            $validated = $request->validate([
                'site_name' => 'sometimes|string|max:255',
                'site_description' => 'sometimes|nullable|string|max:500',
                'logo_alt_text' => 'sometimes|nullable|string|max:255',
                'enable_dark_mode' => 'sometimes|boolean',
                'enable_compact_mode' => 'sometimes|boolean',
            ]);

            $settings->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully',
                'data' => $settings
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating settings',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
