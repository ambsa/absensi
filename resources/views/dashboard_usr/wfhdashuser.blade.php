<!-- Bagian Kartu Statistik WFH -->
<div class="mb-8 px-4">
    <h1 class="text-md md:text-xl font-semibold text-gray-400 mb-4">Pengajuan WFH</h1>
    <div class="grid grid-cols-1 gap-4 mx-auto max-w-5xl">
        <!-- Total Pengajuan WFH (Full Width) -->
        <div class="bg-[#1E293B] p-4 rounded-xl shadow-md text-white w-full">
            <div class="flex justify-between items-center mb-4">
                <i class="fa-solid fa-house-laptop text-3xl text-gray-500"></i>
            </div>
            <div class="text-left">
                <h5 class="text-3xl font-bold">{{ $totalWfh }}</h5>
                <h3 class="text-base font-semibold text-gray-400 mt-1">Total Pengajuan WFH</h3>
            </div>
        </div>

        <!-- Sisanya (Pending, Approved, Rejected) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Pengajuan WFH Pending -->
            <div class="bg-[#1E293B] p-4 rounded-xl shadow-md text-white w-full">
                <div class="flex justify-between items-center mb-4">
                    <i class="fa-solid fa-clock text-3xl text-yellow-500"></i>
                </div>
                <div class="text-left">
                    <h5 class="text-3xl font-bold">{{ $wfhPending }}</h5>
                    <h3 class="text-base font-semibold text-yellow-500 mt-1">Pending</h3>
                </div>
            </div>

            <!-- Pengajuan WFH Approved -->
            <div class="bg-[#1E293B] p-4 rounded-xl shadow-md text-white w-full">
                <div class="flex justify-between items-center mb-4">
                    <i class="fa-solid fa-check-circle text-3xl text-green-500"></i>
                </div>
                <div class="text-left">
                    <h5 class="text-3xl font-bold">{{ $wfhApproved }}</h5>
                    <h3 class="text-base font-semibold text-green-500 mt-1">Approved</h3>
                </div>
            </div>

            <!-- Pengajuan WFH Rejected -->
            <div class="bg-[#1E293B] p-4 rounded-xl shadow-md text-white w-full">
                <div class="flex justify-between items-center mb-4">
                    <i class="fa-solid fa-times-circle text-3xl text-red-500"></i>
                </div>
                <div class="text-left">
                    <h5 class="text-3xl font-bold">{{ $wfhRejected }}</h5>
                    <h3 class="text-base font-semibold text-red-500 mt-1">Rejected</h3>
                </div>
            </div>
        </div>
    </div>
</div>
