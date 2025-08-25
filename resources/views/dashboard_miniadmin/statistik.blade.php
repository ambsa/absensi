<div class="py-5 mt-5 relative h-64 md:h-96 flex flex-col justify-center bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
    <!-- Judul Statistik -->
    <h2 class="text-md md:text-lg font-semibold mb-4">Statistik Kehadiran</h2>

    <!-- Form Pilih Bulan -->
    <form action="{{ route('admin.index') }}" method="GET" class="flex flex-wrap items-center gap-4 mb-6">
        <!-- Input Bulan -->
        <input type="month" name="bulan" value="{{ $bulan ?? now()->format('Y-m') }}" id="bulan"
            class="block w-full md:w-auto px-2 py-1 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 dark:focus:ring-blue-400">

        <!-- Input Tanggal -->
        <input type="date" name="tanggal" value="{{ request('tanggal') ?? '' }}" id="tanggal"
            class="block w-full md:w-auto px-2 py-1 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 dark:focus:ring-blue-400">

        <!-- Tombol Filter -->
        <button type="submit"
            class="px-4 py-1 bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium rounded-md transition duration-300">
            Filter
        </button>

        <!-- Tombol Reset -->
        <a href="{{ route('admin.index') }}"
            class="px-3 py-1 sm:px-4 sm:py-2 bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white rounded-md transition duration-200 text-sm font-medium whitespace-nowrap">
            Reset
        </a>
    </form>

    <!-- Canvas untuk Diagram Garis -->
    <div class="w-full h-full flex items-center border border-gray-300 dark:border-gray-600 rounded-lg justify-center">
        <canvas id="ketepatanChart" class="w-full h-full"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = {!! json_encode($labels) !!};
        const dataTepatWaktu = {!! json_encode($dataTepatWaktu) !!};
        const dataTerlambat = {!! json_encode($dataTerlambat) !!};

        if (labels.length === 0 || dataTepatWaktu.length === 0 || dataTerlambat.length === 0) {
            document.getElementById('ketepatanChart').parentElement.innerHTML = `
                <p class="text-center text-gray-900 dark:text-gray-100 py-4">Tidak ada data absensi untuk ditampilkan.</p>`;
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
                                color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
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
                                color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
                            },
                            grid: {
                                color: document.documentElement.classList.contains('dark') ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        y: {
                            ticks: {
                                color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b',
                                stepSize: 1,
                                beginAtZero: true
                            },
                            grid: {
                                color: document.documentElement.classList.contains('dark') ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
                            }
                        }
                    }
                }
            });

            // Update chart colors when theme changes
            function updateChartColors() {
                const isDark = document.documentElement.classList.contains('dark');
                const textColor = isDark ? '#f1f5f9' : '#1e293b';
                const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
                
                ketepatanChart.options.plugins.legend.labels.color = textColor;
                ketepatanChart.options.scales.x.ticks.color = textColor;
                ketepatanChart.options.scales.y.ticks.color = textColor;
                ketepatanChart.options.scales.x.grid.color = gridColor;
                ketepatanChart.options.scales.y.grid.color = gridColor;
                
                ketepatanChart.update();
            }

            // Listen for theme changes
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        updateChartColors();
                    }
                });
            });

            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });

            // Trigger resize chart saat layar berubah
            window.addEventListener('resize', () => {
                if (ketepatanChart) ketepatanChart.resize();
            });
        }
    </script>
</div>