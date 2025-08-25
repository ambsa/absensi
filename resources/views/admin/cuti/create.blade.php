@extends('layouts.main')

@section('title', 'Cuti')

@section('content')
    <div class="container mx-auto p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg rounded-lg text-gray-900 dark:text-gray-100 md:max-w-xl">

        <form action="{{ route('admin.cuti.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Jenis Cuti -->
            <div>
                <label for="id_jenis_cuti" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Cuti</label>
                <select id="id_jenis_cuti" name="id_jenis_cuti"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 sm:text-sm">
                    <option value="" class="text-gray-500 dark:text-gray-400">Pilih Jenis Cuti</option>
                    @foreach ($jenis_cuti as $item)
                        <option value="{{ $item->id_jenis_cuti }}" class="text-gray-900 dark:text-gray-100">{{ $item->nama }}</option>
                    @endforeach
                </select>
                @error('id_jenis_cuti')
                    <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Mulai -->
            <div>
                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 sm:text-sm">
                @error('tanggal_mulai')
                    <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Selesai -->
            <div>
                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 sm:text-sm">
                @error('tanggal_selesai')
                    <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Alasan Cuti -->
            <div>
                <label for="alasan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alasan Cuti</label>
                <textarea id="alasan" name="alasan" rows="3"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 sm:text-sm">{{ old('alasan') }}</textarea>
                @error('alasan')
                    <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-between">
                <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                    Ajukan Cuti
                </button>
                <a href="{{ route('admin.cuti.index') }}"
                    class="inline-flex justify-center py-2 px-4 shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-red-400">
                    Cancel
                </a>
            </div>
        </form>
    </div>

@endsection