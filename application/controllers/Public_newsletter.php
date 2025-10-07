<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class : Public_newsletter
 * Public Newsletter Class to handle public newsletter subscription page
 */
class Public_newsletter extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // No authentication required for public pages
    }

    /**
     * Display the public newsletter subscription page
     */
    public function index() {
        // Load the newsletter.php content directly
        $newsletter_file = FCPATH . 'newsletter.php';
        
        if (file_exists($newsletter_file)) {
            // Include the newsletter.php file content
            include($newsletter_file);
        } else {
            show_404();
        }
    }
}