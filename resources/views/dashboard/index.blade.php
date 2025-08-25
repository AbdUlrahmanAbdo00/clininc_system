@extends('dashboard.layouts.app')

@section('title', 'لوحة التحكم - مركز صحي')
@section('page-title', 'لوحة التحكم')
@section('page-description', 'نظرة عامة على مركز صحي')

@section('content')
<div class="space-y-6">
    <!-- Adaptive Header band with hero illustration-like tiles -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-4 glass-card rounded-2xl p-6 shadow card-hover hover-glow hover-scale transform transition-all duration-300">
            <div class="flex items-center">
                {{-- صورة افتراضية للطبيب/الملف --}}
                <img src="https://ui-avatars.com/api/?name=Jane+Cooper&background=0D9488&color=fff" alt="avatar" class="w-14 h-14 rounded-full ml-4 hover-rotate transition-transform duration-300">
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-lg font-semibold text-gray-800">Jane Cooper</h3>
                        <span class="tag-pill pulse-glow">Cardiology</span>
                    </div>
                    <p class="text-gray-600 text-sm">{{-- وصف موجز ثابت للعرض فقط --}} طبيبة قلب بخبرة 5 سنوات.</p>
                </div>
            </div>
            <button class="mt-4 w-full bg-teal-600 hover:bg-teal-700 text-white rounded-lg py-2 transform hover:scale-105 transition-all duration-200 hover-glow">الملف</button>
        </div>
        <div class="lg:col-span-5 bg-white rounded-2xl p-6 shadow card-hover hover-glow hover-scale transform transition-all duration-300">
            <div class="grid grid-cols-3 gap-4">
                <div class="flex items-center gap-3 hover:scale-110 transition-all duration-200 hover-glow">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center hover-rotate transition-transform duration-300">
                        <i class="fas fa-user-plus text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">مرضى جدد</div>
                        <div class="font-semibold text-gray-800">{{ $stats['patients'] ?? 0 }}</div>
                    </div>
                </div>
                <div class="flex items-center gap-3 hover:scale-110 transition-all duration-200 hover-glow">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center hover-rotate transition-transform duration-300">
                        <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">المواعيد</div>
                        <div class="font-semibold text-gray-800">{{ $stats['appointments'] ?? 0 }}</div>
                    </div>
                </div>
                <div class="flex items-center gap-3 hover:scale-110 transition-all duration-200 hover-glow">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center hover-rotate transition-transform duration-300">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">الشيفتات</div>
                        <div class="font-semibold text-gray-800">{{ $stats['shifts'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-3 bg-white rounded-2xl p-6 shadow card-hover hover-glow hover-scale transform transition-all duration-300">
            <div class="text-center">
                <div class="text-2xl font-bold text-teal-600 mb-2 hover-rotate transition-transform duration-300">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="font-semibold text-gray-800 mb-1">اليوم</div>
                <div class="text-sm text-gray-600 animate-pulse">{{ date('Y-m-d') }}</div>
                <div class="text-xs text-gray-500 mt-2">
                    {{ date('l') }} - {{ date('H:i') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <a href="{{ route('dashboard.doctors.index') }}" class="bg-white rounded-2xl shadow p-6 card-hover hover-glow hover-scale transform transition-all duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 hover-rotate transition-transform duration-300">
                    <i class="fas fa-user-md text-2xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-600">الأطباء</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $stats['doctors'] ?? 0 }}
                    </p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('dashboard.specializations.index') }}" class="bg-white rounded-2xl shadow p-6 card-hover hover-glow hover-scale transform transition-all duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 hover-rotate transition-transform duration-300">
                    <i class="fas fa-stethoscope text-2xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-600">الاختصاصات</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $stats['specializations'] ?? 0 }}
                    </p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('dashboard.shifts.index') }}" class="bg-white rounded-2xl shadow p-6 card-hover hover-glow hover-scale transform transition-all duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 hover-rotate transition-transform duration-300">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-600">الشيفتات</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $stats['shifts'] ?? 0 }}
                    </p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('dashboard.appointments.index') }}" class="bg-white rounded-2xl shadow p-6 card-hover hover-glow hover-scale transform transition-all duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 hover-rotate transition-transform duration-300">
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-600">المواعيد</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $stats['appointments'] ?? 0 }}
                    </p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('dashboard.patients.index') }}" class="bg-white rounded-2xl shadow p-6 card-hover hover-glow hover-scale transform transition-all duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600 hover-rotate transition-transform duration-300">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-600">المرضى</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $stats['patients'] ?? 0 }}
                    </p>
                </div>
            </div>
        </a>
    </div>
    
    <!-- Monthly Appointments + Aside list -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8 bg-white rounded-2xl shadow p-6 hover-glow hover-scale transform transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">نشاط النظام</h3>
                <span class="text-sm text-gray-600">آخر تحديث: {{ date('H:i') }}</span>
            </div>
            

            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="text-3xl font-bold text-blue-600">{{ $stats['doctors'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">طبيب نشط</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-lg hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="text-3xl font-bold text-green-600">{{ $stats['patients'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">مريض مسجل</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="text-3xl font-bold text-yellow-600">{{ $stats['appointments'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">موعد اليوم</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="text-3xl font-bold text-purple-600">{{ $stats['shifts'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">شيفت نشط</div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-4 bg-white rounded-2xl shadow p-6 hover-glow hover-scale transform transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">إحصائيات سريعة</h3>
                <span class="text-sm text-gray-600">{{ date('d/m/Y') }}</span>
            </div>
            <div class="space-y-4">
                <div class="text-center p-4 bg-teal-50 rounded-lg">
                    <div class="text-3xl font-bold text-teal-600">{{ $stats['doctors'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">طبيب نشط</div>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-3xl font-bold text-blue-600">{{ $stats['specializations'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">اختصاص طبي</div>
                </div>
            </div>
        </div>
    </div>
    

    
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('dashboard.doctors.create') }}" 
           class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition-shadow card-hover hover-glow hover-scale transform transition-all duration-300">
            <div class="text-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mx-auto w-16 h-16 flex items-center justify-center mb-4 hover-rotate transition-transform duration-300">
                    <i class="fas fa-user-plus text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">إضافة طبيب جديد</h3>
                <p class="text-sm text-gray-600">إضافة طبيب جديد إلى النظام</p>
            </div>
        </a>
        
        <a href="{{ route('dashboard.specializations.create') }}" 
           class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition-shadow card-hover hover-glow hover-scale transform transition-all duration-300">
            <div class="text-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mx-auto w-16 h-16 flex items-center justify-center mb-4 hover-rotate transition-transform duration-300">
                    <i class="fas fa-plus text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">إضافة اختصاص</h3>
                <p class="text-sm text-gray-600">إضافة اختصاص طبي جديد</p>
            </div>
        </a>
        
        <a href="{{ route('dashboard.shifts.create') }}" 
           class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition-shadow card-hover hover-glow hover-scale transform transition-all duration-300">
            <div class="text-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mx-auto w-16 h-16 flex items-center justify-center mb-4 hover-rotate transition-transform duration-300">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">إضافة شيفت</h3>
                <p class="text-sm text-gray-600">جدولة شيفت جديد للطبيب</p>
            </div>
        </a>
    </div>
</div>
@endsection
