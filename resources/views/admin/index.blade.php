@extends('layouts.main')

@section('title', 'Admin Dashboard')

@section('content')



    {{-- <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>    --}}


    @include('dashboard_adm.pengajuancuti')

    <div class="container mx-auto mt-8 px-4">
        <!-- Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Bagian Diagram -->
            @include('dashboard_adm.statistik')

            <!-- Bagian Tabel Catatan -->
            @include('dashboard_adm.tabelpegawai')

        </div>
    </div>





    <div class="container mx-auto mt-8 p-4">
        <!-- Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            @include('dashboard_adm.sotm')







            <script src="https://cdn.jsdelivr.net/npm/sweetalert2 @11"></script>

            <!-- Inisialisasi Flatpickr -->


        @endsection
