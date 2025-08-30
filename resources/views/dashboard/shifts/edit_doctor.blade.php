@extends('dashboard.layouts.app')

@section('title', 'تعديل ارتباط الطبيب بالشيفت - مركز صحي')
@section('page-title', 'تعديل ارتباط الطبيب بالشيفت')
@section('page-description', 'تعديل الشيفتات المرتبطة بالطبيب والأيام')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">تعديل ارتباط الطبيب بالشيفت</h2>
            <p class="text-gray-600">الطبيب: {{ $shiftDoctors->first()->user->first_name . ' ' . $shiftDoctors->first()->user->last_name ?? 'غير محدد' }}</p>
        </div>
        <a href="{{ route('dashboard.shifts.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
            <i class="fas fa-arrow-right ml-2"></i>
            العودة للشيفتات
        </a>
    </div>

    <!-- Current Doctors for Shift -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">الأطباء المرتبطين بهذا الشيفت حالياً</h3>
        @if($shiftDoctors->count() > 0)
            <div class="space-y-3">
                @foreach($shiftDoctors as $doctor)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="mr-4">
                            <div class="text-sm font-medium text-gray-900">{{ $shift->shift_type }}</div>
                            <div class="text-sm text-gray-500">{{ $shift->start_time }} - {{ $shift->end_time }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @php
                            $days = json_decode($doctor->pivot->days, true);
                            $arabicDays = [
                                'Saturday' => 'السبت',
                                'Sunday' => 'الأحد',
                                'Monday' => 'الاثنين',
                                'Tuesday' => 'الثلاثاء',
                                'Wednesday' => 'الأربعاء',
                                'Thursday' => 'الخميس',
                                'Friday' => 'الجمعة'
                            ];
                        @endphp
                        @if($days)
                            @foreach($days as $day)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $arabicDays[$day] ?? $day }}
                                </span>
                            @endforeach
                        @endif
                        <button onclick="editDoctorDays({{ $doctor->id }}, {{ json_encode($days) }})" class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="removeDoctor({{ $doctor->id }})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-info-circle text-2xl mb-2"></i>
                <p>لا يوجد أطباء مرتبطين بهذا الشيفت حالياً</p>
            </div>
        @endif
    </div>

    <!-- Add New Shift for Doctor -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">إضافة شيفت جديد للطبيب</h3>
        <form id="addShiftForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Shift Selection -->
                <div>
                    <label for="shift_id" class="block text-sm font-medium text-gray-700 mb-2">اختر الشيفت</label>
                    <select name="shift_id" id="shift_id" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent" required>
                        <option value="">اختر الشيفت</option>
                        @foreach($shifts as $shiftOption)
                            @if(!$shiftDoctors->contains('id', $shiftOption->id))
                                <option value="{{ $shiftOption->id }}">
                                    {{ $shiftOption->shift_type }} ({{ $shiftOption->start_time }} - {{ $shiftOption->end_time }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                
                <!-- Days Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">اختر الأيام</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="days[]" value="Saturday" class="mr-2">
                            <span class="text-sm">السبت</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days[]" value="Sunday" class="mr-2">
                            <span class="text-sm">الأحد</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days[]" value="Monday" class="mr-2">
                            <span class="text-sm">الاثنين</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days[]" value="Tuesday" class="mr-2">
                            <span class="text-sm">الثلاثاء</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days[]" value="Wednesday" class="mr-2">
                            <span class="text-sm">الأربعاء</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days[]" value="Thursday" class="mr-2">
                            <span class="text-sm">الخميس</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days[]" value="Friday" class="mr-2">
                            <span class="text-sm">الجمعة</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition-colors">
                    <i class="fas fa-plus ml-2"></i>
                    إضافة الشيفت للطبيب
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Days Modal -->
<div id="editDaysModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">تعديل أيام الطبيب</h3>
            <form id="editDaysForm">
                @csrf
                <input type="hidden" id="edit_doctor_id" name="doctor_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">اختر الأيام</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="days[]" value="Saturday" class="mr-2">
                            <span class="text-sm">السبت</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days[]" value="Sunday" class="mr-2">
                            <span class="text-sm">الأحد</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days[]" value="Monday" class="mr-2">
                            <span class="text-sm">الاثنين</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days[]" value="Tuesday" class="mr-2">
                            <span class="text-sm">الثلاثاء</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days[]" value="Wednesday" class="mr-2">
                            <span class="text-sm">الأربعاء</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days[]" value="Thursday" class="mr-2">
                            <span class="text-sm">الخميس</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="days[]" value="Friday" class="mr-2">
                            <span class="text-sm">الجمعة</span>
                        </label>
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        حفظ التغييرات
                    </button>
                    <button type="button" onclick="closeEditModal()" class="flex-1 bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// إضافة شيفت جديد للطبيب
document.getElementById('addShiftForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const days = Array.from(formData.getAll('days'));
    
    if (days.length === 0) {
        alert('يرجى اختيار يوم واحد على الأقل');
        return;
    }
    
    // إضافة طبيب لشيفت جديد
    const doctorId = {{ $shiftDoctors->count() > 0 ? $shiftDoctors->first()->id : 'null' }};
    if (doctorId) {
        updateDoctorShift(formData.get('shift_id'), doctorId, days, 'add');
    } else {
        alert('لا يمكن إضافة شيفت جديد - لا يوجد طبيب محدد');
    }
});

// تعديل أيام الطبيب
document.getElementById('editDaysForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const days = Array.from(formData.getAll('days'));
    
    if (days.length === 0) {
        alert('يرجى اختيار يوم واحد على الأقل');
        return;
    }
    
    updateDoctorShift({{ $shift->id }}, formData.get('doctor_id'), days, 'update');
    closeEditModal();
});

function editDoctorDays(doctorId, currentDays) {
    document.getElementById('edit_doctor_id').value = doctorId;
    
    // إلغاء تحديد جميع الأيام
    document.querySelectorAll('#editDaysForm input[name="days[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // تحديد الأيام الحالية
    if (currentDays) {
        currentDays.forEach(day => {
            const checkbox = document.querySelector(`#editDaysForm input[value="${day}"]`);
            if (checkbox) checkbox.checked = true;
        });
    }
    
    document.getElementById('editDaysModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editDaysModal').classList.add('hidden');
}

function removeDoctor(doctorId) {
    if (confirm('هل أنت متأكد من إلغاء ارتباط هذا الطبيب بالشيفت؟')) {
        updateDoctorShift({{ $shift->id }}, doctorId, [], 'delete');
    }
}

function updateDoctorShift(shiftId, doctorId, days, action) {
    fetch(`/dashboard/shifts/${shiftId}/update-doctor`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            doctor_id: doctorId,
            days: days,
            action: action
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert('حدث خطأ: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء تحديث البيانات');
    });
}
</script>
@endsection
