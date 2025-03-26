@extends('layouts.main')

@section('title', 'Work Schedule')

@section('content')
    <div class="container mx-auto mt-8 max-w-3xl">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Set Work Schedule for All Users</h1>

            <!-- Form for setting work schedule -->
            <form action="{{ route('admin.work_schedule.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <!-- Schedule Days Input -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                            <div class="flex items-center space-x-2">
                                <!-- Day Name -->
                                <label for="{{ strtolower($day) }}_start" class="block text-sm font-medium text-gray-700 w-24">{{ $day }}</label>

                                <!-- Waktu Masuk dan Waktu Pulang untuk semua pengguna -->
                                <input type="time" name="{{ strtolower($day) }}_start" id="{{ strtolower($day) }}_start" class="mt-2 block w-28 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>

                                <span class="mx-2">-</span>

                                <input type="time" name="{{ strtolower($day) }}_end" id="{{ strtolower($day) }}_end" class="mt-2 block w-28 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            </div>
                        @endforeach
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-center mt-6">
                        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Save Schedule</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
