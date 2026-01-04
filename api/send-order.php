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

// Email configuration
$to = 'zirkonalab@gmail.com';
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

// Email headers
$headers = array(
    'MIME-Version: 1.0',
    'Content-type: text/html; charset=UTF-8',
    'From: zirkonalab@gmail.com',
    'Reply-To: zirkonalab@gmail.com',
    'X-Mailer: PHP/' . phpversion()
);

// Send email
$mailSent = mail($to, $subject, $htmlContent, implode("\r\n", $headers));

if ($mailSent) {
    echo json_encode(['message' => 'Order sent successfully']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Order received but email failed', 'error' => 'Mail function failed']);
}
?>