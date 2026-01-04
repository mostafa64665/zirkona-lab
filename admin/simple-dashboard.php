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
        
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        
        .login-card h2 {
            color: #333;
            margin-bottom: 30px;
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
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
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
            margin-top: 10px;
        }
        
        .login-btn:hover {
            background: #5a67d8;
        }
        
        .error {
            background: #fee;
            color: #c33;
            padding: 10px;
            border-radius: 8px;
            margin-top: 15px;
        }
        
        .dashboard {
            display: none;
            min-height: 100vh;
        }
        
        .dashboard.active {
            display: block;
        }
        
        .header {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            color: #333;
        }
        
        .logout-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
        }
        
        .logout-btn:hover {
            background: #c0392b;
        }
        
        .main-content {
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .stat-card .number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .stat-card .label {
            color: #666;
            font-size: 1rem;
        }
        
        .section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .section h2 {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f3f4;
        }
        
        .refresh-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        
        .refresh-btn:hover {
            background: #5a67d8;
        }
        
        .data-content {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            white-space: pre-wrap;
            font-family: monospace;
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
        }
        
        .no-data {
            text-align: center;
            padding: 50px;
            color: #666;
        }
        
        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
            }
            
            .main-content {
                padding: 20px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php
    // بدء الجلسة
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // بيانات الدخول
    $admin_username = 'zirkona';
    $admin_password = 'admin123';
    
    // معالجة تسجيل الدخول
    if (isset($_POST['login'])) {
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        
        if ($username === $admin_username && $password === $admin_password) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
        } else {
            $login_error = 'اسم المستخدم أو كلمة المرور غير صحيحة';
        }
    }
    
    // معالجة تسجيل الخروج
    if (isset($_GET['logout'])) {
        session_destroy();
        header('Location: simple-dashboard.php');
        exit;
    }
    
    // قراءة البيانات
    $ordersFile = '../api/orders_log.txt';
    $contactFile = '../api/contact_log.txt';
    
    $orderCount = 0;
    $contactCount = 0;
    $ordersContent = '';
    $contactsContent = '';
    
    // عد الطلبات
    if (file_exists($ordersFile)) {
        $ordersContent = file_get_contents($ordersFile);
        $orderCount = substr_count($ordersContent, '=== NEW ORDER ===');
        if ($orderCount == 0) {
            $orderCount = substr_count($ordersContent, 'New Order');
        }
    }
    
    // عد الرسائل
    if (file_exists($contactFile)) {
        $contactsContent = file_get_contents($contactFile);
        $contactCount = substr_count($contactsContent, '=== NEW CONTACT MESSAGE ===');
        if ($contactCount == 0) {
            $contactCount = substr_count($contactsContent, 'Contact from:');
        }
    }
    
    // طلبات اليوم
    $today = date('Y-m-d');
    $todayCount = substr_count($ordersContent, $today);
    ?>

    <!-- Login Form -->
    <?php if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true): ?>
    <div class="login-container">
        <div class="login-card">
            <h2>🦷 لوحة التحكم - Zirkona Lab</h2>
            <form method="POST">
                <div class="form-group">
                    <label>اسم المستخدم:</label>
                    <input type="text" name="username" required placeholder="zirkona">
                </div>
                <div class="form-group">
                    <label>كلمة المرور:</label>
                    <input type="password" name="password" required placeholder="admin123">
                </div>
                <button type="submit" name="login" class="login-btn">دخول</button>
                <?php if (isset($login_error)): ?>
                    <div class="error"><?php echo $login_error; ?></div>
                <?php endif; ?>
            </form>
            <div style="margin-top: 20px; padding: 15px; background: #f0f8ff; border-radius: 8px; font-size: 14px;">
                <strong>بيانات الدخول:</strong><br>
                اسم المستخدم: <code>zirkona</code><br>
                كلمة المرور: <code>admin123</code>
            </div>
        </div>
    </div>
    <?php else: ?>

    <!-- Dashboard -->
    <div class="dashboard active">
        <div class="header">
            <h1>🦷 لوحة التحكم - Zirkona Lab</h1>
            <div>
                <span>مرحباً، <?php echo $_SESSION['admin_username']; ?></span>
                <a href="?logout=1" class="logout-btn">خروج</a>
            </div>
        </div>

        <div class="main-content">
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="number"><?php echo $orderCount; ?></div>
                    <div class="label">🛒 إجمالي الطلبات</div>
                </div>
                <div class="stat-card">
                    <div class="number"><?php echo $contactCount; ?></div>
                    <div class="label">📧 رسائل التواصل</div>
                </div>
                <div class="stat-card">
                    <div class="number"><?php echo $todayCount; ?></div>
                    <div class="label">📅 طلبات اليوم</div>
                </div>
                <div class="stat-card">
                    <div class="number"><?php echo date('H:i'); ?></div>
                    <div class="label">🕐 الوقت الحالي</div>
                </div>
            </div>

            <!-- Orders Section -->
            <div class="section">
                <h2>📋 سجل الطلبات</h2>
                <button class="refresh-btn" onclick="location.reload()">🔄 تحديث</button>
                <div class="data-content">
                    <?php if (!empty($ordersContent)): ?>
                        <?php echo htmlspecialchars($ordersContent); ?>
                    <?php else: ?>
                        <div class="no-data">لا توجد طلبات حتى الآن</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Contacts Section -->
            <div class="section">
                <h2>💬 سجل رسائل التواصل</h2>
                <div class="data-content">
                    <?php if (!empty($contactsContent)): ?>
                        <?php echo htmlspecialchars($contactsContent); ?>
                    <?php else: ?>
                        <div class="no-data">لا توجد رسائل تواصل حتى الآن</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- System Info -->
            <div class="section">
                <h2>🔧 معلومات النظام</h2>
                <div class="data-content">
PHP Version: <?php echo phpversion(); ?>

Server Software: <?php echo isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : 'Unknown'; ?>

Current Time: <?php echo date('Y-m-d H:i:s'); ?>

Orders File: <?php echo file_exists($ordersFile) ? 'موجود ✅' : 'غير موجود ❌'; ?>

Contact File: <?php echo file_exists($contactFile) ? 'موجود ✅' : 'غير موجود ❌'; ?>

File Permissions: <?php echo is_writable('../api/') ? 'قابل للكتابة ✅' : 'غير قابل للكتابة ❌'; ?>

Session Status: <?php echo (session_status() === PHP_SESSION_ACTIVE) ? 'نشط ✅' : 'غير نشط ❌'; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script>
        // Auto refresh every 60 seconds
        setTimeout(function() {
            if (document.querySelector('.dashboard.active')) {
                location.reload();
            }
        }, 60000);
    </script>
</body>
</html>