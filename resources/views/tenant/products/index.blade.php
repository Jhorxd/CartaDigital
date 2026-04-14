<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Productos') }}
            </h2>
            <a href="{{ route('tenant.admin.products.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                + Nuevo Producto
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('status'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('tenant.admin.products.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <x-input-label for="search" :value="__('Buscar por nombre')" />
                            <x-text-input id="search" name="search" type="text" class="mt-1 block w-full" :value="request('search')" placeholder="Nombre del producto..." />
                        </div>
                        
                        <div class="w-full md:w-64">
                            <x-input-label for="category_id" :value="__('Filtrar por Categoría')" />
                            <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Todas las categorías</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-6 rounded-md transition">
                                Filtrar
                            </button>
                            @if(request()->anyFilled(['search', 'category_id']))
                                <a href="{{ route('tenant.admin.products.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-6 rounded-md transition text-center">
                                    Limpiar
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Vista de Tabla (Escritorio) -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($products as $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($product->image)
                                            <img src="{{ $product->image }}" class="w-12 h-12 object-cover rounded-lg border">
                                        @else
                                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 text-xs text-center p-1">No imagen</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-xs text-gray-500 truncate max-w-xs">{{ $product->description }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $product->categories->pluck('name')->join(', ') ?: 'Sin categoría' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-emerald-600">
                                        S/ {{ number_format($product->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('tenant.admin.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Editar</a>
                                        <form action="{{ route('tenant.admin.products.destroy', $product) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold" onclick="return confirm('¿Eliminar producto?')">Borrar</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay productos.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Vista de Tarjetas (Móvil) -->
                    <div class="md:hidden space-y-4">
                        @forelse($products as $product)
                        <div class="border rounded-xl p-4 bg-gray-50 space-y-4">
                            <div class="flex gap-4">
                                @if($product->image)
                                    <img src="{{ $product->image }}" class="w-20 h-20 object-cover rounded-lg border shadow-sm">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 text-xs">No img</div>
                                @endif
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <h4 class="font-bold text-gray-900">{{ $product->name }}</h4>
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 line-clamp-2 mt-1">{{ $product->description }}</p>
                                    <div class="mt-2 flex justify-between items-center">
                                        <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2 py-1 rounded truncate max-w-[150px]">{{ $product->categories->pluck('name')->join(', ') ?: 'Sin cat.' }}</span>
                                        <span class="font-black text-emerald-600">S/ {{ number_format($product->price, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2 pt-2 border-t">
                                <a href="{{ route('tenant.admin.products.edit', $product) }}" class="flex-1 bg-white border border-indigo-600 text-indigo-600 text-center py-2 rounded-lg font-bold text-sm active:bg-indigo-50 transition">
                                    Editar Producto
                                </a>
                                <form action="{{ route('tenant.admin.products.destroy', $product) }}" method="POST" class="flex-1">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full bg-white border border-red-600 text-red-600 py-2 rounded-lg font-bold text-sm active:bg-red-50 transition" onclick="return confirm('¿Eliminar?')">
                                        Borrar
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <p class="text-center py-10 text-gray-500">No hay productos.</p>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
