<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
class Dashboard extends BaseController {

  public function __construct()
     {
        // Ensure you run parent constructor
        parent::__construct();
        $this->isLoggedIn();
        $this->load->model('media_model');
        $this->load->model('account_model');
        $this->load->model('user_model');
        $this->load->model('comments_model');
        $this->load->model('Newsletter_model');
        
        
     }

    public function index(){
      $data['audios'] = $this->media_model->get_total_media("audio");
      $data['videos'] = $this->media_model->get_total_media("video");
      $data['users'] = $this->account_model->get_total_users();
      $data['admin'] = $this->user_model->get_total_admin();
      $data['comments'] = $this->comments_model->get_total_user_comments();
      $data['reports'] = $this->comments_model->get_total_reports();
      
      // Add newsletter statistics
      $newsletter_stats = $this->Newsletter_model->get_dashboard_stats();
      $data['newsletter_subscribers'] = $newsletter_stats['total_subscribers'];
      $data['newsletter_confirmed'] = $newsletter_stats['confirmed_subscribers'];
      $data['newsletter_pending'] = $newsletter_stats['pending_confirmations'];
      $data['newsletter_sent'] = $newsletter_stats['sent_newsletters'];
      
      // Add recent newsletter data for integrated dashboard
      $data['recent_subscribers'] = $this->Newsletter_model->get_recent_subscribers(5);
      $data['recent_newsletters'] = $this->Newsletter_model->get_recent_newsletters(5);
      
      $this->load->template('dashboard', $data); // this will load the view file
    }

    public function subscribers(){
      // Get filter parameters
      $search = $this->input->get('search');
      $status = $this->input->get('status') ?: 'all';
      $date_range = $this->input->get('date_range') ?: 'all';
      
      // Get subscriber statistics
      $data['stats'] = $this->Newsletter_model->get_subscriber_stats();
      
      // Get filtered subscribers
      $data['subscribers'] = $this->Newsletter_model->get_subscribers_by_filter($search, $status, $date_range);
      
      $this->load->template('subscribers/manage', $data); // this will load the view file
    }
}
