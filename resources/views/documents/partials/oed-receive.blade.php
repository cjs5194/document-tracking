<td class="px-5 py-3">
    @if ($document->oed_date_received)
        <!-- ✅ Show tooltip with "Received at" info -->
        <div x-data="{ show: false, x: null, y: null }" class="relative inline-flex items-center">
            <span
                x-ref="text"
                @mouseenter="
                    const r = $refs.text.getBoundingClientRect();
                    x = r.left;
                    y = r.top + r.height / 2;
                    show = true;
                "
                @mouseleave="show = false"
                class="text-sm font-bold cursor-pointer"
            >
                Received
            </span>

            <!-- Tooltip -->
            <template x-teleport="body">
                <template x-if="show && x !== null && y !== null">
                    <div
                        x-transition
                        class="fixed z-50 px-2 py-1 text-xs bg-gray-800 text-white rounded shadow pointer-events-none whitespace-nowrap"
                        :style="`top:${y}px; left:${x}px; transform: translate(calc(-100% - 8px), -50%);`"
                    >
                        Received at {{ $document->oed_date_received->format('m/d/Y h:i A') }}
                    </div>
                </template>
            </template>
        </div>
    @else
        @hasrole('oed')
            @if ($document->forwarded_to_oed)
                <div class="relative inline-block text-left" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="bg-white border border-gray-300 text-xs px-3 py-1 rounded hover:border-gray-400">
                        Action
                    </button>

                    <!-- ✅ Intercept with Alpine fetch + Toast -->
                    <div x-show="open" @click.away="open = false" x-cloak
                        class="absolute z-10 mt-2 w-32 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                        <button
                            @click="
                                fetch('{{ route('documents.oed.receive.single', $document->id) }}', {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                })
                                .then(res => {
                                    if (res.ok) {
                                        Toast.fire({ icon: 'success', title: 'Document marked as received' });
                                        open = false;
                                        setTimeout(() => window.location.reload(), 1500);
                                    } else {
                                        Toast.fire({ icon: 'error', title: 'Failed to mark as received' });
                                    }
                                })
                                .catch(() => {
                                    Toast.fire({ icon: 'error', title: 'Request failed' });
                                });
                            "
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        >
                            Mark as Received
                        </button>
                    </div>
                </div>
            @endif
        @endhasrole
    @endif
</td>
