@extends('layouts.main')

@section('title', 'Pengajuan WFH')

@section('content')

    <div class="container mx-auto p-6 bg-[#161A23]">
        <div class="flex flex-col space-y-4 mb-6">
            <!-- Filter Status -->
            <div class="flex items-center space-x-4">
                <select name="status" id="status"
                    class="border border-gray-700 p-2 rounded-md text-sm text-gray-300 bg-gray-700 w-32">
                    <option value="" {{ request('status') === '' ? 'selected' : '' }}>Semua</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>

            <!-- Tombol Ajukan WFH -->
            <div class="flex justify-end">
                <a href="{{ route('admin.wfh.create') }}"
                    class="bg-indigo-800 text-white px-4 py-2 rounded hover:bg-blue-900 flex items-center space-x-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Ajukan WFH</span>
                </a>
            </div>
        </div>

        <!-- Tabel Pengajuan WFH -->
        <div class="overflow-x-auto">
            <div class="overflow-x-auto rounded-lg border border-gray-700 shadow-md">
                <table class="min-w-full w-full bg-[#161A23] divide-y divide-gray-700">
                    <thead class="bg-gray-800">
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
                            <tr class="hover:bg-gray-700 transition duration-200 ease-in-out">
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-300">
                                    {{ $loop->iteration + ($wfhs->currentPage() - 1) * $wfhs->perPage() }}
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-300">
                                    {{ $item->id_wfh }}
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-300">
                                    {{ $item->pegawai?->nama_pegawai ?? 'Pegawai Tidak Ditemukan' }}
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-300">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
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
        </div>


        <!-- Pagination -->
        <div class="mt-4 flex justify-center">
            {{ $wfhs->links('pagination::tailwind') }}
        </div>

        <!-- Tombol Absen di Luar Tabel -->
        <div class="mt-6">
            @foreach ($wfhs as $item)
                @if ($item->status == 'approved' && now()->toDateString() == \Carbon\Carbon::parse($item->tanggal)->toDateString())
                    <div class="mb-4">
                        <p class="text-gray-300 text-sm mb-2">
                            Pegawai: {{ $item->pegawai?->nama_pegawai ?? 'Pegawai Tidak Ditemukan' }} -
                            Tanggal: {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                        </p>

                        <!-- Tombol Absen Masuk -->
                        @if (!$item->absen_masuk)
                            <form action="{{ route('admin.wfh.absen.masuk', $item->id_wfh) }}" method="POST"
                                class="inline-block">
                                @csrf
                                <button type="submit"
                                    class="ml-2 px-3 py-1 bg-blue-500 text-white text-xs rounded-md hover:bg-blue-600">
                                    Hadir
                                </button>
                            </form>
                        @endif

                        <!-- Tombol Absen Pulang -->
                        @if ($item->absen_masuk && !$item->absen_pulang)
                            <form action="{{ route('admin.wfh.absen.pulang', $item->id_wfh) }}" method="POST"
                                class="inline-block">
                                @csrf
                                <button type="submit"
                                    class="ml-2 px-3 py-1 bg-red-500 text-white text-xs rounded-md hover:bg-red-600">
                                    Pulang
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>


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


    @endsection

    @section('scripts')
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    @endsection
