<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - Zirkona Lab</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        
        /* Login Form */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        
        .login-card .logo {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 20px;
        }
        
        .login-card h2 {
            color: #333;
            margin-bottom: 30px;
            font-weight: 300;
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
            padding: 15px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .login-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .error {
            background: #fee;
            color: #c33;
            padding: 10px;
            border-radius: 8px;
            margin-top: 15px;
            border: 1px solid #fcc;
        }
        
        /* Dashboard */
        .dashboard {
            display: none;
            min-height: 100vh;
        }
        
        .dashboard.active {
            display: block;
        }
        
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 20px 30px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            color: #333;
            font-weight: 300;
        }
        
        .header .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logout-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background: #c0392b;
            transform: translateY(-1px);
        }
        
        .main-content {
            padding: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .stat-card .icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        
        .stat-card.orders .icon { color: #3498db; }
        .stat-card.contacts .icon { color: #e74c3c; }
        .stat-card.today .icon { color: #f39c12; }
        .stat-card.revenue .icon { color: #27ae60; }
        
        .stat-card .number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .stat-card .label {
            color: #666;
            font-size: 1rem;
        }
        
        /* Orders Section */
        .section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f1f3f4;
        }
        
        .section-title {
            font-size: 1.5rem;
            color: #333;
            font-weight: 600;
        }
        
        .refresh-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .refresh-btn:hover {
            background: #5a67d8;
            transform: translateY(-1px);
        }
        
        /* Order Cards */
        .orders-grid {
            display: grid;
            gap: 20px;
        }
        
        .order-card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-right: 4px solid #667eea;
            transition: all 0.3s ease;
        }
        
        .order-card:hover {
            transform: translateX(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .order-id {
            background: #667eea;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .order-date {
            color: #666;
            font-size: 0.9rem;
        }
        
        .customer-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .customer-detail {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .customer-detail i {
            color: #667eea;
            width: 20px;
        }
        
        .products-list {
            margin-bottom: 15px;
        }
        
        .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .product-item:last-child {
            border-bottom: none;
        }
        
        .product-name {
            font-weight: 500;
            color: #333;
        }
        
        .product-details {
            color: #666;
            font-size: 0.9rem;
        }
        
        .product-total {
            font-weight: 600;
            color: #27ae60;
        }
        
        .order-total {
            text-align: left;
            padding-top: 15px;
            border-top: 2px solid #eee;
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }
        
        .no-data {
            text-align: center;
            padding: 50px;
            color: #666;
            font-size: 1.1rem;
        }
        
        .no-data i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #ddd;
        }
        
        /* Contact Messages */
        .contact-card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-right: 4px solid #e74c3c;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .contact-card:hover {
            transform: translateX(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .contact-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .contact-name {
            font-weight: 600;
            color: #333;
            font-size: 1.1rem;
        }
        
        .contact-message {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            line-height: 1.6;
            color: #555;
        }
        
        /* Responsive */
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
            
            .customer-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php
    session_start();
    
    // بيانات الدخول
    $admin_username = 'zirkona';
    $admin_password = 'admin123';
    
    // معالجة تسجيل الدخول
    if (isset($_POST['login'])) {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
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
        header('Location: dashboard.php');
        exit;
    }
    
    // قراءة البيانات
    $ordersFile = '../api/orders_log.txt';
    $contactFile = '../api/contact_log.txt';
    
    $orders = [];
    $contacts = [];
    $totalRevenue = 0;
    
    // قراءة الطلبات
    if (file_exists($ordersFile)) {
        $content = file_get_contents($ordersFile);
        $orderBlocks = explode('=== NEW ORDER ===', $content);
        
        foreach ($orderBlocks as $block) {
            if (trim($block)) {
                $order = parseOrder($block);
                if ($order) {
                    $orders[] = $order;
                    $totalRevenue += $order['total'];
                }
            }
        }
        
        // ترتيب الطلبات من الأحدث للأقدم
        usort($orders, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
    }
    
    // قراءة رسائل التواصل
    if (file_exists($contactFile)) {
        $content = file_get_contents($contactFile);
        $contactBlocks = explode('=== NEW CONTACT MESSAGE ===', $content);
        
        foreach ($contactBlocks as $block) {
            if (trim($block)) {
                $contact = parseContact($block);
                if ($contact) {
                    $contacts[] = $contact;
                }
            }
        }
        
        // ترتيب الرسائل من الأحدث للأقدم
        usort($contacts, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
    }
    
    // دوال المساعدة
    function parseOrder($block) {
        $lines = explode("\n", trim($block));
        $order = [];
        $products = [];
        $inProducts = false;
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (strpos($line, 'Date:') === 0) {
                $order['date'] = trim(substr($line, 5));
            } elseif (strpos($line, 'Customer:') === 0) {
                $order['customer'] = trim(substr($line, 9));
            } elseif (strpos($line, 'Email:') === 0) {
                $order['email'] = trim(substr($line, 6));
            } elseif (strpos($line, 'Phone:') === 0) {
                $order['phone'] = trim(substr($line, 6));
            } elseif (strpos($line, 'Total Amount:') === 0) {
                $order['total'] = (float) filter_var(substr($line, 13), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            } elseif ($line === 'Products Details:') {
                $inProducts = true;
            } elseif ($inProducts && strpos($line, '- ') === 0) {
                $productLine = substr($line, 2);
                if (preg_match('/^(.+?) \(Qty: (\d+), Unit Price: ([\d.]+) SAR, Total: ([\d.]+) SAR\)/', $productLine, $matches)) {
                    $products[] = [
                        'name' => $matches[1],
                        'quantity' => (int) $matches[2],
                        'unit_price' => (float) $matches[3],
                        'total' => (float) $matches[4]
                    ];
                }
            }
        }
        
        if (!empty($order['customer'])) {
            $order['products'] = $products;
            $order['id'] = 'ORD-' . date('ymd', strtotime($order['date'])) . '-' . substr(md5($order['customer'] . $order['date']), 0, 4);
            return $order;
        }
        
        return null;
    }
    
    function parseContact($block) {
        $lines = explode("\n", trim($block));
        $contact = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (strpos($line, 'Date:') === 0) {
                $contact['date'] = trim(substr($line, 5));
            } elseif (strpos($line, 'Name:') === 0) {
                $contact['name'] = trim(substr($line, 5));
            } elseif (strpos($line, 'Email:') === 0) {
                $contact['email'] = trim(substr($line, 6));
            } elseif (strpos($line, 'Phone:') === 0) {
                $contact['phone'] = trim(substr($line, 6));
            } elseif (strpos($line, 'Message:') === 0) {
                $contact['message'] = trim(substr($line, 8));
            }
        }
        
        if (!empty($contact['name'])) {
            return $contact;
        }
        
        return null;
    }
    
    // إحصائيات اليوم
    $todayOrders = array_filter($orders, function($order) {
        return date('Y-m-d', strtotime($order['date'])) === date('Y-m-d');
    });
    ?>

    <!-- Login Form -->
    <?php if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true): ?>
    <div class="login-container">
        <div class="login-card">
            <div class="logo">
                <i class="fas fa-tooth"></i>
            </div>
            <h2>لوحة التحكم - Zirkona Lab</h2>
            <form method="POST">
                <div class="form-group">
                    <label>اسم المستخدم:</label>
                    <input type="text" name="username" required placeholder="أدخل اسم المستخدم">
                </div>
                <div class="form-group">
                    <label>كلمة المرور:</label>
                    <input type="password" name="password" required placeholder="أدخل كلمة المرور">
                </div>
                <button type="submit" name="login" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> دخول
                </button>
                <?php if (isset($login_error)): ?>
                    <div class="error">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo $login_error; ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <?php else: ?>

    <!-- Dashboard -->
    <div class="dashboard active">
        <div class="header">
            <h1><i class="fas fa-tachometer-alt"></i> لوحة التحكم - Zirkona Lab</h1>
            <div class="user-info">
                <span>مرحباً، <?php echo $_SESSION['admin_username']; ?></span>
                <a href="?logout=1" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> خروج
                </a>
            </div>
        </div>

        <div class="main-content">
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card orders">
                    <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                    <div class="number"><?php echo count($orders); ?></div>
                    <div class="label">إجمالي الطلبات</div>
                </div>
                <div class="stat-card contacts">
                    <div class="icon"><i class="fas fa-envelope"></i></div>
                    <div class="number"><?php echo count($contacts); ?></div>
                    <div class="label">رسائل التواصل</div>
                </div>
                <div class="stat-card today">
                    <div class="icon"><i class="fas fa-calendar-day"></i></div>
                    <div class="number"><?php echo count($todayOrders); ?></div>
                    <div class="label">طلبات اليوم</div>
                </div>
                <div class="stat-card revenue">
                    <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
                    <div class="number"><?php echo number_format($totalRevenue, 0); ?></div>
                    <div class="label">إجمالي المبيعات (ريال)</div>
                </div>
            </div>

            <!-- Orders Section -->
            <div class="section">
                <div class="section-header">
                    <h2 class="section-title"><i class="fas fa-list"></i> الطلبات الأخيرة</h2>
                    <button class="refresh-btn" onclick="location.reload()">
                        <i class="fas fa-sync-alt"></i> تحديث
                    </button>
                </div>
                
                <div class="orders-grid">
                    <?php if (empty($orders)): ?>
                        <div class="no-data">
                            <i class="fas fa-inbox"></i>
                            <div>لا توجد طلبات حتى الآن</div>
                        </div>
                    <?php else: ?>
                        <?php foreach (array_slice($orders, 0, 10) as $order): ?>
                        <div class="order-card">
                            <div class="order-header">
                                <div class="order-id"><?php echo $order['id']; ?></div>
                                <div class="order-date">
                                    <i class="fas fa-clock"></i>
                                    <?php echo date('d/m/Y H:i', strtotime($order['date'])); ?>
                                </div>
                            </div>
                            
                            <div class="customer-info">
                                <div class="customer-detail">
                                    <i class="fas fa-user"></i>
                                    <span><?php echo htmlspecialchars($order['customer']); ?></span>
                                </div>
                                <div class="customer-detail">
                                    <i class="fas fa-envelope"></i>
                                    <span><?php echo htmlspecialchars($order['email']); ?></span>
                                </div>
                                <div class="customer-detail">
                                    <i class="fas fa-phone"></i>
                                    <span><?php echo htmlspecialchars($order['phone']); ?></span>
                                </div>
                            </div>
                            
                            <div class="products-list">
                                <?php foreach ($order['products'] as $product): ?>
                                <div class="product-item">
                                    <div>
                                        <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                                        <div class="product-details">
                                            الكمية: <?php echo $product['quantity']; ?> × 
                                            <?php echo number_format($product['unit_price'], 0); ?> ريال
                                        </div>
                                    </div>
                                    <div class="product-total">
                                        <?php echo number_format($product['total'], 0); ?> ريال
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="order-total">
                                <i class="fas fa-calculator"></i>
                                الإجمالي: <?php echo number_format($order['total'], 0); ?> ريال سعودي
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Contacts Section -->
            <div class="section">
                <div class="section-header">
                    <h2 class="section-title"><i class="fas fa-comments"></i> رسائل التواصل الأخيرة</h2>
                </div>
                
                <?php if (empty($contacts)): ?>
                    <div class="no-data">
                        <i class="fas fa-comment-slash"></i>
                        <div>لا توجد رسائل تواصل حتى الآن</div>
                    </div>
                <?php else: ?>
                    <?php foreach (array_slice($contacts, 0, 5) as $contact): ?>
                    <div class="contact-card">
                        <div class="contact-header">
                            <div class="contact-name">
                                <i class="fas fa-user-circle"></i>
                                <?php echo htmlspecialchars($contact['name']); ?>
                            </div>
                            <div class="order-date">
                                <i class="fas fa-clock"></i>
                                <?php echo date('d/m/Y H:i', strtotime($contact['date'])); ?>
                            </div>
                        </div>
                        
                        <div class="customer-info">
                            <div class="customer-detail">
                                <i class="fas fa-envelope"></i>
                                <span><?php echo htmlspecialchars($contact['email']); ?></span>
                            </div>
                            <div class="customer-detail">
                                <i class="fas fa-phone"></i>
                                <span><?php echo htmlspecialchars($contact['phone']); ?></span>
                            </div>
                        </div>
                        
                        <div class="contact-message">
                            <i class="fas fa-quote-right"></i>
                            <?php echo nl2br(htmlspecialchars($contact['message'])); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script>
        // Auto refresh every 30 seconds
        setInterval(function() {
            if (document.querySelector('.dashboard.active')) {
                location.reload();
            }
        }, 30000);
    </script>
</body>
</html>