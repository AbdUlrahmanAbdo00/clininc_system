@extends('dashboard.layouts.app')

@section('title', 'تعديل بيانات المريض - مركز صحي')
@section('page-title', 'تعديل بيانات المريض')
@section('page-description', 'تعديل بيانات المريض')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">تعديل بيانات المريض</h2>
            <p class="text-gray-600">تعديل بيانات المريض: {{ $patient->user->first_name }} {{ $patient->user->last_name }}</p>
        </div>
        <a href="{{ route('dashboard.patients.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-right ml-2"></i>
            العودة للقائمة
        </a>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-2xl shadow p-6 card-hover">
        <form action="{{ route('dashboard.patients.update', $patient->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">المعلومات الشخصية</h3>
                    
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">الاسم الأول</label>
                        <input type="text" id="first_name" name="first_name" 
                               value="{{ old('first_name', $patient->user->first_name) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        @error('first_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-2">اسم الأب</label>
                        <input type="text" id="middle_name" name="middle_name" 
                               value="{{ old('middle_name', $patient->user->middle_name) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        @error('middle_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">اسم العائلة</label>
                        <input type="text" id="last_name" name="last_name" 
                               value="{{ old('last_name', $patient->user->last_name) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        @error('last_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="mother_name" class="block text-sm font-medium text-gray-700 mb-2">اسم الأم</label>
                        <input type="text" id="mother_name" name="mother_name" 
                               value="{{ old('mother_name', $patient->user->mother_name) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        @error('mother_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Contact & Additional Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">معلومات الاتصال والإضافية</h3>
                    
                    <div>
                        <label for="number" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                        <input type="text" id="number" name="number" 
                               value="{{ old('number', $patient->user->number) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        @error('number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="national_number" class="block text-sm font-medium text-gray-700 mb-2">الرقم الوطني</label>
                        <input type="text" id="national_number" name="national_number" 
                               value="{{ old('national_number', $patient->user->national_number) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        @error('national_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_day" class="block text-sm font-medium text-gray-700 mb-2">تاريخ الميلاد</label>
                        <input type="date" id="birth_day" name="birth_day" 
                               value="{{ old('birth_day', $patient->user->birth_day) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        @error('birth_day')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">الجنس</label>
                        <select id="gender" name="gender" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            <option value="">اختر الجنس</option>
                            <option value="ذكر" {{ old('gender', $patient->user->gender) == 'ذكر' ? 'selected' : '' }}>ذكر</option>
                            <option value="أنثى" {{ old('gender', $patient->user->gender) == 'أنثى' ? 'selected' : '' }}>أنثى</option>
                        </select>
                        @error('gender')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Medical Information -->
            <div class="mt-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">المعلومات الطبية</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="daily_doses_number" class="block text-sm font-medium text-gray-700 mb-2">عدد الجرعات اليومية</label>
                        <input type="number" id="daily_doses_number" name="daily_doses_number" 
                               value="{{ old('daily_doses_number', $patient->daily_doses_number) }}"
                               min="0" max="10"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        @error('daily_doses_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="taken_doses" class="block text-sm font-medium text-gray-700 mb-2">الجرعات المأخوذة</label>
                        <input type="number" id="taken_doses" name="taken_doses" 
                               value="{{ old('taken_doses', $patient->taken_doses) }}"
                               min="0" max="10"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        @error('taken_doses')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-end space-x-3 space-x-reverse">
                <a href="{{ route('dashboard.patients.index') }}" 
                   class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    إلغاء
                </a>
                <button type="submit" 
                        class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition-colors">
                    <i class="fas fa-save ml-2"></i>
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Form validation and enhancement
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input, select');
        
        // Add focus effects
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-teal-500');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-teal-500');
            });
        });
        
        // Form submission confirmation
        form.addEventListener('submit', function(e) {
            if (!confirm('هل أنت متأكد من حفظ التغييرات؟')) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush

