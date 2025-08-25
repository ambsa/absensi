@extends('layouts.main')

@section('title', 'Pegawai')

@section('content')

    <div class="mb-4 flex justify-end">
        <a href="{{ route('admin.pegawai.create') }}"
            class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-4 py-2 rounded flex items-center space-x-2 transition duration-200">
            <i class="fa-solid fa-plus mr-2"></i>Tambah Pegawai
        </a>
    </div>

    <div class="overflow-x-auto">
        <div class="overflow-x-auto rounded-lg border border-[var(--table-border)] shadow-md">
            <table class="min-w-full w-full bg-[var(--table-bg)] divide-y divide-[var(--table-border)]">
                <thead class="bg-[var(--table-header-bg)]">
                    <tr>
                        <th class="py-3 px-4 text-left text-xs font-medium text-[var(--text-color)] uppercase tracking-wider">
                            Nama Pegawai
                        </th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-[var(--text-color)] uppercase tracking-wider">
                            Email
                        </th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-[var(--text-color)] uppercase tracking-wider">
                            Role
                        </th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-[var(--text-color)] uppercase tracking-wider">
                            Departemen
                        </th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-[var(--text-color)] uppercase tracking-wider">
                            Kartu
                        </th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-[var(--text-color)] uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--table-border)]">
                    @foreach ($pegawais as $pegawai)
                        <tr class="hover:bg-[var(--table-hover-bg)] transition duration-200 ease-in-out">
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-[var(--text-color)]">
                                {{ $pegawai->nama_pegawai }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-[var(--text-color)]">
                                {{ $pegawai->email }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-[var(--text-color)]">
                                {{ $pegawai->role->name ?? '-' }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-[var(--text-color)]">
                                {{ $pegawai->departemen->nama_departemen ?? '-' }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-[var(--text-color)]">
                                {{ $pegawai->uuid ?? '-' }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm">
                                <!-- Edit Button with hover effect -->
                                <a href="{{ route('admin.pegawai.edit', $pegawai) }}"
                                    class="text-[var(--icon-color)] hover:text-[var(--icon-hover-color)] transition duration-200 ease-in-out transform hover:scale-110">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
    
                                <!-- Delete Button with hover effect -->
                                <form action="{{ route('admin.pegawai.destroy', $pegawai) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="delete-btn text-[var(--button-danger)] hover:text-[var(--button-danger)]/80 ml-2 transition duration-200 ease-in-out transform hover:scale-110">
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
                background: '#1f2937',
                color: '#f9fafb'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Kirim form setelah konfirmasi
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Cek jika ada pesan sukses
        @if (session('success'))
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000, 
                background: '#1f2937',
                color: '#f9fafb',
                toast: true,
                width: '350px',
                padding: '1.5rem',
                customClass: {
                    popup: 'swal2-noanimation swal2-padding',
                    title: 'swal2-title-large',
                },
            });
        @endif

        // Cek jika ada pesan error
        @if (session('error'))
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: true,    
                confirmButtonText: 'Tutup', 
                confirmButtonColor: '#dc3545',
                background: '#1f2937',
                color: '#f9fafb',
                width: '400px',
                padding: '2rem',
                customClass: {
                    popup: 'swal2-noanimation swal2-padding', 
                    title: 'swal2-title-large',
                },
            });
        @endif
    });
    </script>
@endsection
