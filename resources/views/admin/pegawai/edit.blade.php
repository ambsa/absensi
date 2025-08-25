@extends('layouts.main')

@section('title', 'Edit User')

@section('content')
    <div class="container mx-auto mt-8 max-w-lg bg-white dark:bg-gray-800">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
            <h1 class="text-3xl font-bold mb-6 text-center text-gray-900 dark:text-white">Edit User</h1>

            <form action="{{ route('admin.pegawai.update', $pegawai->id_pegawai) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="nama_pegawai" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input type="text" name="nama_pegawai" id="nama_pegawai"
                            value="{{ old('nama_pegawai', $pegawai->nama_pegawai) }}" required
                            class="mt-2 block w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 sm:text-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $pegawai->email) }}" required
                            class="mt-2 block w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 sm:text-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                        <select name="id_role" id="role" required
                            class="mt-2 block w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 sm:text-sm text-gray-900 dark:text-white">
                            <option value="" class="text-gray-500 dark:text-gray-400">Select Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id_role }}" 
                                    {{ $role->id_role == $pegawai->id_role ? 'selected' : '' }}
                                    class="bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Departemen -->
                    <div>
                        <label for="departemen" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Departemen</label>
                        <select name="id_departemen" id="departemen" required
                            class="mt-2 block w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 sm:text-sm text-gray-900 dark:text-white">
                            <option value="" class="text-gray-500 dark:text-gray-400">Select Departemen</option>
                            @foreach ($departemens as $departemen)
                                <option value="{{ $departemen->id_departemen }}"
                                    {{ $departemen->id_departemen == $pegawai->id_departemen ? 'selected' : '' }}
                                    class="bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    {{ $departemen->nama_departemen }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- RFID Card Number -->
                    <div>
                        <label for="uuid" class="block text-sm font-medium text-gray-700 dark:text-gray-300">RFID Card Number</label>
                        <input type="text" name="uuid" id="uuid" value="{{ old('uuid', $pegawai->uuid) }}" required
                            class="mt-2 block w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 sm:text-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">
                    </div>

                    <!-- Submit and Cancel Buttons -->
                    <div class="flex justify-between mt-6">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-6 py-2 rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                            Update User
                        </button>
                        <a href="{{ route('admin.pegawai.index') }}"
                            class="bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 text-white px-6 py-2 rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-red-400">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const role = document.getElementById('role').value;
            const departemen = document.getElementById('departemen').value;

            if (!role || !departemen) {
                e.preventDefault();
                alert('Silakan pilih Role dan Departemen!');
            }
        });
    </script>
@endsection
