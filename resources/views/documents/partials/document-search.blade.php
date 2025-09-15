<form method="GET" action="{{ route($prefix . 'documents.index') }}" class="flex items-center ml-2">
    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Search documents..."
        class="border border-gray-300 rounded px-3 py-1.5 text-sm w-64
               focus:border-blue-400 focus:ring-1 focus:ring-blue-300 focus:outline-none"
    >
    <button
        type="submit"
        class="text-gray-800 px-4 py-2 rounded border border-gray-400 hover:border-gray-600 ml-2"
    >
        Search
    </button>

    @if(request('search'))
        <a href="{{ route($prefix . 'documents.index') }}"
           class="text-gray-800 px-4 py-2 rounded border border-gray-400 hover:border-gray-600 ml-2">
            Clear Search
        </a>
    @endif
</form>
