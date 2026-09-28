<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TestComposer extends CI_Controller
{
    public function index()
    {
        echo "TestComposer ready.\n";
    }

    public function send_test()
    {
        $this->load->model('Newsletter_model');

        $newsletter = new stdClass();
        $newsletter->id = 9;
        $newsletter->subject = "A New Season of Faith & Ministry Updates";
        $newsletter->content = '<p>Beloved of God,</p>'
            . '<p>I am delighted to connect with you through this newsletter as we step into an exciting new season of ministry, outreach, and global impact. Thank you for standing with us in prayer, faith, and support as a valued Lighthouse Pillar.</p>'
            . '<p style="text-align: center; margin: 25px 0;">'
            . '<img src="https://newsletter.lighthouseglobalmissions.org/assets/images/header.jpg" alt="Lighthouse Global Missions Outreach" style="width: 100%; max-width: 540px; height: auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); display: block; margin: 0 auto;">'
            . '</p>'
            . '<h3 style="color: #203550; margin-top: 25px;">Walking in Faith & Divine Direction</h3>'
            . '<blockquote style="border-left: 4px solid #1da2f0; margin: 15px 0; padding-left: 15px; color: #555; font-style: italic;">'
            . '“Trust in the Lord with all your heart and lean not on your own understanding; in all your ways submit to Him, and He will make your paths straight.” &mdash; Proverbs 3:5–6'
            . '</blockquote>'
            . '<p>As we continue to expand our mission initiatives and community outreaches, we see God moving powerfully in the lives of many.</p>';
        $newsletter->sender_name = "Lighthouse Global Missions";
        $newsletter->sender_email = "pastorsimon@lgmissions.org";

        $subscriber = new stdClass();
        $subscriber->id = 52;
        $subscriber->first_name = "Emmanuel";
        $subscriber->last_name = "Taah";
        $subscriber->email = "ttemmanuel2020@gmail.com";

        echo "Sending test email to " . $subscriber->email . "...\n";
        $result = $this->Newsletter_model->send_newsletter_email($newsletter, $subscriber);

        if ($result) {
            echo "SUCCESS: Test email was sent successfully!\n";
        } else {
            echo "FAILED: Test email failed to send.\n";
            echo $this->email->print_debugger() . "\n";
        }
    }
}
