@extends('layouts.main')


@section('title', 'Cuti')


@section('content')
    <div class="mb-4 flex justify-end">
        <a href="{{ route('admin.cuti.create') }}"
            class="bg-indigo-800 text-white px-3 py-1 rounded hover:bg-blue-900 flex items-center">
            <i class="fa-solid fa-plus mr-2"></i>Tambah cuti
        </a>
    </div>

    <div class="container mx-auto p-6 bg-[#161A23] border border-gray-700 shadow-lg rounded-lg text-white">
        <!-- Judul Halaman -->
        <h2 class="text-2xl font-bold mb-6 text-gray-200">Daftar Pengajuan Cuti Semua Karyawan</h2>
    
        <!-- Tabel Cuti -->
        <div class="overflow-x-auto max-h-[400px] overflow-y-auto">
            <table class="w-full bg-[#1E293B] divide-y divide-gray-700 rounded-lg overflow-hidden">
                <thead class="bg-gray-800 sticky top-0">
                    <tr>
                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider w-[50px]">No</th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider w-[100px]">ID</th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Nama Karyawan</th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Jenis Cuti</th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider w-[120px]">Tanggal Mulai</th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider w-[120px]">Tanggal Selesai</th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider w-[100px]">Status</th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider w-[100px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach ($cuti as $item)
                        <tr class="hover:bg-gray-900">
                            <td class="px-3 py-2 text-center whitespace-nowrap">{{ $loop->iteration + ($cuti->currentPage() - 1) * $cuti->perPage() }}</td>
                            <td class="px-3 py-2 text-center whitespace-nowrap">{{ $item->id_cuti }}</td>
                            <td class="px-3 py-2 text-center whitespace-nowrap">{{ $item->pegawai->nama_pegawai }}</td>
                            <td class="px-3 py-2 text-center whitespace-nowrap">{{ $item->jenis_cuti->nama }}</td>
                            <td class="px-3 py-2 text-center whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}
                            </td>
                            <td class="px-3 py-2 text-center whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}
                            </td>
                            <td class="px-3 py-2 text-center whitespace-nowrap">
                                @if ($item->status == 'pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-500 text-white">Pending</span>
                                @elseif ($item->status == 'approved')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-500 text-white">Approved</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-500 text-white">Rejected</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 text-center whitespace-nowrap relative">
                                <!-- Tombol Trigger Dropdown -->
                                <button type="button" class="focus:outline-none" onclick="toggleDropdown({{ $item->id_cuti }})">
                                    <i class="fa-solid fa-ellipsis text-gray-400 hover:text-gray-200 text-lg"></i>
                                </button>
                            
                                <!-- Dropdown Aksi -->
                                <div id="dropdown-{{ $item->id_cuti }}" class="hidden absolute right-0 mt-2 w-48 border border-gray-700 bg-[#1E293B] divide-y divide-gray-700 rounded-lg shadow-lg z-10 transform scale-95 transition-all duration-300 ease-in-out">
                                    <div class="py-1">
                                        <!-- Tombol Approve -->
                                        <form action="{{ route('admin.cuti.update_status', $item->id_cuti) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" name="status" value="approved"
                                                class="flex items-center w-full px-4 py-2 text-sm text-left text-gray-300 hover:bg-green-600 hover:text-white rounded-t-md"
                                                {{ $item->updated_at_status && now()->diffInHours($item->updated_at_status) >= 1 ? 'disabled' : '' }}>
                                                <i class="fa-solid fa-check mr-2 text-green-400"></i> Approve
                                            </button>
                                        </form>
                            
                                        <!-- Tombol Reject -->
                                        <form action="{{ route('admin.cuti.update_status', $item->id_cuti) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" name="status" value="rejected"
                                                class="flex items-center w-full px-4 py-2 text-sm text-left text-gray-300 hover:bg-red-600 hover:text-white"
                                                {{ $item->updated_at_status && now()->diffInHours($item->updated_at_status) >= 1 ? 'disabled' : '' }}>
                                                <i class="fa-solid fa-ban mr-2 text-red-400"></i> Reject
                                            </button>
                                        </form>
                                    </div>
                                    <div class="py-1">
                                        <!-- Tombol Hapus -->
                                        <form id="delete-form-{{ $item->id_cuti }}" action="{{ route('admin.cuti.destroy', $item->id_cuti) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button" onclick="confirmDelete({{ $item->id_cuti }})"
                                            class="flex items-center w-full px-4 py-2 text-sm text-left text-gray-300 hover:bg-red-700 hover:text-white rounded-b-md">
                                            <i class="fa-solid fa-trash mr-2 text-red-400"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    
        <!-- Pagination -->
        <div class="mt-6">
            {{ $cuti->links('pagination::tailwind') }}
        </div>
    </div>
    


    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                background: '#161A23',
                color:'#ffffff'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form jika pengguna mengonfirmasi
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        function toggleDropdown(id) {
        const dropdown = document.getElementById(`dropdown-${id}`);
        dropdown.classList.toggle('hidden');
    }

    // Sembunyikan dropdown jika klik di luar
    document.addEventListener('click', function (event) {
        const dropdowns = document.querySelectorAll('.absolute');
        dropdowns.forEach(dropdown => {
            if (!dropdown.contains(event.target) && !event.target.matches('.fa-ellipsis')) {
                dropdown.classList.add('hidden');
            }
        });
    });
    </script>

@endsection
