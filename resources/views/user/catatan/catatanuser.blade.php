@extends('layouts.main')

@section('title', 'Catatan Harian User')

@section('content')

<div class="flex items-center justify-center bg-[#161A23] px-4">
    <div class="w-full max-w-2xl bg-[#1E293B] p-6 rounded-lg shadow-md text-white">
        <h1 class="text-xl font-bold mb-6 text-center">Catatan Harian</h1>

        <!-- Form Catatan -->
        <form action="{{ route('user.catatan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
        
            <!-- Input Catatan -->
            <div>
                <label for="catatan" class="block text-sm font-medium text-gray-300 mb-1">Catatan Harian</label>
                <textarea name="catatan" id="catatan" rows="4"
                    class="w-full bg-[#2A303C] text-white border border-gray-600 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 {{ session('catatanSudahDiisi') ? 'cursor-not-allowed' : '' }}"
                    placeholder="Tulis aktivitas hari ini..." {{ session('catatanSudahDiisi') ? 'disabled' : '' }}></textarea>
            </div>
        
            <!-- Input File -->
            <div>
                <label for="file_catatan" class="block text-sm font-medium text-gray-300 mb-1">Unggah File</label>
                <input type="file" name="file_catatan" id="file_catatan"
                    class="w-full text-sm file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:bg-blue-500 file:text-white hover:file:bg-blue-600 {{ session('catatanSudahDiisi') ? 'cursor-not-allowed' : '' }}"
                    {{ session('catatanSudahDiisi') ? 'disabled' : '' }}>
            </div>
        
            <!-- Tombol Submit -->
            <div class="flex justify-center mt-4">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded text-sm transition duration-200 {{ session('catatanSudahDiisi') ? 'opacity-50 cursor-not-allowed' : '' }}"
                    {{ session('catatanSudahDiisi') ? 'disabled' : '' }}>
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
                timer: 1500,
                background: '#161A23',
                color: '#ffffff',
                toast: true,
                width: '250px',
                padding: '1rem',
                customClass: {
                    popup: 'swal2-noanimation',
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
                background: '#161A23',
                color: '#ffffff',
                toast: true,
                width: '250px',
                padding: '1rem',
                customClass: {
                    popup: 'swal2-noanimation',
                },
            });
        @endif
    });
</script>

    
@endsection
