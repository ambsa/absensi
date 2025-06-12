@extends('layouts.main')

@section('title', 'Pengajuan WFH')

@section('content')

    <div class="container mx-auto p-6 bg-[#161A23]">
        <div class="flex justify-between items-center mb-4">
            <!-- Filter Status -->
            <form action="{{ route('admin.wfh.index') }}" method="GET" class="flex items-center space-x-2">
                <label for="status" class="font-semibold text-white">Filter Status:</label>
                <select name="status" id="status"
                    class="border border-gray-700 p-2 rounded-md text-gray-300 bg-gray-700 w-32">
                    <option value="" {{ request('status') === '' ? 'selected' : '' }}>Semua</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </form>

            <!-- Tombol Ajukan WFH -->
            <div class="mb-4 flex justify-end">
                <a href="{{ route('admin.wfh.create') }}"
                    class="bg-indigo-800 text-white px-3 py-1 rounded hover:bg-blue-900 flex items-center">
                    <i class="fa-solid fa-plus mr-2"></i>Ajukan WFH
                </a>
            </div>
        </div>

        <!-- Tabel Pengajuan WFH -->
        <div class="shadow-md">
            <table class="w-full bg-[#1E293B] divide-y divide-gray-700 rounded-lg">
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
                        <th
                            class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider w-[120px]">
                            Tanggal
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
                    @foreach ($wfhs as $item)
                        <tr class="hover:bg-gray-900">
                            <td class="px-3 py-2 text-center whitespace-nowrap">
                                {{ $loop->iteration + ($wfhs->currentPage() - 1) * $wfhs->perPage() }}
                            </td>
                            <td class="px-3 py-2 text-center whitespace-nowrap">
                                {{ $item->id_wfh }}
                            </td>
                            <td class="px-3 py-2 text-center whitespace-nowrap">
                                {{ $item->pegawai?->nama_pegawai ?? 'Pegawai Tidak Ditemukan' }}
                            </td>
                            <td class="px-3 py-2 text-center whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                            </td>
                            <td class="px-3 py-2 text-center whitespace-nowrap">
                                @if ($item->status == 'pending')
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-500 text-white">Pending</span>
                                @elseif ($item->status == 'approved')
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-500 text-white">Approved</span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-500 text-white">Rejected</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 text-center whitespace-nowrap relative">
                                <!-- Dropdown Aksi -->
                                <button type="button" class="focus:outline-none"
                                    onclick="toggleDropdown({{ $item->id_wfh }})">
                                    <i class="fa-solid fa-ellipsis text-gray-400 hover:text-gray-200 text-lg"></i>
                                </button>
                                <div id="wfh-dropdown-{{ $item->id_wfh }}"
                                    class="hidden absolute right-0 mt-2 w-48 border border-gray-700 bg-[#1E293B] divide-y divide-gray-700 rounded-lg shadow-lg z-50 transform scale-95 transition-all duration-300 ease-in-out">
                                    <div class="py-1">
                                        <!-- Approve -->
                                        <form action="{{ route('admin.wfh.update', $item->id_wfh) }}" method="POST"
                                            class="block">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" name="status" value="approved"
                                                class="flex items-center w-full px-4 py-2 text-sm text-left text-gray-300 hover:bg-green-600 hover:text-white rounded-t-md"
                                                {{ $item->status !== 'pending' || ($item->updated_at && now()->diffInHours($item->updated_at) >= 1) ? 'disabled' : '' }}>
                                                <i class="fa-solid fa-check mr-2 text-green-400"></i> Approve
                                            </button>
                                        </form>
                                        <!-- Reject -->
                                        <form action="{{ route('admin.wfh.update', $item->id_wfh) }}" method="POST"
                                            class="block">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" name="status" value="rejected"
                                                class="flex items-center w-full px-4 py-2 text-sm text-left text-gray-300 hover:bg-red-600 hover:text-white"
                                                {{ $item->status !== 'pending' || ($item->updated_at && now()->diffInHours($item->updated_at) >= 1) ? 'disabled' : '' }}>
                                                <i class="fa-solid fa-ban mr-2 text-red-400"></i> Reject
                                            </button>
                                        </form>
                                    </div>
                                    <div class="py-1">
                                        <!-- Hapus -->
                                        <button type="button" onclick="confirmDelete('{{ $item->id_wfh }}')"
                                            class="flex items-center w-full px-4 py-2 text-sm text-left text-gray-300 hover:bg-red-700 hover:text-white rounded-b-md">
                                            <i class="fa-solid fa-trash mr-2 text-red-400"></i> Hapus
                                        </button>
                                        <!-- Form Hapus (sembunyi) -->
                                        <form id="delete-form-{{ $item->id_wfh }}"
                                            action="{{ route('admin.wfh.destroy', $item->id_wfh) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $wfhs->links('pagination::tailwind') }}
        </div>

        <!-- Floating Button untuk Absen -->
        @if ($wfhApprovedToday)
            <button id="floatingButton" class="floating-button hidden" onclick="openModal()">
                Absen {{ isMorning() ? 'Masuk' : 'Pulang' }}
            </button>

            <!-- Modal Absen -->
            <div id="absenModal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Absen Kehadiran WFH/WFA</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Absen sebagai:</p>
                            <form id="absenForm" action="{{ route('absen.wfh.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="wfh_id" value="{{ $wfhApprovedToday?->id ?? '' }}">
                                <input type="hidden" name="type" id="absenType" value="">
                                <button type="submit" class="btn btn-success w-full mb-2"
                                    onclick="setAbsenType('masuk')" style="display: none;" id="btnMasuk">
                                    Absen Masuk
                                </button>
                                <button type="submit" class="btn btn-danger w-full" onclick="setAbsenType('pulang')"
                                    style="display: none;" id="btnPulang">
                                    Absen Pulang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <script>
                let alertType = '{{ session('alertType') }}';
                let message = '{{ session('success') }}';

                Swal.fire({
                    position: 'center',
                    icon: alertType, // Gunakan alertType dari session
                    title: message, // Gunakan pesan dari session
                    showConfirmButton: false,
                    timer: 1500,
                    toast: true,
                    background: '#1E293B', // Warna latar belakang gelap
                    color: '#ffffff', // Warna teks putih
                    customClass: {
                        popup: 'swal-dark' // Kelas kustom untuk tema gelap
                    }
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                let alertType = '{{ session('alertType') }}';
                let message = '{{ session('error') }}';

                Swal.fire({
                    position: 'center',
                    icon: alertType, // Gunakan alertType dari session
                    title: message, // Gunakan pesan dari session
                    showConfirmButton: false,
                    timer: 1500,
                    toast: true,
                    background: '#1E293B', // Warna latar belakang gelap
                    color: '#ffffff', // Warna teks putihbackdrop: 'rgba(0, 0, 0, 0.8)', // Efek bayangan gelap
                    customClass: {
                        popup: 'swal-dark' // Kelas kustom untuk tema gelap
                    }
                });
            </script>
        @endif

        <!-- JavaScript untuk Dropdown -->
        <script>
            // Fungsi untuk membuka/tutup dropdown
            function toggleDropdown(id) {
                // Tutup semua dropdown lainnya
                document.querySelectorAll('[id^="wfh-dropdown-"]').forEach(dropdown => {
                    if (dropdown.id !== `wfh-dropdown-${id}`) {
                        dropdown.classList.add('hidden');
                    }
                });
                // Toggle dropdown yang dipilih
                const dropdown = document.getElementById(`wfh-dropdown-${id}`);
                if (dropdown) {
                    dropdown.classList.toggle('hidden');
                }
            }

            // Mendeteksi klik di luar dropdown
            document.addEventListener('click', function(event) {
                // Ambil semua dropdown wfh
                const dropdowns = document.querySelectorAll('[id^="wfh-dropdown-"]');
                // Iterasi melalui setiap dropdown
                dropdowns.forEach(dropdown => {
                    const id = dropdown.id.split('-')[2]; // Parse ID dari dropdown
                    // Jika klik tidak terjadi di dalam dropdown atau tombol trigger dropdown, tutup dropdown tersebut
                    if (!dropdown.contains(event.target) &&
                        !event.target.matches(`[onclick="toggleDropdown(${id})"]`) &&
                        !event.target.closest(`[onclick="toggleDropdown(${id})"]`)) {
                        dropdown.classList.add('hidden');
                    }
                });
            });

            // Konfirmasi hapus
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
                        // Kirim form hapus
                        document.getElementById(`delete-form-${id}`).submit();
                    }
                });
            }
        </script>


        <script>
            function isMorning() {
                const now = new Date();
                const hours = now.getHours();
                return hours >= 7 && hours < 9; // Jam 07.00-09.00
            }

            function isEvening() {
                const now = new Date();
                const hours = now.getHours();
                return hours >= 15 && hours < 17; // Jam 15.00-17.00
            }

            function toggleFloatingButton() {
                const button = document.getElementById('floatingButton');
                const btnMasuk = document.getElementById('btnMasuk');
                const btnPulang = document.getElementById('btnPulang');

                if (isMorning()) {
                    button.textContent = 'Absen Masuk';
                    button.classList.remove('hidden');
                    btnMasuk.style.display = 'block';
                    btnPulang.style.display = 'none';
                } else if (isEvening()) {
                    button.textContent = 'Absen Pulang';
                    button.classList.remove('hidden');
                    btnMasuk.style.display = 'none';
                    btnPulang.style.display = 'block';
                } else {
                    button.classList.add('hidden');
                    btnMasuk.style.display = 'none';
                    btnPulang.style.display = 'none';
                }
            }

            setInterval(toggleFloatingButton, 1000);
            toggleFloatingButton();

            function openModal() {
                const modal = new bootstrap.Modal(document.getElementById('absenModal'));
                modal.show();
            }

            function setAbsenType(type) {
                document.getElementById('absenType').value = type;
            }
        </script>

        <!-- SweetAlert CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Bootstrap JS untuk Modal -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <!-- CSS untuk Button Mengambang -->
        <style>
            .floating-button {
                position: fixed;
                bottom: 20px;
                right: 20px;
                width: 150px;
                height: 50px;
                background-color: #3B82F6;
                color: white;
                border: none;
                border-radius: 5px;
                font-size: 16px;
                z-index: 50;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: transform 0.2s ease-in-out;
            }

            .floating-button:hover {
                transform: scale(1.1);
            }

            .floating-button.hidden {
                display: none;
            }

            /* Gaya Modal */
            .modal-content {
                background-color: #1E293B !important;
                color: white !important;
            }
        </style>
    @endsection

    @section('scripts')
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    @endsection
