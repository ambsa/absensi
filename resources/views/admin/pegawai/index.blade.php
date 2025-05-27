@extends('layouts.main')

@section('title', 'Pegawai')

@section('content')

    <div class="mb-4 flex justify-end">
        <a href="{{ route('admin.pegawai.create') }}"
            class="bg-indigo-800 text-white px-3 py-1 rounded hover:bg-blue-900 flex items-center">
            <i class="fa-solid fa-plus mr-2"></i>Tambah Pegawai
        </a>
    </div>

    <div class="overflow-x-auto">
        <div class="overflow-x-auto rounded-lg">
            <table class="min-w-full w-full bg-[#161A23] border border-gray-700">
                <thead>
                    <tr class="bg-gray-800">
                        <th class="py-3 px-4 border-b border-gray-700 text-left text-white">
                            Nama Pegawai
                        </th>
                        <th class="py-3 px-4 border-b border-gray-700 text-left text-white">
                            Email
                        </th>
                        <th class="py-3 px-4 border-b border-gray-700 text-left text-white">
                            Role
                        </th>
                        <th class="py-3 px-4 border-b border-gray-700 text-left text-white">
                            Departemen
                        </th>
                        <th class="py-3 px-4 border-b border-gray-700 text-left text-white">
                            Kartu
                        </th>
                        <th class="py-3 px-4 border-b border-gray-700 text-left text-white">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pegawais as $pegawai)
                        <tr class="{{ $loop->even ? 'bg-gray-900' : 'bg-[#161A23]' }}">
                            <td class="py-3 px-4 border-b border-gray-700 whitespace-nowrap text-white">
                                {{ $pegawai->nama_pegawai }}
                            </td>
                            <td class="py-3 px-4 border-b border-gray-700 whitespace-nowrap text-white">
                                {{ $pegawai->email }}
                            </td>
                            <td class="py-3 px-4 border-b border-gray-700 whitespace-nowrap text-white">
                                {{ $pegawai->role->name ?? '-' }}
                            </td>
                            <td class="py-3 px-4 border-b border-gray-700 whitespace-nowrap text-white">
                                {{ $pegawai->departemen->nama_departemen ?? '-' }}
                            </td>
                            <td class="py-3 px-4 border-b border-gray-700 whitespace-nowrap text-white">
                                {{ $pegawai->uuid ?? '-' }}
                            </td>
                            <td class="py-3 px-4 border-b border-gray-700 whitespace-nowrap">
                                <!-- Edit Button with hover effect -->
                                <a href="{{ route('admin.pegawai.edit', $pegawai) }}"
                                    class="text-blue-400 hover:text-blue-300 transition duration-200 ease-in-out transform hover:scale-110">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
    
                                <!-- Delete Button with hover effect -->
                                <form action="{{ route('admin.pegawai.destroy', $pegawai) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="delete-btn text-red-400 hover:text-red-300 ml-2 transition duration-200 ease-in-out transform hover:scale-110">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Menghentikan pengiriman form langsung

            const form = this.closest('form'); // Ambil form terdekat

            Swal.fire({
                title: "Apakah anda yakin?",
                text: "Kamu akan menghapus data ini secara permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                background: '#161A23',
                color:'#ffffff'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Kirim form setelah konfirmasi
                }
            });
        });
    });
    </script>
@endsection
