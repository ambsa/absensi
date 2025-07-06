<div class="p-4 shadow-md relative h-64 md:h-96 flex flex-col justify-center">
    <!-- Judul Statistik -->
    <h2 class="text-md md:text-lg font-normal text-gray-200 mb-4">Statistik Kehadiran</h2>
    <!-- Form Pilih Bulan -->
    <form action="{{ route('admin.index') }}" method="GET" class="flex items-center space-x-4 mb-6">
        <input type="month" name="bulan" value="{{ $bulan ?? now()->format('Y-m') }}" id="bulan"
            class="block w-full md:w-auto px-4 py-1 border border-gray-700 bg-gray-800 text-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
        <button type="submit"
            class="px-4 py-1 bg-blue-700 hover:bg-blue-800 text-white font-medium rounded-md transition duration-300">
            Tampilkan
        </button>
    </form>


    <!-- Canvas untuk Diagram Garis -->
    <div class="w-full h-full flex items-center border border-gray-700 rounded-lg justify-center">
        <canvas id="ketepatanChart" class="w-full h-full"></canvas>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = {!! json_encode($labels) !!};
        const dataTepatWaktu = {!! json_encode($dataTepatWaktu) !!};
        const dataTerlambat = {!! json_encode($dataTerlambat) !!};

        if (labels.length === 0 || dataTepatWaktu.length === 0 || dataTerlambat.length === 0) {
            document.getElementById('ketepatanChart').parentElement.innerHTML = `
<p class="text-center text-white py-4">Tidak ada data absensi untuk ditampilkan.</p>`;
        } else {
            const ctx = document.getElementById('ketepatanChart').getContext('2d');
            const ketepatanChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels.map(date => new Date(date).toLocaleDateString('id-ID', {
                        weekday: 'long'
                    })),
                    datasets: [{
                        label: 'Tepat Waktu',
                        data: dataTepatWaktu,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 2,
                        fill: true
                    }, {
                        label: 'Terlambat',
                        data: dataTerlambat,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderWidth: 2,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#ffffff'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: '#ffffff'
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            }
                        },
                        y: {
                            ticks: {
                                color: '#ffffff',
                                stepSize: 1,
                                beginAtZero: true
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            }
                        }
                    }
                }
            });

            // Trigger resize chart saat layar berubah
            window.addEventListener('resize', () => {
                if (ketepatanChart) ketepatanChart.resize();
            });
        }
    </script>

</div>
