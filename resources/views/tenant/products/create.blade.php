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
                                    <x-input-label :value="__('Categorías relacionadas')" class="mb-2" />
                                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-3 bg-gray-50 p-4 border border-gray-300 rounded-md shadow-sm max-h-48 overflow-y-auto">
                                        @foreach($categories as $category)
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ collect(old('categories', []))->contains($category->id) ? 'checked' : '' }}>
                                                <span class="ms-2 text-sm text-gray-700 font-medium truncate">{{ $category->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('categories')" />
                                </div>

                                <div>
                                    <x-input-label for="name" :value="__('Nombre del Producto')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                @if($tenant->business_type === 'urban')
                                <div>
                                    <x-input-label for="brand" :value="__('Marca (Opcional)')" />
                                    <x-text-input id="brand" name="brand" type="text" class="mt-1 block w-full" :value="old('brand')" placeholder="Ej: Nike, Adidas, Jordan" />
                                    <x-input-error class="mt-2" :messages="$errors->get('brand')" />
                                </div>
                                @endif

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="price" :value="__('Precio Actual (S/)')" />
                                        <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full" :value="old('price')" required />
                                        <x-input-error class="mt-2" :messages="$errors->get('price')" />
                                    </div>
                                    @if($tenant->business_type === 'urban')
                                    <div>
                                        <x-input-label for="old_price" :value="__('Precio Anterior (Opcional)')" />
                                        <x-text-input id="old_price" name="old_price" type="number" step="0.01" class="mt-1 block w-full border-red-200" :value="old('old_price')" />
                                        <x-input-error class="mt-2" :messages="$errors->get('old_price')" />
                                    </div>
                                    @endif
                                </div>

                                @if($tenant->business_type === 'urban')
                                <div>
                                    <x-input-label for="badge" :value="__('Etiqueta / Badge')" />
                                    <select id="badge" name="badge" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Ninguna</option>
                                        <option value="Nuevo" {{ old('badge') == 'Nuevo' ? 'selected' : '' }}>Nuevo</option>
                                        <option value="Oferta" {{ old('badge') == 'Oferta' ? 'selected' : '' }}>Oferta</option>
                                        <option value="Top Sell" {{ old('badge') == 'Top Sell' ? 'selected' : '' }}>Top Sell</option>
                                        <option value="Limitado" {{ old('badge') == 'Limitado' ? 'selected' : '' }}>Edición Limitada</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('badge')" />
                                </div>

                                <div>
                                    <x-input-label for="sizes" :value="__('Tallas Disponibles')" />
                                    <x-text-input id="sizes" name="sizes" type="text" class="mt-1 block w-full" :value="old('sizes')" placeholder="Ej: 38, 39, 40, 42" />
                                    <p class="mt-1 text-xs text-gray-500 italic">Separa las tallas por comas.</p>
                                    <x-input-error class="mt-2" :messages="$errors->get('sizes')" />
                                </div>
                                @endif
                            </div>

                            <!-- Col Derecha -->
                            <div class="space-y-6">
                                <div>
                                    <x-input-label for="description" :value="__('Descripción')" />
                                    <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                                </div>

                                @if($tenant->business_type === 'urban')
                                <div>
                                    <x-input-label :value="__('Galería de Imágenes (Opcional)')" />
                                    <input type="file" name="gallery[]" multiple accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                    <p class="mt-1 text-[10px] text-gray-400">Puedes seleccionar varias fotos a la vez.</p>
                                    <x-input-error class="mt-2" :messages="$errors->get('gallery')" />
                                </div>
                                @endif

                                <div x-data="{ 
                                    previewUrl: '', 
                                    fileName: '' 
                                }">
                                    <x-input-label for="image" :value="__('Foto del Producto')" />
                                    
                                    <!-- Vista Previa -->
                                    <div class="mt-2 mb-4 relative group w-32 h-32" x-cloak x-show="previewUrl">
                                        <img :src="previewUrl" class="w-32 h-32 object-cover rounded-lg border-2 border-indigo-100 shadow-md group-hover:opacity-75 transition-all">
                                    </div>
                                    <div class="mt-2 mb-4 relative group w-32 h-32" x-show="!previewUrl">
                                        <div class="w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col sm:flex-row gap-2 mt-2">
                                        <button type="button" @click="$refs.imageInput.setAttribute('capture', 'environment'); $refs.imageInput.click();" class="sm:hidden inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition w-full">
                                            📸 Usar Cámara
                                        </button>
                                        <button type="button" @click="$refs.imageInput.removeAttribute('capture'); $refs.imageInput.click();" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition w-full sm:w-auto">
                                            📁 Galería / Archivo
                                        </button>
                                    </div>
                                    
                                    <input id="image" x-ref="imageInput" name="image" type="file" accept="image/*" class="hidden" 
                                           @change="
                                                const file = $event.target.files[0];
                                                if (file) {
                                                    fileName = file.name;
                                                    previewUrl = URL.createObjectURL(file);
                                                }
                                           " />
                                    
                                    <p x-cloak x-show="fileName" class="mt-2 text-sm text-green-600 font-bold flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        <span x-text="'Listo: ' + fileName"></span>
                                    </p>
                                    
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
