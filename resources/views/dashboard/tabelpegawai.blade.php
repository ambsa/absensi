<div class="col-span-1 p-2 flex flex-col">
    <h2 class="text-md md:text-md font-normal text-gray-200 mb-4">Catatan Pegawai</h2>

    <form action="{{ route('admin.index') }}" method="GET"
        class="mb-4 flex flex-wrap items-center justify-end gap-2 sm:gap-3">

        <!-- Search -->
        <div class="flex-grow min-w-[150px] max-w-xs">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama pegawai..."
                class="w-full px-2 py-1 text-sm border border-gray-500 rounded-md bg-[#2A303C] text-white placeholder-gray-400 focus:outline-none focus:border-blue-500">
        </div>

        <!-- Datepicker -->
        <div class="flex-grow min-w-[150px] relative">
            <input type="text" id="datepicker" placeholder="Pilih Tanggal"
                class="w-full px-2 py-1 text-sm border border-gray-500 rounded-md bg-[#2A303C] text-white placeholder-gray-400 focus:outline-none focus:border-blue-500">
            <input type="hidden" name="tanggal" id="tanggalInput"
                value="{{ request('tanggal') ?? now()->format('Y-m-d') }}">
        </div>

        <!-- Tombol Filter -->
        <button type="submit"
            class="px-3 py-1 sm:px-4 sm:py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200 text-sm font-medium whitespace-nowrap order-last">
            Filter
        </button>

        <!-- Tombol Reset -->
        <a href="{{ route('admin.index') }}"
            class="px-3 py-1 sm:px-4 sm:py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition duration-200 text-sm font-medium whitespace-nowrap order-last">
            Reset
        </a>
    </form>

    <!-- Inisialisasi Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#datepicker", {
            dateFormat: "Y-m-d",
            defaultDate: "{{ request('tanggal') ?? now()->format('Y-m-d') }}",
            onChange: function(selectedDates, dateStr) {
                document.getElementById('tanggalInput').value = dateStr;
            },
            theme: 'dark'
        });
    </script>

    <!-- Tabel Catatan -->
    <div class="overflow-y-auto max-h-48 flex-1 border-t border-gray-700 scrollbar">
        <table class="min-w-full divide-y divide-gray-700">
            <thead class="bg-gray-800 sticky top-0 z-10">
                <tr>
                    <th scope="col"
                        class="px-4 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        No
                    </th>
                    <th scope="col"
                        class="px-4 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Nama Pegawai
                    </th>
                    <th scope="col"
                        class="px-4 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Catatan
                    </th>
                    <th scope="col"
                        class="px-4 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Tanggal Masuk
                    </th>
                </tr>
            </thead>
            <tbody class="bg-gray-900 divide-y divide-gray-700">
                @forelse ($catatans as $index => $catatan)
                    <tr>
                        <td class="px-4 py-2 sm:py-4 whitespace-nowrap text-sm text-white">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-4 py-2 sm:py-4 whitespace-nowrap text-sm text-white">
                            {{ $catatan->pegawai->nama_pegawai ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-2 sm:px-6 sm:py-4 whitespace-nowrap text-sm text-gray-300">
                            {{ Str::limit($catatan->catatan, 50) ?? 'Tidak ada catatan' }}
                        </td>
                        <td class="px-4 py-2 sm:py-4 whitespace-nowrap text-sm text-white">
                            {{ \Carbon\Carbon::parse($catatan->jam_masuk)->format('d M Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-gray-400">Tidak ada data catatan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<style>
    .scrollbar::-webkit-scrollbar {
        width: 6px;
    }


    .scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.3);
        border-radius: 9999px;
        border: 2px solid transparent;
    }

    .scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
</style>
