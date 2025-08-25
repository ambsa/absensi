<!-- catatan.blade.php -->
@extends('layouts.main')

@section('title', 'Catatan Harian')

@section('content')

<div class="flex items-center justify-center dark:bg-gray-900 px-4">
    <div class="w-full max-w-2xl bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md text-gray-900 dark:text-gray-100">
        <h1 class="text-xl font-bold mb-6 text-center">
            @if(request('wfh_id'))
                Catatan Harian WFH
            @else
                Catatan Harian
            @endif
        </h1>

        @if(request('wfh_id'))
            <div class="mb-4 p-3 bg-blue-100 dark:bg-blue-900 bg-opacity-50 border border-blue-300 dark:border-blue-600 rounded-md">
                <p class="text-blue-800 dark:text-blue-200 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    Anda sedang mengisi catatan untuk absen WFH. Catatan ini wajib diisi sebelum melakukan absen pulang.
                </p>
            </div>
        @else
            <div class="mb-4 p-3 bg-yellow-100 dark:bg-yellow-900 bg-opacity-50 border border-yellow-300 dark:border-yellow-600 rounded-md">
                <p class="text-yellow-800 dark:text-yellow-200 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    Anda sedang mengisi catatan untuk absen tap kartu. Catatan ini wajib diisi sebelum melakukan absen pulang.
                </p>
            </div>
        @endif

        <!-- Form Catatan -->
        <form action="{{ route('admin.data_absen.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-4">
            @csrf
            <div>
                <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan Harian</label>
                <textarea name="catatan" id="catatan" rows="4"
                    class="w-full bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"
                    placeholder="Tulis aktivitas hari ini..."></textarea>
            </div>

            <div>
                <label for="file_catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Unggah File</label>
                <input type="file" name="file_catatan" id="file_catatan"
                    class="w-full text-sm file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:bg-blue-500 file:text-white hover:file:bg-blue-600 dark:file:bg-blue-600 dark:hover:file:bg-blue-700">
            </div>

            <!-- Tombol Simpan Ditengahkan -->
            <div class="flex justify-center mt-4">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white font-semibold py-2 px-4 rounded text-sm transition duration-200">
                    Simpan Catatan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Cek jika ada pesan sukses
        @if (session('success'))
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000, 
                background: '#1f2937',
                color: '#f9fafb',
                toast: true,
                width: '350px',
                padding: '1.5rem',
                customClass: {
                    popup: 'swal2-noanimation swal2-padding',
                    title: 'swal2-title-large',
                },
            });
        @endif

        // Cek jika ada pesan error
        @if (session('error'))
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: true,    
                confirmButtonText: 'Tutup', 
                confirmButtonColor: '#dc2626',
                background: '#1f2937',
                color: '#f9fafb',
                width: '400px',
                padding: '2rem',
                customClass: {
                    popup: 'swal2-noanimation swal2-padding', 
                    title: 'swal2-title-large',
                },
            });
        @endif
    });
</script>
@endsection
