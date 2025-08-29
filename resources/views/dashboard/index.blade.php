@extends('dashboard.layouts.app')

@section('title', 'لوحة التحكم - مركز صحي')
@section('page-title', 'لوحة التحكم')
@section('page-description', 'نظرة عامة على مركز صحي')

@section('content')
@php
    $statCards = [
        ['label'=>'الأطباء','value'=>12,'icon'=>'fa-user-md','color'=>'blue','route'=>'dashboard.doctors.index'],
        ['label'=>'الاختصاصات','value'=>8,'icon'=>'fa-stethoscope','color'=>'green','route'=>'dashboard.specializations.index'],
        ['label'=>'الشيفتات','value'=>15,'icon'=>'fa-clock','color'=>'yellow','route'=>'dashboard.shifts.index'],
        ['label'=>'المواعيد','value'=>45,'icon'=>'fa-calendar-check','color'=>'purple','route'=>'dashboard.appointments.index'],
        ['label'=>'المرضى','value'=>30,'icon'=>'fa-users','color'=>'red','route'=>'dashboard.patients.index'],
    ];

    $quickActions = [
        ['title'=>'إضافة طبيب جديد','desc'=>'إضافة طبيب جديد إلى النظام','icon'=>'fa-user-plus','color'=>'blue','route'=>'dashboard.doctors.create'],
        ['title'=>'إضافة اختصاص','desc'=>'إضافة اختصاص طبي جديد','icon'=>'fa-plus','color'=>'green','route'=>'dashboard.specializations.create'],
        ['title'=>'إضافة شيفت','desc'=>'جدولة شيفت جديد للطبيب','icon'=>'fa-clock','color'=>'yellow','route'=>'dashboard.shifts.create'],
    ];
@endphp

<div class="space-y-6">

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        @foreach($statCards as $card)
        <a href="{{ route($card['route']) }}" class="bg-white rounded-2xl shadow p-6 card-hover hover-glow hover-scale transform transition-all duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-{{ $card['color'] }}-100 text-{{ $card['color'] }}-600 hover-rotate transition-transform duration-300">
                    <i class="fas {{ $card['icon'] }} text-2xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-600">{{ $card['label'] }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $card['value'] }}</p>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Line Chart: عدد المواعيد اليومية -->
        <div class="bg-white rounded-2xl shadow p-6 hover-glow hover-scale transform transition-all duration-300">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">عدد المواعيد اليومية</h3>
            <canvas id="appointmentsChart" class="w-full h-64"></canvas>
        </div>

        <!-- Bar Chart: أفضل الأطباء حسب عدد المواعيد هذا الأسبوع -->
        <div class="bg-white rounded-2xl shadow p-6 hover-glow hover-scale transform transition-all duration-300">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">أفضل 5 أطباء حسب عدد المواعيد هذا الأسبوع</h3>
            <canvas id="topDoctorsChart" class="w-full h-64"></canvas>
        </div>

    </div>

    <!-- Top Performers Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- أفضل 5 أطباء -->
        <div class="bg-white rounded-2xl shadow p-6 hover-glow hover-scale transform transition-all duration-300">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-trophy text-yellow-600 ml-2"></i>
                أفضل 5 أطباء هذا الشهر
            </h3>
            <div class="space-y-3">
                @if(count($doctorStats['top_doctors']) > 0)
                    @foreach($doctorStats['top_doctors'] as $index => $doctor)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-{{ $index == 0 ? 'yellow' : ($index == 1 ? 'gray' : ($index == 2 ? 'orange' : 'blue')) }}-100 text-{{ $index == 0 ? 'yellow' : ($index == 1 ? 'gray' : ($index == 2 ? 'orange' : 'blue')) }}-600 rounded-full flex items-center justify-center text-sm font-bold">
                                {{ $index + 1 }}
                            </div>
                            <div class="mr-3">
                                <p class="font-medium text-gray-900">{{ $doctor['name'] }}</p>
                                <p class="text-xs text-gray-600">{{ $doctor['specialization'] }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-blue-600">{{ $doctor['appointments_count'] }}</p>
                            <p class="text-xs text-gray-500">موعد</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-info-circle text-2xl mb-2"></i>
                        <p>لا توجد بيانات متاحة</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- أفضل 5 اختصاصات -->
        <div class="bg-white rounded-2xl shadow p-6 hover-glow hover-scale transform transition-all duration-300">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-star text-purple-600 ml-2"></i>
                أفضل 5 اختصاصات
            </h3>
            <div class="space-y-3">
                @if(count($specializationStats['top_specializations']) > 0)
                    @foreach($specializationStats['top_specializations'] as $index => $specialization)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-{{ $index == 0 ? 'purple' : ($index == 1 ? 'blue' : ($index == 2 ? 'green' : 'yellow')) }}-100 text-{{ $index == 0 ? 'purple' : ($index == 1 ? 'blue' : ($index == 2 ? 'green' : 'yellow')) }}-600 rounded-full flex items-center justify-center text-sm font-bold">
                                {{ $index + 1 }}
                            </div>
                            <div class="mr-3">
                                <p class="font-medium text-gray-900">{{ $specialization['name'] }}</p>
                                <p class="text-xs text-gray-600">اختصاص طبي</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-purple-600">{{ $specialization['doctors_count'] }}</p>
                            <p class="text-xs text-gray-500">طبيب</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-info-circle text-2xl mb-2"></i>
                        <p>لا توجد بيانات متاحة</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        @foreach($quickActions as $action)
        <a href="{{ route($action['route']) }}" class="bg-white rounded-2xl shadow p-6 hover:shadow-lg card-hover hover-glow hover-scale transform transition-all duration-300">
            <div class="text-center">
                <div class="p-3 rounded-full bg-{{ $action['color'] }}-100 text-{{ $action['color'] }}-600 mx-auto w-16 h-16 flex items-center justify-center mb-4 hover-rotate transition-transform duration-300">
                    <i class="fas {{ $action['icon'] }} text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $action['title'] }}</h3>
                <p class="text-sm text-gray-600">{{ $action['desc'] }}</p>
            </div>
        </a>
        @endforeach
    </div>

</div>

@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // أيام الأسبوع
    const days = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];

    // بيانات وهمية للمخطط الخطي للمواعيد اليومية
    const appointmentsData = [12, 19, 10, 15, 20, 25, 18];

    // بيانات وهمية لأفضل الأطباء هذا الأسبوع
    const topDoctors = ['د. أحمد','د. سارة','د. علي','د. ليلى','د. عمر'];
    const topDoctorsAppointments = [25, 22, 20, 18, 15];

    // Line Chart: عدد المواعيد اليومية
    const ctxAppointments = document.getElementById('appointmentsChart').getContext('2d');
    new Chart(ctxAppointments, {
        type: 'line',
        data: {
            labels: days,
            datasets: [{
                label: 'عدد المواعيد',
                data: appointmentsData,
                backgroundColor: 'rgba(79, 70, 229, 0.2)',
                borderColor: 'rgba(79, 70, 229, 1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: 'white',
                pointBorderColor: 'rgba(79, 70, 229, 1)',
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: true, position: 'top' } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 5 } } }
        }
    });

    // Bar Chart: أفضل الأطباء حسب عدد المواعيد
    const ctxTopDoctors = document.getElementById('topDoctorsChart').getContext('2d');
    new Chart(ctxTopDoctors, {
        type: 'bar',
        data: {
            labels: topDoctors,
            datasets: [{
                label: 'عدد المواعيد',
                data: topDoctorsAppointments,
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y', // الأعمدة أفقية
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, stepSize: 5 } }
        }
    });
</script>
@endsection

