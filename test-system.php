<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار النظام - Zirkona Lab</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f5f5f5;
            direction: rtl;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .test-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .success { background: #d4edda; border-color: #c3e6cb; }
        .error { background: #f8d7da; border-color: #f5c6cb; }
        .warning { background: #fff3cd; border-color: #ffeaa7; }
        .btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }
        .btn:hover { background: #0056b3; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 5px; overflow-x: auto; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea { 
            width: 100%; 
            padding: 8px; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 اختبار النظام - Zirkona Lab</h1>
        
        <?php
        // Test 1: PHP Basic Info
        echo '<div class="test-section success">';
        echo '<h2>✅ 1. معلومات PHP الأساسية</h2>';
        echo '<p><strong>إصدار PHP:</strong> ' . phpversion() . '</p>';
        echo '<p><strong>الخادم:</strong> ' . ($_SERVER['SERVER_SOFTWARE'] ?? 'غير معروف') . '</p>';
        echo '<p><strong>الوقت:</strong> ' . date('Y-m-d H:i:s') . '</p>';
        echo '<p><strong>وظيفة mail():</strong> ' . (function_exists('mail') ? '✅ متاحة' : '❌ غير متاحة') . '</p>';
        echo '</div>';
        
        // Test 2: File System
        echo '<div class="test-section">';
        echo '<h2>📁 2. اختبار نظام الملفات</h2>';
        
        $api_dir = 'api/';
        $orders_file = $api_dir . 'orders_log.txt';
        $contact_file = $api_dir . 'contact_log.txt';
        
        if (!is_dir($api_dir)) {
            mkdir($api_dir, 0755, true);
            echo '<p>✅ تم إنشاء مجلد api/</p>';
        }
        
        $writable = is_writable($api_dir);
        echo '<p><strong>مجلد API قابل للكتابة:</strong> ' . ($writable ? '✅ نعم' : '❌ لا') . '</p>';
        
        if ($writable) {
            // Test writing
            $test_content = "Test entry - " . date('Y-m-d H:i:s') . "\n";
            if (file_put_contents($orders_file, $test_content, FILE_APPEND | LOCK_EX)) {
                echo '<p>✅ تم اختبار الكتابة في ملف الطلبات</p>';
            } else {
                echo '<p>❌ فشل في الكتابة في ملف الطلبات</p>';
            }
        }
        
        echo '<p><strong>ملف الطلبات:</strong> ' . (file_exists($orders_file) ? '✅ موجود' : '❌ غير موجود') . '</p>';
        echo '<p><strong>ملف الرسائل:</strong> ' . (file_exists($contact_file) ? '✅ موجود' : '❌ غير موجود') . '</p>';
        echo '</div>';
        
        // Test 3: Email Test Form
        if (isset($_POST['test_email'])) {
            echo '<div class="test-section">';
            echo '<h2>📧 3. نتيجة اختبار الإيميل</h2>';
            
            $to = 'zirkonalab@gmail.com';
            $subject = 'اختبار النظام - ' . date('Y-m-d H:i:s');
            $message = "هذا اختبار للنظام\n\nالوقت: " . date('Y-m-d H:i:s') . "\nالخادم: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'غير معروف');
            $headers = "From: zirkonalab@gmail.com\r\nContent-Type: text/plain; charset=UTF-8\r\n";
            
            if (mail($to, $subject, $message, $headers)) {
                echo '<p class="success">✅ تم إرسال الإيميل بنجاح!</p>';
            } else {
                echo '<p class="error">❌ فشل في إرسال الإيميل</p>';
            }
            echo '</div>';
        }
        
        // Test 4: Order Test Form
        if (isset($_POST['test_order'])) {
            echo '<div class="test-section">';
            echo '<h2>🛒 4. نتيجة اختبار الطلب</h2>';
            
            $order_data = [
                'name' => $_POST['customer_name'] ?? 'عميل تجريبي',
                'email' => $_POST['customer_email'] ?? 'test@example.com',
                'phone' => $_POST['customer_phone'] ?? '0501234567',
                'products' => [
                    [
                        'name' => 'All-ceramic veneer (3D)',
                        'quantity' => 2,
                        'price' => 750
                    ]
                ]
            ];
            
            // Log the order
            $log_entry = "=== NEW ORDER ===\n";
            $log_entry .= "Date: " . date('Y-m-d H:i:s') . "\n";
            $log_entry .= "Customer: " . $order_data['name'] . "\n";
            $log_entry .= "Email: " . $order_data['email'] . "\n";
            $log_entry .= "Phone: " . $order_data['phone'] . "\n";
            $log_entry .= "Products Count: " . count($order_data['products']) . "\n";
            $log_entry .= "Total Amount: 1500 SAR\n";
            $log_entry .= "\nProducts Details:\n";
            foreach ($order_data['products'] as $product) {
                $log_entry .= "- " . $product['name'] . " (Qty: " . $product['quantity'] . ", Unit Price: " . $product['price'] . " SAR, Total: " . ($product['price'] * $product['quantity']) . " SAR)\n";
            }
            $log_entry .= "\nEmail Status: Test Order\n";
            $log_entry .= str_repeat('=', 60) . "\n\n";
            
            if (file_put_contents($orders_file, $log_entry, FILE_APPEND | LOCK_EX)) {
                echo '<p class="success">✅ تم حفظ الطلب التجريبي بنجاح!</p>';
                echo '<pre>' . htmlspecialchars($log_entry) . '</pre>';
            } else {
                echo '<p class="error">❌ فشل في حفظ الطلب</p>';
            }
            echo '</div>';
        }
        
        // Test 5: Read Orders
        echo '<div class="test-section">';
        echo '<h2>📋 5. قراءة الطلبات المحفوظة</h2>';
        
        if (file_exists($orders_file)) {
            $orders_content = file_get_contents($orders_file);
            $order_count = substr_count($orders_content, '=== NEW ORDER ===');
            echo '<p><strong>عدد الطلبات:</strong> ' . $order_count . '</p>';
            
            if (!empty($orders_content)) {
                echo '<h4>آخر 500 حرف من ملف الطلبات:</h4>';
                echo '<pre>' . htmlspecialchars(substr($orders_content, -500)) . '</pre>';
            }
        } else {
            echo '<p class="warning">⚠️ لا يوجد ملف طلبات حتى الآن</p>';
        }
        echo '</div>';
        ?>
        
        <!-- Email Test Form -->
        <div class="test-section">
            <h2>📧 اختبار الإيميل</h2>
            <form method="POST">
                <button type="submit" name="test_email" class="btn">إرسال إيميل تجريبي</button>
            </form>
        </div>
        
        <!-- Order Test Form -->
        <div class="test-section">
            <h2>🛒 اختبار الطلبات</h2>
            <form method="POST">
                <div class="form-group">
                    <label>اسم العميل:</label>
                    <input type="text" name="customer_name" value="أحمد محمد" required>
                </div>
                <div class="form-group">
                    <label>الإيميل:</label>
                    <input type="email" name="customer_email" value="ahmed@example.com" required>
                </div>
                <div class="form-group">
                    <label>الهاتف:</label>
                    <input type="text" name="customer_phone" value="0501234567" required>
                </div>
                <button type="submit" name="test_order" class="btn">إنشاء طلب تجريبي</button>
            </form>
        </div>
        
        <!-- API Test -->
        <div class="test-section">
            <h2>🔗 اختبار API</h2>
            <button onclick="testAPI()" class="btn">اختبار API الطلبات</button>
            <div id="api-result"></div>
        </div>
        
        <!-- Navigation -->
        <div class="test-section">
            <h2>🔗 روابط مفيدة</h2>
            <a href="admin/" class="btn">لوحة الإدارة</a>
            <a href="admin/panel.php" class="btn">لوحة التحكم</a>
            <a href="admin/health.php" class="btn">فحص النظام</a>
            <a href="status.php" class="btn">حالة الخادم</a>
        </div>
    </div>

    <script>
        async function testAPI() {
            const resultDiv = document.getElementById('api-result');
            resultDiv.innerHTML = '<p>جاري الاختبار...</p>';
            
            const testOrder = {
                name: 'عميل تجريبي API',
                email: 'api-test@example.com',
                phone: '0501234567',
                products: [
                    {
                        name: 'All-ceramic veneer (3D)',
                        quantity: 1,
                        price: 750
                    }
                ]
            };
            
            try {
                // Test different endpoints
                const endpoints = [
                    'api/send-order-smtp.php',
                    'api/send-order.php'
                ];
                
                let results = [];
                
                for (const endpoint of endpoints) {
                    try {
                        const response = await fetch(endpoint, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(testOrder)
                        });
                        
                        const data = await response.json();
                        results.push({
                            endpoint: endpoint,
                            status: response.status,
                            success: response.ok,
                            data: data
                        });
                    } catch (error) {
                        results.push({
                            endpoint: endpoint,
                            success: false,
                            error: error.message
                        });
                    }
                }
                
                let html = '<h4>نتائج اختبار API:</h4>';
                results.forEach(result => {
                    const status = result.success ? '✅' : '❌';
                    html += `<p><strong>${status} ${result.endpoint}</strong></p>`;
                    if (result.data) {
                        html += `<pre>${JSON.stringify(result.data, null, 2)}</pre>`;
                    }
                    if (result.error) {
                        html += `<p style="color: red;">خطأ: ${result.error}</p>`;
                    }
                });
                
                resultDiv.innerHTML = html;
                
            } catch (error) {
                resultDiv.innerHTML = `<p style="color: red;">خطأ في الاختبار: ${error.message}</p>`;
            }
        }
    </script>
</body>
</html>