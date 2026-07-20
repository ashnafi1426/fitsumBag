<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Testing database connection...\n";
    
    $pdo = DB::connection()->getPdo();
    echo "✓ SUCCESS: Database connection established!\n";
    echo "  Driver: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";
    
    // Test query
    $result = DB::select('SELECT version()');
    echo "✓ PostgreSQL Version: " . $result[0]->version . "\n";
    
    exit(0);
} catch (Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "  Code: " . $e->getCode() . "\n";
    exit(1);
}
?>
