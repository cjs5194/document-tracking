<!-- Document Submission Modal -->
<div
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm bg-white/30"
    @keydown.escape.window="open = false"
    @click.away="open = false"
>
    <!-- Modal Wrapper -->
    <div
        class="bg-white rounded border border-gray-400 shadow-lg p-4 w-full max-w-2xl mx-auto"
        @click.stop
    >
        <h2 class="text-lg font-semibold mb-4">Submit a Document</h2>

        <form
            action="{{ auth()->user()->hasRole('admin') ? route('admin.documents.store') : route('documents.store') }}"
            method="POST"
        >
            @csrf
            <input type="hidden" name="id" value="{{ auth()->user()->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <!-- Your input fields (unchanged) -->
                <div>
                    <label class="block text-sm font-medium mb-1">Date Received</label>
                    <input type="date" name="date_received" class="w-full border rounded p-2 text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Document No.</label>
                    <input type="text" name="document_no" class="w-full border rounded p-2 text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Document Type</label>
                    <input type="text" name="document_type" class="w-full border rounded p-2 text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Particulars</label>
                    <input type="text" name="particulars" class="w-full border rounded p-2 text-sm" required>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-5">
                <button type="button" @click="open = false" class="bg-gray-300 text-gray-800 px-4 py-2 text-sm rounded hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 text-sm rounded hover:bg-blue-700">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>
