<div class="bg-[#1E293B] p-6 rounded-lg shadow-md border border-gray-700 text-white">
    <h3 class="text-lg font-semibold mb-4">🏆 Karyawan Terbaik Bulan Ini</h3>
    
    @if ($karyawanTerbaik)
        <div class="flex items-center space-x-4">
            <!-- Avatar -->
            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-xl font-bold">
                {{ substr($karyawanTerbaik['nama'], 0, 1) }}
            </div>
            <!-- Detail -->
            <div>
                <p class="font-bold">{{ $karyawanTerbaik['nama'] }}</p>
                <p class="text-sm text-gray-400">Kehadiran: {{ $karyawanTerbaik['kehadiran'] }}/{{ now()->day }}</p>
                <p class="text-sm text-gray-400">Rata-rata Masuk: {{ $karyawanTerbaik['rataWaktuMasuk'] }}</p>
                <p class="text-sm text-gray-400">Catatan: {{ $karyawanTerbaik['catatanLengkap'] }}</p>
            </div>
        </div>
    @else
        <p class="text-sm text-gray-400">Belum ada data karyawan bulan ini.</p>
    @endif
</div> 
