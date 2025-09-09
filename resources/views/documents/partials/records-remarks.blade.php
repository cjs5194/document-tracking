@props(['document'])

@hasanyrole('admin|records|oed')
<td class="px-5 py-3" x-data="{ editing: false, remarks: '{{ $document->records_remarks }}' }">
    @hasrole('records')
        <!-- Display Mode -->
        <template x-if="!editing">
            <span
                @click="editing = true"
                class="cursor-pointer text-blue-600 hover:underline"
                x-text="remarks || 'Add Remarks'">
            </span>
        </template>

        <!-- Edit Mode -->
        <template x-if="editing">
            <div class="flex flex-col space-y-1">
                <textarea
                    x-model="remarks"
                    class="border border-gray-300 rounded p-1 text-xs resize-none">
                </textarea>

                <div class="flex items-center space-x-2">
                    <!-- Save Button -->
                    <button
                        @click="
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
                                Toast.fire({ icon: 'success', title: 'Remarks updated successfully' });
                            })
                            .catch(() => {
                                Toast.fire({ icon: 'error', title: 'Update failed' });
                            });
                        "
                        class="bg-green-500 text-white px-2 py-0.5 text-xs rounded">
                        Save
                    </button>

                    <!-- Cancel Button -->
                    <button @click="editing = false" class="text-xs text-gray-500">
                        Cancel
                    </button>
                </div>
            </div>
        </template>
    @else
        <!-- Read-only for non-records roles -->
        <span>{{ $document->records_remarks }}</span>
    @endhasrole
</td>
@endhasanyrole
