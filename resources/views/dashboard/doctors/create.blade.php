@extends('dashboard.layouts.app')

@section('title', 'إضافة طبيب جديد - مركز صحي')
@section('page-title', 'إضافة طبيب جديد')
@section('page-description', 'إضافة طبيب جديد إلى النظام')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 hover-glow hover-scale transform transition-all duration-300">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">معلومات الطبيب</h2>
            <p class="text-gray-600">أدخل معلومات الطبيب الجديد</p>
        </div>
        
        <form id="doctorForm">
            @csrf
            
            <div class="space-y-6">
                <!-- User Selection -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">اختر المستخدم</label>
                    <select name="user_id" id="user_id" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent" required>
                        <option value="">اختر المستخدم</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->first_name }} {{ $user->last_name }} - {{ $user->number }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Specialization -->
                <div>
                    <label for="specialization_id" class="block text-sm font-medium text-gray-700 mb-2">الاختصاص</label>
                    <select name="specialization_id" id="specialization_id" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent" required>
                        <option value="">اختر الاختصاص</option>
                        @foreach($specializations as $specialization)
                            <option value="{{ $specialization->id }}">{{ $specialization->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Consultation Duration -->
                <div>
                    <label for="consultation_duration" class="block text-sm font-medium text-gray-700 mb-2">مدة الاستشارة (بالدقائق)</label>
                    <input type="number" name="consultation_duration" id="consultation_duration" 
                           min="15" max="1440" step="15"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                           placeholder="أدخل مدة الاستشارة بالدقائق" required>
                    <p class="mt-1 text-sm text-gray-500">مثال: 30 دقيقة = 30، ساعة = 60</p>
                </div>
                
                <!-- Bio -->
                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">السيرة الذاتية</label>
                    <textarea name="bio" id="bio" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                              placeholder="أدخل السيرة الذاتية للطبيب" required></textarea>
                </div>
                
                <!-- Actions -->
                <div class="flex gap-4 pt-6">
                    <button type="submit" 
                            class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition-colors transform hover:scale-105">
                        <i class="fas fa-save ml-2"></i>
                        حفظ الطبيب
                    </button>
                    <a href="{{ route('dashboard.doctors.index') }}" 
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
    // إرسال النموذج
    document.getElementById('doctorForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const messageDiv = document.getElementById('message');
        
        // إظهار رسالة التحميل
        messageDiv.className = 'mt-4 p-3 rounded-lg bg-blue-100 text-blue-700';
        messageDiv.textContent = 'جاري إنشاء الطبيب...';
        messageDiv.classList.remove('hidden');
        
        fetch('{{ route("dashboard.doctors.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                user_id: formData.get('user_id'),
                specialization_id: formData.get('specialization_id'),
                consultation_duration: formData.get('consultation_duration'),
                bio: formData.get('bio')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageDiv.className = 'mt-4 p-3 rounded-lg bg-green-100 text-green-700';
                messageDiv.textContent = data.message;
                
                // إعادة التوجيه بعد ثانيتين
                setTimeout(() => {
                    window.location.href = '{{ route("dashboard.doctors.index") }}';
                }, 2000);
            } else {
                messageDiv.className = 'mt-4 p-3 rounded-lg bg-red-100 text-red-700';
                messageDiv.textContent = data.message || 'حدث خطأ أثناء إنشاء الطبيب';
            }
        })
        .catch(error => {
            messageDiv.className = 'mt-4 p-3 rounded-lg bg-red-100 text-red-700';
            messageDiv.textContent = 'حدث خطأ أثناء إنشاء الطبيب';
            console.error('Error:', error);
        });
    });
</script>
@endsection
