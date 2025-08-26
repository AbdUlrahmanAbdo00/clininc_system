@extends('dashboard.layouts.app')

@section('title', 'إدارة الاختصاصات - مركز صحي')
@section('page-title', 'إدارة الاختصاصات')
@section('page-description', 'عرض وإدارة جميع الاختصاصات الطبية')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">الاختصاصات</h2>
            <p class="text-gray-600">إدارة جميع الاختصاصات الطبية في المركز الصحي</p>
        </div>
        <a href="{{ route('dashboard.specializations.create') }}" 
           class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors">
            <i class="fas fa-plus ml-2"></i>
            إضافة اختصاص جديد
        </a>
    </div>
    
    <!-- Search -->
    <div class="bg-white rounded-2xl shadow p-6 card-hover">
        <div class="flex gap-4">
            <div class="flex-1">
                <div class="flex items-center bg-gray-100 rounded-full px-4 py-2">
                    <i class="fas fa-search ml-2 text-gray-500"></i>
                    <input id="specializationSearch" type="text" placeholder="البحث عن اختصاص..." 
                           class="w-full bg-transparent focus:outline-none text-sm">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Specializations Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($specializations as $specialization)
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover transform hover:scale-105 transition-all duration-300 group cursor-pointer" onclick="editSpecialization({{ $specialization->id }})">
            <!-- Image Section with Overlay -->
            <div class="relative h-48 overflow-hidden">
                @if($specialization->path)
                    <img src="{{ $specialization->path }}" 
                         alt="{{ $specialization->name }}" 
                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    <!-- Specialization Name on Image -->
                    <div class="absolute bottom-4 left-4 right-4">
                        <h3 class="text-xl font-bold text-white text-center drop-shadow-lg">{{ $specialization->name }}</h3>
                    </div>
                @else
                    <div class="w-full h-full bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center relative">
                        <!-- Icon with Background -->
                        <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-stethoscope text-4xl text-white"></i>
                        </div>
                        <!-- Specialization Name -->
                        <div class="absolute bottom-4 left-4 right-4">
                            <h3 class="text-xl font-bold text-white text-center drop-shadow-lg">{{ $specialization->name }}</h3>
                        </div>
                    </div>
                @endif
                
                <!-- Delete Button Overlay -->
                <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <button onclick="deleteSpecialization({{ $specialization->id }})" 
                            class="w-8 h-8 bg-white/90 rounded-full flex items-center justify-center text-red-600 hover:bg-white transition-colors">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            </div>
            
            <!-- Content Section -->
            <div class="p-6">
                <!-- Stats Row -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-teal-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">نشط</span>
                    </div>
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-clock ml-1"></i>
                        {{ $specialization->created_at->diffForHumans() }}
                    </div>
                </div>
                
                <!-- Info Section -->
                <div class="text-center">
                    <p class="text-sm text-gray-500">
                        اضغط على الصورة لتعديل الاختصاص
                    </p>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-2xl shadow p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-stethoscope text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد اختصاصات</h3>
                <p class="text-gray-500 mb-6">لم يتم إنشاء أي اختصاصات بعد. ابدأ بإنشاء اختصاص جديد.</p>
                <a href="{{ route('dashboard.specializations.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                    <i class="fas fa-plus ml-2"></i>
                    إنشاء اختصاص جديد
                </a>
            </div>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($specializations->hasPages())
    <div class="bg-white rounded-2xl shadow px-6 py-4">
        {{ $specializations->links() }}
    </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl p-6 max-w-sm w-full mx-4">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">تأكيد الحذف</h3>
                <p class="text-gray-500 mb-6">هل أنت متأكد من حذف هذا الاختصاص؟ لا يمكن التراجع عن هذا الإجراء.</p>
                <div class="flex space-x-3 space-x-reverse">
                    <button onclick="confirmDelete()" 
                            class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                        حذف
                    </button>
                    <button onclick="closeDeleteModal()" 
                            class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let specializationToDelete = null;
    
    // Local search for specializations
    (function(){
        function applyFilter(){
            const q = (document.getElementById('specializationSearch')?.value || '').toLowerCase();
            const cards = document.querySelectorAll('.grid > div:not(.col-span-full)');
            
            cards.forEach(card => {
                const text = card.innerText.toLowerCase();
                const okQuery = !q || text.includes(q);
                card.classList.toggle('hidden', !okQuery);
            });
        }
        
        document.addEventListener('input', (e) => {
            if (e.target && e.target.id === 'specializationSearch') {
                applyFilter();
            }
        });
    })();
    
    // Delete specialization functions
    function deleteSpecialization(specializationId) {
        specializationToDelete = specializationId;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        specializationToDelete = null;
    }
    
    function confirmDelete() {
        if (specializationToDelete) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/dashboard/specializations/${specializationToDelete}`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
        closeDeleteModal();
    }
    
    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
    
    // Edit specialization function
    function editSpecialization(specializationId) {
        window.location.href = `/dashboard/specializations/${specializationId}/edit`;
    }
</script>
@endpush 