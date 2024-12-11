<?php
require_once __DIR__ . '/includes/monitoring/UptimeMonitor.php';
require_once __DIR__ . '/includes/config/Database.php';

// Set content type to JSON
header('Content-Type: application/json');

try {
    // Initialize database connection
    $db = new Database();
    $conn = $db->getConnection();
    
    // Initialize uptime monitor
    $monitor = new \Monitoring\UptimeMonitor($conn);
    
    // Check system health
    $status = $monitor->checkHealth();
    
    // Set HTTP status code based on health status
    http_response_code($status['status'] === 'healthy' ? 200 : 503);
    
    // Output status
    echo json_encode([
        'status' => $status['status'],
        'timestamp' => $status['timestamp'],
        'environment' => getenv('APP_ENV'),
        'version' => '1.0.0'
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Internal server error',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}
