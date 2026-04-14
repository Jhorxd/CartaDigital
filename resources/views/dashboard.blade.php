<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                {{ __('Centro de Control SaaS') }}
            </h2>
            <a href="{{ route('tenants.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-indigo-200 transition-all hover:scale-105 active:scale-95 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Negocio
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ 
        search: '',
        tenants: {{ $tenants->map(fn($t) => [
            'id' => $t->id,
            'name' => $t->name,
            'email' => $t->owner->email ?? 'Sin dueño',
            'subdomain' => $t->subdomain,
            'is_active' => (bool)$t->is_active,
            'edit_url' => route('tenants.edit', $t),
            'view_url' => str_replace('://', "://{$t->subdomain}.", config('app.url')),
            'delete_url' => route('tenants.destroy', $t),
        ])->toJson() }},
        get filteredTenants() {
            return this.tenants.filter(t => 
                t.name.toLowerCase().includes(this.search.toLowerCase()) || 
                t.subdomain.toLowerCase().includes(this.search.toLowerCase()) ||
                t.email.toLowerCase().includes(this.search.toLowerCase())
            );
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if (session('status'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-sm animate-bounce">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                    <div class="p-4 bg-indigo-50 text-indigo-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Negocios Totales</p>
                        <p class="text-3xl font-black text-slate-800">{{ $stats['total'] }}</p>
                    </div>
                </div>

                <!-- Activos -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                    <div class="p-4 bg-emerald-50 text-emerald-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Suscripciones Activas</p>
                        <p class="text-3xl font-black text-emerald-600">{{ $stats['active'] }}</p>
                    </div>
                </div>

                <!-- Inactivos -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                    <div class="p-4 bg-rose-50 text-rose-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Inactivos / Pendientes</p>
                        <p class="text-3xl font-black text-rose-600">{{ $stats['inactive'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-slate-100">
                <div class="p-8">
                    
                    <!-- Search & Filters -->
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
                        <div class="relative w-full md:w-96">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                            <input type="text" x-model="search" placeholder="Buscar por nombre, email o subdominio..." 
                                   class="pl-10 w-full rounded-2xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all px-4 py-2.5 text-sm">
                        </div>
                        <div class="text-sm text-slate-500">
                            Mostrando <span class="font-bold text-slate-800" x-text="filteredTenants.length"></span> de {{ $stats['total'] }} negocios
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-50">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Negocio</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Dueño</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">URL / Subdominio</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Estado</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-widest">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-50">
                                <template x-for="tenant in filteredTenants" :key="tenant.id">
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold" x-text="tenant.name.charAt(0)"></div>
                                                <div class="text-sm font-bold text-slate-800" x-text="tenant.name"></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-medium" x-text="tenant.email"></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-slate-400 font-mono italic" x-text="new URL(tenant.view_url).host"></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="tenant.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'" 
                                                  class="px-3 py-1 text-xs font-bold rounded-full uppercase tracking-tighter"
                                                  x-text="tenant.is_active ? 'Activo' : 'Inactivo'">
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold space-x-2">
                                            <a :href="tenant.edit_url" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors">Editar</a>
                                            <a :href="tenant.view_url" target="_blank" class="text-emerald-600 hover:text-emerald-900 bg-emerald-50 px-3 py-1.5 rounded-lg transition-colors">Tienda</a>
                                            <form :action="tenant.delete_url" method="POST" class="inline" @submit.prevent="if(confirm('¿Seguro?')) $el.submit()">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-rose-600 hover:text-rose-900 bg-rose-50 px-3 py-1.5 rounded-lg transition-colors">Baja</button>
                                            </form>
                                        </td>
                                    </tr>
                                </template>
                                
                                <tr x-show="filteredTenants.length === 0">
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                        <svg class="w-16 h-16 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        No se encontraron negocios con esos criterios.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
