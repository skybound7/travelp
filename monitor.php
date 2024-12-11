<?php
class SystemMonitor {
    private $config;
    private $alerts = [];
    
    public function __construct() {
        $this->config = require_once 'config/config.php';
    }
    
    public function monitor() {
        $this->checkSystem();
        $this->checkPerformance();
        $this->checkSecurity();
        $this->checkErrors();
        $this->checkAnalytics();
        
        return $this->alerts;
    }
    
    private function checkSystem() {
        // Check server resources
        $load = sys_getloadavg();
        if ($load[0] > 2) {
            $this->alert('HIGH_LOAD', "High server load: {$load[0]}");
        }
        
        // Check disk space
        $free = disk_free_space('/');
        $total = disk_total_space('/');
        $used = ($total - $free) / $total * 100;
        if ($used > 90) {
            $this->alert('DISK_SPACE', "Low disk space: {$used}% used");
        }
        
        // Check memory usage
        $memory = memory_get_usage(true);
        $limit = ini_get('memory_limit');
        if ($memory > $this->parseSize($limit) * 0.9) {
            $this->alert('MEMORY', "High memory usage: {$memory} bytes");
        }
    }
    
    private function checkPerformance() {
        // Check page load times
        $urls = [
            '/',
            '/booking',
            '/search',
            '/profile'
        ];
        
        foreach ($urls as $url) {
            $startTime = microtime(true);
            file_get_contents($this->config['base_url'] . $url);
            $loadTime = microtime(true) - $startTime;
            
            if ($loadTime > 3) {
                $this->alert('SLOW_PAGE', "Slow page load on $url: {$loadTime}s");
            }
        }
        
        // Check database performance
        $this->checkDatabasePerformance();
    }
    
    private function checkSecurity() {
        // Check for failed login attempts
        $query = "SELECT COUNT(*) as count FROM login_history WHERE success = 0 AND timestamp > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
        $result = mysqli_query($this->conn, $query);
        $row = mysqli_fetch_assoc($result);
        if ($row['count'] > 10) {
            $this->alert('SECURITY', "High number of failed login attempts: {$row['count']}");
        }
        
        // Check file permissions
        $this->checkFilePermissions();
        
        // Check SSL certificate
        $this->checkSSLCertificate();
    }
    
    private function checkErrors() {
        // Check error logs
        $logs = file_get_contents('logs/error.log');
        $errors = explode("\n", $logs);
        $recentErrors = array_slice($errors, -100);
        
        $errorCount = 0;
        foreach ($recentErrors as $error) {
            if (strpos($error, '[ERROR]') !== false) {
                $errorCount++;
            }
        }
        
        if ($errorCount > 10) {
            $this->alert('ERRORS', "High number of errors in log: $errorCount");
        }
        
        // Check 404 errors
        $this->check404Errors();
    }
    
    private function checkAnalytics() {
        // Check Google Analytics
        if (!$this->isAnalyticsWorking()) {
            $this->alert('ANALYTICS', "Google Analytics not reporting data");
        }
        
        // Check conversion tracking
        $this->checkConversionTracking();
    }
    
    private function checkDatabasePerformance() {
        $query = "SHOW GLOBAL STATUS WHERE Variable_name IN ('Slow_queries', 'Questions')";
        $result = mysqli_query($this->conn, $query);
        
        $stats = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $stats[$row['Variable_name']] = $row['Value'];
        }
        
        $slowQueryRatio = $stats['Slow_queries'] / $stats['Questions'] * 100;
        if ($slowQueryRatio > 1) {
            $this->alert('DB_PERFORMANCE', "High number of slow queries: {$slowQueryRatio}%");
        }
    }
    
    private function checkFilePermissions() {
        $criticalFiles = [
            'config/config.php' => '0644',
            'includes/database.php' => '0644'
        ];
        
        foreach ($criticalFiles as $file => $expectedPerm) {
            if (file_exists($file)) {
                $currentPerm = substr(sprintf('%o', fileperms($file)), -4);
                if ($currentPerm != $expectedPerm) {
                    $this->alert('FILE_PERMISSIONS', "Incorrect permissions on $file: $currentPerm (should be $expectedPerm)");
                }
            }
        }
    }
    
    private function checkSSLCertificate() {
        $url = parse_url($this->config['base_url'])['host'];
        $cert = openssl_x509_parse(openssl_x509_read(openssl_get_peer_certificate(fsockopen("ssl://$url", 443))));
        
        $expiry = $cert['validTo_time_t'];
        $daysToExpiry = ($expiry - time()) / (60 * 60 * 24);
        
        if ($daysToExpiry < 30) {
            $this->alert('SSL', "SSL certificate expires in $daysToExpiry days");
        }
    }
    
    private function check404Errors() {
        $accessLog = file_get_contents('logs/access.log');
        $lines = explode("\n", $accessLog);
        
        $errors404 = [];
        foreach ($lines as $line) {
            if (strpos($line, ' 404 ') !== false) {
                preg_match('/GET ([^ ]+)/', $line, $matches);
                if (isset($matches[1])) {
                    $errors404[$matches[1]] = ($errors404[$matches[1]] ?? 0) + 1;
                }
            }
        }
        
        foreach ($errors404 as $url => $count) {
            if ($count > 10) {
                $this->alert('404_ERRORS', "Frequent 404 errors for $url: $count times");
            }
        }
    }
    
    private function isAnalyticsWorking() {
        // Check if analytics.js is loaded
        $homepage = file_get_contents($this->config['base_url']);
        return strpos($homepage, 'google-analytics.com/analytics.js') !== false;
    }
    
    private function checkConversionTracking() {
        // Check conversion events in the last hour
        $query = "SELECT COUNT(*) as count FROM analytics_events WHERE event_type = 'conversion' AND timestamp > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
        $result = mysqli_query($this->conn, $query);
        $row = mysqli_fetch_assoc($result);
        
        if ($row['count'] === 0) {
            $this->alert('CONVERSIONS', "No conversions recorded in the last hour");
        }
    }
    
    private function alert($type, $message) {
        $this->alerts[] = [
            'type' => $type,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // Log alert
        error_log("[ALERT][$type] $message");
        
        // Send notification if critical
        if (in_array($type, ['SECURITY', 'HIGH_LOAD', 'ERRORS'])) {
            $this->sendNotification($type, $message);
        }
    }
    
    private function sendNotification($type, $message) {
        // Configure your notification method (email, Slack, etc.)
        mail(
            $this->config['admin_email'],
            "System Alert: $type",
            $message,
            "From: {$this->config['system_email']}\r\n"
        );
    }
    
    private function parseSize($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);
        return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
    }
}

// Run monitoring
$monitor = new SystemMonitor();
$alerts = $monitor->monitor();

// Output alerts
if (!empty($alerts)) {
    echo "System Alerts:\n\n";
    foreach ($alerts as $alert) {
        echo "[{$alert['timestamp']}] {$alert['type']}: {$alert['message']}\n";
    }
} else {
    echo "All systems operating normally.\n";
}

// Save alerts to database
$query = "INSERT INTO system_alerts (type, message, timestamp) VALUES (?, ?, NOW())";
$stmt = mysqli_prepare($conn, $query);

foreach ($alerts as $alert) {
    mysqli_stmt_bind_param($stmt, 'ss', $alert['type'], $alert['message']);
    mysqli_stmt_execute($stmt);
}
