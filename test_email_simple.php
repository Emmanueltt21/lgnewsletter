<?php
// Simple test email script with basic Gmail SMTP

// Include PHPMailer
require_once '/Applications/XAMPP/xamppfiles/htdocs/lgnewsletter/application/third_party/phpmailer/class.phpmailer.php';
require_once '/Applications/XAMPP/xamppfiles/htdocs/lgnewsletter/application/third_party/phpmailer/class.smtp.php';

try {
    echo "Testing basic email functionality...\n";
    
    $mail = new PHPMailer();
    
    // Enable verbose debug output
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = 'echo';
    
    // Server settings - using Gmail for testing
    $mail->IsSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'test@lgmissions.org';  // This needs to be a valid Gmail account
    $mail->Password   = 'your_app_password';    // This needs to be an app password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;
    
    // Recipients
    $mail->SetFrom('test@lgmissions.org', 'Test Sender');
    $mail->AddAddress('test@example.com', 'Test Recipient');
    
    // Content
    $mail->IsHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body    = '<h1>Test Email</h1><p>This is a test email.</p>';
    $mail->AltBody = 'This is a test email.';
    
    echo "Attempting to send email...\n";
    $result = $mail->Send();
    
    if ($result) {
        echo "Email sent successfully!\n";
    } else {
        echo "Email sending failed: " . $mail->ErrorInfo . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n--- Testing with current database settings ---\n";

// Function to get SMTP settings from database
function getSmtpSettings() {
    try {
        $dsn = 'mysql:host=localhost;dbname=lgnewsletter;unix_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock';
        $username = 'root';
        $password = '';
        
        $pdo = new PDO($dsn, $username, $password, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ));
        
        $stmt = $pdo->prepare("SELECT setting_key, setting_value FROM newsletter_settings WHERE setting_key IN ('smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption', 'sender_name', 'sender_email')");
        $stmt->execute();
        
        $settings = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        
        return $settings;
    } catch (Exception $e) {
        echo "Database error: " . $e->getMessage() . "\n";
        return false;
    }
}

// Test with database settings but without authentication
try {
    $smtpSettings = getSmtpSettings();
    if ($smtpSettings) {
        echo "Database SMTP Settings:\n";
        foreach ($smtpSettings as $key => $value) {
            if ($key === 'smtp_password') {
                echo "$key: [HIDDEN]\n";
            } else {
                echo "$key: $value\n";
            }
        }
        
        echo "\nNote: The current credentials appear to be invalid for authentication.\n";
        echo "The SMTP server connection works, but authentication fails.\n";
        echo "You may need to:\n";
        echo "1. Use valid email credentials\n";
        echo "2. Enable 'Less secure app access' if using Gmail\n";
        echo "3. Use an App Password if using Gmail with 2FA\n";
        echo "4. Check with your email provider for correct SMTP settings\n";
    }
} catch (Exception $e) {
    echo "Error testing database settings: " . $e->getMessage() . "\n";
}
?>