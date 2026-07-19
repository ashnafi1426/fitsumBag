<?php
// Test Laravel bootstrap
echo "<h1>Laravel Bootstrap Test</h1>";

try {
    echo "1. Loading autoloader...<br>";
    require __DIR__.'/vendor/autoload.php';
    echo "✅ Autoloader loaded<br>";

    echo "2. Loading Laravel app...<br>";
    $app = require_once __DIR__.'/bootstrap/app.php';
    echo "✅ Laravel app loaded<br>";

    echo "3. Testing environment...<br>";
    echo "APP_ENV: " . $app->environment() . "<br>";
    echo "✅ Laravel initialized successfully!<br>";

    echo "4. Testing database config...<br>";
    $config = $app->make('config');
    echo "DB Host: " . $config->get('database.connections.mysql.host') . "<br>";
    echo "DB Name: " . $config->get('database.connections.mysql.database') . "<br>";
    echo "✅ Configuration loaded<br>";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
}
?>
