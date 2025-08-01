<x-admin-layout>
    <div x-data="{ filterStatus: 'all', open: false }">
        <!-- Cards -->
        <div class="flex gap-6">
            <div class="flex-1 min-w-[200px]">
                <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white">
                    <div class="p-3 rounded-full bg-blue-600 bg-opacity-75">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="mx-5">
                        <h4 class="text-2xl font-semibold text-gray-700">{{ $documents->count() }}</h4>
                        <div class="text-gray-500">All Documents</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Button and Modal, visible only to Records users -->
        @hasrole('records')
            <div x-data="{ open: false }" class="relative w-full sm:w-auto flex justify-start py-2">
                <button @click="open = true" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Add New Document
                </button>

                <!-- Modal -->
                @include('components.documents.document-modal')
            </div>
        @endhasrole

            <!-- Documents Table -->
            <table class="min-w-full bg-white text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase">Date Received</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase">Document #</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase">Document Type</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase">Particulars</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase">OED Level</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase">Date Received</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase">Status Timestamp</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase">Remarks OED</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase">Records Section</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase">Date Received</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($documents as $document)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-5 py-3">{{ $document->date_received?->format('Y-m-d') }}</td>
                            <td class="px-5 py-3">{{ $document->document_no }}</td>
                            <td class="px-5 py-3">{{ $document->document_type }}</td>
                            <td class="px-5 py-3">{{ $document->particulars }}</td>
                            <td class="px-5 py-3">
                                @if ($document->oed_date_received)
                                    <span class="inline-block bg-green-600 text-white text-xs px-3 py-1 rounded">Received</span>
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
                                            <select name="oed_status" onchange="this.form.submit()" class="text-xs rounded px-2 py-1 border border-gray-300">
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
                                    <span class="px-2 py-1 rounded-full text-xs text-white
                                        {{ $document->oed_status === 'Under Review' ? 'bg-green-500' : 'bg-yellow-500' }}">
                                        {{ $document->oed_status ?? '—' }}
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
                                    <span class="text-xs text-gray-600">{{ $document->records_received }}</span>
                                @endif
                            @else
                                <span class="text-xs text-gray-600">{{ $document->records_received }}</span>
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
                            <td colspan="11" class="text-center text-gray-500 py-4">No documents found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
