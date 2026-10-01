<?php
define('ENVIRONMENT', 'development');
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
ob_start();
require 'index.php';
ob_end_clean();
$CI =& get_instance();
$CI->load->model('Newsletter_model');
$subscriber = $CI->Newsletter_model->get_subscriber_by_id(16); // Using one of the pending IDs
$result = $CI->Newsletter_model->resend_confirmation_email($subscriber);
var_dump($result);
