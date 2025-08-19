@props(['document', 'divisions'])

<div x-data="{ open: false, selectAll: false, divisionSelect: {} }" class="relative">
    <!-- Button to open modal -->
    <button @click="open = true"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Send To
    </button>

    <!-- Modal -->
    <div x-show="open" x-cloak class="fixed inset-0 flex items-center justify-center z-50 backdrop-blur-sm bg-black/30">
        <div @click.away="open = false" class="bg-white rounded shadow-lg p-6 w-full max-w-lg overflow-y-auto max-h-[80vh]">
            <h2 class="text-xl font-bold mb-4 text-gray-900">Send To</h2>

            <form action="{{ route('documents.send', $document) }}" method="POST">
                @csrf

                <!-- Global Select All -->
                <div class="flex items-center mb-4">
                    <input type="checkbox" id="selectAll" x-model="selectAll"
                           @change="$el.closest('form').querySelectorAll('input.user-checkbox').forEach(cb => cb.checked = selectAll);
                                    Object.keys(divisionSelect).forEach(key => divisionSelect[key] = selectAll)"
                           class="mr-2">
                    <label for="selectAll" class="font-medium text-gray-700">Select All</label>
                </div>

                <!-- Divisions & Users -->
                @foreach($divisions as $division)
                    <div class="mb-3 border-b pb-2" x-ref="division{{ $division->id }}">
                        <div class="flex items-center justify-between">
                            <div class="font-semibold text-gray-700">{{ $division->name }}</div>
                            <div>
                                <!-- Division Select All -->
                                <input type="checkbox" x-model="divisionSelect[{{ $division->id }}]"
                                       @change="$refs['division{{ $division->id }}'].querySelectorAll('input.user-checkbox').forEach(cb => cb.checked = divisionSelect[{{ $division->id }}]);
                                                selectAll = Object.values(divisionSelect).every(v => v)"
                                       class="mr-2">
                                <label class="text-gray-700 text-sm">Select All</label>
                            </div>
                        </div>

                        <div class="ml-4 mt-1 space-y-1">
                            @foreach($division->users as $user)
                                <div class="flex items-center">
                                    <input type="checkbox" name="users[]" value="{{ $user->id }}"
                                           class="user-checkbox mr-2"
                                           @change="divisionSelect[{{ $division->id }}] = Array.from($refs['division{{ $division->id }}'].querySelectorAll('input.user-checkbox')).every(cb => cb.checked);
                                                    selectAll = Object.values(divisionSelect).every(v => v);">
                                    <label class="text-gray-700">{{ $user->name }} ({{ $user->email }})</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" @click="open = false"
                            class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Send
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
