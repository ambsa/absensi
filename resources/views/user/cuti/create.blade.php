@extends('layouts.main')


@section('title', 'Cuti')


@section('content')
    <div class="container mx-auto p-6 bg-[#161A23] border border-gray-700 shadow-lg rounded-lg text-white">
        <!-- Judul Form -->
        <h2 class="text-2xl font-bold mb-6 text-gray-200">Form Cuti</h2>

        <form action="{{ route('user.cuti.store') }}" method="POST" class="space-y-4">
            @csrf
        
            <!-- Jenis Cuti -->
            <div>
                <label for="id_jenis_cuti" class="block text-sm font-medium text-gray-400">Jenis Cuti</label>
                <select id="id_jenis_cuti" name="id_jenis_cuti"
                    class="mt-1 block w-full px-3 py-2 border border-gray-600 bg-[#1E293B] text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="" class="text-gray-500">Pilih Jenis Cuti</option>
                    @foreach ($jenis_cuti as $item)
                        <option value="{{ $item->id_jenis_cuti }}" class="text-white">{{ $item->nama }}</option>
                    @endforeach
                </select>
                @error('id_jenis_cuti')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
        
            <!-- Tanggal Mulai -->
            <div>
                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-400">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                    class="mt-1 block w-full px-3 py-2 border border-gray-600 bg-[#1E293B] text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('tanggal_mulai')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
        
            <!-- Tanggal Selesai -->
            <div>
                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-400">Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                    class="mt-1 block w-full px-3 py-2 border border-gray-600 bg-[#1E293B] text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('tanggal_selesai')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
        
            <!-- Alasan Cuti -->
            <div>
                <label for="alasan" class="block text-sm font-medium text-gray-400">Alasan Cuti</label>
                <textarea id="alasan" name="alasan" rows="3"
                    class="mt-1 block w-full px-3 py-2 border border-gray-600 bg-[#1E293B] text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('alasan') }}</textarea>
                @error('alasan')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
        
            <!-- Submit Button -->
            <div class="flex justify-between">
                <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-800 hover:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Ajukan Cuti
                </button>
                <a href="{{ route('user.cuti.index') }}"
                    class="inline-flex justify-center py-2 px-4 shadow-sm text-sm font-medium rounded-md text-white bg-red-800 hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
            </div>
        </form>
    </div>

@endsection
