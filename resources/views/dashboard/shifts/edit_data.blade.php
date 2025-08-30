@extends('layouts.dashboard')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <!-- العنوان -->
    <h1 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i class="fas fa-edit text-teal-600"></i>
        تعديل بيانات الشيفت - {{ $shift->shift_type }}
    </h1>

    <!-- رسائل النجاح -->
    @if (session('success'))
        <div id="success-toast" class="fixed top-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-2 animate-slide-in">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        <script>
            setTimeout(() => {
                document.getElementById('success-toast').remove();
                window.location.href = "{{ route('shifts.index') }}";
            }, 2000);
        </script>
    @endif

    <!-- رسائل الأخطاء -->
    @if ($errors->any())
        <div class="rounded-lg bg-red-50 border border-red-300 p-4 flex items-start mb-6">
            <i class="fas fa-exclamation-circle text-red-600 ml-2 mt-1"></i>
            <div>
                <p class="text-red-800 font-semibold mb-1">يوجد بعض الأخطاء:</p>
                <ul class="list-disc pr-5 text-red-700 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- الفورم -->
    <form action="{{ route('shifts.update', $shift->id) }}" method="POST" class="space-y-6 bg-white rounded-xl shadow p-6">
        @csrf

        <!-- قسم أوقات الشيفت -->
        <div class="border-b pb-4">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="fas fa-clock text-teal-600"></i> أوقات الشيفت
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 mb-1">وقت البداية</label>
                    <input type="time" name="start_time" value="{{ $shift->start_time }}" required class="input-field">
                </div>
                <div>
                    <label class="block text-gray-700 mb-1">وقت النهاية</label>
                    <input type="time" name="end_time" value="{{ $shift->end_time }}" required class="input-field">
                </div>
            </div>
        </div>

        <!-- قسم الاستراحة -->
        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="fas fa-coffee text-teal-600"></i> أوقات الاستراحة
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 mb-1">بداية الاستراحة</label>
                    <input type="time" name="break_start" value="{{ $shift->break_start }}" class="input-field">
                </div>
                <div>
                    <label class="block text-gray-700 mb-1">نهاية الاستراحة</label>
                    <input type="time" name="break_end" value="{{ $shift->break_end }}" class="input-field">
                </div>
            </div>
        </div>

        <!-- الأزرار -->
        <div class="flex items-center gap-4 pt-4 border-t">
            <button type="submit" id="saveBtn" class="bg-teal-600 hover:bg-teal-700 text-white px-5 py-2 rounded-lg shadow transition flex items-center gap-2">
                <i class="fas fa-save"></i>
                <span id="btnText">حفظ التغييرات</span>
                <span id="spinner" class="hidden animate-spin"><i class="fas fa-spinner"></i></span>
            </button>

            <a href="{{ route('shifts.index') }}" class="border border-teal-600 text-teal-600 hover:bg-teal-50 px-5 py-2 rounded-lg transition">
                إلغاء
            </a>
        </div>
    </form>
</div>

<script>
    const form = document.querySelector('form');
    const saveBtn = document.getElementById('saveBtn');
    const btnText = document.getElementById('btnText');
    const spinner = document.getElementById('spinner');

    form.addEventListener('submit', () => {
        saveBtn.disabled = true;
        btnText.textContent = 'جاري الحفظ...';
        spinner.classList.remove('hidden');
    });
</script>

<style>
    .input-field {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        transition: border-color 0.2s;
    }
    .input-field:focus {
        border-color: #14b8a6;
        outline: none;
        box-shadow: 0 0 0 2px #99f6e4;
    }
    @keyframes slide-in {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .animate-slide-in {
        animation: slide-in 0.4s ease-out;
    }
</style>
@endsection
