@extends('dashboard.layouts.app')

@section('title', 'إضافة شيفت جديد - مركز صحي')
@section('page-title', 'إضافة شيفت جديد')
@section('page-description', 'إضافة شيفت جديد وإسناده للطبيب')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- إنشاء شيفت جديد -->
    <div class="bg-white rounded-lg shadow p-6 hover-glow hover-scale transform transition-all duration-300">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">إنشاء شيفت جديد</h2>
            <p class="text-gray-600">أدخل معلومات الشيفت الجديد</p>
        </div>
        
        <form id="shiftForm">
            @csrf
            
            <div class="space-y-6">
                <!-- Shift Type -->
                <div>
                    <label for="shift_type" class="block text-sm font-medium text-gray-700 mb-2">نوع الشيفت</label>
                    <input type="text" name="shift_type" id="shift_type" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                           placeholder="مثال: صباحي، مسائي، ليلي" required>
                </div>
                
                <!-- Time Range -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">وقت البداية</label>
                        <input type="time" name="start_time" id="start_time" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent" required>
                    </div>
                    
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">وقت النهاية</label>
                        <input type="time" name="end_time" id="end_time" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent" required>
                    </div>
                </div>

                <!-- Break Time Range -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_break_time" class="block text-sm font-medium text-gray-700 mb-2">بداية الاستراحة</label>
                        <input type="time" name="start_break_time" id="start_break_time"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="end_break_time" class="block text-sm font-medium text-gray-700 mb-2">نهاية الاستراحة</label>
                        <input type="time" name="end_break_time" id="end_break_time"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex gap-4 pt-6">
                    <button type="submit" 
                            class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition-colors transform hover:scale-105">
                        <i class="fas fa-save ml-2"></i>
                        إنشاء الشيفت
                    </button>
                </div>
            </div>
        </form>
        
        <!-- رسائل النجاح/الخطأ -->
        <div id="shiftMessage" class="mt-4 p-3 rounded-lg hidden"></div>
    </div>
    
    <!-- إسناد الشيفت للطبيب -->
    <div class="bg-white rounded-lg shadow p-6 hover-glow hover-scale transform transition-all duration-300">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">إسناد الشيفت للطبيب</h2>
            <p class="text-gray-600">اختر الطبيب والشيفت والأيام</p>
        </div>
        
        <form id="assignShiftForm">
            @csrf
            
            <div class="space-y-6">
                <!-- Doctor -->
                <div>
                    <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">الطبيب</label>
                    <select name="doctor_id" id="doctor_id" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent" required>
                        <option value="">اختر الطبيب</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">
                                {{ $doctor->user->first_name ?? 'غير محدد' }} {{ $doctor->user->last_name ?? '' }} - {{ $doctor->specialization->name ?? 'غير محدد' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Shift Type -->
                <div>
                    <label for="assign_shift_type" class="block text-sm font-medium text-gray-700 mb-2">نوع الشيفت</label>
                    <select name="shift_type" id="assign_shift_type" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent" required>
                        <option value="">اختر نوع الشيفت</option>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->shift_type  }}">
                                {{ $shift->shift_type }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Days -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الأيام</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="days[]" value="Saturday" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <span class="mr-2 text-sm text-gray-700">السبت</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days[]" value="Sunday" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <span class="mr-2 text-sm text-gray-700">الأحد</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days[]" value="Monday" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <span class="mr-2 text-sm text-gray-700">الاثنين</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days[]" value="Tuesday" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <span class="mr-2 text-sm text-gray-700">الثلاثاء</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days[]" value="Wednesday" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <span class="mr-2 text-sm text-gray-700">الأربعاء</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days[]" value="Thursday" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <span class="mr-2 text-sm text-gray-700">الخميس</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days[]" value="Friday" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <span class="mr-2 text-sm text-gray-700">الجمعة</span>
                        </label>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex gap-4 pt-6">
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors transform hover:scale-105">
                        <i class="fas fa-link ml-2"></i>
                        إسناد الشيفت
                    </button>
                </div>
            </div>
        </form>
        
        <!-- رسائل النجاح/الخطأ -->
        <div id="assignMessage" class="mt-4 p-3 rounded-lg hidden"></div>
    </div>
    
    <!-- Actions -->
    <div class="flex justify-center">
        <a href="{{ route('dashboard.shifts.index') }}" 
           class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-colors">
            <i class="fas fa-arrow-right ml-2"></i>
            العودة للشيفتات
        </a>
    </div>
</div>

<script>
    // إنشاء شيفت جديد (AJAX)
    document.getElementById('shiftForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const messageDiv = document.getElementById('shiftMessage');
        
        messageDiv.className = 'mt-4 p-3 rounded-lg bg-blue-100 text-blue-700';
        messageDiv.textContent = 'جاري إنشاء الشيفت...';
        messageDiv.classList.remove('hidden');
        
        fetch('{{ route("dashboard.shifts.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                shift_type: formData.get('shift_type'),
                start_time: formData.get('start_time'),
                end_time: formData.get('end_time'),
                start_break_time: formData.get('start_break_time'),
                end_break_time: formData.get('end_break_time')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageDiv.className = 'mt-4 p-3 rounded-lg bg-green-100 text-green-700';
                messageDiv.textContent = data.message;
                this.reset();
                location.reload(); // إعادة تحميل الصفحة لتحديث القائمة
            } else {
                messageDiv.className = 'mt-4 p-3 rounded-lg bg-red-100 text-red-700';
                messageDiv.textContent = data.message || 'حدث خطأ أثناء إنشاء الشيفت';
            }
        })
        .catch(error => {
            messageDiv.className = 'mt-4 p-3 rounded-lg bg-red-100 text-red-700';
            messageDiv.textContent = 'حدث خطأ أثناء إنشاء الشيفت';
            console.error('Error:', error);
        });
    });
    
    // إسناد الشيفت للطبيب
    document.getElementById('assignShiftForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const messageDiv = document.getElementById('assignMessage');
        
        const selectedDays = formData.getAll('days[]');
        if (selectedDays.length === 0) {
            messageDiv.className = 'mt-4 p-3 rounded-lg bg-red-100 text-red-700';
            messageDiv.textContent = 'يرجى اختيار يوم واحد على الأقل';
            messageDiv.classList.remove('hidden');
            return;
        }
        
        messageDiv.className = 'mt-4 p-3 rounded-lg bg-blue-100 text-blue-700';
        messageDiv.textContent = 'جاري إسناد الشيفت...';
        messageDiv.classList.remove('hidden');
        
        fetch('{{ route("dashboard.shifts.assign") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                doctor_id: formData.get('doctor_id'),
                shift_type: formData.get('shift_type'),
                days: selectedDays
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageDiv.className = 'mt-4 p-3 rounded-lg bg-green-100 text-green-700';
                messageDiv.textContent = data.message;
                this.reset();
            } else {
                messageDiv.className = 'mt-4 p-3 rounded-lg bg-red-100 text-red-700';
                messageDiv.textContent = data.message || 'حدث خطأ أثناء إسناد الشيفت';
            }
        })
        .catch(error => {
            messageDiv.className = 'mt-4 p-3 rounded-lg bg-red-100 text-red-700';
            messageDiv.textContent = 'حدث خطأ أثناء إسناد الشيفت';
            console.error('Error:', error);
        });
    });
</script>
@endsection
