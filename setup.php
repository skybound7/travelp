<?php
echo "PhonePe Payment Integration Setup\n";
echo "===============================\n\n";

// 1. Check PHP Installation
echo "1. Checking PHP Installation...\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Required extensions:\n";
$required_extensions = ['json', 'curl', 'mysqli'];
$missing_extensions = [];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✓ {$ext} extension loaded\n";
    } else {
        echo "✗ {$ext} extension missing\n";
        $missing_extensions[] = $ext;
    }
}

if (!empty($missing_extensions)) {
    echo "\nPlease enable these extensions in your php.ini file:\n";
    foreach ($missing_extensions as $ext) {
        echo "extension={$ext}\n";
    }
}

// 2. Check Database Connection
echo "\n2. Checking Database Connection...\n";
require_once __DIR__ . '/database/config.php';

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        throw new Exception($conn->connect_error);
    }
    echo "✓ Database connection successful\n";
    $conn->close();
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    echo "Please update database credentials in database/config.php\n";
}

// 3. Check PhonePe Configuration
echo "\n3. Checking PhonePe Configuration...\n";
require_once __DIR__ . '/includes/config/payment.php';

if (PHONEPE_MERCHANT_ID === 'your_phonepe_merchant_id' || 
    PHONEPE_SALT_KEY === 'your_phonepe_salt_key') {
    echo "✗ PhonePe credentials not configured\n";
    echo "Please update credentials in includes/config/payment.php:\n";
    echo "- PHONEPE_MERCHANT_ID\n";
    echo "- PHONEPE_SALT_KEY\n";
} else {
    echo "✓ PhonePe credentials configured\n";
}

// 4. Check Migration Status
echo "\n4. Checking Migration Status...\n";
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $result = $conn->query("SHOW COLUMNS FROM payments LIKE 'merchant_transaction_id'");
    if ($result->num_rows > 0) {
        echo "✓ Migration already applied\n";
    } else {
        echo "✗ Migration pending\n";
        echo "Run: php database/run_migration.php\n";
    }
    $conn->close();
} catch (Exception $e) {
    echo "✗ Could not check migration status: " . $e->getMessage() . "\n";
}

// 5. Directory Permissions
echo "\n5. Checking Directory Permissions...\n";
$dirs = [
    __DIR__ . '/logs',
    __DIR__ . '/database',
    __DIR__ . '/includes/config'
];

foreach ($dirs as $dir) {
    if (is_writable($dir)) {
        echo "✓ {$dir} is writable\n";
    } else {
        echo "✗ {$dir} is not writable\n";
    }
}

echo "\nSetup check complete!\n";
