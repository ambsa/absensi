@extends('layouts.main')

@section('title', 'Cuti')

@section('content')
    <!-- Wrapper Responsif -->
    <div class="overflow-x-auto">
        <!-- Dropdown untuk memilih jumlah data dan Tombol Tambah Cuti -->
        <div class="mb-4 flex justify-between items-center">
            <div>
                <label for="per_page" class="text-gray-400 text-sm">Tampilkan:</label>
                <select id="per_page" name="per_page"
                    class="ml-2 px-2 py-1 bg-[#1E293B] text-white border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    onchange="location.href='?per_page=' + this.value">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                    <option value="500" {{ $perPage == 500 ? 'selected' : '' }}>500</option>
                </select>
            </div>

            <!-- Tombol Tambah Cuti -->
            <a href="{{ route('admin.cuti.create') }}"
                class="bg-indigo-800 text-white px-3 py-1 rounded hover:bg-blue-900 flex items-center sm:flex">
                <i class="fa-solid fa-plus mr-2"></i>
                <span class="inline-flex">Tambah cuti</span>
            </a>
        </div>

        <!-- Container untuk tabel -->
        <div class="container mx-auto p-6 bg-[#161A23] rounded-lg">
            <!-- Tabel Cuti -->
            <div class="shadow-md overflow-x-auto rounded-lg border border-gray-700">
                <table id="cuti-table" class="w-full divide-y divide-gray-700">
                    <thead class="bg-gray-800 sticky top-0 z-10">
                        <tr>
                            <th
                                class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider w-[50px]">
                                No
                            </th>
                            <th
                                class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider w-[100px]">
                                ID
                            </th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Nama Karyawan
                            </th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Jenis Cuti
                            </th>
                            <th
                                class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider w-[120px]">
                                Tanggal Mulai
                            </th>
                            <th
                                class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider w-[120px]">
                                Tanggal Selesai
                            </th>
                            <th
                                class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider w-[100px]">
                                Alasan
                            </th>
                            <th
                                class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider w-[100px]">
                                Status
                            </th>
                            <th
                                class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider w-[100px]">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach ($cuti as $item)
                            <tr class="hover:bg-gray-700 transition duration-200 ease-in-out">
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-300">
                                    {{ $loop->iteration + ($cuti->currentPage() - 1) * $cuti->perPage() }}
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-300">
                                    {{ $item->id_cuti }}
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-300">
                                    {{ $item->pegawai->nama_pegawai }}
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-300">
                                    {{ $item->jenis_cuti->nama }}
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-300">
                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-300">
                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-300">
                                    {{ Str::limit($item->alasan, 50, '...') }}
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm">
                                    @if ($item->status == 'pending')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-500 text-black">Pending</span>
                                    @elseif ($item->status == 'approved')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-500 text-white">Approved</span>
                                    @else
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-500 text-white">Rejected</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap relative text-sm">
                                    <!-- Tombol Trigger Dropdown -->
                                    <button type="button" class="focus:outline-none"
                                        onclick="toggleDropdown({{ $item->id_cuti }})">
                                        <i class="fa-solid fa-ellipsis text-gray-400 hover:text-gray-200 text-lg"></i>
                                    </button>
                                    <!-- Dropdown Aksi -->
                                    <div id="cuti-dropdown-{{ $item->id_cuti }}"
                                        class="hidden absolute left-0 mt-2 -translate-x-full max-w-xs sm:w-48 border border-gray-700 bg-[#1E293B] divide-y divide-gray-700 rounded-lg shadow-lg z-50 transform scale-95 transition-all duration-300 ease-in-out p-2">
                                        <div class="py-1">
                                            <!-- Approve -->
                                            <form action="{{ route('admin.cuti.update_status', $item->id_cuti) }}"
                                                method="POST" class="block">
                                                @csrf @method('PUT')
                                                <button type="submit" name="status" value="approved"
                                                    class="flex items-center w-full px-4 py-2 text-sm text-left text-gray-300 hover:bg-green-600 hover:text-white rounded-t-md whitespace-nowrap"
                                                    {{ $item->updated_at_status && now()->diffInHours($item->updated_at_status) >= 1 ? 'disabled' : '' }}>
                                                    <i class="fa-solid fa-check mr-2 text-green-400"></i> Approve
                                                </button>
                                            </form>
                                            <!-- Reject -->
                                            <form action="{{ route('admin.cuti.update_status', $item->id_cuti) }}"
                                                method="POST" class="block">
                                                @csrf @method('PUT')
                                                <button type="submit" name="status" value="rejected"
                                                    class="flex items-center w-full px-4 py-2 text-sm text-left text-gray-300 hover:bg-red-600 hover:text-white whitespace-nowrap">
                                                    <i class="fa-solid fa-ban mr-2 text-red-400"></i> Reject
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
                                                class="flex items-center w-full px-4 py-2 text-sm text-left text-gray-300 hover:bg-red-700 hover:text-white rounded-b-md whitespace-nowrap">
                                                <i class="fa-solid fa-trash mr-2 text-red-400"></i> Hapus
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
                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-[#1E293B] hover:bg-gray-800 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 {{ !$cuti->onFirstPage() ? '' : 'disabled opacity-50 cursor-not-allowed' }}"
                onclick="location.href='{{ $cuti->previousPageUrl() }}'">
                Previous
            </button>
            <button id="next-button-mobile"
                class="relative ml-3 inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-[#1E293B] hover:bg-gray-800 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 {{ $cuti->hasMorePages() ? '' : 'disabled opacity-50 cursor-not-allowed' }}"
                onclick="location.href='{{ $cuti->nextPageUrl() }}'">
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
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                background: '#161A23',
                color: '#ffffff'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form jika pengguna mengonfirmasi
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
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
