<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Newsletter_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('email');
    }

    // Admin authentication methods
    public function verify_admin_credentials($username, $password)
    {
        $admin = $this->db->get_where('admin_users', [
            'username' => $username,
            'status' => 'active'
        ])->row();

        if ($admin && password_verify($password, $admin->password)) {
            return $admin;
        }

        return false;
    }

    public function update_last_login($admin_id)
    {
        $this->db->where('id', $admin_id);
        $this->db->update('admin_users', ['last_login' => date('Y-m-d H:i:s')]);
    }

    // Dashboard statistics
    public function get_dashboard_stats()
    {
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
    public function get_subscribers($filter = 'all', $limit = null, $offset = 0)
    {
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

    public function get_subscribers_by_filter($search = null, $status = 'all', $date_range = 'all')
    {
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

    public function get_subscriber_stats()
    {
        $stats = [];

        $total = $this->db->count_all('newsletter_subscribers');
        $confirmed = $this->db->where('status', 'confirmed')->count_all_results('newsletter_subscribers');
        $pending = $this->db->where('status', 'pending')->count_all_results('newsletter_subscribers');
        $unsubscribed = $this->db->where('status', 'unsubscribed')->count_all_results('newsletter_subscribers');

        $stats['total'] = $total;
        $stats['all'] = $total;
        $stats['total_subscribers'] = $total;

        $stats['confirmed'] = $confirmed;
        $stats['confirmed_subscribers'] = $confirmed;

        $stats['pending'] = $pending;
        $stats['pending_confirmations'] = $pending;

        $stats['unsubscribed'] = $unsubscribed;
        $stats['unsubscribed_subscribers'] = $unsubscribed;

        return $stats;
    }

    public function get_subscriber_by_id($id)
    {
        return $this->db->get_where('newsletter_subscribers', ['id' => $id])->row();
    }

    public function get_confirmed_subscribers()
    {
        return $this->db->get_where('newsletter_subscribers', ['status' => 'confirmed'])->result();
    }

    // Find subscriber by email
    public function get_subscriber_by_email($email)
    {
        return $this->db->get_where('newsletter_subscribers', ['email' => $email])->row();
    }

    // Add new subscriber as confirmed, or confirm existing one
    public function add_subscriber_confirmed($first_name, $last_name, $email, $ip_address = null, $user_agent = null)
    {
        $existing = $this->get_subscriber_by_email($email);
        $now = date('Y-m-d H:i:s');

        if ($existing) {
            $data = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'status' => 'confirmed',
                'confirmed_at' => $now,
                'unsubscribed_at' => null
            ];
            if ($ip_address) {
                $data['ip_address'] = $ip_address;
            }
            if ($user_agent) {
                $data['user_agent'] = $user_agent;
            }

            $this->db->where('id', $existing->id);
            return $this->db->update('newsletter_subscribers', $data);
        } else {
            $insert = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'status' => 'confirmed',
                'confirmation_token' => null,
                'subscribed_at' => $now,
                'confirmed_at' => $now,
                'unsubscribed_at' => null,
                'ip_address' => $ip_address,
                'user_agent' => $user_agent
            ];
            $this->db->insert('newsletter_subscribers', $insert);
            return $this->db->insert_id();
        }
    }

    public function get_recent_subscribers($limit = 5)
    {
        $this->db->select('first_name, last_name, email, status, subscribed_at');
        $this->db->from('newsletter_subscribers');
        $this->db->order_by('subscribed_at', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result();
    }

    public function delete_subscriber($id)
    {
        $this->db->trans_start();

        // Delete from email history first (foreign key constraint)
        $this->db->delete('email_history', ['subscriber_id' => $id]);

        // Delete subscriber
        $this->db->delete('newsletter_subscribers', ['id' => $id]);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function get_all_subscribers_for_export()
    {
        $this->db->select('first_name, last_name, email, status, subscribed_at, confirmed_at');
        $this->db->from('newsletter_subscribers');
        $this->db->order_by('subscribed_at', 'DESC');

        return $this->db->get()->result();
    }

    // Newsletter methods
    public function get_newsletters($limit = null, $offset = 0)
    {
        $this->db->select('n.*, au.username as created_by_username');
        $this->db->from('newsletters n');
        $this->db->join('admin_users au', 'n.created_by = au.id', 'left');
        $this->db->order_by('n.id', 'DESC');

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get()->result();
    }

    public function get_newsletter_by_id($id)
    {
        return $this->db->get_where('newsletters', ['id' => $id])->row();
    }

    public function get_recent_newsletters($limit = 5)
    {
        $this->db->select('subject, status, recipients_count, created_at, sent_at');
        $this->db->from('newsletters');
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result();
    }

    public function create_newsletter($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('newsletters', $data);
        return $this->db->insert_id();
    }

    public function update_newsletter($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('newsletters', $data);
    }

    public function delete_newsletter($id)
    {
        $this->db->trans_start();

        // Delete from email history first (foreign key constraint)
        $this->db->delete('email_history', ['newsletter_id' => $id]);

        // Delete newsletter
        $this->db->delete('newsletters', ['id' => $id]);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    // Admin helper: resolve admin_users.id by email
    public function get_admin_id_by_email($email)
    {
        if (empty($email)) {
            return null;
        }
        $admin = $this->db->select('id')->get_where('admin_users', ['email' => $email])->row();
        return $admin ? (int) $admin->id : null;
    }

    // Admin helper: get a default active admin id (fallback)
    public function get_default_admin_id()
    {
        // Prefer super_admins, otherwise any active admin
        $admin = $this->db
            ->select('id')
            ->where('status', 'active')
            ->where('role', 'super_admin')
            ->order_by('id', 'ASC')
            ->get('admin_users')
            ->row();
        if ($admin) {
            return (int) $admin->id;
        }
        $admin = $this->db
            ->select('id')
            ->where('status', 'active')
            ->order_by('id', 'ASC')
            ->get('admin_users')
            ->row();
        return $admin ? (int) $admin->id : null;
    }

    public function get_newsletter_stats()
    {
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

    public function get_all_newsletters_for_export()
    {
        $this->db->select('subject, status, recipients_count, created_at, sent_at');
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('newsletters')->result();
    }

    // Email methods
    public function send_newsletter_email($newsletter, $subscriber)
    {
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

            // Normalize content images to public URLs
            $this->normalize_images_in_content($email_content);

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

    public function resend_confirmation_email($subscriber)
    {
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

            $message = $this->get_confirmation_email_template(
                $subscriber->first_name,
                $confirmation_url,
                $settings['email_signature'],
                null,
                isset($settings['site_url']) ? $settings['site_url'] : null
            );

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
    public function get_email_history($filters = [], $limit = 500, $offset = 0)
    {
        if (is_numeric($filters)) {
            $limit = $filters;
            $filters = [];
        }

        $this->db->select('eh.*, ns.first_name, ns.last_name, n.subject as newsletter_subject');
        $this->db->from('email_history eh');
        $this->db->join('newsletter_subscribers ns', 'eh.subscriber_id = ns.id', 'left');
        $this->db->join('newsletters n', 'eh.newsletter_id = n.id', 'left');

        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('eh.recipient_email', $filters['search']);
            $this->db->or_like('eh.recipient_name', $filters['search']);
            $this->db->or_like('eh.subject', $filters['search']);
            $this->db->or_like('n.subject', $filters['search']);
            $this->db->group_end();
        }

        if (!empty($filters['status'])) {
            $this->db->where('eh.status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $this->db->where('eh.created_at >=', $filters['date_from'] . ' 00:00:00');
        }

        if (!empty($filters['date_to'])) {
            $this->db->where('eh.created_at <=', $filters['date_to'] . ' 23:59:59');
        }

        $this->db->order_by('eh.id', 'DESC');
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get()->result();
    }

    public function get_email_history_by_id($id)
    {
        return $this->db->get_where('email_history', ['id' => $id])->row();
    }

    public function delete_email_log($id)
    {
        return $this->db->delete('email_history', ['id' => $id]);
    }

    public function clear_email_history()
    {
        return $this->db->empty_table('email_history');
    }

    public function update_email_history($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('email_history', $data);
    }

    public function log_email_history($subscriber_id, $recipient_email, $recipient_name, $subject, $email_type, $newsletter_id = null, $status = 'sent', $error_message = null)
    {
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
    public function get_email_settings()
    {
        $settings_query = $this->db->get('newsletter_settings');
        $settings_raw = $settings_query->result_array();

        $settings = [];
        foreach ($settings_raw as $setting) {
            $settings[$setting['setting_key']] = $setting['setting_value'];
        }

        return $settings;
    }

    public function get_all_settings()
    {
        $settings_raw = $this->db->get('newsletter_settings')->result();

        $settings = [];
        foreach ($settings_raw as $setting) {
            $settings[$setting->setting_key] = $setting->setting_value;
        }

        return $settings;
    }

    public function get_setting($key)
    {
        $setting = $this->db->get_where('newsletter_settings', ['setting_key' => $key])->row();
        return $setting ? $setting->setting_value : null;
    }

    public function update_setting($key, $value)
    {
        $this->db->where('setting_key', $key);
        return $this->db->update('newsletter_settings', [
            'setting_value' => $value,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    // Helper methods
    public function delete_subscribers($subscriber_ids)
    {
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

    public function resend_confirmation_emails($subscriber_ids)
    {
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

    private function prepare_newsletter_content($newsletter, $subscriber, $settings, $logo_cid = null)
    {
        $content = $newsletter->content;

        // Replace placeholders
        $content = str_replace('[FIRST_NAME]', $subscriber->first_name, $content);
        $content = str_replace('[LAST_NAME]', $subscriber->last_name, $content);
        $content = str_replace('[EMAIL]', $subscriber->email, $content);

        // Unsubscribe link is handled in the footer; body link commented out
        $unsubscribe_url = base_url() . "unsubscribe.php?email=" . urlencode($subscriber->email);
        /*
        $unsubscribe_link = "<p style='text-align: center; margin-top: 30px; font-size: 12px; color: #666;'>";
        $unsubscribe_link .= "If you no longer wish to receive these emails, you can <a href='{$unsubscribe_url}'>unsubscribe here</a>.";
        $unsubscribe_link .= "</p>";
        */

        $site_url = isset($settings['site_url']) ? $settings['site_url'] : null;

        // Wrap in email template
        $email_template = $this->get_newsletter_email_template($content, $settings['email_signature'], $logo_cid, $site_url, $unsubscribe_url);

        return $email_template;
    }

    private function get_newsletter_email_template($content, $signature, $logo_cid = null, $site_url = null, $unsubscribe_url = null)
    {
        $base = $site_url ? rtrim($site_url, '/') . '/' : base_url();
        $logo_src = 'https://newsletter.lighthouseglobalmissions.org/assets/images/newsletter_logo_90.png';
        if (empty($unsubscribe_url)) {
            $unsubscribe_url = $base . "unsubscribe.php";
        }

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #1da2f0 0%, #203550  100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; }
                .signature { margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <img src='" . $logo_src . "' alt='Lighthouse Global Missions' style='max-height: 90px; width: auto; display: block; margin: 0 auto 10px;'>
                    <h1> Lighthouse Global Missions</h1>
                    
                    <p> Taking Christ’s light to the nations </p>
                   
                </div>
                <div class='content'>
                    {$content}
                    <div class='signature'>
                        <p>" . nl2br(htmlspecialchars($signature)) . "</p>
                    </div>
                </div>
            </div>
            <table width='100%' cellpadding='0' cellspacing='0' border='0' style='background-color:#e6e6e6; padding:30px 0;'>
              <tr>
                <td align='center'>
                  
                  <table width='700' cellpadding='0' cellspacing='0' border='0' style='background-color:#1f3550; font-family:Arial, Helvetica, sans-serif;'>
                    
                     <tr>
                       <td align='center' style='padding:20px 0; background-color:#3c567c; color:#ffffff; font-size:14px; letter-spacing:1px;'>
                         <a href='" . rtrim($base, '/') . "' style='color:#ffffff; text-decoration:none; margin:0 30px;'>ABOUT</a>
                         <a href='" . rtrim($base, '/') . "' style='color:#ffffff; text-decoration:none; margin:0 30px;'>EVENTS</a>
                       </td>
                     </tr>
              
                     <tr>
                       <td align='center' style='padding:25px 0;'>
                         
                         <table cellpadding='0' cellspacing='0' border='0'>
                           <tr>
                             
                             <td align='center' style='padding-right:40px;'>
                               <a href='https://paypal.me/LGmissions?country.x=DE&locale.x=en_US'
                                  style='background-color:#c4312b; color:#ffffff;
                                         padding:12px 35px;
                                         text-decoration:none;
                                         border-radius:25px;
                                         font-weight:bold;
                                         letter-spacing:4px;
                                         display:inline-block;'>
                                 GIVE
                               </a>
                             </td>
              
                             <td align='center' style='padding:0 40px;'>
                               <img src='https://newsletter.lighthouseglobalmissions.org/assets/images/newsletter_logo_90.png'
                                    width='60'
                                    alt='Logo'
                                    style='display:block;'>
                             </td>
              
                             <td align='center' style='padding-left:40px;'>
                               <a href='#'
                                  style='background-color:#4b6f9d;
                                         color:#ffffff;
                                         padding:12px 30px;
                                         text-decoration:none;
                                         border-radius:25px;
                                         display:inline-block;'>
                                 Let’s pray for you!
                               </a>
                             </td>
              
                           </tr>
                         </table>
              
                       </td>
                     </tr>
              
                     <tr>
                       <td align='center' style='padding:20px 40px 40px 40px;'>
                         
                         <table width='100%' cellpadding='0' cellspacing='0' border='0'
                                style='border:1px solid #9fb3c9; padding:30px;'>
                           
                           <tr>
                             <td align='center' style='color:#ffffff;'>
                               
                               <h2 style='margin:0 0 20px 0; font-size:24px; letter-spacing:1px;'>
                                 Lighthouse Global Missions
              
                               </h2>
              
                               <p style='margin:0 0 15px 0; font-size:14px; color:#cdd6e0;'>
                                 Copyright © " . date('Y') . " Lighthouse Global Missions. All rights reserved.
                               </p>
              
                               <p style='margin:0 0 25px 0; font-size:13px; color:#cdd6e0;'>
                                 You are subscribed to LG Missions newsletter | <a href='{$unsubscribe_url}' style='color:#cdd6e0; text-decoration:underline;'>Unsubscribe</a>
                               </p>
              
                               <p style='margin:0;'>
                                 <a href='#' style='margin:0 8px;'>
                                   <img src='https://cdn-icons-png.flaticon.com/512/733/733547.png' width='18' alt='Facebook'>
                                 </a>
                                 <a href='#' style='margin:0 8px;'>
                                   <img src='https://cdn-icons-png.flaticon.com/512/733/733558.png' width='18' alt='Instagram'>
                                 </a>
                                 <a href='#' style='margin:0 8px;'>
                                   <img src='https://cdn-icons-png.flaticon.com/512/3670/3670358.png' width='18' alt='TIKTOK'>
                                 </a>
                                 <a href='#' style='margin:0 8px;'>
                                   <img src='https://cdn-icons-png.flaticon.com/512/1384/1384060.png' width='18' alt='YouTube'>
                                 </a>
                                 <a href='#' style='margin:0 8px;'>
                                   <img src='https://cdn-icons-png.flaticon.com/512/561/561127.png' width='18' alt='Email'>
                                 </a>
                               </p>
              
                             </td>
                           </tr>
              
                         </table>
              
                       </td>
                     </tr>
              
                   </table>
              
                 </td>
               </tr>
             </table>
        </body>
        </html>";
    }

    private function get_confirmation_email_template($first_name, $confirmation_url, $signature, $logo_cid = null, $site_url = null)
    {
        $base = $site_url ? rtrim($site_url, '/') . '/' : base_url();
        $logo_src = 'https://newsletter.lighthouseglobalmissions.org/assets/images/newsletter_logo_90.png';

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
                .header { background: linear-gradient(135deg, #1da2f0 0%, #203550 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f8fafc; padding: 30px; border-radius: 0 0 10px 10px; }
                .button { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; margin: 20px 0; }
                .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                 <img src='" . $logo_src . "' alt='Lighthouse Global Missions' style='max-height: 90px; width: auto; display: block; margin: 0 auto 10px;'>
                   
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
                    <p style='word-break: break-all; color: #ffffffff;'>" . $confirmation_url . "</p>
                    
                    <p>If you didn't subscribe to our newsletter, you can safely ignore this email.</p>
                    
                    <div class='footer'>
                        <p>" . nl2br(htmlspecialchars($signature)) . "</p>
                    </div>
                </div>
            </div>
        </body>
        </html>";
    }

    public function normalize_images_in_content(&$content)
    {
        // Find all images with either single or double quotes
        preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $content, $matches);

        if (empty($matches[1])) {
            return;
        }

        $unique_images = array_unique($matches[1]);
        $public_base = 'https://newsletter.lighthouseglobalmissions.org/';

        foreach ($unique_images as $src) {
            // If already pointing to the public newsletter URL, nothing to change
            if (strpos($src, $public_base) === 0) {
                continue;
            }

            // Parse URL path
            $path = parse_url($src, PHP_URL_PATH);
            if (!$path) {
                continue;
            }
            $path = urldecode($path);

            $clean_path = ltrim($path, '/');
            // Remove local subfolder prefix if present (e.g. lgnewsletter/uploads/...)
            if (strpos($clean_path, 'lgnewsletter/') === 0) {
                $clean_path = substr($clean_path, strlen('lgnewsletter/'));
            }

            // If the image points to uploads or assets, make it point to the public domain
            if (strpos($clean_path, 'uploads/') === 0 || strpos($clean_path, 'assets/') === 0) {
                $new_url = $public_base . $clean_path;
                $content = str_replace($src, $new_url, $content);
            }
        }
    }

    private function embed_images_in_content(&$content)
    {
        $this->normalize_images_in_content($content);
    }
}
?>