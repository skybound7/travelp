@echo off
echo PhonePe Payment Integration Test
echo ==============================
echo.

:CHECK_PHP
echo Checking PHP installation...
where php >nul 2>&1
if %errorlevel% neq 0 (
    echo PHP is not installed or not in PATH
    echo Please download PHP from https://windows.php.net/download/
    echo Extract to C:\php and add to PATH
    pause
    exit /b
)

:RUN_TEST
echo Running payment flow test...
php test_payment.php

if %errorlevel% equ 0 (
    echo.
    echo Test completed successfully!
) else (
    echo.
    echo Test failed!
)

echo Check logs/test_payment.log for detailed results
echo.
pause
