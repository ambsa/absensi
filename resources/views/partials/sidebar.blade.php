<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    {{-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> --}}
</head>

<body class="flex">

    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 opacity-50 hidden lg:hidden"></div>

    <!-- Sidebar -->
    <div id="sidebar"
    class="w-64 h-screen bg-[#161A23] bg-opacity-90 flex flex-col fixed top-0 left-0 z-10 transition-all duration-300">
    <div class="p-5 flex justify-between items-center">
        <div>
            <a href="{{ Auth::user()->role->name === 'admin' ? route('admin.index') : route('user.index') }}"
                class="flex items-center justify-center">
                <img src="{{ asset('assets/images/fyplogo.png') }}" alt="FYP Logo" class="w-auto h-5 mx-auto mb-4">
            </a>
        </div>
        <button id="closeSidebar" class="lg:hidden text-xl">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>
    </div>
    <!-- Garis Pemisah -->
    <hr class="mx-4 border border-gray-700">
    <div class="px-4 py-2 ml-3 text-xs text-gray-500 font-bold">
        MAIN
    </div>
        <ul class="flex-1 overflow-y-auto">
            @if (Auth::user()->role->name === 'admin')
                <!-- Home -->
                <li class="text-gray-500 p-1 font-semibold transition duration-200">
                    <div
                        class="w-11/12 mx-auto py-1 {{ Request::is('admin') ? 'bg-gray-600 rounded-lg' : 'hover:bg-gray-600 rounded-lg' }}">
                        <a href="{{ route('admin.index') }}"
                            class="flex items-center py-2 px-4 font-semibold 
                                  {{ Request::is('admin') ? 'text-gray-300' : 'text-gray-500 hover:text-gray-300' }} 
                                  transition duration-200 w-full">
                            <i class="fa-solid fa-house mr-3"></i> Beranda
                        </a>
                    </div>
                </li>

                <!-- pegawai -->
                <li class="text-gray-500 p-1 font-semibold transition duration-200">
                    <div
                        class="w-11/12 mx-auto py-1 {{ Request::is('admin/pegawai*') ? 'bg-gray-600 rounded-lg' : 'hover:bg-gray-600 rounded-lg' }}">
                        <a href="{{ route('admin.pegawai.index') }}"
                            class="flex items-center py-2 px-4 font-semibold 
                                  {{ Request::is('admin/pegawai*') ? 'text-gray-300' : 'text-gray-500 hover:text-gray-300' }} 
                                  transition duration-200 w-full">
                            <i class="fas fa-users mr-3"></i> Pegawai
                        </a>
                    </div>
                </li>

                <!-- data absensi -->
                <li class="text-gray-500 p-1 font-semibold transition duration-200">
                    <div class="w-11/12 mx-auto py-1 {{ Request::is('admin/data_absen*') ? 'bg-gray-600 rounded-lg' : 'hover:bg-gray-600 rounded-lg' }}">
                        <a href="{{ route('admin.data_absen.index') }}"
                            class="flex items-center py-2 px-4 font-semibold 
                                  {{ Request::is('admin/data_absen*') ? 'text-gray-300' : 'text-gray-500 hover:text-gray-300' }} 
                                  transition duration-200 w-full">
                            <i class="fa-solid fa-box-archive mr-3"></i> Data Absensi
                        </a>
                    </div>
                </li>

                <li class="text-gray-500 p-1 font-semibold transition duration-200">
                    <div class="w-11/12 mx-auto py-1 {{ Request::is('admin/cuti*') ? 'bg-gray-600 rounded-lg' : 'hover:bg-gray-600 rounded-lg' }}">
                        <a href="{{ route('admin.cuti.index') }}"
                            class="flex items-center py-2 px-4 font-semibold 
                                  {{ Request::is('admin/cuti*') ? 'text-gray-300' : 'text-gray-500 hover:text-gray-300' }} 
                                  transition duration-200 w-full">
                            <i class="fa-solid fa-box-archive mr-3"></i> Cuti
                        </a>
                    </div>
                </li>
                
                    {{-- <!-- Dropdown Menu (Awalnya Tersembunyi) -->
                    <div id="pengajuanDropdownContent"
                        class="absolute top-full left-10 mt-1 w-40 bg-gray-700 rounded-lg shadow-md hidden opacity-0 transition-all duration-300 ease-in-out transform origin-top">
                        <ul class="py-2">
                            <!-- Submenu Izin -->
                            <li>
                                <a href="#"
                                    class="flex items-center py-2 px-4 text-gray-400 hover:text-gray-300 hover:bg-gray-600 transition duration-200 w-full">
                                    <i class="fa-solid fa-file-lines mr-3"></i> Izin
                                </a>
                            </li>
                            <!-- Submenu Cuti -->
                            <li>
                                <a href=""
                                    class="flex items-center py-2 px-4 text-gray-400 hover:text-gray-300 hover:bg-gray-600 transition duration-200 w-full">
                                    <i class="fa-solid fa-calendar-days mr-3"></i> Cuti
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> --}}

                <!-- Schedule -->
            @elseif(Auth::user()->role->name === 'user')
            <!-- Dashboard -->
            <li class="text-gray-500 p-1 font-semibold transition duration-200">
                <div
                    class="w-11/12 mx-auto py-1 {{ Request::is('user') ? 'bg-gray-600 rounded-lg' : 'hover:bg-gray-600 rounded-lg' }}">
                    <a href="{{ route('user.index') }}"
                        class="flex items-center py-2 px-4 font-semibold 
                              {{ Request::is('user') ? 'text-gray-300' : 'text-gray-500 hover:text-gray-300' }} 
                              transition duration-200 w-full">
                        <i class="fa-solid fa-house mr-3"></i> Beranda
                    </a>
                </div>
            </li>
             <!-- Pengajuan -->
             <li class="text-gray-500 p-1 font-semibold transition duration-200 relative">
                <!-- Menu Utama -->
                <div class="w-11/12 mx-auto py-1 cursor-pointer" id="pengajuanMenu">
                    <a href="{{ route('user.cuti.index') }}"
                        class="flex items-center py-2 px-4 font-semibold text-gray-500 hover:text-gray-300 transition duration-200 w-full">
                        <i class="fa-solid fa-clipboard-list mr-3"></i> Cuti
                    </a>
                </div>
            
                <!-- Dropdown Menu (Awalnya Tersembunyi) -->
                {{-- <div id="pengajuanDropdownContent"
                    class="absolute top-full left-10 mt-1 w-40 bg-gray-700 rounded-lg shadow-md hidden opacity-0 transition-all duration-300 ease-in-out transform origin-top">
                    <ul class="py-2">
                        <!-- Submenu Izin -->
                        <li>
                            <a href="#"
                                class="flex items-center py-2 px-4 text-gray-400 hover:text-gray-300 hover:bg-gray-600 transition duration-200 w-full">
                                <i class="fa-solid fa-file-lines mr-3"></i> Izin
                            </a>
                        </li>
                        <!-- Submenu Cuti -->
                        <li>
                            <a href="#"
                                class="flex items-center py-2 px-4 text-gray-400 hover:text-gray-300 hover:bg-gray-600 transition duration-200 w-full">
                                <i class="fa-solid fa-calendar-days mr-3"></i> Cuti
                            </a>
                        </li>
                    </ul>
                </div> --}}
            </li>
            
            @endif
        </ul>

        {{-- <div class="p-4 border-t border-gray-700">
            <form id="logoutForm" method="POST" action="{{ route('logout') }}"">
                @csrf
                <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Logout
                </button>
            </form>
        </div> --}}
    </div>

    <!-- Toggle Button -->
    <a id="openSidebar" class="lg:hidden fixed top-4 left-4 text-gray-500 p-2 rounded z-50">
        <i class="fa-solid fa-bars-staggered"></i>
    </a>

    <script src="https://kit.fontawesome.com/a329084b4e.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>
