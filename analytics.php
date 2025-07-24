<?php
session_start();

class Analytics {
    private $analyticsFile = 'data/analytics.json';
    private $userSessionsFile = 'data/user_sessions.json';
    
    public function __construct() {
        // Ensure data directory exists
        if (!file_exists('data')) {
            mkdir('data', 0755, true);
        }
        
        // Initialize analytics file if it doesn't exist
        if (!file_exists($this->analyticsFile)) {
            $this->initializeAnalytics();
        }
        
        // Initialize user sessions file if it doesn't exist
        if (!file_exists($this->userSessionsFile)) {
            file_put_contents($this->userSessionsFile, json_encode([]));
        }
    }
    
    private function initializeAnalytics() {
        $initialData = [
            'unique_visitors' => 0,
            'daily_stats' => [],
            'hourly_stats' => [],
            'browser_stats' => [],
            'os_stats' => [],
            'country_stats' => [],
            'page_views' => [],
            'avg_session_duration' => 0,
            'referrer_stats' => [],
            'device_stats' => [
                'desktop' => 0,
                'mobile' => 0,
                'tablet' => 0
            ]
        ];
        file_put_contents($this->analyticsFile, json_encode($initialData, JSON_PRETTY_PRINT));
    }
    
    public function trackVisit() {
        $ip = $this->getUserIP();
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $referrer = $_SERVER['HTTP_REFERER'] ?? 'Direct';
        $timestamp = time();
        $date = date('Y-m-d');
        $hour = date('H');
        
        // Load current analytics data
        $analytics = json_decode(file_get_contents($this->analyticsFile), true);
        $sessions = json_decode(file_get_contents($this->userSessionsFile), true);
        
        // Check if this is a new unique visitor
        $isNewVisitor = !isset($sessions[$ip]);
        
        // Only track unique visitors
        if (!$isNewVisitor) {
            return; // Exit early if visitor is not unique
        }
        
        // Update unique visitors only
        $analytics['unique_visitors']++;
        
        // Update daily stats
        if (!isset($analytics['daily_stats'][$date])) {
            $analytics['daily_stats'][$date] = [
                'unique_visitors' => 0
            ];
        }
        $analytics['daily_stats'][$date]['unique_visitors']++;
        
        // Update hourly stats
        if (!isset($analytics['hourly_stats'][$hour])) {
            $analytics['hourly_stats'][$hour] = 0;
        }
        $analytics['hourly_stats'][$hour]++;
        
        // Parse user agent for browser and OS info
        $browserInfo = $this->parseBrowser($userAgent);
        $osInfo = $this->parseOS($userAgent);
        $deviceType = $this->getDeviceType($userAgent);
        
        // Update browser stats
        $browserName = $browserInfo['name'];
        if (!isset($analytics['browser_stats'][$browserName])) {
            $analytics['browser_stats'][$browserName] = 0;
        }
        $analytics['browser_stats'][$browserName]++;
        
        // Update OS stats
        if (!isset($analytics['os_stats'][$osInfo])) {
            $analytics['os_stats'][$osInfo] = 0;
        }
        $analytics['os_stats'][$osInfo]++;
        
        // Update device stats
        $analytics['device_stats'][$deviceType]++;
        
        // Update referrer stats
        $referrerDomain = $this->getDomain($referrer);
        if (!isset($analytics['referrer_stats'][$referrerDomain])) {
            $analytics['referrer_stats'][$referrerDomain] = 0;
        }
        $analytics['referrer_stats'][$referrerDomain]++;
        
        // Get country info (simplified - you can integrate with a GeoIP service)
        $country = $this->getCountryByIP($ip);
        if (!isset($analytics['country_stats'][$country])) {
            $analytics['country_stats'][$country] = 0;
        }
        $analytics['country_stats'][$country]++;
        
        // Record page view
        $pageView = [
            'timestamp' => $timestamp,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'referrer' => $referrer,
            'page' => 'index.php'
        ];
        $analytics['page_views'][] = $pageView;
        
        // Keep only last 1000 page views to prevent file from getting too large
        if (count($analytics['page_views']) > 1000) {
            $analytics['page_views'] = array_slice($analytics['page_views'], -1000);
        }
        
        // Update user session (for unique visitors only)
        $sessions[$ip] = [
            'first_visit' => $timestamp,
            'last_visit' => $timestamp,
            'visit_count' => 1,
            'session_start' => $timestamp
        ];
        
        // Save updated data
        file_put_contents($this->analyticsFile, json_encode($analytics, JSON_PRETTY_PRINT));
        file_put_contents($this->userSessionsFile, json_encode($sessions, JSON_PRETTY_PRINT));
    }
    
