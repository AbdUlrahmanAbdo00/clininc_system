@extends('dashboard.layouts.app')

@section('title', 'إضافة شيفت جديد - مركز صحي')
@section('page-title', 'إضافة شيفت جديد')
@section('page-description', 'إضافة شيفت جديد للطبيب')

@section('content')
{{-- 
    المتغيرات المطلوبة من Controller:
    $doctors = قائمة جميع الأطباء للاختيار
--}}
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">معلومات الشيفت</h2>
            <p class="text-gray-600">أدخل معلومات الشيفت الجديد</p>
        </div>
        
        <form action="/dashboard/shifts" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Doctor -->
                <div>
                    <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">الطبيب</label>
                    <select name="doctor_id" id="doctor_id" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('doctor_id') border-red-500 @enderror">
                        <option value="">اختر الطبيب</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }} - {{ $doctor->specialization->name ?? 'غير محدد' }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Day -->
                <div>
                    <label for="day" class="block text-sm font-medium text-gray-700 mb-2">اليوم</label>
                    <select name="day" id="day" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('day') border-red-500 @enderror">
                        <option value="">اختر اليوم</option>
                        <option value="الأحد" {{ old('day') == 'الأحد' ? 'selected' : '' }}>الأحد</option>
                        <option value="الاثنين" {{ old('day') == 'الاثنين' ? 'selected' : '' }}>الاثنين</option>
                        <option value="الثلاثاء" {{ old('day') == 'الثلاثاء' ? 'selected' : '' }}>الثلاثاء</option>
                        <option value="الأربعاء" {{ old('day') == 'الأربعاء' ? 'selected' : '' }}>الأربعاء</option>
                        <option value="الخميس" {{ old('day') == 'الخميس' ? 'selected' : '' }}>الخميس</option>
                        <option value="الجمعة" {{ old('day') == 'الجمعة' ? 'selected' : '' }}>الجمعة</option>
                        <option value="السبت" {{ old('day') == 'السبت' ? 'selected' : '' }}>السبت</option>
                    </select>
                    @error('day')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Time Range -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">وقت البداية</label>
                        <input type="time" name="start_time" id="start_time" 
                               value="{{ old('start_time') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('start_time') border-red-500 @enderror">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">وقت النهاية</label>
                        <input type="time" name="end_time" id="end_time" 
                               value="{{ old('end_time') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('end_time') border-red-500 @enderror">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex gap-4 pt-6">
                    <button type="submit" 
                            class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition-colors">
                        <i class="fas fa-save ml-2"></i>
                        حفظ الشيفت
                    </button>
                    <a href="/dashboard/shifts" 
                       class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                        <i class="fas fa-times ml-2"></i>
                        إلغاء
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection 