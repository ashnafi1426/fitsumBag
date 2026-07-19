#!/bin/bash
# Supabase PostgreSQL Setup Script for Linux/Mac
# This script helps automate the setup process

echo "====================================================="
echo "Supabase PostgreSQL Setup Script"
echo "====================================================="
echo ""

# Check if .env file exists
if [ ! -f ".env" ]; then
    echo "[ERROR] .env file not found!"
    echo ""
    echo "Please copy .env.example to .env first:"
    echo "   cp .env.example .env"
    echo ""
    echo "Then update the database credentials in .env file."
    exit 1
fi

echo "Step 1: Checking PostgreSQL Extension"
echo "-------------------------------------"
php check-pgsql.php
if [ $? -ne 0 ]; then
    echo ""
    echo "[ERROR] PostgreSQL check failed!"
    echo "Please fix the issues above before continuing."
    exit 1
fi

echo ""
echo "Step 2: Clearing Laravel Cache"
echo "-------------------------------------"
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo ""
echo "Step 3: Testing Database Connection"
echo "-------------------------------------"
echo "Testing connection to Supabase..."
php -r "try { require 'vendor/autoload.php'; \$app = require_once 'bootstrap/app.php'; \$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); DB::connection()->getPdo(); echo 'SUCCESS: Connected to database\n'; } catch (Exception \$e) { echo 'ERROR: ' . \$e->getMessage() . '\n'; exit(1); }"

if [ $? -ne 0 ]; then
    echo ""
    echo "[ERROR] Database connection failed!"
    echo ""
    echo "Please verify your Supabase credentials in .env file:"
    echo "- DB_HOST should be your Supabase host (e.g., db.xxxxx.supabase.co)"
    echo "- DB_PASSWORD should be your actual database password"
    echo "- DB_SSLMODE should be set to 'require'"
    echo ""
    exit 1
fi

echo ""
echo "Step 4: Running Database Migrations"
echo "-------------------------------------"
echo ""
echo "Choose migration option:"
echo "1. Fresh install (drops all tables and recreates them)"
echo "2. Standard migration (keeps existing data)"
echo "3. Skip migrations"
echo ""
read -p "Enter your choice (1-3): " migration_choice

case $migration_choice in
    1)
        echo ""
        echo "Running fresh migrations..."
        php artisan migrate:fresh --seed
        ;;
    2)
        echo ""
        echo "Running standard migrations..."
        php artisan migrate
        ;;
    *)
        echo ""
        echo "Skipping migrations."
        ;;
esac

echo ""
echo "Step 5: Verification"
echo "-------------------------------------"
echo ""
echo "Checking migration status..."
php artisan migrate:status

echo ""
echo "====================================================="
echo "Setup Complete!"
echo "====================================================="
echo ""
echo "Your application is now configured to use Supabase PostgreSQL."
echo ""
echo "Next steps:"
echo "1. Test your API endpoints: php artisan serve"
echo "2. Check the documentation in SUPABASE_SETUP.md"
echo "3. Deploy to production with updated .env.production"
echo ""
echo "For troubleshooting, run: php check-pgsql.php"
echo "====================================================="
echo ""
