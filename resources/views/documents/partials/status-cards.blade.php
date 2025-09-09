{{-- resources/views/documents/partials/status-cards.blade.php --}}
<div class="flex flex-wrap gap-6">
    <!-- All Documents -->
    <div onclick="window.location.href='{{ route($prefix . 'documents.index', ['status' => 'all']) }}'"
        class="flex-1 min-w-[200px] cursor-pointer transform transition duration-300 hover:scale-105 hover:shadow-lg">
        <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white">
            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-gray-500">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M4 6h16M4 12h16M4 18h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="mx-5">
                <h4 class="text-2xl font-semibold text-gray-700">{{ $allCount }}</h4>
                <div class="text-gray-500">All Documents</div>
            </div>
        </div>
    </div>

    <!-- In Progress -->
    <div onclick="window.location.href='{{ route($prefix . 'documents.index', ['status' => 'In Progress']) }}'"
        class="flex-1 min-w-[200px] cursor-pointer transform transition duration-300 hover:scale-105 hover:shadow-lg">
        <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white">
            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-500">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M12 8v4l3 3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="12" cy="12" r="9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="mx-5">
                <h4 class="text-2xl font-semibold text-gray-700">{{ $inProgressCount }}</h4>
                <div class="text-gray-500">In Progress</div>
            </div>
        </div>
    </div>

    <!-- Under Review -->
    <div onclick="window.location.href='{{ route($prefix . 'documents.index', ['status' => 'Under Review']) }}'"
        class="flex-1 min-w-[200px] cursor-pointer transform transition duration-300 hover:scale-105 hover:shadow-lg">
        <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white">
            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-yellow-500">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8 12h8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="mx-5">
                <h4 class="text-2xl font-semibold text-gray-700">{{ $underReviewCount }}</h4>
                <div class="text-gray-500">Under Review</div>
            </div>
        </div>
    </div>

    <!-- For Release -->
    <div onclick="window.location.href='{{ route($prefix . 'documents.index', ['status' => 'For Release']) }}'"
        class="flex-1 min-w-[200px] cursor-pointer transform transition duration-300 hover:scale-105 hover:shadow-lg">
        <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white">
            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-green-500">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M8 7h8M8 11h8M8 15h8M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="mx-5">
                <h4 class="text-2xl font-semibold text-gray-700">{{ $forReleaseCount }}</h4>
                <div class="text-gray-500">For Release</div>
            </div>
        </div>
    </div>

    <!-- Returned -->
    <div onclick="window.location.href='{{ route($prefix . 'documents.index', ['status' => 'Returned']) }}'"
        class="flex-1 min-w-[200px] cursor-pointer transform transition duration-300 hover:scale-105 hover:shadow-lg">
        <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white">
            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-red-500">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="mx-5">
                <h4 class="text-2xl font-semibold text-gray-700">{{ $returnedCount }}</h4>
                <div class="text-gray-500">Returned</div>
            </div>
        </div>
    </div>

    <!-- Completed -->
    <div onclick="window.location.href='{{ route($prefix . 'documents.index', ['completed' => 'Completed']) }}'"
        class="flex-1 min-w-[200px] cursor-pointer transform transition duration-300 hover:scale-105 hover:shadow-lg">
        <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white">
            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-gray-500">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="mx-5">
                <h4 class="text-2xl font-semibold text-gray-700">{{ $completedCount }}</h4>
                <div class="text-gray-500">Completed</div>
            </div>
        </div>
    </div>
</div>
