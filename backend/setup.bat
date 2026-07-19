@echo off
echo ================================================
echo Royal Leather QR Menu - Database Setup
echo ================================================
echo.

REM Test database connection
echo [1/4] Testing database connection...
php test-db.php
if errorlevel 1 (
    echo.
    echo ERROR: Database connection failed!
    echo.
    echo Please check QUICK_FIX.md for solutions.
    echo Make sure you've set DB_PASSWORD in .env file.
    echo.
    pause
    exit /b 1
)

echo.
echo [2/4] Clearing Laravel config cache...
php artisan config:clear

echo.
echo [3/4] Running database migrations...
php artisan migrate:fresh --seed

if errorlevel 1 (
    echo.
    echo ERROR: Migration failed!
    pause
    exit /b 1
)

echo.
echo [4/4] Setup complete!
echo.
echo ================================================
echo SUCCESS! Your database is ready.
echo ================================================
echo.
echo Next steps:
echo 1. Make sure your backend server is running (php artisan serve)
echo 2. Refresh your frontend browser
echo 3. The 500 errors should be gone!
echo.
pause
