<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Categorías') }}
            </h2>
            <a href="{{ route('tenant.admin.categories.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                + Nueva Categoría
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
                    <form action="{{ route('tenant.admin.categories.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="flex-1">
                            <x-input-label for="search" :value="__('Buscar por nombre')" />
                            <x-text-input id="search" name="search" type="text" class="mt-1 block w-full" :value="request('search')" placeholder="Nombre de la categoría..." />
                        </div>
                        
                        <div class="flex gap-2">
                            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-6 rounded-md transition">
                                Filtrar
                            </button>
                            @if(request()->filled('search'))
                                <a href="{{ route('tenant.admin.categories.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-6 rounded-md transition text-center">
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orden</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($categories as $category)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $category->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $category->order_position }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('tenant.admin.categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Editar</a>
                                        <form action="{{ route('tenant.admin.categories.destroy', $category) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold" onclick="return confirm('¿Eliminar categoría?')">Borrar</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">No hay categorías.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Vista de Tarjetas (Móvil) -->
                    <div class="md:hidden space-y-4">
                        @forelse($categories as $category)
                        <div class="border rounded-xl p-4 bg-gray-50 flex flex-col gap-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-bold text-gray-900">{{ $category->name }}</h4>
                                    <p class="text-xs text-gray-500 mt-1">Posición: {{ $category->order_position }}</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('tenant.admin.categories.edit', $category) }}" class="flex-1 bg-white border border-indigo-600 text-indigo-600 text-center py-2 rounded-lg font-bold text-sm">
                                    Editar
                                </a>
                                <form action="{{ route('tenant.admin.categories.destroy', $category) }}" method="POST" class="flex-1">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full bg-white border border-red-600 text-red-600 py-2 rounded-lg font-bold text-sm" onclick="return confirm('¿Eliminar?')">
                                        Borrar
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <p class="text-center py-10 text-gray-500">No hay categorías.</p>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
