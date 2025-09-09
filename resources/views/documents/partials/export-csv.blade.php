<div x-show="exportOpen" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm bg-white/30">
    <div @click.away="exportOpen = false"
         class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white text-center mb-4">
            Export Documents CSV
        </h2>

        <form method="GET" action="{{ route('documents.export.csv') }}">
            {{-- Retain filters --}}
            @foreach(request()->except('page') as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach

            <div class="flex justify-center space-x-4 mt-4">
                <button type="button"
                        @click="exportOpen = false"
                        class="py-2 px-4 bg-gray-600 text-white rounded hover:bg-gray-500 focus:outline-none">
                    Cancel
                </button>
                <button type="submit"
                        @click.prevent="exportOpen = false; $nextTick(() => $el.closest('form').submit())"
                        class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Export CSV
                </button>
            </div>
        </form>
    </div>
</div>
