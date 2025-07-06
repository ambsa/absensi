@extends('layouts.main')

@section('title', 'Edit User')

@section('content')


    <div class="max-w-4xl mx-auto mt-10 bg-gray-800 p-6 rounded-lg shadow-lg">

        <form action="{{ route('admin.pegawai.update', $pegawai->id_pegawai) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-4">
                <label for="nama_pegawai" class="block text-sm font-medium text-gray-300">Name</label>
                <input type="text" name="nama_pegawai" id="nama_pegawai"
                    value="{{ old('nama_pegawai', $pegawai->nama_pegawai) }}" required
                    class="mt-2 block w-full px-4 py-2 bg-gray-700 border border-gray-500 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-white placeholder-gray-400">
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $pegawai->email) }}" required
                    class="mt-2 block w-full px-4 py-2 bg-gray-700 border border-gray-500 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-white placeholder-gray-400">
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-300">Role</label>
                <select name="id_role" id="role" required
                    class="mt-2 block w-full px-4 py-2 bg-gray-700 border border-gray-500 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-white">
                    @foreach ($roles as $role)
                        <option value="{{ $role->id_role }}" {{ $role->id_role == $pegawai->id_role ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Departemen -->
            <div class="mb-4">
                <label for="departemen" class="block text-sm font-medium text-gray-300">Departemen</label>
                <select name="id_departemen" id="departemen" required
                    class="mt-2 block w-full px-4 py-2 bg-gray-700 border border-gray-500 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-white">
                    <option value="" disabled {{ !$pegawai->id_departemen ? 'selected' : '' }}>Pilih Departemen
                    </option>
                    @foreach ($departemens as $departemen)
                        <option value="{{ $departemen->id_departemen }}"
                            {{ $departemen->id_depatemen == $pegawai->id_departemen ? 'selected' : '' }}>
                            {{ $departemen->nama_departemen }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- RFID Card Number -->
            <div class="mb-4">
                <label for="rfid_card_number" class="block text-sm font-medium text-gray-300">RFID Card Number</label>
                <input type="text" name="uuid" id="uuid" value="{{ old('uuid', $pegawai->uuid) }}" required
                    class="mt-2 block w-full px-4 py-2 bg-gray-700 border border-gray-500 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-white placeholder-gray-400">
            </div>

            <!-- Submit and Cancel Buttons -->
            <div class="flex justify-between mt-6">
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Update User
                </button>
                <a href="{{ route('admin.pegawai.index') }}"
                    class="bg-red-600 text-white px-6 py-2 rounded-md shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Cancel
                </a>
            </div>
        </form>
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
