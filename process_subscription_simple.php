<?php
session_start(); // Start session for rate limiting

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Response function
function sendResponse($success, $message, $data = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

// Rate limiting to prevent rapid submissions
$current_time = time();
$rate_limit_key = 'last_submission_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');

if (isset($_SESSION[$rate_limit_key])) {
    $time_since_last = $current_time - $_SESSION[$rate_limit_key];
    if ($time_since_last < 5) { // 5 seconds minimum between submissions
        sendResponse(false, 'Please wait a moment before submitting again.');
    }
}

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method');
}

// Get and validate input data
$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');

// Validation
$errors = [];

if (empty($first_name)) {
    $errors[] = 'First name is required';
}

if (empty($last_name)) {
    $errors[] = 'Last name is required';
}

if (empty($email)) {
    $errors[] = 'Email address is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address';
}

if (!empty($errors)) {
    sendResponse(false, implode(', ', $errors));
}

try {
    // Database configuration (from CodeIgniter config)
    $host = 'localhost';
    $dbname = 'lgnewsletter';
    $username = 'rootuser';
    $password = 'rootuser';
    $charset = 'utf8mb4';
    $socket = '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock';
    
    // Create database connection
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset;unix_socket=$socket";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT * FROM newsletter_subscribers WHERE email = ?");
    $stmt->execute([$email]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existing) {
        if ($existing['status'] === 'confirmed') {
            sendResponse(false, "This email address has already subscribed to this newsletter.");
        } elseif ($existing['status'] === 'pending') {
            // Resend confirmation email
            $confirmation_token = $existing['confirmation_token'];
            if (sendConfirmationEmail($email, $first_name, $confirmation_token)) {
                sendResponse(true, "Please check your email to confirm your subscription.");
            } else {
                sendResponse(false, "Error sending confirmation email. Please try again.");
            }
        } elseif ($existing['status'] === 'unsubscribed') {
            // Reactivate subscription
            $confirmation_token = bin2hex(random_bytes(32));
            
            $stmt = $pdo->prepare("UPDATE newsletter_subscribers SET 
                first_name = ?, 
                last_name = ?, 
                status = 'pending', 
                confirmation_token = ?, 
                subscribed_at = NOW(), 
                confirmed_at = NULL, 
                unsubscribed_at = NULL, 
                ip_address = ?, 
                user_agent = ? 
                WHERE id = ?");
            
            $stmt->execute([
                $first_name, 
                $last_name, 
                $confirmation_token, 
                $_SERVER['REMOTE_ADDR'] ?? null, 
                $_SERVER['HTTP_USER_AGENT'] ?? null, 
                $existing['id']
            ]);
            
            if (sendConfirmationEmail($email, $first_name, $confirmation_token)) {
                sendResponse(true, "Welcome back! Please check your email to confirm your subscription.");
            } else {
                sendResponse(false, "Error sending confirmation email. Please try again.");
            }
        }
    } else {
        // New subscription
        $confirmation_token = bin2hex(random_bytes(32));
        
        $stmt = $pdo->prepare("INSERT INTO newsletter_subscribers 
            (first_name, last_name, email, status, confirmation_token, subscribed_at, ip_address, user_agent) 
            VALUES (?, ?, ?, 'pending', ?, NOW(), ?, ?)");
        
        $result = $stmt->execute([
            $first_name, 
            $last_name, 
            $email, 
            $confirmation_token, 
            $_SERVER['REMOTE_ADDR'] ?? null, 
            $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
        
        if ($result) {
            $subscriber_id = $pdo->lastInsertId();
            
            // Update session with successful submission time
            $_SESSION[$rate_limit_key] = $current_time;
            
            if (sendConfirmationEmail($email, $first_name, $confirmation_token)) {
                sendResponse(true, "Thank you for becoming a Lighthouse Pillar! Please check your email to confirm your subscription.");
            } else {
                // Remove the subscriber record if email failed
                $pdo->prepare("DELETE FROM newsletter_subscribers WHERE id = ?")->execute([$subscriber_id]);
                sendResponse(false, "Error sending confirmation email. Please try again.");
            }
        } else {
            sendResponse(false, "Error processing your subscription. Please try again.");
        }
    }
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    sendResponse(false, "Database connection error. Please try again later.");
} catch (Exception $e) {
    error_log("Newsletter subscription error: " . $e->getMessage());
    sendResponse(false, "An error occurred while processing your subscription. Please try again.");
}

// Function to get SMTP settings from database
function getSmtpSettings() {
    try {
        // Database configuration (matching database.php)
        $dsn = 'mysql:host=localhost;dbname=lgnewsletter;unix_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock';
        $username = 'root';
        $password = '';
        
        $pdo = new PDO($dsn, $username, $password, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ));
        
        // Get SMTP settings from database
        $stmt = $pdo->prepare("SELECT setting_key, setting_value FROM newsletter_settings WHERE setting_key IN ('smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption', 'sender_name', 'sender_email')");
        $stmt->execute();
        
        $settings = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        
        return $settings;
    } catch (Exception $e) {
        // Return default settings if database query fails
        return array(
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => '587',
            'smtp_username' => 'your-email@gmail.com',
            'smtp_password' => 'your-password',
            'smtp_encryption' => 'tls',
            'sender_name' => 'Lighthouse Global Missions',
            'sender_email' => 'noreply@lgmissions.org'
        );
    }
}

function sendConfirmationEmail($email, $first_name, $confirmation_token) {
    try {
        // Get SMTP settings from database
        $smtpSettings = getSmtpSettings();
        
        if (!$smtpSettings) {
            error_log("Failed to retrieve SMTP settings from database");
            return false;
        }
        
        // Use PHPMailer for better SMTP support
        require_once(dirname(__FILE__) . '/application/third_party/phpmailer/class.phpmailer.php');
        require_once(dirname(__FILE__) . '/application/third_party/phpmailer/class.smtp.php');
        
        $mail = new PHPMailer();
        
        // Enable debug output for troubleshooting (disable in production)
        // $mail->SMTPDebug = 2;
        // $mail->Debugoutput = 'error_log';
        
        // SMTP configuration from database
        $mail->IsSMTP();
        $mail->Host = $smtpSettings['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $smtpSettings['smtp_username'];
        $mail->Password = $smtpSettings['smtp_password'];
        $mail->SMTPSecure = $smtpSettings['smtp_encryption'];
        $mail->Port = (int)$smtpSettings['smtp_port'];
        
        // Email settings
        $mail->SetFrom($smtpSettings['sender_email'], $smtpSettings['sender_name']);
        $mail->AddAddress($email, $first_name);
        $mail->IsHTML(true);
        $mail->Subject = 'Confirm your subscription to Lighthouse Global Missions Newsletter';
        
        $confirmation_url = "http://localhost:8080/confirm_subscription.php?token=" . $confirmation_token;
        
        $message = "
        <html>
        <head>
            <title>Confirm Your Subscription</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #1a365d 0%, #3182ce 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px; }
                .button { display: inline-block; background: #3182ce; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🏮 Welcome to Lighthouse Global Missions</h1>
                    <p>Thank you for becoming a Lighthouse Pillar!</p>
                </div>
                <div class='content'>
                    <p>Dear " . htmlspecialchars($first_name) . ",</p>
                    <p>Thank you for subscribing to our newsletter! You're now part of our community of Lighthouse Pillars who stand with us in prayer, giving, and volunteering for this ministry.</p>
                    <p>To complete your subscription, please click the button below to confirm your email address:</p>
                    <p style='text-align: center;'>
                        <a href='" . $confirmation_url . "' class='button'>Confirm My Subscription</a>
                    </p>
                    <p>If the button doesn't work, you can copy and paste this link into your browser:</p>
                    <p><a href='" . $confirmation_url . "'>" . $confirmation_url . "</a></p>
                    <p>Once confirmed, you'll receive:</p>
                    <ul>
                        <li>Ministry updates from our global missions</li>
                        <li>Prophetic words and spiritual insights</li>
                        <li>Opportunities to support Kingdom missions</li>
                        <li>Invitations to exclusive partner events</li>
                    </ul>
                    <p>Blessings,<br>Pastor Simon & The Lighthouse Global Missions Team</p>
                </div>
                <div class='footer'>
                    <p>&copy; 2024 Lighthouse Global Missions. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        $mail->Body = $message;
        
        $result = $mail->Send();
        
        if (!$result) {
            error_log("Email sending failed: " . $mail->ErrorInfo);
            error_log("SMTP Host: " . $smtpSettings['smtp_host']);
            error_log("SMTP Port: " . $smtpSettings['smtp_port']);
            error_log("SMTP Username: " . $smtpSettings['smtp_username']);
            error_log("SMTP Encryption: " . $smtpSettings['smtp_encryption']);
        } else {
            error_log("Email sent successfully to: " . $email);
        }
        
        return $result;
        
    } catch (Exception $e) {
        error_log("Email sending error: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        return false;
    }
}
?>