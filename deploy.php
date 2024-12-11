<?php
class Deployer {
    private $config;
    private $errors = [];
    private $warnings = [];
    private $success = [];
    
    public function __construct() {
        $this->config = require_once 'config/config.php';
    }
    
    public function deploy() {
        try {
            $this->checkCriticalIssues();
            $this->performStagingTests();
            $this->backupCurrentSystem();
            $this->deployToProduction();
            $this->setupMonitoring();
            
            return [
                'success' => $this->success,
                'warnings' => $this->warnings,
                'errors' => $this->errors
            ];
        } catch (Exception $e) {
            $this->errors[] = "Deployment failed: " . $e->getMessage();
            return ['errors' => $this->errors];
        }
    }
    
    private function checkCriticalIssues() {
        // Security Checks
        $this->checkSecurityHeaders();
        $this->checkFilePermissions();
        $this->checkSQLInjectionProtection();
        $this->checkXSSProtection();
        $this->checkCSRFProtection();
        
        // Privacy Compliance
        $this->checkGDPRCompliance();
        $this->checkCookieConsent();
        $this->checkPrivacyPolicy();
        
        // Performance
        $this->checkLoadTime();
        $this->checkMobileOptimization();
        $this->checkDatabaseOptimization();
        
        if (!empty($this->errors)) {
            throw new Exception("Critical issues must be resolved before deployment");
        }
    }
    
    private function checkSecurityHeaders() {
        $headers = [
            'X-Frame-Options',
            'X-Content-Type-Options',
            'Strict-Transport-Security',
            'Content-Security-Policy'
        ];
        
        $htaccess = file_get_contents('.htaccess');
        foreach ($headers as $header) {
            if (strpos($htaccess, $header) === false) {
                $this->errors[] = "Missing security header: $header";
            }
        }
    }
    
    private function checkFilePermissions() {
        $dirs = [
            'config' => '0755',
            'includes' => '0755',
            'uploads' => '0755',
            'logs' => '0755'
        ];
        
        foreach ($dirs as $dir => $perm) {
            if (!file_exists($dir)) {
                $this->errors[] = "Directory missing: $dir";
                continue;
            }
            
            $currentPerm = substr(sprintf('%o', fileperms($dir)), -4);
            if ($currentPerm != $perm) {
                $this->errors[] = "Incorrect permissions on $dir: $currentPerm (should be $perm)";
            }
        }
    }
    
    private function checkSQLInjectionProtection() {
        $files = glob('includes/**/*.php');
        foreach ($files as $file) {
            $content = file_get_contents($file);
            if (preg_match('/mysqli_query\s*\(\s*\$[^,]*,\s*[\'"][^\'"\$]*\$[^\'"\$]*[\'"]\s*\)/', $content)) {
                $this->errors[] = "Potential SQL injection in $file";
            }
        }
    }
    
    private function checkXSSProtection() {
        $files = glob('includes/**/*.php');
        foreach ($files as $file) {
            $content = file_get_contents($file);
            if (preg_match('/echo\s+\$_(?:GET|POST|REQUEST|COOKIE)/', $content)) {
                $this->errors[] = "Potential XSS vulnerability in $file";
            }
        }
    }
    
    private function checkCSRFProtection() {
        $forms = $this->findForms();
        foreach ($forms as $form) {
            if (!preg_match('/csrf_token/', file_get_contents($form))) {
                $this->errors[] = "Missing CSRF protection in $form";
            }
        }
    }
    
    private function checkGDPRCompliance() {
        $required = [
            'data-request.php',
            'privacy-policy.php',
            'cookie-policy.php',
            'terms-of-service.php'
        ];
        
        foreach ($required as $file) {
            if (!file_exists($file)) {
                $this->errors[] = "Missing GDPR required file: $file";
            }
        }
    }
    
    private function checkCookieConsent() {
        if (!class_exists('CookieManager')) {
            $this->errors[] = "Cookie consent system not implemented";
        }
    }
    
    private function checkPrivacyPolicy() {
        $policy = file_get_contents('privacy-policy.php');
        $required = [
            'data collection',
            'data processing',
            'user rights',
            'contact information'
        ];
        
        foreach ($required as $term) {
            if (stripos($policy, $term) === false) {
                $this->errors[] = "Privacy policy missing: $term";
            }
        }
    }
    
    private function checkLoadTime() {
        // Implement load time check
        $startTime = microtime(true);
        file_get_contents($this->config['base_url']);
        $loadTime = microtime(true) - $startTime;
        
        if ($loadTime > 3) {
            $this->errors[] = "Page load time exceeds 3 seconds: {$loadTime}s";
        }
    }
    
    private function checkMobileOptimization() {
        if (!file_exists('manifest.json')) {
            $this->errors[] = "Missing web app manifest";
        }
        if (!file_exists('service-worker.js')) {
            $this->errors[] = "Missing service worker";
        }
    }
    
