@extends('layouts.main')

@section('title', 'Cuti')

@section('content')
    <div class="mb-4 flex justify-end">
        <a href="{{ route('user.cuti.create') }}"
            class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-3 py-1 rounded flex items-center transition duration-200">
            <i class="fa-solid fa-plus mr-2"></i>Tambah Cuti
        </a>
    </div>

    <div class="container mx-auto p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg rounded-lg text-gray-900 dark:text-gray-100">
        <!-- Judul Halaman -->
        <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Daftar Pengajuan Cuti Saya</h2>

        <!-- Alert jika ada cuti pending -->
        @if ($pendingCuti)
            <div class="mb-6 p-4 bg-yellow-100 dark:bg-yellow-900 border border-yellow-300 dark:border-yellow-700 rounded-lg text-yellow-800 dark:text-yellow-200">
                <strong>Perhatian:</strong> Anda memiliki pengajuan cuti dengan status 
                <span class="font-semibold">Pending</span>. 
                Harap tunggu konfirmasi dari admin sebelum mengajukan cuti lagi.
            </div>
        @endif

        <!-- Tabel Cuti -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jenis Cuti</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Mulai</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Selesai</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($cuti as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $item->id_cuti }}</td>
                            <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $item->jenis_cuti->nama }}</td>
                            <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                @if ($item->status == 'pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-lg bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">Pending</span>
                                @elseif ($item->status == 'approved')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-lg bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Approved</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-lg bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">Rejected</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($cuti->isEmpty())
                <p class="text-center py-4 text-gray-500 dark:text-gray-400">Belum ada pengajuan cuti.</p>
            @endif
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                background: '#1f2937',
                color: '#f9fafb'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>

    <!-- SweetAlert2 Notification -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
