<header class="flex items-center justify-between px-6 py-4 bg-indigo-800">
    <div class="flex items-center">
        <!-- Hamburger -->
        <button @click="sidebarOpen = true" class="text-white focus:outline-none mr-4">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6H20M4 12H20M4 18H11"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"/>
            </svg>
        </button>

        <!-- Title -->
        <span class="text-xl font-semibold text-white tracking-wide">
            Document Tracking System
        </span>
    </div>

    <!-- User Dropdown -->
    <div class="flex items-center">
        <div x-data="{ dropdownOpen: false }" class="relative">
            <button @click="dropdownOpen = ! dropdownOpen"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-800 hover:bg-blue-700 focus:outline-none transition ease-in-out duration-150">
                <div>{{ explode(' ', Auth::user()->name)[0] }}</div>
                <svg class="w-4" viewBox="0 0 24 24" fill="none"
                     xmlns="http://www.w3.org/2000/svg"
                     stroke="currentColor"
                     stroke-width="2">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M12.7071 14.7071C12.3166 15.0976 11.6834 15.0976 11.2929 14.7071L6.29289 9.70711C5.90237 9.31658 5.90237 8.68342 6.29289 8.29289C6.68342 7.90237 7.31658 7.90237 7.70711 8.29289L12 12.5858L16.2929 8.29289C16.6834 7.90237 17.3166 7.90237 17.7071 8.29289C18.0976 8.68342 18.0976 9.31658 17.7071 9.70711L12.7071 14.7071Z"
                          fill="white"/>
                </svg>
            </button>

            <div x-cloak x-show="dropdownOpen" @click="dropdownOpen = false"
                 class="fixed inset-0 z-10 w-full h-full"></div>

            <div x-cloak x-show="dropdownOpen"
                 class="absolute right-0 z-10 w-48 mt-2 overflow-hidden bg-white rounded-md shadow-xl">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-600"
                       onclick="event.preventDefault(); this.closest('form').submit();">
                        Log Out
                    </a>
                </form>
            </div>
        </div>
    </div>
</header>
