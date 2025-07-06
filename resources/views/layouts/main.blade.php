<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Absensi Dashboard'))</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/fyplogohead.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/fyplogohead.png') }}">

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Flex:opsz,wght@8..144,100..1000&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <!-- CSRF Token (Laravel) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="flex h-screen w-full bg-[#161A23]">


    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black opacity-50 hidden lg:hidden z-40"></div>

    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 md:ml-64 overflow-y-auto bg-[#161A23] text-white flex flex-col">

        <!-- Header / Navbar Atas -->
        <header class="px-4 sm:px-6 lg:px-8 py-4 shadow-sm bg-[#161A23] sticky top-0 z-20">
            <div class="container mx-auto flex items-center">

                <!-- Tombol Hamburger untuk Mobile -->
                <a id="openSidebar" class="lg:hidden text-white p-2 rounded-full hover:bg-gray-700 cursor-pointer mr-2">
                    <i class="fa-solid fa-bars-staggered"></i>
                </a>

                <!-- Judul Dashboard -->
                <h1 class="text-md sm:text-2xl font-bold text-gray-300 sm:mr-4 text-left">
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
        <footer class="px-4 sm:px-6 lg:px-8 py-4 flex justify-center items-center text-sm text-gray-500 space-x-4">
            <span>&copy; {{ now()->year }} - by Ahmad Fatah Maulana</span>
            {{-- <a href="#" class="hover:text-white transition">Absensi Dashboard</a>
        <span>|</span>
        <a href="#" class="hover:text-white transition">Privacy Policy</a>
        <span>|</span>
        <a href="#" class="hover:text-white transition">Terms of Service</a> --}}
        </footer>
    </div>


    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a329084b4e.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        // UNTUK ICON PANAH DROPDOWN PADA SIDEBAR
        document.addEventListener('DOMContentLoaded', function () {
        const toggleButton = document.getElementById('toggleAbsenMenu');
        const dropdownContent = document.getElementById('absenDropdownContent');
        const arrowIcon = document.getElementById('absenArrow');

        // Fungsi untuk membuka dropdown
        function openDropdown() {
            dropdownContent.classList.add('open');
            arrowIcon.classList.add('rotate-180');
            dropdownContent.style.maxHeight = dropdownContent.scrollHeight + 'px';
        }

        // Fungsi untuk menutup dropdown
        function closeDropdown() {
            dropdownContent.classList.remove('open');
            arrowIcon.classList.remove('rotate-180');
            dropdownContent.style.maxHeight = '0px';
        }

        // Periksa apakah salah satu submenu sedang aktif
        const isSubmenuActive = Array.from(dropdownContent.querySelectorAll('a')).some(link => {
            return link.classList.contains('bg-gray-700'); // Cek kelas aktif dari submenu
        });

        // Jika submenu aktif, buka dropdown secara otomatis
        if (isSubmenuActive) {
            openDropdown();
        }

        // Event listener untuk tombol toggle
        toggleButton.addEventListener('click', function () {
            if (dropdownContent.classList.contains('open')) {
                closeDropdown();
            } else {
                openDropdown();
            }
        });

        // Event delegation untuk mendeteksi klik pada submenu
        dropdownContent.addEventListener('click', function (event) {
            const target = event.target;

            // Pastikan dropdown tetap terbuka saat mengklik submenu
            if (target.tagName === 'A' && target.closest('#absenDropdownContent')) {
                openDropdown();
            }
        });

        // Tutup dropdown jika pengguna mengklik di luar dropdown
        // document.addEventListener('click', function (event) {
        //     const isClickInsideDropdown = dropdownContent.contains(event.target);
        //     const isClickOnToggleButton = toggleButton.contains(event.target);

        //     if (!isClickInsideDropdown && !isClickOnToggleButton) {
        //         closeDropdown();
        //     }
        // });
    });
        // ====================================================================

        // UNTUK MEMBUKA SIDEBAR PADA LAYAR KECIL
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
    // ====================================================================
        
    </script>    

    <script src="{{ asset('assets/js/script.js') }}"></script>


    @stack('scripts')


</body>

</html>
