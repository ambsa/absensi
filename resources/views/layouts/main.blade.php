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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Flex:opsz,wght@8..144,100..1000&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    
</head>

<body class="bg-[#161A23] flex">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 ml-64 p-5">
        <!-- Header (Dashboard dan Profile) -->
        <div class="container p-1">
            <div class="flex justify-between bg-transparent items-center mb-8">
                <!-- Judul Dashboard -->
                <h1 class="text-xs lg:text-2xl font-bold text-gray-300">
                    {{ $pageTitle }}
                </h1>

                <!-- Profil di pojok kanan -->
                <div class="flex items-center space-x-4 relative">
                    <!-- Gambar Profil -->
                    <img src="https://img.freepik.com/free-vector/user-circles-set_78370-4691.jpg " alt="Profile"
                        class="w-10 h-10 rounded-full cursor-pointer" id="profileImage">

                    <!-- Dropdown Menu -->
                    <div id="profileDropdownMenu"
                        class="absolute right-0 top-12 mt-2 w-48 bg-[#161A23] shadow-lg rounded-lg border border-gray-700 z-10 
                           opacity-0 invisible transform scale-95 transition-all duration-300 ease-in-out">
                        <ul>
                            <!-- Profile Item -->
                            <li
                                class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 transition duration-300">
                                <i class="fa-regular fa-user mr-3"></i> Profile
                            </li>

                            <!-- Garis Pemisah -->
                            <li class="border-t border-gray-700"></li>

                            <!-- Settings Item -->
                            <li
                                class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 transition duration-300">
                                <i class="fas fa-cogs mr-3"></i> Settings
                            </li>

                            <!-- Garis Pemisah -->
                            <li class="border-t border-gray-700"></li>

                            <!-- Logout Form -->
                            <li
                                class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 transition duration-300">
                                <form id="logoutForm" method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="button" id="logoutButton" class="w-full text-left">
                                        <i class="fa-solid fa-arrow-right-from-bracket mr-3"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Konten Utama -->
        <div class="mt-4">
            @yield('content')
        </div>
    </div>
</body>

</html>
