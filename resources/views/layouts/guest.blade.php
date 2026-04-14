<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Plus Jakarta Sans', 'Figtree', 'sans-serif'],
                        },
                    }
                }
            }
        </script>
    </head>
    <body class="font-sans text-slate-900 antialiased overflow-x-hidden selection:bg-[--brand-color] selection:text-white">
        @php
            $tenant = request()->get('tenant');
            $brandColor = $tenant->brand_color ?? '#6366f1'; // Premium Indigo
        @endphp

        <style>
            :root {
                --brand-color: {{ $brandColor }};
                --brand-color-soft: {{ $brandColor }}15;
            }
            .bg-premium {
                background-color: #030712;
                background-image: 
                    radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                    radial-gradient(at 50% 0%, {{ $brandColor }}33 0, transparent 50%), 
                    radial-gradient(at 100% 0%, hsla(225,39%,30%,1) 0, transparent 50%);
            }
            .glass {
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.08);
            }
            .glass-inner {
                background: rgba(255, 255, 255, 1);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
        </style>

        <div class="min-h-screen flex flex-col sm:justify-center items-center p-6 bg-premium">
            <div class="mb-12 transform transition-all duration-700 hover:scale-105">
                <a href="/">
                    <x-application-logo />
                </a>
            </div>

            <div class="w-full sm:max-w-md glass-inner shadow-[0_20px_50px_rgba(0,0,0,0.3)] overflow-hidden rounded-[2.5rem] border-white/20">
                <div class="px-10 py-12">
                    {{ $slot }}
                </div>
            </div>

            @if(!$tenant)
                <div class="mt-8 text-white/60 text-sm">
                    &copy; {{ date('Y') }} {{ config('app.name') }} - Administración Global
                </div>
            @endif
        </div>
    </body>
</html>
