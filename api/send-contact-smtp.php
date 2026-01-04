<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// Get POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON data']);
    exit();
}

// Extract and validate data
$name = isset($data['name']) ? trim($data['name']) : '';
$email = isset($data['email']) ? trim($data['email']) : '';
$phone = isset($data['phone']) ? trim($data['phone']) : '';
$message = isset($data['message']) ? trim($data['message']) : '';

if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit();
}

// Always log to file first
$logFile = 'contact_log.txt';
$logEntry = date('Y-m-d H:i:s') . " - Contact from: " . $name . " (" . $email . ")\n";
$logEntry .= "Phone: " . ($phone ?: 'N/A') . "\n";
$logEntry .= "Message: " . $message . "\n";
$logEntry .= str_repeat('-', 50) . "\n\n";

$logged = false;
try {
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    $logged = true;
} catch (Exception $e) {
    // Continue even if logging fails
}

// Try to send email
$emailSent = false;
$errorMessage = '';

try {
    $to = 'zirkonalab@gmail.com';
    $subject = 'New Contact Message from ' . $name;
    
    $htmlContent = '<div style="font-family: Arial, sans-serif; max-width:600px; margin:auto; padding:20px; background-color:#f5f5f5; color:#333;">';
    $htmlContent .= '<h2 style="color:#1d3557; text-align:center;">📩 New Contact Message</h2>';
    $htmlContent .= '<div style="margin-top:20px; padding:15px; background-color:#fff; border-radius:8px;">';
    $htmlContent .= '<p><strong>Name:</strong> ' . htmlspecialchars($name) . '</p>';
    $htmlContent .= '<p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>';
    $htmlContent .= '<p><strong>Phone:</strong> ' . htmlspecialchars($phone ?: 'N/A') . '</p>';
    $htmlContent .= '<p><strong>Message:</strong><br>' . nl2br(htmlspecialchars($message)) . '</p>';
    $htmlContent .= '</div>';
    $htmlContent .= '<p style="margin-top:30px; text-align:center; color:#555;">Thank you for contacting us! 🌟</p>';
    $htmlContent .= '</div>';
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Zirkona Lab <zirkonalab@gmail.com>\r\n";
    $headers .= "Reply-To: $email\r\n";
    
    if (mail($to, $subject, $htmlContent, $headers)) {
        $emailSent = true;
    }
    
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
}

// Return response
echo json_encode([
    'message' => 'Contact message received successfully',
    'email_sent' => $emailSent,
    'logged' => $logged,
    'log_file' => $logFile,
    'debug' => $errorMessage ?: 'No errors'
]);
?>