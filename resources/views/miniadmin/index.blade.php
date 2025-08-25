@extends('layouts.main')

@section('title', 'Mini Admin Dashboard')

@section('content')


 <div class="container mx-auto mt-8">
    <!-- Grid Layout Statistik -->
    <div class="grid grid-cols-1 shadow rounded-lg md:grid-cols-2 gap-6">
        <!-- Bagian Diagram -->
        <div class="rounded-lg p-4 md:p-6">
            @include('dashboard_miniadmin.permohonancuti_dash')
        </div>
        <div class="rounded-lg p-4 md:p-6">
            @include('dashboard_miniadmin.pengajuanwfh')
        </div>
    </div>
</div>


    <div class="container mx-auto mt-8">
        <!-- Grid Layout Statistik -->
        <div class="grid grid-cols-1 gap-6">
            <!-- Bagian Diagram -->
            <div class="rounded-lg p-4">
                @include('dashboard_miniadmin.statistik')
            </div>
        </div>

        <!-- Grid Layout SOTM & Tabel Catatan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <div class="rounded-lg shadow p-4">
                @include('dashboard_miniadmin.sotm')
            </div>
            <div class="rounded-lg shadow p-4">
                @include('dashboard_miniadmin.tabelpegawai')
            </div>
        </div>
    </div>







    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@endsection
