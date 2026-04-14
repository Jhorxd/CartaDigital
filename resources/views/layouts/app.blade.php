<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $currentTenant = $tenant ?? request()->get('tenant');
            $pageTitle = $currentTenant ? $currentTenant->name : config('app.name', 'Laravel');
            $favicon = ($currentTenant && $currentTenant->logo) ? $currentTenant->logo : asset('favicon.png');
        @endphp

        <title>{{ $pageTitle }} | {{ config('app.name') }}</title>
        <link rel="icon" type="image/png" href="{{ $favicon }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- Color de marca dinámico --}}
        @php
            $currentTenant = isset($tenant) ? $tenant : request()->get('tenant');
        @endphp
        <style>
            :root {
                --color-primary: {{ $currentTenant->brand_color ?? '#f97316' }};
            }
        </style>

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: 'var(--color-primary)',
                        }
                    }
                }
            }
        </script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
