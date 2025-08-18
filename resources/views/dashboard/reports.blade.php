@extends('dashboard.layouts.app')

@section('title', 'التقارير - مركز صحي')
@section('page-title', 'التقارير')
@section('page-description', 'تقارير وإحصائيات المركز الصحي')

@section('content')
{{-- 
    المتغيرات المطلوبة من Controller:
    $monthlyAppointments = إحصائيات المواعيد الشهرية
    $topDoctors = قائمة الأطباء الأكثر حجزاً
    $topSpecializations = قائمة الاختصاصات مع عدد الأطباء
--}}

<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">التقارير والإحصائيات</h2>
            <p class="text-gray-600">نظرة شاملة على أداء المركز الصحي</p>
        </div>
        <div class="flex gap-2">
            <button class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors">
                <i class="fas fa-download ml-2"></i>
                تصدير التقرير
            </button>
        </div>
    </div>
    
    <!-- Monthly Appointments Chart -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">المواعيد الشهرية</h3>
            <span class="text-sm text-gray-600">آخر 30 يوم</span>
        </div>
        <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
            <div class="text-center">
                <i class="fas fa-chart-line text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-500">رسم بياني للمواعيد الشهرية</p>
                <p class="text-sm text-gray-400 mt-2">سيتم إضافة الرسوم البيانية قريباً</p>
            </div>
        </div>
    </div>
    
    <!-- Top Doctors -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">الأطباء الأكثر حجزاً</h3>
        <div class="space-y-4">
            {{-- 
                هنا كان يتم عرض الأطباء باستخدام $topDoctors
                مثال لكل طبيب:
                الاسم، التخصص، عدد المواعيد
            --}}
            <div class="text-center text-gray-500 py-8">
                <i class="fas fa-user-md text-4xl text-gray-300 mb-2"></i>
                <p>لا توجد بيانات متاحة</p>
            </div>
        </div>
    </div>
    
    <!-- Specializations Stats -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">إحصائيات الاختصاصات</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {{-- 
                هنا كان يتم عرض الاختصاصات باستخدام $topSpecializations
                مثال لكل اختصاص:
                الاسم، عدد الأطباء
            --}}
            <div class="col-span-full text-center text-gray-500 py-8">
                <i class="fas fa-stethoscope text-4xl text-gray-300 mb-2"></i>
                <p>لا توجد اختصاصات مسجلة</p>
            </div>
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-600">مواعيد اليوم</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{-- عدد مواعيد اليوم --}}
                        {{-- {{ \App\Models\Appointment::whereDate('created_at', today())->count() }} --}}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-user-md text-2xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-600">أطباء متاحون</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{-- عدد الأطباء --}}
                        {{-- {{ \App\Models\Doctors::count() }} --}}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-600">شيفتات اليوم</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{-- عدد الشيفتات --}}
                        {{-- {{ \App\Models\Shift::where('day', now()->format('l'))->count() }} --}}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-600">مرضى جدد</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{-- عدد المرضى الجدد --}}
                        {{-- {{ \App\Models\Patients::whereDate('created_at', today())->count() }} --}}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
