<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "==============================================\n";
echo "Database Connection Test\n";
echo "==============================================\n\n";

try {
    // Test database connection
    DB::connection()->getPdo();
    echo "✓ Database connection successful!\n\n";

    // Count records
    echo "Database Statistics:\n";
    echo "-------------------\n";
    echo "Users: " . \App\Models\User::count() . "\n";
    echo "Categories: " . \App\Models\Category::count() . "\n";
    echo "Menu Items: " . \App\Models\MenuItem::count() . "\n";
    echo "Restaurant Settings: " . \App\Models\RestaurantSetting::count() . "\n\n";

    // Show admin user
    $admin = \App\Models\User::where('role', 'admin')->first();
    if ($admin) {
        echo "Admin User:\n";
        echo "  Name: " . $admin->name . "\n";
        echo "  Email: " . $admin->email . "\n\n";
    }

    // Show categories
    echo "Categories:\n";
    $categories = \App\Models\Category::all();
    foreach ($categories as $category) {
        $itemCount = \App\Models\MenuItem::where('category_id', $category->id)->count();
        echo "  - " . $category->name . " (" . $itemCount . " items)\n";
    }

    echo "\n✓ All data loaded successfully!\n";
    echo "==============================================\n";

} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
