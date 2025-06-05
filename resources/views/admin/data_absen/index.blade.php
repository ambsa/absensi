@extends('layouts.main')

@section('title', 'Data Absen')

@section('content')


    <!-- Wrapper Responsif -->
    <div class="overflow-x-auto">
        <div class="overflow-x-auto rounded-lg">
            <table class="min-w-full w-full bg-[#161A23] border border-gray-700">
                <thead>
                <tr class="bg-gray-800 text-sm md:text-base">
                    <th class="py-3 px-2 md:px-4 border-b border-gray-700 text-center text-white">ID Absen</th>
                    <th class="py-3 px-2 md:px-4 border-b border-gray-700 text-center text-white">ID Pegawai</th>
                    <th class="py-3 px-2 md:px-4 border-b border-gray-700 text-center text-white">Jam Masuk</th>
                    <th class="py-3 px-2 md:px-4 border-b border-gray-700 text-center text-white">Jam Pulang</th>
                    <th class="py-3 px-2 md:px-4 border-b border-gray-700 text-center text-white">Catatan</th>
                    <th class="py-3 px-2 md:px-4 border-b border-gray-700 text-center text-white">File Catatan</th>
                    <th class="py-3 px-2 md:px-4 border-b border-gray-700 text-center text-white">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($datasens as $datasen)
                    <tr class="{{ $loop->even ? 'bg-gray-900' : 'bg-[#161A23]' }} text-center">
                        <td class="py-3 px-2 md:px-4 border-b border-gray-700 text-white">{{ $datasen->id_absen }}</td>
                        <td class="py-3 px-2 md:px-4 border-b border-gray-700 text-white">
                            {{ $datasen->pegawai->id_pegawai ?? '-' }}
                        </td>
                        <td class="py-3 px-2 md:px-4 border-b border-gray-700 text-white">{{ $datasen->jam_masuk }}</td>
                        <td class="py-3 px-2 md:px-4 border-b border-gray-700 text-white">{{ $datasen->jam_pulang }}</td>
                        <td class="py-3 px-2 md:px-4 border-b border-gray-700 text-white">{{ $datasen->catatan }}</td>
                        <td class="py-3 px-2 md:px-4 border-b border-gray-700 text-white">
                            @if ($datasen->file_catatan)
                                <a href="{{ asset('storage/' . $datasen->file_catatan) }}" target="_blank"
                                    class="text-blue-400 hover:text-blue-300">Lihat File</a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="py-3 px-2 md:px-4 border-b border-gray-700 text-white">
                            <div class="relative inline-block">
                                <!-- Tombol Trigger Dropdown -->
                                <button type="button"
                                    class="text-white hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    id="dropdownButton{{ $datasen->id_absen }}"
                                    onclick="toggleDropdown('dropdownMenu{{ $datasen->id_absen }}'); event.stopPropagation();">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>

                                <!-- Dropdown Menu -->
                                <div id="dropdownMenu{{ $datasen->id_absen }}"
                                    class="dropdown-menu hidden absolute right-0 mt-2 py-2 w-48 bg-[#161A23] rounded-lg shadow-md z-10">
                                    <a href="{{ route('admin.data_absen.preview-pdf', $datasen->id_absen) }}"
                                        target="_blank"
                                        class="block px-4 py-2 text-white hover:bg-gray-700 transition duration-200 ease-in-out">
                                        <i class="fa-solid fa-file-pdf mr-2"></i> Preview PDF
                                    </a>
                                    <a href="{{ route('admin.data_absen.download-csv', $datasen->id_absen) }}"
                                        class="block px-4 py-2 text-white hover:bg-gray-700 transition duration-200 ease-in-out">
                                        <i class="fa-solid fa-file-csv mr-2"></i> Download CSV
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-3 px-2 md:px-4 border-b border-gray-700 text-center text-white">
                            Tidak ada data absen.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal untuk Preview PDF -->
    <div id="pdfModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <!-- Modal Content -->
            <div class="relative w-full max-w-4xl bg-[#161A23] rounded-lg shadow-lg">
                <!-- Modal Header -->
                <div class="flex justify-between p-4 border-b border-gray-700">
                    <h5 class="text-xl font-bold text-white">Preview PDF</h5>
                    <button onclick="closePdfPopup()" class="text-gray-500 hover:text-gray-700">
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
                <div class="flex justify-end p-4 border-t border-gray-200">
                    <button onclick="closePdfPopup()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay Background -->
    <div id="pdfModalOverlay" class="fixed inset-0 z-40 hidden bg-black opacity-50"></div>

    <script>
        function confirmDelete(event) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                event.target.closest('form').submit();
            }
        }
    </script>
    <script>
        // Fungsi untuk membuka modal
        function openPdfPopup(pdfUrl) {
            // Set src embed ke URL PDF
            document.getElementById('pdfEmbed').src = pdfUrl;

            // Tampilkan modal dan overlay
            document.getElementById('pdfModal').classList.remove('hidden');
            document.getElementById('pdfModalOverlay').classList.remove('hidden');
        }

        // Fungsi untuk menutup modal
        function closePdfPopup() {
            // Sembunyikan modal dan overlay
            document.getElementById('pdfModal').classList.add('hidden');
            document.getElementById('pdfModalOverlay').classList.add('hidden');

            // Kosongkan src embed setelah modal ditutup
            document.getElementById('pdfEmbed').src = '';
        }
    </script>
    <script>
        // Fungsi untuk toggle dropdown
        function toggleDropdown(menuId) {
            const dropdown = document.getElementById(menuId);
            dropdown.classList.toggle('hidden');
        }

        // Event listener untuk menutup dropdown saat klik di luar
        document.addEventListener('click', function(event) {
            console.log('Global click detected:', event.target); // Debugging: Cek target klik

            const dropdownMenus = document.querySelectorAll('.dropdown-menu');

            dropdownMenus.forEach(function(dropdown) {
                console.log('Checking dropdown:', dropdown); // Debugging: Cek dropdown yang diperiksa

                if (!dropdown.contains(event.target) && !event.target.closest(
                        '[onclick^="toggleDropdown"]')) {
                    console.log('Closing dropdown:', dropdown); // Debugging: Cek dropdown yang ditutup
                    dropdown.classList.add('hidden');
                }
            });
        });
    </script>

    </div>
@endsection
