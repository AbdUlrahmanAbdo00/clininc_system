<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar bg-teal-800 text-white w-64 min-h-screen">
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
                    <a href="/dashboard" 
                       class="flex items-center px-4 py-2 text-white hover:bg-teal-700 rounded-lg transition-colors">
                        <i class="fas fa-tachometer-alt ml-3"></i>
                        <span class="sidebar-text">لوحة التحكم</span>
                    </a>
                    
                    <a href="/dashboard/doctors" 
                       class="flex items-center px-4 py-2 text-white hover:bg-teal-700 rounded-lg transition-colors">
                        <i class="fas fa-user-md ml-3"></i>
                        <span class="sidebar-text">الأطباء</span>
                    </a>
                    
                    <a href="/dashboard/specializations" 
                       class="flex items-center px-4 py-2 text-white hover:bg-teal-700 rounded-lg transition-colors">
                        <i class="fas fa-stethoscope ml-3"></i>
                        <span class="sidebar-text">الاختصاصات</span>
                    </a>
                    
                    <a href="/dashboard/shifts" 
                       class="flex items-center px-4 py-2 text-white hover:bg-teal-700 rounded-lg transition-colors">
                        <i class="fas fa-clock ml-3"></i>
                        <span class="sidebar-text">الشيفتات</span>
                    </a>
                    
                    <a href="/dashboard/patients" 
                       class="flex items-center px-4 py-2 text-white hover:bg-teal-700 rounded-lg transition-colors">
                        <i class="fas fa-users ml-3"></i>
                        <span class="sidebar-text">المرضى</span>
                    </a>
                    
                    <a href="/dashboard/appointments" 
                       class="flex items-center px-4 py-2 text-white hover:bg-teal-700 rounded-lg transition-colors">
                        <i class="fas fa-calendar-check ml-3"></i>
                        <span class="sidebar-text">المواعيد</span>
                    </a>
                    
                    <a href="/dashboard/reports" 
                       class="flex items-center px-4 py-2 text-white hover:bg-teal-700 rounded-lg transition-colors">
                        <i class="fas fa-chart-bar ml-3"></i>
                        <span class="sidebar-text">التقارير</span>
                    </a>
                </div>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div id="mainContent" class="main-content flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'لوحة التحكم')</h2>
                        <p class="text-sm text-gray-600">@yield('page-description', 'إدارة مركز صحي')</p>
                    </div>
                    
                    <div class="flex items-center space-x-4 space-x-reverse">
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
    </script>
    
    @stack('scripts')
</body>
</html> 