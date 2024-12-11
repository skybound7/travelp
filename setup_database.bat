@echo off
echo Setting up MySQL database for testing...
echo.

mysql -u root -e "CREATE DATABASE IF NOT EXISTS payment_test;"
mysql -u root -e "CREATE DATABASE IF NOT EXISTS payment_db;"

echo Database setup complete!
pause
