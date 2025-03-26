@extends('layouts.main')

@section('title', 'Create User')

@section('content')
    <div class="container mx-auto mt-8 max-w-lg">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Create New User</h1>

            <!-- Form for creating a user -->
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <!-- Name Input -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name"
                            class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Enter name" required>
                    </div>

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email"
                            class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Enter email" required>
                    </div>

                    <!-- Password Input (Admin will set the password) -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password"
                                class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Enter password" required>
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm text-gray-500">
                                <!-- Default icon for hiding password (eye-slash) -->
                                <i id="eye-icon" class="fa-solid fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role_id" id="role"
                            class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                            <option value="">Select Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Departemen Selection -->
                    <div>
                        <label for="departemen" class="block text-sm font-medium text-gray-700">Departemen</label>
                        <select name="departemen_id" id="departemen"
                            class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            required>
                            <option value="">Select Departemen</option>
                            @foreach ($departemens as $departemen)
                                <option value="{{ $departemen->id }}">{{ $departemen->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- RFID Card Number Input -->
                    <div>
                        <label for="rfid_card_number" class="block text-sm font-medium text-gray-700">Nomor Kartu
                            RFID</label>
                        <input type="text" name="rfid_card_number" id="rfid_card_number"
                            class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Enter RFID Card Number" required>
                    </div>

                    <!-- Submit and Cancel Buttons -->
                    <div class="flex justify-between mt-6">
                        <button type="submit"
                            class="bg-blue-500 text-white px-6 py-2 rounded-md shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Create
                            User</button>
                        <a href="{{ route('users.index') }}"
                            class="bg-red-500 text-white px-6 py-2 rounded-md shadow-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const rfidInput = document.getElementById('rfid_card_number');
            const submitButton = document.querySelector('button[type="submit"]');

            // Cegah form disubmit saat RFID input
            rfidInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    // Mencegah pengiriman form jika Enter ditekan di input RFID
                    e.preventDefault();
                }
            });

            // Cegah form disubmit otomatis oleh pembaca RFID
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Mencegah form disubmit otomatis
            });

            // Handle submit ketika tombol "Create User" diklik
            submitButton.addEventListener('click', function(event) {
                event.preventDefault(); // Mencegah form submit otomatis

                // Validasi input RFID (Anda bisa menambahkan validasi lain sesuai kebutuhan)
                if (!rfidInput.value.trim()) {
                    alert("Please enter a valid RFID Card Number.");
                    return;
                }

                // Jika valid, submit form secara manual
                form.submit();
            });
        });
    </script>


@endsection
