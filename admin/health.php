<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فحص حالة النظام - Zirkona Lab</title>
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
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .header {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
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
            font-size: 1.5rem;
        }
        
        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-right: 4px solid #667eea;
        }
        
        .status-ok {
            border-right-color: #28a745;
        }
        
        .status-warning {
            border-right-color: #ffc107;
        }
        
        .status-error {
            border-right-color: #dc3545;
        }
        
        .status-label {
            font-weight: 500;
            color: #333;
        }
        
        .status-value {
            font-family: monospace;
            color: #666;
        }
        
        .status-icon {
            margin-left: 10px;
        }
        
        .refresh-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 20px;
        }
        
        .refresh-btn:hover {
            background: #5a67d8;
        }
        
        .back-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-left: 10px;
        }
        
        .json-output {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            font-family: monospace;
            font-size: 14px;
            white-space: pre-wrap;
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 فحص حالة النظام - Zirkona Lab</h1>
            <p>مراقبة أداء الخادم والملفات</p>
            <div style="margin-top: 15px;">
                <a href="index.php" class="back-btn">← العودة للإدارة</a>
            </div>
        </div>

        <div class="section">
            <h2>⚡ حالة الخادم</h2>
            <button class="refresh-btn" onclick="location.reload()">🔄 تحديث</button>
            
            <div class="status-grid">
                <div class="status-item status-ok">
                    <span class="status-label">الوقت الحالي</span>
                    <span class="status-value"><?php echo date('Y-m-d H:i:s'); ?> ✅</span>
                </div>
                
                <div class="status-item <?php echo phpversion() ? 'status-ok' : 'status-error'; ?>">
                    <span class="status-label">إصدار PHP</span>
                    <span class="status-value"><?php echo phpversion(); ?> <?php echo phpversion() ? '✅' : '❌'; ?></span>
                </div>
                
                <div class="status-item <?php echo function_exists('mail') ? 'status-ok' : 'status-warning'; ?>">
                    <span class="status-label">وظيفة الإيميل</span>
                    <span class="status-value"><?php echo function_exists('mail') ? 'متاحة ✅' : 'غير متاحة ⚠️'; ?></span>
                </div>
                
                <div class="status-item <?php echo session_status() === PHP_SESSION_ACTIVE ? 'status-ok' : 'status-warning'; ?>">
                    <span class="status-label">حالة الجلسات</span>
                    <span class="status-value"><?php echo session_status() === PHP_SESSION_ACTIVE ? 'نشطة ✅' : 'غير نشطة ⚠️'; ?></span>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>📁 حالة الملفات</h2>
            <div class="status-grid">
                <div class="status-item <?php echo file_exists('../api/orders_log.txt') ? 'status-ok' : 'status-error'; ?>">
                    <span class="status-label">ملف الطلبات</span>
                    <span class="status-value"><?php echo file_exists('../api/orders_log.txt') ? 'موجود ✅' : 'غير موجود ❌'; ?></span>
                </div>
                
                <div class="status-item <?php echo file_exists('../api/contact_log.txt') ? 'status-ok' : 'status-error'; ?>">
                    <span class="status-label">ملف الرسائل</span>
                    <span class="status-value"><?php echo file_exists('../api/contact_log.txt') ? 'موجود ✅' : 'غير موجود ❌'; ?></span>
                </div>
                
                <div class="status-item <?php echo is_writable('../api/') ? 'status-ok' : 'status-error'; ?>">
                    <span class="status-label">صلاحيات الكتابة</span>
                    <span class="status-value"><?php echo is_writable('../api/') ? 'متاحة ✅' : 'غير متاحة ❌'; ?></span>
                </div>
                
                <div class="status-item status-ok">
                    <span class="status-label">مساحة القرص</span>
                    <span class="status-value"><?php echo round(disk_free_space('.') / 1024 / 1024 / 1024, 2); ?> GB ✅</span>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>📊 إحصائيات البيانات</h2>
            <div class="status-grid">
                <?php
                $orders_count = 0;
                $contacts_count = 0;
                
                if (file_exists('../api/orders_log.txt')) {
                    $orders_content = file_get_contents('../api/orders_log.txt');
                    $orders_count = substr_count($orders_content, '=== NEW ORDER ===');
                    if ($orders_count == 0) {
                        $orders_count = substr_count($orders_content, 'New Order');
                    }
                }
                
                if (file_exists('../api/contact_log.txt')) {
                    $contacts_content = file_get_contents('../api/contact_log.txt');
                    $contacts_count = substr_count($contacts_content, '=== NEW CONTACT MESSAGE ===');
                    if ($contacts_count == 0) {
                        $contacts_count = substr_count($contacts_content, 'Contact from:');
                    }
                }
                
                $today_count = isset($orders_content) ? substr_count($orders_content, date('Y-m-d')) : 0;
                ?>
                
                <div class="status-item status-ok">
                    <span class="status-label">إجمالي الطلبات</span>
                    <span class="status-value"><?php echo $orders_count; ?> طلب</span>
                </div>
                
                <div class="status-item status-ok">
                    <span class="status-label">رسائل التواصل</span>
                    <span class="status-value"><?php echo $contacts_count; ?> رسالة</span>
                </div>
                
                <div class="status-item status-ok">
                    <span class="status-label">طلبات اليوم</span>
                    <span class="status-value"><?php echo $today_count; ?> طلب</span>
                </div>
                
                <div class="status-item status-ok">
                    <span class="status-label">آخر تحديث</span>
                    <span class="status-value"><?php echo date('H:i:s'); ?></span>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>🔧 معلومات تقنية مفصلة</h2>
            <div class="json-output">
<?php
$detailed_info = [
    'server' => [
        'software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
        'php_version' => phpversion(),
        'memory_limit' => ini_get('memory_limit'),
        'max_execution_time' => ini_get('max_execution_time'),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size')
    ],
    'extensions' => [
        'curl' => extension_loaded('curl') ? 'متاح' : 'غير متاح',
        'json' => extension_loaded('json') ? 'متاح' : 'غير متاح',
        'mbstring' => extension_loaded('mbstring') ? 'متاح' : 'غير متاح',
        'openssl' => extension_loaded('openssl') ? 'متاح' : 'غير متاح'
    ],
    'files' => [
        'orders_log_size' => file_exists('../api/orders_log.txt') ? filesize('../api/orders_log.txt') . ' bytes' : 'غير موجود',
        'contact_log_size' => file_exists('../api/contact_log.txt') ? filesize('../api/contact_log.txt') . ' bytes' : 'غير موجود'
    ],
    'statistics' => [
        'total_orders' => $orders_count,
        'total_contacts' => $contacts_count,
        'today_orders' => $today_count,
        'last_check' => date('Y-m-d H:i:s')
    ]
];

echo json_encode($detailed_info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
            </div>
        </div>
    </div>

    <script>
        // Auto refresh every 30 seconds
        setInterval(function() {
            location.reload();
        }, 30000);
    </script>
</body>
</html>