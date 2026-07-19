<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Get all feedback (Admin)
     */
    public function index(Request $request)
    {
        $query = Feedback::query()->orderBy('created_at', 'desc');

        // Filter by read status
        if ($request->has('is_read')) {
            $query->where('is_read', $request->boolean('is_read'));
        }

        // Filter by resolved status
        if ($request->has('is_resolved')) {
            $query->where('is_resolved', $request->boolean('is_resolved'));
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by rating
        if ($request->has('rating')) {
            $query->where('rating', $request->rating);
        }

        $feedback = $query->paginate($request->get('per_page', 20));

        return response()->json($feedback);
    }

    /**
     * Submit feedback (Public - from customer)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required|string',
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
        ]);

        $feedback = Feedback::create($validated);

        return response()->json([
            'message' => 'Thank you for your feedback!',
            'data' => $feedback
        ], 201);
    }

    /**
     * Get single feedback (Admin)
     */
    public function show($id)
    {
        $feedback = Feedback::findOrFail($id);
        
        // Mark as read when viewed
        if (!$feedback->is_read) {
            $feedback->update(['is_read' => true]);
        }

        return response()->json(['data' => $feedback]);
    }

    /**
     * Update feedback (Admin - for notes and status)
     */
    public function update(Request $request, $id)
    {
        $feedback = Feedback::findOrFail($id);

        $validated = $request->validate([
            'is_read' => 'sometimes|boolean',
            'is_resolved' => 'sometimes|boolean',
            'admin_notes' => 'nullable|string',
        ]);

        $feedback->update($validated);

        return response()->json([
            'message' => 'Feedback updated successfully',
            'data' => $feedback
        ]);
    }

    /**
     * Delete feedback (Admin)
     */
    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return response()->json([
            'message' => 'Feedback deleted successfully'
        ]);
    }

    /**
     * Mark feedback as read (Admin)
     */
    public function markAsRead($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->update(['is_read' => true]);

        return response()->json([
            'message' => 'Feedback marked as read',
            'data' => $feedback
        ]);
    }

    /**
     * Mark feedback as resolved (Admin)
     */
    public function markAsResolved($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->update(['is_resolved' => true]);

        return response()->json([
            'message' => 'Feedback marked as resolved',
            'data' => $feedback
        ]);
    }

    /**
     * Get feedback statistics (Admin - for dashboard)
     */
    public function statistics()
    {
        $stats = [
            'total' => Feedback::count(),
            'unread' => Feedback::unread()->count(),
            'unresolved' => Feedback::unresolved()->count(),
            'recent' => Feedback::recent(7)->count(),
            'average_rating' => round(Feedback::avg('rating'), 1),
            'by_category' => Feedback::selectRaw('category, COUNT(*) as count')
                ->groupBy('category')
                ->get(),
            'by_rating' => Feedback::selectRaw('rating, COUNT(*) as count')
                ->groupBy('rating')
                ->orderBy('rating', 'desc')
                ->get(),
        ];

        return response()->json(['data' => $stats]);
    }
}
