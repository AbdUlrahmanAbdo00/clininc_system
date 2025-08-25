<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل الدخول - مركز صحي</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        [dir="rtl"] {
            direction: rtl;
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
<body class="bg-gray-50 min-h-screen">
    <div class="animated-bg"></div>
    
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-20 w-20 bg-teal-600 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-heartbeat text-white text-3xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">مرحباً بك</h2>
                <p class="mt-2 text-sm text-gray-600">سجل دخولك لإدارة العيادة</p>
            </div>

            <!-- Login Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <form id="loginForm" class="space-y-6">
                    @csrf
                    
                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            اسم المستخدم
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input id="username" name="username" type="text" required
                                   class="block w-full pr-10 pl-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                   placeholder="ادخل اسم المستخدم">
                        </div>
                        <div id="username_error" class="hidden text-red-600 text-sm mt-1"></div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            كلمة المرور
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password" name="password" type="password" required
                                   class="block w-full pr-10 pl-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                   placeholder="أدخل كلمة المرور">
                        </div>
                        <div id="password_error" class="hidden text-red-600 text-sm mt-1"></div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" id="loginBtn"
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors">
                            <span id="loginBtnText">تسجيل الدخول</span>
                            <span id="loginBtnSpinner" class="hidden">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                            </span>
                        </button>
                    </div>

                    <!-- Error Message -->
                    <div id="errorMessage" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg"></div>

                    <!-- Success Message -->
                    <div id="successMessage" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"></div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center text-sm text-gray-500">
                <p>جميع الحقوق محفوظة &copy; 2024 مركز صحي</p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const loginBtn = document.getElementById('loginBtn');
            const loginBtnText = document.getElementById('loginBtnText');
            const loginBtnSpinner = document.getElementById('loginBtnSpinner');
            const errorMessage = document.getElementById('errorMessage');
            const successMessage = document.getElementById('successMessage');
            
            // إخفاء الرسائل السابقة
            errorMessage.classList.add('hidden');
            successMessage.classList.add('hidden');
            
            // إظهار حالة التحميل
            loginBtn.disabled = true;
            loginBtnText.classList.add('hidden');
            loginBtnSpinner.classList.remove('hidden');
            
            // إرسال الطلب
            fetch('/login', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    username: formData.get('username'),
                    password: formData.get('password')
                })
            })
            .then(response => {
                if (response.redirected) {
                    // إذا كان هناك redirect، اتبع الرابط
                    window.location.href = response.url;
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    successMessage.textContent = data.message;
                    successMessage.classList.remove('hidden');
                    
                    // تخزين التوكن
                    localStorage.setItem('auth_token', data.token);
                    
                    // إعادة التوجيه
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                } else if (data && !data.success) {
                    errorMessage.textContent = data.message;
                    errorMessage.classList.remove('hidden');
                }
            })
            .catch(error => {
                errorMessage.textContent = 'حدث خطأ في الاتصال';
                errorMessage.classList.remove('hidden');
            })
            .finally(() => {
                // إعادة حالة الزر
                loginBtn.disabled = false;
                loginBtnText.classList.remove('hidden');
                loginBtnSpinner.classList.add('hidden');
            });
        });
    </script>
</body>
</html>


