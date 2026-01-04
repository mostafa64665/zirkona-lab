# Zirkona Dental Laboratory Website

موقع مختبر زيركونا لطب الأسنان - موقع احترافي لعرض الخدمات وإدارة الطلبات.

## المميزات
- تصميم متجاوب (Responsive Design)
- معرض صور Before/After تفاعلي
- نظام طلبات وحجز مواعيد
- إرسال إيميلات تلقائي
- واجهة عربية وإنجليزية

## التقنيات المستخدمة
- HTML5, CSS3, JavaScript
- Tailwind CSS
- Node.js + Express
- Nodemailer للإيميلات
- BeerSlider للمعرض التفاعلي

## الرفع على Vercel

### 1. إنشاء حساب على Vercel
1. اذهب إلى [vercel.com](https://vercel.com)
2. سجل دخول بـ GitHub أو Google
3. اربط حسابك بـ GitHub

### 2. رفع المشروع
1. ارفع الملفات على GitHub repository
2. في Vercel اضغط "New Project"
3. اختر الـ repository
4. اضغط "Deploy"

### 3. إعداد متغيرات البيئة (Environment Variables)
في لوحة تحكم Vercel:
1. اذهب إلى Settings → Environment Variables
2. أضف:
   - `EMAIL_USER`: إيميل Gmail الخاص بك
   - `EMAIL_PASS`: App Password من Gmail

### 4. ربط الدومين المخصص
1. في Vercel اذهب إلى Settings → Domains
2. أضف الدومين الخاص بك
3. في لوحة تحكم هوستينجر:
   - اذهب إلى DNS Zone Editor
   - أضف CNAME record:
     - Name: www
     - Value: cname.vercel-dns.com
   - أضف A record:
     - Name: @
     - Value: 76.76.19.61

## إعداد Gmail للإيميلات
1. فعل 2-Factor Authentication
2. إنشاء App Password:
   - Google Account → Security → App passwords
   - اختر "Mail" و "Other"
   - انسخ الباسورد المُنشأ

## الملفات المهمة
- `vercel.json`: إعدادات Vercel
- `backend/server.js`: API endpoints
- `contact.js`: معالجة فورم التواصل
- `js/pricing.js`: معالجة طلبات الأسعار

## الدعم
للدعم التقني تواصل مع فريق التطوير.