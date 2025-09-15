@props(['document','divisions'])

<!-- Document Modal -->
<div
    x-data="{
        open: false,
        isUpdate: false,
        formAction: '{{ auth()->user()->hasRole('admin') ? route('admin.documents.store') : route('documents.store') }}',
        now: '',
        timer: null,
        errors: {},

        // Send To dropdown state
        dropdown: {
            open: false,
            selectAll: false,
            divisionSelect: {},
            updateCount() {
                this.selectedCount = $root.querySelectorAll('input.user-checkbox:checked').length;
            },
            selectedCount: 0
        },

        getNow() {
            const d = new Date();
            const pad = n => n.toString().padStart(2, '0');
            return d.getFullYear() + '-' +
                pad(d.getMonth() + 1) + '-' +
                pad(d.getDate()) + 'T' +
                pad(d.getHours()) + ':' +
                pad(d.getMinutes());
        },
        updateTime() { this.now = this.getNow(); },

        startTimer() {
            this.updateTime();
            this.timer = setInterval(() => this.updateTime(), 1000);
        },
        stopTimer() {
            clearInterval(this.timer);
            this.timer = null;
        },

        startCreate() {
            this.isUpdate = false;
            this.formAction = '{{ auth()->user()->hasRole('admin') ? route('admin.documents.store') : route('documents.store') }}';
            this.updateTime();
            this.clearFields();
            this.open = true;
            this.startTimer();
        },

        startUpdate(updateUrl, data) {
            this.isUpdate = true;
            this.formAction = updateUrl;
            this.now = data.date_received ?? this.getNow();
            this.fillFields(data);
            this.open = true;
            this.stopTimer();
        },

        clearFields() {
            $refs.document_no.value = '';
            $refs.document_type.value = '';
            $refs.particulars.value = '';
            this.clearUsers();
        },

        fillFields(data) {
            $refs.document_no.value = data.document_no ?? '';
            $refs.document_type.value = data.document_type ?? '';
            $refs.particulars.value = data.particulars ?? '';
            this.clearUsers();
            this.checkUsers(data.users ?? []);
        },

        clearUsers() {
            $root.querySelectorAll('input.user-checkbox').forEach(cb => cb.checked = false);
            Object.keys(this.dropdown.divisionSelect).forEach(key => this.dropdown.divisionSelect[key] = false);
            this.dropdown.selectAll = false;
            this.dropdown.updateCount();
        },

        checkUsers(userIds) {
            $root.querySelectorAll('input.user-checkbox').forEach(cb => {
                cb.checked = userIds.includes(parseInt(cb.value));
            });

            @foreach($divisions as $division)
            this.dropdown.divisionSelect[{{ $division->id }}] = Array.from($refs['division{{ $division->id }}'].querySelectorAll('input.user-checkbox')).every(cb => cb.checked);
            @endforeach

            this.dropdown.selectAll = Array.from($root.querySelectorAll('input.user-checkbox')).every(cb => cb.checked);
            this.dropdown.updateCount();
        }
    }"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-start justify-center backdrop-blur-sm bg-white/30 pt-10"
    @keydown.escape.window="open = false; stopTimer()"
    @open-document-modal.window="
        if ($event.detail.mode === 'create') startCreate();
        if ($event.detail.mode === 'update') startUpdate($event.detail.action, $event.detail.data);
    "
