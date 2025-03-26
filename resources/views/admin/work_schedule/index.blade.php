@extends('layouts.main')

@section('title', 'Work Schedules')

@section('content')
    <div class="container mx-auto p-4 md:p-6">
        <div class="text-center mb-8">
            <h1 class="text-2xl md:text-4xl font-semibold text-gray-800">Work Schedules</h1>
        </div>
        <div class="mb-4 flex justify-end">
            <a href="{{ route('admin.work_schedule.create') }}"
                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 flex items-center">
                <i class="fa-solid fa-plus mr-2"></i>Add Schedule
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-4 md:p-6 overflow-x-auto">
            <!-- Tabel jadwal kerja -->
            <table class="min-w-max w-full table-auto">
                <thead>
                    <tr class="text-sm md:text-base">
                        <th class="py-3 px-4 border-b text-left">User</th>
                        <th class="py-3 px-4 border-b text-left">Day</th>
                        <th class="py-3 px-4 border-b text-left">Start Time</th>
                        <th class="py-3 px-4 border-b text-left">End Time</th>
                        <th class="py-3 px-4 border-b text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workschedule as $schedule)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                            <td class="py-3 px-4 border-b">{{ $schedule->user->name }}</td>
                            <td class="py-3 px-4 border-b">{{ $schedule->day }}</td>
                            <td class="py-3 px-4 border-b">{{ $schedule->start }}</td>
                            <td class="py-3 px-4 border-b">{{ $schedule->end }}</td>
                            <td class="py-3 px-4 border-b">
                                <!-- Aksi Edit dan Hapus -->
                                <a href="{{ route('admin.work_schedule.edit', $schedule->id) }}" class="text-blue-500 hover:underline">Edit</a>
                                <form action="{{ route('admin.work_schedule.delete', $schedule->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline ml-2">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
