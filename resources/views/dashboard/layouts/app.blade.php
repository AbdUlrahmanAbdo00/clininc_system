<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم - مركز صحي')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        [dir="rtl"] {
            direction: rtl;
        }
        .sidebar {
            transition: all 0.3s ease;
        }
        .sidebar.collapsed {
            width: 4rem;
        }
        .main-content {
            transition: all 0.3s ease;
        }
        .main-content.expanded {
            margin-right: 4rem;
        }
        /* Animated gradient background */
        .animated-bg {
            position: fixed;
            inset: 0;
            z-index: -10;
            background: linear-gradient(120deg, #ecfeff, #f0fdfa, #e3f9f7);
            background-size: 200% 200%;
            animation: gradientShift 16s ease infinite;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        /* Glass and hover effects to make UI feel modern without touching routes */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        .card-hover {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(16, 185, 129, 0.15);
        }
        .ring-progress {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background:
                radial-gradient(closest-side, white 79%, transparent 80% 100%),
                conic-gradient(#14b8a6 var(--value), #e5e7eb 0);
        }
        .ring-progress.sm { width: 48px; height: 48px; }
        .ring-progress.xs { width: 32px; height: 32px; }
        .tag-pill {
            padding: 2px 10px;
            border-radius: 9999px;
            background: #ecfeff;
            color: #0f766e;
            font-size: 12px;
        }
        .btn-press { transition: transform 0.1s ease; }
        .btn-press:active { transform: translateY(1px) scale(0.98); }
        .icon-hover { transition: transform 0.2s ease, color 0.2s ease; }
        .icon-hover:hover { transform: translateY(-2px); color: #0f766e; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="animated-bg"></div>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar bg-gradient-to-b from-teal-800 to-emerald-700 text-white w-64 min-h-screen">
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-bold">مركز صحي</h1>
                    <button id="sidebarToggle" class="text-white hover:text-gray-300">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
            
            <nav class="mt-8">
                <div class="px-4 space-y-2">
                    <a href="{{ route('dashboard.index') }}" 
                       class="flex items-center px-4 py-2 text-white/90 hover:bg-white/10 rounded-lg transition-colors">
                        <i class="fas fa-tachometer-alt ml-3"></i>
                        <span class="sidebar-text">لوحة التحكم</span>
                    </a>
                    
                    <a href="{{ route('dashboard.doctors.index') }}" 
                       class="flex items-center px-4 py-2 text-white/90 hover:bg-white/10 rounded-lg transition-colors">
                        <i class="fas fa-user-md ml-3"></i>
                        <span class="sidebar-text">الأطباء</span>
                    </a>
                    
                    <a href="{{ route('dashboard.specializations.index') }}" 
                       class="flex items-center px-4 py-2 text-white/90 hover:bg-white/10 rounded-lg transition-colors">
                        <i class="fas fa-stethoscope ml-3"></i>
                        <span class="sidebar-text">الاختصاصات</span>
                    </a>
                    
                    <a href="{{ route('dashboard.shifts.index') }}" 
                       class="flex items-center px-4 py-2 text-white/90 hover:bg-white/10 rounded-lg transition-colors">
                        <i class="fas fa-clock ml-3"></i>
                        <span class="sidebar-text">الشيفتات</span>
                    </a>
                    
                    <a href="{{ route('dashboard.patients.index') }}" 
                       class="flex items-center px-4 py-2 text-white/90 hover:bg-white/10 rounded-lg transition-colors">
                        <i class="fas fa-users ml-3"></i>
                        <span class="sidebar-text">المرضى</span>
                    </a>
                    
                    <a href="{{ route('dashboard.appointments.index') }}" 
                       class="flex items-center px-4 py-2 text-white/90 hover:bg-white/10 rounded-lg transition-colors">
                        <i class="fas fa-calendar-check ml-3"></i>
                        <span class="sidebar-text">المواعيد</span>
                    </a>
                    
                    <a href="{{ route('dashboard.reports') }}" 
                       class="flex items-center px-4 py-2 text-white/90 hover:bg-white/10 rounded-lg transition-colors">
                        <i class="fas fa-chart-bar ml-3"></i>
                        <span class="sidebar-text">التقارير</span>
                    </a>
                </div>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div id="mainContent" class="main-content flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white/90 backdrop-blur border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center gap-4">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'لوحة التحكم')</h2>
                            <p class="text-sm text-gray-600">@yield('page-description', 'إدارة مركز صحي')</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4 space-x-reverse">
                        <button id="quickAdd" class="hidden sm:flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-sm px-3 py-2 rounded-full">
                            <i class="fas fa-plus"></i>
                            إجراء سريع
                        </button>
                        <div class="relative">
                            <button class="flex items-center text-gray-700 hover:text-gray-900">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                            </button>
                        </div>
                        
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <img src="https://ui-avatars.com/api/?name=Admin&background=0D9488&color=fff" 
                                 alt="Admin" class="w-8 h-8 rounded-full">
                            <span class="text-sm font-medium text-gray-700">المدير</span>
                            <button id="logoutBtn" class="text-gray-500 hover:text-gray-700 mr-2">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                <div class="container mx-auto px-6 py-8">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script>
        // إضافة التوكن لجميع الطلبات
        function addAuthToken() {
            const token = localStorage.getItem('auth_token');
            if (token) {
                // إضافة التوكن لجميع طلبات fetch
                const originalFetch = window.fetch;
                window.fetch = function(url, options = {}) {
                    if (!options.headers) {
                        options.headers = {};
                    }
                    options.headers['Authorization'] = `Bearer ${token}`;
                    return originalFetch(url, options);
                };
                
                // إضافة التوكن لجميع طلبات XMLHttpRequest
                const originalXHROpen = XMLHttpRequest.prototype.open;
                XMLHttpRequest.prototype.open = function(method, url, async, user, password) {
                    const result = originalXHROpen.call(this, method, url, async, user, password);
                    this.addEventListener('readystatechange', function() {
                        if (this.readyState === 1) { // OPENED
                            const token = localStorage.getItem('auth_token');
                            if (token) {
                                this.setRequestHeader('Authorization', `Bearer ${token}`);
                            }
                        }
                    });
                    return result;
                };
            }
        }
        
        // تشغيل إضافة التوكن عند تحميل الصفحة
        addAuthToken();
        
        // Sidebar Toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            // Hide/show sidebar text
            const sidebarTexts = document.querySelectorAll('.sidebar-text');
            sidebarTexts.forEach(text => {
                text.classList.toggle('hidden');
            });
        });

        // Simple global search hook (client-only enhancement)
        // تم إزالة مربع البحث من الهيدر بناءً على طلب المستخدم

        // Quick add menu (example only)
        const quickAdd = document.getElementById('quickAdd');
        if (quickAdd) {
            quickAdd.addEventListener('click', function() {
                const menu = document.createElement('div');
                menu.className = 'fixed top-16 left-6 right-6 md:left-auto md:right-6 bg-white shadow-xl rounded-xl p-4 glass-card';
                menu.innerHTML = `
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-semibold text-gray-800">إجراءات سريعة</div>
                        <button class="text-gray-500 hover:text-gray-700" id="closeQuickAdd"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <a href="{{ route('dashboard.doctors.create') }}" class="card-hover border rounded-lg p-4 flex items-center justify-between">
                            <div class="text-sm text-gray-700">إضافة طبيب</div>
                            <i class="fas fa-user-plus text-teal-600"></i>
                        </a>
                        <a href="{{ route('dashboard.specializations.create') }}" class="card-hover border rounded-lg p-4 flex items-center justify-between">
                            <div class="text-sm text-gray-700">إضافة اختصاص</div>
                            <i class="fas fa-stethoscope text-teal-600"></i>
                        </a>
                        <a href="{{ route('dashboard.shifts.create') }}" class="card-hover border rounded-lg p-4 flex items-center justify-between">
                            <div class="text-sm text-gray-700">إضافة شيفت</div>
                            <i class="fas fa-clock text-teal-600"></i>
                        </a>
                    </div>
                `;
                document.body.appendChild(menu);
                document.getElementById('closeQuickAdd').addEventListener('click', () => menu.remove());
                setTimeout(() => {
                    const handler = (e) => { if (!menu.contains(e.target)) { menu.remove(); document.removeEventListener('click', handler); } };
                    document.addEventListener('click', handler);
                }, 0);
            });
        }
        
        // Animate ring-progress via CSS custom property
        function initRingProgress(){
            const rings = document.querySelectorAll('.ring-progress');
            rings.forEach(r => {
                const p = Number(r.getAttribute('data-progress') || 0);
                r.style.setProperty('--value', p + '%');
            });
        }
        
        // Simple count-up animation for numbers
        function runCountUp(){
            const nodes = document.querySelectorAll('.count-up');
            nodes.forEach(node => {
                const target = Number(node.getAttribute('data-target') || 0);
                let current = 0;
                const duration = 800; // ms
                const step = Math.max(1, Math.floor(target / (duration / 16)));
                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) { current = target; clearInterval(timer); }
                    node.textContent = current.toString();
                }, 16);
            });
        }
        
        // Lightweight chart using Chart.js CDN (only on pages that include a canvas#appointmentsChart)
        async function initCharts(){
            const canvas = document.getElementById('appointmentsChart');
            if (!canvas) return;
            if (!window.Chart) {
                await new Promise((resolve) => {
                    const s = document.createElement('script');
                    s.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                    s.onload = resolve; document.head.appendChild(s);
                });
            }
            const ctx = canvas.getContext('2d');
            const data = [12, 18, 9, 22, 17, 26, 30]; // بيانات وهمية للعرض فقط
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['أحد','اثن','ثلث','أربع','خمس','جمع','سبت'],
                    datasets: [{
                        label: 'المواعيد',
                        data,
                        borderColor: '#14b8a6',
                        backgroundColor: 'rgba(20,184,166,0.15)',
                        tension: 0.35,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { x: { grid: { display: false } }, y: { grid: { color: '#eef2f7' } } }
                }
            });
        }
        
        // Init visual effects on load
        window.addEventListener('DOMContentLoaded', function(){
            initRingProgress();
            runCountUp();
            initCharts();
        });

        // Logout functionality
        document.getElementById('logoutBtn').addEventListener('click', function() {
            if (confirm('هل تريد تسجيل الخروج؟')) {
                fetch('/logout', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.redirected) {
                        // إذا كان هناك redirect، اتبع الرابط
                        window.location.href = response.url;
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.success) {
                        // حذف التوكن من localStorage
                        localStorage.removeItem('auth_token');
                        
                        // إعادة التوجيه لصفحة تسجيل الدخول
                        window.location.href = data.redirect;
                    }
                })
                .catch(error => {
                    console.error('Error during logout:', error);
                    // إعادة التوجيه مباشرة في حالة الخطأ
                    window.location.href = '/login';
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html> 