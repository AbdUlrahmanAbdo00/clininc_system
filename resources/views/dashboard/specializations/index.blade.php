@extends('dashboard.layouts.app')

@section('title', 'إدارة الاختصاصات - مركز صحي')
@section('page-title', 'إدارة الاختصاصات')
@section('page-description', 'عرض وإدارة جميع الاختصاصات الطبية')

@section('content')
{{-- 
    المتغيرات المطلوبة من Controller:
    $specializations = قائمة الاختصاصات مع عدد الأطباء لكل اختصاص
--}}
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">الاختصاصات</h2>
            <p class="text-gray-600">إدارة جميع الاختصاصات الطبية في المركز الصحي</p>
        </div>
        <a href="/dashboard/specializations/create" 
           class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors">
            <i class="fas fa-plus ml-2"></i>
            إضافة اختصاص جديد
        </a>
    </div>
    
    <!-- Search -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex gap-4">
            <div class="flex-1">
                <input type="text" placeholder="البحث عن اختصاص..." 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            </div>
        </div>
    </div>
    
    <!-- Specializations Table -->
    {{-- عرض قائمة الاختصاصات من متغير $specializations --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الاختصاص
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الوصف
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            عدد الأطباء
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            تاريخ الإنشاء
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الإجراءات
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($specializations as $specialization)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="p-2 rounded-full bg-teal-100 text-teal-600">
                                    <i class="fas fa-stethoscope"></i>
                                </div>
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $specialization->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ Str::limit($specialization->description, 100) ?: 'لا يوجد وصف' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $specialization->doctors_count }} طبيب
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $specialization->created_at->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex gap-2">
                                <a href="/dashboard/specializations/{{ $specialization->id }}/edit" 
                                   class="text-teal-600 hover:text-teal-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="/dashboard/specializations/{{ $specialization->id }}" 
                                      method="POST" class="inline" 
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الاختصاص؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            لا توجد اختصاصات مسجلة حالياً
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($specializations->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $specializations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection 