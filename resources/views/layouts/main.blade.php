<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Flex:opsz,wght@8..144,100..1000&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

</head>

<body class="bg-[#161A23] flex h-screen w-full">

    <a id="openSidebar" class="lg:hidden fixed top-4 left-4 text-white p-2 rounded z-50">
        <i class="fa-solid fa-bars-staggered"></i>
    </a>

    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-white  opacity-50 hidden lg:hidden"></div>


    <!-- Sidebar -->
    @include('partials.sidebar')


    <!-- Main Content -->
    <div class="flex-1 md:ml-64 overflow-y-auto w-full bg-[#161A23] text-white">
        <!-- Header -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 mb-6">
            <div class="flex justify-between items-center">
                <h1 class="ml-10 sm:ml-0 text-lg sm:text-xl font-bold text-gray-300">{{ $pageTitle }}</h1>
                <!-- Dropdown Profil -->
                @include('partials.profile')
            </div>
        </div>

        <!-- Konten Utama -->
        <!-- Konten Utama -->
        <div class="mt-4 overflow-y-auto min-h-screen">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </div>






        <script src="https://cdn.tailwindcss.com "></script>
        <script src="https://kit.fontawesome.com/a329084b4e.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('assets/js/script.js') }}"></script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const openSidebarButton = document.getElementById('openSidebar');
                const closeSidebarButton = document.getElementById('closeSidebar');
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('overlay');

                function toggleSidebar() {
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');

                    // Sembunyikan/munculkan burger button sesuai kebutuhan
                    if (!sidebar.classList.contains('-translate-x-full')) {
                        // Sidebar dibuka → sembunyikan burger button
                        openSidebarButton.classList.add('hidden');
                    } else {
                        // Sidebar ditutup → tampilkan burger button
                        openSidebarButton.classList.remove('hidden');
                    }
                }

                openSidebarButton.addEventListener('click', toggleSidebar);
                closeSidebarButton.addEventListener('click', () => {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                    openSidebarButton.classList.remove(
                    'hidden'); // Tampilkan burger button saat sidebar ditutup
                });

                overlay.addEventListener('click', () => {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                    openSidebarButton.classList.remove('hidden');
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const toggleButton = document.getElementById('toggleAbsenMenu');
                const dropdownContent = document.getElementById('absenDropdownContent');
                const arrowIcon = document.getElementById('absenArrow');
        
                if (toggleButton && dropdownContent && arrowIcon) {
                    toggleButton.addEventListener('click', function () {
                        dropdownContent.classList.toggle('hidden');
                        arrowIcon.classList.toggle('rotate-180');
                    });
                }
            });
        </script>
</body>

</html>
