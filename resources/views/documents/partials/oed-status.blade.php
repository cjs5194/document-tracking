<td class="px-5 py-3">
    @hasrole('oed')
        @if ($document->oed_date_received)
            <div x-data="{ status: '{{ $document->oed_status }}', loading: false }">
                <template x-if="status === 'Released'">
                    <!-- ✅ If Released, just show badge (no dropdown) -->
                    <span class="whitespace-nowrap px-2 py-1 rounded-full text-xs text-white bg-green-500">
                        Released
                    </span>
                </template>

                <template x-if="status !== 'Released'">
                    <!-- ✅ Otherwise, show dropdown -->
                    <select
                        x-model="status"
                        @change="
                            loading = true;
                            fetch('{{ route('documents.oed.status', $document->id) }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ oed_status: status })
                            })
                            .then(res => {
                                if (res.ok) {
                                    Toast.fire({ icon: 'success', title: 'Status updated to ' + status });
                                    setTimeout(() => window.location.reload(), 1500);
                                } else {
                                    Toast.fire({ icon: 'error', title: 'Failed to update status' });
                                }
                            })
                            .catch(() => {
                                Toast.fire({ icon: 'error', title: 'Request failed' });
                            })
                            .finally(() => loading = false);
                        "
                        class="text-xs rounded pl-2 pr-5 py-1 border border-gray-300"
                        :class="{
                            'bg-yellow-500 text-white': status === 'Under Review',
                            'bg-blue-500 text-white': status === 'In Progress',
                            'bg-green-500 text-white': status === 'For Release',
                            'bg-red-500 text-white': status === 'Returned'
                        }"
                        :disabled="loading"
                    >
                        <!-- ✅ All clickable except Returned -->
                        <option value="Under Review">Under Review</option>
                        <option value="In Progress">In Progress</option>
                        <option value="For Release">For Release</option>
                        <option value="Returned" disabled>Returned</option>
                    </select>
                </template>
            </div>
        @else
            <span class="text-xs text-gray-400">—</span>
        @endif
    @else
        <span class="whitespace-nowrap px-2 py-1 rounded-full text-xs text-white
            {{ $document->oed_status === 'Under Review' ? 'bg-yellow-500' : '' }}
            {{ $document->oed_status === 'In Progress' ? 'bg-blue-500' : '' }}
            {{ $document->oed_status === 'For Release' || $document->oed_status === 'Released' ? 'bg-green-500' : '' }}
            {{ $document->oed_status === 'Returned' ? 'bg-red-500' : '' }}">
            {{ $document->oed_status ?? '' }}
        </span>
    @endhasrole
</td>
