@extends('layouts.main')

@section('title', 'User Dashboard')

@section('content')

    @include('dashboard_usr.pengajuancutiuser')

    <div class="container mx-auto mt-8 px-4">
        <!-- Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2 @11"></script>
@endsection
