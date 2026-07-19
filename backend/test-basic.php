<?php
// Basic PHP test file
echo "<h1>✅ PHP is working!</h1>";
echo "<p>Server Time: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";

// Test if Laravel files exist
$files_to_check = [
    '.env' => file_exists('.env'),
    'vendor/autoload.php' => file_exists('vendor/autoload.php'),
    'bootstrap/app.php' => file_exists('bootstrap/app.php'),
    'config/app.php' => file_exists('config/app.php'),
    'storage/logs' => is_dir('storage/logs'),
];

echo "<h2>File Check:</h2><ul>";
foreach ($files_to_check as $file => $exists) {
    $status = $exists ? "✅ EXISTS" : "❌ MISSING";
    echo "<li><strong>$file</strong>: $status</li>";
}
echo "</ul>";

// Test database connection
echo "<h2>Database Test:</h2>";
if (file_exists('.env')) {
    $env = file_get_contents('.env');
    if (preg_match('/DB_DATABASE=(.+)/', $env, $matches)) {
        $db = trim($matches[1]);
        if (preg_match('/DB_USERNAME=(.+)/', $env, $matches)) {
            $user = trim($matches[1]);
            if (preg_match('/DB_PASSWORD=(.+)/', $env, $matches)) {
                $pass = trim($matches[1]);

                try {
                    $pdo = new PDO("mysql:host=localhost;dbname=$db", $user, $pass);
                    echo "✅ Database connection: SUCCESS";
                } catch(PDOException $e) {
                    echo "❌ Database connection: FAILED<br>";
                    echo "Error: " . $e->getMessage();
                }
            }
        }
    }
} else {
    echo "❌ .env file missing";
}
?>
