<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة Zirkona Lab</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }
        
        .logo {
            font-size: 4rem;
            margin-bottom: 20px;
        }
        
        h1 {
            color: #333;
            margin-bottom: 30px;
            font-size: 2.5rem;
        }
        
        .description {
            color: #666;
            margin-bottom: 40px;
            font-size: 1.2rem;
            line-height: 1.6;
        }
        
        .buttons {
            display: grid;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .btn {
            display: inline-block;
            padding: 15px 30px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-secondary {
            background: #f8f9fa;
            color: #333;
            border: 2px solid #e9ecef;
        }
        
        .btn-secondary:hover {
            background: #e9ecef;
            transform: translateY(-1px);
        }
        
        .info-box {
            background: #f0f8ff;
            border: 2px solid #e3f2fd;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .info-box h3 {
            color: #1976d2;
            margin-bottom: 15px;
        }
        
        .info-box p {
            color: #555;
            line-height: 1.5;
            margin-bottom: 10px;
        }
        
        .info-box code {
            background: #e3f2fd;
            padding: 3px 8px;
            border-radius: 4px;
            font-family: monospace;
            color: #1976d2;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .logo {
                font-size: 3rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">🦷</div>
        <h1>إدارة Zirkona Lab</h1>
        <p class="description">
            مرحباً بك في منطقة الإدارة. اختر لوحة التحكم المناسبة لعرض الطلبات ورسائل التواصل.
        </p>
        
        <div class="buttons">
            <a href="panel.php" class="btn btn-primary">
                🚀 لوحة التحكم الرئيسية
            </a>
            <a href="basic-dashboard.html" class="btn btn-secondary">
                📊 لوحة المعلومات الأساسية
            </a>
            <a href="health.php" class="btn btn-secondary">
                🔍 فحص حالة النظام
            </a>
        </div>
        
        <div class="info-box">
            <h3>🔐 بيانات الدخول</h3>
            <p><strong>اسم المستخدم:</strong> <code>zirkona</code></p>
            <p><strong>كلمة المرور:</strong> <code>admin123</code></p>
            <p><strong>ملاحظة:</strong> ستحتاج هذه البيانات للدخول إلى لوحة التحكم الرئيسية</p>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; color: #999; font-size: 0.9rem;">
            © <?php echo date('Y'); ?> Zirkona Lab - جميع الحقوق محفوظة
        </div>
    </div>
</body>
</html>