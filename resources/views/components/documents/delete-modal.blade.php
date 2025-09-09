<div x-data="{ open: false, deleteUrl: '' }"
     @open-delete-modal.window="deleteUrl = $event.detail; open = true">

    <div x-show="open" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm bg-white/30">
        <div @click.away="open = false"
             class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-sm w-full">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white text-center">
                Are you sure you want to delete this document?
            </h2>

            <form @submit.prevent="
                fetch(deleteUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: new URLSearchParams({ _method: 'DELETE' })
                })
                .then(res => {
                    if (res.ok) {
                        Toast.fire({ icon: 'success', title: 'Document deleted successfully' });
                        open = false;
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        Toast.fire({ icon: 'error', title: 'Failed to delete document' });
                    }
                })
                .catch(() => {
                    Toast.fire({ icon: 'error', title: 'Request failed' });
                });
            " class="mt-4">
                @csrf
                @method('DELETE')
                <div class="flex justify-center space-x-4">
                    <button type="button"
                            @click="open = false"
                            class="py-2 px-4 bg-gray-600 text-white rounded hover:bg-gray-500 focus:outline-none">
                        Cancel
                    </button>
                    <button type="submit"
                            class="py-2 px-4 bg-red-600 text-white rounded hover:bg-red-700">
                        Yes, Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
