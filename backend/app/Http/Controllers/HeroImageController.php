<?php

namespace App\Http\Controllers;

use App\Models\HeroImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroImageController extends Controller
{
    // Public endpoint - Get all active hero images
    public function index()
    {
        $images = HeroImage::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return response()->json(['data' => $images]);
    }

    // Admin endpoint - Get all hero images (including inactive)
    public function adminIndex()
    {
        $images = HeroImage::orderBy('sort_order', 'asc')->get();
        return response()->json(['data' => $images]);
    }

    // Admin endpoint - Create new hero image
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image_url' => 'required|string',
            'title' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        // Auto-increment sort_order if not provided
        if (!isset($validated['sort_order'])) {
            $maxOrder = HeroImage::max('sort_order') ?? 0;
            $validated['sort_order'] = $maxOrder + 1;
        }

        $heroImage = HeroImage::create($validated);

        return response()->json([
            'message' => 'Hero image created successfully',
            'data' => $heroImage
        ], 201);
    }

    // Admin endpoint - Update hero image
    public function update(Request $request, $id)
    {
        $heroImage = HeroImage::findOrFail($id);

        $validated = $request->validate([
            'image_url' => 'sometimes|string',
            'title' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $heroImage->update($validated);

        return response()->json([
            'message' => 'Hero image updated successfully',
            'data' => $heroImage
        ]);
    }

    // Admin endpoint - Delete hero image
    public function destroy($id)
    {
        $heroImage = HeroImage::findOrFail($id);

        // Delete the image file from storage if it's not an external URL
        if ($heroImage->image_url && !str_starts_with($heroImage->image_url, 'http')) {
            $path = str_replace('/storage/', '', $heroImage->image_url);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $heroImage->delete();

        return response()->json([
            'message' => 'Hero image deleted successfully'
        ]);
    }

    // Admin endpoint - Upload hero image
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB max
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('hero-images', 'public');
            $url = Storage::url($path);

            return response()->json([
                'message' => 'Image uploaded successfully',
                'url' => $url,
                'path' => $path
            ]);
        }

        return response()->json(['message' => 'No image provided'], 400);
    }

    // Admin endpoint - Reorder hero images
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|exists:hero_images,id',
            'images.*.sort_order' => 'required|integer',
        ]);

        foreach ($validated['images'] as $imageData) {
            HeroImage::where('id', $imageData['id'])
                ->update(['sort_order' => $imageData['sort_order']]);
        }

        return response()->json([
            'message' => 'Hero images reordered successfully'
        ]);
    }

    // Admin endpoint - Toggle active status
    public function toggleActive($id)
    {
        $heroImage = HeroImage::findOrFail($id);
        $heroImage->is_active = !$heroImage->is_active;
        $heroImage->save();

        return response()->json([
            'message' => 'Hero image status updated successfully',
            'data' => $heroImage
        ]);
    }
}
