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

// Create email content
$subject = '🎉 New Order (' . count($products) . ' products)';

// Create HTML email content
$htmlContent = '
<div style="font-family: \'Segoe UI\', sans-serif; max-width:600px; margin:auto; padding:20px; border-radius:10px; background-color:#f5f5f5; color:#333;">
    <h2 style="color:#1d3557; text-align:center;">🛒 New Order Received</h2>
    <div style="margin-top:20px; padding:15px; background-color:#fff; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1);">
        <p><strong>Name:</strong> ' . htmlspecialchars($name) . '</p>
        <p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>
        <p><strong>Phone:</strong> ' . htmlspecialchars($phone) . '</p>
        <h3 style="margin-top:20px; color:#1d3557;">Products:</h3>
        <table style="width:100%; border-collapse: collapse; margin-top:10px;">
            <thead>
                <tr style="background-color:#a8dadc; color:#1d3557;">
                    <th style="border:1px solid #ddd; padding:8px; text-align:left;">Product</th>
                    <th style="border:1px solid #ddd; padding:8px; text-align:right;">Qty</th>
                    <th style="border:1px solid #ddd; padding:8px; text-align:right;">Price (SAR)</th>
                </tr>
            </thead>
            <tbody>';

foreach ($products as $product) {
    $productName = isset($product['name']) ? htmlspecialchars($product['name']) : 'Unknown Product';
    $quantity = isset($product['quantity']) ? intval($product['quantity']) : 0;
    $price = isset($product['price']) ? floatval($product['price']) : 0;
    $lineTotal = $price * $quantity;
    
    $htmlContent .= '
                <tr>
                    <td style="border:1px solid #ddd; padding:8px;">' . $productName . '</td>
                    <td style="border:1px solid #ddd; padding:8px; text-align:right;">' . $quantity . '</td>
                    <td style="border:1px solid #ddd; padding:8px; text-align:right;">' . $lineTotal . '</td>
                </tr>';
}

$htmlContent .= '
                <tr style="background-color:#e63946; color:#fff; font-weight:bold;">
                    <td colspan="2" style="border:1px solid #ddd; padding:8px; text-align:right;">Total</td>
                    <td style="border:1px solid #ddd; padding:8px; text-align:right;">' . $totalAmount . '</td>
                </tr>
            </tbody>
        </table>
    </div>
    <p style="margin-top:30px; text-align:center; color:#555; font-size:14px;">Thank you for your order! 🌟</p>
</div>';

// Email configuration for Gmail SMTP
$to = 'zirkonalab@gmail.com';
$from = 'zirkonalab@gmail.com';
$fromName = 'Zirkona Lab';

// Email headers for HTML
$headers = array(
    'MIME-Version: 1.0',
    'Content-type: text/html; charset=UTF-8',
    'From: ' . $fromName . ' <' . $from . '>',
    'Reply-To: ' . $email,
    'X-Mailer: PHP/' . phpversion(),
    'X-Priority: 1',
    'Importance: High'
);

// Try to send email
$emailSent = false;
$errorMessage = '';

try {
    // Method 1: Try with mail() function
    if (mail($to, $subject, $htmlContent, implode("\r\n", $headers))) {
        $emailSent = true;
    }
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
}

// Always log to file as backup
$logFile = 'orders_log.txt';
$logEntry = "=== NEW ORDER ===\n";
$logEntry .= "Date: " . date('Y-m-d H:i:s') . "\n";
$logEntry .= "Subject: " . $subject . "\n";
$logEntry .= "Customer: " . $name . "\n";
$logEntry .= "Email: " . $email . "\n";
$logEntry .= "Phone: " . $phone . "\n";
$logEntry .= "Products Count: " . count($products) . "\n";
$logEntry .= "Total Amount: " . $totalAmount . " SAR\n";
$logEntry .= "\nProducts Details:\n";
foreach ($products as $product) {
    $logEntry .= "- " . $product['name'] . " (Qty: " . $product['quantity'] . ", Unit Price: " . $product['price'] . " SAR, Total: " . ($product['price'] * $product['quantity']) . " SAR)\n";
}
$logEntry .= "\nEmail Status: " . ($emailSent ? 'Sent Successfully' : 'Failed to Send') . "\n";
$logEntry .= "Server: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
$logEntry .= "IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . "\n";
$logEntry .= str_repeat('=', 60) . "\n\n";

try {
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
} catch (Exception $e) {
    $errorMessage .= ' | File write failed: ' . $e->getMessage();
}

// Always return success to user
echo json_encode([
    'message' => 'Order received successfully',
    'products' => count($products),
    'total' => $totalAmount . ' SAR',
    'email_sent' => $emailSent,
    'logged' => file_exists($logFile)
]);
?>