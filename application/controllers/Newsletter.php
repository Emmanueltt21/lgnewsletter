<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/BaseController.php';

/**
 * Class : Newsletter
 * Newsletter Class to control newsletter management operations
 */
class Newsletter extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        $this->load->model('Newsletter_model');
        $this->load->library('form_validation');
    }

    // Newsletter management
    public function index()
    {
        $data['title'] = 'Newsletter Management';
        $data['newsletters'] = $this->Newsletter_model->get_newsletters();
        $data['stats'] = $this->Newsletter_model->get_newsletter_stats();

        // Render using standard views to avoid loader method diagnostics
        $this->load->view('templates/header', $data);
        $this->load->view('newsletter/manage', $data);
        $this->load->view('templates/footer', $data);
    }

    // Alias for index
    public function newsletters()
    {
        $this->index();
    }

    // Compose newsletter
    public function compose($id = null)
    {
        $data['title'] = $id ? 'Edit Newsletter' : 'Compose Newsletter';
        $data['newsletter'] = $id ? $this->Newsletter_model->get_newsletter_by_id($id) : null;
        $data['settings'] = $this->Newsletter_model->get_email_settings();
        $data['test_email_address'] = $this->session->flashdata('test_email_address') ?: $this->session->userdata('email');
        $data['selected_recipient_type'] = $this->session->flashdata('recipient_type') ?: 'all';

        if ($this->input->post()) {
            $this->process_newsletter_form($id);
            return;
        }

        // Render using standard views to avoid loader method diagnostics
        $this->load->view('templates/header', $data);
        $this->load->view('newsletter/compose', $data);
        $this->load->view('templates/footer', $data);
    }

    // Process newsletter form
    private function process_newsletter_form($id = null)
    {
        $subject = $this->input->post('subject');
        $content = $this->input->post('content');
        $action = $this->input->post('action');
        $recipient_type = $this->input->post('recipient_type');
        $test_email_address = trim($this->input->post('test_email_address') ?? '');

        // Convert any pasted base64 data URIs into physical files and public URLs
        $this->Newsletter_model->convert_base64_images($content);

        // Resolve created_by to a valid admin_users.id (or NULL if unknown)
        $sessionUser = $this->session->userdata('userId');
        $createdBy = null;
        if (is_numeric($sessionUser)) {
            $createdBy = (int) $sessionUser;
        } elseif (!empty($sessionUser)) {
            // If session stores email, look up corresponding admin user ID
            $createdBy = $this->Newsletter_model->get_admin_id_by_email($sessionUser);
        }

        // If no admin ID found by session, fallback to a default active admin
        if ($createdBy === null) {
            $createdBy = $this->Newsletter_model->get_default_admin_id();
        }

        // If still no admin ID, abort cleanly with a helpful error
        if ($createdBy === null) {
            $this->session->set_flashdata('error', 'No active admin found to attribute this newsletter. Please ensure an admin exists in Admin Users.');
            redirect('newsletter/index');
            return;
        }

        $newsletter_data = [
            'subject' => $subject,
            'content' => $content,
            'sender_name' => $this->input->post('sender_name'),
            'sender_email' => $this->input->post('sender_email'),
            'status' => 'draft',
            'created_by' => $createdBy
        ];

        if ($id) {
            // Update existing newsletter
            $this->Newsletter_model->update_newsletter($id, $newsletter_data);
            $newsletter_id = $id;
        } else {
            // Create new newsletter
            $newsletter_id = $this->Newsletter_model->create_newsletter($newsletter_data);
        }

        if ($action === 'send') {
            if ($recipient_type === 'test') {
                if (empty($test_email_address) || !filter_var($test_email_address, FILTER_VALIDATE_EMAIL)) {
                    $this->session->set_flashdata('error', 'Please enter a valid email address for the test email.');
                    $this->session->set_flashdata('recipient_type', 'test');
                    redirect('newsletter/compose/' . $newsletter_id);
                    return;
                }
                $this->send_test($newsletter_id, $test_email_address);
                return;
            } else {
                $this->send($newsletter_id);
                return;
            }
        }

        $message = $id ? 'Newsletter updated successfully' : 'Newsletter created successfully';
        $this->session->set_flashdata('success', $message);
        redirect('newsletter/index');
    }

    // Send test email
    public function send_test($id, $test_email = null)
    {
        if (!$test_email) {
            $test_email = trim($this->input->post('test_email_address') ?? '');
        }

        if (empty($test_email) || !filter_var($test_email, FILTER_VALIDATE_EMAIL)) {
            $this->session->set_flashdata('error', 'Please provide a valid test email address.');
            $this->session->set_flashdata('recipient_type', 'test');
            redirect('newsletter/compose/' . $id);
            return;
        }

        $newsletter = $this->Newsletter_model->get_newsletter_by_id($id);
        if (!$newsletter) {
            $this->session->set_flashdata('error', 'Newsletter not found.');
            redirect('newsletter/index');
            return;
        }

        // Try to find the actual subscriber to use their real name for a realistic preview
        $test_subscriber = $this->Newsletter_model->get_subscriber_by_email($test_email);
        
        if (!$test_subscriber) {
            // If the test email is not a subscriber, borrow a real name from the database for the preview
            $real_subscriber = $this->db->where('status', 'confirmed')->limit(1)->get('newsletter_subscribers')->row();
            
            $test_subscriber = new stdClass();
            $test_subscriber->id = null;
            $test_subscriber->first_name = $real_subscriber ? $real_subscriber->first_name : 'Test';
            $test_subscriber->last_name = $real_subscriber ? $real_subscriber->last_name : 'Recipient';
            $test_subscriber->email = $test_email;
        }

        $sent = $this->Newsletter_model->send_newsletter_email($newsletter, $test_subscriber);

        if ($sent) {
            $this->session->set_flashdata('success', 'Test newsletter successfully sent to ' . htmlspecialchars($test_email) . '. It is now recorded in Email History.');
        } else {
            $this->session->set_flashdata('error', 'Failed to send test email to ' . htmlspecialchars($test_email) . '. Please check SMTP settings or error log.');
        }

        $this->session->set_flashdata('test_email_address', $test_email);
        $this->session->set_flashdata('recipient_type', 'test');
        redirect('newsletter/compose/' . $id);
    }

    // Send newsletter
    public function send($id)
    {
        $newsletter = $this->Newsletter_model->get_newsletter_by_id($id);
        $subscribers = $this->Newsletter_model->get_confirmed_subscribers();

        if (!$newsletter || !$subscribers) {
            $this->session->set_flashdata('error', 'Newsletter or subscribers not found');
            redirect('newsletter/index');
        }

        $sent_count = 0;
        $failed_count = 0;

        foreach ($subscribers as $subscriber) {
            if ($this->Newsletter_model->send_newsletter_email($newsletter, $subscriber)) {
                $sent_count++;
            } else {
                $failed_count++;
            }
        }

        // Update newsletter status and recipients count
        $this->Newsletter_model->update_newsletter($id, [
            'status' => 'sent',
            'sent_at' => date('Y-m-d H:i:s'),
            'recipients_count' => $sent_count,
            'sent_count' => $sent_count,
            'failed_count' => $failed_count
        ]);

        $message = 'Newsletter successfully sent to ' . $sent_count . ' subscriber' . ($sent_count === 1 ? '' : 's') . '.';
        $this->session->set_flashdata('success', $message);
        redirect('newsletter/index');
    }

    // Email history
    public function email_history($action = null)
    {
        if ($action === 'export') {
            $history = $this->Newsletter_model->get_email_history([], 0);
            $this->export_email_history_csv($history);
            return;
        }

        $data['title'] = 'Email History';
        $filters = [
            'search' => $this->input->get('search'),
            'status' => $this->input->get('status'),
            'date_from' => $this->input->get('date_from'),
            'date_to' => $this->input->get('date_to')
        ];
        $history = $this->Newsletter_model->get_email_history($filters);
        $data['email_history'] = $history;
        $data['emails'] = $history;

        // Render using standard views to avoid loader method diagnostics
        $this->load->view('templates/header', $data);
        $this->load->view('newsletter/email_history', $data);
        $this->load->view('templates/footer', $data);
    }

    private function export_email_history_csv($history)
    {
        $filename = 'email_history_' . date('Y-m-d_H-i-s') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Newsletter Subject', 'Newsletter ID', 'Recipient Email', 'Recipient Name', 'Status', 'Sent Date', 'Error Message']);

        foreach ($history as $row) {
            fputcsv($output, [
                !empty($row->newsletter_subject) ? $row->newsletter_subject : $row->subject,
                $row->newsletter_id ?? 'N/A',
                $row->recipient_email,
                $row->recipient_name,
                ucfirst($row->status),
                $row->sent_at ?? $row->created_at,
                $row->error_message ?? ''
            ]);
        }

        fclose($output);
        exit;
    }

    // Delete email history log entry
    public function delete_email_log($id)
    {
        if ($this->Newsletter_model->delete_email_log($id)) {
            $this->session->set_flashdata('success', 'Email log entry deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete email log entry.');
        }
        redirect('newsletter/email_history');
    }

    // Clear all email history
    public function clear_email_history()
    {
        if ($this->Newsletter_model->clear_email_history()) {
            $this->session->set_flashdata('success', 'All email history has been cleared.');
        } else {
            $this->session->set_flashdata('error', 'Failed to clear email history.');
        }
        redirect('newsletter/email_history');
    }

    // Resend email from history
    public function resend_email($id)
    {
        $log = $this->Newsletter_model->get_email_history_by_id($id);
        if (!$log) {
            $this->session->set_flashdata('error', 'Email log entry not found.');
            redirect('newsletter/email_history');
            return;
        }

        if ($log->newsletter_id) {
            $newsletter = $this->Newsletter_model->get_newsletter_by_id($log->newsletter_id);
            if ($newsletter) {
                $recipient = new stdClass();
                $recipient->id = $log->subscriber_id;
                $recipient->email = $log->recipient_email;
                $recipient->first_name = $log->recipient_name ? explode(' ', $log->recipient_name)[0] : 'Valued';
                $recipient->last_name = '';

                $sent = $this->Newsletter_model->send_newsletter_email($newsletter, $recipient);
                if ($sent) {
                    $this->Newsletter_model->update_email_history($id, [
                        'status' => 'sent',
                        'sent_at' => date('Y-m-d H:i:s'),
                        'error_message' => null
                    ]);
                    $this->session->set_flashdata('success', 'Email successfully resent to ' . htmlspecialchars($log->recipient_email));
                } else {
                    $this->session->set_flashdata('error', 'Failed to resend email to ' . htmlspecialchars($log->recipient_email));
                }
                redirect('newsletter/email_history');
                return;
            }
        }

        $this->session->set_flashdata('error', 'Associated newsletter could not be found to resend.');
        redirect('newsletter/email_history');
    }

    // Alias for email_history (backward compatibility)
    public function history()
    {
        $this->email_history();
    }

    // Settings
    public function settings()
    {
        $data['title'] = 'Newsletter Settings';
        $data['settings'] = $this->Newsletter_model->get_all_settings();

        if ($this->input->post()) {
            $this->process_settings_form();
        }

        // Render using standard views to avoid loader method diagnostics
        $this->load->view('templates/header', $data);
        $this->load->view('newsletter/settings', $data);
        $this->load->view('templates/footer', $data);
    }

    // Process settings form
    private function process_settings_form()
    {
        // Set validation rules to match actual form field names
        $this->form_validation->set_rules('sender_email', 'Sender Email', 'required|valid_email');
        $this->form_validation->set_rules('sender_name', 'Sender Name', 'required');
        $this->form_validation->set_rules('smtp_host', 'SMTP Host', 'required');
        $this->form_validation->set_rules('smtp_port', 'SMTP Port', 'required|numeric');
        $this->form_validation->set_rules('smtp_username', 'SMTP Username', 'required');
        $this->form_validation->set_rules('smtp_password', 'SMTP Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Validation failed, redirect back with errors
            redirect('newsletter/settings');
        } else {
            // Validation passed, save settings
            $settings = $this->input->post();

            foreach ($settings as $key => $value) {
                if ($key !== 'submit') {
                    $this->Newsletter_model->update_setting($key, $value);
                }
            }

            $this->session->set_flashdata('success', 'Newsletter settings have been updated successfully!');
            redirect('newsletter/settings');
        }
    }

    // Delete newsletter
    public function delete($id)
    {
        if ($this->Newsletter_model->delete_newsletter($id)) {
            $this->session->set_flashdata('success', 'Newsletter deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Error deleting newsletter');
        }

        redirect('newsletter/index');
    }

    // Export newsletters
    public function export($format = 'csv')
    {
        $newsletters = $this->Newsletter_model->get_all_newsletters_for_export();

        if ($format === 'csv') {
            $this->export_csv($newsletters);
        }
    }

    // Export CSV
    private function export_csv($newsletters)
    {
        $filename = 'newsletters_' . date('Y-m-d_H-i-s') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // CSV headers
        fputcsv($output, ['Subject', 'Status', 'Recipients', 'Created Date', 'Sent Date']);

        // CSV data
        foreach ($newsletters as $newsletter) {
            fputcsv($output, [
                $newsletter->subject,
                ucfirst($newsletter->status),
                $newsletter->recipients_count ?? 0,
                $newsletter->created_at,
                $newsletter->sent_at
            ]);
        }

        fclose($output);
    }

    // Test email settings
    public function test_email_settings()
    {
        if (!$this->input->post('test_email')) {
            echo json_encode(['success' => false, 'message' => 'Test email address is required']);
            return;
        }

        // Get SMTP settings from POST
        $smtp_config = [
            'protocol' => 'smtp',
            'smtp_host' => $this->input->post('smtp_host'),
            'smtp_port' => $this->input->post('smtp_port'),
            'smtp_user' => $this->input->post('smtp_username'),
            'smtp_pass' => $this->input->post('smtp_password'),
            'smtp_crypto' => $this->input->post('smtp_encryption'),
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n"
        ];

        // Initialize email library with test settings
        $this->load->library('email');
        $this->email->initialize($smtp_config);

        // Send test email
        $this->email->from($this->input->post('sender_email'), $this->input->post('sender_name'));
        $this->email->to($this->input->post('test_email')); // Send to the test email address
        $this->email->subject('Newsletter System - Test Email');
        $this->email->message('<h2>Test Email Successful!</h2><p>Your SMTP settings are working correctly.</p>');

        if ($this->email->send()) {
            echo json_encode(['success' => true, 'message' => 'Test email sent successfully to ' . $this->input->post('test_email')]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to send test email: ' . $this->email->print_debugger()]);
        }
    }

    // Upload image from WYSIWYG editor
    public function upload_image()
    {
        // Check if user is logged in
        if (!$this->session->userdata('isLoggedIn')) {
            header("HTTP/1.1 403 Forbidden");
            header('Content-Type: application/json');
            echo json_encode(['error' => 'You must be logged in to upload images.']);
            return;
        }

        $upload_dir = FCPATH . 'uploads/newsletter_images/';
        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, 0777, true);
        }

        $config['upload_path'] = $upload_dir;
        $config['allowed_types'] = 'gif|jpg|png|jpeg|webp|bmp|GIF|JPG|PNG|JPEG|WEBP|BMP';
        $config['max_size'] = 20480; // 20MB
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);

        header('Content-Type: application/json');

        if ($this->upload->do_upload('file')) {
            $data = $this->upload->data();

            $public_base = 'https://newsletter.lighthouseglobalmissions.org/';
            if (!isset($_SERVER['HTTP_HOST']) || strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
                $public_base = base_url();
            }
            $file_url = rtrim($public_base, '/') . '/uploads/newsletter_images/' . $data['file_name'];

            // Return JSON response for TinyMCE
            echo json_encode(['location' => $file_url]);
        } else {
            $err = $this->upload->display_errors('', '');
            if (empty($err)) {
                $err = 'Failed to upload image. Please check file format and size.';
            }
            http_response_code(400);
            echo json_encode(['error' => $err, 'message' => $err]);
        }
    }

    // Delete subscriber
    public function delete_subscriber()
    {
        // Set content type to JSON
        header('Content-Type: application/json');

        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['id']) || empty($input['id'])) {
            echo json_encode(['success' => false, 'message' => 'Subscriber ID is required']);
            return;
        }

        $subscriber_id = $input['id'];


        // Delete subscriber using model

        // Delete subscriber using model
        $result = $this->Newsletter_model->delete_subscriber($subscriber_id);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Subscriber deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete subscriber']);
        }
    }

    // Bulk delete subscribers
    public function bulk_delete_subscribers()
    {
        // Set content type to JSON
        header('Content-Type: application/json');

        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['ids']) || empty($input['ids']) || !is_array($input['ids'])) {
            echo json_encode(['success' => false, 'message' => 'Subscriber IDs are required']);
            return;
        }

        $subscriber_ids = $input['ids'];

        // Delete subscribers using model
        $result = $this->Newsletter_model->delete_subscribers($subscriber_ids);

        if ($result) {
            echo json_encode(['success' => true, 'message' => count($subscriber_ids) . ' subscribers deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete subscribers']);
        }
    }
}
?>