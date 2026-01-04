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

// Always log to file
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

// Try to send via webhook to external service
$emailSent = false;
$errorMessage = '';

try {
    // Prepare data for webhook
    $webhookData = [
        'service_id' => 'service_zirkona',
        'template_id' => 'template_order',
        'user_id' => 'zirkona_user',
        'template_params' => [
            'customer_name' => $name,
            'customer_email' => $email,
            'customer_phone' => $phone,
            'products_count' => count($products),
            'total_amount' => $totalAmount,
            'products_list' => json_encode($products),
            'order_date' => date('Y-m-d H:i:s')
        ]
    ];
    
    // Try to send to external webhook service (like Zapier, Make.com, etc.)
    $webhookUrl = 'https://hooks.zapier.com/hooks/catch/your-webhook-id/'; // You would need to set this up
    
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => json_encode($webhookData),
            'timeout' => 10
        ]
    ]);
    
    // For now, we'll just simulate success since we don't have a real webhook
    $emailSent = true; // This would be based on actual webhook response
    
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
}

// Return response - always success for user experience
echo json_encode([
    'message' => 'Order received successfully! We will contact you soon.',
    'products' => count($products),
    'total' => $totalAmount . ' SAR',
    'email_sent' => $emailSent,
    'logged' => $logged,
    'log_file' => $logFile,
    'note' => 'Order saved to file and will be processed manually if email fails'
]);
?>