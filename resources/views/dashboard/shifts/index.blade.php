@extends('dashboard.layouts.app')

@section('title', 'إدارة الشيفتات - مركز صحي')
@section('page-title', 'إدارة الشيفتات')
@section('page-description', 'عرض وإدارة جميع شيفتات الأطباء')

@section('content')
{{-- 
    المتغيرات المطلوبة من Controller:
    $shifts = قائمة الشيفتات مع علاقاتهم (doctor)
    $doctors = قائمة جميع الأطباء للفلتر
--}}

<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">الشيفتات</h2>
            <p class="text-gray-600">إدارة جميع شيفتات الأطباء في المركز الصحي</p>
        </div>
        <a href="/dashboard/shifts/create" 
           class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors">
            <i class="fas fa-plus ml-2"></i>
            إضافة شيفت جديد
        </a>
    </div>
    
    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex gap-4">
            <div class="flex-1">
                <input type="text" placeholder="البحث عن شيفت..." 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            </div>
            <select class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                <option value="">جميع الأطباء</option>
                {{-- foreach($doctors as $doctor) --}}
                {{-- <option value="{{ $doctor->id }}">{{ $doctor->name }}</option> --}}
            </select>
            <select class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                <option value="">جميع الأيام</option>
                <option value="الأحد">الأحد</option>
                <option value="الاثنين">الاثنين</option>
                <option value="الثلاثاء">الثلاثاء</option>
                <option value="الأربعاء">الأربعاء</option>
                <option value="الخميس">الخميس</option>
                <option value="الجمعة">الجمعة</option>
                <option value="السبت">السبت</option>
            </select>
        </div>
    </div>
    
    <!-- Shifts Table -->
    {{-- عرض قائمة الشيفتات من متغير $shifts --}}
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
                    {{-- @forelse($shifts as $shift) --}}
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{-- عرض اسم الطبيب وصورته --}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{-- عرض اليوم --}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{-- عرض وقت البداية --}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{-- عرض وقت النهاية --}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{-- عرض مدة الشيفت --}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            {{-- أزرار التعديل والحذف --}}
                        </td>
                    </tr>
                    {{-- @empty --}}
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            لا توجد شيفتات مسجلة حالياً
                        </td>
                    </tr>
                    {{-- @endforelse --}}
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        {{-- @if($shifts->hasPages()) --}}
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{-- {{ $shifts->links() }} --}}
        </div>
        {{-- @endif --}}
    </div>
</div>
@endsection
