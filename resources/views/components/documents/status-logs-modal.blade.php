<!-- Logs Modal -->
<div x-show="logsOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm bg-white/30" @keydown.escape.window="logsOpen = false" @click.away="logsOpen = false">
    <div class="bg-white rounded border border-gray-400 shadow-lg p-4 w-full max-w-2xl mx-auto" @click.stop>
        <h2 class="text-lg font-semibold mb-4">Status Logs</h2>
        <!-- Status Logs Modal Content -->
        <div class="space-y-2">
            @foreach ($document->logs as $log)
                @if ($log->type === 'status')
                    <div class="pt-2 {{ $log->status === 'Returned' ? 'text-red-600 font-semibold' : 'text-gray-800' }}">
                        {{ $log->status }}: {{ $log->created_at->format('F j, Y g:i A') }}
                        @if($log->changed_by) by {{ $log->changed_by }} @endif
                    </div>
                @elseif ($log->type === 'records')
                    <div class="pt-2 text-blue-600 font-semibold">
                        Records Section: {{ $log->status }}: {{ $log->created_at->format('F j, Y g:i A') }}
                        @if($log->changed_by) by {{ $log->changed_by }} @endif
                    </div>
                @endif
            @endforeach
        </div>
        <div class="flex justify-end mt-4">
            <button type="button" @click="logsOpen = false" class="bg-gray-300 text-gray-800 px-4 py-2 text-sm rounded hover:bg-gray-400">Close</button>
        </div>
    </div>
</div>
