<?php

echo "Testing raw PostgreSQL connection...\n";
echo str_repeat("-", 50) . "\n";

$host = 'aws-0-eu-north-1.pooler.supabase.com';
$port = 5432;
$db = 'postgres';
$user = 'postgres.ltrvahgtenvensxickty';
$pass = 'F6i2t6s3e3_@6$6';

echo "Host: $host\n";
echo "Port: $port\n";
echo "Database: $db\n";
echo "User: $user\n";
echo "\nAttempting connection with SSL...\n";

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require";
    
    $pdo = new PDO(
        $dsn,
        $user,
        $pass,
        array(
            PDO::ATTR_TIMEOUT => 10,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        )
    );
    
    echo "✓ SUCCESS: Connected to PostgreSQL!\n";
    
    $result = $pdo->query('SELECT version()')->fetch();
    echo "✓ PostgreSQL Version: " . $result['version'] . "\n";
    
} catch (PDOException $e) {
    echo "✗ CONNECTION ERROR:\n";
    echo "  " . $e->getMessage() . "\n";
    echo "\nTroubleshooting:\n";
    echo "1. Check internet connection\n";
    echo "2. Verify Supabase credentials in .env\n";
    echo "3. Ensure IP is whitelisted in Supabase (if restricted)\n";
    exit(1);
}

?>
