@extends('layouts.main')

@section('title', 'Pengajuan WFH')

@section('content')

    <div class="container mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="flex flex-col space-y-4 mb-6">
            <!-- Filter Status -->
            <form method="GET" action="{{ route('admin.wfh.index') }}" class="flex items-center space-x-4">
                <select name="status" id="status"
                    class="border border-gray-300 dark:border-gray-600 p-2 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 w-32 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                    <option value="" {{ request('status') === '' ? 'selected' : '' }}>Semua</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-200">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </form>

            <!-- Tombol Ajukan WFH -->
            <div class="flex justify-end">
                <a href="{{ route('admin.wfh.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-4 py-2 rounded flex items-center space-x-2 transition duration-200">
                    <i class="fa-solid fa-plus"></i>
                    <span>Ajukan WFH</span>
                </a>
            </div>
        </div>

        <!-- Tabel Pengajuan WFH -->
        <div class="rounded-lg border border-gray-200 dark:border-gray-700 shadow-md">
            <table class="min-w-full w-full bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
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
                        <th
                            class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-[120px]">
                            Tanggal
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
                    @foreach ($wfhs as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                            <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $loop->iteration + ($wfhs->currentPage() - 1) * $wfhs->perPage() }}
                            </td>
                            <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $item->id_wfh }}
                            </td>
                            <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $item->pegawai?->nama_pegawai ?? 'Pegawai Tidak Ditemukan' }}
                            </td>
                            <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
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
                            <td class="px-3 py-2 text-center relative text-sm">
                                <!-- Dropdown Aksi -->
                                <button type="button" class="focus:outline-none"
                                    onclick="toggleDropdown({{ $item->id_wfh }})">
                                    <i class="fa-solid fa-ellipsis text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-lg"></i>
                                </button>
                                <div id="wfh-dropdown-{{ $item->id_wfh }}"
                                    class="hidden absolute right-0 mt-2 w-48 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600 rounded-lg shadow-lg z-50 transform scale-95 transition-all duration-300 ease-in-out">
                                    <div class="py-1">
                                        <!-- Approve -->
                                        <form action="{{ route('admin.wfh.update', $item->id_wfh) }}" method="POST"
                                            class="block">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" name="status" value="approved"
                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-green-100 dark:hover:bg-green-600 hover:text-gray-900 dark:hover:text-white rounded-t-md"
                                                {{ $item->status !== 'pending' || ($item->updated_at && now()->diffInHours($item->updated_at) >= 1) ? 'disabled' : '' }}>
                                                <i class="fa-solid fa-check mr-2 text-green-500 dark:text-green-400"></i> Approve
                                            </button>
                                        </form>
                                        <!-- Reject -->
                                        <form action="{{ route('admin.wfh.update', $item->id_wfh) }}" method="POST"
                                            class="block">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" name="status" value="rejected"
                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-red-100 dark:hover:bg-red-600 hover:text-gray-900 dark:hover:text-white"
                                                {{ $item->status !== 'pending' || ($item->updated_at && now()->diffInHours($item->updated_at) >= 1) ? 'disabled' : '' }}>
                                                <i class="fa-solid fa-ban mr-2 text-red-500 dark:text-red-400"></i> Reject
                                            </button>
                                        </form>
                                    </div>
                                    <div class="py-1">
                                        <!-- Hapus -->
                                        <button type="button" onclick="confirmDelete('{{ $item->id_wfh }}')"
                                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-red-100 dark:hover:bg-red-700 hover:text-gray-900 dark:hover:text-white rounded-b-md">
                                            <i class="fa-solid fa-trash mr-2 text-red-500 dark:text-red-400"></i> Hapus
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
    <div class="mt-6 px-6">
        @php
            $hasWfhToday = false;
        @endphp
        
        @foreach ($wfhs as $item)
            @if ($item->status == 'approved' && 
                 now()->toDateString() == \Carbon\Carbon::parse($item->tanggal)->toDateString() &&
                 $item->id_pegawai == Auth::user()->id_pegawai)
                @php
                    $hasWfhToday = true;
                @endphp
                <div class="mb-4 p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700" id="wfh-item-{{ $item->id_wfh }}">
                    <p class="text-gray-900 dark:text-gray-100 text-sm mb-2">
                        Pegawai: {{ $item->pegawai?->nama_pegawai ?? 'Pegawai Tidak Ditemukan' }} -
                        Tanggal: {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                    </p>

                    <!-- Tombol Absen Masuk (AJAX) -->
                    @if (!$item->absen_harian || !$item->absen_harian->jam_masuk)
                        <button type="button"
                            id="btn-absen-masuk-{{ $item->id_wfh }}"
                            class="ml-2 px-3 py-1 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white text-xs rounded-md transition duration-200"
                            data-id="{{ $item->id_wfh }}">
                            Hadir
                        </button>
                    @endif

                    <!-- Tombol Absen Pulang (AJAX) -->
                    @if ($item->absen_harian && $item->absen_harian->jam_masuk && !$item->absen_harian->jam_pulang)
                        <button type="button"
                            id="btn-absen-pulang-{{ $item->id_wfh }}"
                            class="ml-2 px-3 py-1 bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 text-white text-xs rounded-md transition duration-200"
                            data-id="{{ $item->id_wfh }}">
                            Pulang
                        </button>
                    @endif

                    <!-- Tombol Catatan (muncul setelah absen masuk) -->
                    @if ($item->absen_harian && $item->absen_harian->jam_masuk && !$item->absen_harian->jam_pulang)
                        @if ($item->absen_harian->catatan)
                            <span class="ml-2 px-3 py-1 bg-green-600 dark:bg-green-700 text-white text-xs rounded-md">
                                ✓ Catatan Terisi
                            </span>
                        @else
                            <a href="{{ route('admin.data_absen.catatan') }}?wfh_id={{ $item->id_wfh }}"
                                class="ml-2 px-3 py-1 bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white text-xs rounded-md inline-block transition duration-200">
                                ⚠ Catatan
                            </a>
                        @endif
                    @endif

                    <!-- Status Absen Selesai -->
                    @if ($item->absen_harian && $item->absen_harian->jam_masuk && $item->absen_harian->jam_pulang)
                        <span class="ml-2 px-3 py-1 bg-green-600 dark:bg-green-700 text-white text-xs rounded-md">
                            Absen Selesai
                        </span>
                    @endif
                </div>
            @endif
        @endforeach

        @if (!$hasWfhToday)
            <div class="text-center py-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <p class="text-gray-700 dark:text-gray-300 text-sm">
                    Tidak ada pengajuan WFH yang disetujui untuk hari ini.
                </p>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi untuk bind ulang event pada tombol absen pulang setelah DOM diupdate
            function bindAbsenPulangButton(wfhId) {
                const pulangBtn = document.getElementById(`btn-absen-pulang-${wfhId}`);
                if (pulangBtn) {
                    pulangBtn.addEventListener('click', function() {
                        // AJAX absen pulang
                        fetch(`{{ route('admin.wfh.absen.pulang', ':id') }}`.replace(':id', wfhId), {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                },
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: data.message,
                                        background: '#1f2937',
                                        color: '#f9fafb'
                                    });
                                    const wfhItem = document.getElementById(`wfh-item-${wfhId}`);
                                    wfhItem.innerHTML = `
                                        <p class="text-gray-900 dark:text-gray-100 text-sm mb-2">
                                            Pegawai: ${data.pegawai_nama} - Tanggal: ${data.tanggal}
                                        </p>
                                        <span class="ml-2 px-3 py-1 bg-green-600 dark:bg-green-700 text-white text-xs rounded-md">
                                            Absen Selesai
                                        </span>
                                    `;
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: data.message,
                                        background: '#1f2937',
                                        color: '#f9fafb'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan pada server. Silakan coba lagi.',
                                    background: '#1f2937',
                                    color: '#f9fafb'
                                });
                            });
                    });
                }
            }

            // Tangani tombol absen masuk (AJAX)
            document.querySelectorAll('[id^="btn-absen-masuk-"]').forEach(button => {
                button.addEventListener('click', function() {
                    const wfhId = this.getAttribute('data-id');
                    fetch(`{{ route('admin.wfh.absen.masuk', ':id') }}`.replace(':id', wfhId), {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    background: '#1f2937',
                                    color: '#f9fafb'
                                });
                                // Update tampilan: ganti tombol hadir menjadi tombol pulang dan catatan
                                const wfhItem = document.getElementById(`wfh-item-${wfhId}`);
                                wfhItem.innerHTML = `
                                    <p class="text-gray-900 dark:text-gray-100 text-sm mb-2">
                                        Pegawai: ${data.pegawai_nama} - Tanggal: ${data.tanggal}
                                    </p>
                                    <button type="button"
                                        id="btn-absen-pulang-${wfhId}"
                                        class="ml-2 px-3 py-1 bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 text-white text-xs rounded-md transition duration-200"
                                        data-id="${wfhId}">
                                        Pulang
                                    </button>
                                    <a href="{{ route('admin.data_absen.catatan') }}?wfh_id=${wfhId}"
                                        class="ml-2 px-3 py-1 bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white text-xs rounded-md inline-block transition duration-200">
                                        ⚠ Catatan
                                    </a>
                                `;
                                // Bind event ke tombol pulang yang baru
                                bindAbsenPulangButton(wfhId);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: data.message,
                                    background: '#1f2937',
                                    color: '#f9fafb'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan pada server. Silakan coba lagi.',
                                background: '#1f2937',
                                color: '#f9fafb'
                            });
                        });
                });
            });

            // Tangani tombol absen pulang (AJAX)
            document.querySelectorAll('[id^="btn-absen-pulang-"]').forEach(button => {
                const wfhId = button.getAttribute('data-id');
                bindAbsenPulangButton(wfhId);
            });
        });
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
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                background: '#1f2937',
                color: '#f9fafb'
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