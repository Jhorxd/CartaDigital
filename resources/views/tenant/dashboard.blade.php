<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel del Restaurante') }} - {{ request()->get('tenant')->name }}
        </h2>
    </x-slot>

    <div class="py-12 text-slate-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-sm font-medium text-gray-500 uppercase">Categorías</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['categories'] }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="text-sm font-medium text-gray-500 uppercase">Productos Totales</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['products'] }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-emerald-500">
                    <div class="text-sm font-medium text-gray-500 uppercase">Productos Activos</div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['active_products'] }}</div>
                </div>

            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">Acciones Rápidas</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('tenant.admin.products.create') }}" class="flex items-center justify-center gap-2 p-4 bg-indigo-50 text-indigo-700 rounded-xl hover:bg-indigo-100 transition font-bold">
                            📦 Agregar Producto
                        </a>
                        <a href="{{ route('tenant.admin.categories.index') }}" class="flex items-center justify-center gap-2 p-4 bg-purple-50 text-purple-700 rounded-xl hover:bg-purple-100 transition font-bold">
                            📂 Ver Categorías
                        </a>
                        <a href="{{ route('carta.index', ['tenant' => request()->get('tenant')->subdomain]) }}" target="_blank" class="flex items-center justify-center gap-2 p-4 bg-emerald-50 text-emerald-700 rounded-xl hover:bg-emerald-100 transition font-bold">
                            🌐 Ver Mi Carta Digital
                        </a>
                        <a href="{{ route('tenant.admin.settings.edit') }}" class="flex items-center justify-center gap-2 p-4 bg-slate-50 text-slate-700 rounded-xl hover:bg-slate-100 transition font-bold">
                            ⚙️ Configuración
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
