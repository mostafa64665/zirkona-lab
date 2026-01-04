<?php
// Fix System Script - Zirkona Lab
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إصلاح النظام - Zirkona Lab</title>
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
        .step {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 8px;
            border-right: 4px solid #007bff;
            background: #f8f9fa;
        }
        .success { border-right-color: #28a745; background: #d4edda; }
        .error { border-right-color: #dc3545; background: #f8d7da; }
        .warning { border-right-color: #ffc107; background: #fff3cd; }
        pre { background: #e9ecef; padding: 10px; border-radius: 5px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 إصلاح النظام - Zirkona Lab</h1>
        <p>هذا الاسكريبت سيقوم بفحص وإصلاح المشاكل الأساسية في النظام</p>
        
        <?php
        $fixes = [];
        
        // Fix 1: Create API directory
        $api_dir = 'api/';
        if (!is_dir($api_dir)) {
            if (mkdir($api_dir, 0755, true)) {
                $fixes[] = ['success', '✅ تم إنشاء مجلد API'];
            } else {
                $fixes[] = ['error', '❌ فشل في إنشاء مجلد API'];
            }
        } else {
            $fixes[] = ['success', '✅ مجلد API موجود'];
        }
        
        // Fix 2: Check and fix permissions
        if (is_writable($api_dir)) {
            $fixes[] = ['success', '✅ صلاحيات الكتابة متاحة'];
        } else {
            if (chmod($api_dir, 0755)) {
                $fixes[] = ['success', '✅ تم إصلاح صلاحيات المجلد'];
            } else {
                $fixes[] = ['error', '❌ لا يمكن إصلاح صلاحيات المجلد'];
            }
        }
        
        // Fix 3: Create log files if they don't exist
        $orders_file = $api_dir . 'orders_log.txt';
        $contact_file = $api_dir . 'contact_log.txt';
        
        if (!file_exists($orders_file)) {
            $initial_content = "=== ORDERS LOG INITIALIZED ===\n";
            $initial_content .= "Date: " . date('Y-m-d H:i:s') . "\n";
            $initial_content .= "System: Zirkona Lab Order Management\n";
            $initial_content .= str_repeat('=', 50) . "\n\n";
            
            if (file_put_contents($orders_file, $initial_content)) {
                $fixes[] = ['success', '✅ تم إنشاء ملف سجل الطلبات'];
            } else {
                $fixes[] = ['error', '❌ فشل في إنشاء ملف سجل الطلبات'];
            }
        } else {
            $fixes[] = ['success', '✅ ملف سجل الطلبات موجود'];
        }
        
        if (!file_exists($contact_file)) {
            $initial_content = "=== CONTACTS LOG INITIALIZED ===\n";
            $initial_content .= "Date: " . date('Y-m-d H:i:s') . "\n";
            $initial_content .= "System: Zirkona Lab Contact Management\n";
            $initial_content .= str_repeat('=', 50) . "\n\n";
            
            if (file_put_contents($contact_file, $initial_content)) {
                $fixes[] = ['success', '✅ تم إنشاء ملف سجل الرسائل'];
            } else {
                $fixes[] = ['error', '❌ فشل في إنشاء ملف سجل الرسائل'];
            }
        } else {
            $fixes[] = ['success', '✅ ملف سجل الرسائل موجود'];
        }
        
        // Fix 4: Test order creation
        $test_order = "=== TEST ORDER ===\n";
        $test_order .= "Date: " . date('Y-m-d H:i:s') . "\n";
        $test_order .= "Customer: عميل تجريبي\n";
        $test_order .= "Email: test@zirkonalab.com\n";
        $test_order .= "Phone: 0501234567\n";
        $test_order .= "Products Count: 1\n";
        $test_order .= "Total Amount: 750 SAR\n";
        $test_order .= "\nProducts Details:\n";
        $test_order .= "- All-ceramic veneer (3D) (Qty: 1, Unit Price: 750 SAR, Total: 750 SAR)\n";
        $test_order .= "\nEmail Status: System Test\n";
        $test_order .= "Server: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
        $test_order .= str_repeat('=', 60) . "\n\n";
        
        if (file_put_contents($orders_file, $test_order, FILE_APPEND | LOCK_EX)) {
            $fixes[] = ['success', '✅ تم إنشاء طلب تجريبي بنجاح'];
        } else {
            $fixes[] = ['error', '❌ فشل في إنشاء طلب تجريبي'];
        }
        
        // Fix 5: Test email function
        if (function_exists('mail')) {
            $fixes[] = ['success', '✅ وظيفة الإيميل متاحة'];
            
            // Try to send a test email
            $test_subject = 'اختبار النظام - ' . date('Y-m-d H:i:s');
            $test_message = "هذا اختبار لنظام Zirkona Lab\n\nالوقت: " . date('Y-m-d H:i:s');
            $test_headers = "From: zirkonalab@gmail.com\r\nContent-Type: text/plain; charset=UTF-8\r\n";
            
            if (@mail('zirkonalab@gmail.com', $test_subject, $test_message, $test_headers)) {
                $fixes[] = ['success', '✅ تم إرسال إيميل تجريبي'];
            } else {
                $fixes[] = ['warning', '⚠️ وظيفة الإيميل متاحة لكن قد لا تعمل على هذا الخادم'];
            }
        } else {
            $fixes[] = ['error', '❌ وظيفة الإيميل غير متاحة'];
        }
        
        // Fix 6: Check admin files
        $admin_files = [
            'admin/index.php' => 'صفحة الإدارة الرئيسية',
            'admin/panel.php' => 'لوحة التحكم',
            'admin/health.php' => 'فحص النظام'
        ];
        
        foreach ($admin_files as $file => $description) {
            if (file_exists($file)) {
                $fixes[] = ['success', "✅ $description موجودة"];
            } else {
                $fixes[] = ['error', "❌ $description غير موجودة"];
            }
        }
        
        // Display all fixes
        foreach ($fixes as $fix) {
            $class = $fix[0];
            $message = $fix[1];
            echo "<div class='step $class'><p>$message</p></div>";
        }
        
        // Summary
        $success_count = count(array_filter($fixes, function($fix) { return $fix[0] === 'success'; }));
        $total_count = count($fixes);
        
        echo "<div class='step'>";
        echo "<h3>📊 ملخص الإصلاح</h3>";
        echo "<p><strong>نجح:</strong> $success_count من $total_count</p>";
        echo "<p><strong>معدل النجاح:</strong> " . round(($success_count / $total_count) * 100, 1) . "%</p>";
        
        if ($success_count === $total_count) {
            echo "<p style='color: green; font-weight: bold;'>🎉 النظام يعمل بشكل مثالي!</p>";
        } elseif ($success_count > $total_count * 0.7) {
            echo "<p style='color: orange; font-weight: bold;'>⚠️ النظام يعمل مع بعض التحذيرات</p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>❌ النظام يحتاج إصلاحات إضافية</p>";
        }
        echo "</div>";
        
        // Show current orders count
        if (file_exists($orders_file)) {
            $orders_content = file_get_contents($orders_file);
            $order_count = substr_count($orders_content, '=== NEW ORDER ===') + substr_count($orders_content, '=== TEST ORDER ===');
            
            echo "<div class='step success'>";
            echo "<h3>📋 إحصائيات الطلبات</h3>";
            echo "<p><strong>عدد الطلبات المحفوظة:</strong> $order_count</p>";
            echo "<p><strong>حجم الملف:</strong> " . round(filesize($orders_file) / 1024, 2) . " KB</p>";
            echo "</div>";
        }
        ?>
        
        <div class="step">
            <h3>🔗 الخطوات التالية</h3>
            <p><a href="test-system.php">🧪 اختبار النظام الشامل</a></p>
            <p><a href="test-order.html">🛒 اختبار الطلبات</a></p>
            <p><a href="admin/">🏠 لوحة الإدارة</a></p>
            <p><a href="admin/panel.php">📊 لوحة التحكم</a></p>
        </div>
        
        <div class="step">
            <h3>📋 معلومات النظام</h3>
            <pre>
PHP Version: <?php echo phpversion(); ?>

Server: <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?>

Current Time: <?php echo date('Y-m-d H:i:s'); ?>

Memory Usage: <?php echo round(memory_get_usage() / 1024 / 1024, 2); ?> MB

Max Execution Time: <?php echo ini_get('max_execution_time'); ?> seconds

Upload Max Size: <?php echo ini_get('upload_max_filesize'); ?>

Post Max Size: <?php echo ini_get('post_max_size'); ?>
            </pre>
        </div>
    </div>
</body>
</html>