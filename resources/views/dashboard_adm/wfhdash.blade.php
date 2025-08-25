<!-- Bagian Kartu Statistik WFH -->
<div class="mb-8 px-4">
    <h1 class="text-md md:text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Work from Home</h1>
    <div class="grid grid-cols-1 gap-4 mx-auto max-w-5xl">
        <!-- Total Pengajuan WFH (Full Width) -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md text-gray-800 dark:text-gray-200 w-full">
            <div class="flex justify-between items-center mb-4">
                <i class="fa-solid fa-house-laptop text-3xl text-gray-500 dark:text-gray-400"></i>
            </div>
            <div class="text-left">
                <h5 class="text-3xl font-bold">{{ $totalWfh }}</h5>
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-500 mt-1">Total Pengajuan WFH</h3>
            </div>
        </div>

        <!-- Sisanya (Pending, Approved, Rejected) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Pengajuan WFH Pending -->
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md text-gray-800 dark:text-gray-200 w-full">
                <div class="flex justify-between items-center mb-4">
                    <i class="fa-solid fa-clock text-3xl text-yellow-500 dark:text-yellow-400"></i>
                </div>
                <div class="text-left">
                    <h5 class="text-3xl font-bold">{{ $wfhPending }}</h5>
                    <h3 class="text-base font-semibold text-yellow-500 dark:text-yellow-400 mt-1">Pending</h3>
                </div>
            </div>

            <!-- Pengajuan WFH Approved -->
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md text-gray-800 dark:text-gray-200 w-full">
                <div class="flex justify-between items-center mb-4">
                    <i class="fa-solid fa-check-circle text-3xl text-green-500 dark:text-green-400"></i>
                </div>
                <div class="text-left">
                    <h5 class="text-3xl font-bold">{{ $wfhApproved }}</h5>
                    <h3 class="text-base font-semibold text-green-500 dark:text-green-400 mt-1">Approved</h3>
                </div>
            </div>

            <!-- Pengajuan WFH Rejected -->
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md text-gray-800 dark:text-gray-200 w-full">
                <div class="flex justify-between items-center mb-4">
                    <i class="fa-solid fa-times-circle text-3xl text-red-500 dark:text-red-400"></i>
                </div>
                <div class="text-left">
                    <h5 class="text-3xl font-bold">{{ $wfhRejected }}</h5>
                    <h3 class="text-base font-semibold text-red-500 dark:text-red-400 mt-1">Rejected</h3>
                </div>
            </div>
        </div>
    </div>
</div>