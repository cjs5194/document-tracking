<div id="deleteModal" tabindex="-1" aria-hidden="true"
     class="hidden fixed inset-0 z-50 items-center justify-center">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-sm w-full">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white text-center">
            Are you sure you want to delete this user?
        </h2>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex justify-center space-x-4 mt-4">
                <button type="button" class="closeModal py-2 px-4 bg-gray-600 text-white rounded hover:bg-gray-500 focus:outline-none">
                    Cancel
                </button>
                <button type="submit" class="py-2 px-4 bg-red-600 text-white rounded hover:bg-red-700">
                    Yes, Delete
                </button>
            </div>
        </form>
    </div>
</div>
