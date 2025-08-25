<!-- Bagian Data Absen User -->
<div class="mb-8 px-4">
    <h1 class="text-md md:text-xl font-semibold text-gray-400 mb-4">Data Absen Saya</h1>
    
    <!-- Dropdown untuk memilih jumlah data -->
    <div class="mb-4 flex justify-between items-center">
        <div>
            <label for="per_page" class="text-gray-400 text-sm">Tampilkan:</label>
            <select id="per_page" name="per_page"
                class="ml-2 px-2 py-1 bg-[#1E293B] text-white border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                onchange="location.href='?per_page=' + this.value">
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
            </select>
        </div>
    </div>

    <!-- Tabel Data Absen User -->
    <div class="overflow-x-auto rounded-lg border border-gray-700 shadow-md">
        <table class="min-w-full w-full bg-[#161A23] divide-y divide-gray-700">
            <thead class="bg-gray-800">
                <tr>
                    <th class="py-3 px-2 md:px-4 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">
                        No.
                    </th>
                    <th class="py-3 px-2 md:px-4 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th class="py-3 px-2 md:px-4 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">
                        Jam Masuk
                    </th>
                    <th class="py-3 px-2 md:px-4 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">
                        Jam Pulang
                    </th>
                    <th class="py-3 px-2 md:px-4 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">
                        Catatan
                    </th>
                    <th class="py-3 px-2 md:px-4 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">
                        File Catatan
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse ($userAbsen as $absen)
                    <tr class="hover:bg-gray-700 transition duration-200 ease-in-out">
                        <td class="py-3 px-2 md:px-4 text-center whitespace-nowrap text-sm text-gray-300">
                            {{ $loop->iteration + ($userAbsen->currentPage() - 1) * $userAbsen->perPage() }}
                        </td>
                        <td class="py-3 px-2 md:px-4 text-center whitespace-nowrap text-sm text-gray-300">
                            {{ \Carbon\Carbon::parse($absen->created_at)->format('d M Y') }}
                        </td>
                        <td class="py-3 px-2 md:px-4 text-center whitespace-nowrap text-sm text-gray-300">
                            {{ $absen->jam_masuk ? \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i:s') : '-' }}
                        </td>
                        <td class="py-3 px-2 md:px-4 text-center whitespace-nowrap text-sm text-gray-300">
                            {{ $absen->jam_pulang ? \Carbon\Carbon::parse($absen->jam_pulang)->format('H:i:s') : '-' }}
                        </td>
                        <td class="py-3 px-2 md:px-4 text-center whitespace-nowrap text-sm text-gray-300">
                            {{ $absen->catatan ? Str::limit($absen->catatan, 30) : '-' }}
                        </td>
                        <td class="py-3 px-2 md:px-4 text-center whitespace-nowrap text-sm text-gray-300">
                            @if ($absen->file_catatan)
                                <a href="{{ asset('uploads/catatan/' . $absen->file_catatan) }}" target="_blank"
                                    class="text-blue-400 hover:text-blue-300 transition duration-200 ease-in-out">
                                    <i class="fa-solid fa-file mr-1"></i>Lihat
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 px-2 md:px-4 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fa-solid fa-calendar-check text-4xl text-gray-600 mb-4"></i>
                                <p class="text-gray-400 text-lg font-medium">Belum ada data absen</p>
                                <p class="text-gray-500 text-sm mt-1">Data absen Anda akan muncul di sini</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($userAbsen->hasPages())
        <div class="flex items-center justify-between px-4 py-3 sm:px-6 flex-col sm:flex-row">
            <div class="hidden sm:block sm:flex-1 sm:items-center sm:justify-between w-full mb-4 sm:mb-0">
                <div class="text-sm text-gray-400 mb-2 sm:mb-0">
                    Menampilkan {{ $userAbsen->firstItem() ?? 0 }} sampai {{ $userAbsen->lastItem() ?? 0 }} dari {{ $userAbsen->total() }} data
                </div>
                <div>
                    {{ $userAbsen->appends(['per_page' => $perPage])->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    @endif
</div> 