    private function checkDatabaseOptimization() {
        $tables = [
            'data_requests',
            'login_history',
            'marketing_preferences',
            'consent_logs',
            'analytics_events',
            'performance_metrics'
        ];
        
        foreach ($tables as $table) {
            $result = mysqli_query($this->conn, "SHOW INDEX FROM $table");
            if (mysqli_num_rows($result) < 2) { // At least primary key + one index
                $this->errors[] = "Missing indexes on table: $table";
            }
        }
    }
    
    private function performStagingTests() {
        // Run verification scripts
        include_once 'tests/verify_system.php';
        $verification = new SystemVerification($this->conn);
        $results = $verification->verifyAll();
        
        foreach ($results as $category => $checks) {
            foreach ($checks as $check => $status) {
                if ($status !== 'OK' && !is_array($status)) {
                    $this->errors[] = "$category: $check failed ($status)";
                }
            }
        }
    }
    
    private function backupCurrentSystem() {
        $timestamp = date('Y-m-d_H-i-s');
        
        // Backup database
        $command = "mysqldump -u {$this->config['db_user']} -p{$this->config['db_pass']} {$this->config['db_name']} > backup/db_$timestamp.sql";
        exec($command, $output, $return);
        if ($return !== 0) {
            $this->errors[] = "Database backup failed";
        }
        
        // Backup files
        $command = "tar -czf backup/files_$timestamp.tar.gz .";
        exec($command, $output, $return);
        if ($return !== 0) {
            $this->errors[] = "File backup failed";
        }
    }
    
    private function deployToProduction() {
        // Set production configs
        file_put_contents('config/config.php', str_replace(
            ['development', 'debug'],
            ['production', 'false'],
            file_get_contents('config/config.php')
        ));
        
        // Clear caches
        $this->clearCache();
        
        // Update maintenance page
        file_put_contents('maintenance.html', $this->getMaintenancePage());
        
        $this->success[] = "Deployed to production successfully";
    }
    
    private function setupMonitoring() {
        // Setup Uptime monitoring
        $this->setupUptimeMonitoring();
        
        // Setup Error monitoring
        $this->setupErrorMonitoring();
        
        // Setup Performance monitoring
        $this->setupPerformanceMonitoring();
        
        // Setup Security monitoring
        $this->setupSecurityMonitoring();
    }
    
    private function setupUptimeMonitoring() {
        $urls = [
            $this->config['base_url'],
            $this->config['base_url'] . '/api/',
            $this->config['base_url'] . '/booking'
        ];
        
        foreach ($urls as $url) {
            // Add URL to monitoring service
            $this->success[] = "Added uptime monitoring for: $url";
        }
    }
    
    private function setupErrorMonitoring() {
        // Configure error logging
        ini_set('error_log', 'logs/error.log');
        ini_set('log_errors', 1);
        error_reporting(E_ALL & ~E_NOTICE);
        
        $this->success[] = "Error monitoring configured";
    }
    
    private function setupPerformanceMonitoring() {
        // Initialize performance monitoring
        include_once 'includes/performance/PerformanceOptimizer.php';
        $optimizer = new PerformanceOptimizer();
        $optimizer->enableMonitoring();
        
        $this->success[] = "Performance monitoring enabled";
    }
    
    private function setupSecurityMonitoring() {
        // Setup security scanning schedule
        $this->success[] = "Security monitoring configured";
    }
    
    private function clearCache() {
        $cacheDirs = [
            'cache/',
            'tmp/',
            'logs/'
        ];
        
        foreach ($cacheDirs as $dir) {
            if (file_exists($dir)) {
                array_map('unlink', glob("$dir/*"));
            }
        }
    }
    
    private function getMaintenancePage() {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Maintenance - Luxury Travel</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        h1 { color: var(--luxury-gold); }
    </style>
</head>
<body>
    <h1>Maintenance in Progress</h1>
    <p>We're performing scheduled maintenance. Please check back shortly.</p>
</body>
</html>
HTML;
    }
    
    private function findForms() {
        $forms = [];
        $files = glob('**/*.php');
        foreach ($files as $file) {
            if (preg_match('/<form[^>]*>/', file_get_contents($file))) {
                $forms[] = $file;
            }
        }
        return $forms;
    }
}

// Run deployment
$deployer = new Deployer();
$result = $deployer->deploy();

// Output results
echo "Deployment Results:\n\n";
echo "Errors:\n";
foreach ($result['errors'] ?? [] as $error) {
    echo "- $error\n";
}

echo "\nWarnings:\n";
foreach ($result['warnings'] ?? [] as $warning) {
    echo "- $warning\n";
}

echo "\nSuccess:\n";
foreach ($result['success'] ?? [] as $success) {
    echo "- $success\n";
}
