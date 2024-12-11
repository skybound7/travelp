<?php
// Load test configuration
require_once __DIR__ . '/test_config.php';
require_once __DIR__ . '/includes/utils/Logger.php';
require_once __DIR__ . '/includes/services/PaymentManager.php';

$logger = new Logger(PAYMENT_LOG_FILE, true);
$logger->info("Starting payment flow test");

try {
    // Create test database if not exists
    $logger->info("Setting up test database");
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Create database if not exists
    $conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    $conn->select_db(DB_NAME);
    
    // Create test tables
    $logger->debug("Creating test tables");
    $tables = [
        "CREATE TABLE IF NOT EXISTS payments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            booking_id VARCHAR(100),
            booking_type VARCHAR(50),
            amount DECIMAL(10,2),
            status VARCHAR(20),
            payment_method VARCHAR(50),
            transaction_id VARCHAR(100),
            merchant_transaction_id VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_booking (booking_id, booking_type),
            INDEX idx_merchant_txn (merchant_transaction_id)
        )",
        "CREATE TABLE IF NOT EXISTS payment_attempts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            booking_id VARCHAR(100),
            booking_type VARCHAR(50),
            payment_method VARCHAR(50),
            amount DECIMAL(10,2),
            status VARCHAR(20),
            transaction_id VARCHAR(100),
            merchant_transaction_id VARCHAR(100),
            response_data TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_booking (booking_id, booking_type),
            INDEX idx_merchant_txn (merchant_transaction_id)
        )"
    ];
    
    foreach ($tables as $sql) {
        if (!$conn->query($sql)) {
            throw new Exception("Error creating table: " . $conn->error);
        }
    }
    $logger->debug("Test tables created successfully");
    
    // Test data
    $testData = [
        'bookingId' => 'TEST_' . time(),
        'bookingType' => 'test',
        'paymentMethod' => 'phonepe',
        'amount' => 100,
        'paymentDetails' => [
            'userId' => 'test_user_' . time(),
            'mobileNumber' => '9999999999'
        ]
    ];
    
    $logger->debug("Test data: " . json_encode($testData, JSON_PRETTY_PRINT));
    
    // 1. Test Payment Initiation
    $logger->info("1. Testing payment initiation");
    $paymentManager = new PaymentManager();
    
    $result = $paymentManager->initiatePayment(
        $testData['bookingId'],
        $testData['bookingType'],
        $testData['amount'],
        $testData['paymentMethod'],
        $testData['paymentDetails']
    );
    
    $logger->debug("Payment initiation result: " . json_encode($result, JSON_PRETTY_PRINT));
    
    // 2. Test Callback Processing
    $logger->info("2. Testing callback processing");
    
    // Simulate successful payment callback
    $callbackData = [
        'transactionId' => 'PG_' . time(),
        'merchantTransactionId' => $result['merchantTransactionId'],
        'amount' => $testData['amount'],
        'status' => 'SUCCESS',
        'code' => 'PAYMENT_SUCCESS',
        'merchantId' => PHONEPE_MERCHANT_ID
    ];
    
    // Create signature
    $verificationString = json_encode($callbackData) . '/pg/v1/status/notify' . PHONEPE_SALT_KEY;
    $signature = hash('sha256', $verificationString);
    
    $logger->debug("Callback data: " . json_encode($callbackData, JSON_PRETTY_PRINT));
    $logger->debug("Generated signature: " . $signature);
    
    // Process callback
    $callbackResult = $paymentManager->handlePaymentCallback('phonepe', $callbackData);
    $logger->debug("Callback processing result: " . json_encode($callbackResult, JSON_PRETTY_PRINT));
    
    // 3. Verify Final Payment Status
    $logger->info("3. Verifying final payment status");
    $finalStatus = $paymentManager->getPaymentStatus($testData['bookingId'], $testData['bookingType']);
    $logger->debug("Final payment status: " . json_encode($finalStatus, JSON_PRETTY_PRINT));
    
    // Cleanup test data
    $logger->info("4. Cleaning up test data");
    $conn->query("DELETE FROM payments WHERE booking_id = '{$testData['bookingId']}'");
    $conn->query("DELETE FROM payment_attempts WHERE booking_id = '{$testData['bookingId']}'");
    
    // Summary
    $logger->info("Test completed successfully!");
    echo "Test completed successfully! Check logs/test_payment.log for details.\n";
    
} catch (Exception $e) {
    $logger->error("Test failed: " . $e->getMessage());
    $logger->debug("Error trace: " . $e->getTraceAsString());
    echo "Test failed! Check logs/test_payment.log for details.\n";
    exit(1);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
