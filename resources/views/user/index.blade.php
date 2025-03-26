@extends('layouts.main')

@section('title', 'User Dashboard')

@section('content')
<div class="container p-1">
    <!-- Menampilkan Dashboard dan Profile -->
    <div class="flex justify-between bg-white shadow-lg rounded-lg p-2 items-center mb-8">
        <!-- Judul Dashboard -->
        <h1 class="text-md font-semibold text-gray-800">
            SISTEM INFORMASI MONITORING
        </h1>

        <!-- Profil di pojok kanan -->
        <div class="flex items-center space-x-4 relative">
            <!-- Profil Wrapper untuk background menyatu -->
            <div class="flex items-center space-x-3 relative">
                <!-- Gambar Profil -->
                <img src="https://img.freepik.com/free-vector/businessman-character-avatar-isolated_24877-60111.jpg"
                    alt="Profile Picture" class="w-10 h-10 rounded-full cursor-pointer" id="profileImage">
            </div>

            <!-- Dropdown Menu -->
            <div id="dropdownMenu"
                class="hidden absolute right-0 mt-40 w-48 bg-white shadow-lg rounded-lg border border-gray-200 z-10">
                <ul>
                    <!-- Profile Item -->
                    <li class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-200">
                        <i class="fa-regular fa-user mr-3"></i> Profile
                    </li>

                    <!-- Garis Pemisah -->
                    <li class="border-t border-gray-200"></li>

                    <!-- Settings Item -->
                    <li class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-200">
                        <i class="fas fa-cogs mr-3"></i> Settings
                    </li>

                    <!-- Garis Pemisah -->
                    <li class="border-t border-gray-200"></li>

                    <!-- Logout Form -->
                    <li class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-200">
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


<div class="container mx-auto p-6 bg-white shadow-lg rounded-lg">
    <!-- Menentukan waktu saat ini -->
    @php
        $hour = now()->format('H'); // Mengambil jam saat ini dengan Laravel (dengan zona waktu yang disesuaikan)
        $greeting = '';

        // Menentukan ucapan berdasarkan jam
        if ($hour >= 0 && $hour < 12) {
            $greeting = 'Selamat Pagi';
        } elseif ($hour >= 12 && $hour < 15) {
            $greeting = 'Selamat Siang';
        } elseif ($hour >= 15 && $hour < 18) {
            $greeting = 'Selamat Sore';
        } else {
            $greeting = 'Selamat Malam';
        }
    @endphp

    <!-- Menampilkan ucapan dan nama pengguna -->
    <h1 class="text-4xl font-semibold text-gray-800 mb-4">{{ $greeting }}, {{ Auth::user()->name }}!</h1>

    <p class="mt-4 text-gray-600">This is the content of the user dashboard.</p>
</div>
@endsection
