@echo off
echo Checking MySQL installation...

REM Check if MySQL is installed in common locations
set "MYSQL_PATHS=C:\Program Files\MySQL\MySQL Server 8.0\bin;C:\xampp\mysql\bin;C:\wamp64\bin\mysql\mysql8.0.31\bin"

for %%p in (%MYSQL_PATHS%) do (
    if exist "%%p\mysql.exe" (
        echo Found MySQL in: %%p
        setx PATH "%PATH%;%%p"
        echo Added MySQL to PATH
        goto :MYSQL_FOUND
    )
)

echo MySQL not found in common locations.
echo Please make sure MySQL is installed and the bin directory is added to PATH
echo You can download MySQL from: https://dev.mysql.com/downloads/installer/
echo.
echo Common installation paths are:
echo - C:\Program Files\MySQL\MySQL Server 8.0\bin
echo - C:\xampp\mysql\bin
echo - C:\wamp64\bin\mysql\mysql8.0.31\bin
pause
exit /b 1

:MYSQL_FOUND
echo MySQL setup complete!
echo Please restart your terminal for PATH changes to take effect.
pause