    private function getUserIP() {
        $ipKeys = ['HTTP_CF_CONNECTING_IP', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    private function parseBrowser($userAgent) {
        $browsers = [
            'Chrome' => '/Chrome\/([0-9\.]+)/',
            'Firefox' => '/Firefox\/([0-9\.]+)/',
            'Safari' => '/Safari\/([0-9\.]+)/',
            'Edge' => '/Edge\/([0-9\.]+)/',
            'Internet Explorer' => '/MSIE ([0-9\.]+)/',
            'Opera' => '/Opera\/([0-9\.]+)/'
        ];
        
        foreach ($browsers as $browser => $pattern) {
            if (preg_match($pattern, $userAgent, $matches)) {
                return ['name' => $browser, 'version' => $matches[1] ?? 'Unknown'];
            }
        }
        return ['name' => 'Unknown', 'version' => 'Unknown'];
    }
    
    private function parseOS($userAgent) {
        $osList = [
            'Windows 10' => '/Windows NT 10\.0/',
            'Windows 8.1' => '/Windows NT 6\.3/',
            'Windows 8' => '/Windows NT 6\.2/',
            'Windows 7' => '/Windows NT 6\.1/',
            'Mac OS X' => '/Mac OS X/',
            'Linux' => '/Linux/',
            'Ubuntu' => '/Ubuntu/',
            'Android' => '/Android/',
            'iOS' => '/iPhone|iPad/',
        ];
        
        foreach ($osList as $os => $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return $os;
            }
        }
        return 'Unknown';
    }
    
    private function getDeviceType($userAgent) {
        if (preg_match('/Mobile|Android|iPhone|iPad/', $userAgent)) {
            if (preg_match('/iPad/', $userAgent)) {
                return 'tablet';
            }
            return 'mobile';
        }
        return 'desktop';
    }
    
    private function getDomain($url) {
        if ($url === 'Direct' || empty($url)) {
            return 'Direct';
        }
        $parsedUrl = parse_url($url);
        return $parsedUrl['host'] ?? 'Unknown';
    }
    
    private function getCountryByIP($ip) {
        // Simplified country detection - you can integrate with a GeoIP service
        // For now, we'll just return a placeholder
        if ($ip === '127.0.0.1' || $ip === '::1' || strpos($ip, '192.168.') === 0) {
            return 'Local';
        }
        return 'Unknown'; // In production, use a GeoIP service
    }
    
    public function getAnalytics() {
        return json_decode(file_get_contents($this->analyticsFile), true);
    }
    
    public function getRealtimeStats($minutes = 30) {
        $analytics = $this->getAnalytics();
        $cutoffTime = time() - ($minutes * 60);
        
        $realtimeVisits = 0;
        foreach ($analytics['page_views'] as $pageView) {
            if ($pageView['timestamp'] >= $cutoffTime) {
                $realtimeVisits++;
            }
        }
        
        return [
            'realtime_visitors' => $realtimeVisits,
            'timeframe' => $minutes . ' minutes'
        ];
    }
    
    public function displayDashboard() {
        $analytics = $this->getAnalytics();
        $realtime = $this->getRealtimeStats();
        
        echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Site Analytics</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #eff0f2;
            color: #202124;
            line-height: 1.5;
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 24px; }
        .header { 
            background: #fff;
            padding: 24px 32px;
            border-radius: 8px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid #e8eaed;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 { 
            font-size: 24px;
            font-weight: 400;
            color: #202124;
        }
        .refresh-btn { 
            background: #1976d2;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s;
        }
        .refresh-btn:hover { background: #1565c0; }
        
        .main-metric {
            background: #fff;
            padding: 48px 32px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid #e8eaed;
        }
        .main-metric .number {
            font-size: 64px;
            font-weight: 300;
            color: #1976d2;
            margin-bottom: 8px;
            line-height: 1;
        }
        .main-metric .label {
            font-size: 16px;
            color: #5f6368;
            font-weight: 400;
        }
        
        .secondary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        .metric-card {
            background: #fff;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid #e8eaed;
        }
        .metric-card .number {
            font-size: 28px;
            font-weight: 400;
            color: #202124;
            margin-bottom: 4px;
        }
        .metric-card .label {
            font-size: 14px;
            color: #5f6368;
            font-weight: 400;
        }
        
        .data-section {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid #e8eaed;
            margin-bottom: 16px;
            overflow: hidden;
        }
        .section-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e8eaed;
            background: #f8f9fa;
        }
        .section-header h3 {
            font-size: 16px;
            font-weight: 500;
            color: #202124;
        }
        .section-content {
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 24px;
            text-align: left;
            border-bottom: 1px solid #e8eaed;
            font-size: 14px;
        }
        th {
            background: #f8f9fa;
            font-weight: 500;
            color: #5f6368;
        }
        td {
            color: #202124;
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:hover {
            background: #f8f9fa;
        }
        .percentage {
            color: #5f6368;
            font-size: 13px;
        }
        .no-data {
            padding: 24px;
            text-align: center;
            color: #5f6368;
            font-size: 14px;
        }
    </style>
    <script>
        setTimeout(function(){ location.reload(); }, 60000);
    </script>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>Site Analytics</h1>
            <button onclick='location.reload()' class='refresh-btn'>Refresh</button>
        </div>
        
        <div class='main-metric'>
            <div class='number'>{$analytics['unique_visitors']}</div>
            <div class='label'>Total Site Visitors</div>
        </div>
        
        <div class='secondary-grid'>
            <div class='metric-card'>
                <div class='number'>{$realtime['realtime_visitors']}</div>
                <div class='label'>Active now</div>
            </div>";
        
        // Today's visitors
        $today = date('Y-m-d');
        $todayUnique = $analytics['daily_stats'][$today]['unique_visitors'] ?? 0;
        
        echo "<div class='metric-card'>
                <div class='number'>{$todayUnique}</div>
                <div class='label'>Today's visitors</div>
            </div>
        </div>";
        
        // Browser stats
        echo "<div class='data-section'>
            <div class='section-header'>
                <h3>Browsers</h3>
            </div>
            <div class='section-content'>";
        
        if (!empty($analytics['browser_stats'])) {
            echo "<table>
                <thead>
                    <tr><th>Browser</th><th>Visitors</th><th>Share</th></tr>
                </thead>
                <tbody>";
            arsort($analytics['browser_stats']);
            foreach (array_slice($analytics['browser_stats'], 0, 5) as $browser => $count) {
                $percentage = round(($count / $analytics['unique_visitors']) * 100, 1);
                echo "<tr><td>{$browser}</td><td>{$count}</td><td class='percentage'>{$percentage}%</td></tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='no-data'>No data available</div>";
        }
        echo "</div></div>";
        
        // OS stats
        echo "<div class='data-section'>
            <div class='section-header'>
                <h3>Operating Systems</h3>
            </div>
            <div class='section-content'>";
        
        if (!empty($analytics['os_stats'])) {
            echo "<table>
                <thead>
                    <tr><th>Operating System</th><th>Visitors</th><th>Share</th></tr>
                </thead>
                <tbody>";
            arsort($analytics['os_stats']);
            foreach (array_slice($analytics['os_stats'], 0, 5) as $os => $count) {
                $percentage = round(($count / $analytics['unique_visitors']) * 100, 1);
                echo "<tr><td>{$os}</td><td>{$count}</td><td class='percentage'>{$percentage}%</td></tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='no-data'>No data available</div>";
        }
        echo "</div></div>";
        
        // Device stats
        echo "<div class='data-section'>
            <div class='section-header'>
                <h3>Devices</h3>
            </div>
            <div class='section-content'>
                <table>
                    <thead>
                        <tr><th>Device Type</th><th>Visitors</th><th>Share</th></tr>
                    </thead>
                    <tbody>";
        
        foreach ($analytics['device_stats'] as $device => $count) {
            $percentage = $analytics['unique_visitors'] > 0 ? round(($count / $analytics['unique_visitors']) * 100, 1) : 0;
            echo "<tr><td>" . ucfirst($device) . "</td><td>{$count}</td><td class='percentage'>{$percentage}%</td></tr>";
        }
        echo "</tbody></table>
            </div>
        </div>";
        
        // Recent page views
        echo "<div class='data-section'>
            <div class='section-header'>
                <h3>Recent Visitors</h3>
            </div>
            <div class='section-content'>";
        
        if (!empty($analytics['page_views'])) {
            echo "<table>
                <thead>
                    <tr><th>Time</th><th>IP Address</th><th>Browser</th><th>Device</th><th>Source</th></tr>
                </thead>
                <tbody>";
            $recentViews = array_slice($analytics['page_views'], -10);
            foreach (array_reverse($recentViews) as $view) {
                $time = date('M j, H:i', $view['timestamp']);
                $browser = $this->parseBrowser($view['user_agent'])['name'];
                $device = ucfirst($this->getDeviceType($view['user_agent']));
                $referrer = $this->getDomain($view['referrer']);
                echo "<tr><td>{$time}</td><td>{$view['ip']}</td><td>{$browser}</td><td>{$device}</td><td>{$referrer}</td></tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='no-data'>No recent visitors</div>";
        }
        
        echo "</div></div>";
        echo "</div></body></html>";
    }
}

// If this file is accessed directly, show the dashboard
if (basename($_SERVER['PHP_SELF']) == 'analytics.php') {
    $analytics = new Analytics();
    $analytics->displayDashboard();
}
?>
