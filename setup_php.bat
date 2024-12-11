@echo off
REM Check for administrator privileges
net session >nul 2>&1
if %errorLevel% neq 0 (
    echo This script requires administrator privileges.
    echo Please run this script as administrator.
    pause
    exit /b 1
)

echo Setting up PHP for Windows...
echo.

REM Create PHP directory if it doesn't exist
if not exist "C:\php" mkdir "C:\php"

REM Download PHP
echo Downloading PHP...
powershell -Command "& {[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12; Invoke-WebRequest -Uri 'https://windows.php.net/downloads/releases/latest/php-8.2-nts-Win32-vs16-x64-latest.zip' -OutFile 'C:\php\php.zip'}"

REM Extract PHP
echo Extracting PHP...
powershell -Command "& {Expand-Archive -Path 'C:\php\php.zip' -DestinationPath 'C:\php' -Force}"

REM Clean up zip file
del "C:\php\php.zip"

REM Copy and configure php.ini
copy "C:\php\php.ini-development" "C:\php\php.ini"

REM Enable required extensions
powershell -Command "& {(Get-Content 'C:\php\php.ini') -replace ';extension=mysqli', 'extension=mysqli' -replace ';extension=curl', 'extension=curl' | Set-Content 'C:\php\php.ini'}"
PhonePe Payment Integration Test
==============================

Checking PHP installation...
PhonePe Payment Integration Test
==============================

Checking PHP installation...
PhonePe Payment Integration Test
==============================

Checking PHP installation...
PHP is not installed or not in PATH
Please download PHP from https://windows.php.net/download/
Extract to C:\php and add to PATH
Press any key to continue . . . 
This script requires administrator privileges.
Please run this script as administrator.
Press any key to continue . . . 

REM Add PHP to system PATH
setx /M PATH "%PATH%;C:\php"

echo.
echo PHP setup complete! Please restart your terminal for PATH changes to take effect.
echo You may need to restart your IDE as well.
pause
