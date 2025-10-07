<?php
/**
 * Server Debug Script
 * This script helps diagnose server configuration issues
 * Remove this file after debugging for security reasons
 */

// Prevent direct access in production (optional safety check)
if (!isset($_GET['debug']) || $_GET['debug'] !== 'true') {
    die('Access denied. Add ?debug=true to access this script.');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Debug Information</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        .section { background: white; margin: 20px 0; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .section h2 { color: #333; border-bottom: 2px solid #007cba; padding-bottom: 10px; }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        .info { color: #17a2b8; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; }
        .code { background: #f8f9fa; padding: 10px; border-radius: 4px; font-family: monospace; margin: 10px 0; }
        .status-ok { background: #d4edda; color: #155724; padding: 5px 10px; border-radius: 4px; }
        .status-error { background: #f8d7da; color: #721c24; padding: 5px 10px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Server Debug Information</h1>
        <p><strong>Generated:</strong> <?php echo date('Y-m-d H:i:s T'); ?></p>

        <!-- Server Environment -->
        <div class="section">
            <h2>🌐 Server Environment</h2>
            <table>
                <tr><th>Server Software</th><td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></td></tr>
                <tr><th>PHP Version</th><td><?php echo PHP_VERSION; ?></td></tr>
                <tr><th>Document Root</th><td><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown'; ?></td></tr>
                <tr><th>Script Name</th><td><?php echo $_SERVER['SCRIPT_NAME'] ?? 'Unknown'; ?></td></tr>
                <tr><th>Request URI</th><td><?php echo $_SERVER['REQUEST_URI'] ?? 'Unknown'; ?></td></tr>
                <tr><th>HTTP Host</th><td><?php echo $_SERVER['HTTP_HOST'] ?? 'Unknown'; ?></td></tr>
                <tr><th>Server Name</th><td><?php echo $_SERVER['SERVER_NAME'] ?? 'Unknown'; ?></td></tr>
                <tr><th>Server Port</th><td><?php echo $_SERVER['SERVER_PORT'] ?? 'Unknown'; ?></td></tr>
            </table>
        </div>

        <!-- HTTPS Detection -->
        <div class="section">
            <h2>🔒 HTTPS Detection</h2>
            <table>
                <tr><th>$_SERVER['HTTPS']</th><td><?php echo isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : '<span class="error">Not Set</span>'; ?></td></tr>
                <tr><th>$_SERVER['HTTP_X_FORWARDED_PROTO']</th><td><?php echo $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '<span class="error">Not Set</span>'; ?></td></tr>
                <tr><th>$_SERVER['HTTP_X_FORWARDED_SSL']</th><td><?php echo $_SERVER['HTTP_X_FORWARDED_SSL'] ?? '<span class="error">Not Set</span>'; ?></td></tr>
                <tr><th>$_SERVER['SERVER_PORT']</th><td><?php echo $_SERVER['SERVER_PORT'] ?? 'Unknown'; ?></td></tr>
            </table>
            
            <?php
            // Test HTTPS detection logic
            $is_https = (
                (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
                (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
                (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') ||
                (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
            );
            ?>
            <p><strong>Detected Protocol:</strong> 
                <span class="<?php echo $is_https ? 'status-ok' : 'status-error'; ?>">
                    <?php echo $is_https ? 'HTTPS' : 'HTTP'; ?>
                </span>
            </p>
        </div>

        <!-- Base URL Generation -->
        <div class="section">
            <h2>🔗 Base URL Generation</h2>
            <?php
            // Simulate the base_url logic from config.php
            $protocol = (
                (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
                (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
                (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') ||
                (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
            ) ? 'https' : 'http';
            
            $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
            $script_name = $_SERVER['SCRIPT_NAME'] ?? '';
            $path = rtrim(dirname($script_name), '/\\');
            $base_url = $protocol . '://' . $host . $path . '/';
            ?>
            
            <div class="code">
                <strong>Generated Base URL:</strong> <?php echo $base_url; ?>
            </div>
            
            <table>
                <tr><th>Protocol</th><td><?php echo $protocol; ?></td></tr>
                <tr><th>Host</th><td><?php echo $host; ?></td></tr>
                <tr><th>Path</th><td><?php echo $path; ?></td></tr>
                <tr><th>Full Base URL</th><td><?php echo $base_url; ?></td></tr>
            </table>
        </div>

        <!-- File Existence Check -->
        <div class="section">
            <h2>📁 Critical Files Check</h2>
            <?php
            $files_to_check = [
                'application/config/config.php',
                'application/config/routes.php',
                'application/controllers/Login.php',
                'index.php',
                '.htaccess'
            ];
            ?>
            <table>
                <tr><th>File</th><th>Status</th><th>Readable</th></tr>
                <?php foreach ($files_to_check as $file): ?>
                <tr>
                    <td><?php echo $file; ?></td>
                    <td>
                        <?php if (file_exists($file)): ?>
                            <span class="status-ok">✅ Exists</span>
                        <?php else: ?>
                            <span class="status-error">❌ Missing</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (file_exists($file) && is_readable($file)): ?>
                            <span class="status-ok">✅ Readable</span>
                        <?php else: ?>
                            <span class="status-error">❌ Not Readable</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <!-- Routes Check -->
        <div class="section">
            <h2>🛣️ Routes Configuration</h2>
            <?php
            $routes_file = 'application/config/routes.php';
            if (file_exists($routes_file) && is_readable($routes_file)):
                $routes_content = file_get_contents($routes_file);
                $has_authenticate_route = strpos($routes_content, "route['authenticate']") !== false;
            ?>
                <p><strong>Routes file status:</strong> <span class="status-ok">✅ Found and readable</span></p>
                <p><strong>Authenticate route:</strong> 
                    <span class="<?php echo $has_authenticate_route ? 'status-ok' : 'status-error'; ?>">
                        <?php echo $has_authenticate_route ? '✅ Found' : '❌ Not found'; ?>
                    </span>
                </p>
                
                <?php if ($has_authenticate_route): ?>
                    <?php
                    // Extract the authenticate route line
                    $lines = explode("\n", $routes_content);
                    foreach ($lines as $line) {
                        if (strpos($line, "route['authenticate']") !== false) {
                            echo '<div class="code"><strong>Route definition:</strong> ' . htmlspecialchars(trim($line)) . '</div>';
                            break;
                        }
                    }
                    ?>
                <?php endif; ?>
            <?php else: ?>
                <p><strong>Routes file status:</strong> <span class="status-error">❌ Not found or not readable</span></p>
            <?php endif; ?>
        </div>

        <!-- Login Controller Check -->
        <div class="section">
            <h2>🎮 Login Controller Check</h2>
            <?php
            $controller_file = 'application/controllers/Login.php';
            if (file_exists($controller_file) && is_readable($controller_file)):
                $controller_content = file_get_contents($controller_file);
                $has_authenticate_method = strpos($controller_content, 'function authenticate') !== false || strpos($controller_content, 'public function authenticate') !== false;
                $has_ci_controller = strpos($controller_content, 'extends CI_Controller') !== false;
            ?>
                <p><strong>Login controller:</strong> <span class="status-ok">✅ Found and readable</span></p>
                <p><strong>Authenticate method:</strong> 
                    <span class="<?php echo $has_authenticate_method ? 'status-ok' : 'status-error'; ?>">
                        <?php echo $has_authenticate_method ? '✅ Found' : '❌ Not found'; ?>
                    </span>
                </p>
                <p><strong>Controller structure:</strong> 
                    <span class="<?php echo $has_ci_controller ? 'status-ok' : 'status-error'; ?>">
                        <?php echo $has_ci_controller ? '✅ Properly extends CI_Controller' : '❌ Does not extend CI_Controller'; ?>
                    </span>
                </p>
            <?php else: ?>
                <p><strong>Login controller:</strong> <span class="status-error">❌ Not found or not readable</span></p>
            <?php endif; ?>
        </div>

        <!-- Route Resolution Test -->
        <div class="section">
            <h2>🛣️ Route Resolution Test</h2>
            <?php
            // Test direct controller access URLs
            $test_urls = [
                'authenticate' => $base_url . 'authenticate',
                'login/authenticate' => $base_url . 'login/authenticate',
                'login' => $base_url . 'login'
            ];
            ?>
            <p><strong>Test URLs for authentication:</strong></p>
            <table>
                <tr><th>Route Type</th><th>URL</th><th>Action</th></tr>
                <?php foreach ($test_urls as $type => $url): ?>
                <tr>
                    <td><?php echo $type; ?></td>
                    <td><?php echo $url; ?></td>
                    <td><a href="<?php echo $url; ?>" target="_blank">Test Link</a></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <!-- Server Module Check -->
        <div class="section">
            <h2>🔧 Server Module Check</h2>
            <?php
            $mod_rewrite_enabled = false;
            if (function_exists('apache_get_modules')) {
                $modules = apache_get_modules();
                $mod_rewrite_enabled = in_array('mod_rewrite', $modules);
            }
            ?>
            <table>
                <tr><th>Module</th><th>Status</th></tr>
                <tr>
                    <td>mod_rewrite</td>
                    <td>
                        <?php if (function_exists('apache_get_modules')): ?>
                            <span class="<?php echo $mod_rewrite_enabled ? 'status-ok' : 'status-error'; ?>">
                                <?php echo $mod_rewrite_enabled ? '✅ Enabled' : '❌ Disabled'; ?>
                            </span>
                        <?php else: ?>
                            <span class="warning">⚠️ Cannot check (function not available)</span>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
            
            <?php if (!$mod_rewrite_enabled && function_exists('apache_get_modules')): ?>
                <p class="status-error">⚠️ mod_rewrite is disabled - this will cause routing issues with clean URLs</p>
            <?php endif; ?>
        </div>

        <!-- URL Testing -->
        <div class="section">
            <h2>🔗 URL Testing</h2>
            <p><strong>Test URLs:</strong></p>
            <ul>
                <li><a href="<?php echo $base_url; ?>" target="_blank"><?php echo $base_url; ?></a> (Home)</li>
                <li><a href="<?php echo $base_url; ?>authenticate" target="_blank"><?php echo $base_url; ?>authenticate</a> (Authenticate)</li>
                <li><a href="<?php echo $base_url; ?>login" target="_blank"><?php echo $base_url; ?>login</a> (Login)</li>
            </ul>
        </div>

        <!-- Recommendations -->
        <div class="section">
            <h2>💡 Recommendations</h2>
            <ul>
                <?php if (!($has_authenticate_route ?? false)): ?>
                <li class="error">❌ The authenticate route is missing from routes.php</li>
                <?php endif; ?>
                
                <?php if (!($has_authenticate_method ?? false)): ?>
                <li class="error">❌ The authenticate method is missing from Login controller</li>
                <?php endif; ?>
                
                <?php if (!file_exists('application/config/config.php')): ?>
                <li class="error">❌ Config file is missing - this will cause base_url issues</li>
                <?php endif; ?>
                
                <?php if (!file_exists('.htaccess')): ?>
                <li class="warning">⚠️ .htaccess file is missing - URL rewriting may not work</li>
                <?php endif; ?>
                
                <?php if (!$mod_rewrite_enabled && function_exists('apache_get_modules')): ?>
                <li class="error">❌ mod_rewrite is disabled - enable it in Apache configuration</li>
                <?php endif; ?>
                
                <?php if (($has_authenticate_route ?? false) && ($has_authenticate_method ?? false)): ?>
                <li class="success">✅ Authentication route and method are properly configured</li>
                <?php endif; ?>
                
                <li class="info">ℹ️ Try both /authenticate and /login/authenticate URLs to test routing</li>
                <li class="info">ℹ️ Remove this debug file after troubleshooting for security</li>
            </ul>
        </div>

        <div class="section">
            <p><strong>🔒 Security Note:</strong> This debug script exposes server information. Remove it after troubleshooting by deleting <code>debug_server.php</code></p>
        </div>
    </div>
</body>
</html>