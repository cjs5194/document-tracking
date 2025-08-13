<x-admin-layout>
    <div x-data="{ filterStatus: 'all', open: false }">
        {{-- <div class="flex flex-wrap gap-6">
            <!-- All Tickets Card -->
            <div
                class="flex-1 min-w-[200px] cursor-pointer transform transition duration-300 hover:scale-105 hover:shadow-lg">
                <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white transition duration-300">
                    <div class="p-3 rounded-full bg-blue-600 bg-opacity-75">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="mx-5">
                        <h4 class="text-2xl font-semibold text-gray-700">
                            {{ $documents->count() }}</h4>
                        <div class="text-gray-500">All Tickets</div>
                    </div>
                </div>
            </div>

            <!-- Pending Tickets Card -->
            <div
                class="flex-1 min-w-[200px] cursor-pointer transform transition duration-300 hover:scale-105 hover:shadow-lg">
                <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white transition duration-300">
                    <div class="p-3 rounded-full bg-yellow-600 bg-opacity-75">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M12 8v4l3 3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="mx-5">
                        <h4 class="text-2xl font-semibold text-gray-700">5</h4>
                        <div class="text-gray-500">Pending Tickets</div>
                    </div>
                </div>
            </div>

            <!-- Approved Tickets Card -->
            <div
                class="flex-1 min-w-[200px] cursor-pointer transform transition duration-300 hover:scale-105 hover:shadow-lg">
                <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white transition duration-300">
                    <div class="p-3 rounded-full bg-green-600 bg-opacity-75">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="mx-5">
                        <h4 class="text-2xl font-semibold text-gray-700">15</h4>
                        <div class="text-gray-500">Approved Tickets</div>
                    </div>
                </div>
            </div>

            <!-- Declined Tickets Card -->
            <div
                class="flex-1 min-w-[200px] cursor-pointer transform transition duration-300 hover:scale-105 hover:shadow-lg">
                <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white transition duration-300">
                    <div class="p-3 rounded-full bg-red-600 bg-opacity-75">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="mx-5">
                        <h4 class="text-2xl font-semibold text-gray-700">56</h4>
                        <div class="text-gray-500">Declined Tickets</div>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Documents Table -->
        <div class="overflow-hidden rounded-lg shadow-md border border-gray-200 mt-4">
            <table class="min-w-full bg-white text-sm border-collapse">
                <thead>
                    <tr>
                        <th colspan="10" class="text-left p-2">
                            @hasrole('records')
                                <div x-data="{ open: false }" class="relative w-full sm:w-auto flex justify-start py-2">
                                    <button @click="open = true" class="text-gray-800 px-4 py-2 rounded border border-gray-400 hover:border-gray-600">
                                        + Add New Document
                                    </button>

                                    <!-- Modal -->
                                    @include('components.documents.document-modal')
                                </div>
                            @endhasrole
                        </th>
                    </tr>
                    <tr class="bg-gray-100">
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200">Date Received</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200 whitespace-nowrap">Document #</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200 whitespace-nowrap">Document Type</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200">Particulars</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200 whitespace-nowrap">OED Level</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200">Date Received</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200">Status</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200">Status Timestamp</th>
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
                                                class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700">
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
                                                    {{ $document->oed_status === 'In Progress' ? 'bg-green-500' : '' }}
                                                    {{ $document->oed_status === 'For Release' ? 'bg-blue-500' : '' }}">
                                                <option value="" disabled {{ is_null($document->oed_status) ? 'selected' : '' }}>Action</option>
                                                <option value="Under Review" {{ $document->oed_status === 'Under Review' ? 'selected' : '' }}>Under Review</option>
                                                <option value="In Progress" {{ $document->oed_status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                <option value="For Release" {{ $document->oed_status === 'For Release' ? 'selected' : '' }}>For Release</option>
                                            </select>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                @else
                                    <span class="whitespace-nowrap px-2 py-1 rounded-full text-xs text-white
                                        {{ $document->oed_status === 'Under Review' ? 'bg-yellow-500' : '' }}
                                        {{ $document->oed_status === 'In Progress' ? 'bg-green-500' : '' }}
                                        {{ $document->oed_status === 'For Release' ? 'bg-blue-500' : '' }}">
                                        {{ $document->oed_status ?? '' }}
                                    </span>
                                @endhasrole
                            </td>
                            <td class="px-5 py-3 text-xs text-gray-700 leading-5 space-y-1">
                                @if ($document->under_review_at)
                                    <div><strong>Under Review:</strong> {{ $document->under_review_at->format('F j, Y g:i A') }}</div>
                                @endif
                                @if ($document->in_progress_at)
                                    <div><strong>In Progress:</strong> {{ $document->in_progress_at->format('F j, Y g:i A') }}</div>
                                @endif
                                @if ($document->for_release_at)
                                    <div><strong>For Release:</strong> {{ $document->for_release_at->format('F j, Y g:i A') }}</div>
                                @endif
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
                                                class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700">
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
                                    @if ($document->records_date_received)
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
