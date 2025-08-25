<div class="relative ml-auto flex items-center space-x-4">

    <!-- Tombol Toggle Mode Terang/Gelap -->
    <button id="theme-toggle"
        class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition duration-200 text-gray-800 dark:text-gray-200">
        <i id="theme-icon" class="fa-solid fa-moon text-gray-800 transition-opacity duration-300"></i>
        <i id="theme-icon-sun" class="fa-solid fa-sun text-yellow-400 transition-opacity duration-300 absolute"></i>
    </button>

    <!-- Profil Pengguna -->
    <div
        class="flex items-center space-x-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md transition duration-200 px-2 py-1">
        <!-- Avatar Profil -->
        <img src="{{ Auth::user()->pegawai->foto_profil ?? 'https://img.freepik.com/free-vector/user-circles-set_78370-4691.jpg' }}"
            alt="Profile"
            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full cursor-pointer object-cover border border-gray-600 dark:border-gray-400"
            id="profileImageToggle">

        <!-- Nama User -->
        <span class="sm:inline-block text-sm font-medium text-gray-900 dark:text-gray-200">
            {{ Auth::user()->nama_pegawai }}
        </span>
    </div>

    <!-- Dropdown Menu -->
    <div id="profileDropdownMenu"
        class="absolute right-0 top-12 mt-2 w-32 sm:w-48 bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 z-10 
           opacity-0 invisible transform scale-95 transition-all duration-300 ease-in-out origin-top-right">
        <ul class="py-2 text-xs sm:text-sm">
            <!-- Profile -->
            <li>
                <a href="#"
                    class="flex items-center px-3 py-2 text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white w-full transition duration-150">
                    <i class="fa-regular fa-user mr-2 sm:mr-3"></i> Profile
                </a>
            </li>
            <li class="border-t border-gray-200 dark:border-gray-700 my-1"></li>
            <!-- Settings -->
            <li>
                <a href="#"
                    class="flex items-center px-3 py-2 text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white w-full transition duration-150">
                    <i class="fas fa-cogs mr-2 sm:mr-3"></i> Settings
                </a>
            </li>
            <li class="border-t border-gray-200 dark:border-gray-700 my-1"></li>
            <!-- Logout -->
            <li>
                <button type="button" id="logoutButton" onclick="confirmLogout()"
                    class="flex items-center w-full px-3 py-2 text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition duration-150 cursor-pointer">
                    <i class="fa-solid fa-arrow-right-from-bracket mr-2 sm:mr-3"></i> Logout
                </button>
            </li>

            <!-- Form Logout -->
            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                @csrf
            </form>
        </ul>
    </div>
</div>
