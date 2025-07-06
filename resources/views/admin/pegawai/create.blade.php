@extends('layouts.main')

@section('title', 'Create User')

@section('content')
    <div class="container mx-auto mt-8 max-w-lg">
        <div class="bg-gray-800 shadow-lg rounded-lg p-6">
            <h1 class="text-3xl font-bold mb-6 text-center text-white">Create New User</h1>

            <!-- Form for creating a user -->
            <form action="{{ route('admin.pegawai.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <!-- Name Input -->
                    <div>
                        <label for="nama_pegawai" class="block text-sm font-medium text-gray-300">Name</label>
                        <input type="text" name="nama_pegawai" id="nama_pegawai"
                            class="mt-2 block w-full px-4 py-2 bg-gray-700 border border-gray-500 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-white placeholder-gray-400"
                            placeholder="Enter name" required>
                    </div>

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                        <input type="email" name="email" id="email"
                            class="mt-2 block w-full px-4 py-2 bg-gray-700 border border-gray-500 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-white placeholder-gray-400"
                            placeholder="Enter email" required>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password"
                                class="mt-2 block w-full px-4 py-2 bg-gray-700 border border-gray-500 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-white placeholder-gray-400"
                                placeholder="Enter password" required>
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm text-gray-400 hover:text-gray-200">
                                <!-- Default icon for hiding password (eye-slash) -->
                                <i id="eye-icon" class="fa-solid fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-300">Role</label>
                        <select name="id_role" id="role"
                            class="mt-2 block w-full px-4 py-2 bg-gray-700 border border-gray-500 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-white">
                            <option value="" class="text-gray-400">Select Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id_role }}" class="bg-gray-700 text-white">{{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Departemen Selection -->
                    <div>
                        <label for="departemen" class="block text-sm font-medium text-gray-300">Departemen</label>
                        <select name="id_departemen" id="departemen"
                            class="mt-2 block w-full px-4 py-2 bg-gray-700 border border-gray-500 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-white">
                            <option value="" class="text-gray-400">Select Departemen</option>
                            @foreach ($departemens as $departemen)
                                <option value="{{ $departemen->id_departemen }}" class="bg-gray-700 text-white">
                                    {{ $departemen->nama_departemen }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- RFID Card Number Input -->
                    <div>
                        <label for="uuid" class="block text-sm font-medium text-gray-300">Nomor Kartu RFID</label>
                        <input type="text" name="uuid" id="uuid"
                            class="mt-2 block w-full px-4 py-2 bg-gray-700 border border-gray-500 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-white placeholder-gray-400"
                            placeholder="Enter RFID Card Number" value="-" required>
                    </div>

                    <!-- Submit and Cancel Buttons -->
                    <div class="flex justify-between mt-6">
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Create
                            User</button>
                        <a href="{{ route('admin.pegawai.index') }}"
                            class="bg-red-600 text-white px-6 py-2 rounded-md shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>



@endsection
