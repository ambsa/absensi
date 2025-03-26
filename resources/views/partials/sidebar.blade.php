<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex">
    
    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 opacity-50 hidden lg:hidden"></div>
    
    <!-- Sidebar -->
    <div id="sidebar" class="w-64 h-screen bg-white bg-opacity-90 flex flex-col shadow-lg fixed lg:relative -left-64 lg:left-0 transition-all duration-300">
        <div class="p-4 border-b flex justify-between items-center">
            <h1 class="text-2xl font-bold">
                <a href="{{ Auth::user()->role->name === 'admin' ? route('admin.index') : route('user.index') }}">
                    Dashboard
                </a>
            </h1>
            <button id="closeSidebar" class="lg:hidden text-xl">
                <i class="fa-solid fa-bars-staggered"></i>
            </button>
        </div>
        
        <ul class="flex-1 overflow-y-auto">
            @if(Auth::user()->role->name === 'admin')
                <li class="hover:bg-gray-200 text-gray-600 font-semibold transition duration-200">
                    <a href="{{ route('admin.index') }}" class="flex items-center py-2 px-4 font-semibold">
                        <i class="fa-solid fa-house mr-3"></i> Home
                    </a>
                </li>
                <li class="hover:bg-gray-200 text-gray-600 font-semibold transition duration-200">
                    <a href="{{ route('admin.manage_user.index') }}" class="flex items-center py-2 px-4">
                        <i class="fas fa-users mr-3"></i> Manage Users
                    </a>
                </li>
                <li class="hover:bg-gray-200 text-gray-600 font-semibold transition duration-200">
                    <a href="{{ route('admin.attendance.index') }}" class="flex items-center py-2 px-4">
                        <i class="fa-solid fa-clipboard-list mr-3"></i> Attendance Data
                    </a>
                </li>
                <li class="hover:bg-gray-200 text-gray-600 font-semibold transition duration-200">
                    <a href="{{ route('admin.work_schedule.index') }}" class="flex items-center py-2 px-4">
                        <i class="fas fa-calendar-check mr-3"></i> Schedule
                    </a>
                </li>
            @elseif(Auth::user()->role->name === 'user')
                <li class="hover:bg-gray-200 text-gray-600 font-semibold transition duration-200">
                    <a href="{{ route('user.attendance') }}" class="flex items-center py-2 px-4">
                        <i class="fa-solid fa-calendar-day mr-3"></i> Aktivitas
                    </a>
                </li>
            @endif
        </ul>
        
        <div class="p-4 border-t border-gray-700">
            <form  id="logoutForm" method="POST" action="{{ route('logout') }}"">
                @csrf
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Logout
                </button>
            </form>
        </div>
    </div>
    
    <!-- Toggle Button -->
    <a id="openSidebar" class="lg:hidden fixed top-4 left-4 text-gray-500 p-2 rounded z-50">
        <i class="fa-solid fa-bars-staggered"></i>
    </a>
    
    <script>
        const sidebar = document.getElementById('sidebar');
        const openSidebar = document.getElementById('openSidebar');
        const closeSidebar = document.getElementById('closeSidebar');
        const overlay = document.getElementById('overlay');

        openSidebar.addEventListener('click', () => {
            sidebar.classList.remove('-left-64');
            sidebar.classList.add('left-0');
            overlay.classList.remove('hidden');
            openSidebar.classList.add('hidden');
        });

        function closeMenu() {
            sidebar.classList.remove('left-0');
            sidebar.classList.add('-left-64');
            overlay.classList.add('hidden');
            openSidebar.classList.remove('hidden');
        }

        closeSidebar.addEventListener('click', closeMenu);
        overlay.addEventListener('click', closeMenu);
    </script>
    
    <script src="https://kit.fontawesome.com/a329084b4e.js" crossorigin="anonymous"></script>
</body>
</html>
