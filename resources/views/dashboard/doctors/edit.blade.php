@extends('dashboard.layouts.app')

@section('title', 'تعديل الطبيب - مركز صحي')
@section('page-title', 'تعديل الطبيب')
@section('page-description', 'تعديل بيانات الطبيب')

@section('content')
{{-- 
    المتغيرات المطلوبة من Controller:
    $doctor = بيانات الطبيب المراد تعديله
    $specializations = قائمة جميع الاختصاصات للاختيار
--}}

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">تعديل معلومات الطبيب</h2>
            {{-- عرض اسم الطبيب --}}
            <p class="text-gray-600">تعديل بيانات الطبيب: {{-- {{ $doctor->name }} --}}</p>
        </div>
        
        <form action="{{-- رابط التحديث --}}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم الطبيب</label>
                    <input type="text" name="name" id="name" 
                           value="{{-- {{ old('name', $doctor->name) }} --}}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('name') border-red-500 @enderror"
                           placeholder="أدخل اسم الطبيب">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                    <input type="email" name="email" id="email" 
                           value="{{-- {{ old('email', $doctor->email) }} --}}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('email') border-red-500 @enderror"
                           placeholder="أدخل البريد الإلكتروني">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                    <input type="text" name="phone" id="phone" 
                           value="{{-- {{ old('phone', $doctor->phone) }} --}}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                           placeholder="أدخل رقم الهاتف">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Specialization -->
                <div>
                    <label for="specialization_id" class="block text-sm font-medium text-gray-700 mb-2">الاختصاص</label>
                    <select name="specialization_id" id="specialization_id" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('specialization_id') border-red-500 @enderror">
                        <option value="">اختر الاختصاص</option>
                        {{-- @foreach($specializations as $specialization)
                            <option value="{{ $specialization->id }}" 
                                    {{ old('specialization_id', $doctor->specialization_id) == $specialization->id ? 'selected' : '' }}>
                                {{ $specialization->name }}
                            </option>
                        @endforeach --}}
                    </select>
                    @error('specialization_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Experience Years -->
                <div>
                    <label for="experience_years" class="block text-sm font-medium text-gray-700 mb-2">سنوات الخبرة</label>
                    <input type="number" name="experience_years" id="experience_years" 
                           value="{{-- {{ old('experience_years', $doctor->experience_years) }} --}}"
                           min="0" max="50"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('experience_years') border-red-500 @enderror"
                           placeholder="أدخل سنوات الخبرة">
                    @error('experience_years')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Bio -->
                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">السيرة الذاتية</label>
                    <textarea name="bio" id="bio" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('bio') border-red-500 @enderror"
                              placeholder="أدخل السيرة الذاتية للطبيب">{{-- {{ old('bio', $doctor->bio) }} --}}</textarea>
                    @error('bio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Actions -->
                <div class="flex gap-4 pt-6">
                    <button type="submit" 
                            class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition-colors">
                        <i class="fas fa-save ml-2"></i>
                        حفظ التغييرات
                    </button>
                    <a href="{{-- رابط العودة --}}" 
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
