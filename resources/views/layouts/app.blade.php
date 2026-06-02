<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=merriweather:400,400i,700|playfair-display:400,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />
        <div class="fixed inset-x-0 top-4 z-[10000] pointer-events-none px-4">
            <div class="mx-auto max-w-3xl space-y-2">
                @if (session()->has('success'))
                    <div
                        x-data="{ show: true }"
                        x-init="setTimeout(() => show = false, 4500)"
                        x-show="show"
                        x-transition.opacity.duration.300ms
                        class="pointer-events-auto rounded-xl border border-green-500/70 bg-green-900/95 px-4 py-3 text-green-100 shadow-2xl"
                    >
                        {{ session('success') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div
                        x-data="{ show: true }"
                        x-init="setTimeout(() => show = false, 5500)"
                        x-show="show"
                        x-transition.opacity.duration.300ms
                        class="pointer-events-auto rounded-xl border border-red-500/70 bg-red-900/95 px-4 py-3 text-red-100 shadow-2xl"
                    >
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="min-h-screen flex flex-col bg-[#1c1816] text-white selection:bg-[#b58f5c] selection:text-[#1c1816]">
            @livewire('navigation-menu')
            @if (isset($header))
                <header class="bg-[#2d2019] border-b border-[#3e2b1e] shadow-md">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif
            <main class="flex-grow">
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
