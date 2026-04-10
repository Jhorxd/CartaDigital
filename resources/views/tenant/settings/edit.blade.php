<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuración del Local') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('status'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 font-sans">
                    
                    <form method="POST" action="{{ route('tenant.admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            <!-- Información básica -->
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

                            <!-- Identidad Visual -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-bold text-gray-700 border-b pb-2">Identidad Visual</h3>

                                <div>
                                    <x-input-label for="logo" :value="__('Logo del Restaurante')" />
                                    <div class="mt-2 flex items-center gap-4">
                                        @if($tenant->logo)
                                            <img src="{{ $tenant->logo }}" class="w-20 h-20 rounded-full border shadow-sm">
                                        @else
                                            <div class="w-20 h-20 bg-gray-100 rounded-full border border-dashed flex items-center justify-center text-gray-400 text-xs text-center">Sin logo</div>
                                        @endif
                                        <input id="logo" name="logo" type="file" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('logo')" />
                                </div>

                                <div class="p-4 bg-indigo-50 rounded-xl border border-indigo-100 mt-4">
                                    <h4 class="text-indigo-800 font-bold text-sm mb-1">Tu Subdominio actual</h4>
                                    <p class="text-indigo-600 text-sm font-mono">{{ $tenant->subdomain }}.localhost</p>
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
        </div>
    </div>
</x-app-layout>
