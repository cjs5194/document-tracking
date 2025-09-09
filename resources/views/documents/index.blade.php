<x-admin-layout>
    @php
        $prefix = Auth::user()->hasRole('admin') ? 'admin.' : '';
    @endphp
    <div id="documentTable" x-data="{ filterStatus: 'all', open: false, filterOpen: false, logsOpen: false }">
        {{-- Start Cards --}}
        @include('documents.partials.status-cards')

        <!-- Documents Table -->
        <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 mt-4">
            <table class="min-w-full bg-white text-sm border-collapse">
                <thead>
                    <!-- Wrap in a single Alpine scope so both modals share the same state -->
                    <div x-data="{ open: false, filterOpen: false }">
                        <tr>
                            <th colspan="10" class="text-left p-2">
                                <div class="relative w-full sm:w-auto flex justify-start py-2">
                                    @hasrole('records')
                                    <button
                                        @click="$dispatch('open-document-modal', { mode: 'create' })"
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
                                    <a href="{{ route($prefix . 'documents.index') }}"
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
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200 whitespace-nowrap">Date Received</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200 whitespace-nowrap">Document #</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200">Doc Type</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200">Particulars</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200 whitespace-nowrap">FORWARDED to OED</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200 whitespace-nowrap">OED Level</th>
                        {{-- <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200 whitespace-nowrap">Date Received</th> --}}
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200 whitespace-nowrap">Status</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200 whitespace-nowrap">Logs</th>
                        {{-- <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200 whitespace-nowrap">Remarks OED</th> --}}
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200 whitespace-nowrap">FWD to RECORDS</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200 whitespace-nowrap">Records</th>
                        {{-- <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200 whitespace-nowrap">Date Received</th> --}}
                        @hasanyrole('admin|records|oed')
                            <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200 whitespace-nowrap">Remarks</th>
                        @endhasanyrole
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200 whitespace-nowrap">Completed</th>
                        @hasanyrole('admin|records')
                            <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase border-b border-gray-200 whitespace-nowrap">Action</th>
                        @endhasanyrole
                    </tr>
                </thead>
                <tbody>
                    @forelse ($documents as $document)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm text-gray-600 whitespace-nowrap">{{ $document->date_received?->format('m/d/Y h:i A') }}</td>
                            <td class="px-5 py-3 whitespace-nowrap">{{ $document->document_no }}</td>
                            <td class="px-5 py-3">{{ $document->document_type }}</td>
                            <td class="px-5 py-3 w-64">{{ $document->particulars }}</td>
                            @include('documents.partials.forward-to-oed', ['document' => $document])
                            @include('documents.partials.oed-receive', ['document' => $document])
                            {{-- <td class="px-5 py-3">{{ $document->oed_date_received?->format('m/d/Y h:i A') }}</td> --}}
                            @include('documents.partials.oed-status', ['document' => $document])
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
                            {{-- <td class="px-5 py-3" x-data="{ editing: false, remarks: '{{ $document->oed_remarks }}' }">
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
                            </td> --}}
                            <td class="px-5 py-3">
                                @include('documents.partials.forward-to-records', ['document' => $document])
                            </td>
                            <td class="px-5 py-3">
                                @include('documents.partials.records-receive', ['document' => $document])
                            </td>
                            {{-- <td class="px-5 py-3">{{ $document->records_date_received?->format('m/d/Y h:i A') }}</td> --}}
                            @include('documents.partials.records-remarks', ['document' => $document])
                            @include('documents.partials.mark-completed', ['document' => $document])
                            @hasanyrole('admin|records')
                            <td>
                            <div class="flex items-center justify-center gap-1 mr-2">
                                <!-- View Button -->
                                <div class="relative" x-data="{ tooltip: false }">
                                    <button
                                    @click="$dispatch('open-view-modal', {
                                        doc: {
                                            date_received: '{{ optional($document->date_received)->format('m/d/Y h:i A') }}',
                                            document_no: @js($document->document_no),
                                            document_type: @js($document->document_type),
                                            particulars: @js($document->particulars),
                                            users: @js($document->users->pluck('name')) // from pivot
                                        }
                                    })"
                                    @mouseenter="tooltip = true"
                                    @mouseleave="tooltip = false"
                                    class="flex items-center justify-center p-2 rounded-full hover:scale-105 transition-transform"
                                    style="background-color:#1f4b82;"
                                >
                                    <!-- keep your eye icon EXACTLY as-is -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943
                                            9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                    <div x-show="tooltip" x-transition
                                        class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1
                                            text-xs bg-gray-800 text-white rounded whitespace-nowrap">
                                        View
                                    </div>
                                </div>

                                @include('components.documents.view-modal')

                                <!-- Update Button -->
                                <div class="relative" x-data="{ tooltip: false }">
                                    <button
                                        @click="$dispatch('open-document-modal', {
                                            mode: 'update',
                                            action: '{{ route('documents.update', $document->id) }}',
                                            data: {
                                                date_received: '{{ $document->date_received }}',
                                                document_no: '{{ $document->document_no }}',
                                                document_type: '{{ $document->document_type }}',
                                                particulars: '{{ $document->particulars }}',
                                                users: @json($document->users->pluck('id'))
                                            }
                                        })"
                                        @mouseenter="tooltip = true"
                                        @mouseleave="tooltip = false"
                                        class="flex items-center justify-center p-2 rounded-full hover:scale-105 transition-transform"
                                        style="background-color:#1f4b82;"
                                    >
                                        <!-- Document Update Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0
                                                012-2h5l5 5v11a2 2 0 01-2 2z"/>
                                        </svg>
                                    </button>

                                    <div x-show="tooltip" x-transition
                                        class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1
                                            text-xs bg-gray-800 text-white rounded whitespace-nowrap">
                                        Update
                                    </div>
                                </div>

                                <!-- Delete Button -->
                                <div class="relative" x-data="{ tooltip: false }">
                                    <button
                                        @click="$dispatch('open-delete-modal', '{{ route('documents.destroy', $document->id) }}')"
                                        @mouseenter="tooltip = true"
                                        @mouseleave="tooltip = false"
                                        class="flex items-center justify-center p-2 rounded-full hover:scale-105 transition-transform"
                                        style="background-color:#1f4b82;"
                                    >
                                        <!-- Trash Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0
                                                01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 3h4a1
                                                1 0 011 1v1H9V4a1 1 0 011-1z"/>
                                        </svg>
                                    </button>

                                    <!-- Tooltip -->
                                    <div x-show="tooltip" x-transition
                                        class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1
                                            text-xs bg-gray-800 text-white rounded whitespace-nowrap">
                                        Delete
                                    </div>
                                </div>
                            </div>
                            </td>
                            @endhasanyrole
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center text-gray-500 py-4">No documents found.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="16" class="px-6 py-3">
                            <div class="flex justify-end items-center space-x-4 w-full">
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

<script>
    if (window.location.pathname === "/documents") {
        setInterval(() => {
            window.location.reload();
        }, 120000);
    }
</script>

