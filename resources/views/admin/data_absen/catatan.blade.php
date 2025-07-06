<!-- catatan.blade.php -->
@extends('layouts.main')

@section('title', 'Catatan Harian')

@section('content')

<div class="flex items-center justify-center bg-[#161A23] px-4">
    <div class="w-full max-w-2xl bg-[#1E293B] p-6 rounded-lg shadow-md text-white">
        <h1 class="text-xl font-bold mb-6 text-center">Catatan Harian</h1>

        <!-- Form Catatan -->
        <form action="{{ route('admin.data_absen.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-4">
            @csrf
            <div>
                <label for="catatan" class="block text-sm font-medium text-gray-300 mb-1">Catatan Harian</label>
                <textarea name="catatan" id="catatan" rows="4"
                    class="w-full bg-[#2A303C] text-white border border-gray-600 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Tulis aktivitas hari ini..."></textarea>
            </div>

            <div>
                <label for="file_catatan" class="block text-sm font-medium text-gray-300 mb-1">Unggah File</label>
                <input type="file" name="file_catatan" id="file_catatan"
                    class="w-full text-sm file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:bg-blue-500 file:text-white hover:file:bg-blue-600">
            </div>

            <!-- Tombol Simpan Ditengahkan -->
            <div class="flex justify-center mt-4">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded text-sm transition duration-200">
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
                background: '#161A23',
                color: '#ffffff',
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
                confirmButtonColor: '#dc3545',
                background: '#161A23',
                color: '#ffffff',
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
