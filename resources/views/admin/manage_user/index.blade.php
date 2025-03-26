@extends('layouts.main')

@section('title', 'Manage User')

@section('content')
    <h1 class="text-4xl text-center font-bold mb-4">Manage Users</h1>

    <div class="mb-4 flex justify-end">
        <a href="{{ route('users.create') }}"
            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 flex items-center">
            <i class="fa-solid fa-plus mr-2"></i>Tambah User
        </a>
    </div>

    <div class="overflow-x-auto">
        <div class="overflow-x-auto">
            <table class="min-w-full w-full bg-white border border-gray-200">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="py-3 px-4 border-b text-left">Name</th>
                        <th class="py-3 px-4 border-b text-left">Email</th>
                        <th class="py-3 px-4 border-b text-left">Role</th>
                        <th class="py-3 px-4 border-b text-left">Departemen</th>
                        <th class="py-3 px-4 border-b text-left">No. Kartu</th>
                        <th class="py-3 px-4 border-b text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                            <td class="py-3 px-4 border-b whitespace-nowrap">{{ $user->name }}</td>
                            <td class="py-3 px-4 border-b whitespace-nowrap">{{ $user->email }}</td>
                            <td class="py-3 px-4 border-b whitespace-nowrap">{{ $user->role->name ?? '-' }}</td>
                            <td class="py-3 px-4 border-b whitespace-nowrap">{{ $user->departemen->name ?? '-' }}</td>
                            <td class="py-3 px-4 border-b whitespace-nowrap">{{ $user->rfidCard->card_number ?? '-' }}</td>
                            <td class="py-3 px-4 border-b whitespace-nowrap">
                                <!-- Edit Button with hover effect -->
                                <a href="{{ route('users.edit', $user->id) }}" class="text-blue-500 hover:text-blue-700 transition duration-200 ease-in-out transform hover:scale-110">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            
                                <!-- Delete Button with hover effect -->
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="text-red-500 hover:text-red-700 ml-2 transition duration-200 ease-in-out transform hover:scale-110" id="deleteBtn-{{ $user->id }}">
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
        document.querySelectorAll('.text-red-500').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault(); // Menghentikan pengiriman form langsung

                const form = this.closest('form'); // Ambil form terdekat
                // Menampilkan konfirmasi SweetAlert2
                Swal.fire({
                    title: "Apakah anda yakin?",
                    text: "Kamu akan menghapus data ini secara permanen!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Hapus!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Kirim form setelah konfirmasi penghapusan
                    }
                });
            });
        });
    </script>
@endsection
