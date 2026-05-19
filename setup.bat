@echo off
:: Setup Script for Laundry Hotel SMKN 1 Ciamis
:: Created by Antigravity AI

title Laundry Hotel SMKN 1 Ciamis - Project Setup
color 0B
clear
echo =====================================================================
echo              LAUNDRY HOTEL SMKN 1 CIAMIS - PROJECT SETUP
echo =====================================================================
echo.
echo Preparing local development environment...
echo.

:: Step 1: Check requirements
echo [STEP 1] Checking system prerequisites...
echo ---------------------------------------------------------------------

where php >nul 2>&1
if %ERRORLEVEL% equ 0 (
    for /f "tokens=2 delims= " %%v in ('php -r "echo PHP_VERSION;"') do set php_ver=%%v
    echo   [OK] PHP is installed (Version: %php_ver%^)
) else (
    echo   [ERROR] PHP is not installed or not in PATH!
    echo           Please install PHP 8.3 or higher.
    pause
    exit /b 1
)

where composer >nul 2>&1
if %ERRORLEVEL% equ 0 (
    echo   [OK] Composer is installed.
) else (
    echo   [ERROR] Composer is not installed or not in PATH!
    echo           Please install Composer from https://getcomposer.org/
    pause
    exit /b 1
)

where node >nul 2>&1
if %ERRORLEVEL% equ 0 (
    for /f "tokens=1 delims= " %%v in ('node -v') do set node_ver=%%v
    echo   [OK] Node.js is installed (Version: %node_ver%^)
) else (
    echo   [WARNING] Node.js is not installed. You won't be able to compile assets with Vite.
)

where npm >nul 2>&1
if %ERRORLEVEL% equ 0 (
    echo   [OK] NPM is installed.
) else (
    echo   [WARNING] NPM is not installed.
)

echo.

:: Step 2: Set up environment file
echo [STEP 2] Preparing environment configuration...
echo ---------------------------------------------------------------------
if exist .env (
    echo   [OK] .env file already exists.
) else (
    if exist .env.example (
        echo   [INFO] Copying .env.example to .env...
        copy .env.example .env >nul
        echo   [OK] .env file created.
    ) else (
        echo   [ERROR] .env.example not found! Cannot create .env file.
        pause
        exit /b 1
    )
)

:: Ensure SQLite file exists
if not exist database\database.sqlite (
    echo   [INFO] Creating empty SQLite database...
    echo. > database\database.sqlite
    echo   [OK] database/database.sqlite created.
) else (
    echo   [OK] SQLite database already exists.
)
echo.

:: Step 3: Install PHP Dependencies
echo [STEP 3] Installing Composer dependencies (composer install)...
echo ---------------------------------------------------------------------
call composer install
if %ERRORLEVEL% neq 0 (
    echo   [ERROR] Composer install failed!
    pause
    exit /b 1
)
echo.

:: Step 4: Generate Application Key
echo [STEP 4] Ensuring application key exists...
echo ---------------------------------------------------------------------
:: Check if key is already set in .env
findstr /C:"APP_KEY=base64:" .env >nul
if %ERRORLEVEL% equ 0 (
    echo   [OK] APP_KEY is already configured.
) else (
    echo   [INFO] Generating application key...
    call php artisan key:generate
)
echo.

:: Step 5: Database Migrations and Seeding
echo [STEP 5] Running database migrations and seeding...
echo ---------------------------------------------------------------------
echo This will fresh migrate the database. All existing data in SQLite will be reset.
set /p confirm_mig="Do you want to run fresh migrations with seed data? (Y/N): "
if /i "%confirm_mig%"=="Y" (
    call php artisan migrate:fresh --seed
    if %ERRORLEVEL% neq 0 (
        echo   [ERROR] Database migration or seeding failed!
    ) else (
        echo   [OK] Database migrated and seeded successfully!
    )
) else (
    echo   [INFO] Skipping database migrations.
)
echo.

:: Step 6: Install Node Dependencies & Build Assets
echo [STEP 6] Preparing frontend assets...
echo ---------------------------------------------------------------------
where npm >nul 2>&1
if %ERRORLEVEL% equ 0 (
    echo   [INFO] Installing NPM packages (npm install)...
    call npm install
    if %ERRORLEVEL% neq 0 (
        echo   [WARNING] npm install failed.
    ) else (
        echo   [OK] NPM packages installed.
        echo   [INFO] Compiling assets (npm run build)...
        call npm run build
        if %ERRORLEVEL% neq 0 (
            echo   [WARNING] Vite assets compilation failed.
        ) else (
            echo   [OK] Vite assets compiled successfully.
        )
    )
) else (
    echo   [INFO] Skipping frontend asset compilation (NPM not found).
)
echo.

:: Step 7: Completed!
color 0A
echo =====================================================================
echo             SETUP COMPLETED SUCCESSFULLY!
echo =====================================================================
echo.
echo The Laundry Hotel SMKN 1 Ciamis application is now ready to run.
echo.
echo Default User accounts for testing:
echo.
echo   [Admin Account]
echo   Email:    admin@laundry.com
echo   Password: password
echo.
echo   [Staff Accounts]
echo   Division: Customer Service
echo   Email:    kasir@laundry.com
echo   Password: 123456
echo.
echo   Division: Washing
echo   Email:    washing@laundry.com
echo   Password: 123456
echo.
echo   Division: Ironing (Setrika)
echo   Email:    setrika@laundry.com
echo   Password: 123456
echo.
echo   Division: Packing
echo   Email:    packing@laundry.com
echo   Password: 123456
echo.
echo   Division: Inventory
echo   Email:    inventory@laundry.com
echo   Password: 123456
echo.
echo ---------------------------------------------------------------------
echo.

set /p start_serv="Do you want to start the Laravel development server now? (Y/N): "
if /i "%start_serv%"=="Y" (
    echo.
    echo Starting Laravel server...
    echo Access it at: http://127.0.0.1:8000
    echo Press Ctrl+C in that window to stop the server.
    echo.
    start http://127.0.0.1:8000
    call php artisan serve
) else (
    echo.
    echo To start the server manually later, run:
    echo   php artisan serve
    echo.
    echo Have a great day!
    pause
)
