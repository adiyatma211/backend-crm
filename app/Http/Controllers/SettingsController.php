<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = \App\Models\Settings::first();
        
        if (!$settings) {
            $settings = \App\Models\Settings::create([
                'site_name' => 'Sentosa CMS',
                'site_description' => 'Content Management System',
                'logo_url' => null,
                'logo_light_url' => null,
                'logo_dark_url' => null,
                'logo_alt_text' => 'Sentosa',
                'enable_dark_mode' => false,
                'enable_compact_mode' => false,
            ]);
        }

        return view('settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'logo_alt_text' => 'nullable|string|max:255',
        ]);

        $settings = \App\Models\Settings::first();
        
        $settingsData = [
            'site_name' => $request->site_name,
            'site_description' => $request->site_description,
            'logo_alt_text' => $request->logo_alt_text,
        ];

        if ($request->hasFile('logo')) {
            if ($settings->logo_url && file_exists(public_path($settings->logo_url))) {
                unlink(public_path($settings->logo_url));
            }
            
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('logos'), $filename);
            $settingsData['logo_url'] = 'logos/' . $filename;
            $settingsData['logo_light_url'] = 'logos/' . $filename;
            $settingsData['logo_dark_url'] = 'logos/' . $filename;
        }

        $settings->update($settingsData);

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'logo_alt_text' => 'nullable|string|max:255',
            'enable_dark_mode' => 'boolean',
            'enable_compact_mode' => 'boolean',
        ]);

        $settings = \App\Models\Settings::first();

        $settings->update([
            'site_name' => $request->site_name,
            'site_description' => $request->site_description,
            'logo_alt_text' => $request->logo_alt_text,
            'enable_dark_mode' => $request->boolean('enable_dark_mode', $settings->enable_dark_mode),
            'enable_compact_mode' => $request->boolean('enable_compact_mode', $settings->enable_compact_mode),
        ]);

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully!');
    }

    public function uploadLogo(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|in:general,light,dark',
                'logo' => 'required|image|mimes:png,jpg,jpeg,svg|max:2048',
            ]);

            $type = $request->type;
            $file = $request->file('logo');

            $settings = \App\Models\Settings::first();

            $filename = 'logo_' . $type . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('logos'), $filename);
            $path = 'logos/' . $filename;

            $updateData = [
                'logo_url' => $path,
            ];

            if ($type === 'light') {
                if ($settings->logo_light_url && file_exists(public_path($settings->logo_light_url))) {
                    unlink(public_path($settings->logo_light_url));
                }
                $updateData['logo_light_url'] = $path;
            } elseif ($type === 'dark') {
                if ($settings->logo_dark_url && file_exists(public_path($settings->logo_dark_url))) {
                    unlink(public_path($settings->logo_dark_url));
                }
                $updateData['logo_dark_url'] = $path;
            } else {
                if ($settings->logo_url && file_exists(public_path($settings->logo_url))) {
                    unlink(public_path($settings->logo_url));
                }
                if ($settings->logo_light_url && file_exists(public_path($settings->logo_light_url))) {
                    unlink(public_path($settings->logo_light_url));
                }
                if ($settings->logo_dark_url && file_exists(public_path($settings->logo_dark_url))) {
                    unlink(public_path($settings->logo_dark_url));
                }
                $updateData['logo_light_url'] = $path;
                $updateData['logo_dark_url'] = $path;
            }

            $settings->update($updateData);

            // Check if this is an AJAX request
            if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'Logo uploaded successfully',
                    'data' => [
                        'path' => $path,
                        'url' => asset($path),
                        'preview_url' => asset($path),
                    ]
                ], 200);
            }

            return redirect()->route('settings.index')->with('success', 'Logo uploaded successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return JSON error for AJAX requests
            if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            // Redirect with errors for regular form submissions
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Return JSON error for AJAX requests
            if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Error uploading logo: ' . $e->getMessage()
                ], 500);
            }

            // Redirect with error for regular form submissions
            return redirect()->back()->with('error', 'Error uploading logo: ' . $e->getMessage())->withInput();
        }
    }

    public function resetLogo(Request $request)
    {
        try {
            $type = $request->query('type', 'general');
            $settings = \App\Models\Settings::first();

            if ($type === 'light') {
                if ($settings->logo_light_url && file_exists(public_path($settings->logo_light_url))) {
                    unlink(public_path($settings->logo_light_url));
                }
                $settings->update([
                    'logo_light_url' => null,
                ]);
                $message = 'Light mode logo reset successfully';
            } elseif ($type === 'dark') {
                if ($settings->logo_dark_url && file_exists(public_path($settings->logo_dark_url))) {
                    unlink(public_path($settings->logo_dark_url));
                }
                $settings->update([
                    'logo_dark_url' => null,
                ]);
                $message = 'Dark mode logo reset successfully';
            } else {
                // General - reset all logos
                if ($settings->logo_url && file_exists(public_path($settings->logo_url))) {
                    unlink(public_path($settings->logo_url));
                }
                if ($settings->logo_light_url && file_exists(public_path($settings->logo_light_url))) {
                    unlink(public_path($settings->logo_light_url));
                }
                if ($settings->logo_dark_url && file_exists(public_path($settings->logo_dark_url))) {
                    unlink(public_path($settings->logo_dark_url));
                }
                $settings->update([
                    'logo_url' => null,
                    'logo_light_url' => null,
                    'logo_dark_url' => null,
                ]);
                $message = 'Logo reset successfully';
            }

            // Return JSON for AJAX requests
            if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => [
                        'type' => $type,
                        'logo_url' => $settings->logo_url,
                        'logo_light_url' => $settings->logo_light_url,
                        'logo_dark_url' => $settings->logo_dark_url,
                    ]
                ]);
            }

            return redirect()->route('settings.index')->with('success', $message);

        } catch (\Exception $e) {
            // Return JSON error for AJAX requests
            if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Error resetting logo: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('settings.index')->with('error', 'Error resetting logo: ' . $e->getMessage());
        }
    }

    public function deleteLogo(Request $request)
    {
        $type = $request->type ?? 'general';

        $settings = \App\Models\Settings::first();

        if ($type === 'general') {
            if ($settings->logo_url && file_exists(public_path($settings->logo_url))) {
                unlink(public_path($settings->logo_url));
            }
            if ($settings->logo_light_url && file_exists(public_path($settings->logo_light_url))) {
                unlink(public_path($settings->logo_light_url));
            }
            if ($settings->logo_dark_url && file_exists(public_path($settings->logo_dark_url))) {
                unlink(public_path($settings->logo_dark_url));
            }
            $settings->update([
                'logo_url' => null,
                'logo_light_url' => null,
                'logo_dark_url' => null,
            ]);
        } elseif ($type === 'light') {
            if ($settings->logo_light_url && file_exists(public_path($settings->logo_light_url))) {
                unlink(public_path($settings->logo_light_url));
            }
            $settings->update([
                'logo_light_url' => null,
            ]);
        } elseif ($type === 'dark') {
            if ($settings->logo_dark_url && file_exists(public_path($settings->logo_dark_url))) {
                unlink(public_path($settings->logo_dark_url));
            }
            $settings->update([
                'logo_dark_url' => null,
            ]);
        }

        return redirect()->route('settings.index')->with('success', 'Logo deleted successfully!');
    }

    public function getLogoPreview(Request $request)
    {
        $settings = \App\Models\Settings::first();
        $logoUrl = $settings ? $settings->getLogoUrl(false) : null;
        
        if ($logoUrl) {
            return response()->json([
                'success' => true,
                'data' => [
                    'logo_url' => $logoUrl,
                    'preview_url' => asset($logoUrl),
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'logo_url' => $logoUrl,
                'logo_light_url' => $settings->logo_light_url,
                'logo_dark_url' => $settings->logo_dark_url,
            ],
        ]);
    }
}
