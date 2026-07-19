<?php

namespace App\Http\Controllers;

use App\Models\AboutGalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutGalleryImageController extends Controller
{
    // Public endpoint - Get all active about gallery images
    public function index()
    {
        $images = AboutGalleryImage::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return response()->json(['data' => $images]);
    }

    // Admin endpoint - Get all about gallery images (including inactive)
    public function adminIndex()
    {
        $images = AboutGalleryImage::orderBy('sort_order', 'asc')->get();
        return response()->json(['data' => $images]);
    }

    // Admin endpoint - Create new about gallery image
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
            $maxOrder = AboutGalleryImage::max('sort_order') ?? 0;
            $validated['sort_order'] = $maxOrder + 1;
        }

        $galleryImage = AboutGalleryImage::create($validated);

        return response()->json([
            'message' => 'About gallery image created successfully',
            'data' => $galleryImage
        ], 201);
    }

    // Admin endpoint - Update about gallery image
    public function update(Request $request, $id)
    {
        $galleryImage = AboutGalleryImage::findOrFail($id);

        $validated = $request->validate([
            'image_url' => 'sometimes|string',
            'title' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $galleryImage->update($validated);

        return response()->json([
            'message' => 'About gallery image updated successfully',
            'data' => $galleryImage
        ]);
    }

    // Admin endpoint - Delete about gallery image
    public function destroy($id)
    {
        $galleryImage = AboutGalleryImage::findOrFail($id);

        // Delete the image file from storage if it's not an external URL
        if ($galleryImage->image_url && !str_starts_with($galleryImage->image_url, 'http')) {
            $path = str_replace('/storage/', '', $galleryImage->image_url);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $galleryImage->delete();

        return response()->json([
            'message' => 'About gallery image deleted successfully'
        ]);
    }

    // Admin endpoint - Upload about gallery image
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB max
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('about-gallery-images', 'public');
            $url = Storage::url($path);

            return response()->json([
                'message' => 'Image uploaded successfully',
                'url' => $url,
                'path' => $path
            ]);
        }

        return response()->json(['message' => 'No image provided'], 400);
    }

    // Admin endpoint - Reorder about gallery images
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|exists:about_gallery_images,id',
            'images.*.sort_order' => 'required|integer',
        ]);

        foreach ($validated['images'] as $imageData) {
            AboutGalleryImage::where('id', $imageData['id'])
                ->update(['sort_order' => $imageData['sort_order']]);
        }

        return response()->json([
            'message' => 'About gallery images reordered successfully'
        ]);
    }

    // Admin endpoint - Toggle active status
    public function toggleActive($id)
    {
        $galleryImage = AboutGalleryImage::findOrFail($id);
        $galleryImage->is_active = !$galleryImage->is_active;
        $galleryImage->save();

        return response()->json([
            'message' => 'About gallery image status updated successfully',
            'data' => $galleryImage
        ]);
    }
}
