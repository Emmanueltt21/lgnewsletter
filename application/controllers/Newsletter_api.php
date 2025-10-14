<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Newsletter_api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        
        // Set JSON response headers
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        
        // Handle preflight OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit(0);
        }
    }

    /**
     * Subscribe a user to the newsletter
     * POST /newsletter_api/subscribe
     */
    public function subscribe() {
        if ($this->input->method() !== 'post') {
            $this->_send_response(false, 'Only POST method allowed', null, 405);
            return;
        }

        // Get input data
        $first_name = trim($this->input->post('first_name'));
        $last_name = trim($this->input->post('last_name'));
        $email = trim($this->input->post('email'));

        // Validate input
        if (empty($first_name) || empty($last_name) || empty($email)) {
            $this->_send_response(false, 'All fields are required', null, 400);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->_send_response(false, 'Invalid email address', null, 400);
            return;
        }

        try {
            // Check if email already exists
            $existing = $this->db->where('email', $email)->get('newsletter_subscribers')->row();

            if ($existing) {
                if ($existing->status === 'confirmed') {
                    $this->_send_response(false, 'You are already subscribed to our newsletter', null, 409);
                    return;
                } elseif ($existing->status === 'pending') {
                    // Resend confirmation email
                    $this->_resend_confirmation($existing);
                    $this->_send_response(true, 'Confirmation email has been resent to your email address', null);
                    return;
                } elseif ($existing->status === 'unsubscribed') {
                    // Reactivate subscription
                    $this->_reactivate_subscription($existing, $first_name, $last_name);
                    $this->_send_response(true, 'Your subscription has been reactivated. Please check your email for confirmation', null);
                    return;
                }
            }

            // Create new subscription
            $token = $this->_generate_token();
            $data = array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'status' => 'pending',
                'confirmation_token' => $token,
                'subscribed_at' => date('Y-m-d H:i:s'),
                'ip_address' => $this->input->ip_address(),
                'user_agent' => $this->input->user_agent()
            );

            if ($this->db->insert('newsletter_subscribers', $data)) {
                $subscriber_id = $this->db->insert_id();
                
                // Send confirmation email
                $this->_send_confirmation_email($email, $first_name, $last_name, $token, $subscriber_id);
                
                $this->_send_response(true, 'Thank you for subscribing! Please check your email to confirm your subscription', array('subscriber_id' => $subscriber_id));
            } else {
                $this->_send_response(false, 'Failed to process subscription. Please try again', null, 500);
            }

        } catch (Exception $e) {
            log_message('error', 'Newsletter subscription error: ' . $e->getMessage());
            $this->_send_response(false, 'An error occurred. Please try again later', null, 500);
        }
    }

    /**
     * Get subscriber information
     * GET /newsletter_api/subscriber/{email}
     */
    public function subscriber($email = null) {
        if ($this->input->method() !== 'get') {
            $this->_send_response(false, 'Only GET method allowed', null, 405);
            return;
        }

        if (empty($email)) {
            $this->_send_response(false, 'Email parameter is required', null, 400);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->_send_response(false, 'Invalid email address', null, 400);
            return;
        }

        try {
            $subscriber = $this->db->select('id, first_name, last_name, email, status, subscribed_at, confirmed_at')
                                  ->where('email', $email)
                                  ->get('newsletter_subscribers')
                                  ->row();

            if ($subscriber) {
                $this->_send_response(true, 'Subscriber found', $subscriber);
            } else {
                $this->_send_response(false, 'Subscriber not found', null, 404);
            }

        } catch (Exception $e) {
            log_message('error', 'Newsletter subscriber lookup error: ' . $e->getMessage());
            $this->_send_response(false, 'An error occurred. Please try again later', null, 500);
        }
    }

    /**
     * Confirm subscription
     * POST /newsletter_api/confirm
     */
    public function confirm() {
        if ($this->input->method() !== 'post') {
            $this->_send_response(false, 'Only POST method allowed', null, 405);
            return;
        }

        $token = trim($this->input->post('token'));

        if (empty($token)) {
            $this->_send_response(false, 'Confirmation token is required', null, 400);
            return;
        }

        try {
            $subscriber = $this->db->where('confirmation_token', $token)
                                  ->where('status', 'pending')
                                  ->get('newsletter_subscribers')
                                  ->row();

            if ($subscriber) {
                // Update subscription status
                $update_data = array(
                    'status' => 'confirmed',
                    'confirmed_at' => date('Y-m-d H:i:s'),
                    'confirmation_token' => null
                );

                if ($this->db->where('id', $subscriber->id)->update('newsletter_subscribers', $update_data)) {
                    $this->_send_response(true, 'Your subscription has been confirmed successfully!', array('subscriber_id' => $subscriber->id));
                } else {
                    $this->_send_response(false, 'Failed to confirm subscription. Please try again', null, 500);
                }
            } else {
                $this->_send_response(false, 'Invalid or expired confirmation token', null, 400);
            }

        } catch (Exception $e) {
            log_message('error', 'Newsletter confirmation error: ' . $e->getMessage());
            $this->_send_response(false, 'An error occurred. Please try again later', null, 500);
        }
    }

    /**
     * Private helper methods
     */
    private function _send_response($success, $message, $data = null, $http_code = 200) {
        http_response_code($http_code);
        echo json_encode(array(
            'success' => $success,
            'message' => $message,
            'data' => $data
        ));
        exit;
    }

    private function _generate_token() {
        return bin2hex(random_bytes(32));
    }

    private function _resend_confirmation($subscriber) {
        // Generate new token
        $token = $this->_generate_token();
        
        // Update token in database
        $this->db->where('id', $subscriber->id)
                 ->update('newsletter_subscribers', array('confirmation_token' => $token));
        
        // Send confirmation email
        $this->_send_confirmation_email($subscriber->email, $subscriber->first_name, $subscriber->last_name, $token, $subscriber->id);
    }

    private function _reactivate_subscription($subscriber, $first_name, $last_name) {
        $token = $this->_generate_token();
        
        $update_data = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'status' => 'pending',
            'confirmation_token' => $token,
            'subscribed_at' => date('Y-m-d H:i:s'),
            'unsubscribed_at' => null
        );
        
        $this->db->where('id', $subscriber->id)->update('newsletter_subscribers', $update_data);
        
        // Send confirmation email
        $this->_send_confirmation_email($subscriber->email, $first_name, $last_name, $token, $subscriber->id);
    }

    private function _send_confirmation_email($email, $first_name, $last_name, $token, $subscriber_id) {
        // Get email settings
        $settings = $this->_get_email_settings();

        // Subject and confirmation URL
        $subject = isset($settings['confirmation_subject'])
            ? $settings['confirmation_subject']
            : 'Confirm Your Newsletter Subscription - Lighthouse Global Missions';
        $confirmation_url = base_url() . "confirm_subscription.php?token=" . $token;

        // Use CI Email library for sending and inline CID image support
        $this->load->library('email');
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => isset($settings['smtp_host']) ? $settings['smtp_host'] : 'localhost',
            'smtp_port' => isset($settings['smtp_port']) ? $settings['smtp_port'] : 587,
            'smtp_user' => isset($settings['smtp_username']) ? $settings['smtp_username'] : '',
            'smtp_pass' => isset($settings['smtp_password']) ? $settings['smtp_password'] : '',
            'smtp_crypto' => isset($settings['smtp_encryption']) ? $settings['smtp_encryption'] : 'tls',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n"
        ];
        $this->email->initialize($config);

        // Inline-embed logo image
        $logo_cid = null;
        $logo_path = FCPATH . 'assets/images/newsletter_logo_90.png';
        if (is_file($logo_path)) {
            $this->email->attach($logo_path, 'inline');
            $logo_cid = $this->email->attachment_cid($logo_path);
        }

        // Build message with CID logo or site URL fallback
        $message = $this->_get_email_template(
            $first_name,
            $last_name,
            $confirmation_url,
            $logo_cid,
            isset($settings['site_url']) ? $settings['site_url'] : null
        );

        // Send email
        $this->email->from(
            isset($settings['sender_email']) ? $settings['sender_email'] : (isset($settings['from_email']) ? $settings['from_email'] : 'noreply@lighthouseglobal.org'),
            isset($settings['sender_name']) ? $settings['sender_name'] : (isset($settings['from_name']) ? $settings['from_name'] : 'Lighthouse Global Missions')
        );
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($message);
        $sent = $this->email->send();
        
        // Log email history
        $this->_log_email_history($email, $first_name . ' ' . $last_name, $subject, 'confirmation', null, $subscriber_id, $sent);
        
        return $sent;
    }

    private function _get_email_settings() {
        try {
            $rows = $this->db->get('newsletter_settings')->result();
            $settings = [];
            foreach ($rows as $row) {
                if (isset($row->setting_key)) {
                    $settings[$row->setting_key] = $row->setting_value;
                }
            }
            // Backward-compatible keys
            $settings['from_name'] = isset($settings['sender_name']) ? $settings['sender_name'] : 'Lighthouse Global Missions';
            $settings['from_email'] = isset($settings['sender_email']) ? $settings['sender_email'] : 'noreply@lighthouseglobal.org';
            return $settings;
        } catch (Exception $e) {
            return array(
                'sender_name' => 'Lighthouse Global Missions',
                'sender_email' => 'noreply@lighthouseglobal.org',
                'from_name' => 'Lighthouse Global Missions',
                'from_email' => 'noreply@lighthouseglobal.org'
            );
        }
    }

    private function _get_email_template($first_name, $last_name, $confirmation_url, $logoCid = null, $siteUrl = null) {
        $base = $siteUrl ? rtrim($siteUrl, '/') . '/' : base_url();
        $logoUrl = $logoCid ? ('cid:' . $logoCid) : ($base . 'assets/images/newsletter_logo_90.png');
        $year = date('Y');
        $first_name_esc = htmlspecialchars($first_name, ENT_QUOTES, 'UTF-8');
        $last_name_esc = htmlspecialchars($last_name, ENT_QUOTES, 'UTF-8');

        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Your Subscription</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
            .content { padding: 30px; background: #f9f9f9; }
            .button { display: inline-block; padding: 12px 30px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
            .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
        </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="$logoUrl" alt="Lighthouse Global Missions" style="max-height: 90px; width: auto; display: block; margin: 0 auto 10px;">
            <h1>Lighthouse Global Missions</h1>
            <p>Newsletter Subscription Confirmation</p>
        </div>
        <div class="content">
            <h2>Hello $first_name_esc $last_name_esc,</h2>
            <p>Thank you for subscribing to our newsletter! To complete your subscription, please click the button below to confirm your email address:</p>
            <p style="text-align: center;">
                <a href="$confirmation_url" class="button">Confirm Subscription</a>
            </p>
            <p>If the button doesn't work, you can copy and paste this link into your browser:</p>
            <p><a href="$confirmation_url">$confirmation_url</a></p>
            <p>If you didn't subscribe to our newsletter, you can safely ignore this email.</p>
        </div>
        <div class="footer">
            <p>&copy; $year Lighthouse Global Missions. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;
        return $html;
    }

    private function _log_email_history($recipient_email, $recipient_name, $subject, $email_type, $newsletter_id, $subscriber_id, $sent) {
        $data = array(
            'recipient_email' => $recipient_email,
            'recipient_name' => $recipient_name,
            'subject' => $subject,
            'email_type' => $email_type,
            'newsletter_id' => $newsletter_id,
            'subscriber_id' => $subscriber_id,
            'sent_at' => date('Y-m-d H:i:s'),
            'status' => $sent ? 'sent' : 'failed'
        );
        
        $this->db->insert('email_history', $data);
    }
}