<div class="mb-8 px-4">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 justify-items-center mx-auto max-w-5xl">
        <!-- Total Pengajuan Cuti -->
        <div class="bg-[#1E293B] p-3 sm:p-4 rounded-xl shadow-md max-w-64 text-white w-full">
            <div class="flex justify-between items-center mb-2 sm:mb-4">
                <i class="fa-solid fa-calendar-days text-2xl sm:text-3xl text-gray-500"></i>
            </div>
            <div class="text-left">
                <h5 class="text-2xl sm:text-3xl font-bold">{{ $totalCuti }}</h5>
                <h3 class="text-sm sm:text-base font-semibold text-gray-400 mt-1">Total Pengajuan Cuti</h3>   
            </div>
        </div>
    
        <!-- Pengajuan Cuti Pending -->
        <div class="bg-[#1E293B] p-3 sm:p-4 rounded-xl shadow-md max-w-64 text-white w-full">
            <div class="flex justify-between items-center mb-2 sm:mb-4">
                <i class="fa-solid fa-clock text-2xl sm:text-3xl text-yellow-500"></i>
            </div>
            <div class="text-left">
                <h5 class="text-2xl sm:text-3xl font-bold">{{ $cutiPending }}</p>
                <h3 class="text-sm sm:text-base font-semibold text-yellow-500 mt-1">Pending</h3>
            </div>
        </div>
    
        <!-- Pengajuan Cuti Approved -->
        <div class="bg-[#1E293B] p-3 sm:p-4 rounded-xl shadow-md max-w-64 text-white w-full">
            <div class="flex justify-between items-center mb-2 sm:mb-4">
                <i class="fa-solid fa-check-circle text-2xl sm:text-3xl text-green-500"></i>
            </div>
            <div class="text-left">
                <h5 class="text-2xl sm:text-3xl font-bold">{{ $cutiApproved }}</p>                            
                <h3 class="text-sm sm:text-base font-semibold text-green-500 mt-1">Approved</h3>
            </div>
        </div>
    
        <!-- Pengajuan Cuti Rejected -->
        <div class="bg-[#1E293B] p-3 sm:p-4 rounded-xl shadow-md max-w-64 text-white w-full">
            <div class="flex justify-between items-center mb-2 sm:mb-4">
                <i class="fa-solid fa-times-circle text-2xl sm:text-3xl text-red-500"></i>
            </div>
            <div class="text-left">
                <h5 class="text-2xl sm:text-3xl font-bold">{{ $cutiRejected }}</p>
                <h3 class="text-sm sm:text-base font-semibold text-red-500 mt-1">Rejected</h3>
            </div>
        </div>
    </div>
</div>
