<?php

namespace App\Http\Controllers;

use App\Models\RestaurantSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RestaurantSettingController extends Controller
{
    public function index()
    {
        try {
            $settings = RestaurantSetting::first();

            if (!$settings) {
                $settings = RestaurantSetting::create([
                    'name' => 'Royal Leather',
                    'tagline' => 'Crafted Excellence, Timeless Elegance',
                    'slug' => 'royal-leather',
                    'currency' => 'USD',
                    'language' => 'en',
                ]);
            }

            return response()->json(['data' => $settings]);
        } catch (\Exception $e) {
            \Log::error('RestaurantSettingController@index error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'message' => 'Failed to fetch settings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $settings = RestaurantSetting::first();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'slug' => 'sometimes|string|unique:restaurant_settings,slug,' . ($settings ? $settings->id : ''),
            'logo' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'currency' => 'sometimes|string|max:10',
            'language' => 'sometimes|string|max:10',
            'is_active' => 'boolean',
            'business_hours' => 'nullable|array',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
            'telegram_url' => 'nullable|url|max:255',
        ]);

        if ($settings) {
            $settings->update($validated);
        } else {
            $settings = RestaurantSetting::create($validated);
        }

        return response()->json([
            'message' => 'Settings updated successfully',
            'data' => $settings
        ]);
    }

    public function uploadLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $settings = RestaurantSetting::first();

            // Delete old logo if exists
            if ($settings && $settings->logo && Storage::exists($settings->logo)) {
                Storage::delete($settings->logo);
            }

            $path = $request->file('logo')->store('logos', 'public');
            $url = Storage::url($path);

            if ($settings) {
                $settings->update(['logo' => $url]);
            }

            return response()->json([
                'message' => 'Logo uploaded successfully',
                'url' => $url,
                'path' => $path
            ]);
        }

        return response()->json(['message' => 'No logo provided'], 400);
    }
}
