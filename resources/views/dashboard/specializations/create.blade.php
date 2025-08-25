@extends('dashboard.layouts.app')

@section('title', 'إضافة اختصاص جديد - مركز صحي')
@section('page-title', 'إضافة اختصاص جديد')
@section('page-description', 'إضافة اختصاص طبي جديد إلى النظام')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 hover-glow hover-scale transform transition-all duration-300">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">معلومات الاختصاص</h2>
            <p class="text-gray-600">أدخل معلومات الاختصاص الجديد</p>
        </div>
        
        <form id="specializationForm" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم الاختصاص</label>
                    <input type="text" name="name" id="name" 
                           value="{{ old('name') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('name') border-red-500 @enderror"
                           placeholder="أدخل اسم الاختصاص" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Image -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">صورة الاختصاص</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-teal-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-teal-600 hover:text-teal-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-teal-500">
                                    <span>اختر صورة</span>
                                    <input id="image" name="image" type="file" class="sr-only" accept="image/*" required>
                                </label>
                                <p class="pr-1">أو اسحب وأفلت</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF حتى 2MB</p>
                        </div>
                    </div>
                    <div id="imagePreview" class="mt-2 hidden">
                        <img id="previewImg" class="h-32 w-32 object-cover rounded-lg border border-gray-300">
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex gap-4 pt-6">
                    <button type="submit" 
                            class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition-colors transform hover:scale-105">
                        <i class="fas fa-save ml-2"></i>
                        حفظ الاختصاص
                    </button>
                    <a href="{{ route('dashboard.specializations.index') }}" 
                       class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                        <i class="fas fa-times ml-2"></i>
                        إلغاء
                    </a>
                </div>
            </div>
        </form>
        
        <!-- رسائل النجاح/الخطأ -->
        <div id="message" class="mt-4 p-3 rounded-lg hidden"></div>
    </div>
</div>

<script>
    // معاينة الصورة
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    // إرسال النموذج
    document.getElementById('specializationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const messageDiv = document.getElementById('message');
        
        // إظهار رسالة التحميل
        messageDiv.className = 'mt-4 p-3 rounded-lg bg-blue-100 text-blue-700';
        messageDiv.textContent = 'جاري إنشاء الاختصاص...';
        messageDiv.classList.remove('hidden');
        
        fetch('{{ route("dashboard.specializations.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageDiv.className = 'mt-4 p-3 rounded-lg bg-green-100 text-green-700';
                messageDiv.textContent = data.message;
                
                // إعادة التوجيه بعد ثانيتين
                setTimeout(() => {
                    window.location.href = '{{ route("dashboard.specializations.index") }}';
                }, 2000);
            } else {
                messageDiv.className = 'mt-4 p-3 rounded-lg bg-red-100 text-red-700';
                messageDiv.textContent = data.message || 'حدث خطأ أثناء إنشاء الاختصاص';
            }
        })
        .catch(error => {
            messageDiv.className = 'mt-4 p-3 rounded-lg bg-red-100 text-red-700';
            messageDiv.textContent = 'حدث خطأ أثناء إنشاء الاختصاص';
            console.error('Error:', error);
        });
    });
</script>
@endsection 