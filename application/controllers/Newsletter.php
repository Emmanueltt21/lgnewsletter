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
        
        $this->load->template('newsletter/manage', $data);
    }

    // Compose newsletter
    public function compose($id = null) {
        $data['title'] = $id ? 'Edit Newsletter' : 'Compose Newsletter';
        $data['newsletter'] = $id ? $this->Newsletter_model->get_newsletter_by_id($id) : null;
        $data['settings'] = $this->Newsletter_model->get_email_settings();
        
        if ($this->input->post()) {
            $this->process_newsletter_form($id);
        }
        
        $this->load->template('newsletter/compose', $data);
    }

    // Process newsletter form
    private function process_newsletter_form($id = null) {
        $subject = $this->input->post('subject');
        $content = $this->input->post('content');
        $action = $this->input->post('action');
        
        $newsletter_data = [
            'subject' => $subject,
            'content' => $content,
            'sender_name' => $this->input->post('sender_name'),
            'sender_email' => $this->input->post('sender_email'),
            'status' => ($action === 'send') ? 'sent' : 'draft',
            'created_by' => $this->session->userdata('userId')
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
        }
        
        $message = $id ? 'Newsletter updated successfully' : 'Newsletter created successfully';
        if ($action === 'send') {
            $message .= ' and sent to subscribers';
        }
        
        $this->session->set_flashdata('success', $message);
        redirect('newsletter');
    }

    // Send newsletter
    public function send($id) {
        $newsletter = $this->Newsletter_model->get_newsletter_by_id($id);
        $subscribers = $this->Newsletter_model->get_confirmed_subscribers();
        
        if (!$newsletter || !$subscribers) {
            $this->session->set_flashdata('error', 'Newsletter or subscribers not found');
            redirect('newsletter');
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
        
        $message = "Newsletter sent successfully to {$sent_count} subscribers";
        if ($failed_count > 0) {
            $message .= " ({$failed_count} failed)";
        }
        
        $this->session->set_flashdata('success', $message);
        redirect('newsletter');
    }

    // Email history
    public function email_history() {
        $data['title'] = 'Email History';
        $data['emails'] = $this->Newsletter_model->get_email_history();
        
        $this->load->template('newsletter/email_history', $data);
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
        
        $this->load->template('newsletter/settings', $data);
    }

    // Process settings form
    private function process_settings_form() {
        // Set validation rules
        $this->form_validation->set_rules('sender_email', 'Sender Email', 'required|valid_email');
        $this->form_validation->set_rules('sender_name', 'Sender Name', 'required');
        $this->form_validation->set_rules('smtp_host', 'SMTP Host', 'required');
        $this->form_validation->set_rules('smtp_port', 'SMTP Port', 'required|numeric');
        $this->form_validation->set_rules('smtp_username', 'SMTP Username', 'required');
        
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
            
            $this->session->set_flashdata('success', 'Settings updated successfully');
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
        
        redirect('newsletter');
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
            show_404();
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
        $this->email->to($this->input->post('sender_email')); // Send to sender email for testing
        $this->email->subject('Newsletter System - Test Email');
        $this->email->message('<h2>Test Email Successful!</h2><p>Your SMTP settings are working correctly.</p>');
        
        if ($this->email->send()) {
            echo json_encode(['success' => true, 'message' => 'Test email sent successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => $this->email->print_debugger()]);
        }
    }
}
?>