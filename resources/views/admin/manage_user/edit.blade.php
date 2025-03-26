@extends('layouts.main')

@section('title', 'Edit User')

@section('content')

    <div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit User</h2>

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                    class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                    class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role_id" id="role" required
                    class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ $role->id == $user->role_id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Departemen -->
            <div class="mb-4">
                <label for="departemen" class="block text-sm font-medium text-gray-700">Departemen</label>
                <select name="departemen_id" id="departemen" required
                    class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach ($departemens as $departemen)
                        <option value="{{ $departemen->id }}"
                            {{ $departemen->id == $user->departemen_id ? 'selected' : '' }}>
                            {{ $departemen->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- RFID Card Number -->
            <div class="mb-4">
                <label for="rfid_card_number" class="block text-sm font-medium text-gray-700">RFID Card Number</label>
                <input type="text" name="rfid_card_number" id="rfid_card_number"
                    value="{{ old('rfid_card_number', $user->rfidCard->card_number ?? '') }}" required
                    class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <!-- Submit and Cancel Buttons -->
            <div class="flex justify-between mt-6">
                <button type="submit"
                    class="bg-blue-500 text-white px-6 py-2 rounded-md shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Update User
                </button>
                <a href="{{ route('admin.manage_user.index') }}"
                    class="bg-gray-500 text-white px-6 py-2 rounded-md shadow-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Cancel
                </a>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitButton = document.querySelector('button[type="submit"]');
            const rfidInput = document.getElementById('rfid_card_number');

            // Cegah form disubmit secara otomatis jika Enter ditekan
            rfidInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // Mencegah aksi Enter yang akan mengirimkan form
                    console.log("Enter key detected but form submission is prevented.");
                }
            });

            // Cegah form dari pengiriman otomatis
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Mencegah form disubmit secara otomatis
                console.log("Form submission prevented.");
            });

            submitButton.addEventListener('click', function(event) {
                event.preventDefault(); // Mencegah aksi default tombol submit

                // Validasi input RFID
                const rfidInputValue = rfidInput.value;
                if (!rfidInputValue) {
                    alert("Please enter a valid RFID Card Number.");
                    return;
                }

                // Jika valid, submit form secara manual
                console.log("Form is valid, submitting form...");
                form.submit(); // Mengirimkan form secara manual
            });
        });
    </script>
@endsection
