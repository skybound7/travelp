<?php
require_once 'includes/config.php';
require_once 'includes/booking/ComboItinerary.php';

function runTests() {
    $conn = getDBConnection();
    echo "Database connection: " . ($conn ? "SUCCESS" : "FAILED") . "\n";
    
    // Test database tables
    $tables = ['combo_itineraries', 'itinerary_components', 'itinerary_schedule', 'payments', 'payment_refunds'];
    foreach ($tables as $table) {
        $result = $conn->query("SELECT 1 FROM $table LIMIT 1");
        echo "Table $table: " . ($result ? "EXISTS" : "MISSING") . "\n";
    }
    
    // Test sample data
    $result = $conn->query("SELECT COUNT(*) as count FROM combo_itineraries");
    $row = $result->fetch_assoc();
    echo "Sample itineraries: " . $row['count'] . "\n";
    
    // Test ComboItinerary class
    try {
        $itinerary = new ComboItinerary(1); // Test with ID 1
        $mismatches = $itinerary->validateDateTimeConsistency();
        echo "Time validation: " . (is_array($mismatches) ? "SUCCESS" : "FAILED") . "\n";
        if (!empty($mismatches)) {
            echo "Found " . count($mismatches) . " time mismatches\n";
            foreach ($mismatches as $mismatch) {
                echo "- " . $mismatch['message'] . "\n";
            }
        }
    } catch (Exception $e) {
        echo "Error testing ComboItinerary: " . $e->getMessage() . "\n";
    }
}

// Run the tests
echo "=== Running System Verification ===\n";
runTests();
