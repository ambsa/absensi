<!-- admin/data_absen/all-pdf.blade.php -->
<table class="min-w-full border-collapse">
    <thead>
        <tr>
            <th class="border px-4 py-2">ID Absen</th>
            <th class="border px-4 py-2">Nama Pegawai</th>
            <th class="border px-4 py-2">Jam Masuk</th>
            <th class="border px-4 py-2">Jam Pulang</th>
            <th class="border px-4 py-2">Catatan</th>
            <th class="border px-4 py-2">Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datasens as $datasen)
            <tr>
                <td class="border px-4 py-2">{{ $datasen['id_absen'] }}</td>
                <td class="border px-4 py-2">{{ $datasen['nama_pegawai'] }}</td>
                <td class="border px-4 py-2">{{ $datasen['jam_masuk'] }}</td>
                <td class="border px-4 py-2">{{ $datasen['jam_pulang'] }}</td>
                <td class="border px-4 py-2">{{ $datasen['catatan'] }}</td>
                <td class="border px-4 py-2">{{ $datasen['tanggal'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>