@extends('layouts.main')

@section('title', 'Create User')

@section('content')
    <div class="container mx-auto mt-8 max-w-lg bg-[var(--bg-color)]">
        <div class="bg-[var(--card-bg)] shadow-lg rounded-lg p-6">
            <h1 class="text-3xl font-bold mb-6 text-center text-[var(--text-color)]">Create New User</h1>

            <!-- Form for creating a user -->
            <form action="{{ route('admin.pegawai.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <!-- Name Input -->
                    <div>
                        <label for="nama_pegawai" class="block text-sm font-medium text-[var(--text-color)]">Name</label>
                        <input type="text" name="nama_pegawai" id="nama_pegawai"
                            class="mt-2 block w-full px-4 py-2 bg-[var(--input-bg)] border border-[var(--input-border)] rounded-md shadow-sm focus:ring-[var(--button-primary)] focus:border-[var(--button-primary)] sm:text-sm text-[var(--input-text)] placeholder-[var(--placeholder-color)]"
                            placeholder="Enter name" required>
                    </div>

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-[var(--text-color)]">Email</label>
                        <input type="email" name="email" id="email"
                            class="mt-2 block w-full px-4 py-2 bg-[var(--input-bg)] border border-[var(--input-border)] rounded-md shadow-sm focus:ring-[var(--button-primary)] focus:border-[var(--button-primary)] sm:text-sm text-[var(--input-text)] placeholder-[var(--placeholder-color)]"
                            placeholder="Enter email" required>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-[var(--text-color)]">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password"
                                class="mt-2 block w-full px-4 py-2 bg-[var(--input-bg)] border border-[var(--input-border)] rounded-md shadow-sm focus:ring-[var(--button-primary)] focus:border-[var(--button-primary)] sm:text-sm text-[var(--input-text)] placeholder-[var(--placeholder-color)]"
                                placeholder="Enter password" required>
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm text-[var(--placeholder-color)] hover:text-[var(--text-color)]">
                                <!-- Default icon for hiding password (eye-slash) -->
                                <i id="eye-icon" class="fa-solid fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-[var(--text-color)]">Role</label>
                        <select name="id_role" id="role"
                            class="mt-2 block w-full px-4 py-2 bg-[var(--input-bg)] border border-[var(--input-border)] rounded-md shadow-sm focus:ring-[var(--button-primary)] focus:border-[var(--button-primary)] sm:text-sm text-[var(--input-text)]">
                            <option value="" class="text-[var(--placeholder-color)]">Select Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id_role }}" class="bg-[var(--input-bg)] text-[var(--text-color)]">{{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Departemen Selection -->
                    <div>
                        <label for="departemen" class="block text-sm font-medium text-[var(--text-color)]">Departemen</label>
                        <select name="id_departemen" id="departemen"
                            class="mt-2 block w-full px-4 py-2 bg-[var(--input-bg)] border border-[var(--input-border)] rounded-md shadow-sm focus:ring-[var(--button-primary)] focus:border-[var(--button-primary)] sm:text-sm text-[var(--input-text)]">
                            <option value="" class="text-[var(--placeholder-color)]">Select Departemen</option>
                            @foreach ($departemens as $departemen)
                                <option value="{{ $departemen->id_departemen }}" class="bg-[var(--input-bg)] text-[var(--text-color)]">
                                    {{ $departemen->nama_departemen }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- RFID Card Number Input -->
                    <div>
                        <label for="uuid" class="block text-sm font-medium text-[var(--text-color)]">Nomor Kartu RFID</label>
                        <input type="text" name="uuid" id="uuid"
                            class="mt-2 block w-full px-4 py-2 bg-[var(--input-bg)] border border-[var(--input-border)] rounded-md shadow-sm focus:ring-[var(--button-primary)] focus:border-[var(--button-primary)] sm:text-sm text-[var(--input-text)] placeholder-[var(--placeholder-color)]"
                            placeholder="Enter RFID Card Number" value="{{ $uuid }}" required>
                        @if($uuid !== '-')
                            <p class="mt-1 text-sm text-green-400">
                                <i class="fa-solid fa-info-circle mr-1"></i>
                                UID ini berasal dari daftar UID tidak terdaftar
                            </p>
                        @endif
                    </div>

                    <!-- Submit and Cancel Buttons -->
                    <div class="flex justify-between mt-6">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-6 py-2 rounded-md shadow-md hover:bg-blue-500/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Create
                            User</button>
                        <a href="{{ route('admin.pegawai.index') }}"
                            class="bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 text-white px-6 py-2 rounded-md shadow-md hover:bg-red-500/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection