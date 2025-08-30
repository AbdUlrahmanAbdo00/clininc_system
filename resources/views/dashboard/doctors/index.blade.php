@extends('dashboard.layouts.app')

@section('title', 'إدارة الأطباء - مركز صحي')
@section('page-title', 'إدارة الأطباء')
@section('page-description', 'عرض وإدارة جميع الأطباء')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">الأطباء</h2>
            <p class="text-gray-600">إدارة جميع الأطباء في المركز الصحي</p>
        </div>
                        <a href="{{ route('dashboard.doctors.create') }}" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors">
            <i class="fas fa-plus ml-2"></i>
            إضافة طبيب جديد
        </a>
    </div>
    
    <!-- Search and Filter -->
    <div class="bg-white rounded-2xl shadow p-6 card-hover">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <div class="flex items-center bg-gray-100 rounded-full px-4 py-2">
                    <i class="fas fa-search ml-2 text-gray-500"></i>
                    <input id="doctorSearch" type="text" placeholder="البحث عن طبيب..." 
                           class="w-full bg-transparent focus:outline-none text-sm">
                </div>
            </div>
            <div>
                <select id="specializationFilter" class="w-full border border-gray-300 rounded-full px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">جميع الاختصاصات</option>
                    @foreach($specializations as $specialization)
                        <option value="{{ $specialization->name }}">{{ $specialization->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    
    <!-- Doctors Table -->
    <div class="bg-white rounded-2xl shadow overflow-hidden card-hover">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الطبيب</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاختصاص</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الرقم الوطني</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الهاتف</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الجنس</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">سعر الجلسة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="doctorsBody" class="bg-white divide-y divide-gray-200">
                    @forelse($doctors as $doctor)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap flex items-center">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($doctor->user->first_name . ' ' . $doctor->user->last_name) }}&background=0D9488&color=fff" 
                                 alt="{{ $doctor->user->first_name . ' ' . $doctor->user->last_name }}" class="w-10 h-10 rounded-full mr-4">
                            <div class="text-sm font-medium text-gray-900">{{ $doctor->user->first_name . ' ' . $doctor->user->last_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $doctor->specialization->name ?? 'غير محدد' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $doctor->user->national_number ?? 'غير محدد' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $doctor->user->number ?? 'غير محدد' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $doctor->user->gender ?? 'غير محدد' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ ($doctor->price ?? 0) > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ($doctor->price ?? 0) }} ريال
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex gap-2">
                                <a href="{{ route('dashboard.doctors.edit', $doctor->id) }}" class="text-teal-600 hover:text-teal-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            لا توجد أطباء مسجلين حالياً
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($doctors->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $doctors->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Local search/filter without touching routes or back-end
    (function(){
        function applyFilter() {
            const q = (document.getElementById('doctorSearch')?.value || '').toLowerCase();
            const spec = (document.getElementById('specializationFilter')?.value || '').toLowerCase();
            const rows = document.querySelectorAll('#doctorsBody tr');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const okQuery = !q || text.includes(q);
                const okSpec = !spec || text.includes(spec);
                row.classList.toggle('hidden', !(okQuery && okSpec));
            });
        }
        document.addEventListener('input', (e) => {
            if (e.target && (e.target.id === 'doctorSearch' || e.target.id === 'specializationFilter')) applyFilter();
        });
        document.addEventListener('dashboard:search', applyFilter);
    })();
</script>
@endpush
