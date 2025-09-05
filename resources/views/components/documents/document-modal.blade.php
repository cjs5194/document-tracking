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
                            <option value="ACIC" {{ old('document_type')=='ACIC' ? 'selected' : '' }}>ACIC</option>
                            <option value="Accomplishment Report" {{ old('document_type')=='Accomplishment Report' ? 'selected' : '' }}>Accomplishment Report</option>
                            <option value="Activity Proposal" {{ old('document_type')=='Activity Proposal' ? 'selected' : '' }}>Activity Proposal</option>
                            <option value="Advisory" {{ old('document_type')=='Advisory' ? 'selected' : '' }}>Advisory</option>
                            <option value="APP" {{ old('document_type')=='APP' ? 'selected' : '' }}>APP</option>
                            <option value="Authority to Release (ATR)" {{ old('document_type')=='Authority to Release (ATR)' ? 'selected' : '' }}>Authority to Release (ATR)</option>
                            <option value="Audit Itinerary" {{ old('document_type')=='Audit Itinerary' ? 'selected' : '' }}>Audit Itinerary</option>
                            <option value="Audit Observation Memorandum (AOM)" {{ old('document_type')=='Audit Observation Memorandum (AOM)' ? 'selected' : '' }}>Audit Observation Memorandum (AOM)</option>
                            <option value="Bid Documents" {{ old('document_type')=='Bid Documents' ? 'selected' : '' }}>Bid Documents</option>
                            <option value="Certificate" {{ old('document_type')=='Certificate' ? 'selected' : '' }}>Certificate (Appreciation, Attendance, etc..)</option>
                            <option value="Certification" {{ old('document_type')=='Certification' ? 'selected' : '' }}>Certification</option>
                            <option value="Clearance" {{ old('document_type')=='Clearance' ? 'selected' : '' }}>Clearance</option>
                            <option value="Contract of Service" {{ old('document_type')=='Contract of Service' ? 'selected' : '' }}>Contract of Service</option>
                            <option value="CSPRF" {{ old('document_type')=='CSPRF' ? 'selected' : '' }}>CSPRF</option>
                            <option value="Customer Satisfaction Survey (CSS)" {{ old('document_type')=='Customer Satisfaction Survey (CSS)' ? 'selected' : '' }}>Customer Satisfaction Survey (CSS)</option>
                            <option value="Daily Time Record (DTR)" {{ old('document_type')=='Daily Time Record (DTR)' ? 'selected' : '' }}>Daily Time Record (DTR)</option>
                            <option value="DA Comms" {{ old('document_type')=='DA Comms' ? 'selected' : '' }}>DA Comms</option>
                            <option value="DA Memo" {{ old('document_type')=='DA Memo' ? 'selected' : '' }}>DA Memo</option>
                            <option value="DA SO" {{ old('document_type')=='DA SO' ? 'selected' : '' }}>DA SO</option>
                            <option value="Demand Letter" {{ old('document_type')=='Demand Letter' ? 'selected' : '' }}>Demand Letter</option>
                            <option value="Disbursement Voucher (DV)" {{ old('document_type')=='Disbursement Voucher (DV)' ? 'selected' : '' }}>Disbursement Voucher (DV)</option>
                            <option value="Document Registration/ Revision Form(DRR)" {{ old('document_type')=='Document Registration/ Revision Form(DRR)' ? 'selected' : '' }}>Document Registration/ Revision Form(DRR)</option>
                            <option value="Endorsement" {{ old('document_type')=='Endorsement' ? 'selected' : '' }}>Endorsement</option>
                            <option value="Financial Accomplishment Report (FAR)" {{ old('document_type')=='Financial Accomplishment Report (FAR)' ? 'selected' : '' }}>Financial Accomplishment Report (FAR)</option>
                            <option value="FOI Request" {{ old('document_type')=='FOI Request' ? 'selected' : '' }}>FOI Request</option>
                            <option value="Incident Report (IR)" {{ old('document_type')=='Incident Report (IR)' ? 'selected' : '' }}>Incident Report (IR)</option>
                            <option value="ITR" {{ old('document_type')=='ITR' ? 'selected' : '' }}>ITR</option>
                            <option value="Leave Application" {{ old('document_type')=='Leave Application' ? 'selected' : '' }}>Leave Application</option>
                            <option value="Letter" {{ old('document_type')=='Letter' ? 'selected' : '' }}>Letter</option>
                            <option value="LDDAP-ADA" {{ old('document_type')=='LDDAP-ADA' ? 'selected' : '' }}>LDDAP-ADA</option>
                            <option value="Memorandum of Agreement (MOA, MOU)" {{ old('document_type')=='Memorandum of Agreement (MOA, MOU)' ? 'selected' : '' }}>Memorandum of Agreement (MOA, MOU)</option>
                            <option value="Minutes of Meeting (MoM)" {{ old('document_type')=='Minutes of Meeting (MoM)' ? 'selected' : '' }}>Minutes of Meeting (MoM)</option>
                            <option value="Modification Advice Form (MAF)" {{ old('document_type')=='Modification Advice Form (MAF)' ? 'selected' : '' }}>Modification Advice Form (MAF)</option>
                            <option value="Notice of Award" {{ old('document_type')=='Notice of Award' ? 'selected' : '' }}>Notice of Award</option>
                            <option value="Notice of Finality of Decision (NFD)" {{ old('document_type')=='Notice of Finality of Decision (NFD)' ? 'selected' : '' }}>Notice of Finality of Decision (NFD)</option>
                            <option value="Notice of Salary Adjustment (NOSA)" {{ old('document_type')=='Notice of Salary Adjustment (NOSA)' ? 'selected' : '' }}>Notice of Salary Adjustment (NOSA)</option>
                            <option value="Notice of Step Increment (NOSI)" {{ old('document_type')=='Notice of Step Increment (NOSI)' ? 'selected' : '' }}>Notice of Step Increment (NOSI)</option>
                            <option value="Obligation (ORS)" {{ old('document_type')=='Obligation (ORS)' ? 'selected' : '' }}>Obligation (ORS)</option>
                            <option value="Overtime Request" {{ old('document_type')=='Overtime Request' ? 'selected' : '' }}>Overtime Request</option>
                            <option value="PAR" {{ old('document_type')=='PAR' ? 'selected' : '' }}>PAR</option>
                            <option value="PCC Letter" {{ old('document_type')=='PCC Letter' ? 'selected' : '' }}>PCC Letter</option>
                            <option value="PCC MEMO" {{ old('document_type')=='PCC MEMO' ? 'selected' : '' }}>PCC MEMO</option>
                            <option value="PCC RC Letter" {{ old('document_type')=='PCC RC Letter' ? 'selected' : '' }}>PCC RC Letter</option>
                            <option value="PCC SO" {{ old('document_type')=='PCC SO' ? 'selected' : '' }}>PCC SO</option>
                            <option value="Performance and Commitment Review (PCR)" {{ old('document_type')=='Performance and Commitment Review (PCR)' ? 'selected' : '' }}>Performance and Commitment Review (PCR)</option>
                            <option value="PPMP" {{ old('document_type')=='PPMP' ? 'selected' : '' }}>PPMP</option>
                            <option value="Program of Works (POW)" {{ old('document_type')=='Program of Works (POW)' ? 'selected' : '' }}>Program of Works (POW)</option>
                            <option value="Project Proposal" {{ old('document_type')=='Project Proposal' ? 'selected' : '' }}>Project Proposal</option>
                            <option value="Purchase Order (PO)" {{ old('document_type')=='Purchase Order (PO)' ? 'selected' : '' }}>Purchase Order (PO)</option>
                            <option value="Purchase Request PR" {{ old('document_type')=='Purchase Request PR' ? 'selected' : '' }}>Purchase Request PR</option>
                            <option value="PTR" {{ old('document_type')=='PTR' ? 'selected' : '' }}>PTR</option>
                            <option value="Revised Activity Proposal" {{ old('document_type')=='Revised Activity Proposal' ? 'selected' : '' }}>Revised Activity Proposal</option>
                            <option value="Report" {{ old('document_type')=='Report' ? 'selected' : '' }}>Report</option>
                            <option value="Research Proposal" {{ old('document_type')=='Research Proposal' ? 'selected' : '' }}>Research Proposal</option>
                            <option value="Resolution" {{ old('document_type')=='Resolution' ? 'selected' : '' }}>Resolution</option>
                            <option value="Statement of Account (SOA)" {{ old('document_type')=='Statement of Account (SOA)' ? 'selected' : '' }}>Statement of Account (SOA)</option>
                            <option value="Training Request" {{ old('document_type')=='Training Request' ? 'selected' : '' }}>Training Request</option>
                            <option value="Transmittal" {{ old('document_type')=='Transmittal' ? 'selected' : '' }}>Transmittal</option>
                            <option value="Travel Order" {{ old('document_type')=='Travel Order' ? 'selected' : '' }}>Travel Order</option>
                            <option value="Travel Report" {{ old('document_type')=='Travel Report' ? 'selected' : '' }}>Travel Report</option>
                            <option value="Trip Ticket" {{ old('document_type')=='Trip Ticket' ? 'selected' : '' }}>Trip Ticket</option>
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
