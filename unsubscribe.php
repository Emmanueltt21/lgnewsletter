<?php
// Unsubscribe page for newsletter
require_once 'system/core/Common.php';
require_once BASEPATH . 'core/CodeIgniter.php';

// Get email from URL parameter
$email = isset($_GET['email']) ? trim($_GET['email']) : '';
$token = isset($_GET['token']) ? trim($_GET['token']) : '';
$success = false;
$error_message = '';

// Database connection
$config = array(
    'dsn' => 'mysql:host=localhost;dbname=u889622533_yourddlight;unix_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock',
    'username' => 'rootuser',
    'password' => 'rootuser'
);

try {
    $pdo = new PDO($config['dsn'], $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email']);
        $reason = trim($_POST['reason'] ?? '');
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = 'Please enter a valid email address.';
        } else {
            // Check if subscriber exists
            $stmt = $pdo->prepare("SELECT id, status FROM newsletter_subscribers WHERE email = ?");
            $stmt->execute([$email]);
            $subscriber = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($subscriber) {
                if ($subscriber['status'] === 'unsubscribed') {
                    $error_message = 'This email is already unsubscribed from our newsletter.';
                } else {
                    // Update subscriber status
                    $stmt = $pdo->prepare("UPDATE newsletter_subscribers SET status = 'unsubscribed', unsubscribed_at = NOW() WHERE email = ?");
                    $stmt->execute([$email]);
                    
                    // Log unsubscribe reason
                    if (!empty($reason)) {
                        $stmt = $pdo->prepare("INSERT INTO newsletter_unsubscribes (subscriber_id, email, reason, ip_address) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$subscriber['id'], $email, $reason, $_SERVER['REMOTE_ADDR'] ?? '']);
                    }
                    
                    $success = true;
                }
            } else {
                $error_message = 'Email address not found in our newsletter list.';
            }
        }
    } elseif (!empty($email) && !empty($token)) {
        // Direct unsubscribe via token (from email link)
        $stmt = $pdo->prepare("SELECT id, status FROM newsletter_subscribers WHERE email = ? AND confirmation_token = ?");
        $stmt->execute([$email, $token]);
        $subscriber = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($subscriber) {
            if ($subscriber['status'] === 'unsubscribed') {
                $error_message = 'This email is already unsubscribed from our newsletter.';
            } else {
                $stmt = $pdo->prepare("UPDATE newsletter_subscribers SET status = 'unsubscribed', unsubscribed_at = NOW() WHERE email = ?");
                $stmt->execute([$email]);
                $success = true;
            }
        } else {
            $error_message = 'Invalid unsubscribe link or email address not found.';
        }
    }
    
} catch (Exception $e) {
    error_log("Unsubscribe error: " . $e->getMessage());
    $error_message = 'An error occurred while processing your request. Please try again later.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsubscribe - Lighthouse Global Missions Newsletter</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            line-height: 1.6;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 500px;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .header .subtitle {
            font-size: 14px;
            opacity: 0.9;
        }

        .content {
            padding: 40px 30px;
        }

        .success-message {
            text-align: center;
            color: #16a34a;
            margin-bottom: 30px;
        }

        .success-message i {
            font-size: 48px;
            margin-bottom: 20px;
            display: block;
        }

        .success-message h2 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #1f2937;
        }

        .success-message p {
            color: #6b7280;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
            font-size: 14px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f9fafb;
            font-family: 'Inter', sans-serif;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .unsubscribe-btn {
            width: 100%;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .unsubscribe-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3);
        }

        .unsubscribe-btn:active {
            transform: translateY(0);
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-danger {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .back-link a:hover {
            color: #764ba2;
        }

        .info-text {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            color: #0c4a6e;
            font-size: 14px;
        }

        .info-text i {
            color: #0284c7;
            margin-right: 8px;
        }

        @media (max-width: 480px) {
            .container {
                margin: 10px;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .content {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-lighthouse"></i> Unsubscribe</h1>
            <p class="subtitle">Lighthouse Global Missions Newsletter</p>
        </div>
        
        <div class="content">
            <?php if ($success): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <h2>Successfully Unsubscribed</h2>
                    <p>You have been successfully unsubscribed from our newsletter.</p>
                    <p>We're sorry to see you go! If you change your mind, you can always subscribe again.</p>
                </div>
            <?php else: ?>
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>
                
                <div class="info-text">
                    <i class="fas fa-info-circle"></i>
                    We're sorry to see you go! If you no longer wish to receive our newsletter updates, please enter your email address below to unsubscribe.
                </div>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo htmlspecialchars($email); ?>"
                               placeholder="Enter your email address">
                    </div>
                    
                    <div class="form-group">
                        <label for="reason">Reason for Unsubscribing (Optional)</label>
                        <textarea id="reason" name="reason" 
                                  placeholder="Help us improve by telling us why you're unsubscribing..."></textarea>
                    </div>
                    
                    <button type="submit" class="unsubscribe-btn">
                        <i class="fas fa-user-times"></i> Unsubscribe from Newsletter
                    </button>
                </form>
            <?php endif; ?>
            
            <div class="back-link">
                <a href="newsletter-subscription.php">
                    <i class="fas fa-arrow-left"></i> Back to Newsletter Subscription
                </a>
            </div>
        </div>
    </div>

    <script>
        // Auto-focus on email field if empty
        <?php if (empty($email) && !$success): ?>
        document.getElementById('email').focus();
        <?php endif; ?>
    </script>
</body>
</html>