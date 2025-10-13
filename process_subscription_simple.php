<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set content type to JSON
header('Content-Type: application/json');

function sendResponse($success, $message, $data = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Only POST requests are allowed');
}

// Validate required fields
$required_fields = ['first_name', 'last_name', 'email'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        sendResponse(false, "Field '$field' is required");
    }
}

// Validate email format
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    sendResponse(false, 'Please enter a valid email address');
}

try {
    // Include the Newsletter wrapper class
    require_once 'newsletter_wrapper.php';
    
    // Create an instance of the wrapper
    $newsletter = new NewsletterWrapper();
    
    // Call the subscribe method
    $result = $newsletter->subscribe(
        trim($_POST['first_name']),
        trim($_POST['last_name']),
        trim(strtolower($_POST['email']))
    );
    
    // Return the result as JSON
    echo json_encode($result);
    
} catch (Exception $e) {
    // Log the error for debugging
    file_put_contents('debug.log', "Exception in process_subscription_simple: " . $e->getMessage() . "\n", FILE_APPEND);
    sendResponse(false, 'An error occurred. Please try again later.');
}
?>