# نظام إدارة المركز الصحي - دليل الإعداد

## المتطلبات الأساسية
- PHP 8.1 أو أحدث
- Composer
- MySQL/MariaDB
- Node.js (اختياري للـ frontend)

## خطوات الإعداد

### 1. تثبيت المتطلبات
```bash
composer install
npm install
```

### 2. إعداد قاعدة البيانات
```bash
# نسخ ملف البيئة
cp .env.example .env

# تعديل ملف .env بإعدادات قاعدة البيانات
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clinic_system
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3. إنشاء مفتاح التطبيق
```bash
php artisan key:generate
```

### 4. تشغيل الـ Migrations
```bash
php artisan migrate
```

### 5. تشغيل الـ Seeders
```bash
php artisan db:seed
```

### 6. تشغيل الخادم
```bash
php artisan serve
```

## بيانات تسجيل الدخول الافتراضية

بعد تشغيل الـ seeders، يمكنك تسجيل الدخول باستخدام:

**اسم المستخدم:** `admin`  
**كلمة المرور:** `admin123`

## الميزات المتاحة

### 1. إدارة الاختصاصات
- إنشاء اختصاص جديد مع صورة
- تعديل الاختصاصات الموجودة
- عرض جميع الاختصاصات

### 2. إدارة الأطباء
- إنشاء طبيب جديد
- اختيار المستخدم والاختصاص
- تحديد مدة الاستشارة
- إضافة السيرة الذاتية

### 3. إدارة الشيفتات
- إنشاء شيفت جديد
- تحديد نوع الشيفت والوقت
- إسناد الشيفت للطبيب
- تحديد أيام العمل

### 4. إدارة المرضى
- عرض قائمة المرضى
- إدارة بيانات المرضى

### 5. إدارة المواعيد
- عرض المواعيد
- إدارة المواعيد

## التوابع المتاحة في API

### الاختصاصات
- `GET /api/getAllSpecializations` - جلب جميع الاختصاصات
- `POST /api/uploadSpecializationImage` - رفع صورة اختصاص

### الأطباء
- `POST /api/Doctor/create` - إنشاء طبيب جديد
- `GET /api/doctors/{specializationId}` - جلب الأطباء حسب الاختصاص

### الشيفتات
- `POST /api/add_shift` - إنشاء شيفت جديد
- `POST /api/assignShift` - إسناد شيفت للطبيب

## الملفات المهمة

- `app/Http/Controllers/DashboardController.php` - تحكم لوحة التحكم
- `app/Http/Controllers/AuthController.php` - تحكم المصادقة
- `app/Http/Controllers/DoctorsController.php` - تحكم الأطباء
- `app/Http/Controllers/ShiftsController.php` - تحكم الشيفتات
- `resources/views/dashboard/` - صفحات لوحة التحكم
- `resources/views/auth/` - صفحات المصادقة

## ملاحظات مهمة

1. تأكد من إعداد Cloudinary لرفع الصور
2. تأكد من تثبيت حزمة Google Translate
3. تأكد من إعداد Spatie Permission للأدوار
4. تأكد من إعداد Laravel Sanctum للمصادقة

## استكشاف الأخطاء

### مشاكل شائعة:
1. **خطأ في قاعدة البيانات**: تأكد من صحة إعدادات قاعدة البيانات
2. **خطأ في الصلاحيات**: تأكد من تشغيل الـ seeders
3. **خطأ في رفع الصور**: تأكد من إعداد Cloudinary
4. **خطأ في المصادقة**: تأكد من إعداد Laravel Sanctum

### مشاكل المصادقة:
1. **عدم القدرة على الوصول للداش بورد**: 
   - تأكد من تسجيل الدخول بنجاح
   - تأكد من حفظ التوكن في localStorage
   - تأكد من إرسال التوكن مع كل طلب

2. **إعادة التوجيه لصفحة تسجيل الدخول**:
   - تأكد من صحة التوكن
   - تأكد من عدم انتهاء صلاحية التوكن
   - تأكد من إعدادات middleware

### سجلات الأخطاء:
```bash
tail -f storage/logs/laravel.log
```

## الدعم

للمساعدة أو الإبلاغ عن مشاكل، يرجى التواصل مع فريق التطوير.

