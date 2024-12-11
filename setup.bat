@echo off
echo PhonePe Payment Integration Setup
echo ===============================
echo.

:CHECK_PHP
echo 1. Checking PHP installation...
where php >nul 2>&1
if %errorlevel% neq 0 (
    echo PHP is not installed or not in PATH
    echo Please download PHP from https://windows.php.net/download/
    echo Extract to C:\php and add to PATH
    pause
    exit /b
)
echo PHP installation found
echo.

:CHECK_CONFIG
echo 2. Checking configuration files...
if not exist "database\config.php" (
    echo Creating database config from template...
    copy "database\config.template.php" "database\config.php"
    echo Please update database credentials in database\config.php
)

if not exist "includes\config\payment.php" (
    echo Creating payment config from template...
    copy "includes\config\payment.template.php" "includes\config\payment.php"
    echo Please update PhonePe credentials in includes\config\payment.php
)
echo.

:CREATE_DIRS
echo 3. Creating required directories...
if not exist "logs" mkdir logs
echo Created logs directory
echo.

:RUN_MIGRATION
echo 4. Running database migration...
echo Please enter your database details:
set /p DB_HOST=Database Host (default: localhost): 
if "%DB_HOST%"=="" set DB_HOST=localhost

set /p DB_NAME=Database Name: 
set /p DB_USER=Database Username: 
set /p DB_PASS=Database Password: 

echo Running migration...
mysql -h %DB_HOST% -u %DB_USER% -p%DB_PASS% %DB_NAME% < database\migrations\add_merchant_transaction_id.sql

if %errorlevel% equ 0 (
    echo Migration completed successfully
) else (
    echo Migration failed
)
echo.

:VERIFY_SETUP
echo 5. Verifying setup...
php setup.php

echo.
echo Setup process complete!
echo Please ensure you have:
echo 1. Updated database credentials in database\config.php
echo 2. Updated PhonePe credentials in includes\config\payment.php
echo 3. Set up your web server to point to this directory
echo.
pause
