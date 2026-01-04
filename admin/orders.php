<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الطلبات - Zirkona Lab</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            direction: rtl;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .login-form {
            max-width: 400px;
            margin: 100px auto;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        .login-form h2 {
            color: #1d3557;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: right;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .form-group input:focus {
            border-color: #457b9d;
            outline: none;
        }
        .login-btn {
            width: 100%;
            padding: 12px;
            background: #457b9d;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        .login-btn:hover {
            background: #1d3557;
        }
        .error {
            color: #e63946;
            margin-top: 10px;
            font-weight: bold;
        }
        h1 {
            color: #1d3557;
            text-align: center;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 40px;
        }
        .section h2 {
            color: #457b9d;
            border-bottom: 2px solid #a8dadc;
            padding-bottom: 10px;
        }
        .log-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            white-space: pre-wrap;
            font-family: monospace;
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
        }
        .no-data {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 20px;
        }
        .refresh-btn, .logout-btn {
            background: #457b9d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 15px;
            margin-left: 10px;
        }
        .refresh-btn:hover, .logout-btn:hover {
            background: #1d3557;
        }
        .logout-btn {
            background: #e63946;
            float: left;
        }
        .logout-btn:hover {
            background: #c53030;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: linear-gradient(135deg, #457b9d, #1d3557);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    
    // Simple password protection
    $admin_password = 'zirkona2024'; // كلمة المرور
    $admin_username = 'admin'; // اسم المستخدم
    
    // Handle login
    if (isset($_POST['login'])) {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if ($username === $admin_username && $password === $admin_password) {
            $_SESSION['admin_logged_in'] = true;
        } else {
            $login_error = 'اسم المستخدم أو كلمة المرور غير صحيحة';
        }
    }
    
    // Handle logout
    if (isset($_GET['logout'])) {
        session_destroy();
        header('Location: orders.php');
        exit;
    }
    
    // Check if logged in
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        ?>
        <div class="login-form">
            <h2>🔐 تسجيل الدخول</h2>
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
                <?php if (isset($login_error)): ?>
                    <div class="error"><?php echo $login_error; ?></div>
                <?php endif; ?>
            </form>
            <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 5px; font-size: 14px; color: #666;">
                <strong>بيانات الدخول:</strong><br>
                اسم المستخدم: <code>admin</code><br>
                كلمة المرور: <code>zirkona2024</code>
            </div>
        </div>
        <?php
        exit;
    }
    ?>
    
    <div class="container">
        <h1>🛒 إدارة الطلبات - Zirkona Lab</h1>
        
        <div style="text-align: left; margin-bottom: 20px;">
            <button class="refresh-btn" onclick="location.reload()">تحديث الصفحة</button>
            <a href="?logout=1" class="logout-btn">تسجيل الخروج</a>
            <div style="clear: both;"></div>
        </div>
        
        <?php
        $ordersFile = '../api/orders_log.txt';
        $contactFile = '../api/contact_log.txt';
        
        // Count orders and contacts
        $orderCount = 0;
        $contactCount = 0;
        
        if (file_exists($ordersFile)) {
            $orderContent = file_get_contents($ordersFile);
            $orderCount = substr_count($orderContent, '=== NEW ORDER ===');
        }
        
        if (file_exists($contactFile)) {
            $contactContent = file_get_contents($contactFile);
            $contactCount = substr_count($contactContent, '=== NEW CONTACT MESSAGE ===');
        }
        ?>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $orderCount; ?></div>
                <div>إجمالي الطلبات</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $contactCount; ?></div>
                <div>رسائل التواصل</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo date('Y-m-d'); ?></div>
                <div>تاريخ اليوم</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo date('H:i'); ?></div>
                <div>الوقت الحالي</div>
            </div>
        </div>
        
        <div class="section">
            <h2>📋 سجل الطلبات</h2>
            <div class="log-content">
                <?php
                if (file_exists($ordersFile)) {
                    $content = file_get_contents($ordersFile);
                    if (!empty($content)) {
                        // Show latest orders first
                        $orders = explode('=== NEW ORDER ===', $content);
                        $orders = array_reverse($orders);
                        $content = implode('=== NEW ORDER ===', $orders);
                        echo htmlspecialchars($content);
                    } else {
                        echo '<div class="no-data">لا توجد طلبات حتى الآن</div>';
                    }
                } else {
                    echo '<div class="no-data">ملف الطلبات غير موجود</div>';
                }
                ?>
            </div>
        </div>
        
        <div class="section">
            <h2>📩 سجل رسائل التواصل</h2>
            <div class="log-content">
                <?php
                if (file_exists($contactFile)) {
                    $content = file_get_contents($contactFile);
                    if (!empty($content)) {
                        // Show latest contacts first
                        $contacts = explode('=== NEW CONTACT MESSAGE ===', $content);
                        $contacts = array_reverse($contacts);
                        $content = implode('=== NEW CONTACT MESSAGE ===', $contacts);
                        echo htmlspecialchars($content);
                    } else {
                        echo '<div class="no-data">لا توجد رسائل تواصل حتى الآن</div>';
                    }
                } else {
                    echo '<div class="no-data">ملف رسائل التواصل غير موجود</div>';
                }
                ?>
            </div>
        </div>
        
        <div class="section">
            <h2>🔧 معلومات النظام</h2>
            <div class="log-content">
PHP Version: <?php echo phpversion(); ?>

Server Software: <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?>

Mail Function: <?php echo function_exists('mail') ? 'Available ✅' : 'Not Available ❌'; ?>

Current Time: <?php echo date('Y-m-d H:i:s'); ?>

Orders File: <?php echo file_exists($ordersFile) ? 'Exists ✅' : 'Not Found ❌'; ?>

Contact File: <?php echo file_exists($contactFile) ? 'Exists ✅' : 'Not Found ❌'; ?>

File Permissions: <?php echo is_writable('../api/') ? 'Writable ✅' : 'Not Writable ❌'; ?>

Session Status: <?php echo session_status() === PHP_SESSION_ACTIVE ? 'Active ✅' : 'Inactive ❌'; ?>

Admin Logged In: <?php echo isset($_SESSION['admin_logged_in']) ? 'Yes ✅' : 'No ❌'; ?>
            </div>
        </div>
    </div>
</body>
</html>