<?php
/**
 * PostgreSQL Extension Check Script
 *
 * This script checks if your PHP environment is properly configured for PostgreSQL.
 * Run this before migrating to Supabase PostgreSQL.
 *
 * Usage: php check-pgsql.php
 */

echo "==============================================\n";
echo "PostgreSQL Configuration Check for Supabase\n";
echo "==============================================\n\n";

// Check PHP Version
echo "1. PHP Version Check\n";
echo "   Current version: " . PHP_VERSION . "\n";
if (version_compare(PHP_VERSION, '8.1.0', '>=')) {
    echo "   ✓ PHP version is compatible\n\n";
} else {
    echo "   ✗ PHP 8.1 or higher is required\n\n";
}

// Check PDO Extension
echo "2. PDO Extension Check\n";
if (extension_loaded('pdo')) {
    echo "   ✓ PDO extension is loaded\n\n";
} else {
    echo "   ✗ PDO extension is NOT loaded\n";
    echo "   Please install PDO extension\n\n";
}

// Check PostgreSQL PDO Driver
echo "3. PostgreSQL PDO Driver Check\n";
if (extension_loaded('pdo_pgsql')) {
    echo "   ✓ pdo_pgsql extension is loaded\n\n";
} else {
    echo "   ✗ pdo_pgsql extension is NOT loaded\n";
    echo "   Installation instructions:\n";
    echo "   - Windows: Uncomment 'extension=pdo_pgsql' in php.ini\n";
    echo "   - Linux: sudo apt-get install php-pgsql\n";
    echo "   - Mac: brew install php@8.x-pgsql\n\n";
}

// List all available PDO drivers
echo "4. Available PDO Drivers\n";
$drivers = PDO::getAvailableDrivers();
if (!empty($drivers)) {
    echo "   Available drivers: " . implode(', ', $drivers) . "\n";
    if (in_array('pgsql', $drivers)) {
        echo "   ✓ PostgreSQL driver is available\n\n";
    } else {
        echo "   ✗ PostgreSQL driver is NOT available\n\n";
    }
} else {
    echo "   ✗ No PDO drivers found\n\n";
}

// Check if .env file exists and has PostgreSQL configuration
echo "5. Environment Configuration Check\n";
if (file_exists(__DIR__ . '/.env')) {
    $envContent = file_get_contents(__DIR__ . '/.env');

    if (strpos($envContent, 'DB_CONNECTION=pgsql') !== false) {
        echo "   ✓ .env file is configured for PostgreSQL\n";

        // Extract database configuration
        preg_match('/DB_HOST=(.*)/', $envContent, $hostMatch);
        preg_match('/DB_PORT=(.*)/', $envContent, $portMatch);
        preg_match('/DB_DATABASE=(.*)/', $envContent, $dbMatch);

        if (isset($hostMatch[1])) {
            echo "   Database Host: " . trim($hostMatch[1]) . "\n";
        }
        if (isset($portMatch[1])) {
            echo "   Database Port: " . trim($portMatch[1]) . "\n";
        }
        if (isset($dbMatch[1])) {
            echo "   Database Name: " . trim($dbMatch[1]) . "\n";
        }

        if (strpos($envContent, 'supabase.co') !== false) {
            echo "   ✓ Supabase host detected\n";
        } else {
            echo "   ⚠ Update DB_HOST with your Supabase host\n";
        }
        echo "\n";
    } else {
        echo "   ⚠ .env file exists but DB_CONNECTION is not set to pgsql\n";
        echo "   Current DB_CONNECTION: ";
        preg_match('/DB_CONNECTION=(.*)/', $envContent, $match);
        echo isset($match[1]) ? trim($match[1]) : 'not set';
        echo "\n\n";
    }
} else {
    echo "   ✗ .env file not found\n";
    echo "   Copy .env.example to .env and configure your Supabase credentials\n\n";
}

// Test PostgreSQL connection (if configured)
echo "6. Database Connection Test\n";
if (file_exists(__DIR__ . '/.env')) {
    $envContent = file_get_contents(__DIR__ . '/.env');

    preg_match('/DB_CONNECTION=(.*)/', $envContent, $connMatch);
    preg_match('/DB_HOST=(.*)/', $envContent, $hostMatch);
    preg_match('/DB_PORT=(.*)/', $envContent, $portMatch);
    preg_match('/DB_DATABASE=(.*)/', $envContent, $dbMatch);
    preg_match('/DB_USERNAME=(.*)/', $envContent, $userMatch);
    preg_match('/DB_PASSWORD=(.*)/', $envContent, $passMatch);

    $connection = isset($connMatch[1]) ? trim($connMatch[1]) : '';
    $host = isset($hostMatch[1]) ? trim($hostMatch[1]) : '';
    $port = isset($portMatch[1]) ? trim($portMatch[1]) : '5432';
    $database = isset($dbMatch[1]) ? trim($dbMatch[1]) : '';
    $username = isset($userMatch[1]) ? trim($userMatch[1]) : '';
    $password = isset($passMatch[1]) ? trim($passMatch[1]) : '';

    if ($connection === 'pgsql' && $host && $database && $username && extension_loaded('pdo_pgsql')) {
        // Check if host looks like placeholder
        if (strpos($host, 'your-supabase-host') !== false || strpos($host, 'supabase.co') === false) {
            echo "   ⚠ Please update DB_HOST with your actual Supabase host\n";
            echo "   Current host looks like a placeholder: $host\n\n";
        } else {
            try {
                echo "   Attempting to connect to: $host:$port\n";

                $dsn = "pgsql:host=$host;port=$port;dbname=$database;sslmode=require";
                $pdo = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_TIMEOUT => 5
                ]);

                echo "   ✓ Successfully connected to PostgreSQL database\n";
                echo "   Server version: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n\n";

            } catch (PDOException $e) {
                echo "   ✗ Connection failed: " . $e->getMessage() . "\n";
                echo "   Please verify your Supabase credentials in .env file\n\n";
            }
        }
    } else {
        echo "   ⊗ Skipping connection test\n";
        echo "   Reason: ";
        if ($connection !== 'pgsql') {
            echo "DB_CONNECTION is not set to pgsql\n";
        } elseif (!$host || !$database || !$username) {
            echo "Database credentials are not fully configured\n";
        } elseif (!extension_loaded('pdo_pgsql')) {
            echo "pdo_pgsql extension is not loaded\n";
        }
        echo "\n";
    }
} else {
    echo "   ⊗ Skipping test - .env file not found\n\n";
}

echo "==============================================\n";
echo "Summary\n";
echo "==============================================\n\n";

$allGood = extension_loaded('pdo') && extension_loaded('pdo_pgsql') && version_compare(PHP_VERSION, '8.1.0', '>=');

if ($allGood) {
    echo "✓ Your PHP environment is ready for PostgreSQL!\n\n";
    echo "Next steps:\n";
    echo "1. Update .env with your Supabase credentials\n";
    echo "2. Run: php artisan config:clear\n";
    echo "3. Run: php artisan migrate\n\n";
} else {
    echo "✗ Some requirements are not met.\n";
    echo "Please review the checks above and install missing extensions.\n\n";
}

echo "For more information, see SUPABASE_SETUP.md\n";
echo "==============================================\n";
