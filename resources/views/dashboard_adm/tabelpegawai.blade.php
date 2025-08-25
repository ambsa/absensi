<div class="col-span-1 p-2 flex flex-col bg-[var(--bg-color)] text-[var(--text-color)]">
    <h2 class="text-md md:text-md font-normal mb-4">Catatan Pegawai</h2>

    <form action="{{ route('admin.index') }}" method="GET" class="mb-4 flex flex-col gap-3 items-end">

        <!-- Search & Date (sebelahan) -->
        <div class="flex w-full gap-3">
            <div class="w-1/2 min-w-[150px] max-w-xs">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pegawai..."
                    class="w-full px-2 py-1 text-sm border border-[var(--border-color)] rounded-md bg-[var(--input-bg)] text-[var(--input-text)] placeholder-gray-400 focus:outline-none focus:border-[var(--button-primary)]">
            </div>
            <div class="w-1/2 min-w-[150px] max-w-xs">
                <input type="date" name="tanggal" id="tanggalInput" value="{{ request('tanggal') ?? '' }}"
                    class="w-full px-2 py-1 text-sm border border-[var(--border-color)] rounded-md bg-[var(--input-bg)] text-[var(--input-text)] placeholder-gray-400 focus:outline-none focus:border-[var(--button-primary)]">
            </div>
        </div>

        <!-- Tombol Filter & Reset -->
        <div class="flex gap-2 w-full justify-end">
            <button type="submit"
                class="px-3 py-1 sm:px-4 sm:py-2 bg-[var(--button-primary)] hover:bg-[var(--button-primary)]/80 text-white rounded-md transition duration-200 text-sm font-medium whitespace-nowrap">
                Filter
            </button>
            <a href="{{ route('admin.index') }}"
                class="px-3 py-1 sm:px-4 sm:py-2 bg-[var(--button-danger)] hover:bg-[var(--button-danger)]/80 text-white rounded-md transition duration-200 text-sm font-medium whitespace-nowrap">
                Reset
            </a>
        </div>
    </form>

    <!-- Tabel Catatan -->
    <div class="overflow-y-auto max-h-48 flex-1 border-t border-[var(--border-color)] scrollbar">
        <table class="min-w-full divide-y divide-[var(--border-color)]">
            <thead class="bg-[var(--table-header-bg)] sticky top-0 z-10">
                <tr>
                    <th scope="col"
                        class="px-4 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-[var(--text-color)] uppercase tracking-wider">
                        No
                    </th>
                    <th scope="col"
                        class="px-4 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-[var(--text-color)] uppercase tracking-wider">
                        Nama Pegawai
                    </th>
                    <th scope="col"
                        class="px-4 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-[var(--text-color)] uppercase tracking-wider">
                        Catatan
                    </th>
                    <th scope="col"
                        class="px-4 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-[var(--text-color)] uppercase tracking-wider">
                        Tanggal Masuk
                    </th>
                </tr>
            </thead>
            <tbody class="bg-[var(--table-row-bg)] divide-y divide-[var(--border-color)]">
                @forelse ($catatans as $index => $catatan)
                    <tr>
                        <td class="px-4 py-2 sm:py-4 whitespace-nowrap text-sm text-[var(--text-color)]">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-4 py-2 sm:py-4 whitespace-nowrap text-sm text-[var(--text-color)]">
                            {{ $catatan->pegawai->nama_pegawai ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-2 sm:px-6 sm:py-4 whitespace-nowrap text-sm text-gray-300">
                            {{ Str::limit($catatan->catatan, 50) ?? 'Tidak ada catatan' }}
                        </td>
                        <td class="px-4 py-2 sm:py-4 whitespace-nowrap text-sm text-[var(--text-color)]">
                            {{ \Carbon\Carbon::parse($catatan->jam_masuk)->format('d M Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-400">Tidak ada data catatan</td>
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
        background-color: var(--scrollbar-thumb);
        border-radius: 9999px;
        border: 2px solid transparent;
    }

    .scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
</style>
