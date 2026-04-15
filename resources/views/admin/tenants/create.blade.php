<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Negocio (Tenant)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('tenants.store') }}" class="space-y-8">
                        @csrf
                        
                        <!-- Sección Negocio -->
                        <div class="border-b pb-4">
                            <h3 class="text-lg font-bold text-gray-700 mb-4">🏠 Información del Local / Negocio</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="name" :value="__('Nombre del Negocio')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <div>
                                    <x-input-label for="subdomain" :value="__('Subdominio (ej: polleria)')" />
                                    <div class="flex items-center mt-1">
                                        <x-text-input id="subdomain" name="subdomain" type="text" class="block w-full rounded-r-none border-r-0" :value="old('subdomain')" required />
                                        <span class="inline-flex items-center px-4 py-2 border border-gray-300 bg-gray-50 text-gray-500 text-sm rounded-r-md">
                                            .{{ config('app.domain') }}
                                        </span>
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('subdomain')" />
                                </div>
                            </div>
                            <div class="mt-6">
                                <x-input-label for="business_type" :value="__('Tipo de Negocio')" />
                                <select id="business_type" name="business_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="restaurant" {{ old('business_type') == 'restaurant' ? 'selected' : '' }}>Restaurante / Cafetería / Comida</option>
                                    <option value="boutique" {{ old('business_type') == 'boutique' ? 'selected' : '' }}>Boutique / Perfumería / Ropa</option>
                                    <option value="urban" {{ old('business_type') == 'urban' ? 'selected' : '' }}>Urbano / Zapatillas</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('business_type')" />
                            </div>
                        </div>

                        <!-- Sección Credenciales -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-700 mb-4">🔑 Credenciales de Acceso</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="email" :value="__('Email del Propietario')" />
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                </div>

                                <div>
                                    <x-input-label for="password" :value="__('Contraseña Inicial')" />
                                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('password')" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-6 border-t">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150">
                                Cancelar
                            </a>
                            <x-primary-button>
                                {{ __('Guardar Negocio y Dueño') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
