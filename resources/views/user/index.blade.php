@extends('layouts.main')

@section('title', 'User Dashboard')

@section('content')
<div class="container mx-auto bg-[#161A23]">
    <!-- Grid Layout Utama -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-3">
        <!-- Kolom Kiri: Kartu Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Total Pengajuan Cuti -->
            <div class="bg-[#1E293B] p-5 rounded-xl shadow-md text-center space-y-4">
                <div class="flex justify-left">
                    <i class="fa-solid fa-calendar-days text-4xl text-gray-500"></i>
                </div>
                <div class="text-left">
                    <h5 class="text-4xl font-bold text-white mb-5">{{ $totalCuti }}</h5>
                    <h3 class="text-md font-semibold text-gray-400">Total Pengajuan Cuti</h3>   
                </div>
            </div>
        
            <!-- Pengajuan Cuti Pending -->
            <div class="bg-[#1E293B] p-5 rounded-xl shadow-md text-center space-y-4">
                <div class="flex justify-left">
                    <i class="fa-solid fa-clock text-4xl text-yellow-500"></i>
                </div>
                <div class="text-left">
                    <p class="text-4xl font-bold text-white mb-5">{{ $cutiPending }}</p>
                    <h3 class="text-md font-semibold text-yellow-500">Menunggu Persetujuan</h3>
                </div>
            </div>
        
            <!-- Pengajuan Cuti Approved -->
            <div class="bg-[#1E293B] p-5 rounded-lg shadow-md text-center space-y-4">
                <div class="flex justify-left">
                    <i class="fa-solid fa-check-circle text-4xl text-green-500"></i>
                </div>
                <div class="text-left">
                    <p class="text-4xl font-bold text-white mb-5">{{ $cutiApproved }}</p>                            
                    <h3 class="text-md font-semibold text-green-500">Disetujui</h3>
                </div>
            </div>
        
            <!-- Pengajuan Cuti Rejected -->
            <div class="bg-[#1E293B] p-5 rounded-lg shadow-md text-center space-y-4">
                <div class="flex justify-left">
                    <i class="fa-solid fa-times-circle text-4xl text-red-500"></i>
                </div>
                <div class="text-left">
                    <p class="text-4xl font-bold text-white mb-5">{{ $cutiRejected }}</p>
                    <h3 class="text-md font-semibold text-red-500">Ditolak</h3>
                </div>
            </div>
        </div>
        <div class="bg-[#161A23] border border-gray-700 shadow-lg rounded-lg text-white p-6">
            <!-- Judul Form -->
            <h2 class="text-xl font-bold mb-6 text-center text-gray-200">Form Absensi</h2>

            <!-- Form Input Catatan dan File -->
            <form action="{{ route('user.data_absen.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
            
                <!-- Input Catatan -->
                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-200">Catatan Harian</label>
                    <textarea 
                        id="catatan" 
                        name="catatan" 
                        rows="4" 
                        class="mt-1 block w-full rounded-md border-gray-600 bg-[#2A303C] text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm {{ session('catatanSudahDiisi') ? 'cursor-not-allowed' : '' }}" 
                        placeholder="Tulis catatan harian Anda..."
                        {{ session('catatanSudahDiisi') ? 'disabled' : '' }}
                    ></textarea>
                </div>
            
                <!-- Input File Catatan -->
                <div>
                    <label for="file_catatan" class="block text-sm font-medium text-gray-200">Unggah File Catatan</label>
                    <input 
                        type="file" 
                        id="file_catatan" 
                        name="file_catatan" 
                        class="mt-1 block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600 {{ session('catatanSudahDiisi') ? 'cursor-not-allowed' : '' }}"
                        {{ session('catatanSudahDiisi') ? 'disabled' : '' }}
                    >
                </div>
            
                <!-- Tombol Submit -->
                <div class="flex justify-center">
                    <button 
                        type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        {{ session('catatanSudahDiisi') ? 'disabled' : '' }}
                    >
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
                        title: '{{ session("success") }}',
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
                        title: '{{ session("error") }}',
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
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2 @11"></script>
@endsection
