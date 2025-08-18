@extends('dashboard.layouts.app')

@section('title', 'لوحة التحكم - مركز صحي')
@section('page-title', 'لوحة التحكم')
@section('page-description', 'نظرة عامة على مركز صحي')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-user-md text-2xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-600">الأطباء</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{-- هنا كان يظهر عدد الأطباء --}}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-stethoscope text-2xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-600">الاختصاصات</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{-- هنا كان يظهر عدد الاختصاصات --}}
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
                    <p class="text-sm font-medium text-gray-600">الشيفتات</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{-- هنا كان يظهر عدد الشيفتات --}}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-600">المواعيد</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{-- هنا كان يظهر عدد المواعيد --}}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-600">المرضى</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{-- هنا كان يظهر عدد المرضى --}}
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Monthly Appointments -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">المواعيد الشهرية</h3>
            <span class="text-sm text-gray-600">هذا الشهر: 
                {{-- هنا كان يظهر عدد المواعيد الشهرية --}}
                موعد</span>
        </div>
        <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
            <p class="text-gray-500">رسم بياني للمواعيد الشهرية</p>
        </div>
    </div>
    
    <!-- Top Doctors -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">الأطباء الأكثر حجزاً</h3>
        <div class="space-y-4">
            {{-- هنا كانت تُعرض قائمة الأطباء الأكثر حجزاً --}}
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    {{-- صورة افتراضية للطبيب --}}
                    <img src="https://ui-avatars.com/api/?name=Doctor+Name&background=0D9488&color=fff" 
                         alt="Doctor Name" class="w-10 h-10 rounded-full">
                    <div class="mr-4">
                        <p class="font-medium text-gray-900">Doctor Name</p>
                        <p class="text-sm text-gray-600">الاختصاص</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-teal-600">0</p>
                    <p class="text-sm text-gray-600">موعد</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="#" 
           class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="text-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mx-auto w-16 h-16 flex items-center justify-center mb-4">
                    <i class="fas fa-user-plus text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">إضافة طبيب جديد</h3>
                <p class="text-sm text-gray-600">إضافة طبيب جديد إلى النظام</p>
            </div>
        </a>
        
        <a href="#" 
           class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="text-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mx-auto w-16 h-16 flex items-center justify-center mb-4">
                    <i class="fas fa-plus text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">إضافة اختصاص</h3>
                <p class="text-sm text-gray-600">إضافة اختصاص طبي جديد</p>
            </div>
        </a>
        
        <a href="#" 
           class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="text-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mx-auto w-16 h-16 flex items-center justify-center mb-4">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">إضافة شيفت</h3>
                <p class="text-sm text-gray-600">جدولة شيفت جديد للطبيب</p>
            </div>
        </a>
    </div>
</div>
@endsection
