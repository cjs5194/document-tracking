@props(['document'])

<td class="px-4 py-2">
    @if(!$document->forwarded_to_oed)
        @role('records')
            <div class="relative inline-block text-left" x-data="{ open: false }">
                <button @click="open = !open"
                    class="bg-white border border-gray-300 text-xs px-3 py-1 rounded hover:border-gray-400">
                    Action
                </button>
                <div x-show="open" @click.away="open = false" x-cloak
                    class="absolute z-10 mt-2 w-40 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">

                    <button
                        @click="
                            fetch('{{ route('documents.forwarded_to_oed', $document->id) }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(res => {
                                if (res.ok) {
                                    Toast.fire({ icon: 'success', title: 'Document forwarded to OED' });
                                    open = false;
                                    setTimeout(() => window.location.reload(), 1500);
                                } else {
                                    Toast.fire({ icon: 'error', title: 'Failed to forward document' });
                                }
                            })
                            .catch(() => {
                                Toast.fire({ icon: 'error', title: 'Failed to forward document' });
                            });
                        "
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    >
                        Forwarded to OED
                    </button>
                </div>
            </div>
        @endrole
    @else
        <span class="text-sm text-gray-600 whitespace-nowrap">
            {{ \Carbon\Carbon::parse($document->forwarded_to_oed)->format('m/d/Y h:i A') }}
        </span>
    @endif
</td>
