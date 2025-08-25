<!DOCTYPE html>
<html lang="en" class="{{ session('theme', 'light') === 'dark' ? 'dark' : '' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Absensi Dashboard'))</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/fyplogohead.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/fyplogohead.png') }}">

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {

                }
            }
        }
    </script>

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Flex:opsz,wght@8..144,100..1000&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

    <!-- css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <!-- CSRF Token (Laravel) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="flex h-screen w-full bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

    <!-- Sidebar -->
    <div id="sidebar" class="bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100">
        @include('partials.sidebar')
    </div>

    <!-- Main Content -->
    <div
        class="flex-1 md:ml-64 overflow-y-auto bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex flex-col">

        <!-- Header / Navbar Atas -->
        <header class="px-4 sm:px-6 lg:px-8 py-4 shadow-sm bg-white dark:bg-gray-800 sticky top-0 z-20">
            <div class="container mx-auto flex items-center">
                <!-- Tombol Hamburger untuk Mobile -->
                <a id="openSidebar"
                    class="lg:hidden text-gray-800 dark:text-gray-200 p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer mr-2">
                    <i class="fa-solid fa-bars-staggered"></i>
                </a>

                <!-- Judul Dashboard -->
                <h1 class="text-md sm:text-2xl font-bold text-gray-800 dark:text-white sm:mr-4 text-left">
                    {{ $pageTitle ?? 'Dashboard' }}
                </h1>

                <!-- Dropdown Profil Pengguna -->
                @include('partials.profile')
            </div>
        </header>

        <!-- Konten Utama -->
        <main class="px-4 sm:px-6 lg:px-8 py-6 flex-1">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer
            class="px-4 sm:px-6 lg:px-8 py-4 flex justify-center items-center text-sm text-gray-900 dark:text-gray-100 space-x-4 bg-gray-100 dark:bg-gray-800">
            <span>&copy; {{ now()->year }} - by Ahmad Fatah Maulana</span>
        </footer>
    </div>


    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a329084b4e.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const html = document.documentElement;

    function initializeTheme() {
        if (localStorage.theme === 'dark') {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }
        updateIcon();
    }

    function updateIcon() {
        const isDark = html.classList.contains('dark');
        const themeIcon = document.getElementById('theme-icon');
        const themeIconSun = document.getElementById('theme-icon-sun');
        
        if (themeIcon && themeIconSun) {
            if (isDark) {
                themeIcon.classList.add('opacity-0', 'absolute');
                themeIconSun.classList.remove('opacity-0', 'absolute');
            } else {
                themeIconSun.classList.add('opacity-0', 'absolute');
                themeIcon.classList.remove('opacity-0', 'absolute');
            }
        }
    }

    function toggleTheme() {
        const isDark = html.classList.toggle('dark');
        localStorage.theme = isDark ? 'dark' : 'light';
        updateIcon();
    }

    initializeTheme();
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }

            initializeTheme();
            if (themeToggle) {
                themeToggle.addEventListener('click', toggleTheme);
            }

            // SIDEBAR DROPDOWN (kode Anda tetap sama)
            const toggleButton = document.getElementById('toggleAbsenMenu');
            const dropdownContent = document.getElementById('absenDropdownContent');
            const arrowIcon = document.getElementById('absenArrow');

            if (toggleButton && dropdownContent && arrowIcon) {
                function openDropdown() {
                    dropdownContent.classList.add('open');
                    arrowIcon.classList.add('rotate-180');
                    dropdownContent.style.maxHeight = dropdownContent.scrollHeight + 'px';
                }

                function closeDropdown() {
                    dropdownContent.classList.remove('open');
                    arrowIcon.classList.remove('rotate-180');
                    dropdownContent.style.maxHeight = '0px';
                }

                const isSubmenuActive = Array.from(dropdownContent.querySelectorAll('a')).some(link => link
                    .classList.contains('bg-gray-700'));
                if (isSubmenuActive) openDropdown();

                toggleButton.addEventListener('click', function() {
                    dropdownContent.classList.contains('open') ? closeDropdown() : openDropdown();
                });

                dropdownContent.addEventListener('click', function(event) {
                    if (event.target.tagName === 'A' && event.target.closest('#absenDropdownContent')) {
                        openDropdown();
                    }
                });
            }
        });

        // MOBILE SIDEBAR
        const openSidebarButton = document.getElementById('openSidebar');
        const closeSidebarButton = document.getElementById('closeSidebar');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        if (openSidebarButton && closeSidebarButton && sidebar && overlay) {
            function toggleSidebar() {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
                openSidebarButton.classList.toggle('hidden', !sidebar.classList.contains('-translate-x-full'));
            }
            openSidebarButton.addEventListener('click', toggleSidebar);
            closeSidebarButton.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                openSidebarButton.classList.remove('hidden');
            });
            overlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                openSidebarButton.classList.remove('hidden');
            });
        }
    </script>

    <script src="{{ asset('assets/js/script.js') }}"></script>

    @stack('scripts')


</body>

</html>
