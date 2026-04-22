<x-app-layout>
    <x-slot name="header">
        @php $currentTenant = request()->get('tenant'); @endphp
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-800 leading-tight tracking-tight">
                    {{ $currentTenant->name }}
                </h2>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
                    @if($currentTenant->business_type === 'gastos')
                        🏦 Centro de Gestión Financiera
                    @else
                        🏪 Panel de Administración
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-3">
                @if($currentTenant->business_type === 'gastos')
                    <form action="{{ route('tenant.admin.dashboard') }}" method="GET" class="flex items-center gap-2">
                        <input type="month" name="month" value="{{ $stats['selected_month'] ?? now()->format('Y-m') }}" 
                               onchange="this.form.submit()"
                               class="border-slate-200 bg-white rounded-xl text-xs font-black uppercase tracking-widest focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2 shadow-sm">
                    </form>
                @endif
                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[10px] font-black uppercase tracking-tighter">
                    {{ now()->format('d M, Y') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 text-slate-900 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @php $currentTenant = request()->get('tenant'); @endphp

            @if($currentTenant->business_type === 'gastos')

                {{-- SECCIÓN 1: BALANCE GENERAL (HERO) --}}
                <div class="relative overflow-hidden bg-slate-900 rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl">
                    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-72 h-72 bg-indigo-500/20 rounded-full blur-3xl"></div>
                    
                    <div class="relative grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-7 space-y-4">
                            <h3 class="text-emerald-400 font-black uppercase tracking-[0.2em] text-xs">Estado de Resultados: {{ date('M Y', strtotime($stats['selected_month'] ?? now())) }}</h3>
                            <div class="flex items-baseline gap-2">
                                <span class="text-5xl md:text-7xl font-black tracking-tighter">S/ {{ number_format($stats['balance_month'], 2) }}</span>
                                <span class="text-xl text-emerald-400/80 font-bold">Balance</span>
                            </div>
                            <p class="text-slate-400 text-sm md:text-base max-w-md font-medium">
                                Tu rendimiento financiero este mes. El balance anual acumulado es de 
                                <span class="text-white font-bold">S/ {{ number_format($stats['balance_year'], 2) }}</span>.
                            </p>
                        </div>
                        
                        <div class="lg:col-span-5 grid grid-cols-2 gap-4">
                            <div class="bg-white/5 backdrop-blur-md rounded-3xl p-6 border border-white/10">
                                <div class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-2">Total Ingresos</div>
                                <div class="text-2xl font-black">+ S/ {{ number_format($stats['incomes_month'], 0) }}</div>
                            </div>
                            <div class="bg-white/5 backdrop-blur-md rounded-3xl p-6 border border-white/10">
                                <div class="text-[10px] font-black text-rose-400 uppercase tracking-widest mb-2">Total Gastos</div>
                                <div class="text-2xl font-black">- S/ {{ number_format($stats['expenses_month'], 0) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN 2: ACCESO DIRECTO Y ÚLTIMOS MOVIMIENTOS --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    {{-- Bloque Ingresos --}}
                    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden group">
                        <div class="p-8">
                            <div class="flex justify-between items-start mb-8">
                                <div class="p-4 bg-emerald-50 text-emerald-600 rounded-2xl group-hover:scale-110 transition-transform duration-500">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3m0-12c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4m0 16v-2m0-12V4m0 16H8m8 0h-4"/></svg>
                                </div>
                                <div class="text-right">
                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Registros</div>
                                    <div class="text-2xl font-black text-slate-800">{{ $stats['incomes_count'] }}</div>
                                </div>
                            </div>

                            <h4 class="text-2xl font-black text-slate-800 mb-2">Ingresos</h4>
                            <p class="text-slate-400 text-sm mb-8 font-medium">Gestiona tus ventas, cobros y entradas de capital de forma masiva.</p>

                            <div class="flex flex-col sm:flex-row gap-3 mb-10">
                                <a href="{{ route('tenant.admin.incomes.quick') }}" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-2xl text-center transition shadow-lg shadow-emerald-200 active:scale-95">
                                    ⚡ Entrada Rápida
                                </a>
                                <a href="{{ route('tenant.admin.incomes.index') }}" class="flex-1 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold py-4 rounded-2xl text-center transition active:scale-95">
                                    Ver Todos
                                </a>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Recientes</span>
                                    <a href="{{ route('tenant.admin.incomes.report') }}" class="text-[10px] font-black text-emerald-600 uppercase tracking-widest hover:underline">Ver Reporte →</a>
                                </div>
                                @forelse($stats['recent_incomes'] as $inc)
                                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-transparent hover:border-emerald-100 hover:bg-emerald-50/30 transition group/item">
                                        <div>
                                            <div class="font-bold text-slate-800 text-sm">{{ $inc->title }}</div>
                                            <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $inc->date->format('d M') }}</div>
                                        </div>
                                        <div class="text-right font-black text-emerald-600">
                                            + S/ {{ number_format($inc->amount, 2) }}
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center py-6 text-slate-400 text-sm font-bold italic">No hay ingresos registrados</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Bloque Gastos --}}
                    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden group">
                        <div class="p-8">
                            <div class="flex justify-between items-start mb-8">
                                <div class="p-4 bg-rose-50 text-rose-600 rounded-2xl group-hover:scale-110 transition-transform duration-500">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="text-right">
                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Registros</div>
                                    <div class="text-2xl font-black text-slate-800">{{ $stats['expenses_count'] }}</div>
                                </div>
                            </div>

                            <h4 class="text-2xl font-black text-slate-800 mb-2">Gastos</h4>
                            <p class="text-slate-400 text-sm mb-8 font-medium">Registra pagos, compras y costos operativos en segundos.</p>

                            <div class="flex flex-col sm:flex-row gap-3 mb-10">
                                <a href="{{ route('tenant.admin.expenses.quick') }}" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white font-bold py-4 rounded-2xl text-center transition shadow-lg shadow-rose-200 active:scale-95">
                                    ⚡ Entrada Rápida
                                </a>
                                <a href="{{ route('tenant.admin.expenses.index') }}" class="flex-1 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold py-4 rounded-2xl text-center transition active:scale-95">
                                    Ver Todos
                                </a>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Recientes</span>
                                    <a href="{{ route('tenant.admin.expenses.report') }}" class="text-[10px] font-black text-rose-600 uppercase tracking-widest hover:underline">Ver Reporte →</a>
                                </div>
                                @forelse($stats['recent_expenses'] as $exp)
                                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-transparent hover:border-rose-100 hover:bg-rose-50/30 transition group/item">
                                        <div>
                                            <div class="font-bold text-slate-800 text-sm">{{ $exp->title }}</div>
                                            <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $exp->date->format('d M') }}</div>
                                        </div>
                                        <div class="text-right font-black text-rose-600">
                                            - S/ {{ number_format($exp->amount, 2) }}
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center py-6 text-slate-400 text-sm font-bold italic">No hay gastos registrados</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>

            @else
                {{-- MANTENER DASHBOARD NORMAL PARA OTROS NEGOCIOS --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-8 border-b-4 border-indigo-500">
                        <div class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Categorías</div>
                        <div class="mt-1 text-4xl font-black text-slate-800">{{ $stats['categories'] }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-8 border-b-4 border-purple-500">
                        <div class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Productos</div>
                        <div class="mt-1 text-4xl font-black text-slate-800">{{ $stats['products'] }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-8 border-b-4 border-emerald-500">
                        <div class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Activos</div>
                        <div class="mt-1 text-4xl font-black text-slate-800">{{ $stats['active_products'] }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] p-8">
                    <h3 class="text-xl font-black mb-6 flex items-center gap-2 text-slate-800">
                        <span class="w-2 h-8 bg-indigo-600 rounded-full"></span> Acciones Principales
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('tenant.admin.products.create') }}" class="flex flex-col gap-2 p-6 bg-indigo-50 text-indigo-700 rounded-3xl hover:bg-indigo-100 transition group">
                            <span class="text-2xl group-hover:scale-125 transition-transform origin-left">📦</span>
                            <span class="font-black text-sm">Nuevo Producto</span>
                        </a>
                        <a href="{{ route('tenant.admin.categories.index') }}" class="flex flex-col gap-2 p-6 bg-purple-50 text-purple-700 rounded-3xl hover:bg-purple-100 transition group">
                            <span class="text-2xl group-hover:scale-125 transition-transform origin-left">📂</span>
                            <span class="font-black text-sm">Categorías</span>
                        </a>
                        <a href="{{ route('carta.index', ['tenant' => $currentTenant->subdomain]) }}" target="_blank" class="flex flex-col gap-2 p-6 bg-emerald-50 text-emerald-700 rounded-3xl hover:bg-emerald-100 transition group">
                            <span class="text-2xl group-hover:scale-125 transition-transform origin-left">🌐</span>
                            <span class="font-black text-sm">Ver Mi Carta</span>
                        </a>
                        <a href="{{ route('tenant.admin.settings.edit') }}" class="flex flex-col gap-2 p-6 bg-slate-100 text-slate-700 rounded-3xl hover:bg-slate-200 transition group">
                            <span class="text-2xl group-hover:scale-125 transition-transform origin-left">⚙️</span>
                            <span class="font-black text-sm">Ajustes</span>
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
