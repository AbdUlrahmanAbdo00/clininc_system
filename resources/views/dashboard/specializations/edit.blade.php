@extends('dashboard.layouts.app')

@section('title', 'تعديل الاختصاص - مركز صحي')
@section('page-title', 'تعديل الاختصاص')
@section('page-description', 'تعديل بيانات الاختصاص')

@section('content')
{{-- 
    المتغيرات المطلوبة من Controller:
    $specialization = بيانات الاختصاص المراد تعديله
--}}
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">تعديل معلومات الاختصاص</h2>
            <p class="text-gray-600">تعديل بيانات الاختصاص: {{ $specialization->name }}</p>
        </div>
        
        <form action="{{ route('dashboard.specializations.update', $specialization->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم الاختصاص</label>
                    <input type="text" name="name" id="name" 
                           value="{{ old('name', $specialization->name) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('name') border-red-500 @enderror"
                           placeholder="أدخل اسم الاختصاص">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Current Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الصورة الحالية</label>
                    <div class="flex items-center space-x-4">
                        @if($specialization->path)
                            <div class="relative">
                                <img src="{{ $specialization->path }}" 
                                     alt="صورة الاختصاص الحالية" 
                                     class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200">
                                <div class="absolute top-2 right-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">
                                    الصورة الحالية
                                </div>
                            </div>
                        @else
                            <div class="w-32 h-32 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                <span class="text-gray-500 text-sm">لا توجد صورة</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- New Image -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        صورة الاختصاص الجديدة
                        <span class="text-gray-500 text-xs">(اختياري - اتركه فارغاً للاحتفاظ بالصورة الحالية)</span>
                    </label>
                    <input type="file" name="image" id="image" 
                           accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('image') border-red-500 @enderror">
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        الصيغ المدعومة: JPG, PNG, GIF, SVG. الحد الأقصى: 2MB
                    </p>
                </div>
                
                <!-- Actions -->
                <div class="flex gap-4 pt-6">
                    <button type="submit" 
                            class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition-colors">
                        <i class="fas fa-save ml-2"></i>
                        حفظ التغييرات
                    </button>
                    <a href="{{ route('dashboard.specializations.index') }}" 
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