@extends('layouts.main')

@section('title', 'Catatan Harian User')

@section('content')

    <div class="flex items-center justify-center bg-[#161A23] px-4">
        <div class="w-full max-w-2xl bg-[#1E293B] p-6 rounded-lg shadow-md text-white">
            <!-- Judul Form -->
            <h1 class="text-xl font-bold mb-6 text-center">Catatan Harian</h1>

            <!-- Form Input Catatan dan File -->
            <form action="{{ route('user.catatan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Input Catatan -->
                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-200">Catatan Harian</label>
                    <textarea id="catatan" name="catatan" rows="4"
                        class="mt-1 block w-full rounded-md border-gray-600 bg-[#2A303C] text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm {{ session('catatanSudahDiisi') ? 'cursor-not-allowed' : '' }}"
                        placeholder="Tulis catatan harian Anda..." {{ session('catatanSudahDiisi') ? 'disabled' : '' }}></textarea>
                </div>

                <!-- Input File Catatan -->
                <div>
                    <label for="file_catatan" class="block text-sm font-medium text-gray-200">Unggah File Catatan</label>
                    <input type="file" id="file_catatan" name="file_catatan"
                        class="mt-1 block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600 {{ session('catatanSudahDiisi') ? 'cursor-not-allowed' : '' }}"
                        {{ session('catatanSudahDiisi') ? 'disabled' : '' }}>
                </div>

                <!-- Tombol Submit -->
                <div class="flex justify-center">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        {{ session('catatanSudahDiisi') ? 'disabled' : '' }}>
                        Simpan Catatan
                    </button>
                </div>
            </form>

            <!-- SweetAlert -->
            <script>
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
            </script>
        </div>
    </div>

@endsection 
