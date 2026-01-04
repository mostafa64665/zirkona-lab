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

// Extract data
$name = isset($data['name']) ? $data['name'] : 'No Name';
$email = isset($data['email']) ? $data['email'] : 'noemail@example.com';
$phone = isset($data['phone']) ? $data['phone'] : 'N/A';
$products = isset($data['products']) ? $data['products'] : [];

// Validate products
if (empty($products)) {
    echo json_encode(['message' => 'Order received but no valid products', 'skipped' => true]);
    exit();
}

// Calculate total
$totalAmount = 0;
foreach ($products as $product) {
    if (isset($product['price']) && isset($product['quantity'])) {
        $totalAmount += $product['price'] * $product['quantity'];
    }
}

// Always log to file first
$logFile = 'orders_log.txt';
$logEntry = date('Y-m-d H:i:s') . " - New Order (" . count($products) . " products)\n";
$logEntry .= "Customer: " . $name . " (" . $email . ", " . $phone . ")\n";
$logEntry .= "Products: " . count($products) . " items, Total: " . $totalAmount . " SAR\n";
foreach ($products as $product) {
    $logEntry .= "- " . $product['name'] . " x" . $product['quantity'] . " = " . ($product['price'] * $product['quantity']) . " SAR\n";
}
$logEntry .= str_repeat('-', 50) . "\n\n";

$logged = false;
try {
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    $logged = true;
} catch (Exception $e) {
    // Continue even if logging fails
}

// Try SMTP using Gmail
$emailSent = false;
$errorMessage = '';

try {
    // Use Gmail SMTP via fsockopen
    $smtp_server = "smtp.gmail.com";
    $smtp_port = 587;
    $smtp_username = "zirkonalab@gmail.com";
    $smtp_password = "zrot oama bkvv aplk";
    
    // Create email content
    $to = "zirkonalab@gmail.com";
    $subject = "=?UTF-8?B?" . base64_encode("🎉 New Order (" . count($products) . " products)") . "?=";
    
    $message = "MIME-Version: 1.0\r\n";
    $message .= "Content-Type: text/html; charset=UTF-8\r\n";
    $message .= "From: Zirkona Lab <zirkonalab@gmail.com>\r\n";
    $message .= "To: $to\r\n";
    $message .= "Subject: $subject\r\n\r\n";
    
    $htmlContent = '<div style="font-family: Arial, sans-serif; max-width:600px; margin:auto; padding:20px; background-color:#f5f5f5; color:#333;">';
    $htmlContent .= '<h2 style="color:#1d3557; text-align:center;">🛒 New Order Received</h2>';
    $htmlContent .= '<div style="margin-top:20px; padding:15px; background-color:#fff; border-radius:8px;">';
    $htmlContent .= '<p><strong>Name:</strong> ' . htmlspecialchars($name) . '</p>';
    $htmlContent .= '<p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>';
    $htmlContent .= '<p><strong>Phone:</strong> ' . htmlspecialchars($phone) . '</p>';
    $htmlContent .= '<h3 style="color:#1d3557;">Products:</h3>';
    $htmlContent .= '<table style="width:100%; border-collapse: collapse;">';
    $htmlContent .= '<tr style="background-color:#a8dadc; color:#1d3557;">';
    $htmlContent .= '<th style="border:1px solid #ddd; padding:8px;">Product</th>';
    $htmlContent .= '<th style="border:1px solid #ddd; padding:8px;">Qty</th>';
    $htmlContent .= '<th style="border:1px solid #ddd; padding:8px;">Price (SAR)</th>';
    $htmlContent .= '</tr>';
    
    foreach ($products as $product) {
        $htmlContent .= '<tr>';
        $htmlContent .= '<td style="border:1px solid #ddd; padding:8px;">' . htmlspecialchars($product['name']) . '</td>';
        $htmlContent .= '<td style="border:1px solid #ddd; padding:8px; text-align:center;">' . $product['quantity'] . '</td>';
        $htmlContent .= '<td style="border:1px solid #ddd; padding:8px; text-align:right;">' . ($product['price'] * $product['quantity']) . '</td>';
        $htmlContent .= '</tr>';
    }
    
    $htmlContent .= '<tr style="background-color:#e63946; color:#fff; font-weight:bold;">';
    $htmlContent .= '<td colspan="2" style="border:1px solid #ddd; padding:8px; text-align:right;">Total</td>';
    $htmlContent .= '<td style="border:1px solid #ddd; padding:8px; text-align:right;">' . $totalAmount . '</td>';
    $htmlContent .= '</tr>';
    $htmlContent .= '</table>';
    $htmlContent .= '</div>';
    $htmlContent .= '<p style="margin-top:30px; text-align:center; color:#555;">Thank you for your order! 🌟</p>';
    $htmlContent .= '</div>';
    
    $message .= $htmlContent;
    
    // Try simple mail() first
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Zirkona Lab <zirkonalab@gmail.com>\r\n";
    $headers .= "Reply-To: $email\r\n";
    
    if (mail($to, "New Order (" . count($products) . " products)", $htmlContent, $headers)) {
        $emailSent = true;
    }
    
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
}

// Return response
echo json_encode([
    'message' => 'Order received successfully',
    'products' => count($products),
    'total' => $totalAmount . ' SAR',
    'email_sent' => $emailSent,
    'logged' => $logged,
    'log_file' => $logFile,
    'debug' => $errorMessage ?: 'No errors'
]);
?>