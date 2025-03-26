<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a329084b4e.js" crossorigin="anonymous"></script>

    <style>
        tr:hover {
            background-color: #f1f5f9;
        }
    </style>
</head>

<body>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Riwayat Absensi</h1>
        <div class="mb-6">
            <a href="{{ route('user.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300 ease-in-out transform hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
            </a>
        </div>

        <!-- Tabel Riwayat Absensi -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full table-auto border-collapse">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="py-3 px-6 text-left text-sm font-medium">Tanggal</th>
                        <th class="py-3 px-6 text-left text-sm font-medium">Jam Masuk</th>
                        <th class="py-3 px-6 text-left text-sm font-medium">Jam Pulang</th>
                        <th class="py-3 px-6 text-left text-sm font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendances as $attendance)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : '' }} hover:bg-gray-100">
                            <td class="py-3 px-6 text-sm text-gray-700">{{ $attendance->date }}</td>
                            <td class="py-3 px-6 text-sm text-gray-700">{{ $attendance->check_in }}</td>
                            <td class="py-3 px-6 text-sm text-gray-700">{{ $attendance->check_out ?? 'Belum Pulang' }}</td>
                            <td class="py-3 px-6 text-sm text-gray-700">{{ ucfirst($attendance->status) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>
</body>

</html>
