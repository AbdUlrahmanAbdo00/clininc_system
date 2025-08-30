@extends('dashboard.layouts.app')

@section('title', 'تعديل علاقة الطبيب بالشيفت - مركز صحي')
@section('page-title', 'تعديل علاقة الطبيب بالشيفت')
@section('page-description', 'تعديل أو حذف علاقة الطبيب بالشيفت')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">تعديل علاقة الطبيب بالشيفت</h2>
            <p class="text-gray-600">إدارة علاقات الأطباء بالشيفتات</p>
        </div>
        <a href="{{ route('dashboard.shifts.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
            <i class="fas fa-arrow-right ml-2"></i>
            العودة للشيفتات
        </a>
    </div>

    <!-- Current Doctor-Shift Relationships -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-user-md text-teal-600 ml-2"></i>
            العلاقات الحالية بين الأطباء والشيفتات
        </h3>
        
        @if($shiftDoctors->count() > 0)
            <div class="space-y-4">
                @foreach($shiftDoctors as $doctor)
                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <!-- Doctor and Shift Info -->
                        <div class="flex-1">
                            <div class="flex items-center gap-4">
                                <!-- Doctor Info -->
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user-md text-teal-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 text-lg">
                                            {{ $doctor->user->first_name }} {{ $doctor->user->last_name }}
                                        </h4>
                                        <p class="text-sm text-gray-600">
                                            {{ $doctor->specialization->name ?? 'غير محدد' }}
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Separator -->
                                <div class="text-gray-300 text-2xl">|</div>
                                
                                <!-- Shift Info -->
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-clock text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 text-lg">
                                            {{ $doctor->shift->shift_type }}
                                        </h4>
                                        <p class="text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($doctor->shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($doctor->shift->end_time)->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Current Days -->
                            <div class="mt-3">
                                <span class="text-sm text-gray-600 ml-16">أيام العمل الحالية:</span>
                                <div class="flex flex-wrap gap-2 mt-2 ml-16">
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
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $arabicDays[$day] ?? $day }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-gray-500 text-sm">لا توجد أيام محددة</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex items-center gap-3">
                            <!-- Edit Days Button -->
                            <button onclick="editDoctorDays({{ $doctor->id }}, {{ json_encode($days) }})" 
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                                <i class="fas fa-edit"></i>
                                <span>تعديل الأيام</span>
                            </button>
                            
                            <!-- Remove Relationship Button -->
                            <button onclick="removeDoctorRelationship({{ $doctor->id }}, '{{ $doctor->user->first_name }} {{ $doctor->user->last_name }}', '{{ $doctor->shift->shift_type }}')" 
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                                <i class="fas fa-unlink"></i>
                                <span>حذف الصلة</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-info-circle text-4xl mb-3"></i>
                <p class="text-lg">لا يوجد أطباء مرتبطين بهذا الشيفت حالياً</p>
            </div>
        @endif
    </div>
</div>

<!-- Edit Days Modal -->
<div id="editDaysModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 w-96 max-w-full">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">تعديل أيام العمل</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="editDaysForm">
                @csrf
                <input type="hidden" id="edit_doctor_id" name="doctor_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-3">اختر أيام العمل الجديدة</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach(['Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday','Friday'] as $day)
                        <label class="flex items-center">
                            <input type="checkbox" name="days[]" value="{{ $day }}" class="mr-2">
                            <span class="text-sm">{{ $arabicDays[$day] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save ml-2"></i>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تعديل أيام الطبيب
    document.getElementById('editDaysForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const days = Array.from(formData.getAll('days'));
        
        if (days.length === 0) {
            showMessage('يرجى اختيار يوم واحد على الأقل', 'error');
            return;
        }
        
        updateDoctorShift({{ $shift->id }}, formData.get('doctor_id'), days, 'update');
        closeEditModal();
    });
});

function editDoctorDays(doctorId, currentDays) {
    document.getElementById('edit_doctor_id').value = doctorId;
    
    document.querySelectorAll('#editDaysForm input[name="days[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    
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

function removeDoctorRelationship(doctorId, doctorName, shiftType) {
    if (confirm(`هل أنت متأكد من حذف الصلة بين الطبيب "${doctorName}" والشيفت "${shiftType}"؟`)) {
        updateDoctorShift({{ $shift->id }}, doctorId, [], 'delete');
    }
}

async function updateDoctorShift(shiftId, doctorId, days, action) {
    try {
        const response = await fetch(`/dashboard/shifts/${shiftId}/update-doctor`, {
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
        });

        const data = await response.json();
        
        if (data.success) {
            showMessage(data.message, 'success');
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showMessage('حدث خطأ: ' + data.message, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showMessage('حدث خطأ أثناء تحديث البيانات', 'error');
    }
}

function showMessage(message, type) {
    const existingMessage = document.querySelector('.message-toast');
    if (existingMessage) existingMessage.remove();
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `message-toast fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
    
    if (type === 'success') {
        messageDiv.className += ' bg-green-500 text-white';
        messageDiv.innerHTML = `<div class="flex items-center"><i class="fas fa-check-circle ml-2"></i><span>${message}</span></div>`;
    } else {
        messageDiv.className += ' bg-red-500 text-white';
        messageDiv.innerHTML = `<div class="flex items-center"><i class="fas fa-exclamation-circle ml-2"></i><span>${message}</span></div>`;
    }
    
    document.body.appendChild(messageDiv);
    setTimeout(() => { messageDiv.classList.remove('translate-x-full'); }, 100);
    setTimeout(() => { messageDiv.classList.add('translate-x-full'); setTimeout(() => messageDiv.remove(), 300); }, 3000);
}
</script>
@endpush
