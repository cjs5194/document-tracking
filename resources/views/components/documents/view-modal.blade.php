<div x-data="{ open:false, doc:{date_received:'', document_no:'', document_type:'', particulars:'', forwarded_to_records:'', users:[]} }"
     @open-view-modal.window="doc=$event.detail.doc; open=true">

    <div x-show="open" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-white/10"
     x-transition.opacity
     style="backdrop-filter: blur(2px); -webkit-backdrop-filter: blur(2px);">
        <div @click.away="open=false"
             class="bg-white rounded border border-gray-400 shadow-lg p-4 w-full max-w-2xl mx-auto">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Document Details</h2>

            <div class="space-y-3 text-sm text-gray-700">
                <p><span class="font-medium">Date Received:</span> <span x-text="doc.date_received || '—'"></span></p>
                <p><span class="font-medium">Document No.:</span> <span x-text="doc.document_no || '—'"></span></p>
                <p><span class="font-medium">Document Type:</span> <span x-text="doc.document_type || '—'"></span></p>
                <p><span class="font-medium">Particulars:</span> <span x-text="doc.particulars || '—'"></span></p>
                <p><span class="font-medium">Date forwarded to Records:</span> <span x-text="doc.forwarded_to_records || '—'"></span></p>

                <div>
                    <span class="font-medium">Send to:</span>
                    <template x-if="doc.users && doc.users.length">
                        <div class="mt-1 space-x-1 space-y-1">
                            <template x-for="name in doc.users" :key="name">
                                <span class="inline-block bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs mr-2 mb-1" x-text="name"></span>
                            </template>
                        </div>
                    </template>
                    <template x-if="!doc.users || !doc.users.length">
                        <span class="ml-1 text-gray-500">—</span>
                    </template>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button @click="open=false"
                        class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Close</button>
            </div>
        </div>
    </div>
</div>
