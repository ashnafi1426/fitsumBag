<?php

use App\Http\Controllers\AboutGalleryImageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\HeroImageController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\OrderRequestController;
use App\Http\Controllers\RestaurantSettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API is running',
        'timestamp' => now()->toIso8601String(),
    ]);
});

// Auth routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/change-password', [AuthController::class, 'changePassword']);
});

// Public routes (for customer-facing menu)
Route::prefix('public')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/menu-items', [MenuItemController::class, 'index']);
    Route::get('/menu-items/{id}', [MenuItemController::class, 'show']);
    Route::get('/settings', [RestaurantSettingController::class, 'index']);

    // Hero Images (public - for menu page carousel)
    Route::get('/hero-images', [HeroImageController::class, 'index']);

    // About Gallery Images (public - for about us page gallery)
    Route::get('/about-gallery-images', [AboutGalleryImageController::class, 'index']);

    // Feedback submission (public - no auth required)
    Route::post('/feedback', [FeedbackController::class, 'store']);

    // Order Requests (public - no auth required)
    Route::post('/order-requests', [OrderRequestController::class, 'store']);
});

// Admin routes (protected with auth)
Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    // Categories
    Route::get('/categories', [CategoryController::class, 'adminIndex']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
    Route::post('/categories/reorder', [CategoryController::class, 'reorder']);

    // Menu Items
    Route::get('/menu-items', [MenuItemController::class, 'adminIndex']);
    Route::post('/menu-items', [MenuItemController::class, 'store']);
    Route::get('/menu-items/{id}', [MenuItemController::class, 'show']);
    Route::put('/menu-items/{id}', [MenuItemController::class, 'update']);
    Route::delete('/menu-items/{id}', [MenuItemController::class, 'destroy']);
    Route::post('/menu-items/{id}/toggle-availability', [MenuItemController::class, 'toggleAvailability']);
    Route::post('/menu-items/{id}/toggle-featured', [MenuItemController::class, 'toggleFeatured']);
    Route::post('/menu-items/upload-image', [MenuItemController::class, 'uploadImage']);

    // Restaurant Settings
    Route::get('/settings', [RestaurantSettingController::class, 'index']);
    Route::put('/settings', [RestaurantSettingController::class, 'update']);
    Route::post('/settings/upload-logo', [RestaurantSettingController::class, 'uploadLogo']);

    // Feedback Management
    Route::get('/feedback', [FeedbackController::class, 'index']);
    Route::get('/feedback/statistics', [FeedbackController::class, 'statistics']);
    Route::get('/feedback/{id}', [FeedbackController::class, 'show']);
    Route::put('/feedback/{id}', [FeedbackController::class, 'update']);
    Route::delete('/feedback/{id}', [FeedbackController::class, 'destroy']);
    Route::post('/feedback/{id}/mark-read', [FeedbackController::class, 'markAsRead']);
    Route::post('/feedback/{id}/mark-resolved', [FeedbackController::class, 'markAsResolved']);

    // Hero Images Management
    Route::get('/hero-images', [HeroImageController::class, 'adminIndex']);
    Route::post('/hero-images', [HeroImageController::class, 'store']);
    Route::put('/hero-images/{id}', [HeroImageController::class, 'update']);
    Route::delete('/hero-images/{id}', [HeroImageController::class, 'destroy']);
    Route::post('/hero-images/upload-image', [HeroImageController::class, 'uploadImage']);
    Route::post('/hero-images/reorder', [HeroImageController::class, 'reorder']);
    Route::post('/hero-images/{id}/toggle-active', [HeroImageController::class, 'toggleActive']);

    // About Gallery Images Management
    Route::get('/about-gallery-images', [AboutGalleryImageController::class, 'adminIndex']);
    Route::post('/about-gallery-images', [AboutGalleryImageController::class, 'store']);
    Route::put('/about-gallery-images/{id}', [AboutGalleryImageController::class, 'update']);
    Route::delete('/about-gallery-images/{id}', [AboutGalleryImageController::class, 'destroy']);
    Route::post('/about-gallery-images/upload-image', [AboutGalleryImageController::class, 'uploadImage']);
    Route::post('/about-gallery-images/reorder', [AboutGalleryImageController::class, 'reorder']);
    Route::post('/about-gallery-images/{id}/toggle-active', [AboutGalleryImageController::class, 'toggleActive']);

    // Order Requests Management
    Route::get('/order-requests', [OrderRequestController::class, 'index']);
    Route::get('/order-requests/stats', [OrderRequestController::class, 'stats']);
    Route::get('/order-requests/{id}', [OrderRequestController::class, 'show']);
    Route::put('/order-requests/{id}/status', [OrderRequestController::class, 'updateStatus']);
    Route::delete('/order-requests/{id}', [OrderRequestController::class, 'destroy']);
});
