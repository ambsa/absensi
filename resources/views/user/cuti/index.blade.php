@extends('layouts.main')


@section('title', 'Cuti')


@section('content')
    <div class="mb-4 flex justify-end">
        <a href="{{ route('user.cuti.create') }}"
            class="bg-indigo-800 text-white px-3 py-1 rounded hover:bg-blue-900 flex items-center">
            <i class="fa-solid fa-plus mr-2"></i>Tambah cuti
        </a>
    </div>

    <div class="container mx-auto p-6 bg-[#161A23] border border-gray-700 shadow-lg rounded-lg text-white">
        <!-- Judul Halaman -->
        <h2 class="text-2xl font-bold mb-6 text-gray-200">Daftar Pengajuan Cuti Saya</h2>
    
        <!-- Tabel Cuti -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-[#1E293B] divide-y divide-gray-700 rounded-lg overflow-hidden">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Jenis Cuti</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal Mulai</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal Selesai</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach ($cuti as $item)
                        <tr class="hover:bg-gray-900">
                            <td class="px-6 py-4 text-center whitespace-nowrap">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">{{ $item->id_cuti }}</td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">{{ $item->jenis_cuti->nama }}</td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y') }}
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                @if ($item->status == 'pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-lg bg-yellow-500 text-white">Pending</span>
                                @elseif ($item->status == 'approved')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-lg bg-green-500 text-white">Approved</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-lg bg-red-500 text-white">Rejected</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form jika pengguna mengonfirmasi
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>

@endsection
