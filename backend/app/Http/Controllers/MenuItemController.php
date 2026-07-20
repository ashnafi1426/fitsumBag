<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = MenuItem::with('category')->where('is_available', true);

            if ($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            if ($request->has('featured')) {
                $query->where('is_featured', true);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('name_amharic', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            $items = $query->orderBy('sort_order')->get();

            return response()->json(['data' => $items]);
        } catch (\Exception $e) {
            \Log::error('MenuItemController@index error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'message' => 'Failed to fetch menu items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function adminIndex(Request $request)
    {
        $query = MenuItem::with('category');

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $items = $query->orderBy('sort_order')->get();

        return response()->json(['data' => $items]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'name_amharic' => 'required|string|max:255',
            'description' => 'nullable|string',
            'description_amharic' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|string',
            'tags' => 'nullable|array',
            'features' => 'nullable|array',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $item = MenuItem::create($validated);

        return response()->json([
            'message' => 'Menu item created successfully',
            'data' => $item->load('category')
        ], 201);
    }

    public function show($id)
    {
        $item = MenuItem::with('category')->findOrFail($id);
        return response()->json(['data' => $item]);
    }

    public function update(Request $request, $id)
    {
        $item = MenuItem::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'name_amharic' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'description_amharic' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|string',
            'tags' => 'nullable|array',
            'features' => 'nullable|array',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $item->update($validated);

        return response()->json([
            'message' => 'Menu item updated successfully',
            'data' => $item->load('category')
        ]);
    }

    public function destroy($id)
    {
        $item = MenuItem::findOrFail($id);

        // Delete image if exists
        if ($item->image && Storage::exists($item->image)) {
            Storage::delete($item->image);
        }

        $item->delete();

        return response()->json([
            'message' => 'Menu item deleted successfully'
        ]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menu-items', 'public');
            $url = Storage::url($path);

            return response()->json([
                'message' => 'Image uploaded successfully',
                'url' => $url,
                'path' => $path
            ]);
        }

        return response()->json(['message' => 'No image provided'], 400);
    }

    public function toggleAvailability($id)
    {
        $item = MenuItem::findOrFail($id);
        $item->is_available = !$item->is_available;
        $item->save();

        return response()->json([
            'message' => 'Availability updated successfully',
            'data' => $item
        ]);
    }

    public function toggleFeatured($id)
    {
        $item = MenuItem::findOrFail($id);
        $item->is_featured = !$item->is_featured;
        $item->save();

        return response()->json([
            'message' => 'Featured status updated successfully',
            'data' => $item
        ]);
    }
}
