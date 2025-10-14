<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Newsletter_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('email');
    }

    // Admin authentication methods
    public function verify_admin_credentials($username, $password) {
        $admin = $this->db->get_where('admin_users', [
            'username' => $username,
            'status' => 'active'
        ])->row();
        
        if ($admin && password_verify($password, $admin->password)) {
            return $admin;
        }
        
        return false;
    }

    public function update_last_login($admin_id) {
        $this->db->where('id', $admin_id);
        $this->db->update('admin_users', ['last_login' => date('Y-m-d H:i:s')]);
    }

    // Dashboard statistics
    public function get_dashboard_stats() {
        $stats = [];
        
        // Total subscribers
        $stats['total_subscribers'] = $this->db->count_all('newsletter_subscribers');
        
        // Confirmed subscribers
        $stats['confirmed_subscribers'] = $this->db->where('status', 'confirmed')
                                                  ->count_all_results('newsletter_subscribers');
        
        // Pending confirmations
        $stats['pending_confirmations'] = $this->db->where('status', 'pending')
                                                   ->count_all_results('newsletter_subscribers');
        
        // Sent newsletters
        $stats['sent_newsletters'] = $this->db->where('status', 'sent')
                                              ->count_all_results('newsletters');
        
        // Recent subscriptions (last 30 days)
        $stats['recent_subscriptions'] = $this->db->where('subscribed_at >=', date('Y-m-d H:i:s', strtotime('-30 days')))
                                                  ->count_all_results('newsletter_subscribers');
        
        return $stats;
    }

    // Subscriber methods
    public function get_subscribers($filter = 'all', $limit = null, $offset = 0) {
        $this->db->select('*');
        $this->db->from('newsletter_subscribers');
        
        if ($filter !== 'all') {
            $this->db->where('status', $filter);
        }
        
        $this->db->order_by('subscribed_at', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    public function get_subscribers_by_filter($search = null, $status = 'all', $date_range = 'all') {
        $this->db->select('id, first_name, last_name, email, status, subscribed_at, confirmed_at, 
                          CONCAT(first_name, " ", last_name) as name, 
                          subscribed_at as created_at');
        $this->db->from('newsletter_subscribers');
        
        // Apply search filter
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('first_name', $search);
            $this->db->or_like('last_name', $search);
            $this->db->or_like('email', $search);
            $this->db->group_end();
        }
        
        // Apply status filter
        if ($status !== 'all') {
            $this->db->where('status', $status);
        }
        
        // Apply date range filter
        if ($date_range !== 'all') {
            switch ($date_range) {
                case 'today':
                    $this->db->where('DATE(subscribed_at)', date('Y-m-d'));
                    break;
                case 'week':
                    $this->db->where('subscribed_at >=', date('Y-m-d H:i:s', strtotime('-7 days')));
                    break;
                case 'month':
                    $this->db->where('subscribed_at >=', date('Y-m-d H:i:s', strtotime('-30 days')));
                    break;
                case 'year':
                    $this->db->where('subscribed_at >=', date('Y-m-d H:i:s', strtotime('-1 year')));
                    break;
            }
        }
        
        $this->db->order_by('subscribed_at', 'DESC');
        
        return $this->db->get()->result();
    }

    public function get_subscriber_stats() {
        $stats = [];
        
        $stats['all'] = $this->db->count_all('newsletter_subscribers');
        $stats['confirmed'] = $this->db->where('status', 'confirmed')->count_all_results('newsletter_subscribers');
        $stats['pending'] = $this->db->where('status', 'pending')->count_all_results('newsletter_subscribers');
        $stats['unsubscribed'] = $this->db->where('status', 'unsubscribed')->count_all_results('newsletter_subscribers');
        
        return $stats;
    }

    public function get_subscriber_by_id($id) {
        return $this->db->get_where('newsletter_subscribers', ['id' => $id])->row();
    }

    public function get_confirmed_subscribers() {
        return $this->db->get_where('newsletter_subscribers', ['status' => 'confirmed'])->result();
    }

    public function get_recent_subscribers($limit = 5) {
        $this->db->select('first_name, last_name, email, status, subscribed_at');
        $this->db->from('newsletter_subscribers');
        $this->db->order_by('subscribed_at', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }

    public function delete_subscriber($id) {
        $this->db->trans_start();
        
        // Delete from email history first (foreign key constraint)
        $this->db->delete('email_history', ['subscriber_id' => $id]);
        
        // Delete subscriber
        $this->db->delete('newsletter_subscribers', ['id' => $id]);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    public function get_all_subscribers_for_export() {
        $this->db->select('first_name, last_name, email, status, subscribed_at, confirmed_at');
        $this->db->from('newsletter_subscribers');
        $this->db->order_by('subscribed_at', 'DESC');
        
        return $this->db->get()->result();
    }

    // Newsletter methods
    public function get_newsletters($limit = null, $offset = 0) {
        $this->db->select('n.*, au.username as created_by_username');
        $this->db->from('newsletters n');
        $this->db->join('admin_users au', 'n.created_by = au.id', 'left');
        $this->db->order_by('n.created_at', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    public function get_newsletter_by_id($id) {
        return $this->db->get_where('newsletters', ['id' => $id])->row();
    }

    public function get_recent_newsletters($limit = 5) {
        $this->db->select('subject, status, recipients_count, created_at, sent_at');
        $this->db->from('newsletters');
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }

    public function create_newsletter($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('newsletters', $data);
        return $this->db->insert_id();
    }

    public function update_newsletter($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('newsletters', $data);
    }

    public function delete_newsletter($id) {
        $this->db->trans_start();
        
        // Delete from email history first (foreign key constraint)
        $this->db->delete('email_history', ['newsletter_id' => $id]);
        
        // Delete newsletter
        $this->db->delete('newsletters', ['id' => $id]);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    // Admin helper: resolve admin_users.id by email
    public function get_admin_id_by_email($email) {
        if (empty($email)) {
            return null;
        }
        $admin = $this->db->select('id')->get_where('admin_users', ['email' => $email])->row();
        return $admin ? (int)$admin->id : null;
    }

    // Admin helper: get a default active admin id (fallback)
    public function get_default_admin_id() {
        // Prefer super_admins, otherwise any active admin
        $admin = $this->db
            ->select('id')
            ->where('status', 'active')
            ->where('role', 'super_admin')
            ->order_by('id', 'ASC')
            ->get('admin_users')
            ->row();
        if ($admin) {
            return (int)$admin->id;
        }
        $admin = $this->db
            ->select('id')
            ->where('status', 'active')
            ->order_by('id', 'ASC')
            ->get('admin_users')
            ->row();
        return $admin ? (int)$admin->id : null;
    }

    public function get_newsletter_stats() {
        $stats = new stdClass();
        
        // Total newsletters
        $stats->total_newsletters = $this->db->count_all('newsletters');
        
        // Draft newsletters
        $this->db->where('status', 'draft');
        $stats->draft_newsletters = $this->db->count_all_results('newsletters');
        
        // Sent newsletters
        $this->db->where('status', 'sent');
        $stats->sent_newsletters = $this->db->count_all_results('newsletters');
        
        // Recent newsletters (last 30 days)
        $this->db->where('created_at >=', date('Y-m-d H:i:s', strtotime('-30 days')));
        $stats->recent_newsletters = $this->db->count_all_results('newsletters');
        
        return $stats;
    }

    public function get_all_newsletters_for_export() {
        $this->db->select('subject, status, recipients_count, created_at, sent_at');
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('newsletters')->result();
    }

    // Email methods
    public function send_newsletter_email($newsletter, $subscriber) {
        try {
            $settings = $this->get_email_settings();
            
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
            
            $this->email->initialize($config);
            
            // Prepare email content
            $email_content = $this->prepare_newsletter_content($newsletter, $subscriber, $settings);
            
            // Fallback to settings sender details if newsletter lacks them
            $from_email = (!empty($newsletter->sender_email) && filter_var($newsletter->sender_email, FILTER_VALIDATE_EMAIL)) 
                ? $newsletter->sender_email 
                : (isset($settings['sender_email']) ? $settings['sender_email'] : null);
            $from_name = !empty($newsletter->sender_name) 
                ? $newsletter->sender_name 
                : (isset($settings['sender_name']) ? $settings['sender_name'] : '');

            $this->email->from($from_email, $from_name);
            $this->email->to($subscriber->email);
            $this->email->subject($newsletter->subject);
            $this->email->message($email_content);
            
            $result = $this->email->send();
            
            // Log email history
            $this->log_email_history(
                $subscriber->id,
                $subscriber->email,
                $subscriber->first_name . ' ' . $subscriber->last_name,
                $newsletter->subject,
                'newsletter',
                $newsletter->id,
                $result ? 'sent' : 'failed',
                $result ? null : $this->email->print_debugger()
            );
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Newsletter email error: " . $e->getMessage());
            
            // Log failed email
            $this->log_email_history(
                $subscriber->id,
                $subscriber->email,
                $subscriber->first_name . ' ' . $subscriber->last_name,
                $newsletter->subject,
                'newsletter',
                $newsletter->id,
                'failed',
                $e->getMessage()
            );
            
            return false;
        }
    }

    public function resend_confirmation_email($subscriber) {
        try {
            $settings = $this->get_email_settings();
            
            // Generate new confirmation token
            $confirmation_token = bin2hex(random_bytes(32));
            
            // Update subscriber with new token
            $this->db->where('id', $subscriber->id);
            $this->db->update('newsletter_subscribers', ['confirmation_token' => $confirmation_token]);
            
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
            
            $this->email->initialize($config);
            
            // Email content
            $confirmation_url = base_url() . "confirm_subscription.php?token=" . $confirmation_token;
            $message = $this->get_confirmation_email_template($subscriber->first_name, $confirmation_url, $settings['email_signature']);
            
            $this->email->from($settings['sender_email'], $settings['sender_name']);
            $this->email->to($subscriber->email);
            $this->email->subject($settings['confirmation_subject']);
            $this->email->message($message);
            
            $result = $this->email->send();
            
            // Log email history
            $this->log_email_history(
                $subscriber->id,
                $subscriber->email,
                $subscriber->first_name . ' ' . $subscriber->last_name,
                $settings['confirmation_subject'],
                'confirmation',
                null,
                $result ? 'sent' : 'failed',
                $result ? null : $this->email->print_debugger()
            );
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Confirmation email error: " . $e->getMessage());
            return false;
        }
    }

    // Email history methods
    public function get_email_history($limit = 100, $offset = 0) {
        $this->db->select('eh.*, ns.first_name, ns.last_name, n.subject as newsletter_subject');
        $this->db->from('email_history eh');
        $this->db->join('newsletter_subscribers ns', 'eh.subscriber_id = ns.id', 'left');
        $this->db->join('newsletters n', 'eh.newsletter_id = n.id', 'left');
        $this->db->order_by('eh.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result();
    }

    public function log_email_history($subscriber_id, $recipient_email, $recipient_name, $subject, $email_type, $newsletter_id = null, $status = 'sent', $error_message = null) {
        $data = [
            'recipient_email' => $recipient_email,
            'recipient_name' => $recipient_name,
            'subject' => $subject,
            'email_type' => $email_type,
            'newsletter_id' => $newsletter_id,
            'subscriber_id' => $subscriber_id,
            'status' => $status,
            'sent_at' => ($status === 'sent') ? date('Y-m-d H:i:s') : null,
            'error_message' => $error_message,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->insert('email_history', $data);
    }

    // Settings methods
    public function get_email_settings() {
        $settings_query = $this->db->get('newsletter_settings');
        $settings_raw = $settings_query->result_array();
        
        $settings = [];
        foreach ($settings_raw as $setting) {
            $settings[$setting['setting_key']] = $setting['setting_value'];
        }
        
        return $settings;
    }

    public function get_all_settings() {
        $settings_raw = $this->db->get('newsletter_settings')->result();
        
        $settings = [];
        foreach ($settings_raw as $setting) {
            $settings[$setting->setting_key] = $setting->setting_value;
        }
        
        return $settings;
    }

    public function get_setting($key) {
        $setting = $this->db->get_where('newsletter_settings', ['setting_key' => $key])->row();
        return $setting ? $setting->setting_value : null;
    }

    public function update_setting($key, $value) {
        $this->db->where('setting_key', $key);
        return $this->db->update('newsletter_settings', [
            'setting_value' => $value,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    // Helper methods
    public function delete_subscribers($subscriber_ids) {
        if (empty($subscriber_ids) || !is_array($subscriber_ids)) {
            return false;
        }
        
        $this->db->trans_start();
        
        // Delete from email history first (foreign key constraint)
        $this->db->where_in('subscriber_id', $subscriber_ids);
        $this->db->delete('email_history');
        
        // Delete subscribers
        $this->db->where_in('id', $subscriber_ids);
        $this->db->delete('newsletter_subscribers');
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    public function resend_confirmation_emails($subscriber_ids) {
        if (empty($subscriber_ids) || !is_array($subscriber_ids)) {
            return false;
        }
        
        $success_count = 0;
        
        foreach ($subscriber_ids as $id) {
            $subscriber = $this->get_subscriber_by_id($id);
            
            if ($subscriber && $subscriber->status === 'pending') {
                if ($this->resend_confirmation_email($subscriber)) {
                    $success_count++;
                }
            }
        }
        
        return $success_count > 0;
    }

    private function prepare_newsletter_content($newsletter, $subscriber, $settings) {
        $content = $newsletter->content;
        
        // Replace placeholders
        $content = str_replace('[FIRST_NAME]', $subscriber->first_name, $content);
        $content = str_replace('[LAST_NAME]', $subscriber->last_name, $content);
        $content = str_replace('[EMAIL]', $subscriber->email, $content);
        
        // Add unsubscribe link
        $unsubscribe_url = base_url() . "unsubscribe.php?email=" . urlencode($subscriber->email);
        $unsubscribe_link = "<p style='text-align: center; margin-top: 30px; font-size: 12px; color: #666;'>";
        $unsubscribe_link .= "If you no longer wish to receive these emails, you can <a href='{$unsubscribe_url}'>unsubscribe here</a>.";
        $unsubscribe_link .= "</p>";
        
        // Wrap in email template
        $email_template = $this->get_newsletter_email_template($content . $unsubscribe_link, $settings['email_signature']);
        
        return $email_template;
    }

    private function get_newsletter_email_template($content, $signature) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Newsletter - Lighthouse Global Missions</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #1f2937 0%, #374151 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; }
                .footer { background: #f8fafc; padding: 20px; text-align: center; color: #666; font-size: 14px; border-radius: 0 0 10px 10px; border: 1px solid #e5e7eb; border-top: none; }
                .signature { margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <img src='" . base_url() . "assets/images/newsletter_logo_90.png' alt='Lighthouse Global Missions' style='max-height: 90px; width: auto; display: block; margin: 0 auto 10px;'>
                    <h1> Lighthouse Global Missions</h1>
                    <p>Ministry Updates & News</p>
                </div>
                <div class='content'>
                    {$content}
                    <div class='signature'>
                        <p>" . nl2br(htmlspecialchars($signature)) . "</p>
                    </div>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " Lighthouse Global Missions. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>";
    }

    private function get_confirmation_email_template($first_name, $confirmation_url, $signature) {
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
                    <img src='" . base_url() . "assets/images/newsletter_logo_90.png' alt='Lighthouse Global Missions' style='max-height: 90px; width: auto; display: block; margin: 0 auto 10px;'>
                    <h1> Lighthouse Global Missions</h1>
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
}
?>