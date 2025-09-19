@props(['document'])

@hasanyrole('admin|records')
<td>
    <div class="flex items-center justify-center mr-2">
        <!-- View Button -->
        <div class="relative" x-data="{ tooltip: false }">
            <button
                @click="$dispatch('open-view-modal', {
                    doc: {
                        date_received: '{{ optional($document->date_received)->format('m/d/Y h:i A') }}',
                        document_no: @js($document->document_no),
                        document_type: @js($document->document_type),
                        particulars: @js($document->particulars),
                        forwarded_to_records: '{{ optional($document->forwarded_to_records)->format('m/d/Y h:i A') }}',
                        users: @js($document->users->pluck('name')) // from pivot
                    }
                })"
                @mouseenter="tooltip = true"
                @mouseleave="tooltip = false"
                class="flex items-center justify-center p-2 rounded-full hover:scale-105 transition-transform"
                style="background-color:#1f4b82;"
            >
                <!-- Eye Icon -->
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
            <div x-show="tooltip" x-transition
                class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1
                    text-xs bg-gray-800 text-white rounded whitespace-nowrap">
                Delete
            </div>
        </div>
    </div>
</td>
@endhasanyrole
