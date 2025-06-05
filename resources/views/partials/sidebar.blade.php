<!-- Sidebar -->
<aside id="sidebar"
    class="w-64 h-screen bg-[#161A23] bg-opacity-90 flex-col fixed top-0 left-0 z-40 transition-all duration-300 transform -translate-x-full lg:translate-x-0">
    
    <div class="p-5 flex justify-between items-center">
        <a href="{{ Auth::user()->role->name === 'admin' ? route('admin.index') : route('user.index') }}"
            class="flex items-center justify-center">
            <img src="{{ asset('assets/images/fyplogo.png') }}" alt="FYP Logo" class="h-5 mx-auto mb-4">
        </a>
        <button id="closeSidebar" class="lg:hidden text-md text-white">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    {{-- <hr class="mx-4 border-gray-700 my-2"> --}}

    <div class="px-4 py-2 ml-3 text-xs text-gray-500 font-bold">
        MAIN
    </div>

    <ul class="px-4 space-y-1 overflow-y-auto flex-1 pb-10">
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

            <!-- Pegawai -->
            <li class="text-gray-500 p-1 font-semibold transition duration-200">
                <div class="w-11/12 mx-auto py-1 {{ Request::is('admin/pegawai') ? 'bg-gray-600 rounded-lg' : 'hover:bg-gray-600 rounded-lg' }}">
                    <a href="{{ route('admin.pegawai.index') }}"
                        class="flex items-center py-2 px-4 font-semibold 
                              {{ Request::is('admin/pegawai') ? 'text-gray-300' : 'text-gray-500 hover:text-gray-300' }} 
                              transition duration-200 w-full">
                        <i class="fas fa-users mr-3"></i> Pegawai
                    </a>
                </div>
            </li>

            <!-- Data Absensi -->
            <li class="text-gray-500 p-1 font-semibold transition duration-200 relative">
                <!-- Tombol Induk -->
                <div id="toggleAbsenMenu"
                    class="w-11/12 mx-auto py-1 rounded-lg cursor-pointer transition-all duration-200 hover:bg-gray-600">
                    <div class="flex items-center justify-between px-4 py-2 font-semibold text-gray-500">
                        <span class="flex items-center">
                            <i class="fa-solid fa-box-archive mr-3"></i> Absensi
                        </span>
                        <i id="absenArrow" class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                    </div>
                </div>
            
                <!-- Dropdown Content -->
                <div id="absenDropdownContent" class="mt-1 space-y-2 px-2 {{ Request::is('admin/data_absen*') ? '' : 'hidden' }}">
                    <!-- Riwayat Absensi -->
                    <a href="{{ route('admin.data_absen.index') }}"
                        class="block py-2 px-6 text-sm rounded-md transition duration-200 
                        @if (Route::currentRouteName() == 'admin.data_absen.index') bg-gray-700 @else hover:bg-gray-700 @endif text-gray-400 hover:text-white">
                        <i class="fa-solid fa-list mr-2"></i> Riwayat Absensi
                    </a>
            
                    <!-- Isi Catatan -->
                    <a href="{{ route('admin.data_absen.catatan') }}"
                        class="block py-2 px-6 text-sm rounded-md transition duration-200 
                        @if (Route::currentRouteName() == 'admin.data_absen.catatan') bg-gray-700 @else hover:bg-gray-700 @endif text-gray-400 hover:text-white">
                        <i class="fa-solid fa-pen-to-square mr-2"></i> Isi Catatan
                    </a>

                    <!-- Isi Catatan -->
                    <a href="{{ route('admin.wfh.index') }}"
                        class="block py-2 px-6 text-sm rounded-md transition duration-200 
                        @if (Route::currentRouteName() == 'admin.wfh.index') bg-gray-700 @else hover:bg-gray-700 @endif text-gray-400 hover:text-white">
                        <i class="fa-solid fa-house-laptop"></i></i> WFH
                    </a>
                </div>
            </li>

            <!-- Cuti -->
            <li class="text-gray-500 p-1 font-semibold transition duration-200">
                <div class="w-11/12 mx-auto py-1 {{ Request::is('admin/cuti') ? 'bg-gray-600 rounded-lg' : 'hover:bg-gray-600 rounded-lg' }}">
                    <a href="{{ route('admin.cuti.index') }}"
                        class="flex items-center py-2 px-4 font-semibold 
                              {{ Request::is('admin/cuti') ? 'text-gray-300' : 'text-gray-500 hover:text-gray-300' }} 
                              transition duration-200 w-full">
                        <i class="fa-solid fa-square-plus mr-3"></i> Cuti
                    </a>
                </div>
            </li>

        @elseif(Auth::user()->role->name === 'user')
            <!-- Dashboard -->
            <li class="text-gray-500 p-1 font-semibold transition duration-200">
                <div class="w-11/12 mx-auto py-1 {{ Request::is('user') ? 'bg-gray-600 rounded-lg' : 'hover:bg-gray-600 rounded-lg' }}">
                    <a href="{{ route('user.index') }}"
                        class="flex items-center py-2 px-4 font-semibold 
                              {{ Request::is('user') ? 'text-gray-300' : 'text-gray-500 hover:text-gray-300' }} 
                              transition duration-200 w-full">
                        <i class="fa-solid fa-house mr-3"></i> Beranda
                    </a>
                </div>
            </li>

            <!-- Cuti -->
            <li class="text-gray-500 p-1 font-semibold transition duration-200">
                <div class="w-11/12 mx-auto py-1 {{ Request::is('user/cuti') ? 'bg-gray-600 rounded-lg' : 'hover:bg-gray-600 rounded-lg' }}">
                    <a href="{{ route('user.cuti.index') }}"
                        class="flex items-center py-2 px-4 font-semibold 
                              {{ Request::is('user/cuti') ? 'text-gray-300' : 'text-gray-500 hover:text-gray-300' }} 
                              transition duration-200 w-full">
                        <i class="fa-solid fa-clipboard-list mr-3"></i> Cuti
                    </a>
                </div>
            </li>

            <!-- Catatan Harian -->
            <li class="text-gray-500 p-1 font-semibold transition duration-200">
                <div class="w-11/12 mx-auto py-1 {{ Request::is('user/catatan/catatanuser') ? 'bg-gray-600 rounded-lg' : 'hover:bg-gray-600 rounded-lg'}}">
                    <a href="{{ route('user.catatan.catatanuser') }}"
                        class="flex items-center py-2 px-4 font-semibold 
                              {{ Request::is('user/catatan/catatanuser') ? 'text-gray-300' : 'text-gray-500 hover:text-gray-300' }} 
                              transition duration-200 w-full">
                        <i class="fa-regular fa-pen-to-square mr-3"></i> Catatan
                    </a>
                </div>
            </li>
        @endif

        <!-- Logout -->
        {{-- <li class="absolute bottom-4 w-full px-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-left py-2 px-4 bg-blue-600 hover:bg-blue-700 rounded-md text-sm font-medium transition">
                    <i class="fa-solid fa-arrow-right-from-bracket mr-3"></i> Logout
                </button>
            </form>
        </li> --}}
    </ul>
</aside>
