<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إنشاء حساب جديد - مركز صحي</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        [dir="rtl"] {
            direction: rtl;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .animated-bg {
            position: fixed;
            inset: 0;
            z-index: -10;
            background: linear-gradient(120deg, #ecfeff, #f0fdfa, #e3f9f7);
            background-size: 200% 200%;
            animation: gradientShift 16s ease infinite;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center py-8">
    <div class="animated-bg"></div>
    
    <div class="w-full max-w-lg">
        <div class="glass-card rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-teal-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-plus text-white text-3xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">إنشاء حساب جديد</h1>
                <p class="text-gray-600 mt-2">انضم إلى مركز صحي</p>
            </div>
            
            <form id="registerForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">الاسم الأول</label>
                        <input type="text" id="first_name" name="first_name" required
                               class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200"
                               placeholder="الاسم الأول">
                    </div>
                    
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">الاسم الأخير</label>
                        <input type="text" id="last_name" name="last_name" required
                               class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200"
                               placeholder="الاسم الأخير">
                    </div>
                </div>
                
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">اسم المستخدم</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" id="username" name="username" required
                               class="w-full pr-10 pl-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200"
                               placeholder="أدخل اسم المستخدم">
                    </div>
                </div>
                
                <div>
                    <label for="number" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-phone text-gray-400"></i>
                        </div>
                        <input type="tel" id="number" name="number" required
                               class="w-full pr-10 pl-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200"
                               placeholder="07xxxxxxxxx">
                    </div>
                </div>
                
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">الجنس</label>
                    <select id="gender" name="gender" required
                            class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200">
                        <option value="">اختر الجنس</option>
                        <option value="ذكر">ذكر</option>
                        <option value="أنثى">أنثى</option>
                    </select>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" id="password" name="password" required
                               class="w-full pr-10 pl-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200"
                               placeholder="أدخل كلمة المرور">
                    </div>
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">تأكيد كلمة المرور</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="w-full pr-10 pl-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200"
                               placeholder="أعد إدخال كلمة المرور">
                    </div>
                </div>
                
                <button type="submit" 
                        class="w-full bg-teal-600 hover:bg-teal-700 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 transform hover:scale-105">
                    <i class="fas fa-user-plus ml-2"></i>
                    إنشاء الحساب
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    لديك حساب بالفعل؟ 
                    <a href="{{ route('auth.login') }}" class="text-teal-600 hover:text-teal-700 font-medium">تسجيل الدخول</a>
                </p>
            </div>
            
            <div id="message" class="mt-4 p-3 rounded-lg hidden"></div>
        </div>
    </div>
    
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const messageDiv = document.getElementById('message');
            
            // التحقق من تطابق كلمتي المرور
            if (formData.get('password') !== formData.get('password_confirmation')) {
                messageDiv.className = 'mt-4 p-3 rounded-lg bg-red-100 text-red-700';
                messageDiv.textContent = 'كلمتا المرور غير متطابقتين';
                messageDiv.classList.remove('hidden');
                return;
            }
            
            // إظهار رسالة التحميل
            messageDiv.className = 'mt-4 p-3 rounded-lg bg-blue-100 text-blue-700';
            messageDiv.textContent = 'جاري إنشاء الحساب...';
            messageDiv.classList.remove('hidden');
            
            fetch('/register', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    username: formData.get('username'),
                    password: formData.get('password'),
                    password_confirmation: formData.get('password_confirmation'),
                    first_name: formData.get('first_name'),
                    last_name: formData.get('last_name'),
                    number: formData.get('number'),
                    gender: formData.get('gender')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageDiv.className = 'mt-4 p-3 rounded-lg bg-green-100 text-green-700';
                    messageDiv.textContent = data.message;
                    
                    // إعادة التوجيه لصفحة تسجيل الدخول
                    setTimeout(() => {
                        window.location.href = '{{ route("auth.login") }}';
                    }, 2000);
                } else {
                    messageDiv.className = 'mt-4 p-3 rounded-lg bg-red-100 text-red-700';
                    messageDiv.textContent = data.message || 'حدث خطأ أثناء إنشاء الحساب';
                }
            })
            .catch(error => {
                messageDiv.className = 'mt-4 p-3 rounded-lg bg-red-100 text-red-700';
                messageDiv.textContent = 'حدث خطأ أثناء إنشاء الحساب';
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>



