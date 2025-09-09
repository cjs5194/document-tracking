<!DOCTYPE html>
<html lang="{{ $page->language ?? 'en' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="referrer" content="always">

        <title>Document Tracking System</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('images/favicon.jpg') }}" type="image/jpeg">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div
            x-data="{ sidebarOpen: false }"
            class="flex h-screen bg-gray-200 font-roboto"
        >
            <!-- Sidebar -->
            <div
                x-show="sidebarOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="-translate-x-full opacity-0"
                x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="translate-x-0 opacity-100"
                x-transition:leave-end="-translate-x-full opacity-0"
                class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-lg overflow-y-auto"
                @click.away="sidebarOpen = false"
                style="display: none;" <!-- prevents FOUC -->
            >
                @include('layouts.admin.sidebar')
            </div>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                @include('layouts.admin.header')

                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                    <div class="px-4 py-2">
                        {{ $slot }}
                        <x-documents.delete-modal />
                    </div>
                </main>
            </div>

            <!-- ✅ Toast Notification Support -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                window.Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    timer: 2000,
                    showConfirmButton: false,
                    timerProgressBar: true,
                });
            </script>
            @stack('scripts')
        </div>
    </body>
</html>
