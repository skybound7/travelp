<?php
// Test Configuration

// Database Configuration
define('DB_HOST', 'localhost');     // Your database host
define('DB_NAME', 'payment_test');  // Test database name
define('DB_USER', 'root');          // Your database username
define('DB_PASS', '');              // Your database password
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', 'utf8mb4_unicode_ci');

// PhonePe Test Configuration
define('PHONEPE_MERCHANT_ID', 'TEST_MERCHANT_ID');
define('PHONEPE_SALT_KEY', 'TEST_SALT_KEY');
define('PHONEPE_SALT_INDEX', '1');
define('PHONEPE_API_ENDPOINT', 'https://api.phonepe.com/apis/test');

// Test Environment Settings
define('SITE_URL', 'http://localhost');
define('PAYMENT_LOG_ENABLED', true);
define('PAYMENT_LOG_FILE', __DIR__ . '/logs/test_payment.log');
define('DEBUG_MODE', true);

// Test IP Whitelist
define('PAYMENT_IP_WHITELIST', [
    '127.0.0.1',
    'localhost',
    '::1'
]);
