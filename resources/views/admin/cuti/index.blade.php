@extends('layouts.main')

@section('title', 'Cuti')

@section('content')
    <!-- Wrapper Responsif -->
    <div class="overflow-x-auto">
        <!-- Dropdown untuk memilih jumlah data dan Tombol Tambah Cuti -->
        <div class="mb-4 flex justify-between items-center">
            <div>
                <label for="per_page" class="text-gray-700 dark:text-gray-300 text-sm">Tampilkan:</label>
                <select id="per_page" name="per_page"
                    class="ml-2 px-2 py-1 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"
                    onchange="location.href='?per_page=' + this.value">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                    <option value="500" {{ $perPage == 500 ? 'selected' : '' }}>500</option>
                </select>
            </div>

            <!-- Tombol Tambah Cuti -->
            <a href="{{ route('admin.cuti.create') }}"
                class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-3 py-1 rounded flex items-center transition duration-200">
                <i class="fa-solid fa-plus mr-2"></i>
                <span class="inline-flex">Tambah cuti</span>
            </a>
        </div>

        <!-- Container untuk tabel -->
        <div class="container mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
            <!-- Tabel Cuti -->
            <div class="shadow-md overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table id="cuti-table" class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-[50px]">
                                No
                            </th>
                            <th
                                class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-[100px]">
                                ID
                            </th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nama Karyawan
                            </th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Jenis Cuti
                            </th>
                            <th
                                class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-[120px]">
                                Tanggal Mulai
                            </th>
                            <th
                                class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-[120px]">
                                Tanggal Selesai
                            </th>
                            <th
                                class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-[100px]">
                                Alasan
                            </th>
                            <th
                                class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-[100px]">
                                Status
                            </th>
                            <th
                                class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-[100px]">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($cuti as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $loop->iteration + ($cuti->currentPage() - 1) * $cuti->perPage() }}
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $item->id_cuti }}
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $item->pegawai->nama_pegawai }}
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $item->jenis_cuti->nama }}
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ Str::limit($item->alasan, 50, '...') }}
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm">
                                    @if ($item->status == 'pending')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">Pending</span>
                                    @elseif ($item->status == 'approved')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Approved</span>
                                    @else
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">Rejected</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap relative text-sm">
                                    <!-- Tombol Trigger Dropdown -->
                                    <button type="button" class="focus:outline-none"
                                        onclick="toggleDropdown({{ $item->id_cuti }})">
                                        <i class="fa-solid fa-ellipsis text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-lg"></i>
                                    </button>
                                    <!-- Dropdown Aksi -->
                                    <div id="cuti-dropdown-{{ $item->id_cuti }}"
                                        class="hidden absolute left-0 mt-2 -translate-x-full max-w-xs sm:w-48 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600 rounded-lg shadow-lg z-50 transform scale-95 transition-all duration-300 ease-in-out p-2">
                                        <div class="py-1">
                                            <!-- Approve -->
                                            <form action="{{ route('admin.cuti.update_status', $item->id_cuti) }}"
                                                method="POST" class="block">
                                                @csrf @method('PUT')
                                                <button type="submit" name="status" value="approved"
                                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-green-100 dark:hover:bg-green-600 hover:text-gray-900 dark:hover:text-white rounded-t-md whitespace-nowrap">
                                                    <i class="fa-solid fa-check mr-2 text-green-500 dark:text-green-400"></i> Approve
                                                </button>
                                            </form>
                                            <!-- Reject -->
                                            <form action="{{ route('admin.cuti.update_status', $item->id_cuti) }}"
                                                method="POST" class="block">
                                                @csrf @method('PUT')
                                                <button type="submit" name="status" value="rejected"
                                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-red-100 dark:hover:bg-red-600 hover:text-gray-900 dark:hover:text-white whitespace-nowrap">
                                                    <i class="fa-solid fa-ban mr-2 text-red-500 dark:text-red-400"></i> Reject
                                                </button>
                                            </form>
                                        </div>
                                        <div class="py-1">
                                            <!-- Hapus -->
                                            <form id="delete-form-{{ $item->id_cuti }}"
                                                action="{{ route('admin.cuti.destroy', $item->id_cuti) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button" onclick="confirmDelete({{ $item->id_cuti }})"
                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-red-100 dark:hover:bg-red-700 hover:text-gray-900 dark:hover:text-white rounded-b-md whitespace-nowrap">
                                                <i class="fa-solid fa-trash mr-2 text-red-500 dark:text-red-400"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between px-4 py-3 sm:px-6 flex-col sm:flex-row">
            <!-- Informasi Paginasi (Tampilan Desktop) -->
            <div class="hidden sm:block sm:flex-1 sm:items-center sm:justify-between w-full mb-4 sm:mb-0">
                <!-- Navigasi Halaman -->
                <div>
                    {{ $cuti->appends(['per_page' => $perPage])->links('pagination::tailwind') }}
                </div>
            </div>
        </div>

        <!-- Tombol Previous dan Next untuk Mobile -->
        <div class="flex flex-1 justify-between sm:hidden w-full">
            <button id="prev-button-mobile"
                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 {{ !$cuti->onFirstPage() ? '' : 'disabled opacity-50 cursor-not-allowed' }}">
                Previous
            </button>
            <button id="next-button-mobile"
                class="relative ml-3 inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 {{ $cuti->hasMorePages() ? '' : 'disabled opacity-50 cursor-not-allowed' }}">
                Next
            </button>
        </div>
    </div>

    <!-- SweetAlert Styling -->
    <style>
        .swal-dark {
            animation: slide-in 0.3s ease-out;
        }

        @keyframes slide-in {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(0);
            }
        }
    </style>

    <!-- JavaScript untuk Konfirmasi Penghapusan -->
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
                    // Submit form jika pengguna mengonfirmasi
                    document.getElementById('delete-form-' + id).submit();
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

    <!-- JavaScript untuk Toggle Dropdown -->
    <script>
        function toggleDropdown(id) {
            // Tutup semua dropdown cuti lain
            document.querySelectorAll('[id^="cuti-dropdown-"]').forEach(dropdown => {
                if (dropdown.id !== `cuti-dropdown-${id}`) {
                    dropdown.classList.add('hidden');
                }
            });
            // Toggle dropdown yang diklik
            const dropdown = document.getElementById(`cuti-dropdown-${id}`);
            dropdown.classList.toggle('hidden');
        }

        // Tutup dropdown jika klik di luar
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('[id^="cuti-dropdown-"]');
            dropdowns.forEach(dropdown => {
                if (!dropdown.contains(event.target) &&
                    !event.target.closest(
                        `[onclick='toggleDropdown(${dropdown.id.split('-')[2]}, this)']`) &&
                    !event.target.matches('.fa-ellipsis')) {
                    dropdown.classList.add('hidden');
                }
            });
        });
    </script>
@endsection