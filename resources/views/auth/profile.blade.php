@extends('dashboard.layouts.app')

@section('title', 'الملف الشخصي - مركز صحي')
@section('page-title', 'الملف الشخصي')
@section('page-description', 'تعديل معلومات الحساب')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- معلومات الحساب -->
    <div class="bg-white rounded-2xl shadow p-6 hover-glow hover-scale transform transition-all duration-300">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">معلومات الحساب</h3>
            <button id="editProfileBtn" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-edit ml-2"></i>
                تعديل
            </button>
        </div>
        
        <form id="profileForm" class="space-y-4 hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">اسم المستخدم</label>
                    <input type="text" id="username" name="username" value="{{ $user->username }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200">
                </div>
                
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">الاسم الأول</label>
                    <input type="text" id="first_name" name="first_name" value="{{ $user->first_name }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200">
                </div>
                
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">الاسم الأخير</label>
                    <input type="text" id="last_name" name="last_name" value="{{ $user->last_name }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200">
                </div>
                
                <div>
                    <label for="number" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                    <input type="tel" id="number" name="number" value="{{ $user->number }}" readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                </div>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-save ml-2"></i>
                    حفظ التغييرات
                </button>
                <button type="button" id="cancelProfileBtn" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                    إلغاء
                </button>
            </div>
        </form>
        
        <div id="profileInfo" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <span class="text-sm font-medium text-gray-500">اسم المستخدم:</span>
                <p class="text-gray-900">{{ $user->username }}</p>
            </div>
            
            <div>
                <span class="text-sm font-medium text-gray-500">الاسم الأول:</span>
                <p class="text-gray-900">{{ $user->first_name }}</p>
            </div>
            
            <div>
                <span class="text-sm font-medium text-gray-500">الاسم الأخير:</span>
                <p class="text-gray-900">{{ $user->last_name }}</p>
            </div>
            
            <div>
                <span class="text-sm font-medium text-gray-500">رقم الهاتف:</span>
                <p class="text-gray-900">{{ $user->number }}</p>
            </div>
            
            <div>
                <span class="text-sm font-medium text-gray-500">الجنس:</span>
                <p class="text-gray-900">{{ $user->gender }}</p>
            </div>
            
            <div>
                <span class="text-sm font-medium text-gray-500">تاريخ الإنشاء:</span>
                <p class="text-gray-900">{{ $user->created_at->format('Y-m-d') }}</p>
            </div>
        </div>
    </div>
    
    <!-- تغيير كلمة المرور -->
    <div class="bg-white rounded-2xl shadow p-6 hover-glow hover-scale transform transition-all duration-300">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">تغيير كلمة المرور</h3>
        
        <form id="passwordForm" class="space-y-4">
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور الحالية</label>
                <input type="password" id="current_password" name="current_password" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200"
                       placeholder="أدخل كلمة المرور الحالية">
            </div>
            
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور الجديدة</label>
                <input type="password" id="new_password" name="new_password" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200"
                       placeholder="أدخل كلمة المرور الجديدة">
            </div>
            
            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">تأكيد كلمة المرور الجديدة</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200"
                       placeholder="أعد إدخال كلمة المرور الجديدة">
            </div>
            
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                <i class="fas fa-key ml-2"></i>
                تغيير كلمة المرور
            </button>
        </form>
    </div>
    
    <!-- رسائل النجاح/الخطأ -->
    <div id="message" class="p-4 rounded-lg hidden"></div>
</div>

<script>
    // تعديل الملف الشخصي
    const editProfileBtn = document.getElementById('editProfileBtn');
    const profileForm = document.getElementById('profileForm');
    const profileInfo = document.getElementById('profileInfo');
    const cancelProfileBtn = document.getElementById('cancelProfileBtn');
    
    editProfileBtn.addEventListener('click', function() {
        profileForm.classList.remove('hidden');
        profileInfo.classList.add('hidden');
        editProfileBtn.classList.add('hidden');
    });
    
    cancelProfileBtn.addEventListener('click', function() {
        profileForm.classList.add('hidden');
        profileInfo.classList.remove('hidden');
        editProfileBtn.classList.remove('hidden');
    });
    
    profileForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const messageDiv = document.getElementById('message');
        
        // إظهار رسالة التحميل
        messageDiv.className = 'p-4 rounded-lg bg-blue-100 text-blue-700';
        messageDiv.textContent = 'جاري حفظ التغييرات...';
        messageDiv.classList.remove('hidden');
        
        fetch('/profile/update', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                username: formData.get('username'),
                first_name: formData.get('first_name'),
                last_name: formData.get('last_name')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageDiv.className = 'p-4 rounded-lg bg-green-100 text-green-700';
                messageDiv.textContent = data.message;
                
                // تحديث المعلومات المعروضة
                document.getElementById('username').value = formData.get('username');
                document.getElementById('first_name').value = formData.get('first_name');
                document.getElementById('last_name').value = formData.get('last_name');
                
                // إعادة عرض المعلومات
                setTimeout(() => {
                    profileForm.classList.add('hidden');
                    profileInfo.classList.remove('hidden');
                    editProfileBtn.classList.remove('hidden');
                    
                    // تحديث النص المعروض
                    profileInfo.querySelector('div:nth-child(1) p').textContent = formData.get('username');
                    profileInfo.querySelector('div:nth-child(2) p').textContent = formData.get('first_name');
                    profileInfo.querySelector('div:nth-child(3) p').textContent = formData.get('last_name');
                }, 1000);
            } else {
                messageDiv.className = 'p-4 rounded-lg bg-red-100 text-red-700';
                messageDiv.textContent = data.message || 'حدث خطأ أثناء حفظ التغييرات';
            }
        })
        .catch(error => {
            messageDiv.className = 'p-4 rounded-lg bg-red-100 text-red-700';
            messageDiv.textContent = 'حدث خطأ أثناء حفظ التغييرات';
            console.error('Error:', error);
        });
    });
    
    // تغيير كلمة المرور
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const messageDiv = document.getElementById('message');
        
        // التحقق من تطابق كلمتي المرور
        if (formData.get('new_password') !== formData.get('new_password_confirmation')) {
            messageDiv.className = 'p-4 rounded-lg bg-red-100 text-red-700';
            messageDiv.textContent = 'كلمتا المرور الجديدتان غير متطابقتين';
            messageDiv.classList.remove('hidden');
            return;
        }
        
        // إظهار رسالة التحميل
        messageDiv.className = 'p-4 rounded-lg bg-blue-100 text-blue-700';
        messageDiv.textContent = 'جاري تغيير كلمة المرور...';
        messageDiv.classList.remove('hidden');
        
        fetch('/profile/change-password', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                current_password: formData.get('current_password'),
                new_password: formData.get('new_password'),
                new_password_confirmation: formData.get('new_password_confirmation')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageDiv.className = 'p-4 rounded-lg bg-green-100 text-green-700';
                messageDiv.textContent = data.message;
                
                // مسح النموذج
                this.reset();
            } else {
                messageDiv.className = 'p-4 rounded-lg bg-red-100 text-red-700';
                messageDiv.textContent = data.message || 'حدث خطأ أثناء تغيير كلمة المرور';
            }
        })
        .catch(error => {
            messageDiv.className = 'p-4 rounded-lg bg-red-100 text-red-700';
            messageDiv.textContent = 'حدث خطأ أثناء تغيير كلمة المرور';
            console.error('Error:', error);
        });
    });
</script>
@endsection



