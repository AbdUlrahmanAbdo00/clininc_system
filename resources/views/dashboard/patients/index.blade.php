@extends('dashboard.layouts.app')

@section('title', 'إدارة المرضى - مركز صحي')
@section('page-title', 'إدارة المرضى')
@section('page-description', 'عرض وإدارة جميع المرضى')

@section('content')
{{-- 
    المتغيرات المطلوبة من Controller:
    $patients = قائمة المرضى مع عدد مواعيدهم
--}}

<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">المرضى</h2>
            <p class="text-gray-600">إدارة جميع المرضى في المركز الصحي</p>
        </div>
        {{-- <a href="/dashboard/patients/create" 
           class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors">
            <i class="fas fa-plus ml-2"></i>
            إضافة مريض جديد
        </a> --}}
    </div>
    
    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex gap-4">
            <div class="flex-1">
                <input type="text" placeholder="البحث عن مريض..." 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            </div>
            <select class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                <option value="">جميع الأعمار</option>
                <option value="child">أطفال (0-12)</option>
                <option value="teen">مراهقين (13-19)</option>
                <option value="adult">بالغين (20-59)</option>
                <option value="senior">كبار السن (60+)</option>
            </select>
        </div>
    </div>
    
    <!-- Patients Table -->
    {{-- عرض قائمة المرضى من متغير $patients --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            المريض
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            العمر
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            رقم الهاتف
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            عدد المواعيد
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            آخر زيارة
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الإجراءات
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    {{-- @forelse($patients as $patient) --}}
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                {{-- <img src="https://ui-avatars.com/api/?name={{ urlencode($patient->name) }}&background=0D9488&color=fff" 
                                     alt="{{ $patient->name }}" class="w-10 h-10 rounded-full"> --}}
                                <div class="mr-4">
                                    {{-- <div class="text-sm font-medium text-gray-900">{{ $patient->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $patient->email ?? 'لا يوجد بريد إلكتروني' }}</div> --}}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{-- {{ $patient->age ?? 'غير محدد' }} سنة --}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{-- {{ $patient->phone ?? 'غير محدد' }} --}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{-- {{ $patient->appointments_count ?? 0 }} موعد --}}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{-- {{ $patient->last_visit ? $patient->last_visit->format('Y-m-d') : 'لا توجد زيارات' }} --}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex gap-2">
                                {{-- <a href="/dashboard/patients/{{ $patient->id }}" class="text-blue-600 hover:text-blue-900"><i class="fas fa-eye"></i></a>
                                <a href="/dashboard/patients/{{ $patient->id }}/edit" class="text-teal-600 hover:text-teal-900"><i class="fas fa-edit"></i></a>
                                <form action="/dashboard/patients/{{ $patient->id }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المريض؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                </form> --}}
                            </div>
                        </td>
                    </tr>
                    {{-- @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            لا توجد مرضى مسجلين حالياً
                        </td>
                    </tr>
                    @endforelse --}}
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        {{-- @if($patients->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $patients->links() }}
        </div>
        @endif --}}
    </div>
</div>
@endsection
