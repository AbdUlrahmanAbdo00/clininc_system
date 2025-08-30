@extends('dashboard.layouts.app')

@section('title', 'تعديل بيانات الشيفت - مركز صحي')
@section('page-title', 'تعديل بيانات الشيفت')
@section('page-description', 'تعديل بيانات الشيفت: ' . $shift->shift_type)

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">تعديل بيانات الشيفت</h2>
            <p class="text-gray-600">تعديل بيانات الشيفت: {{ $shift->shift_type }}</p>
        </div>
        <a href="{{ route('dashboard.shifts.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-right ml-2"></i>
            العودة للقائمة
        </a>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-2xl shadow p-6 card-hover">
        <form id="editShiftForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Shift Type -->
                <div>
                    <label for="shift_type" class="block text-sm font-medium text-gray-700 mb-2">نوع الشيفت</label>
                    <input type="text" id="shift_type" name="shift_type" 
                           value="{{ old('shift_type', $shift->shift_type) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                           placeholder="مثال: صباحي، مسائي، ليلي">
                </div>

                <!-- Start Time -->
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">وقت البداية</label>
                    <input type="time" id="start_time" name="start_time" 
                           value="{{ old('start_time', \Carbon\Carbon::parse($shift->start_time)->format('H:i')) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>

                <!-- End Time -->
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">وقت النهاية</label>
                    <input type="time" id="end_time" name="end_time" 
                           value="{{ old('end_time', \Carbon\Carbon::parse($shift->end_time)->format('H:i')) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>

                <!-- Start Break Time -->
                <div>
                    <label for="start_break_time" class="block text-sm font-medium text-gray-700 mb-2">وقت بداية الاستراحة</label>
                    <input type="time" id="start_break_time" name="start_break_time" 
                           value="{{ old('start_break_time', \Carbon\Carbon::parse($shift->start_break_time)->format('H:i')) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>

                <!-- End Break Time -->
                <div>
                    <label for="end_break_time" class="block text-sm font-medium text-gray-700 mb-2">وقت نهاية الاستراحة</label>
                    <input type="time" id="end_break_time" name="end_break_time" 
                           value="{{ old('end_break_time', \Carbon\Carbon::parse($shift->end_break_time)->format('H:i')) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>
            </div>

            <!-- Error Messages -->
            <div id="error-messages" class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg hidden">
                <!-- Errors will be displayed here -->
            </div>

            <!-- Success Messages -->
            <div id="success-messages" class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg hidden">
                <!-- Success messages will be displayed here -->
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-end space-x-3 space-x-reverse">
                <a href="{{ route('dashboard.shifts.index') }}" 
                   class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    إلغاء
                </a>
                <button type="submit" id="submitBtn"
                        class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition-colors flex items-center">
                    <i class="fas fa-save ml-2"></i>
                    <span>حفظ التغييرات</span>
                    <div id="loadingSpinner" class="hidden ml-2">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('editShiftForm');
        const submitBtn = document.getElementById('submitBtn');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const errorMessages = document.getElementById('error-messages');
        const successMessages = document.getElementById('success-messages');

        // Add focus effects
        const inputs = form.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-teal-500');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-teal-500');
            });
        });

        // Form submission
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Clear previous messages
            errorMessages.classList.add('hidden');
            successMessages.classList.add('hidden');
            
            // Show loading state
            submitBtn.disabled = true;
            loadingSpinner.classList.remove('hidden');
            submitBtn.querySelector('span').textContent = 'جاري الحفظ...';

            try {
                // Get form data
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());
                
                // Add method for PUT request
                data._method = 'PUT';

                const response = await fetch('{{ route("dashboard.shifts.update_data", $shift->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    // Show success message
                    successMessages.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-600 ml-2"></i>
                            <span class="text-green-800">${result.message}</span>
                        </div>
                    `;
                    successMessages.classList.remove('hidden');
                    
                    // Redirect after 2 seconds
                    setTimeout(() => {
                        window.location.href = '{{ route("dashboard.shifts.index") }}';
                    }, 2000);
                } else {
                    // Show error message
                    if (result.errors) {
                        let errorsHtml = '<div class="text-red-800 font-medium mb-2">يرجى تصحيح الأخطاء التالية:</div><ul class="list-disc pl-5 text-red-700">';
                        Object.keys(result.errors).forEach(field => {
                            const fieldName = getFieldDisplayName(field);
                            result.errors[field].forEach(error => {
                                errorsHtml += `<li><strong>${fieldName}:</strong> ${error}</li>`;
                            });
                        });
                        errorsHtml += '</ul>';
                        errorMessages.innerHTML = errorsHtml;
                    } else {
                        errorMessages.innerHTML = `
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-600 ml-2"></i>
                                <span class="text-red-800">${result.message}</span>
                            </div>
                        `;
                    }
                    errorMessages.classList.remove('hidden');
                }
            } catch (error) {
                // Show network error
                errorMessages.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-600 ml-2"></i>
                        <span class="text-red-800">حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.</span>
                    </div>
                `;
                errorMessages.classList.remove('hidden');
            } finally {
                // Reset loading state
                submitBtn.disabled = false;
                loadingSpinner.classList.add('hidden');
                submitBtn.querySelector('span').textContent = 'حفظ التغييرات';
            }
        });

        // Helper function to get Arabic field names
        function getFieldDisplayName(field) {
            const fieldNames = {
                'shift_type': 'نوع الشيفت',
                'start_time': 'وقت البداية',
                'end_time': 'وقت النهاية',
                'start_break_time': 'وقت بداية الاستراحة',
                'end_break_time': 'وقت نهاية الاستراحة'
            };
            return fieldNames[field] || field;
        }
    });
</script>
@endpush
