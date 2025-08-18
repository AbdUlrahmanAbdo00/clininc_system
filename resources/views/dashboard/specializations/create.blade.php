@extends('dashboard.layouts.app')

@section('title', 'إضافة اختصاص جديد - مركز صحي')
@section('page-title', 'إضافة اختصاص جديد')
@section('page-description', 'إضافة اختصاص طبي جديد إلى النظام')

@section('content')
{{-- 
    المتغيرات المطلوبة من Controller:
    لا توجد متغيرات مطلوبة لهذه الصفحة
--}}
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">معلومات الاختصاص</h2>
            <p class="text-gray-600">أدخل معلومات الاختصاص الجديد</p>
        </div>
        
        <form action="/dashboard/specializations" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم الاختصاص</label>
                    <input type="text" name="name" id="name" 
                           value="{{ old('name') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('name') border-red-500 @enderror"
                           placeholder="أدخل اسم الاختصاص">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">وصف الاختصاص</label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('description') border-red-500 @enderror"
                              placeholder="أدخل وصف الاختصاص">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Actions -->
                <div class="flex gap-4 pt-6">
                    <button type="submit" 
                            class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition-colors">
                        <i class="fas fa-save ml-2"></i>
                        حفظ الاختصاص
                    </button>
                    <a href="/dashboard/specializations" 
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