>
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl max-h-[90vh] flex flex-col border border-gray-400"
         @click.stop>
        <!-- Header -->
        <div class="p-4 border-b shrink-0">
            <h2 class="text-lg font-semibold" x-text="isUpdate ? 'Update Document' : 'Add Document'"></h2>
        </div>

        <!-- Content -->
        <div class="p-4 flex-1 overflow-y-visible">
            <form x-ref="documentForm"
                  :action="formAction"
                  method="POST"
                  @submit.prevent="
                    let form = $refs.documentForm;
                    if (dropdown.selectedCount === 0) {
                        Toast.fire({ icon: 'error', title: 'Please select at least one user' });
                        return;
                    }

                    let formData = new FormData(form);
                    errors = {};

                    fetch(formAction, {
                        method: isUpdate ? 'POST' : 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(async res => {
                        if (res.ok) {
                            Toast.fire({
                                icon: 'success',
                                title: isUpdate ? 'Document updated successfully' : 'Document added successfully'
                            });
                            open = false;
                            setTimeout(() => window.location.reload(), 1500);
                        } else if (res.status === 422) {
                            const data = await res.json();
                            errors = data.errors || {};
                            const firstErrorField = Object.keys(errors)[0];
                            const el = form.querySelector(`[name='${firstErrorField}']`);
                            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            Toast.fire({ icon: 'error', title: 'Please fix validation errors.' });
                        } else {
                            Toast.fire({ icon: 'error', title: 'Failed to save document.' });
                        }
                    })
                    .catch(() => {
                        Toast.fire({ icon: 'error', title: 'Request failed' });
                    });
                  "
            >
                @csrf
                <input type="hidden" name="_method" :value="isUpdate ? 'PATCH' : 'POST'">
                <input type="hidden" name="id" value="{{ auth()->user()->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <!-- Date Received -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Date Received</label>
                        <input type="datetime-local" name="date_received" x-model="now"
                               class="w-full border rounded p-2 text-sm" required>
                        <p x-text="errors.date_received" class="text-red-500 text-sm mt-1"></p>
                    </div>

                    <!-- Document No -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Document No.</label>
                        <input type="text" name="document_no" x-ref="document_no"
                               class="w-full border rounded p-2 text-sm" required>
                        <p x-text="errors.document_no" class="text-red-500 text-sm mt-1"></p>
                    </div>

                    <!-- Document Type -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Document Type</label>
                        <select name="document_type" x-ref="document_type" class="w-full border rounded p-2 text-sm" required>
                            <option value="" disabled>Select type</option>
                            @foreach([
                                'ACIC','Accomplishment Report','Activity Proposal','Advisory','APP',
                                'Authority to Release','Audit Itinerary','Audit Observation Memorandum', 'Approved Budget for the Contract',
                                'Bid Documents','Certificate','Certification','Clearance',
                                'Contract of Service','CSPRF','Customer Satisfaction Survey','Daily Time Record',
                                'DA Comms','DA Memo','DA SO','Demand Letter','Disbursement Voucher',
                                'Document Registration/ Revision Form','Endorsement','Financial Accomplishment Report',
                                'FOI Request','Incident Report','ITR','Leave Application','Letter','LDDAP-ADA', 'Liquidation Report',
                                'Memorandum of Agreement','Minutes of Meeting','Modification Advice Form',
                                'Notice of Award','Notice of Finality of Decision','Notice of Salary Adjustment',
                                'Notice of Step Increment','Obligation','Overtime Request','PAR','PCC Letter',
                                'PCC MEMO','PCC RC Letter','PCC SO','Performance and Commitment Review','PPMP',
                                'Program of Works','Project Proposal','Purchase Order','Purchase Request PR','PTR',
                                'Revised Activity Proposal','Report','Research Proposal','Resolution','Statement of Account',
                                'Training Request','Transmittal','Travel Order','Travel Report','Trip Ticket'
                            ] as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                        <p x-text="errors.document_type" class="text-red-500 text-sm mt-1"></p>
                    </div>

                    <!-- Particulars -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Particulars</label>
                        <textarea name="particulars" x-ref="particulars"
                            class="w-full border rounded p-2 text-sm"
                            rows="3" required></textarea>
                        <p x-text="errors.particulars" class="text-red-500 text-sm mt-1"></p>
                    </div>

                    <!-- Send To Dropdown -->
                    <div class="relative">
                        <label class="block text-sm font-medium mb-1">Send To</label>
                        <button type="button" @click="dropdown.open = !dropdown.open"
                                class="w-full border rounded p-2 text-sm">
                            <span x-text="dropdown.selectedCount > 0 ? `${dropdown.selectedCount} users selected` : 'No user selected'" class="font-medium text-gray-700"></span>
                        </button>

                        <input type="hidden" name="users_required" x-bind:disabled="dropdown.selectedCount > 0" required>

                        <div x-show="dropdown.open" x-transition @click.outside="dropdown.open = false"
                             class="absolute left-0 top-full z-50 mt-1 w-full max-h-[60vh] bg-white border rounded shadow-lg p-3 space-y-3 text-sm overflow-y-auto">
                            <div class="flex items-center border-b pb-2">
                                <input type="checkbox" id="selectAll" x-model="dropdown.selectAll"
                                       @change="$root.querySelectorAll('input.user-checkbox').forEach(cb => cb.checked = dropdown.selectAll);
                                                Object.keys(dropdown.divisionSelect).forEach(key => dropdown.divisionSelect[key] = dropdown.selectAll);
                                                dropdown.updateCount();"
                                       class="mr-2">
                                <label for="selectAll" class="font-medium text-gray-700">Select All</label>
                            </div>

                            @foreach($divisions as $division)
                                <div class="mb-2 border-b pb-2" x-ref="division{{ $division->id }}">
                                    <div class="flex items-center">
                                        <input type="checkbox"
                                               x-model="dropdown.divisionSelect[{{ $division->id }}]"
                                               @change="$refs['division{{ $division->id }}'].querySelectorAll('input.user-checkbox').forEach(cb => cb.checked = dropdown.divisionSelect[{{ $division->id }}]);
                                                        dropdown.selectAll = Array.from($root.querySelectorAll('input.user-checkbox')).every(cb => cb.checked);
                                                        dropdown.updateCount();"
                                               class="mr-2">
                                        <span class="font-semibold text-gray-700">{{ $division->name }}</span>
                                    </div>

                                    <div class="ml-4 mt-1 space-y-1">
                                        @foreach($division->users as $user)
                                            <div class="flex items-center">
                                                <input type="checkbox" name="users[]" value="{{ $user->id }}"
                                                       class="user-checkbox mr-2"
                                                       @change="dropdown.divisionSelect[{{ $division->id }}] = Array.from($refs['division{{ $division->id }}'].querySelectorAll('input.user-checkbox')).every(cb => cb.checked);
                                                                dropdown.selectAll = Array.from($root.querySelectorAll('input.user-checkbox')).every(cb => cb.checked);
                                                                dropdown.updateCount();">
                                                <label class="text-gray-700">{{ $user->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p x-text="errors.users" class="text-red-500 text-sm mt-1"></p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-4 border-t flex justify-end gap-2 shrink-0">
                    <button type="button" @click="open = false; stopTimer()"
                            class="bg-gray-300 text-gray-800 px-4 py-2 text-sm rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 text-sm rounded hover:bg-blue-700"
                            x-text="isUpdate ? 'Update' : 'Submit'">
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
