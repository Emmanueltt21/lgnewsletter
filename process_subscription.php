<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Include CodeIgniter bootstrap
require_once 'index.php';

// Get CodeIgniter instance
$CI =& get_instance();

// Load necessary libraries and models
$CI->load->database();
$CI->load->library('email');
$CI->load->helper('url');

// Response function
function sendResponse($success, $message, $data = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
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
    // Check if email already exists
    $existing = $CI->db->get_where('newsletter_subscribers', ['email' => $email])->row();
    
    if ($existing) {
        if ($existing->status === 'confirmed') {
            sendResponse(false, "You're already subscribed to our newsletter.");
        } elseif ($existing->status === 'pending') {
            // Resend confirmation email
            $confirmation_token = $existing->confirmation_token;
            if (sendConfirmationEmail($email, $first_name, $confirmation_token)) {
                sendResponse(true, "Please check your email to confirm your subscription.");
            } else {
                sendResponse(false, "Error sending confirmation email. Please try again.");
            }
        } elseif ($existing->status === 'unsubscribed') {
            // Reactivate subscription
            $confirmation_token = bin2hex(random_bytes(32));
            
            $update_data = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'status' => 'pending',
                'confirmation_token' => $confirmation_token,
                'subscribed_at' => date('Y-m-d H:i:s'),
                'confirmed_at' => null,
                'unsubscribed_at' => null,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
            ];
            
            $CI->db->where('id', $existing->id);
            $CI->db->update('newsletter_subscribers', $update_data);
            
            if (sendConfirmationEmail($email, $first_name, $confirmation_token)) {
                sendResponse(true, "Welcome back! Please check your email to confirm your subscription.");
            } else {
                sendResponse(false, "Error sending confirmation email. Please try again.");
            }
        }
    } else {
        // New subscription
        $confirmation_token = bin2hex(random_bytes(32));
        
        $subscriber_data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'status' => 'pending',
            'confirmation_token' => $confirmation_token,
            'subscribed_at' => date('Y-m-d H:i:s'),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ];
        
        $CI->db->insert('newsletter_subscribers', $subscriber_data);
        $subscriber_id = $CI->db->insert_id();
        
        if ($subscriber_id && sendConfirmationEmail($email, $first_name, $confirmation_token)) {
            // Log email history
            logEmailHistory($subscriber_id, $email, $first_name . ' ' . $last_name, 'Confirm your subscription to Lighthouse Global Missions Newsletter', 'confirmation');
            
            sendResponse(true, "Thank you for subscribing! Please check your email to confirm your subscription.");
        } else {
            // Remove the subscriber record if email failed
            if ($subscriber_id) {
                $CI->db->delete('newsletter_subscribers', ['id' => $subscriber_id]);
            }
            sendResponse(false, "Error processing your subscription. Please try again.");
        }
    }
    
} catch (Exception $e) {
    error_log("Newsletter subscription error: " . $e->getMessage());
    sendResponse(false, "An error occurred while processing your subscription. Please try again.");
}

function sendConfirmationEmail($email, $first_name, $confirmation_token) {
    global $CI;
    
    try {
        // Get email settings
        $settings = getEmailSettings();
        
        // Configure email
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => $settings['smtp_host'],
            'smtp_port' => $settings['smtp_port'],
            'smtp_user' => $settings['smtp_username'],
            'smtp_pass' => $settings['smtp_password'],
            'smtp_crypto' => $settings['smtp_encryption'],
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n"
        ];
        
        $CI->email->initialize($config);
        
        // Email content
        $confirmation_url = base_url() . "confirm_subscription.php?token=" . $confirmation_token;
        
        $subject = $settings['confirmation_subject'];
        $message = getConfirmationEmailTemplate($first_name, $confirmation_url, $settings['email_signature']);
        
        $CI->email->from($settings['sender_email'], $settings['sender_name']);
        $CI->email->to($email);
        $CI->email->subject($subject);
        $CI->email->message($message);
        
        return $CI->email->send();
        
    } catch (Exception $e) {
        error_log("Email sending error: " . $e->getMessage());
        return false;
    }
}

