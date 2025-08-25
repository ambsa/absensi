<div class="bg-gradient-to-br from-[var(--gradient-start)] via-[var(--gradient-middle)] to-[var(--gradient-end)] p-6 rounded-2xl shadow-lg border border-[var(--card-border)] text-[var(--card-text)]">
    <h3 class="text-xl font-bold mb-6 flex items-center gap-3">
        <span class="text-[var(--icon-color)] text-2xl">🏆</span>
        Karyawan Terbaik Bulan Ini
    </h3>
    @if ($karyawanTerbaik)
        <div class="flex items-start gap-6">
            <!-- Avatar -->
            <div class="w-16 h-16 bg-gradient-to-br from-[var(--avatar-bg)] to-blue-700 rounded-full flex items-center justify-center text-3xl font-extrabold shadow-lg border-4 border-[var(--card-border)] flex-shrink-0">
                {{ strtoupper(substr($karyawanTerbaik['nama'], 0, 1)) }}
            </div>
            <!-- Detail -->
            <div class="flex-1 min-w-0">
                <p class="font-bold text-lg mb-3">{{ $karyawanTerbaik['nama'] }}</p>
                
                <!-- Stats Row -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <div class="flex items-center gap-2 text-[var(--icon-color)]">
                        <i class="fa-solid fa-calendar-check text-sm"></i>
                        <div>
                            <span class="text-xs text-gray-400">Hadir</span>
                            <div class="font-bold">{{ $karyawanTerbaik['kehadiran'] }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-green-300">
                        <i class="fa-solid fa-clock text-sm"></i>
                        <div>
                            <span class="text-xs text-gray-400">Tepat Waktu</span>
                            <div class="font-bold">{{ $karyawanTerbaik['tepat_waktu'] }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-yellow-300">
                        <i class="fa-solid fa-stopwatch text-sm"></i>
                        <div>
                            <span class="text-xs text-gray-400">Terlambat</span>
                            <div class="font-bold">{{ $karyawanTerbaik['terlambat'] }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-purple-300">
                        <i class="fa-solid fa-pen text-sm"></i>
                        <div>
                            <span class="text-xs text-gray-400">Catatan</span>
                            <div class="font-bold">{{ $karyawanTerbaik['catatan'] }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Score and Category Row -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 mb-3">
                    <div class="flex items-center gap-2 text-[var(--score-color)] font-bold">
                        <i class="fa-solid fa-star text-lg"></i>
                        <span>Skor: {{ $karyawanTerbaik['score'] }}</span>
                    </div>
                    <span class="text-sm font-semibold px-4 py-2 rounded-full
                        @if(str_contains($karyawanTerbaik['kategori'], 'Sangat')) bg-green-700 text-white
                        @elseif(str_contains($karyawanTerbaik['kategori'], 'Disiplin')) bg-blue-700 text-white
                        @elseif(str_contains($karyawanTerbaik['kategori'], 'Cukup')) bg-yellow-600 text-white
                        @else bg-red-700 text-white @endif">
                        {{ $karyawanTerbaik['kategori'] }}
                    </span>
                </div>
                
                <!-- Evaluation Note -->
                @if($karyawanTerbaik['catatan_evaluasi'])
                    <div class="bg-[var(--warning-bg)] border border-[var(--card-border)] rounded-lg p-3">
                        <p class="text-sm text-[var(--warning-text)] italic flex items-start gap-2">
                            <i class="fa-solid fa-circle-exclamation text-sm mt-0.5 flex-shrink-0"></i>
                            <span class="break-words">{{ $karyawanTerbaik['catatan_evaluasi'] }}</span>
                        </p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="flex items-center justify-center py-8">
            <div class="text-center">
                <i class="fa-solid fa-trophy text-4xl text-gray-600 mb-3"></i>
                <p class="text-gray-400">Belum ada data karyawan bulan ini.</p>
            </div>
        </div>
    @endif
</div>
