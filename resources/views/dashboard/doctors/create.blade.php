@extends('dashboard.layouts.app')

@section('title', 'إضافة طبيب جديد - مركز صحي')
@section('page-title', 'إضافة طبيب جديد')
@section('page-description', 'إضافة طبيب جديد إلى النظام')

@section('content')
{{-- 
    المتغيرات المطلوبة من Controller:
    // $specializations = قائمة جميع الاختصاصات للاختيار
--}}
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">معلومات الطبيب</h2>
            <p class="text-gray-600">أدخل معلومات الطبيب الجديد</p>
        </div>
        
        <form action="{{-- رابط حفظ الطبيب --}}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم الطبيب</label>
                    <input type="text" name="name" id="name" 
                           value="{{-- قيمة الاسم القديمة --}}"
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
                           value="{{-- قيمة البريد القديم --}}"
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
                           value="{{-- قيمة الهاتف القديمة --}}"
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
                        {{-- حلقه لاختيار الاختصاصات --}}
                    </select>
                    @error('specialization_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Experience Years -->
                <div>
                    <label for="experience_years" class="block text-sm font-medium text-gray-700 mb-2">سنوات الخبرة</label>
                    <input type="number" name="experience_years" id="experience_years" 
                           value="{{-- قيمة سنوات الخبرة القديمة --}}"
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
                              placeholder="أدخل السيرة الذاتية للطبيب">{{-- السيرة الذاتية القديمة --}}</textarea>
                    @error('bio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Actions -->
                <div class="flex gap-4 pt-6">
                    <button type="submit" 
                            class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition-colors">
                        <i class="fas fa-save ml-2"></i>
                        حفظ الطبيب
                    </button>
                    <a href="{{-- رابط إلغاء العملية --}}" 
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
