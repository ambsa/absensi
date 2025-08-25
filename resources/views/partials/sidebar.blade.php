<!-- Sidebar -->
<aside id="sidebar"
    class="w-64 h-screen bg-slate-900 dark:bg-gray-800 bg-opacity-90 flex-col fixed top-0 left-0 z-40 transition-all duration-300 transform -translate-x-full lg:translate-x-0">

    <div class="p-5 flex justify-between items-center">
        <a href="{{ Auth::user()->role->name === 'admin' ? route('admin.index') : route('user.index') }}"
            class="flex items-center justify-center">
            <img src="{{ asset('assets/images/fyplogo.png') }}" alt="FYP Logo" class="h-5 mx-auto mb-4">
        </a>
        <button id="closeSidebar" class="lg:hidden text-md text-white dark:text-gray-200">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <div class="px-4 py-2 ml-3 text-xs text-gray-500 dark:text-gray-400 font-bold">
        MAIN
    </div>

    <ul class="px-4 space-y-1 overflow-y-auto flex-1 pb-10">
        @if (Auth::user()->role->name === 'admin')
            <!-- Home -->
            <li class="text-white p-1 font-semibold transition duration-200">
                <div
                    class="w-full mx-auto py-1 {{ Request::is('admin') ? 'bg-gray-600 dark:bg-gray-700 rounded-lg' : 'hover:bg-gray-600 dark:hover:bg-gray-700 rounded-lg' }}">
                    <a href="{{ route('admin.index') }}"
                        class="flex items-center py-2 px-4 font-semibold 
                          {{ Request::is('admin') ? 'text-white' : 'text-gray-300 hover:text-white' }} 
                          transition duration-200 w-full">
                        <i class="fa-solid fa-house mr-3"></i> Beranda
                    </a>
                </div>
            </li>

            <!-- Pegawai -->
            <li class="text-white p-1 font-semibold transition duration-200">
                <div
                    class="w-full mx-auto py-1 {{ Request::is('admin/pegawai') ? 'bg-gray-600 dark:bg-gray-700 rounded-lg' : 'hover:bg-gray-600 dark:hover:bg-gray-700 rounded-lg' }}">
                    <a href="{{ route('admin.pegawai.index') }}"
                        class="flex items-center py-2 px-4 font-semibold 
                          {{ Request::is('admin/pegawai') ? 'text-white' : 'text-gray-300 hover:text-white' }} 
                          transition duration-200 w-full">
                        <i class="fas fa-users mr-3"></i> Daftar Pegawai
                    </a>
                </div>
            </li>

            <!-- Cuti -->
            <li class="text-white p-1 font-semibold transition duration-200">
                <div
                    class="w-full mx-auto py-1 {{ Request::is('admin/cuti') ? 'bg-gray-600 dark:bg-gray-700 rounded-lg' : 'hover:bg-gray-600 dark:hover:bg-gray-700 rounded-lg' }}">
                    <a href="{{ route('admin.cuti.index') }}"
                        class="flex items-center py-2 px-4 font-semibold 
                          {{ Request::is('admin/cuti') ? 'text-white' : 'text-gray-300 hover:text-white' }} 
                          transition duration-200 w-full">
                        <i class="fa-solid fa-square-plus mr-3"></i> Cuti
                    </a>
                </div>
            </li>

            <!-- riwayat absen -->
            <li class="text-white p-1 font-semibold transition duration-200">
                <div
                    class="w-full mx-auto py-1 {{ Request::is('admin/data_absen') ? 'bg-gray-600 dark:bg-gray-700 rounded-lg' : 'hover:bg-gray-600 dark:hover:bg-gray-700 rounded-lg' }}">
                    <a href="{{ route('admin.data_absen.index') }}"
                        class="flex items-center py-2 px-4 font-semibold 
                          {{ Request::is('admin/data_absen') ? 'text-white' : 'text-gray-300 hover:text-white' }} 
                          transition duration-200 w-full">
                        <i class="fa-solid fa-list mr-3"></i> Riwayat Absensi
                    </a>
                </div>
            </li>

            <!-- Isi Catatan -->
            <li class="text-white p-1 font-semibold transition duration-200">
                <div
                    class="{{ Request::is('admin/data_absen/catatan*') ? 'bg-gray-600 dark:bg-gray-700 rounded-lg' : 'hover:bg-gray-600 dark:hover:bg-gray-700 rounded-lg' }} w-11/12 mx-auto py-1">
                    <a href="{{ route('admin.data_absen.catatan') }}"
                        class="flex items-center py-2 px-4 font-semibold 
                  {{ Request::is('admin/data_absen/catatan*') ? 'text-white' : 'text-gray-300 hover:text-white' }} 
                  transition duration-200 w-full">
                        <i class="fa-solid fa-pen-to-square mr-3"></i> Catatan Harian
                    </a>
                </div>
            </li>

            <li class="text-white p-1 font-semibold transition duration-200">
                <div
                    class="w-full mx-auto py-1 {{ Request::is('admin/wfh') ? 'bg-gray-600 dark:bg-gray-700 rounded-lg' : 'hover:bg-gray-600 dark:hover:bg-gray-700 rounded-lg' }}">
                    <a href="{{ route('admin.wfh.index') }}"
                        class="flex items-center py-2 px-4 font-semibold 
                          {{ Request::is('admin/wfh') ? 'text-white' : 'text-gray-300 hover:text-white' }} 
                          transition duration-200 w-full">
                        <i class="fa-solid fa-house-laptop mr-3"></i> WFH
                    </a>
                </div>
            </li>

            <!-- Unknown UID -->
            <li class="text-white p-1 font-semibold transition duration-200">
                <div
                    class="w-full mx-auto py-1 {{ Request::is('admin/unknown-uids*') ? 'bg-gray-600 dark:bg-gray-700 rounded-lg' : 'hover:bg-gray-600 dark:hover:bg-gray-700 rounded-lg' }}">
                    <a href="{{ route('admin.unknown_uids.index') }}"
                        class="flex items-center py-2 px-4 font-semibold 
                          {{ Request::is('admin/unknown-uids*') ? 'text-white' : 'text-gray-300 hover:text-white' }} 
                          transition duration-200 w-full">
                        <i class="fa-solid fa-address-card mr-3"></i> Kartu RFID
                    </a>
                </div>
            </li>
        @elseif(Auth::user()->role->name === 'user')
            <!-- Dashboard -->
            <li class="text-white p-1 font-semibold transition duration-200">
                <div
                    class="w-full mx-auto py-1 {{ Request::is('user') ? 'bg-gray-600 dark:bg-gray-700 rounded-lg' : 'hover:bg-gray-600 dark:hover:bg-gray-700 rounded-lg' }}">
                    <a href="{{ route('user.index') }}"
                        class="flex items-center py-2 px-4 font-semibold 
                          {{ Request::is('user') ? 'text-white' : 'text-gray-300 hover:text-white' }} 
                          transition duration-200 w-full">
                        <i class="fa-solid fa-house mr-3"></i> Beranda
                    </a>
                </div>
            </li>

            <!-- Data Absensi -->
            <li class="text-white p-1 font-semibold transition duration-200 relative">
                <!-- Tombol Induk -->
                <div id="toggleAbsenMenu"
                    class="w-full  mx-auto py-1 rounded-lg cursor-pointer transition-all duration-200 hover:bg-gray-600 dark:hover:bg-gray-700">
                    <div class="flex items-center justify-between px-4 py-2 font-semibold text-white">
                        <span class="flex items-center">
                            <i class="fa-solid fa-box-archive mr-3"></i> Absensi
                        </span>
                        <i id="absenArrow" class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                    </div>
                </div>

                <!-- Dropdown Content -->
                <div id="absenDropdownContent"
                    class="mt-1 space-y-2 px-2 overflow-hidden transition-all duration-300 ease-in-out max-h-0">

                    <!-- Data Absen -->
                    <a href="{{ route('user.data_absen.index') }}"
                        class="block py-2 px-6 text-sm rounded-md transition duration-200 
                    @if (Route::currentRouteName() == 'user.data_absen.index') bg-gray-700 dark:bg-gray-600 @else hover:bg-gray-700 dark:hover:bg-gray-600 @endif text-gray-300 hover:text-white">
                        <i class="fa-solid fa-box-archive"></i> Data Absen
                    </a>

                    <!-- Isi Catatan -->
                    <a href="{{ route('user.catatan.catatanuser') }}"
                        class="block py-2 px-6 text-sm rounded-md transition duration-200 
                    @if (Route::currentRouteName() == 'user.catatan.catatanuser') bg-gray-700 dark:bg-gray-600 @else hover:bg-gray-700 dark:hover:bg-gray-600 @endif text-gray-300 hover:text-white">
                        <i class="fa-solid fa-pen-to-square mr-2"></i> Isi Catatan
                    </a>

                    <!-- WFH -->
                    <a href="{{ route('user.wfh.index') }}"
                        class="block py-2 px-6 text-sm rounded-md transition duration-200 
                    @if (Route::currentRouteName() == 'user.wfh.index') bg-gray-700 dark:bg-gray-600 @else hover:bg-gray-700 dark:hover:bg-gray-600 @endif text-gray-300 hover:text-white">
                        <i class="fa-solid fa-house-laptop"></i> WFH
                    </a>
                </div>
            </li>

            <!-- Cuti -->
            <li class="text-white p-1 font-semibold transition duration-200">
                <div
                    class="w-full  mx-auto py-1 {{ Request::is('user/cuti') ? 'bg-gray-600 dark:bg-gray-700 rounded-lg' : 'hover:bg-gray-600 dark:hover:bg-gray-700 rounded-lg' }}">
                    <a href="{{ route('user.cuti.index') }}"
                        class="flex items-center py-2 px-4 font-semibold 
                          {{ Request::is('user/cuti') ? 'text-white' : 'text-gray-300 hover:text-white' }} 
                          transition duration-200 w-full">
                        <i class="fa-solid fa-clipboard-list mr-3"></i> Cuti
                    </a>
                </div>
            </li>
        @elseif(Auth::user()->role->name === 'mini_admin')
            <li class="text-white p-1 font-semibold transition duration-200">
                <div
                    class="w-full mx-auto py-1 {{ Request::is('/mini-admin') ? 'bg-gray-600 dark:bg-gray-700 rounded-lg' : 'hover:bg-gray-600 dark:hover:bg-gray-700 rounded-lg' }}">
                    <a href="{{ route('miniadmin.index') }}"
                        class="flex items-center py-2 px-4 font-semibold 
                            {{ Request::is('/mini-admin') ? 'text-white' : 'text-gray-300 hover:text-white' }} 
                            transition duration-200 w-full">
                        <i class="fa-solid fa-house mr-3"></i> Beranda
                    </a>
                </div>
            </li>

            <!-- Cuti -->
            <li class="text-white p-1 font-semibold transition duration-200">
                <div
                    class="w-full  mx-auto py-1 {{ Request::is('mini-admin/cuti') ? 'bg-gray-600 dark:bg-gray-700 rounded-lg' : 'hover:bg-gray-600 dark:hover:bg-gray-700 rounded-lg' }}">
                    <a href="{{ route('miniadmin.cuti.index') }}"
                        class="flex items-center py-2 px-4 font-semibold 
                          {{ Request::is('mini-admin/cuti') ? 'text-white' : 'text-gray-300 hover:text-white' }} 
                          transition duration-200 w-full">
                        <i class="fa-solid fa-square-plus mr-3"></i> Cuti
                    </a>
                </div>
            </li>
            
            <li class="text-white p-1 font-semibold transition duration-200">
                <div
                    class="w-full  mx-auto py-1 {{ Request::is('mini-admin/wfh') ? 'bg-gray-600 dark:bg-gray-700 rounded-lg' : 'hover:bg-gray-600 dark:hover:bg-gray-700 rounded-lg' }}">
                    <a href="{{ route('miniadmin.wfh.index') }}"
                        class="flex items-center py-2 px-4 font-semibold 
                          {{ Request::is('mini-admin/wfh') ? 'text-white' : 'text-gray-300 hover:text-white' }} 
                          transition duration-200 w-full">
                        <i class="fa-solid fa-house-laptop mr-3"></i> WFH
                    </a>
                </div>
            </li>

            <!-- riwayat absen -->
            <li class="text-white p-1 font-semibold transition duration-200">
                <div
                    class="w-full mx-auto py-1 {{ Request::is('mini_admin/riwayatabsen') ? 'bg-gray-600 dark:bg-gray-700 rounded-lg' : 'hover:bg-gray-600 dark:hover:bg-gray-700 rounded-lg' }}">
                    <a href="{{ route('mini_admin.riwayatabsen.index') }}"
                        class="flex items-center py-2 px-4 font-semibold 
                          {{ Request::is('mini_admin/riwayatabsen') ? 'text-white' : 'text-gray-300 hover:text-white' }} 
                          transition duration-200 w-full">
                        <i class="fa-solid fa-list mr-3"></i> Riwayat Absensi
                    </a>
                </div>
            </li>
        @endif
    </ul>
</aside>
