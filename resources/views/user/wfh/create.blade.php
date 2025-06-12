@extends('layouts.main')

@section('title', 'Pengajuan WFH Saya')

@section('content')

<div class="container mx-auto px-4 py-8">

    <form action="{{ route('user.wfh.store') }}" method="POST" class="max-w-md mx-auto bg-gray-800 p-8 rounded-lg shadow-lg">
        @csrf

        <!-- Nama Pegawai -->
        <div class="mb-4">
            <label for="nama_pegawai" class="block text-gray-300 text-sm font-bold mb-2">Nama Pegawai</label>
            <input type="text" name="nama_pegawai" id="nama_pegawai" 
                class="shadow appearance-none border border-gray-700 rounded w-full py-2 px-3 text-gray-300 bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                value="{{ Auth::user()->nama_pegawai ?? '-' }}" readonly>
        </div>

        <!-- Tanggal -->
        <div class="mb-4">
            <label for="tanggal" class="block text-gray-300 text-sm font-bold mb-2">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" 
                class="shadow appearance-none border border-gray-700 rounded w-full py-2 px-3 text-gray-300 bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <!-- Tombol Ajukan dan Cancel -->
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline hover:bg-indigo-800">
                Ajukan
            </button>
            <a href="{{ route('user.wfh.index') }}" class="bg-red-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline hover:bg-red-700">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
