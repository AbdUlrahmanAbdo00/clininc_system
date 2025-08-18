@extends('dashboard.layouts.app')

@section('title', 'إدارة الأطباء - مركز صحي')
@section('page-title', 'إدارة الأطباء')
@section('page-description', 'عرض وإدارة جميع الأطباء')

@section('content')
{{-- 
    المتغيرات المطلوبة من Controller:
    $doctors = قائمة الأطباء مع علاقاتهم (specialization)
    $specializations = قائمة جميع الاختصاصات للفلتر
    إذا لم تتوفر المتغيرات أو الروابط، يمكن استخدام تعليقات أو بيانات وهمية
--}}
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">الأطباء</h2>
            <p class="text-gray-600">إدارة جميع الأطباء في المركز الصحي</p>
        </div>
        {{-- رابط إضافة طبيب جديد --}}
        {{-- <a href="/dashboard/doctors/create" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors">
            <i class="fas fa-plus ml-2"></i>
            إضافة طبيب جديد
        </a> --}}
    </div>
    
    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex gap-4">
            <div class="flex-1">
                <input type="text" placeholder="البحث عن طبيب..." 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            </div>
            <select class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                <option value="">جميع الاختصاصات</option>
                {{-- @foreach($specializations as $specialization)
                    <option value="{{ $specialization->id }}">{{ $specialization->name }}</option>
                @endforeach --}}
                {{-- يمكن وضع تعليقات هنا بدلاً من المتغير --}}
            </select>
        </div>
    </div>
    
    <!-- Doctors Table -->
    {{-- عرض قائمة الأطباء من متغير $doctors --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الطبيب</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاختصاص</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">البريد الإلكتروني</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الهاتف</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">سنوات الخبرة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    {{-- @forelse($doctors as $doctor) --}}
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{-- صورة واسم الطبيب --}}
                            {{-- <img src="https://ui-avatars.com/api/?name={{ urlencode($doctor->name) }}&background=0D9488&color=fff" 
                                 alt="{{ $doctor->name }}" class="w-10 h-10 rounded-full"> --}}
                            <div class="mr-4">
                                {{-- <div class="text-sm font-medium text-gray-900">{{ $doctor->name }}</div> --}}
                                {{-- اسم الطبيب هنا --}}
                                <div class="text-sm font-medium text-gray-900">اسم الطبيب</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{-- {{ $doctor->specialization->name ?? 'غير محدد' }} --}}
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                تخصص الطبيب
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{-- {{ $doctor->email }} --}}
                            بريد الطبيب
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{-- {{ $doctor->phone }} --}}
                            هاتف الطبيب
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{-- {{ $doctor->experience_years }} سنوات --}}
                            سنوات الخبرة
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex gap-2">
                                {{-- <a href="/dashboard/doctors/{{ $doctor->id }}/edit" class="text-teal-600 hover:text-teal-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="/dashboard/doctors/{{ $doctor->id }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطبيب؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form> --}}
                                {{-- أزرار التعديل والحذف هنا --}}
                            </div>
                        </td>
                    </tr>
                    {{-- @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            لا توجد أطباء مسجلين حالياً
                        </td>
                    </tr>
                    @endforelse --}}
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        {{-- @if($doctors->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $doctors->links() }}
        </div>
        @endif --}}
    </div>
</div>
@endsection
