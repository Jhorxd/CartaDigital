<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-800 tracking-tight flex items-center gap-2">
                    <span class="p-2 bg-emerald-100 text-emerald-600 rounded-xl">💰</span> Control de Ingresos
                </h2>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Monitoreo de entradas de capital</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('tenant.admin.incomes.quick') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 px-6 rounded-2xl transition shadow-lg shadow-indigo-100 text-sm active:scale-95">
                    ⚡ Entrada Rápida
                </a>
                <a href="{{ route('tenant.admin.incomes.create') }}" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-black py-3 px-6 rounded-2xl transition text-sm active:scale-95">
                    + Nuevo Ingreso
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 p-4 rounded-2xl font-bold text-sm shadow-sm flex items-center gap-3">
                    <span class="text-lg">✅</span> {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                {{-- Total --}}
                <div class="lg:col-span-4 bg-slate-900 rounded-[2rem] p-6 text-white shadow-xl flex items-center justify-between">
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-[0.2em] opacity-50">Total Ingresos</div>
                        <div class="text-3xl font-black mt-1">S/ {{ number_format($totalFiltered, 2) }}</div>
                    </div>
                    <div class="p-3 bg-white/10 rounded-2xl text-2xl">💰</div>
                </div>

                {{-- Filtros --}}
                <div class="lg:col-span-8 bg-white shadow-sm border border-slate-100 rounded-[2rem] p-4">
                    <form action="{{ route('tenant.admin.incomes.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="flex-1 w-full">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Buscar fuente</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ej: Venta, Cliente..." class="w-full border-slate-100 bg-slate-50/50 rounded-xl shadow-sm text-sm focus:ring-emerald-500 focus:border-emerald-500 px-4 py-2.5">
                        </div>
                        <div class="w-full md:w-auto">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Categoría</label>
                            <select name="category" class="w-full border-slate-100 bg-slate-50/50 rounded-xl shadow-sm text-sm focus:ring-emerald-500 focus:border-emerald-500 px-4 py-2.5">
                                <option value="">Todas</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full md:w-auto">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Mes</label>
                            <input type="month" name="month" value="{{ request('month') }}" class="w-full border-slate-100 bg-slate-50/50 rounded-xl shadow-sm text-sm focus:ring-emerald-500 focus:border-emerald-500 px-4 py-2.5">
                        </div>
                        <div class="flex gap-2 w-full md:w-auto">
                            <button type="submit" class="flex-1 md:flex-none bg-slate-800 hover:bg-slate-900 text-white font-black py-2.5 px-6 rounded-xl text-sm transition">Filtrar</button>
                            @if(request()->hasAny(['search','category','month']))
                                <a href="{{ route('tenant.admin.incomes.index') }}" class="flex-1 md:flex-none bg-slate-100 hover:bg-slate-200 text-slate-600 font-black py-2.5 px-6 rounded-xl text-sm transition text-center">Limpiar</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabla Desktop --}}
            <div class="bg-white shadow-sm border border-slate-100 rounded-[2.5rem] hidden md:block overflow-hidden">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Fecha</th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Concepto</th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Categoría</th>
                            <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Monto</th>
                            <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-50">
                        @forelse($incomes as $income)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-8 py-6 whitespace-nowrap text-xs font-bold text-slate-400">
                                {{ $income->date->format('d/m/Y') }}
                            </td>
                            <td class="px-8 py-6">
                                <div class="font-black text-slate-800 text-sm group-hover:text-emerald-600 transition-colors">{{ $income->title }}</div>
                                @if($income->description)
                                    <div class="text-[10px] text-slate-400 font-bold mt-1 uppercase tracking-tighter">{{ $income->description }}</div>
                                @endif
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                @if($income->category)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-600 uppercase tracking-widest">
                                        {{ $income->category }}
                                    </span>
                                @else
                                    <span class="text-slate-300 text-[10px] font-black tracking-widest uppercase">Sin Cat.</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right whitespace-nowrap font-black text-emerald-600 text-sm">
                                S/ {{ number_format($income->amount, 2) }}
                            </td>
                            <td class="px-8 py-6 text-right whitespace-nowrap space-x-3">
                                <a href="{{ route('tenant.admin.incomes.edit', $income) }}" class="text-indigo-600 hover:text-indigo-900 text-xs font-black uppercase tracking-widest">Editar</a>
                                <form action="{{ route('tenant.admin.incomes.destroy', $income) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-rose-400 hover:text-rose-600 text-xs font-black uppercase tracking-widest" onclick="return confirm('¿Eliminar este ingreso?')">Borrar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="text-5xl mb-4">💰</div>
                                <div class="text-slate-400 font-black uppercase tracking-[0.2em] text-xs">No se encontraron ingresos</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Tarjetas Móvil --}}
            <div class="md:hidden space-y-4">
                @forelse($incomes as $income)
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 space-y-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-black text-slate-800 text-base">{{ $income->title }}</div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[10px] font-black text-slate-400 uppercase">{{ $income->date->format('d M, Y') }}</span>
                                @if($income->category)
                                    <span class="text-[9px] font-black bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full uppercase">{{ $income->category }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right font-black text-emerald-600 text-lg">
                            S/ {{ number_format($income->amount, 2) }}
                        </div>
                    </div>
                    
                    @if($income->description)
                        <p class="text-xs text-slate-400 font-medium bg-slate-50 p-3 rounded-xl border border-slate-100 italic">"{{ $income->description }}"</p>
                    @endif

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <a href="{{ route('tenant.admin.incomes.edit', $income) }}" class="text-center py-3 bg-slate-50 text-slate-700 rounded-2xl text-xs font-black uppercase tracking-widest">Editar</a>
                        <form action="{{ route('tenant.admin.incomes.destroy', $income) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full py-3 bg-rose-50 text-rose-600 rounded-2xl text-xs font-black uppercase tracking-widest" onclick="return confirm('¿Eliminar?')">Borrar</button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-slate-200">
                    <p class="text-slate-400 font-black uppercase tracking-widest text-xs">No hay ingresos registrados</p>
                </div>
                @endforelse
            </div>

            @if($incomes->hasPages())
                <div class="mt-8">{{ $incomes->links() }}</div>
            @endif

        </div>
    </div>
</x-app-layout>
