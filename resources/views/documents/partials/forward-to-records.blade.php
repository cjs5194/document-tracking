<div>
    @if ($document->forwarded_to_records)
        <span class="text-sm text-gray-600 whitespace-nowrap">
            {{ $document->forwarded_to_records->format('m/d/Y h:i A') }}
        </span>
    @else
        @hasrole('oed')
            @if ($document->oed_status === 'For Release')
                <div class="relative inline-block text-left" x-data="{ open: false, loading: false }">
                    <button @click="open = !open"
                        class="bg-white border border-gray-300 text-xs px-3 py-1 rounded hover:border-gray-400">
                        Action
                    </button>

                    <div x-show="open" @click.away="open = false" x-cloak
                        class="absolute z-10 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                        <button
                            @click="
                                loading = true;
                                fetch('{{ route('documents.forwardToRecords', $document->id) }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    },
                                })
                                .then(res => {
                                    if (res.ok) {
                                        Toast.fire({ icon: 'success', title: 'Document forwarded to Records' });
                                        setTimeout(() => window.location.reload(), 1500);
                                    } else {
                                        Toast.fire({ icon: 'error', title: 'Failed to forward document' });
                                    }
                                })
                                .catch(() => {
                                    Toast.fire({ icon: 'error', title: 'Request failed' });
                                })
                                .finally(() => loading = false);
                            "
                            :disabled="loading"
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        >
                            Forward to Records
                        </button>
                    </div>
                </div>
            @endif
        @endhasrole
    @endif
</div>
