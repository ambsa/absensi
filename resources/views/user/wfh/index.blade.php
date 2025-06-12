@extends('layouts.main')

@section('title', 'Pengajuan WFH Saya')

@section('content')
<div class="container mx-auto p-6 bg-[#161A23]">
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center space-x-2">
            <!-- Form Filter -->
            <form action="{{ route('user.wfh.index') }}" method="GET" class="flex items-center space-x-2">
                <label for="status" class="font-semibold text-white">Filter Status:</label>
                <select name="status" id="status"
                    class="border border-gray-700 p-2 rounded-md text-gray-300 bg-gray-700 w-32">
                    <option value="" {{ request('status') === '' ? 'selected' : '' }}>Semua</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </form>
        </div>
    </div>

    <div class="mb-4 flex justify-end">
        <!-- Tombol Ajukan WFH -->
        <a href="{{ route('user.wfh.create') }}"
            class="bg-indigo-800 text-white px-3 py-1 rounded hover:bg-blue-900 flex items-center">
            <i class="fa-solid fa-plus mr-2"></i>Ajukan WFH
        </a>
    </div>

    <!-- Tabel Pengajuan WFH -->
    <div class="shadow-md">
        <table class="w-full bg-[#1E293B] divide-y divide-gray-700 rounded-lg overflow-hidden">
            <thead class="bg-gray-800 sticky top-0 z-10">
                <tr>
                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider w-[50px]">
                        No
                    </th>
                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider w-[100px]">
                        Tanggal
                    </th>
                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider w-[80px]">
                        Status
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse ($wfhs as $wfh)
                    <tr class="hover:bg-gray-900">
                        <td class="px-3 py-2 text-center whitespace-nowrap">
                            {{ $loop->iteration + ($wfhs->currentPage() - 1) * $wfhs->perPage() }}
                        </td>
                        <td class="px-3 py-2 text-center whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($wfh->tanggal)->format('d-m-Y') }}
                        </td>
                        <td class="px-3 py-2 text-center whitespace-nowrap">
                            @if ($wfh->status === 'pending')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-500 text-white">
                                    Pending
                                </span>
                            @elseif ($wfh->status === 'approved')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-500 text-white">
                                    Approved
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-500 text-white">
                                    Rejected
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-3 py-2 text-center text-gray-300">
                            Tidak ada pengajuan WFH.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $wfhs->appends(request()->except('page'))->links('pagination::tailwind') }}
    </div>
</div>
@endsection
