<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Halaman Absensi Karyawan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a329084b4e.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
</head>
<body class="bg-gray-100">

    <!-- Container untuk halaman -->
    <div class="relative min-h-screen">

        <!-- Tombol Logout di pojok kanan atas -->
        <button 
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
            class="absolute top-4 right-4 bg-red-500 text-white py-2 px-4 rounded-md shadow-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            Logout
        </button>

        <!-- Form Logout -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>

        <!-- Card untuk form -->
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white shadow-lg rounded-xl p-8 max-w-xl w-full">
                <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Scan RFID Untuk Kehadiran</h1>

                <!-- Form untuk scanning RFID -->
                <div class="flex flex-col items-center">
                    <label for="rfid_card_number" class="block text-sm font-medium text-gray-700 mb-2">Scan Kartu RFID</label>
                    
                    <!-- Input RFID, sudah autofocus dan siap menerima input -->
                    <input 
                        type="text" 
                        id="rfid_card_number" 
                        class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-center"
                        placeholder="Scan RFID Card" 
                        oninput="submitRfid()" 
                        autofocus 
                    >
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk menangani scan RFID dan mengirimkan data ke server
        function submitRfid() {
            let rfidCardNumber = document.getElementById('rfid_card_number').value;

            if (rfidCardNumber.length > 0) {
                fetch('{{ route('tap_rfid.attendance.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ rfid_card_number: rfidCardNumber })
                })
                .then(response => response.json())
                .then(data => {
                    // Tampilkan popup jika berhasil
                    if (data.status === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message, // Pesan berhasil
                        }).then(() => {
                            // Jika berhasil, reset input dan mungkin refresh halaman absensi
                            document.getElementById('rfid_card_number').value = ''; 
                            // Optionally refresh the attendance list if needed
                            // window.location.reload();
                        });
                    } else {
                        // Tampilkan popup jika gagal
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message, // Pesan gagal
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Terjadi kesalahan saat memproses absensi.',
                    });
                });
            }
        }
    </script>

</body>
</html>
