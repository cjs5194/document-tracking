@props(['document', 'divisions'])

<!-- Document Submission Modal -->
<div
    x-data="{
        open: false, selectAll: false, divisionSelect: {},
        now: '', timer: null,
        updateTime() {
            const d = new Date();
            const pad = (n) => n.toString().padStart(2, '0');
            this.now = d.getFullYear() + '-' +
                pad(d.getMonth() + 1) + '-' +
                pad(d.getDate()) + 'T' +
                pad(d.getHours()) + ':' +
                pad(d.getMinutes());
        },
        startTimer() { this.updateTime(); this.timer = setInterval(() => this.updateTime(), 1000); },
        stopTimer() { clearInterval(this.timer); this.timer = null; }
    }"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm bg-white/30"
    @keydown.escape.window="open = false; stopTimer()"
    @open-modal.window="
        if ($event.detail === 'document-submission') {
            open = true;
            startTimer();
        }
    "
>
    <!-- Modal Container -->
    <div
        class="bg-white rounded-lg shadow-lg w-full max-w-2xl max-h-[90vh] flex flex-col border border-gray-400"
        @click.stop
    >
        <!-- Header -->
        <div class="p-4 border-b shrink-0">
            <h2 class="text-lg font-semibold">Add Records</h2>
        </div>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto p-4">
            <form id="documentForm"
                  action="{{ auth()->user()->hasRole('admin') ? route('admin.documents.store') : route('documents.store') }}"
                  method="POST"
                  class="space-y-4"
            >
                @csrf
                <input type="hidden" name="id" value="{{ auth()->user()->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <!-- Date Received -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Date Received</label>
                        <input type="datetime-local" name="date_received"
                               x-model="now"
                               class="w-full border rounded p-2 text-sm" required>
                    </div>

                    <!-- Document No. -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Document No.</label>
                        <input type="text" name="document_no" class="w-full border rounded p-2 text-sm" required>
                    </div>

                    <!-- Document Type -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Document Type</label>
                        <select name="document_type" class="w-full border rounded p-2 text-sm" required>
                            <option value="" disabled selected>Select type</option>
                            <option value="TOR">TOR</option>
                            <!-- your options -->
                        </select>
                    </div>

                    <!-- Particulars -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Particulars</label>
                        <input type="text" name="particulars" class="w-full border rounded p-2 text-sm" required>
                    </div>
                </div>

                <!-- Global Select All -->
                <div class="flex items-center">
                    <input type="checkbox" id="selectAll" x-model="selectAll"
                           @change="$el.closest('form').querySelectorAll('input.user-checkbox').forEach(cb => cb.checked = selectAll);
                                    Object.keys(divisionSelect).forEach(key => divisionSelect[key] = selectAll)"
                           class="mr-2">
                    <label for="selectAll" class="font-medium text-gray-700">Select All</label>
                </div>

                <!-- Divisions & Users -->
                @foreach($divisions as $division)
                    <div class="mb-3 border-b pb-2" x-ref="division{{ $division->id }}">
                        <div class="flex items-center justify-between">
                            <div class="font-semibold text-gray-700">{{ $division->name }}</div>
                            <div>
                                <input type="checkbox" x-model="divisionSelect[{{ $division->id }}]"
                                       @change="$refs['division{{ $division->id }}'].querySelectorAll('input.user-checkbox').forEach(cb => cb.checked = divisionSelect[{{ $division->id }}]);
                                                selectAll = Object.values(divisionSelect).every(v => v)"
                                       class="mr-2">
                                <label class="text-gray-700 text-sm">Select All</label>
                            </div>
                        </div>

                        <div class="ml-4 mt-1 space-y-1">
                            @foreach($division->users as $user)
                                <div class="flex items-center">
                                    <input type="checkbox" name="users[]" value="{{ $user->id }}"
                                           class="user-checkbox mr-2"
                                           @change="divisionSelect[{{ $division->id }}] = Array.from($refs['division{{ $division->id }}'].querySelectorAll('input.user-checkbox')).every(cb => cb.checked);
                                                    selectAll = Object.values(divisionSelect).every(v => v);">
                                    <label class="text-gray-700">{{ $user->name }} ({{ $user->email }})</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                <!-- Footer -->
        <div class="p-4 border-t flex justify-end gap-2 shrink-0">
            <button type="button" @click="open = false; stopTimer()"
                    class="bg-gray-300 text-gray-800 px-4 py-2 text-sm rounded hover:bg-gray-400">
                Cancel
            </button>
            <button type="submit" form="documentForm"
                    class="bg-blue-600 text-white px-4 py-2 text-sm rounded hover:bg-blue-700">
                Submit
            </button>
        </div>
            </form>
        </div>
    </div>
</div>
