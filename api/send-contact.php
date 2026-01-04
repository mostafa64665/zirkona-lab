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

// Email configuration
$to = 'zirkonalab@gmail.com';
$subject = '📩 New Contact Message from ' . $name;

// Create HTML email content
$htmlContent = '
<div style="font-family: \'Segoe UI\', sans-serif; max-width:600px; margin:auto; padding:20px; border-radius:10px; background-color:#f5f5f5; color:#333;">
    <h2 style="color:#1d3557; text-align:center;">📩 New Contact Message</h2>
    <div style="margin-top:20px; padding:15px; background-color:#fff; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1);">
        <p><strong>Name:</strong> ' . htmlspecialchars($name) . '</p>
        <p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>
        <p><strong>Phone:</strong> ' . htmlspecialchars($phone ?: 'N/A') . '</p>
        <p><strong>Message:</strong><br>' . nl2br(htmlspecialchars($message)) . '</p>
    </div>
    <p style="margin-top:30px; text-align:center; color:#555; font-size:14px;">Thank you for contacting us! 🌟</p>
</div>';

// Email headers
$headers = array(
    'MIME-Version: 1.0',
    'Content-type: text/html; charset=UTF-8',
    'From: Zirkona Lab <zirkonalab@gmail.com>',
    'Reply-To: ' . $email,
    'X-Mailer: PHP/' . phpversion(),
    'X-Priority: 1',
    'Importance: High'
);

// Send email
$mailSent = false;
try {
    $mailSent = mail($to, $subject, $htmlContent, implode("\r\n", $headers));
} catch (Exception $e) {
    // Log error but don't fail
}

// Always log to file as backup
$logFile = 'contact_log.txt';
$logEntry = date('Y-m-d H:i:s') . " - Contact from: " . $name . " (" . $email . ")\n";
$logEntry .= "Phone: " . ($phone ?: 'N/A') . "\n";
$logEntry .= "Message: " . $message . "\n";
$logEntry .= str_repeat('-', 50) . "\n\n";

try {
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
} catch (Exception $e) {
    // Ignore file write errors
}

// Always return success
echo json_encode([
    'message' => 'Contact message received successfully',
    'email_sent' => $mailSent,
    'logged' => file_exists($logFile)
]);
?>