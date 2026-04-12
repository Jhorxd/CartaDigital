<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans text-gray-900 antialiased overflow-x-hidden">
        @php
            $tenant = request()->get('tenant');
            $brandColor = $tenant->brand_color ?? '#4f46e5'; // Indigo default
        @endphp

        <style>
            :root {
                --brand-color: {{ $brandColor }};
                --brand-color-soft: {{ $brandColor }}22;
            }
            .bg-premium {
                background: radial-gradient(circle at top right, var(--brand-color), transparent),
                            radial-gradient(circle at bottom left, #7c3aed, transparent),
                            #0f172a;
            }
            .glass {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }
        </style>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-premium">
            <div class="mb-8 transform transition hover:scale-105 duration-300">
                <a href="/">
                    @if($tenant && $tenant->logo)
                        <img src="{{ $tenant->logo }}" alt="{{ $tenant->name }}" class="h-24 w-auto drop-shadow-2xl">
                    @else
                        <div class="flex flex-col items-center">
                            <x-application-logo class="w-20 h-20 fill-current text-white drop-shadow-lg" />
                            @if($tenant)
                                <span class="text-white font-bold text-2xl mt-4 tracking-tight">{{ $tenant->name }}</span>
                            @endif
                        </div>
                    @endif
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-2 px-8 py-10 glass shadow-2xl overflow-hidden sm:rounded-3xl transform transition-all">
                {{ $slot }}
            </div>

            @if(!$tenant)
                <div class="mt-8 text-white/60 text-sm">
                    &copy; {{ date('Y') }} {{ config('app.name') }} - Administración Global
                </div>
            @endif
        </div>
    </body>
</html>
