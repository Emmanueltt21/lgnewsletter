<?php
// Include CodeIgniter bootstrap
require_once 'index.php';

// Get CodeIgniter instance
$CI =& get_instance();

// Load necessary libraries and models
$CI->load->database();
$CI->load->library('email');
$CI->load->helper('url');

// Get confirmation token from URL
$token = $_GET['token'] ?? '';

if (empty($token)) {
    showErrorPage('Invalid confirmation link');
    exit;
}

try {
    // Find subscriber with this token
    $subscriber = $CI->db->get_where('newsletter_subscribers', [
        'confirmation_token' => $token,
        'status' => 'pending'
    ])->row();
    
    if (!$subscriber) {
        showErrorPage('Invalid or expired confirmation link');
        exit;
    }
    
    // Update subscriber status to confirmed
    $update_data = [
        'status' => 'confirmed',
        'confirmed_at' => date('Y-m-d H:i:s'),
        'confirmation_token' => null
    ];
    
    $CI->db->where('id', $subscriber->id);
    $CI->db->update('newsletter_subscribers', $update_data);
    
    // Send welcome email
    if (sendWelcomeEmail($subscriber->email, $subscriber->first_name)) {
        // Log welcome email
        logEmailHistory($subscriber->id, $subscriber->email, $subscriber->first_name . ' ' . $subscriber->last_name, 'Welcome to Lighthouse Global Missions!', 'welcome');
    }
    
    // Show success page
    showSuccessPage($subscriber->first_name);
    
} catch (Exception $e) {
    error_log("Confirmation error: " . $e->getMessage());
    showErrorPage('An error occurred while confirming your subscription');
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

function showSuccessPage($first_name) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Subscription Confirmed - Lighthouse Global Missions</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Inter', sans-serif;
                line-height: 1.6;
                color: #333;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .container {
                max-width: 600px;
                margin: 2rem;
                background: white;
                border-radius: 20px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                text-align: center;
            }

            .header {
                background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
                color: white;
                padding: 3rem 2rem;
            }

            .success-icon {
                font-size: 4rem;
                margin-bottom: 1rem;
                animation: bounce 2s infinite;
            }

            @keyframes bounce {
                0%, 20%, 50%, 80%, 100% {
                    transform: translateY(0);
                }
                40% {
                    transform: translateY(-10px);
                }
                60% {
                    transform: translateY(-5px);
                }
            }

            .header h1 {
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
            }

            .header p {
                font-size: 1.2rem;
                opacity: 0.9;
            }

            .content {
                padding: 3rem 2rem;
            }

            .content h2 {
                font-size: 1.8rem;
                color: #1f2937;
                margin-bottom: 1rem;
            }

            .content p {
                font-size: 1.1rem;
                color: #6b7280;
                margin-bottom: 2rem;
                line-height: 1.7;
            }

            .benefits {
                background: #f8fafc;
                border-radius: 12px;
                padding: 2rem;
                margin: 2rem 0;
                text-align: left;
            }

            .benefits h3 {
                color: #1f2937;
                margin-bottom: 1rem;
                text-align: center;
            }

            .benefits ul {
                list-style: none;
                padding: 0;
            }

            .benefits li {
                padding: 0.5rem 0;
                color: #374151;
                display: flex;
                align-items: center;
            }

            .benefits li i {
                color: #667eea;
                margin-right: 1rem;
                width: 20px;
            }

            .cta-button {
                display: inline-block;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 1rem 2rem;
                text-decoration: none;
                border-radius: 12px;
                font-weight: 600;
                margin-top: 1rem;
                transition: transform 0.3s ease;
            }

            .cta-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            }

            .footer {
                background: #f8fafc;
                padding: 2rem;
                color: #6b7280;
                font-size: 0.9rem;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1>Subscription Confirmed!</h1>
                <p>Welcome to our community</p>
            </div>

            <div class="content">
                <h2>Thank you, <?php echo htmlspecialchars($first_name); ?>!</h2>
                <p>Your subscription to Lighthouse Global Missions newsletter has been successfully confirmed. You are now part of our Lighthouse Pillars community!</p>

                <div class="benefits">
                    <h3>What to expect:</h3>
                    <ul>
                        <li><i class="fas fa-envelope"></i> Ministry updates and news</li>
                        <li><i class="fas fa-pray"></i> Prophetic words and spiritual insights</li>
                        <li><i class="fas fa-calendar"></i> Invitations to events and programs</li>
                        <li><i class="fas fa-heart"></i> Special announcements and opportunities</li>
                    </ul>
                </div>

                <p>A welcome email has been sent to your inbox with more details about our ministry and community.</p>

                <a href="https://www.lgmissions.org" class="cta-button">
                    <i class="fas fa-home"></i> Visit Our Website
                </a>
            </div>

            <div class="footer">
                <p>&copy; 2024 Lighthouse Global Missions. All rights reserved.</p>
                <p>Blessings, Pastor Simon Mungwa</p>
            </div>
        </div>
    </body>
    </html>
    <?php
}

function showErrorPage($message) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Confirmation Error - Lighthouse Global Missions</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Inter', sans-serif;
                line-height: 1.6;
                color: #333;
                background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .container {
                max-width: 600px;
                margin: 2rem;
                background: white;
                border-radius: 20px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                text-align: center;
            }

            .header {
                background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
                color: white;
                padding: 3rem 2rem;
            }

            .error-icon {
                font-size: 4rem;
                margin-bottom: 1rem;
            }

            .header h1 {
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
            }

            .content {
                padding: 3rem 2rem;
            }

            .content p {
                font-size: 1.1rem;
                color: #6b7280;
                margin-bottom: 2rem;
                line-height: 1.7;
            }

            .cta-button {
                display: inline-block;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 1rem 2rem;
                text-decoration: none;
                border-radius: 12px;
                font-weight: 600;
                margin: 0.5rem;
                transition: transform 0.3s ease;
            }

            .cta-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            }

            .footer {
                background: #f8fafc;
                padding: 2rem;
                color: #6b7280;
                font-size: 0.9rem;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <div class="error-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h1>Confirmation Error</h1>
            </div>

            <div class="content">
                <p><?php echo htmlspecialchars($message); ?></p>
                <p>If you believe this is an error, please try subscribing again or contact our support team.</p>

                <a href="newsletter-subscription.php" class="cta-button">
                    <i class="fas fa-redo"></i> Try Again
                </a>
                <a href="https://www.lgmissions.org" class="cta-button">
                    <i class="fas fa-home"></i> Visit Website
                </a>
            </div>

            <div class="footer">
                <p>&copy; 2024 Lighthouse Global Missions. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?>