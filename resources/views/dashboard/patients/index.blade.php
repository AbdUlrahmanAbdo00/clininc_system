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
    </div>
    
    <!-- Search and Filter -->
    <div class="bg-white rounded-2xl shadow p-6 card-hover">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <div class="flex items-center bg-gray-100 rounded-full px-4 py-2">
                    <i class="fas fa-search ml-2 text-gray-500"></i>
                    <input id="patientSearch" type="text" placeholder="البحث عن مريض..." 
                           class="w-full bg-transparent focus:outline-none text-sm">
                </div>
            </div>
            <div>
                <select id="ageFilter" class="w-full border border-gray-300 rounded-full px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">جميع الأعمار</option>
                    <option value="أطفال">أطفال (0-12)</option>
                    <option value="مراهقين">مراهقين (13-19)</option>
                    <option value="بالغين">بالغين (20-59)</option>
                    <option value="كبار">كبار السن (60+)</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Patients Table -->
    {{-- عرض قائمة المرضى من متغير $patients --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden card-hover">
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
                <tbody id="patientsBody" class="bg-white divide-y divide-gray-200">
                    @forelse($patients as $patient)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($patient->user->first_name . ' ' . $patient->user->last_name) }}&background=0D9488&color=fff" 
                                     alt="{{ $patient->user->first_name . ' ' . $patient->user->last_name }}" class="w-10 h-10 rounded-full">
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $patient->user->first_name . ' ' . $patient->user->last_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $patient->user->mother_name ?? 'لا يوجد' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($patient->user->birth_day)
                                {{ \Carbon\Carbon::parse($patient->user->birth_day)->age }} سنة
                            @else
                                غير محدد
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $patient->user->number ?? 'غير محدد' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $patient->appointments_count ?? 0 }} موعد
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $patient->user->created_at ? \Carbon\Carbon::parse($patient->user->created_at)->format('Y-m-d') : 'لا توجد زيارات' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex gap-2">
                                <a href="{{ route('dashboard.patients.edit', $patient->id) }}" class="text-teal-600 hover:text-teal-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            لا توجد مرضى مسجلين حالياً
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($patients->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $patients->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Local search/filter for patients
    (function(){
        function applyFilter(){
            const q = (document.getElementById('patientSearch')?.value || '').toLowerCase();
            const age = (document.getElementById('ageFilter')?.value || '').toLowerCase();
            const rows = document.querySelectorAll('#patientsBody tr');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const okQuery = !q || text.includes(q);
                const okAge = !age || text.includes(age);
                row.classList.toggle('hidden', !(okQuery && okAge));
            });
        }
        document.addEventListener('input', (e) => {
            if (e.target && (e.target.id === 'patientSearch' || e.target.id === 'ageFilter')) applyFilter();
        });
        document.addEventListener('dashboard:search', applyFilter);
    })();
</script>
@endpush
