@props(['document'])

<td class="px-5 py-3 text-xs text-gray-700"
    x-data="{
        open: false,
        loading: false,
        completed: {{ $document->completed_at ? 'true' : 'false' }},
        completedAt: '{{ $document->completed_at ? $document->completed_at->format('m/d/Y h:i A') : '' }}'
    }">

    <template x-if="completed">
        <!-- ✅ Completed Icon + Tooltip -->
        <div x-data="{ show: false, x: null, y: null }" class="relative inline-flex items-center">
            <svg
                x-ref="icon"
                @mouseenter="
                    const r = $refs.icon.getBoundingClientRect();
                    x = r.left;
                    y = r.top + r.height / 2;
                    show = true;
                "
                @mouseleave="show = false"
                xmlns="http://www.w3.org/2000/svg"
                class="w-6 h-6 text-green-600 inline-block cursor-pointer"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>

            <!-- Tooltip -->
            <template x-teleport="body">
                <template x-if="show && x !== null && y !== null">
                    <div
                        x-transition
                        class="fixed z-50 px-2 py-1 text-xs bg-gray-800 text-white rounded shadow pointer-events-none whitespace-nowrap"
                        :style="`top:${y}px; left:${x}px; transform: translate(calc(-100% - 8px), -50%);`"
                        x-text="'Completed at ' + completedAt"
                    ></div>
                </template>
            </template>
        </div>
    </template>

    <template x-if="!completed">
        @role('records')
            @if (!is_null($document->records_received))
                <div class="relative inline-block text-left">
                    <button @click="open = !open"
                        class="bg-white border border-gray-300 text-xs px-3 py-1 rounded hover:border-gray-400">
                        Action
                    </button>

                    <div x-show="open" @click.away="open = false" x-cloak
                        class="absolute z-10 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                        <button
                            @click="
                                loading = true;
                                fetch('{{ route('documents.markCompleted', $document->id) }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ _method: 'PATCH' })
                                })
                                .then(res => {
                                    if (res.ok) {
                                        completed = true;
                                        completedAt = new Date().toLocaleString('en-US', {
                                            month: '2-digit', day: '2-digit', year: 'numeric',
                                            hour: '2-digit', minute: '2-digit', hour12: true
                                        });
                                        Toast.fire({ icon: 'success', title: 'Document marked as completed' });
                                        open = false;
                                    } else {
                                        Toast.fire({ icon: 'error', title: 'Failed to mark document as completed' });
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
                            Mark as Completed
                        </button>
                    </div>
                </div>
            @endif
        @endrole
    </template>
</td>
