<?php
/**
 * Database Connection Test Script
 * 
 * This script tests the database connection using the server configuration.
 * It will attempt to connect to the database and display the connection status.
 */

// Prevent direct access in production
if (!defined('DB_TEST_MODE')) {
    define('DB_TEST_MODE', true);
}

// Server database configuration
$db_config = array(
    'dsn' => 'mysql:host=localhost;dbname=u889622533_lgnewletter',
    'hostname' => 'localhost',
    'username' => 'u889622533_lgnewletter',
    'password' => 'lgnewsLetter@300010',
    'database' => 'u889622533_lgnewletter',
    'dbdriver' => 'pdo',
    'char_set' => 'utf8'
);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success {
            color: #28a745;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .error {
            color: #dc3545;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .info {
            color: #0c5460;
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .config-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .config-table th, .config-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .config-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .test-section {
            margin: 30px 0;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        h1, h2 {
            color: #333;
        }
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }
        .status-success { background-color: #28a745; }
        .status-error { background-color: #dc3545; }
        .status-warning { background-color: #ffc107; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Database Connection Test</h1>
        <p>This script tests the database connection using the server configuration.</p>
        
        <div class="test-section">
            <h2>Configuration Details</h2>
            <table class="config-table">
                <tr>
                    <th>Parameter</th>
                    <th>Value</th>
                </tr>
                <tr>
                    <td>Hostname</td>
                    <td><?php echo htmlspecialchars($db_config['hostname']); ?></td>
                </tr>
                <tr>
                    <td>Database</td>
                    <td><?php echo htmlspecialchars($db_config['database']); ?></td>
                </tr>
                <tr>
                    <td>Username</td>
                    <td><?php echo htmlspecialchars($db_config['username']); ?></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><?php echo str_repeat('*', strlen($db_config['password'])); ?></td>
                </tr>
                <tr>
                    <td>Driver</td>
                    <td><?php echo htmlspecialchars($db_config['dbdriver']); ?></td>
                </tr>
                <tr>
                    <td>Character Set</td>
                    <td><?php echo htmlspecialchars($db_config['char_set']); ?></td>
                </tr>
            </table>
        </div>

        <div class="test-section">
            <h2>Connection Tests</h2>
            
            <?php
            // Test 1: PDO Extension Check
            echo "<h3><span class='status-indicator " . (extension_loaded('pdo') ? 'status-success' : 'status-error') . "'></span>PDO Extension</h3>";
            if (extension_loaded('pdo')) {
                echo "<div class='success'>✓ PDO extension is loaded and available.</div>";
            } else {
                echo "<div class='error'>✗ PDO extension is not loaded. Please install php-pdo.</div>";
            }

            // Test 2: PDO MySQL Driver Check
            echo "<h3><span class='status-indicator " . (extension_loaded('pdo_mysql') ? 'status-success' : 'status-error') . "'></span>PDO MySQL Driver</h3>";
            if (extension_loaded('pdo_mysql')) {
                echo "<div class='success'>✓ PDO MySQL driver is loaded and available.</div>";
            } else {
                echo "<div class='error'>✗ PDO MySQL driver is not loaded. Please install php-pdo-mysql.</div>";
            }

            // Test 3: Database Connection
            echo "<h3><span class='status-indicator status-warning'></span>Database Connection</h3>";
            
            try {
                $pdo = new PDO(
                    $db_config['dsn'],
                    $db_config['username'],
                    $db_config['password'],
                    array(
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . $db_config['char_set']
                    )
                );
                
                echo "<div class='success'>✓ Successfully connected to the database!</div>";
                
                // Test 4: Database Information
                echo "<h3><span class='status-indicator status-success'></span>Database Information</h3>";
                
                // Get MySQL version
                $version = $pdo->query('SELECT VERSION()')->fetchColumn();
                echo "<div class='info'><strong>MySQL Version:</strong> " . htmlspecialchars($version) . "</div>";
                
                // Get current database
                $current_db = $pdo->query('SELECT DATABASE()')->fetchColumn();
                echo "<div class='info'><strong>Current Database:</strong> " . htmlspecialchars($current_db) . "</div>";
                
                // Test 5: Table Listing
                echo "<h3><span class='status-indicator status-success'></span>Database Tables</h3>";
                
                $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
                if (!empty($tables)) {
                    echo "<div class='success'>✓ Found " . count($tables) . " tables in the database:</div>";
                    echo "<div class='info'>";
                    echo "<ul>";
                    foreach ($tables as $table) {
                        echo "<li>" . htmlspecialchars($table) . "</li>";
                    }
                    echo "</ul>";
                    echo "</div>";
                } else {
                    echo "<div class='error'>✗ No tables found in the database.</div>";
                }
                
                // Test 6: Sample Query Test
                echo "<h3><span class='status-indicator status-success'></span>Sample Query Test</h3>";
                
                // Try to query a common table (users table if it exists)
                if (in_array('users', $tables)) {
                    try {
                        $user_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
                        echo "<div class='success'>✓ Successfully queried 'users' table. Found {$user_count} records.</div>";
                    } catch (Exception $e) {
                        echo "<div class='error'>✗ Error querying 'users' table: " . htmlspecialchars($e->getMessage()) . "</div>";
                    }
                } else {
                    echo "<div class='info'>ℹ 'users' table not found. Skipping sample query test.</div>";
                }
                
                // Test 7: Connection Status
                echo "<h3><span class='status-indicator status-success'></span>Connection Status</h3>";
                echo "<div class='success'>✓ Database connection is working properly!</div>";
                echo "<div class='info'><strong>Connection ID:</strong> " . $pdo->query('SELECT CONNECTION_ID()')->fetchColumn() . "</div>";
                
            } catch (PDOException $e) {
                echo "<div class='error'>✗ Database connection failed!</div>";
                echo "<div class='error'><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</div>";
                echo "<div class='error'><strong>Error Code:</strong> " . $e->getCode() . "</div>";
                
                // Common error solutions
                echo "<h3>Possible Solutions:</h3>";
                echo "<div class='info'>";
                echo "<ul>";
                echo "<li>Check if the database server is running</li>";
                echo "<li>Verify the hostname, username, and password</li>";
                echo "<li>Ensure the database name is correct</li>";
                echo "<li>Check if the user has proper permissions</li>";
                echo "<li>Verify firewall settings allow database connections</li>";
                echo "</ul>";
                echo "</div>";
            } catch (Exception $e) {
                echo "<div class='error'>✗ Unexpected error occurred!</div>";
                echo "<div class='error'><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</div>";
            }
            ?>
        </div>

        <div class="test-section">
            <h2>System Information</h2>
            <table class="config-table">
                <tr>
                    <td><strong>PHP Version</strong></td>
                    <td><?php echo PHP_VERSION; ?></td>
                </tr>
                <tr>
                    <td><strong>Server Software</strong></td>
                    <td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></td>
                </tr>
                <tr>
                    <td><strong>Operating System</strong></td>
                    <td><?php echo PHP_OS; ?></td>
                </tr>
                <tr>
                    <td><strong>Current Time</strong></td>
                    <td><?php echo date('Y-m-d H:i:s'); ?></td>
                </tr>
            </table>
        </div>

        <div class="info">
            <strong>Note:</strong> This test file should be removed from the server after testing for security reasons.
        </div>
    </div>
</body>
</html>