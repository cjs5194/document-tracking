<!-- Filter Modal -->
<div
    x-show="filterOpen"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm bg-white/30"
    @keydown.escape.window="filterOpen = false"
    @click.away="filterOpen = false"
>
    <!-- Modal Wrapper -->
    <div
        class="bg-white rounded border border-gray-400 shadow-lg p-4 w-full max-w-2xl mx-auto"
        @click.stop
    >
        <h2 class="text-lg font-semibold mb-4">Filter Documents</h2>

        <form action="{{ route('documents.index') }}" method="GET">
            <!-- ➜ Make the selects 2×2 using a grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Document Type -->
                <div>
                    <label class="block text-sm font-medium mb-1">Document Type</label>
                    <select name="document_type" class="w-full border rounded p-2 text-sm">
                        <option value="" {{ request('document_type') ? '' : 'selected' }}>All</option>
                        <option value="ACIC">ACIC</option>
                        <option value="Accomplishment Report">Accomplishment Report</option>
                        <option value="Activity Proposal">Activity Proposal</option>
                        <option value="Advisory">Advisory</option>
                        <option value="APP">APP</option>
                        <option value="Authority to Release (ATR)">Authority to Release (ATR)</option>
                        <option value="Audit Itinerary">Audit Itinerary</option>
                        <option value="Audit Observation Memorandum (AOM)">Audit Observation Memorandum (AOM)</option>
                        <option value="Bid Documents">Bid Documents</option>
                        <option value="Certificate">Certificate (Appreciation, Attendance, etc..)</option>
                        <option value="Certification">Certification</option>
                        <option value="Clearance">Clearance</option>
                        <option value="Contract of Service">Contract of Service</option>
                        <option value="CSPRF">CSPRF</option>
                        <option value="Customer Satisfaction Survey (CSS)">Customer Satisfaction Survey (CSS)</option>
                        <option value="Daily Time Record (DTR)">Daily Time Record (DTR)</option>
                        <option value="DA Comms">DA Comms</option>
                        <option value="DA Memo">DA Memo</option>
                        <option value="DA SO">DA SO</option>
                        <option value="Demand Letter">Demand Letter</option>
                        <option value="Disbursement Voucher (DV)">Disbursement Voucher (DV)</option>
                        <option value="Document Registration/ Revision Form(DRR)">Document Registration/ Revision Form(DRR)</option>
                        <option value="Endorsement">Endorsement</option>
                        <option value="Financial Accomplishment Report (FAR)">Financial Accomplishment Report (FAR)</option>
                        <option value="FOI Request">FOI Request</option>
                        <option value="Incident Report (IR)">Incident Report (IR)</option>
                        <option value="ITR">ITR</option>
                        <option value="Leave Application">Leave Application</option>
                        <option value="Letter">Letter</option>
                        <option value="LDDAP-ADA">LDDAP-ADA</option>
                        <option value="Memorandum of Agreement (MOA, MOU)">Memorandum of Agreement (MOA, MOU)</option>
                        <option value="Minutes of Meeting (MoM)">Minutes of Meeting (MoM)</option>
                        <option value="Modification Advice Form (MAF)">Modification Advice Form (MAF)</option>
                        <option value="Notice of Award">Notice of Award</option>
                        <option value="Notice of Finality of Decision (NFD)">Notice of Finality of Decision (NFD)</option>
                        <option value="Notice of Salary Adjustment (NOSA)">Notice of Salary Adjustment (NOSA)</option>
                        <option value="Notice of Step Increment (NOSI)">Notice of Step Increment (NOSI)</option>
                        <option value="Obligation (ORS)">Obligation (ORS)</option>
                        <option value="Overtime Request">Overtime Request</option>
                        <option value="PAR">PAR</option>
                        <option value="PCC Letter">PCC Letter</option>
                        <option value="PCC MEMO">PCC MEMO</option>
                        <option value="PCC RC Letter">PCC RC Letter</option>
                        <option value="PCC SO">PCC SO</option>
                        <option value="Performance and Commitment Review (PCR)">Performance and Commitment Review (PCR)</option>
                        <option value="PPMP">PPMP</option>
                        <option value="Program of Works (POW)">Program of Works (POW)</option>
                        <option value="Project Proposal">Project Proposal</option>
                        <option value="Purchase Order (PO)">Purchase Order (PO)</option>
                        <option value="Purchase Request PR">Purchase Request PR</option>
                        <option value="PTR">PTR</option>
                        <option value="Revised Activity Proposal">Revised Activity Proposal</option>
                        <option value="Report">Report</option>
                        <option value="Research Proposal">Research Proposal</option>
                        <option value="Resolution">Resolution</option>
                        <option value="Statement of Account (SOA)">Statement of Account (SOA)</option>
                        <option value="Training Request">Training Request</option>
                        <option value="Transmittal">Transmittal</option>
                        <option value="Travel Order">Travel Order</option>
                        <option value="Travel Report">Travel Report</option>
                        <option value="Trip Ticket">Trip Ticket</option>
                    </select>
                </div>

                <!-- OED Level -->
                <div>
                    <label class="block text-sm font-medium mb-1">OED Level</label>
                    <select name="oed_received" class="w-full border rounded p-2 text-sm">
                        <option value="" {{ request('oed_received') ? '' : 'selected' }}>All</option>
                        <option value="Received" {{ request('oed_received') == 'Received' ? 'selected' : '' }}>Received</option>
                        <option value="Not yet received" {{ request('oed_received') == 'Not yet received' ? 'selected' : '' }}>Not yet received</option>
                    </select>
                </div>

                <!-- Filter Status -->
                <div>
                    <label class="block text-sm font-medium mb-1">Filter Status</label>
                    <select name="oed_status" class="w-full border rounded p-2 text-sm">
                        <option value="" {{ request('oed_status') ? '' : 'selected' }}>All</option>
                        <option value="For Release" {{ request('oed_status') == 'For Release' ? 'selected' : '' }}>For Release</option>
                        <option value="Under Review" {{ request('oed_status') == 'Under Review' ? 'selected' : '' }}>Under Review</option>
                        <option value="In Progress" {{ request('oed_status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Returned" {{ request('oed_status') == 'Returned' ? 'selected' : '' }}>Returned</option>
                        <option value="null" {{ request('oed_status') == 'null' ? 'selected' : '' }}>No status</option>
                    </select>
                </div>

                <!-- Record Section -->
                <div>
                    <label class="block text-sm font-medium mb-1">Record Section</label>
                    <select name="records_received" class="w-full border rounded p-2 text-sm">
                        <option value="" {{ request('records_received') ? '' : 'selected' }}>All</option>
                        <option value="Received" {{ request('records_received') == 'Received' ? 'selected' : '' }}>Received</option>
                        <option value="Not yet received" {{ request('records_received') == 'Not yet received' ? 'selected' : '' }}>Not yet received</option>
                    </select>
                </div>
            </div>

            <!-- Completed -->
            <div>
                <label class="block text-sm font-medium mb-1">Completed</label>
                <select name="completed" class="w-full border rounded p-2 text-sm">
                    <option value="" {{ request('completed') ? '' : 'selected' }}>All</option>
                    <option value="Completed" {{ request('completed') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Not yet completed" {{ request('completed') == 'Not yet completed' ? 'selected' : '' }}>Not yet completed</option>
                </select>
            </div>

            <!-- Footer buttons -->
            <div class="flex justify-end gap-2 mt-6">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 text-sm rounded hover:bg-blue-700">
                    Apply Filter
                </button>
                <button type="button" @click="filterOpen = false" class="bg-gray-300 text-gray-800 px-4 py-2 text-sm rounded hover:bg-gray-400">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
