<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Producto') }}
        </h2>
    </x-slot>

    <div class="py-12 text-slate-900">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    <form method="POST" action="{{ route('tenant.admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Col Izquierda -->
                            <div class="space-y-6">
                                <div>
                                    <x-input-label :value="__('Categorías relacionadas')" class="mb-2" />
                                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-3 bg-gray-50 p-4 border border-gray-300 rounded-md shadow-sm max-h-48 overflow-y-auto">
                                        @foreach($categories as $category)
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ in_array($category->id, old('categories', $product->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                <span class="ms-2 text-sm text-gray-700 font-medium truncate">{{ $category->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('categories')" />
                                </div>

                                <div>
                                    <x-input-label for="name" :value="__('Nombre del Producto')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $product->name)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                {{-- Campos de Marca desactivados para evitar errores de BD --}}
                                {{-- 
                                @if($tenant->business_type === 'urban')
                                <div>
                                    <x-input-label for="brand" :value="__('Marca (Opcional)')" />
                                    <x-text-input id="brand" name="brand" type="text" class="mt-1 block w-full" :value="old('brand', $product->brand)" placeholder="Ej: Nike, Adidas, Jordan" />
                                    <x-input-error class="mt-2" :messages="$errors->get('brand')" />
                                </div>
                                @endif
                                --}}

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="price" :value="__('Precio Actual (S/)')" />
                                        <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full" :value="old('price', $product->price)" required />
                                        <x-input-error class="mt-2" :messages="$errors->get('price')" />
                                    </div>
                                    {{-- Precio Anterior desactivado --}}
                                    {{-- 
                                    @if($tenant->business_type === 'urban')
                                    <div>
                                        <x-input-label for="old_price" :value="__('Precio Anterior (Opcional)')" />
                                        <x-text-input id="old_price" name="old_price" type="number" step="0.01" class="mt-1 block w-full border-red-200" :value="old('old_price', $product->old_price)" />
                                        <x-input-error class="mt-2" :messages="$errors->get('old_price')" />
                                    </div>
                                    @endif
                                    --}}
                                </div>

                                {{-- Etiqueta / Badge desactivado --}}
                                {{-- 
                                @if($tenant->business_type === 'urban')
                                <div>
                                    <x-input-label for="badge" :value="__('Etiqueta / Badge')" />
                                    <select id="badge" name="badge" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Ninguna</option>
                                        <option value="Nuevo" {{ old('badge', $product->badge) == 'Nuevo' ? 'selected' : '' }}>Nuevo</option>
                                        <option value="Oferta" {{ old('badge', $product->badge) == 'Oferta' ? 'selected' : '' }}>Oferta</option>
                                        <option value="Top Sell" {{ old('badge', $product->badge) == 'Top Sell' ? 'selected' : '' }}>Top Sell</option>
                                        <option value="Limitado" {{ old('badge', $product->badge) == 'Limitado' ? 'selected' : '' }}>Edición Limitada</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('badge')" />
                                </div>
                                @endif
                                --}}

                                <div>
                                    <x-input-label :value="__('Tallas Disponibles (Informativo)')" />
                                    
                                    <div x-data="{ 
                                        selectedSizes: '{{ old('sizes', is_array($product->sizes) ? implode(', ', $product->sizes) : ($product->sizes ?? '')) }}'.split(',').map(s => s.trim()).filter(s => s !== '')
                                    }" class="mt-2">
                                        <div class="grid grid-cols-4 sm:grid-cols-5 gap-3 bg-gray-50 p-4 border border-gray-200 rounded-lg shadow-inner max-h-40 overflow-y-auto">
                                            @foreach(['35','36','37','38','39','40','41','42','43','44','45'] as $size)
                                                <label class="inline-flex items-center p-2 bg-white rounded border border-gray-100 shadow-sm hover:border-indigo-300 cursor-pointer transition-all">
                                                    <input type="checkbox" value="{{ $size }}" x-model="selectedSizes" 
                                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                    <span class="ms-2 text-xs font-bold text-gray-700">{{ $size }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        
                                        <input type="hidden" name="sizes" :value="selectedSizes.join(', ')">
                                        
                                        <div class="mt-2 flex items-center gap-2">
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Seleccionadas:</span>
                                            <template x-if="selectedSizes.length > 0">
                                                <div class="flex flex-wrap gap-1">
                                                    <template x-for="sz in selectedSizes" :key="sz">
                                                        <span class="text-[9px] bg-indigo-600 text-white px-1.5 py-0.5 rounded font-black" x-text="sz"></span>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('sizes')" />
                                </div>
                            </div>

                            <!-- Col Derecha -->
                            <div class="space-y-6">
                                <div>
                                    <x-input-label for="description" :value="__('Descripción')" />
                                    <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $product->description) }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                                </div>

                                {{-- Galería desactivada temporalmente --}}
                                {{-- 
                                @if($tenant->business_type === 'urban')
                                <div>
                                    <x-input-label :value="__('Añadir a Galería (Opcional)')" />
                                    <input type="file" name="gallery[]" multiple accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                    <x-input-error class="mt-2" :messages="$errors->get('gallery')" />
                                </div>
                                @endif
                                --}}

                                <div x-data="{ 
                                    previewUrl: '{{ $product->image }}', 
                                    fileName: '' 
                                }">
                                    <x-input-label for="image" :value="__('Imagen del Producto')" />
                                    <div class="mt-2 mb-4 relative group w-32 h-32">
                                        <template x-if="previewUrl">
                                            <img :src="previewUrl" class="w-32 h-32 object-cover rounded-lg border-2 border-indigo-100 shadow-md">
                                        </template>
                                        <template x-if="!previewUrl">
                                            <div class="w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300 text-gray-400 font-bold text-[8px] uppercase">Sin Foto</div>
                                        </template>
                                    </div>
                                    
                                    <button type="button" @click="$refs.imageInput.click();" class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase hover:bg-gray-50 transition w-full">
                                        Seleccionar Imagen
                                    </button>
                                    
                                    <input id="image" x-ref="imageInput" name="image" type="file" accept="image/*" class="hidden" @change="const file = $event.target.files[0]; if (file) { previewUrl = URL.createObjectURL(file); }" />
                                    <x-input-error class="mt-2" :messages="$errors->get('image')" />
                                </div>

                                <div>
                                    <label for="is_active" class="inline-flex items-center">
                                        <input id="is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                        <span class="ms-2 text-sm text-gray-600">{{ __('Producto Activo y Visible') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-4 border-t">
                            <a href="{{ route('tenant.admin.products.index') }}" class="text-sm text-gray-600 hover:underline">Cancelar</a>
                            <x-primary-button>{{ __('Actualizar Producto') }}</x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
