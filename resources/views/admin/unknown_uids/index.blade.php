@extends('layouts.main')

@section('title', 'UID Tidak Terdaftar')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <p class="text-gray-600 dark:text-gray-400">Daftar UID yang tidak terdaftar dalam sistem</p>
        </div>

        <!-- Card Container -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <!-- Card Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <span
                        class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-medium border border-blue-200 dark:border-blue-700">
                        {{ $unknownUids->total() }} Total
                    </span>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                No
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                UID
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tanggal Dibuat
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Waktu
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($unknownUids as $index => $uid)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ ($unknownUids->currentPage() - 1) * $unknownUids->perPage() + $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 border border-red-200 dark:border-red-700">
                                        {{ $uid->uuid }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($uid->created_at)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($uid->created_at)->format('H:i:s') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.pegawai.create', ['uuid' => $uid->uuid]) }}"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-green-400 transition-colors duration-200">
                                            <i class="fa-solid fa-plus mr-1"></i>
                                            Tambahkan
                                        </a>
                                        <button onclick="deleteUnknownUid({{ $uid->id_unknown }}, '{{ $uid->uuid }}')"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-red-400 transition-colors duration-200">
                                            <i class="fa-solid fa-trash mr-1"></i>
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i
                                            class="fa-solid fa-address-card text-4xl text-gray-400 dark:text-gray-500 mb-4"></i>
                                        <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Tidak ada UID tidak
                                            terdaftar</p>
                                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Semua UID yang masuk sudah
                                            terdaftar dalam
                                            sistem</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($unknownUids->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Menampilkan {{ $unknownUids->firstItem() ?? 0 }} sampai {{ $unknownUids->lastItem() ?? 0 }} dari
                            {{ $unknownUids->total() }} data
                        </div>
                        <div class="flex space-x-2">
                            {{ $unknownUids->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Alert Success/Error Messages -->
    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                icon: 'success',
                background: '#1f2937',
                color: '#f9fafb',
                backdrop: 'rgba(0, 0, 0, 0.8)',
                confirmButtonColor: '#10b981',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'dark-theme-popup',
                    title: 'text-gray-200',
                    content: 'text-gray-300',
                    confirmButton: 'bg-green-600 hover:bg-green-700 text-white'
                }
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                background: '#1f2937',
                color: '#f9fafb',
                backdrop: 'rgba(0, 0, 0, 0.8)',
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'dark-theme-popup',
                    title: 'text-gray-200',
                    content: 'text-gray-300',
                    confirmButton: 'bg-red-600 hover:bg-red-700 text-white'
                }
            });
        </script>
    @endif

    <script>
        function deleteUnknownUid(id, uuid) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus UID "${uuid}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#4b5563',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#1f2937',
                color: '#f9fafb',
                backdrop: 'rgba(0, 0, 0, 0.8)',
                customClass: {
                    popup: 'dark-theme-popup',
                    title: 'text-gray-200',
                    content: 'text-gray-300',
                    confirmButton: 'bg-red-600 hover:bg-red-700 text-white',
                    cancelButton: 'bg-gray-600 hover:bg-gray-700 text-white'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat form untuk delete
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('admin.unknown_uids.destroy', ':id') }}'.replace(':id', id);

                    // Tambahkan CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    // Tambahkan method DELETE
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
