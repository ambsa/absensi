<!-- Profil di pojok kanan -->
<div class="flex items-center space-x-2 sm:space-x-4 relative">
    <!-- Gambar Profil -->
    <img src="https://img.freepik.com/free-vector/user-circles-set_78370-4691.jpg " alt="Profile"
        class="w-8 h-8 sm:w-10 sm:h-10 rounded-full cursor-pointer transition-transform duration-200 hover:scale-105"
        id="profileImage">

    <!-- Dropdown Menu -->
    <div id="profileDropdownMenu"
        class="absolute right-0 top-12 mt-2 w-32 sm:w-48 bg-[#161A23] shadow-lg rounded-lg border border-gray-700 z-10 
               opacity-0 invisible transform scale-95 transition-all duration-300 ease-in-out">
        <ul class="text-xs sm:text-sm">
            <!-- Profile Item -->
            <li class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 cursor-pointer">
                <i class="fa-regular fa-user mr-2 sm:mr-3"></i> Profile
            </li>

            <!-- Garis Pemisah -->
            <li class="border-t border-gray-700"></li>

            <!-- Settings Item -->
            <li class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 cursor-pointer">
                <i class="fas fa-cogs mr-2 sm:mr-3"></i> Settings
            </li>

            <!-- Garis Pemisah -->
            <li class="border-t border-gray-700"></li>

            <!-- Logout Form -->
            <li class="px-3 py-2 text-gray-300 hover:bg-gray-700 cursor-pointer">
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-2 sm:mr-3"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
