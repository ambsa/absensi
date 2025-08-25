@extends('layouts.main')

@section('title', 'Pengajuan WFH Saya')

@section('content')
<div class="container mx-auto p-6 bg-[#161A23]">
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center space-x-2">
            <!-- Form Filter -->
            <form action="{{ route('user.wfh.index') }}" method="GET" class="flex items-center space-x-2">
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
        </div>
    </div>

    <div class="mb-4 flex justify-end">
        <!-- Tombol Ajukan WFH -->
        <a href="{{ route('user.wfh.create') }}"
            class="bg-indigo-800 text-white px-3 py-1 rounded hover:bg-blue-900 flex items-center">
            <i class="fa-solid fa-plus mr-2"></i>Ajukan WFH
        </a>
    </div>

    <!-- Tabel Pengajuan WFH -->
    <div class="shadow-md">
        <table class="w-full bg-[#1E293B] divide-y divide-gray-700 rounded-lg overflow-hidden">
            <thead class="bg-gray-800 sticky top-0 z-10">
                <tr>
                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider w-[50px]">
                        No
                    </th>
                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider w-[100px]">
                        Tanggal
                    </th>
                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider w-[80px]">
                        Status
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse ($wfhs as $wfh)
                    <tr class="hover:bg-gray-900">
                        <td class="px-3 py-2 text-center whitespace-nowrap">
                            {{ $loop->iteration + ($wfhs->currentPage() - 1) * $wfhs->perPage() }}
                        </td>
                        <td class="px-3 py-2 text-center whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($wfh->tanggal)->format('d-m-Y') }}
                        </td>
                        <td class="px-3 py-2 text-center whitespace-nowrap">
                            @if ($wfh->status === 'pending')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-500 text-white">
                                    Pending
                                </span>
                            @elseif ($wfh->status === 'approved')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-500 text-white">
                                    Approved
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-500 text-white">
                                    Rejected
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-3 py-2 text-center text-gray-300">
                            Tidak ada pengajuan WFH.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $wfhs->appends(request()->except('page'))->links('pagination::tailwind') }}
    </div>

    <!-- Tombol Absen di Luar Tabel -->
    <div class="mt-6">
        @php
            $hasWfhToday = false;
        @endphp
        
        @foreach ($wfhs as $item)
            @if ($item->status == 'approved' && 
                 now()->toDateString() == \Carbon\Carbon::parse($item->tanggal)->toDateString())
                @php
                    $hasWfhToday = true;
                @endphp
                <div class="mb-4 p-4" id="wfh-item-{{ $item->id_wfh }}">
                    <p class="text-gray-300 text-sm mb-2">
                        Tanggal: {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                    </p>

                    <!-- Tombol Absen Masuk (AJAX) -->
                    @if (!$item->absen_harian || !$item->absen_harian->jam_masuk)
                        <button type="button"
                            id="btn-absen-masuk-{{ $item->id_wfh }}"
                            class="ml-2 px-3 py-1 bg-blue-500 text-white text-xs rounded-md hover:bg-blue-600"
                            data-id="{{ $item->id_wfh }}">
                            Hadir
                        </button>
                    @endif

                    <!-- Tombol Absen Pulang (AJAX) -->
                    @if ($item->absen_harian && $item->absen_harian->jam_masuk && !$item->absen_harian->jam_pulang)
                        <button type="button"
                            id="btn-absen-pulang-{{ $item->id_wfh }}"
                            class="ml-2 px-3 py-1 bg-red-500 text-white text-xs rounded-md hover:bg-red-600"
                            data-id="{{ $item->id_wfh }}">
                            Pulang
                        </button>
                    @endif

                    <!-- Tombol Catatan (muncul setelah absen masuk) -->
                    @if ($item->absen_harian && $item->absen_harian->jam_masuk && !$item->absen_harian->jam_pulang)
                        @if ($item->absen_harian->catatan)
                            <span class="ml-2 px-3 py-1 bg-green-500 text-white text-xs rounded-md">
                                ✓ Catatan Terisi
                            </span>
                        @else
                            <a href="{{ route('user.catatan.catatanuser') }}?wfh_id={{ $item->id_wfh }}"
                                class="ml-2 px-3 py-1 bg-yellow-500 text-white text-xs rounded-md hover:bg-yellow-600 inline-block">
                                ⚠ Catatan
                            </a>
                        @endif
                    @endif

                    <!-- Status Absen Selesai -->
                    @if ($item->absen_harian && $item->absen_harian->jam_masuk && $item->absen_harian->jam_pulang)
                        <span class="ml-2 px-3 py-1 bg-green-500 text-white text-xs rounded-md">
                            Absen Selesai
                        </span>
                    @endif
                </div>
            @endif
        @endforeach

        @if (!$hasWfhToday)
            <div class="text-center py-4">
                <p class="text-gray-400 text-sm">
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
                        fetch(`{{ route('user.wfh.absen.pulang', ':id') }}`.replace(':id', wfhId), {
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
                                        background: '#161A23',
                                        color: '#ffffff'
                                    });
                                    const wfhItem = document.getElementById(`wfh-item-${wfhId}`);
                                    wfhItem.innerHTML = `
                                        <p class="text-gray-300 text-sm mb-2">
                                            Tanggal: ${data.tanggal}
                                        </p>
                                        <span class="ml-2 px-3 py-1 bg-green-500 text-white text-xs rounded-md">
                                            Absen Selesai
                                        </span>
                                    `;
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: data.message,
                                        background: '#161A23',
                                        color: '#ffffff'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan pada server. Silakan coba lagi.',
                                    background: '#161A23',
                                    color: '#ffffff'
                                });
                            });
                    });
                }
            }

            // Tangani tombol absen masuk (AJAX)
            document.querySelectorAll('[id^="btn-absen-masuk-"]').forEach(button => {
                button.addEventListener('click', function() {
                    const wfhId = this.getAttribute('data-id');
                    fetch(`{{ route('user.wfh.absen.masuk', ':id') }}`.replace(':id', wfhId), {
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
                                    background: '#161A23',
                                    color: '#ffffff'
                                });
                                // Update tampilan: ganti tombol hadir menjadi tombol pulang dan catatan
                                const wfhItem = document.getElementById(`wfh-item-${wfhId}`);
                                wfhItem.innerHTML = `
                                    <p class="text-gray-300 text-sm mb-2">
                                        Tanggal: ${data.tanggal}
                                    </p>
                                    <button type="button"
                                        id="btn-absen-pulang-${wfhId}"
                                        class="ml-2 px-3 py-1 bg-red-500 text-white text-xs rounded-md hover:bg-red-600"
                                        data-id="${wfhId}">
                                        Pulang
                                    </button>
                                    <a href="{{ route('user.catatan.catatanuser') }}?wfh_id=${wfhId}"
                                        class="ml-2 px-3 py-1 bg-yellow-500 text-white text-xs rounded-md hover:bg-yellow-600 inline-block">
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
                                    background: '#161A23',
                                    color: '#ffffff'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan pada server. Silakan coba lagi.',
                                background: '#161A23',
                                color: '#ffffff'
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

    @if (session('success'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 1500,
                background: '#161A23',
                color: '#ffffff',
                toast: true,
                width: '350px',
                padding: '1.5rem',
                customClass: {
                    popup: 'swal2-noanimation swal2-padding',
                    title: 'swal2-title-large',
                },
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: true,    
                confirmButtonText: 'Tutup', 
                confirmButtonColor: '#dc3545',
                background: '#161A23',
                color: '#ffffff',
                width: '400px',
                padding: '2rem',
                customClass: {
                    popup: 'swal2-noanimation swal2-padding', 
                    title: 'swal2-title-large',
                },
            });
        </script>
    @endif
</div>
@endsection
