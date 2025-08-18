@extends('dashboard.layouts.app')

@section('title', 'إدارة المواعيد - مركز صحي')
@section('page-title', 'إدارة المواعيد')
@section('page-description', 'عرض وإدارة جميع المواعيد')

@section('content')
{{-- 
    المتغيرات المطلوبة من Controller:
    $appointments = قائمة المواعيد مع علاقاتهم (patient, doctor)
--}}
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">المواعيد</h2>
            <p class="text-gray-600">إدارة جميع المواعيد في المركز الصحي</p>
        </div>
        <a href="/dashboard/appointments/create" 
           class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors">
            <i class="fas fa-plus ml-2"></i>
            إضافة موعد جديد
        </a>
    </div>
    
    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex gap-4">
            <div class="flex-1">
                <input type="text" placeholder="البحث عن موعد..." 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            </div>
            <select class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                <option value="">جميع الحالات</option>
                <option value="pending">في الانتظار</option>
                <option value="confirmed">مؤكد</option>
                <option value="completed">مكتمل</option>
                <option value="cancelled">ملغي</option>
            </select>
            <input type="date" 
                   class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
        </div>
    </div>
    
    <!-- Appointments Table -->
    {{-- عرض قائمة المواعيد من متغير $appointments --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            المريض
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الطبيب
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            التاريخ والوقت
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الحالة
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            نوع الموعد
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الإجراءات
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($appointments as $appointment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($appointment->patient->name) }}&background=0D9488&color=fff" 
                                     alt="{{ $appointment->patient->name }}" class="w-10 h-10 rounded-full">
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $appointment->patient->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $appointment->patient->phone ?? 'لا يوجد هاتف' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($appointment->doctor->name) }}&background=0D9488&color=fff" 
                                     alt="{{ $appointment->doctor->name }}" class="w-8 h-8 rounded-full">
                                <div class="mr-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $appointment->doctor->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $appointment->doctor->specialization->name ?? 'غير محدد' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>
                                <div class="font-medium">{{ $appointment->appointment_date->format('Y-m-d') }}</div>
                                <div class="text-gray-500">{{ $appointment->appointment_time }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'confirmed' => 'bg-green-100 text-green-800',
                                    'completed' => 'bg-blue-100 text-blue-800',
                                    'cancelled' => 'bg-red-100 text-red-800'
                                ];
                                $statusText = [
                                    'pending' => 'في الانتظار',
                                    'confirmed' => 'مؤكد',
                                    'completed' => 'مكتمل',
                                    'cancelled' => 'ملغي'
                                ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusText[$appointment->status] ?? $appointment->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $appointment->appointment_type ?? 'موعد عادي' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex gap-2">
                                <a href="/dashboard/appointments/{{ $appointment->id }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/dashboard/appointments/{{ $appointment->id }}/edit" 
                                   class="text-teal-600 hover:text-teal-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="/dashboard/appointments/{{ $appointment->id }}" 
                                      method="POST" class="inline" 
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الموعد؟')">
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
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            لا توجد مواعيد مسجلة حالياً
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($appointments->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $appointments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection 