@extends('layouts.main')

@section('title', 'Attendance')

@section('content')
    <div class="container mx-auto p-4 md:p-6">

        <div class="text-center mb-8">
            <h1 class="text-2xl md:text-4xl font-semibold text-gray-800">Data Absensi Karyawan</h1>
        </div>

        <!-- Wrapper Responsif -->
        <div class="bg-white shadow-md rounded-lg p-4 md:p-6 overflow-x-auto">
            <table class="min-w-max w-full">
                <thead>
                    <tr class="text-sm md:text-base">
                        <th class="py-3 px-2 md:px-4 border-b text-left">Nama</th>
                        <th class="py-3 px-2 md:px-4 border-b text-left">Tanggal</th>
                        <th class="py-3 px-2 md:px-4 border-b text-left">Jam Masuk</th>
                        <th class="py-3 px-2 md:px-4 border-b text-left">Jam Pulang</th>
                        <th class="py-3 px-2 md:px-4 border-b text-left">Status</th>
                        <th class="py-3 px-2 md:px-4 border-b text-left">Nomor Kartu RFID</th>
                        <th class="py-3 px-2 md:px-4 border-b text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $attendance)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                            <td class="py-3 px-2 md:px-4 border-b">{{ $attendance->user->name }}</td>
                            <td class="py-3 px-2 md:px-4 border-b">{{ $attendance->date }}</td>
                            <td class="py-3 px-2 md:px-4 border-b">{{ $attendance->check_in }}</td>
                            <td class="py-3 px-2 md:px-4 border-b">{{ $attendance->check_out ?? '-' }}</td>
                            <td class="py-3 px-2 md:px-4 border-b">{{ ucfirst($attendance->status) }}</td>
                            <td class="py-3 px-2 md:px-4 border-b">
                                {{ $attendance->rfidCard->card_number ?? '-' }}
                            </td>
                            <td class="py-3 px-2 md:px-4 border-b">
                                <button class="text-blue-500 hover:underline">Edit</button>
                                <button class="text-red-500 hover:underline ml-2">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
@endsection