function sendWelcomeEmail($email, $first_name) {
    global $CI;
    
    try {
        // Get email settings
        $settings = getEmailSettings();
        
        // Configure email
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => $settings['smtp_host'],
            'smtp_port' => $settings['smtp_port'],
            'smtp_user' => $settings['smtp_username'],
            'smtp_pass' => $settings['smtp_password'],
            'smtp_crypto' => $settings['smtp_encryption'],
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n"
        ];
        
        $CI->email->initialize($config);
        
        // Email content
        $subject = $settings['welcome_subject'];
        $message = getWelcomeEmailTemplate($first_name, $settings['email_signature']);
        
        $CI->email->from($settings['sender_email'], $settings['sender_name']);
        $CI->email->to($email);
        $CI->email->subject($subject);
        $CI->email->message($message);
        
        return $CI->email->send();
        
    } catch (Exception $e) {
        error_log("Welcome email error: " . $e->getMessage());
        return false;
    }
}

function getEmailSettings() {
    global $CI;
    
    $settings_query = $CI->db->get('newsletter_settings');
    $settings_raw = $settings_query->result_array();
    
    $settings = [];
    foreach ($settings_raw as $setting) {
        $settings[$setting['setting_key']] = $setting['setting_value'];
    }
    
    return $settings;
}

function logEmailHistory($subscriber_id, $recipient_email, $recipient_name, $subject, $email_type, $newsletter_id = null) {
    global $CI;
    
    $history_data = [
        'recipient_email' => $recipient_email,
        'recipient_name' => $recipient_name,
        'subject' => $subject,
        'email_type' => $email_type,
        'newsletter_id' => $newsletter_id,
        'subscriber_id' => $subscriber_id,
        'status' => 'sent',
        'sent_at' => date('Y-m-d H:i:s'),
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $CI->db->insert('email_history', $history_data);
}

function getConfirmationEmailTemplate($first_name, $confirmation_url, $signature) {
    return "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Confirm Your Subscription</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #1f2937 0%, #374151 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #f8fafc; padding: 30px; border-radius: 0 0 10px 10px; }
            .button { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; margin: 20px 0; }
            .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>🏮 Lighthouse Global Missions</h1>
                <p>Confirm Your Subscription</p>
            </div>
            <div class='content'>
                <h2>Hello " . htmlspecialchars($first_name) . ",</h2>
                <p>Thank you for subscribing to our newsletter! To complete your subscription and start receiving our ministry updates, prophetic messages, and event invitations, please confirm your email address by clicking the button below:</p>
                
                <div style='text-align: center;'>
                    <a href='" . $confirmation_url . "' class='button'>Confirm Subscription</a>
                </div>
                
                <p>If the button doesn't work, you can copy and paste this link into your browser:</p>
                <p style='word-break: break-all; color: #667eea;'>" . $confirmation_url . "</p>
                
                <p>If you didn't subscribe to our newsletter, you can safely ignore this email.</p>
                
                <div class='footer'>
                    <p>" . nl2br(htmlspecialchars($signature)) . "</p>
                </div>
            </div>
        </div>
    </body>
    </html>";
}

function getWelcomeEmailTemplate($first_name, $signature) {
    return "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Welcome to Lighthouse Global Missions</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #1f2937 0%, #374151 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #f8fafc; padding: 30px; border-radius: 0 0 10px 10px; }
            .highlight { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center; }
            .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>🏮 Welcome to Lighthouse Global Missions!</h1>
                <p>You're now part of our Lighthouse Pillars community</p>
            </div>
            <div class='content'>
                <h2>Hello " . htmlspecialchars($first_name) . ",</h2>
                <p>Thank you for subscribing to our newsletter! Your subscription has been confirmed successfully.</p>
                
                <div class='highlight'>
                    <h3>🎉 You are now part of our Lighthouse Pillars community!</h3>
                </div>
                
                <p>As a subscriber, you will receive:</p>
                <ul>
                    <li>📧 Ministry updates and news</li>
                    <li>🙏 Prophetic words and spiritual insights</li>
                    <li>📅 Invitations to our events and programs</li>
                    <li>💝 Special announcements and opportunities to get involved</li>
                </ul>
                
                <p>Stay tuned for updates, prophetic words, and invitations to our events. We're excited to have you join us in God's work around the world!</p>
                
                <div class='footer'>
                    <p>" . nl2br(htmlspecialchars($signature)) . "</p>
                </div>
            </div>
        </div>
    </body>
    </html>";
}
?>