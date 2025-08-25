@extends('layouts.main')

@section('title', 'Data Absen')

@section('content')
    <!-- Wrapper Responsif -->
    <div class="overflow-x-auto">
        <!-- Dropdown untuk memilih jumlah data -->
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

            <!-- Tombol Download -->
            <div class="flex space-x-2">
                <!-- Tombol Download PDF -->
                <button onclick="window.location.href='{{ route('admin.data_absen.download-all-pdf') }}'"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition duration-300 ease-in-out">
                    <i class="fa-solid fa-file-pdf mr-2"></i> Download PDF
                </button>
                <!-- Tombol Download Excel -->
                <button onclick="window.location.href='{{ route('admin.data_absen.download-all-excel') }}'"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-green-400 transition duration-300 ease-in-out">
                    <i class="fa-solid fa-file-excel mr-2"></i> Download Excel
                </button>
            </div>
        </div>

        <!-- Tabel Data Absen -->
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 shadow-md">
            <table class="min-w-full w-full bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th
                            class="py-3 px-2 md:px-4 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            No.
                        </th>
                        <th
                            class="py-3 px-2 md:px-4 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            ID Pegawai
                        </th>
                        <th
                            class="py-3 px-2 md:px-4 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Jam Masuk
                        </th>
                        <th
                            class="py-3 px-2 md:px-4 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Jam Pulang
                        </th>
                        <th
                            class="py-3 px-2 md:px-4 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Catatan
                        </th>
                        <th
                            class="py-3 px-2 md:px-4 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            File Catatan
                        </th>
                        <th
                            class="py-3 px-2 md:px-4 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($datasens as $datasen)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200 ease-in-out">
                            <td class="py-3 px-2 md:px-4 text-center whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $loop->iteration + ($datasens->currentPage() - 1) * $datasens->perPage() }}
                            </td>
                            <td class="py-3 px-2 md:px-4 text-center whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $datasen->pegawai->id_pegawai ?? '-' }}
                            </td>
                            <td class="py-3 px-2 md:px-4 text-center whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $datasen->jam_masuk }}
                            </td>
                            <td class="py-3 px-2 md:px-4 text-center whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $datasen->jam_pulang }}
                            </td>
                            <td class="py-3 px-2 md:px-4 text-center whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $datasen->catatan }}
                            </td>
                            <td class="py-3 px-2 md:px-4 text-center whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                @if ($datasen->file_catatan)
                                    <a href="{{ asset('uploads/catatan/' . $datasen->file_catatan) }}" target="_blank"
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition duration-200 ease-in-out">
                                        Lihat File
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-3 px-2 md:px-4 text-center whitespace-nowrap text-sm">
                                <div class="relative inline-block">
                                    <!-- Tombol Trigger Dropdown -->
                                    <button type="button"
                                        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-400"
                                        onclick="toggleDropdownAbsen('dropdownMenuAbsen{{ $datasen->id_absen }}'); event.stopPropagation();">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <!-- Dropdown Menu -->
                                    <div id="dropdownMenuAbsen{{ $datasen->id_absen }}"
                                        class="dropdown-menu hidden absolute right-0 mt-2 py-2 w-48 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 divide-y divide-gray-200 dark:divide-gray-600 rounded-lg shadow-xl z-20 transition-all duration-200">
                                        <a href="{{ route('admin.data_absen.preview-pdf', $datasen->id_absen) }}"
                                            target="_blank"
                                            class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white transition duration-200 ease-in-out">
                                            <i class="fa-solid fa-file-pdf mr-2 text-red-500 dark:text-red-400"></i> Preview PDF
                                        </a>
                                        <a href="{{ route('admin.data_absen.download-csv', $datasen->id_absen) }}"
                                            class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white transition duration-200 ease-in-out">
                                            <i class="fa-solid fa-file-csv mr-2 text-green-500 dark:text-green-400"></i> Download CSV
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-3 px-2 md:px-4 text-center text-sm text-gray-900 dark:text-gray-100">
                                Tidak ada data absen.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between px-4 py-3 sm:px-6 flex-col sm:flex-row">
            <!-- Informasi Paginasi (Tampilan Desktop) -->
            <div class="hidden sm:block sm:flex-1 sm:items-center sm:justify-between w-full mb-4 sm:mb-0">
                <!-- Navigasi Halaman -->
                <div>
                    {{ $datasens->appends(['per_page' => $perPage])->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Preview PDF -->
    <div id="pdfModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <!-- Modal Content -->
            <div class="relative w-full max-w-4xl bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                <!-- Modal Header -->
                <div class="flex justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                    <h5 class="text-xl font-bold text-gray-900 dark:text-white">Preview PDF</h5>
                    <button onclick="closePdfPopup()" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <!-- Modal Body -->
                <div class="p-4">
                    <!-- Embed PDF -->
                    <embed id="pdfEmbed" src="" type="application/pdf" class="w-full h-[500px]">
                </div>
                <!-- Modal Footer -->
                <div class="flex justify-end p-4 border-t border-gray-200 dark:border-gray-700">
                    <button onclick="closePdfPopup()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 rounded">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Overlay Background -->
    <div id="pdfModalOverlay" class="fixed inset-0 z-40 hidden bg-black opacity-50"></div>

    <script>
        // Fungsi untuk konfirmasi penghapusan
        function confirmDelete(event) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                event.target.closest('form').submit();
            }
        }

        // Fungsi untuk membuka modal PDF
        function openPdfPopup(pdfUrl) {
            document.getElementById('pdfEmbed').src = pdfUrl;
            document.getElementById('pdfModal').classList.remove('hidden');
            document.getElementById('pdfModalOverlay').classList.remove('hidden');
        }

        // Fungsi untuk menutup modal PDF
        function closePdfPopup() {
            document.getElementById('pdfModal').classList.add('hidden');
            document.getElementById('pdfModalOverlay').classList.add('hidden');
            document.getElementById('pdfEmbed').src = '';
        }

        function toggleDropdownAbsen(dropdownId) {
            let dropdownMenu = document.getElementById(dropdownId);
            let allDropdowns = document.querySelectorAll('.dropdown-menu');
            allDropdowns.forEach(function(menu) {
                if (menu !== dropdownMenu) {
                    menu.classList.add('hidden');
                }
            });
            dropdownMenu.classList.toggle('hidden');
        }

        // Tutup dropdown jika klik di luar
        document.addEventListener('click', function(event) {
            let dropdownMenus = document.querySelectorAll('.dropdown-menu');
            dropdownMenus.forEach(function(menu) {
                if (!menu.contains(event.target)) {
                    menu.classList.add('hidden');
                }
            });
        });
    </script>
@endsection
