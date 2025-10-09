<?php
// Test email sending with database SMTP settings

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
        echo "Database error: " . $e->getMessage() . "\n";
        return false;
    }
}

// Test the email sending
try {
    echo "Testing email functionality...\n";
    
    // Get SMTP settings
    $smtpSettings = getSmtpSettings();
    if (!$smtpSettings) {
        die("Failed to get SMTP settings\n");
    }
    
    echo "SMTP Settings retrieved:\n";
    print_r($smtpSettings);
    
    // Check if PHPMailer exists
    $phpmailerPath = '/Applications/XAMPP/xamppfiles/htdocs/lgnewsletter/application/third_party/phpmailer/class.phpmailer.php';
    if (!file_exists($phpmailerPath)) {
        die("PHPMailer not found at: $phpmailerPath\n");
    }
    
    // Include PHPMailer
    require_once '/Applications/XAMPP/xamppfiles/htdocs/lgnewsletter/application/third_party/phpmailer/class.phpmailer.php';
    require_once '/Applications/XAMPP/xamppfiles/htdocs/lgnewsletter/application/third_party/phpmailer/class.smtp.php';
    
    echo "PHPMailer loaded successfully\n";
    
    $mail = new PHPMailer();
    
    // Enable verbose debug output
    $mail->SMTPDebug = 2; // SMTP::DEBUG_SERVER equivalent
    $mail->Debugoutput = 'echo';
    
    // Server settings using database values
    $mail->IsSMTP();
    $mail->Host       = $smtpSettings['smtp_host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $smtpSettings['smtp_username'];
    $mail->Password   = $smtpSettings['smtp_password'];
    $mail->SMTPSecure = $smtpSettings['smtp_encryption'];
    $mail->Port = $smtpSettings['smtp_port'];
    
    // Recipients
    $mail->SetFrom($smtpSettings['sender_email'], $smtpSettings['sender_name']);
    $mail->AddAddress('test@example.com', 'Test User');
    
    // Content
    $mail->IsHTML(true);
    $mail->Subject = 'Test Email from Database SMTP Settings';
    $mail->Body    = '<h1>Test Email</h1><p>This is a test email sent using SMTP settings from the database.</p>';
    $mail->AltBody = 'This is a test email sent using SMTP settings from the database.';
    
    echo "Attempting to send email...\n";
    $result = $mail->send();
    
    if ($result) {
        echo "Email sent successfully!\n";
    } else {
        echo "Email sending failed: " . $mail->ErrorInfo . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>