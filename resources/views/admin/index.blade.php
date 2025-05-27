@extends('layouts.main')

@section('title', 'Admin Dashboard')

@section('content')



    {{-- <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>    --}}


    <div class="container mx-auto bg-[#161A23]">
        <!-- Grid Layout Utama -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kolom Kiri: Kartu Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Total Pengajuan Cuti -->
                <div class="bg-[#1E293B] p-5 rounded-xl shadow-md text-center space-y-4">
                    <div class="flex justify-left">
                        <i class="fa-solid fa-calendar-days text-4xl text-gray-500"></i>
                    </div>
                    <div class="text-left">
                        <h5 class="text-4xl font-bold text-white mb-5">{{ $totalCuti }}</h5>
                        <h3 class="text-md font-semibold text-gray-400">Total Pengajuan Cuti</h3>
                    </div>

                </div>

                <!-- Pengajuan Cuti Pending -->
                <div class="bg-[#1E293B] p-5 rounded-xl shadow-md text-center space-y-4">
                    <div class="flex justify-left">
                        <i class="fa-solid fa-clock text-4xl text-yellow-500"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-4xl font-bold text-white mb-5">{{ $cutiPending }}</p>
                        <h3 class="text-md font-semibold text-yellow-500">Pending</h3>
                    </div>

                </div>

                <!-- Pengajuan Cuti Approved -->
                <div class="bg-[#1E293B] p-5 rounded-lg shadow-md text-center space-y-4">
                    <div class="flex justify-left">
                        <i class="fa-solid fa-check-circle text-4xl text-green-500"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-4xl font-bold text-white mb-5">{{ $cutiApproved }}</p>
                        <h3 class="text-md font-semibold text-green-500">Approved</h3>
                    </div>

                </div>

                <!-- Pengajuan Cuti Rejected -->
                <div class="bg-[#1E293B] p-5 rounded-lg shadow-md text-center space-y-4">
                    <div class="flex justify-left">
                        <i class="fa-solid fa-times-circle text-4xl text-red-500"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-4xl font-bold text-white mb-5">{{ $cutiRejected }}</p>
                        <h3 class="text-md font-semibold text-red-500">Rejected</h3>
                    </div>

                </div>
            </div>

            <!-- Kolom Kanan: Form Absensi -->
            <div class="bg-[#161A23] border border-gray-700 shadow-lg rounded-lg text-white p-6">
                <!-- Judul Form -->
                <h2 class="text-xl font-bold mb-6 text-center text-gray-200">Form Absensi</h2>

                <!-- Form Input Catatan dan File -->
                <form action="{{ route('admin.data_absen.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    <!-- Input Catatan -->
                    <div>
                        <label for="catatan" class="block text-sm font-medium text-gray-200">Catatan Harian</label>
                        <textarea id="catatan" name="catatan" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-600 bg-[#2A303C] text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm {{ session('catatanSudahDiisi') ? 'cursor-not-allowed' : '' }}"
                            placeholder="Tulis catatan harian Anda..." {{ session('catatanSudahDiisi') ? 'disabled' : '' }}></textarea>
                    </div>

                    <!-- Input File Catatan -->
                    <div>
                        <label for="file_catatan" class="block text-sm font-medium text-gray-200">Unggah File
                            Catatan</label>
                        <input type="file" id="file_catatan" name="file_catatan"
                            class="mt-1 block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600 {{ session('catatanSudahDiisi') ? 'cursor-not-allowed' : '' }}"
                            {{ session('catatanSudahDiisi') ? 'disabled' : '' }}>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="flex justify-center">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            {{ session('catatanSudahDiisi') ? 'disabled' : '' }}>
                            Simpan Catatan
                        </button>
                    </div>
                </form>

                <!-- SweetAlert -->
                <script>
                    // Cek jika ada pesan sukses
                    @if (session('success'))
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: '{{ session('success') }}',
                            showConfirmButton: false,
                            timer: 1500,
                            background: '#161A23',
                            color: '#ffffff',
                            toast: true,
                            width: '250px',
                            padding: '1rem',
                            customClass: {
                                popup: 'swal2-noanimation',
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
                            background: '#161A23',
                            color: '#ffffff',
                            toast: true,
                            width: '250px',
                            padding: '1rem',
                            customClass: {
                                popup: 'swal2-noanimation',
                            },
                        });
                    @endif
                </script>
            </div>
        </div>
    </div>

    <!-- diagram ketepatan waktu seluruh pegawai -->
    <div class="container mx-auto mt-8 bg-[#161A23] p-6 border border-gray-700 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-gray-200 mb-6">Statistik Ketepatan Waktu Pegawai</h2>

        <!-- Canvas untuk Diagram Garis -->
        <div>
            <canvas id="ketepatanChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js "></script>
    <script>
        // Data untuk Chart
        const labels = {!! json_encode($labels) !!};
        const dataTepatWaktu = {!! json_encode($dataTepatWaktu) !!};
        const dataTerlambat = {!! json_encode($dataTerlambat) !!};

        console.log('Labels:', labels);
        console.log('Tepat Waktu:', dataTepatWaktu);
        console.log('Terlambat:', dataTerlambat);

        // Periksa apakah ada data
        if (labels.length === 0 || dataTepatWaktu.length === 0 || dataTerlambat.length === 0) {
            document.getElementById('ketepatanChart').parentElement.innerHTML = `
            <p class="text-center text-white">Tidak ada data absensi untuk ditampilkan.</p>
        `;
        } else {
            // Konfigurasi Chart
            const ctx = document.getElementById('ketepatanChart').getContext('2d');
            const ketepatanChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels.map(date => new Date(date).toLocaleDateString('id-ID', {
                        weekday: 'long'
                    })), // Ubah label menjadi nama hari
                    datasets: [{
                            label: 'Tepat Waktu',
                            data: dataTepatWaktu,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderWidth: 2,
                            fill: true,
                        },
                        {
                            label: 'Terlambat',
                            data: dataTerlambat,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 2,
                            fill: true,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#ffffff', // Warna teks legenda
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += context.parsed.y;
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: '#ffffff', // Warna teks sumbu X
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)', // Warna grid sumbu X
                            }
                        },
                        y: {
                            ticks: {
                                color: '#ffffff', // Warna teks sumbu Y
                                stepSize: 1, // Pastikan hanya menampilkan angka bulat
                                beginAtZero: true,
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)', // Warna grid sumbu Y
                            }
                        }
                    }
                }
            });
        }
    </script>


    <!-- Container untuk Catatan Pegawai -->

    <div class="container mx-auto mt-8 bg-[#161A23] p-6 rounded-lg shadow-md border border-gray-700">
        <h2 class="text-2xl font-bold text-gray-200 mb-6">Catatan Pegawai</h2>

        <!-- Form Search dan Filter -->
        <form action="{{ route('admin.index') }}" method="GET" class="mb-4 flex space-x-4 justify-end">
            <!-- Search -->
            <div class="w-full md:w-1/3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pegawai..."
                    class="w-full px-4 py-2 border border-gray-500 rounded-md focus:outline-none text-white placeholder-gray-600 focus:border-blue-500">
            </div>

            <!-- Datepicker -->
            <div class="w-1/2 md:w-1/3 relative">
                <input type="text" id="datepicker" placeholder="Pilih Tanggal"
                    class="w-full px-4 py-2 border border-gray-500 rounded-md focus:outline-none text-white placeholder-gray-600 focus:border-blue-500">
                <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">
            </div>

            <!-- Tombol Submit -->
            <button type="submit"
                class="ml-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Filter</button>
        </form>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $catatans->appends(request()->query())->links() }}
        </div>


        <!-- Inisialisasi Flatpickr -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css ">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css ">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr "></script>
        <script>
            flatpickr("#datepicker", {
                dateFormat: "Y-m-d",
                defaultDate: "{{ request('tanggal') ?? now()->format('Y-m-d') }}",
                onChange: function(selectedDates, dateStr, instance) {
                    console.log(dateStr); // Debugging: Pastikan nilai tanggal terkirim
                    document.querySelector('[name="tanggal"]').value = dateStr;
                },
                theme: 'dark' // Aktifkan tema gelap
            });
        </script>

        <!-- Tabel Catatan -->
        <div class="overflow-x-auto rounded-lg">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-800">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            No
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Nama Pegawai
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Catatan
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            File Catatan
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Tanggal Pembuatan
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-gray-900 divide-y divide-gray-700">
                    @foreach ($catatans as $index => $catatan)
                        <tr>
                            <td class="px-6 py-4 text-white whitespace-nowrap">
                                {{ $catatans->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 text-white whitespace-nowrap">
                                {{ $catatan->pegawai->nama_pegawai ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-white whitespace-nowrap">
                                {{ $catatan->catatan ?? 'Tidak ada catatan' }}
                            </td>
                            <td class="px-6 py-4 text-white whitespace-nowrap">
                                @if ($catatan->file_catatan)
                                    <a href="{{ asset('storage/' . $catatan->file_catatan) }}" target="_blank"
                                        class="text-blue-400 hover:text-blue-600">
                                        Lihat File
                                    </a>
                                @else
                                    Tidak ada file
                                @endif
                            </td>
                            <td class="px-6 py-4 text-white whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($catatan->jam_masuk)->format('d M Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $catatans->appends(request()->query())->links() }}
        </div>
    </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2 @11"></script>

    <!-- Inisialisasi Flatpickr -->


@endsection
