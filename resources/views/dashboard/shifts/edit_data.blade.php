@extends('dashboard.layouts.app')

@section('title', 'تعديل بيانات الشيفت - مركز صحي')
@section('page-title', 'تعديل بيانات الشيفت')
@section('page-description', 'تعديل وقت الشيفت ونوعه وفترات الراحة')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">تعديل بيانات الشيفت</h2>
            <p class="text-gray-600">تعديل وقت الشيفت: {{ $shift->shift_type }}</p>
        </div>
        
        <form id="shiftDataForm">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Shift Type -->
                <div>
                    <label for="shift_type" class="block text-sm font-medium text-gray-700 mb-2">نوع الشيفت</label>
                    <input type="text" name="shift_type" id="shift_type" 
                           value="{{ $shift->shift_type }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                           placeholder="مثال: صباحي، مسائي، ليلي" required>
                </div>
                
                <!-- Time Range -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">وقت البداية</label>
                        <input type="time" name="start_time" id="start_time" 
                               value="{{ $shift->start_time }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent" required>
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">وقت النهاية</label>
                        <input type="time" name="end_time" id="end_time" 
                               value="{{ $shift->end_time }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent" required>
                    </div>
                </div>
                
                <!-- Break Time Range -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_break_time" class="block text-sm font-medium text-gray-700 mb-2">وقت بداية الراحة</label>
                        <input type="time" name="start_break_time" id="start_break_time" 
                               value="{{ $shift->start_break_time }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent" required>
                    </div>
                    <div>
                        <label for="end_break_time" class="block text-sm font-medium text-gray-700 mb-2">وقت نهاية الراحة</label>
                        <input type="time" name="end_break_time" id="end_break_time" 
                               value="{{ $shift->end_break_time }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent" required>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors">
                        <i class="fas fa-save ml-2"></i>
                        حفظ التغييرات
                    </button>
                    <a href="{{ route('dashboard.shifts.index') }}" class="flex-1 bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors text-center">
                        <i class="fas fa-arrow-right ml-2"></i>
                        رجوع
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.getElementById('shiftDataForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const shiftId = {{ $shift->id }};
    
    // تحويل FormData إلى object
    const data = {
        shift_type: formData.get('shift_type'),
        start_time: formData.get('start_time'),
        end_time: formData.get('end_time'),
        start_break_time: formData.get('start_break_time'),
        end_break_time: formData.get('end_break_time')
    };
    
    fetch(`/dashboard/shifts/${shiftId}/update-data`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // رسالة نجاح مع تأكيد
            if (confirm(data.message + '\n\nهل تريد العودة إلى صفحة الشيفتات؟')) {
                window.location.href = '{{ route("dashboard.shifts.index") }}';
            }
        } else {
            // رسالة خطأ مفصلة
            let errorMessage = 'حدث خطأ: ' + data.message;
            if (data.errors) {
                errorMessage += '\n\nتفاصيل الأخطاء:';
                Object.keys(data.errors).forEach(field => {
                    errorMessage += '\n- ' + field + ': ' + data.errors[field].join(', ');
                });
            }
            alert(errorMessage);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء حفظ البيانات: ' + error.message);
    });
});
</script>
@endsection
