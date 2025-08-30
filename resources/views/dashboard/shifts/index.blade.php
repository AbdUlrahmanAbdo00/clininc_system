@extends('dashboard.layouts.app')

@section('title', 'إدارة الشيفتات - مركز صحي')
@section('page-title', 'إدارة الشيفتات')
@section('page-description', 'عرض وإدارة جميع شيفتات الأطباء')

@section('content')

<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">الشيفتات</h2>
            <p class="text-gray-600">إدارة جميع شيفتات الأطباء في المركز الصحي</p>
        </div>
        <a href="{{ route('dashboard.shifts.create') }}" 
           class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors">
            <i class="fas fa-plus ml-2"></i>
            إضافة شيفت جديد
        </a>
    </div>

    <!-- Shifts Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الطبيب
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            اليوم
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            وقت البداية
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            وقت النهاية
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            مدة الشيفت
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الإجراءات
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($shifts as $shift)
                        @if($shift->doctors->count() > 0)
                            @foreach($shift->doctors as $doctor)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($doctor->user->first_name . ' ' . $doctor->user->last_name) }}&background=0D9488&color=fff" alt="">
                                        <div class="mr-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $doctor->user->first_name . ' ' . $doctor->user->last_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $doctor->specialization->name ?? 'غير محدد' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1">
                                                {{ $arabicDays[$day] ?? $day }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-gray-500">غير محدد</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $shift->start_time }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $shift->end_time }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @php
                                        $start = \Carbon\Carbon::parse($shift->start_time);
                                        $end = \Carbon\Carbon::parse($shift->end_time);
                                        $duration = $start->diffInHours($end);
                                    @endphp
                                    {{ $duration }} ساعة
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('dashboard.shifts.edit_data', $shift->id) }}" class="text-green-600 hover:text-green-900" title="تعديل بيانات الشيفت">
                                            <i class="fas fa-cog"></i>
                                        </a>
                                        <a href="{{ route('dashboard.shifts.edit_doctor', $shift->id) }}" class="text-blue-600 hover:text-blue-900" title="تعديل الأطباء والأيام">
                                            <i class="fas fa-user-edit"></i>
                                        </a>

                                        <button class="text-red-600 hover:text-red-900" onclick="removeDoctorFromShift({{ $shift->id }}, {{ $doctor->id }})" title="إلغاء ارتباط الطبيب">
                                            <i class="fas fa-unlink"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    لا يوجد أطباء مسند إليهم هذا الشيفت
                                </td>
                            </tr>
                        @endif
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            لا توجد شيفتات مسجلة حالياً
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($shifts->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $shifts->links() }}
        </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
function removeDoctorFromShift(shiftId, doctorId) {
    if (confirm('هل أنت متأكد من إلغاء ارتباط هذا الطبيب بالشيفت؟')) {
        fetch(`/dashboard/shifts/${shiftId}/update-doctor`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                doctor_id: doctorId,
                days: [],
                action: 'delete'
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
            alert('حدث خطأ أثناء إلغاء الارتباط');
        });
    }
}
</script>
@endsection
