<?php

namespace App\Http\Controllers;

use App\Models\OrderRequest;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class OrderRequestController extends Controller
{
    // Public endpoint - Submit order request
    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'customer_phone' => 'required|string|max:20',
            'description' => 'required|string|max:1000',
        ]);

        // Get menu item details
        $menuItem = MenuItem::findOrFail($validated['menu_item_id']);

        // Create order request with item details
        $orderRequest = OrderRequest::create([
            'menu_item_id' => $validated['menu_item_id'],
            'customer_phone' => $validated['customer_phone'],
            'description' => $validated['description'],
            'item_name' => $menuItem->name,
            'item_image' => $menuItem->image,
            'item_price' => $menuItem->price,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Order request submitted successfully',
            'data' => $orderRequest
        ], 201);
    }

    // Admin endpoint - Get all order requests
    public function index(Request $request)
    {
        $query = OrderRequest::with('menuItem')->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        return response()->json(['data' => $orders]);
    }

    // Admin endpoint - Get single order request
    public function show($id)
    {
        $order = OrderRequest::with('menuItem')->findOrFail($id);
        return response()->json(['data' => $order]);
    }

    // Admin endpoint - Update order request status
    public function updateStatus(Request $request, $id)
    {
        $order = OrderRequest::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,contacted,completed,cancelled',
            'admin_notes' => 'nullable|string',
        ]);

        $order->update($validated);

        return response()->json([
            'message' => 'Order request status updated successfully',
            'data' => $order
        ]);
    }

    // Admin endpoint - Delete order request
    public function destroy($id)
    {
        $order = OrderRequest::findOrFail($id);
        $order->delete();

        return response()->json([
            'message' => 'Order request deleted successfully'
        ]);
    }

    // Admin endpoint - Get order statistics
    public function stats()
    {
        $stats = [
            'total' => OrderRequest::count(),
            'pending' => OrderRequest::where('status', 'pending')->count(),
            'contacted' => OrderRequest::where('status', 'contacted')->count(),
            'completed' => OrderRequest::where('status', 'completed')->count(),
            'cancelled' => OrderRequest::where('status', 'cancelled')->count(),
        ];

        return response()->json(['data' => $stats]);
    }
}
