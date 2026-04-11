<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuración del Local') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Formulario principal --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 font-sans">
                    <form method="POST" action="{{ route('tenant.admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                            {{-- Información básica --}}
                            <div class="space-y-6">
                                <h3 class="text-lg font-bold text-gray-700 border-b pb-2">Información Pública</h3>

                                <div>
                                    <x-input-label for="name" :value="__('Nombre del Negocio')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $tenant->name)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <div>
                                    <x-input-label for="whatsapp" :value="__('WhatsApp para Pedidos')" />
                                    <x-text-input id="whatsapp" name="whatsapp" type="text" class="mt-1 block w-full" :value="old('whatsapp', $tenant->whatsapp)" placeholder="Ej: 51999888777" />
                                    <p class="text-xs text-gray-500 mt-1 italic">Incluye el código de país (ej: 51 para Perú).</p>
                                    <x-input-error class="mt-2" :messages="$errors->get('whatsapp')" />
                                </div>

                                <div>
                                    <x-input-label for="address" :value="__('Dirección Física')" />
                                    <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $tenant->address)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('address')" />
                                </div>

                                <div>
                                    <x-input-label for="schedule" :value="__('Horario de Atención')" />
                                    <x-text-input id="schedule" name="schedule" type="text" class="mt-1 block w-full" :value="old('schedule', $tenant->schedule)" placeholder="Ej: Lun-Dom 12pm a 10pm" />
                                    <x-input-error class="mt-2" :messages="$errors->get('schedule')" />
                                </div>
                            </div>

                            {{-- Identidad Visual --}}
                            <div class="space-y-6">
                                <h3 class="text-lg font-bold text-gray-700 border-b pb-2">Identidad Visual</h3>

                                {{-- Logo --}}
                                <div>
                                    <x-input-label for="logo" :value="__('Logo del Restaurante')" />
                                    <div class="mt-2 flex items-center gap-4">
                                        @if($tenant->logo)
                                            <img src="{{ $tenant->logo }}" class="w-20 h-20 rounded-full border shadow-sm object-cover">
                                        @else
                                            <div class="w-20 h-20 bg-gray-100 rounded-full border border-dashed flex items-center justify-center text-gray-400 text-xs text-center">Sin logo</div>
                                        @endif
                                        <input id="logo" name="logo" type="file" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('logo')" />
                                </div>

                                {{-- Color de Marca --}}
                                <div x-data="{ color: '{{ old('brand_color', $tenant->brand_color ?? '#f97316') }}' }">
                                    <x-input-label for="brand_color" :value="__('Color de Marca')" />
                                    <p class="text-xs text-gray-500 mb-2">Define el color principal de tu carta digital (botones, acentos, etc.).</p>
                                    <div class="flex items-center gap-4 mt-1">
                                        {{-- Preview del color actual --}}
                                        <div class="w-12 h-12 rounded-xl shadow-md border border-gray-200 transition-all duration-300 flex-shrink-0"
                                             :style="`background-color: ${color}`"></div>

                                        {{-- Colores predefinidos --}}
                                        <div class="flex flex-wrap gap-2">
                                            @foreach(['#f97316', '#ef4444', '#8b5cf6', '#06b6d4', '#10b981', '#f59e0b', '#ec4899', '#1d4ed8'] as $presetColor)
                                            <button type="button"
                                                    @click="color = '{{ $presetColor }}'"
                                                    class="w-8 h-8 rounded-full border-2 transition-transform hover:scale-110 active:scale-95 focus:outline-none"
                                                    :class="color === '{{ $presetColor }}' ? 'border-gray-900 scale-110' : 'border-transparent'"
                                                    style="background-color: {{ $presetColor }}"
                                                    title="{{ $presetColor }}">
                                            </button>
                                            @endforeach

                                            {{-- Selector personalizado --}}
                                            <label class="w-8 h-8 rounded-full border-2 border-dashed border-gray-400 flex items-center justify-center cursor-pointer hover:scale-110 transition-transform" title="Color personalizado">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                <input type="color" class="sr-only" x-model="color">
                                            </label>
                                        </div>
                                    </div>
                                    <input type="hidden" name="brand_color" :value="color">
                                    <x-input-error class="mt-2" :messages="$errors->get('brand_color')" />
                                </div>

                                {{-- Subdominio --}}
                                <div class="p-4 bg-indigo-50 rounded-xl border border-indigo-100 mt-4">
                                    <h4 class="text-indigo-800 font-bold text-sm mb-1">Tu Subdominio actual</h4>
                                    <p class="text-indigo-600 text-sm font-mono">{{ $tenant->subdomain }}.micartadig.com</p>
                                    <p class="text-[10px] text-indigo-400 mt-2 uppercase tracking-wider font-bold">No editable por seguridad del SaaS</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-6 border-t mt-8">
                            <x-primary-button class="px-8">{{ __('Guardar Cambios') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Sección: Código QR --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-700 border-b pb-3 mb-6">📱 Tu Código QR</h3>
                    @php
                        $cartaUrl = 'https://' . $tenant->subdomain . '.' . env('APP_DOMAIN', 'micartadig.com');
                        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&format=png&margin=10&data=' . urlencode($cartaUrl);
                        $qrDownloadUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=600x600&format=png&margin=20&data=' . urlencode($cartaUrl);
                    @endphp
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                        {{-- QR Image --}}
                        <div class="flex-shrink-0 p-4 bg-white rounded-2xl shadow-md border border-gray-100">
                            <img src="{{ $qrUrl }}" alt="QR Code de {{ $tenant->name }}" class="w-48 h-48">
                        </div>

                        {{-- info --}}
                        <div class="flex flex-col justify-center gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Este código QR lleva directamente a tu carta digital:</p>
                                <a href="{{ $cartaUrl }}" target="_blank" class="text-indigo-600 font-mono text-sm hover:underline">{{ $cartaUrl }}</a>
                            </div>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                📌 <strong>Tip:</strong> Imprime este código y colócalo en cada mesa de tu restaurante. Tus clientes podrán escanear y ver tu menú al instante, sin descargar ninguna app.
                            </p>
                            <a href="{{ $qrDownloadUrl }}"
                               download="QR-{{ $tenant->subdomain }}.png"
                               class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-5 rounded-xl transition text-sm w-fit shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Descargar QR (Alta Resolución)
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</x-app-layout>
