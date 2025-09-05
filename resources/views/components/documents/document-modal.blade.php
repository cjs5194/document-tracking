@props(['document','divisions'])

<!-- Document Modal (Create + Update) -->
<div
    x-data="{
        open: false,
        isUpdate: false,
        formAction: '{{ auth()->user()->hasRole('admin') ? route('admin.documents.store') : route('documents.store') }}',
        now: '',
        timer: null,

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
            this.stopTimer(); // don't auto-update for existing records
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
        },

        checkUsers(userIds) {
            $root.querySelectorAll('input.user-checkbox').forEach(cb => {
                cb.checked = userIds.includes(parseInt(cb.value));
            });
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
    <!-- Modal Container -->
    <div
        class="bg-white rounded-lg shadow-lg w-full max-w-2xl max-h-[90vh] flex flex-col border border-gray-400"
        @click.stop
    >
        <!-- Header -->
        <div class="p-4 border-b shrink-0">
            <h2 class="text-lg font-semibold" x-text="isUpdate ? 'Update Document' : 'Add Document'"></h2>
        </div>

        <!-- Content -->
        <div class="p-4 flex-1 overflow-y-visible">

            <form x-ref="documentForm" :action="formAction" method="POST">
                @csrf
                <input type="hidden" name="_method" :value="isUpdate ? 'PATCH' : 'POST'">

                <input type="hidden" name="id" value="{{ auth()->user()->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <!-- Date Received -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Date Received</label>
                        <input type="datetime-local" name="date_received" x-model="now"
                               class="w-full border rounded p-2 text-sm" required>
                        @error('date_received')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Document No -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Document No.</label>
                        <input type="text" name="document_no" x-ref="document_no"
                               class="w-full border rounded p-2 text-sm" required>
                        @error('document_no')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Document Type -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Document Type</label>
                        <select name="document_type" x-ref="document_type" class="w-full border rounded p-2 text-sm" required>
                            <option value="" disabled>Select type</option>
                            @foreach([
                                'ACIC','Accomplishment Report','Activity Proposal','Advisory','APP',
                                'Authority to Release (ATR)','Audit Itinerary','Audit Observation Memorandum (AOM)',
                                'Bid Documents','Certificate (Appreciation, Attendance, etc..)','Certification','Clearance',
                                'Contract of Service','CSPRF','Customer Satisfaction Survey (CSS)','Daily Time Record (DTR)',
                                'DA Comms','DA Memo','DA SO','Demand Letter','Disbursement Voucher (DV)',
                                'Document Registration/ Revision Form(DRR)','Endorsement','Financial Accomplishment Report (FAR)',
                                'FOI Request','Incident Report (IR)','ITR','Leave Application','Letter','LDDAP-ADA',
                                'Memorandum of Agreement (MOA, MOU)','Minutes of Meeting (MoM)','Modification Advice Form (MAF)',
                                'Notice of Award','Notice of Finality of Decision (NFD)','Notice of Salary Adjustment (NOSA)',
                                'Notice of Step Increment (NOSI)','Obligation (ORS)','Overtime Request','PAR','PCC Letter',
                                'PCC MEMO','PCC RC Letter','PCC SO','Performance and Commitment Review (PCR)','PPMP',
                                'Program of Works (POW)','Project Proposal','Purchase Order (PO)','Purchase Request PR','PTR',
                                'Revised Activity Proposal','Report','Research Proposal','Resolution','Statement of Account (SOA)',
                                'Training Request','Transmittal','Travel Order','Travel Report','Trip Ticket'
                            ] as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('document_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Particulars -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Particulars</label>
                        <input type="text" name="particulars" x-ref="particulars" class="w-full border rounded p-2 text-sm" required>
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
                    }" class="relative">
                        <label class="block text-sm font-medium mb-1">Send To</label>
                        <button type="button" @click="openDropdown = !openDropdown"
                                class="w-full border rounded p-2 text-sm">
                            <span class="font-medium text-gray-700"
                                x-text="selectedCount > 0 ? `${selectedCount} users selected` : 'No user selected'"></span>
                        </button>

                        <input type="hidden" name="users_required" x-bind:disabled="selectedCount > 0" required>

                        <div x-show="openDropdown" x-transition @click.outside="openDropdown = false"
                             class="absolute left-0 top-full z-50 mt-1 w-full max-h-[60vh] bg-white border rounded shadow-lg p-3 space-y-3 text-sm overflow-y-auto">
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

                            @foreach($divisions as $division)
                                <div class="mb-2 border-b pb-2" x-ref="division{{ $division->id }}">
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
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 text-sm rounded hover:bg-blue-700"
                            x-text="isUpdate ? 'Update' : 'Submit'">
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
