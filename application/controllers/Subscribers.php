<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Subscribers
 * Subscribers Class to control subscriber management operations
 */
class Subscribers extends BaseController {

    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        $this->load->model('Newsletter_model');
    }

    public function index(){
        // Get filter parameters
        $search = $this->input->get('search');
        $status = $this->input->get('status') ?: 'all';
        $date_range = $this->input->get('date_range') ?: 'all';
        
        // Get subscriber statistics
        $data['stats'] = $this->Newsletter_model->get_subscriber_stats();
        
        // Get filtered subscribers
        $data['subscribers'] = $this->Newsletter_model->get_subscribers_by_filter($search, $status, $date_range);
        
        $this->load->template('subscribers/manage', $data); // Custom template method from MY_Loader
    }

    public function delete(){
        if($this->input->post('subscriber_ids')){
            $subscriber_ids = $this->input->post('subscriber_ids');
            $result = $this->Newsletter_model->delete_subscribers($subscriber_ids);
            
            if($result){
                $this->session->set_flashdata('success', 'Selected subscribers deleted successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to delete subscribers.');
            }
        }
        
        redirect('subscribers');
    }

    public function resend_confirmation(){
        if($this->input->post('subscriber_ids')){
            $subscriber_ids = $this->input->post('subscriber_ids');
            $result = $this->Newsletter_model->resend_confirmation_emails($subscriber_ids);
            
            if($result){
                $this->session->set_flashdata('success', 'Confirmation emails sent successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to send confirmation emails.');
            }
        }
        
        redirect('subscribers');
    }

    public function export(){
        $search = $this->input->get('search');
        $status = $this->input->get('status') ?: 'all';
        $date_range = $this->input->get('date_range') ?: 'all';
        
        $subscribers = $this->Newsletter_model->get_subscribers_by_filter($search, $status, $date_range);
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="subscribers_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, array('Name', 'Email', 'Status', 'Subscribed Date'));
        
        // Add subscriber data
        foreach($subscribers as $subscriber){
            fputcsv($output, array(
                $subscriber['name'],
                $subscriber['email'],
                $subscriber['status'],
                $subscriber['created_at']
            ));
        }
        
        fclose($output);
    }

    public function add(){
        $first_name = trim($this->input->post('first_name'));
        $last_name = trim($this->input->post('last_name'));
        $email = trim($this->input->post('email'));

        if (empty($first_name) || empty($last_name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->set_flashdata('error', 'Please provide a valid first name, last name, and email.');
            redirect('subscribers');
            return;
        }

        $ip_address = $this->input->ip_address();
        $user_agent = $this->input->user_agent();

        $result = $this->Newsletter_model->add_subscriber_confirmed($first_name, $last_name, $email, $ip_address, $user_agent);

        if ($result) {
            $this->session->set_flashdata('success', 'Subscriber added and verified successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to add subscriber. The email may already be verified.');
        }

        redirect('subscribers');
    }
}