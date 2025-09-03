@props(['document', 'divisions'])

<!-- Document Submission Modal -->
<div
    x-data="{
        open: @json($errors->any()), selectAll: false, divisionSelect: {},
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
    class="fixed inset-0 z-50 flex items-start justify-center backdrop-blur-sm bg-white/30 pt-10"
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
        <div class="p-4 flex-1 overflow-y-visible">
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
                        @error('date_received')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Document No. -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Document No.</label>
                        <input type="text" name="document_no"
                               class="w-full border rounded p-2 text-sm"
                               required value="{{ old('document_no') }}">
                        @error('document_no')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Document Type -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Document Type</label>
                        <select name="document_type" class="w-full border rounded p-2 text-sm" required>
                            <option value="" disabled {{ old('document_type') ? '' : 'selected' }}>Select type</option>
                            <option value="TOR" {{ old('document_type')=='TOR' ? 'selected' : '' }}>TOR</option>
                            <option value="Memorandum" {{ old('document_type')=='Memorandum' ? 'selected' : '' }}>Memorandum</option>
                            <option value="Endorsement" {{ old('document_type')=='Endorsement' ? 'selected' : '' }}>Endorsement</option>
                            <option value="Communications" {{ old('document_type')=='Communications' ? 'selected' : '' }}>Communications</option>
                            <option value="Purchase Request" {{ old('document_type')=='Purchase Request' ? 'selected' : '' }}>Purchase Request</option>
                            <option value="Purchase Order" {{ old('document_type')=='Purchase Order' ? 'selected' : '' }}>Purchase Order</option>
                            <option value="Resolution" {{ old('document_type')=='Resolution' ? 'selected' : '' }}>Resolution</option>
                            <option value="Voucher" {{ old('document_type')=='Voucher' ? 'selected' : '' }}>Voucher</option>
                            <option value="Travel Order" {{ old('document_type')=='Travel Order' ? 'selected' : '' }}>Travel Order</option>
                            <option value="Payroll" {{ old('document_type')=='Payroll' ? 'selected' : '' }}>Payroll</option>
                            <option value="Certificate" {{ old('document_type')=='Certificate' ? 'selected' : '' }}>Certificate</option>
                        </select>
                        @error('document_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Particulars -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Particulars</label>
                        <input type="text" name="particulars" class="w-full border rounded p-2 text-sm" required value="{{ old('particulars') }}">
                        @error('particulars')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Send To Dropdown -->
                    <div x-data="{
                            openDropdown: false,
                            selectAll: false,
                            divisionSelect: {},
                            selectedCount: 0,
                            updateCount() {
                                this.selectedCount = $root.querySelectorAll('input.user-checkbox:checked').length;
                            }
                        }"
                        class="relative"
                    >
                        <label class="block text-sm font-medium mb-1">Send To</label>
                        <button type="button"
                                @click="openDropdown = !openDropdown"
                                class="w-full border rounded p-2 text-sm">
                            <span class="font-medium text-gray-700" x-text="selectedCount > 0 ? `${selectedCount} users selected` : 'No user selected'"></span>
                        </button>

                        <!-- Hidden required input to trigger validation -->
                        <input type="hidden" name="users_required" x-bind:disabled="selectedCount > 0" required>

                        @error('users')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <!-- Dropdown Panel -->
                        <div x-show="openDropdown" x-transition
                            @click.outside="openDropdown = false"
                            class="absolute left-0 top-full z-50 mt-1 w-full max-h-[60vh] bg-white border rounded shadow-lg p-3 space-y-3 text-sm overflow-y-auto">

                            <!-- Global Select All -->
                            <div class="flex items-center border-b pb-2">
                                <input type="checkbox" id="selectAll" x-model="selectAll"
                                    @change="
                                        Object.keys(divisionSelect).forEach(key => divisionSelect[key] = selectAll);
                                        $root.querySelectorAll('input.user-checkbox').forEach(cb => cb.checked = selectAll);
                                        updateCount();
                                    "
                                    class="mr-2">
                                <label for="selectAll" class="font-medium text-gray-700">Select All</label>
                            </div>

                            <!-- Divisions & Users -->
                            @foreach($divisions as $division)
                                <div class="mb-2 border-b pb-2" x-ref="division{{ $division->id }}">
                                    <!-- Division checkbox -->
                                    <div class="flex items-center">
                                        <input type="checkbox"
                                            x-model="divisionSelect[{{ $division->id }}]"
                                            @change="
                                                $refs['division{{ $division->id }}'].querySelectorAll('input.user-checkbox').forEach(cb => cb.checked = divisionSelect[{{ $division->id }}]);
                                                const allDivisionsChecked = Object.values(divisionSelect).every(v => v);
                                                const allUsersChecked = Array.from($root.querySelectorAll('input.user-checkbox')).every(cb => cb.checked);
                                                selectAll = allDivisionsChecked && allUsersChecked;
                                                updateCount();
                                            "
                                            class="mr-2"
                                        >
                                        <span class="font-semibold text-gray-700">{{ $division->name }}</span>
                                    </div>

                                    <!-- Users -->
                                    <div class="ml-4 mt-1 space-y-1">
                                        @foreach($division->users as $user)
                                            <div class="flex items-center">
                                                <input type="checkbox" name="users[]" value="{{ $user->id }}"
                                                    class="user-checkbox mr-2"
                                                    @change="
                                                        divisionSelect[{{ $division->id }}] =
                                                            Array.from($refs['division{{ $division->id }}'].querySelectorAll('input.user-checkbox')).every(cb => cb.checked);
                                                        const allDivisionsChecked = Object.values(divisionSelect).every(v => v);
                                                        const allUsersChecked = Array.from($root.querySelectorAll('input.user-checkbox')).every(cb => cb.checked);
                                                        selectAll = allDivisionsChecked && allUsersChecked;
                                                        updateCount();
                                                    "
                                                    {{ (is_array(old('users')) && in_array($user->id, old('users'))) ? 'checked' : '' }}
                                                >
                                                <label class="text-gray-700">{{ $user->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

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
