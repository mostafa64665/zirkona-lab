<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - Zirkona Lab</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            direction: rtl;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        
        .header h1 {
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        
        .stat-card .number {
            font-size: 3rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .stat-card .label {
            color: #666;
            font-size: 1.1rem;
        }
        
        .section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }
        
        .data-content {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            white-space: pre-wrap;
            font-family: monospace;
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            font-size: 14px;
            line-height: 1.4;
        }
        
        .no-data {
            text-align: center;
            padding: 50px;
            color: #666;
            font-size: 1.2rem;
        }
        
        .refresh-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            cursor: pointer;
            margin-bottom: 20px;
            font-size: 16px;
        }
        
        .refresh-btn:hover {
            background: #5a67d8;
        }
        
        .login-form {
            background: white;
            border-radius: 15px;
            padding: 40px;
            margin: 100px auto;
            max-width: 400px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 20px;
            text-align: right;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        
        .login-btn {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }
        
        .error {
            background: #fee;
            color: #c33;
            padding: 10px;
            border-radius: 8px;
            margin-top: 15px;
        }
        
        .logout-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php
    // بدء الجلسة بأمان
    if (!session_id()) {
        session_start();
    }
    
    // بيانات الدخول
    $username = 'zirkona';
    $password = 'admin123';
    
    // معالجة تسجيل الدخول
    if (isset($_POST['login'])) {
        $input_user = $_POST['username'] ?? '';
        $input_pass = $_POST['password'] ?? '';
        
        if ($input_user === $username && $input_pass === $password) {
            $_SESSION['logged_in'] = true;
        } else {
            $error = 'بيانات خاطئة';
        }
    }
    
    // معالجة تسجيل الخروج
    if (isset($_GET['logout'])) {
        session_destroy();
        header('Location: panel.php');
        exit;
    }
    
    // التحقق من تسجيل الدخول
    $is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    
    if (!$is_logged_in) {
        ?>
        <div class="login-form">
            <h2>🦷 لوحة التحكم</h2>
            <form method="POST">
                <div class="form-group">
                    <label>اسم المستخدم:</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>كلمة المرور:</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" name="login" class="login-btn">دخول</button>
                <?php if (isset($error)): ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endif; ?>
            </form>
            <div style="margin-top: 20px; padding: 15px; background: #f0f8ff; border-radius: 8px; font-size: 14px;">
                <strong>بيانات الدخول:</strong><br>
                المستخدم: <code>zirkona</code><br>
                المرور: <code>admin123</code>
            </div>
        </div>
        <?php
        exit;
    }
    
    // قراءة البيانات
    $orders_file = '../api/orders_log.txt';
    $contact_file = '../api/contact_log.txt';
    
    $orders_content = '';
    $contact_content = '';
    $order_count = 0;
    $contact_count = 0;
    
    if (file_exists($orders_file)) {
        $orders_content = file_get_contents($orders_file);
        $order_count = substr_count($orders_content, '=== NEW ORDER ===');
        if ($order_count == 0) {
            $order_count = substr_count($orders_content, 'New Order');
        }
    }
    
    if (file_exists($contact_file)) {
        $contact_content = file_get_contents($contact_file);
        $contact_count = substr_count($contact_content, '=== NEW CONTACT MESSAGE ===');
        if ($contact_count == 0) {
            $contact_count = substr_count($contact_content, 'Contact from:');
        }
    }
    
    $today_count = substr_count($orders_content, date('Y-m-d'));
    ?>

    <div class="container">
        <div class="header">
            <h1>🦷 لوحة التحكم - Zirkona Lab</h1>
            <p>إدارة الطلبات ورسائل التواصل</p>
            <div style="margin-top: 15px;">
                <a href="?logout=1" class="logout-btn">خروج</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="number"><?php echo $order_count; ?></div>
                <div class="label">🛒 إجمالي الطلبات</div>
            </div>
            <div class="stat-card">
                <div class="number"><?php echo $contact_count; ?></div>
                <div class="label">📧 رسائل التواصل</div>
            </div>
            <div class="stat-card">
                <div class="number"><?php echo $today_count; ?></div>
                <div class="label">📅 طلبات اليوم</div>
            </div>
            <div class="stat-card">
                <div class="number"><?php echo date('H:i'); ?></div>
                <div class="label">🕐 الوقت الحالي</div>
            </div>
        </div>

        <div class="section">
            <h2>📋 سجل الطلبات</h2>
            <button class="refresh-btn" onclick="location.reload()">🔄 تحديث</button>
            <div class="data-content">
                <?php if (!empty($orders_content)): ?>
                    <?php echo htmlspecialchars($orders_content); ?>
                <?php else: ?>
                    <div class="no-data">لا توجد طلبات حتى الآن</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="section">
            <h2>💬 سجل رسائل التواصل</h2>
            <div class="data-content">
                <?php if (!empty($contact_content)): ?>
                    <?php echo htmlspecialchars($contact_content); ?>
                <?php else: ?>
                    <div class="no-data">لا توجد رسائل تواصل حتى الآن</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="section">
            <h2>🔧 معلومات النظام</h2>
            <div class="data-content">
الوقت الحالي: <?php echo date('Y-m-d H:i:s'); ?>

ملف الطلبات: <?php echo file_exists($orders_file) ? 'موجود ✅' : 'غير موجود ❌'; ?>

ملف الرسائل: <?php echo file_exists($contact_file) ? 'موجود ✅' : 'غير موجود ❌'; ?>

إصدار PHP: <?php echo phpversion(); ?>

حالة الجلسة: <?php echo session_status() === PHP_SESSION_ACTIVE ? 'نشطة ✅' : 'غير نشطة ❌'; ?>
            </div>
        </div>
    </div>

    <script>
        // تحديث تلقائي كل دقيقة
        setTimeout(function() {
            location.reload();
        }, 60000);
    </script>
</body>
</html>