<x-admin-layout>
    <div x-data="{ filterStatus: 'all', open: false, filterOpen: false, logsOpen: false }">
        {{-- Start Cards --}}
        <div class="flex flex-wrap gap-6">
            <!-- All Documents -->
            <div onclick="window.location.href='{{ route('documents.index', ['status' => 'all']) }}'"
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
             <div onclick="window.location.href='{{ route('documents.index', ['status' => 'In Progress']) }}'"
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
            <div onclick="window.location.href='{{ route('documents.index', ['status' => 'Under Review']) }}'"
                class="flex-1 min-w-[200px] cursor-pointer transform transition duration-300 hover:scale-105 hover:shadow-lg">
                <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white">
                    <div class="flex items-center justify-center h-10 w-10 rounded-full bg-yellow-500">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="mx-5">
                        <h4 class="text-2xl font-semibold text-gray-700">{{ $underReviewCount }}</h4>
                        <div class="text-gray-500">Under Review</div>
                    </div>
                </div>
            </div>

            <!-- For Release -->
            <div onclick="window.location.href='{{ route('documents.index', ['status' => 'For Release']) }}'"
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
            <div onclick="window.location.href='{{ route('documents.index', ['status' => 'Returned']) }}'"
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

            <!-- No Status -->
            <div onclick="window.location.href='{{ route('documents.index', ['status' => 'no-status']) }}'"
                class="flex-1 min-w-[200px] cursor-pointer transform transition duration-300 hover:scale-105 hover:shadow-lg">
                <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white">
                    <div class="flex items-center justify-center h-10 w-10 rounded-full bg-gray-500">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="12" cy="12" r="9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8 12h8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="mx-5">
                        <h4 class="text-2xl font-semibold text-gray-700">{{ $noStatusCount }}</h4>
                        <div class="text-gray-500">No Status</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Table -->
        <div class="overflow-hidden rounded-lg shadow-md border border-gray-200 mt-4">
            <table class="min-w-full bg-white text-sm border-collapse">
                <thead>
                    <!-- Wrap in a single Alpine scope so both modals share the same state -->
                    <div x-data="{ open: false, filterOpen: false }">
                        <tr>
                            <th colspan="10" class="text-left p-2">
                                <div class="relative w-full sm:w-auto flex justify-start py-2">
                                    @hasrole('records')
                                    <button
                                        @click="$dispatch('open-modal', 'document-submission')"
                                        class="text-gray-800 px-4 py-2 rounded border border-gray-400 hover:border-gray-600"
                                    >
                                        + Add New Document
                                    </button>
                                    @endhasrole

                                    <!-- Filter Button -->
                                    <button
                                        @click="filterOpen = true"
                                        class="text-gray-800 px-4 py-2 rounded border border-gray-400 hover:border-gray-600 ml-2"
                                    >
                                        Filter
                                    </button>

                                    <!-- Clear filter -->
                                    <a href="{{ route('documents.index') }}"
                                        class="text-gray-800 px-4 py-2 rounded border border-gray-400 hover:border-gray-600 ml-2">
                                        Clear Filter
                                    </a>
                                </div>
                            </th>
                        </tr>

                        <!-- Both modals inside same scope -->
                        @include('components.documents.document-modal')
                        @include('components.documents.filter-modal')
                    </div>

                    <tr class="bg-gray-100">
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200">Date Received</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200 whitespace-nowrap">Document #</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200 whitespace-nowrap">Document Type</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200">Particulars</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200 whitespace-nowrap">OED Level</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200">Date Received</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200">Status</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200">Logs</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200">Remarks OED</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200">Records</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200">Date Received</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($documents as $document)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-5 py-3">{{ $document->date_received?->format('F j, Y g:i A') }}</td>
                            <td class="px-5 py-3 whitespace-nowrap">{{ $document->document_no }}</td>
                            <td class="px-5 py-3">{{ $document->document_type }}</td>
                            <td class="px-5 py-3">{{ $document->particulars }}</td>
                            <td class="px-5 py-3">
                                @if ($document->oed_date_received)
                                    <span class="text-sm font-bold">Received</span>
                                @else
                                    @hasrole('oed')
                                        <div class="relative inline-block text-left" x-data="{ open: false }">
                                            <button @click="open = !open"
                                                class="bg-white border border-gray-300 text-xs px-3 py-1 rounded hover:border-gray-400">
                                                Action
                                            </button>
                                            <div x-show="open" @click.away="open = false" x-cloak
                                                class="absolute z-10 mt-2 w-32 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                                                <form action="{{ route('documents.oed.receive.single', $document->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        Mark as Received
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endhasrole
                                @endif
                            </td>
                            <td class="px-5 py-3">{{ $document->oed_date_received?->format('F j, Y g:i A') }}</td>
                            <td class="px-5 py-3">
                            @hasrole('oed')
                                @if ($document->oed_date_received)
                                    <form action="{{ route('documents.oed.status', $document->id) }}" method="POST">
                                        @csrf
                                        <select
                                            name="oed_status"
                                            onchange="this.form.submit()"
                                            class="text-xs rounded pl-2 pr-5 py-1 border border-gray-300
                                                {{ $document->oed_status === 'Under Review' ? 'bg-yellow-500' : '' }}
                                                {{ $document->oed_status === 'In Progress' ? 'bg-blue-500' : '' }}
                                                {{ $document->oed_status === 'For Release' ? 'bg-green-500' : '' }}
                                                {{ $document->oed_status === 'Returned' ? 'bg-red-500' : '' }}">
                                            <option value="" disabled {{ is_null($document->oed_status) ? 'selected' : '' }}>Action</option>
                                            <option value="Under Review" {{ $document->oed_status === 'Under Review' ? 'selected' : '' }}>Under Review</option>
                                            <option value="In Progress" {{ $document->oed_status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="For Release" {{ $document->oed_status === 'For Release' ? 'selected' : '' }}>For Release</option>
                                            <option value="Returned"
                                                {{ $document->oed_status === 'Returned' ? 'selected' : '' }}
                                                disabled>Returned</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            @else
                                <span class="whitespace-nowrap px-2 py-1 rounded-full text-xs text-white
                                    {{ $document->oed_status === 'Under Review' ? 'bg-yellow-500' : '' }}
                                    {{ $document->oed_status === 'In Progress' ? 'bg-blue-500' : '' }}
                                    {{ $document->oed_status === 'For Release' ? 'bg-green-500' : '' }}
                                    {{ $document->oed_status === 'Returned' ? 'bg-red-500' : '' }}">
                                    {{ $document->oed_status ?? '' }}
                                </span>
                            @endhasrole
                        </td>
                        <td class="px-5 py-3">
                            <div x-data="{ logsOpen: false, tooltip: false }" class="relative inline-block">
                                <!-- Eye Icon Button -->
                                <button
                                    @click="logsOpen = true"
                                    @mouseenter="tooltip = true"
                                    @mouseleave="tooltip = false"
                                    class="text-gray-600 hover:text-blue-600 p-1"
                                >
                                    <!-- Eye SVG -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>

                                <!-- Tooltip -->
                                <div
                                    x-show="tooltip"
                                    x-transition
                                    class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs bg-gray-800 text-white rounded whitespace-nowrap"
                                >
                                    View Logs
                                </div>

                                @include('components.documents.status-logs-modal')
                            </div>
                        </td>
                            <td class="px-5 py-3" x-data="{ editing: false, remarks: '{{ $document->oed_remarks }}' }">
                                @hasrole('oed')
                                    @if ($document->oed_date_received)
                                        <template x-if="!editing">
                                            <span @click="editing = true" class="cursor-pointer text-blue-600 hover:underline" x-text="remarks || 'Add Remarks'"></span>
                                        </template>
                                        <template x-if="editing">
                                            <div class="flex flex-col space-y-1">
                                                <textarea x-model="remarks" class="border border-gray-300 rounded p-1 text-xs resize-none"></textarea>
                                                <div class="flex items-center space-x-2">
                                                    <button @click="
                                                        fetch('{{ route('documents.oed.remarks', $document->id) }}', {
                                                            method: 'POST',
                                                            headers: {
                                                                'Content-Type': 'application/json',
                                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                            },
                                                            body: JSON.stringify({ oed_remarks: remarks })
                                                        })
                                                        .then(res => res.json())
                                                        .then(data => {
                                                            editing = false;
                                                            Toast.fire({ icon: 'success', title: 'Remarks updated' });
                                                        })
                                                        .catch(() => {
                                                            Toast.fire({ icon: 'error', title: 'Update failed' });
                                                        });
                                                    " class="bg-green-500 text-white px-2 py-0.5 text-xs rounded">Save</button>
                                                    <button @click="editing = false" class="text-xs text-gray-500">Cancel</button>
                                                </div>
                                            </div>
                                        </template>
                                    @endif
                                @else
                                    <span>{{ $document->oed_remarks }}</span>
                                @endhasrole
                            </td>
                            <td class="px-5 py-3">
                                @hasrole('records')
                                    @if ($document->oed_status === 'For Release' && is_null($document->records_date_received))
                                        <div class="relative inline-block text-left" x-data="{ open: false }">
                                            <button @click="open = !open"
                                                class="bg-white border border-gray-300 text-xs px-3 py-1 rounded hover:border-gray-400">
                                                Action
                                            </button>
                                            <div x-show="open" @click.away="open = false" x-cloak
                                                class="absolute z-10 mt-2 w-40 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                                                <form method="POST" action="{{ route('documents.records.receive', $document->id) }}">
                                                    @csrf
                                                    <button type="submit"
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        Mark as Received
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('documents.records.return', $document->id) }}">
                                                    @csrf
                                                    <button type="submit"
                                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                                        Returned to OED
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm font-bold">{{ $document->records_received }}</span>
                                    @endif
                                @else
                                    <span class="text-sm font-bold">{{ $document->records_received }}</span>
                                @endhasrole
                            </td>
                            <td class="px-5 py-3">{{ $document->records_date_received?->format('F j, Y g:i A') }}</td>
                            <td class="px-5 py-3" x-data="{ editing: false, remarks: '{{ $document->records_remarks }}' }">
                                @hasrole('records')
                                    @if ($document->records_date_received || $document->oed_status === 'Returned')
                                        <template x-if="!editing">
                                            <span @click="editing = true" class="cursor-pointer text-blue-600 hover:underline" x-text="remarks || 'Add Remarks'"></span>
                                        </template>
                                        <template x-if="editing">
                                            <div class="flex flex-col space-y-1">
                                                <textarea x-model="remarks" class="border border-gray-300 rounded p-1 text-xs resize-none"></textarea>
                                                <div class="flex items-center space-x-2">
                                                    <button @click="
                                                        fetch('{{ route('documents.records.remarks', $document->id) }}', {
                                                            method: 'POST',
                                                            headers: {
                                                                'Content-Type': 'application/json',
                                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                            },
                                                            body: JSON.stringify({ records_remarks: remarks })
                                                        })
                                                        .then(res => res.json())
                                                        .then(data => {
                                                            editing = false;
                                                            Toast.fire({ icon: 'success', title: 'Remarks updated' });
                                                        })
                                                        .catch(() => {
                                                            Toast.fire({ icon: 'error', title: 'Update failed' });
                                                        });
                                                    " class="bg-green-500 text-white px-2 py-0.5 text-xs rounded">Save</button>
                                                    <button @click="editing = false" class="text-xs text-gray-500">Cancel</button>
                                                </div>
                                            </div>
                                        </template>
                                    @endif
                                @else
                                    <span>{{ $document->records_remarks }}</span>
                                @endhasrole
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center text-gray-500 py-4">No documents found.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="12" class="px-6 py-3">
                            <div class="flex justify-end items-center space-x-4">
                                {{-- Items per page --}}
                                <form method="GET" action="{{ url()->current() }}" class="flex items-center">
                                    <label for="perPage" class="mr-2 text-sm text-gray-700">Items per page:</label>
                                    <select name="perPage" id="perPage" onchange="this.form.submit()"
                                        class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        @foreach([10, 25, 50, 100] as $size)
                                            <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>{{ $size }}</option>
                                        @endforeach
                                    </select>
                                </form>

                                {{-- Pagination --}}
                                <div>
                                    {{ $documents->links('vendor.pagination.tailwind') }}
                                </div>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-admin-layout>
