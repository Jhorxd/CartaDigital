<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Producto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('tenant.admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Col Izquierda -->
                            <div class="space-y-6">
                                <div>
                                    <x-input-label for="category_id" :value="__('Categoría')" />
                                    <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Seleccione una categoría</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                                </div>

                                <div>
                                    <x-input-label for="name" :value="__('Nombre del Producto')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <div>
                                    <x-input-label for="price" :value="__('Precio (S/)')" />
                                    <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full" :value="old('price')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('price')" />
                                </div>
                            </div>

                            <!-- Col Derecha -->
                            <div class="space-y-6">
                                <div>
                                    <x-input-label for="description" :value="__('Descripción')" />
                                    <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                                </div>

                                <div x-data="{ fileName: '' }">
                                    <x-input-label for="image" :value="__('Foto del Producto')" />
                                    
                                    <div class="flex flex-col sm:flex-row gap-2 mt-2">
                                        <button type="button" onclick="let i = document.getElementById('image'); i.setAttribute('capture', 'environment'); i.click();" class="sm:hidden inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition w-full">
                                            📸 Usar Cámara
                                        </button>
                                        <button type="button" onclick="let i = document.getElementById('image'); i.removeAttribute('capture'); i.click();" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition w-full sm:w-auto">
                                            📁 Galería / Archivo
                                        </button>
                                    </div>
                                    
                                    <input id="image" name="image" type="file" accept="image/*" class="hidden" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''" />
                                    
                                    <p x-cloak x-show="fileName" class="mt-2 text-sm text-green-600 font-semibold" x-text="'Archivo: ' + fileName"></p>
                                    
                                    <x-input-error class="mt-2" :messages="$errors->get('image')" />
                                </div>

                                <div>
                                    <label for="is_active" class="inline-flex items-center">
                                        <input id="is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <span class="ms-2 text-sm text-gray-600">{{ __('Producto Activo y Visible') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-4 border-t">
                            <a href="{{ route('tenant.admin.products.index') }}" class="text-sm text-gray-600 hover:underline">Cancelar</a>
                            <x-primary-button>{{ __('Crear Producto') }}</x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
