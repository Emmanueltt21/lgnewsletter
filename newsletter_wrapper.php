<?php
/**
 * Newsletter API Wrapper
 * This class provides the same functionality as Newsletter_api controller
 * but can be used independently without CodeIgniter framework
 */
class NewsletterWrapper {
    
    private $pdo;
    
    public function __construct() {
        $this->initDatabase();
    }
    
    private function initDatabase() {
        try {
            // Database configuration
            $host = 'localhost';
            $dbname = 'lgnewsletter';
            $username = 'root';
            $password = '';
            
            // Use Unix socket for XAMPP on macOS
            $dsn = "mysql:unix_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock;dbname={$dbname};charset=utf8mb4";
            
            $this->pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            // Log the specific database error for debugging
            file_put_contents('debug.log', "Database connection error in NewsletterWrapper: " . $e->getMessage() . "\n", FILE_APPEND);
            throw new Exception('Database connection failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Subscribe a user to the newsletter
     */
    public function subscribe($first_name, $last_name, $email) {
        try {
            // Validate input
            if (empty($first_name) || empty($last_name) || empty($email)) {
                return [
                    'success' => false,
                    'message' => 'All fields are required',
                    'data' => null
                ];
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return [
                    'success' => false,
                    'message' => 'Invalid email address',
                    'data' => null
                ];
            }
            
            // Check if email already exists
            $stmt = $this->pdo->prepare("SELECT id, status FROM newsletter_subscribers WHERE email = ?");
            $stmt->execute([$email]);
            $existing = $stmt->fetch();
            
            if ($existing) {
                if ($existing['status'] === 'confirmed') {
                    return [
                        'success' => true,
                        'message' => 'You are already subscribed to our newsletter',
                        'data' => null
                    ];
                } else {
                    // Update existing unconfirmed subscriber
                    $confirmation_token = bin2hex(random_bytes(32));
                    $stmt = $this->pdo->prepare("
                        UPDATE newsletter_subscribers 
                        SET first_name = ?, last_name = ?, confirmation_token = ?, subscribed_at = NOW() 
                        WHERE id = ?
                    ");
                    $stmt->execute([$first_name, $last_name, $confirmation_token, $existing['id']]);
                    
                    return [
                        'success' => true,
                        'message' => 'Confirmation email has been resent to your email address',
                        'data' => null
                    ];
                }
            } else {
                // Create new subscriber
                $confirmation_token = bin2hex(random_bytes(32));
                $stmt = $this->pdo->prepare("
                    INSERT INTO newsletter_subscribers (first_name, last_name, email, confirmation_token, subscribed_at) 
                    VALUES (?, ?, ?, ?, NOW())
                ");
                $stmt->execute([$first_name, $last_name, $email, $confirmation_token]);
                
                $subscriber_id = $this->pdo->lastInsertId();
                
                return [
                    'success' => true,
                    'message' => 'Thank you for subscribing! Please check your email to confirm your subscription',
                    'data' => ['subscriber_id' => (string)$subscriber_id]
                ];
            }
            
        } catch (PDOException $e) {
            // Log the specific database error for debugging
            file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] Database error in subscribe: " . $e->getMessage() . "\n", FILE_APPEND);
            return [
                'success' => false,
                'message' => 'Database error occurred. Please try again later.',
                'data' => null
            ];
        } catch (Exception $e) {
            // Log the general error for debugging
            file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] General error in subscribe: " . $e->getMessage() . "\n", FILE_APPEND);
            return [
                'success' => false,
                'message' => 'An error occurred. Please try again later.',
                'data' => null
            ];
        }
    }
    
    /**
     * Confirm a subscription using the confirmation token
     */
    public function confirmSubscription($token) {
        try {
            if (empty($token)) {
                return [
                    'success' => false,
                    'message' => 'Invalid confirmation link',
                    'data' => null
                ];
            }
            
            // Find subscriber with this token
            $stmt = $this->pdo->prepare("SELECT * FROM newsletter_subscribers WHERE confirmation_token = ? AND status = 'pending'");
            $stmt->execute([$token]);
            $subscriber = $stmt->fetch();
            
            if (!$subscriber) {
                return [
                    'success' => false,
                    'message' => 'Invalid or expired confirmation link',
                    'data' => null
                ];
            }
            
            // Update subscriber status to confirmed
            $update_stmt = $this->pdo->prepare("UPDATE newsletter_subscribers SET status = 'confirmed', confirmed_at = NOW(), confirmation_token = NULL WHERE id = ?");
            $update_stmt->execute([$subscriber['id']]);
            
            return [
                'success' => true,
                'message' => 'Subscription confirmed successfully',
                'data' => [
                    'subscriber_id' => (string)$subscriber['id'],
                    'first_name' => $subscriber['first_name'],
                    'last_name' => $subscriber['last_name'],
                    'email' => $subscriber['email']
                ]
            ];
            
        } catch (Exception $e) {
            file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] Database error in confirmSubscription: " . $e->getMessage() . "\n", FILE_APPEND);
            return [
                'success' => false,
                'message' => 'Database error occurred. Please try again later.',
                'data' => null
            ];
        } catch (Error $e) {
            file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] General error in confirmSubscription: " . $e->getMessage() . "\n", FILE_APPEND);
            return [
                'success' => false,
                'message' => 'An error occurred. Please try again later.',
                'data' => null
            ];
        }
    }
    
    /**
     * Get email settings from database
     */
    public function getEmailSettings() {
        try {
            $stmt = $this->pdo->query("SELECT setting_key, setting_value FROM newsletter_settings");
            $settings_raw = $stmt->fetchAll();
            
            $settings = [];
            foreach ($settings_raw as $setting) {
                $settings[$setting['setting_key']] = $setting['setting_value'];
            }
            
            // Return default settings if none found
            if (empty($settings)) {
                return [
                    'welcome_subject' => 'Welcome to Lighthouse Global Missions!',
                    'sender_name' => 'Lighthouse Global Missions',
                    'sender_email' => 'info@lgmissions.org',
                    'email_signature' => 'Blessings, Pastor Simon Mungwa'
                ];
            }
            
            return $settings;
            
        } catch (Exception $e) {
            file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] Database error in getEmailSettings: " . $e->getMessage() . "\n", FILE_APPEND);
            // Return default settings on error
            return [
                'welcome_subject' => 'Welcome to Lighthouse Global Missions!',
                'sender_name' => 'Lighthouse Global Missions',
                'sender_email' => 'info@lgmissions.org',
                'email_signature' => 'Blessings, Pastor Simon Mungwa'
            ];
        }
    }
    
    /**
     * Log email history
     */
    public function logEmailHistory($subscriber_id, $recipient_email, $recipient_name, $subject, $email_type, $newsletter_id = null) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO email_history (recipient_email, recipient_name, subject, email_type, newsletter_id, subscriber_id, status, sent_at, created_at) VALUES (?, ?, ?, ?, ?, ?, 'sent', NOW(), NOW())");
            $stmt->execute([$recipient_email, $recipient_name, $subject, $email_type, $newsletter_id, $subscriber_id]);
            return true;
        } catch (Exception $e) {
            file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] Database error in logEmailHistory: " . $e->getMessage() . "\n", FILE_APPEND);
            return false;
        }
    }
}
?>