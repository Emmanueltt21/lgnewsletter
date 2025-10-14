<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/BaseController.php';

/**
 * Class : Newsletter
 * Newsletter Class to control newsletter management operations
 */
class Newsletter extends BaseController {

    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        $this->load->model('Newsletter_model');
        $this->load->library('form_validation');
    }

    // Newsletter management
    public function index() {
        $data['title'] = 'Newsletter Management';
        $data['newsletters'] = $this->Newsletter_model->get_newsletters();
        $data['stats'] = $this->Newsletter_model->get_newsletter_stats();
        
        // Render using standard views to avoid loader method diagnostics
        $this->load->view('templates/header', $data);
        $this->load->view('newsletter/manage', $data);
        $this->load->view('templates/footer', $data);
    }

    // Compose newsletter
    public function compose($id = null) {
        $data['title'] = $id ? 'Edit Newsletter' : 'Compose Newsletter';
        $data['newsletter'] = $id ? $this->Newsletter_model->get_newsletter_by_id($id) : null;
        $data['settings'] = $this->Newsletter_model->get_email_settings();
        
        if ($this->input->post()) {
            $this->process_newsletter_form($id);
        }
        
        // Render using standard views to avoid loader method diagnostics
        $this->load->view('templates/header', $data);
        $this->load->view('newsletter/compose', $data);
        $this->load->view('templates/footer', $data);
    }

    // Process newsletter form
    private function process_newsletter_form($id = null) {
        $subject = $this->input->post('subject');
        $content = $this->input->post('content');
        $action = $this->input->post('action');
        
        // Resolve created_by to a valid admin_users.id (or NULL if unknown)
        $sessionUser = $this->session->userdata('userId');
        $createdBy = null;
        if (is_numeric($sessionUser)) {
            $createdBy = (int)$sessionUser;
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
            'status' => ($action === 'send') ? 'sent' : 'draft',
            'created_by' => $createdBy
        ];
        
        if ($id) {
            // Update existing newsletter
            $newsletter_data['updated_at'] = date('Y-m-d H:i:s');
            $this->Newsletter_model->update_newsletter($id, $newsletter_data);
            $newsletter_id = $id;
        } else {
            // Create new newsletter
            $newsletter_id = $this->Newsletter_model->create_newsletter($newsletter_data);
        }
        
        if ($action === 'send') {
            $this->send($newsletter_id);
            return;
        }

        $message = $id ? 'Newsletter updated successfully' : 'Newsletter created successfully';
        $this->session->set_flashdata('success', $message);
        redirect('newsletter/index');
    }

    // Send newsletter
    public function send($id) {
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
            'recipients_count' => $sent_count
        ]);
        
        // Use the exact message specified
        $message = 'Newsletter successfully sent';
        $this->session->set_flashdata('success', $message);
        redirect('newsletter/index');
    }

    // Email history
    public function email_history() {
        $data['title'] = 'Email History';
        $data['emails'] = $this->Newsletter_model->get_email_history();
        
        // Render using standard views to avoid loader method diagnostics
        $this->load->view('templates/header', $data);
        $this->load->view('newsletter/email_history', $data);
        $this->load->view('templates/footer', $data);
    }

    // Alias for email_history (backward compatibility)
    public function history() {
        $this->email_history();
    }

    // Settings
    public function settings() {
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
    private function process_settings_form() {
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
    public function delete($id) {
        if ($this->Newsletter_model->delete_newsletter($id)) {
            $this->session->set_flashdata('success', 'Newsletter deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Error deleting newsletter');
        }

        redirect('newsletter/index');
    }

    // Export newsletters
    public function export($format = 'csv') {
        $newsletters = $this->Newsletter_model->get_all_newsletters_for_export();
        
        if ($format === 'csv') {
            $this->export_csv($newsletters);
        }
    }

    // Export CSV
    private function export_csv($newsletters) {
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
    public function test_email_settings() {
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

    // Delete subscriber
    public function delete_subscriber() {
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
        $result = $this->Newsletter_model->delete_subscriber($subscriber_id);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Subscriber deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete subscriber']);
        }
    }

    // Bulk delete subscribers
    public function bulk_delete_subscribers() {
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