<!-- Overlay -->
<div x-cloak :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false"
     class="fixed inset-0 z-20 transition-opacity bg-black opacity-50"></div>

<!-- Sidebar -->
<div x-cloak :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
     class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-white shadow-lg">
    <div class="flex items-center justify-center mt-8">
        <div class="flex items-center">
            <img src="{{ asset('images/favicon.jpg') }}" alt="Custom Icon" class="h-8 w-8 rounded-full">
            <span class="mx-2 text-2xl font-semibold text-gray-800">Doctracks</span>
        </div>
    </div>

    <nav class="mt-10">
        {{-- Documents --}}
        @if(Auth::user()->hasRole('admin'))
            <a class="flex items-center px-6 py-2 mt-4 text-gray-700 hover:bg-gray-100 hover:text-blue-600 rounded {{ Route::currentRouteNamed('admin.documents.index') ? 'text-blue-600 bg-gray-100' : '' }}"
               href="{{ route('admin.documents.index') }}">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h5l2 2h4a2 2 0 012 2v12a2 2 0 01-2 2z" />
                </svg>
                <span class="mx-3">Documents</span>
            </a>
        @else
            <a class="flex items-center px-6 py-2 mt-4 text-gray-700 hover:bg-gray-100 hover:text-blue-600 rounded {{ Route::currentRouteNamed('documents.index') ? 'text-blue-600 bg-gray-100' : '' }}"
               href="{{ route('documents.index') }}">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h5l2 2h4a2 2 0 012 2v12a2 2 0 01-2 2z" />
                </svg>
                <span class="mx-3">Documents</span>
            </a>
        @endif

        {{-- Configurations (Admin Only) --}}
        @if(Auth::user()->hasRole('admin'))
            <div x-data="{ open: {{ Route::is('admin.users.index', 'admin.roles.index', 'admin.permissions.index') ? 'true' : 'false' }} }" class="relative">
                <button @click="open = !open"
                    class="flex items-center px-6 py-2 mt-4 w-full {{ Route::is('admin.users.index', 'admin.roles.index', 'admin.permissions.index') ? 'text-blue-600 bg-gray-100' : 'text-gray-700' }} hover:bg-gray-100 hover:text-blue-600 rounded">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="mx-3">Configurations</span>
                    <svg xmlns="http://www.w3.org/2000/svg" :class="{ 'rotate-180': open }" class="w-5 h-5 ml-2 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6.293 9.293a1 1 0 011.414 0L10 11.586l2.293-2.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="open" x-cloak>
                    <a class="flex items-center px-6 py-2 mt-2 ml-5 {{ Route::currentRouteNamed('admin.users.index') ? 'text-blue-600 bg-gray-100' : 'text-gray-700' }} hover:bg-gray-100 hover:text-blue-600 rounded"
                       href="{{ route('admin.users.index') }}">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z" />
                        </svg>
                        <span class="mx-3">User</span>
                    </a>

                    <a class="flex items-center px-6 py-2 mt-2 ml-5 {{ Route::currentRouteNamed('admin.roles.index') ? 'text-blue-600 bg-gray-100' : 'text-gray-700' }} hover:bg-gray-100 hover:text-blue-600 rounded"
                       href="{{ route('admin.roles.index') }}">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span class="mx-3">Role</span>
                    </a>

                    <a class="flex items-center px-6 py-2 mt-2 ml-5 {{ Route::currentRouteNamed('admin.permissions.index') ? 'text-blue-600 bg-gray-100' : 'text-gray-700' }} hover:bg-gray-100 hover:text-blue-600 rounded"
                       href="{{ route('admin.permissions.index') }}">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span class="mx-3">Permission</span>
                    </a>
                </div>
            </div>
        @endif
    </nav>
</div>
