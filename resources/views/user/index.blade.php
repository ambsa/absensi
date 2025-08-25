@extends('layouts.main')

@section('title', 'User Dashboard')

@section('content')

<div class="container mx-auto mt-8">
    <!-- Grid Layout Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Bagian Diagram -->
        <div class="rounded-lg shadow p-4 md:p-6">
            @include('dashboard_usr.pengajuancutiuser')
        </div>
        <div class="rounded-lg shadow p-4 md:p-6">
            @include('dashboard_usr.wfhdashuser')
        </div>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Alert Success/Error Messages -->
    @if(session('success'))
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                icon: 'success',
                background: '#1f2937',
                color: '#f3f4f6',
                backdrop: 'rgba(0, 0, 0, 0.8)',
                confirmButtonColor: '#059669',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'dark-theme-popup',
                    title: 'text-gray-200',
                    content: 'text-gray-300',
                    confirmButton: 'bg-green-600 hover:bg-green-700 text-white'
                }
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                background: '#1f2937',
                color: '#f3f4f6',
                backdrop: 'rgba(0, 0, 0, 0.8)',
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'dark-theme-popup',
                    title: 'text-gray-200',
                    content: 'text-gray-300',
                    confirmButton: 'bg-red-600 hover:bg-red-700 text-white'
                }
            });
        </script>
    @endif

    <style>
    .dark-theme-popup {
        background-color: #1f2937 !important;
        border: 1px solid #374151 !important;
    }

    .dark-theme-popup .swal2-title {
        color: #f3f4f6 !important;
    }

    .dark-theme-popup .swal2-content {
        color: #d1d5db !important;
    }

    .dark-theme-popup .swal2-confirm {
        background-color: #059669 !important;
        border-color: #059669 !important;
    }

    .dark-theme-popup .swal2-confirm:hover {
        background-color: #047857 !important;
        border-color: #047857 !important;
    }

    .dark-theme-popup .swal2-cancel {
        background-color: #374151 !important;
        border-color: #374151 !important;
    }

    .dark-theme-popup .swal2-cancel:hover {
        background-color: #4b5563 !important;
        border-color: #4b5563 !important;
    }

    /* Success Alert Specific */
    .dark-theme-popup.swal2-icon-success .swal2-confirm {
        background-color: #059669 !important;
        border-color: #059669 !important;
    }

    .dark-theme-popup.swal2-icon-success .swal2-confirm:hover {
        background-color: #047857 !important;
        border-color: #047857 !important;
    }

    /* Error Alert Specific */
    .dark-theme-popup.swal2-icon-error .swal2-confirm {
        background-color: #dc2626 !important;
        border-color: #dc2626 !important;
    }

    .dark-theme-popup.swal2-icon-error .swal2-confirm:hover {
        background-color: #b91c1c !important;
        border-color: #b91c1c !important;
    }
    </style>
@endsection
